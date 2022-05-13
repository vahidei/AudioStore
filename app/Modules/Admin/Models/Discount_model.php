<?php

namespace App\Modules\Admin\Models;


class Discount_model extends Main_model
{
    public function ajaxItems()
    {
        $db = db_connect();
        $table = $db->table('packages');
        return $table->select(['id', 'title'])->get()->getResult('array');
    }

    public function list($admin_id)
    {
        $db = db_connect();
        $table = $db->table('discounts');
        return $table->where('admin_id', $admin_id)->select()->get()->getResult('array');
    }

    public function delete_rows($items, $admin_id)
    {
        $db = db_connect();
        $db->transStart();
        $table = $db->table('discounts');
        $table->whereIn('id', $items)->where('admin_id', $admin_id)->delete();

        $table = $db->table('discount_items');
        $table->whereIn('discount_id', $items)->delete();

        $table = $db->table('media');
        $files = $table->select('coded_name')
            ->where('type', 'discount_photo')
            ->whereIn('parent_id', $items)
            ->get()->getResultArray();
        $data = [];
        foreach($files as $item){
            $data[] = [
                'file_address'=>'public/demos/discounts/photos/'.$item['coded_name']
            ];
        }
        $this->addToTrash($data);

        $db->transComplete();
        if (!$db->transStatus()) {
            $db->transRollback();
        }

        return $db->transStatus();
    }

    public function selected_items($discount_id, $type)
    {
        $db = db_connect();
        $table = $db->table('discount_items');
        $ditems = $table->select()->where('discount_id', $discount_id)->get()->getResult('array');

        $ids = array_column($ditems, 'item_id');

        if ($type == 'track') {
            $table = $db->table('tracks');
        } elseif ($type == 'package') {
            $table = $db->table('packages');
        }

        $items = $table->whereIn('id', $ids)->select('id, title')->get()->getResult('array');

        return $items;
    }

    public function items_detail($type, $ids)
    {
        $db = db_connect();
        $tb = ($type == 'track') ? 'tracks' : 'packages';
        $table = $db->table($tb);
        $items = $table->whereIn('id', $ids)->select('id, title')->get()->getResult('array');
        return $items;
    }

    public function get($id, $admin_id)
    {
        $db = db_connect();
        $table = $db->table('discounts');
        $item = $table->select()->where(['id'=> $id, 'admin_id'=>$admin_id])->get()->getRow(0, 'array');

        return $item;
    }

    private function get_photo($parent_id){
        $db = db_connect();
        $table = $db->table('media');
        $table->where('parent_id', $parent_id);
        $table->where('type', 'discount_photo');
        $item = $table->select()->get()->getRow(0,'array');

        return $item;
    }

    public function add($item, $photo, $status, $admin_id)
    {
        $expire_date = DATE('Y-m-d H:i:s', time() + ($item['expire'] * 3600 * 24));
        $db = db_connect();
        $db->transStart();

        $discounts = $db->table('discounts');

        $discounts->set('last_update', 'NOW()', false);
        $discounts->set('created_at', 'NOW()', false);
        $discounts->insert(['title' => $item['title'],
            'title_fa' => $item['title_fa'],
            'type' => $item['type'],
            'discount_type' => $item['discount_type'],
            'discount' => $item['discount'],
            'expire' => $item['expire'],
            'expire_date' => $expire_date,
            'status' => $status,
            'admin_id'=>$admin_id
        ]);


        $last_id = $db->insertID();

        if (!empty($photo)) {
            $media = $db->table('media');
            $media->set('created_at', 'NOW()', false);
            $media->insert(['coded_name' => $photo['code'],
                'type' => 'discount_photo',
                'parent_id' => $last_id,
                'file_name' => $photo['file_name'],
                'file_duration' => $photo['duration'],
                'file_size' => $photo['size'],
                'is_original' => '0'
            ]);
        }

        $discount_items = $db->table('discount_items');
        $data = [];
        $i = 0;
        foreach ($item['items'] as $key => $value) {
            $data[$i]['type'] = $item['type'];
            $data[$i]['discount_id'] = $last_id;
            $data[$i]['item_id'] = str_ireplace('t', '', $value);
            $i++;
        }
        $discount_items->insertBatch($data);

        if (!$db->transStatus()) {
            $db->transRollback();
        }
        $db->transComplete();
        return $db->transStatus();

    }

    public function edit($id, $item, $photo, $status, $admin_id)
    {
        $expire_date = DATE('Y-m-d H:i:s', time() + ($item['expire'] * 3600 * 24));
        $db = db_connect();
        $db->transStart();

        $discounts = $db->table('discounts');

        $discounts->set('last_update', 'NOW()', false);

        $discounts->where(['id'=> $id, 'admin_id'=>$admin_id])->update(['title' => $item['title'],
            'title_fa' => $item['title_fa'],
            'type' => $item['type'],
            'discount_type' => $item['discount_type'],
            'discount' => $item['discount'],
            'expire' => $item['expire'],
            'expire_date' => $expire_date,
            'status' => $status
        ]);

        $file = $this->get_photo($id);

        if (!empty($photo)) {

            if(!empty($file)){
                $this->addToTrash('demos/discounts/photos/'.$file['coded_name']);
            }

            $media = $db->table('media');
            $media->where(['parent_id'=> $id, 'type'=> 'discount_photo'])->update([
                'coded_name' => $photo['code'],
                'file_name' => $photo['file_name'],
                'file_duration' => $photo['duration'],
                'file_size' => $photo['size']
            ]);
        }

        $discount_items = $db->table('discount_items');
        $discount_items->where('discount_id', $id)->delete();
        $data = [];
        $i = 0;
        foreach ($item['items'] as $key => $value) {
            $data[$i]['type'] = $item['type'];
            $data[$i]['discount_id'] = $id;
            $data[$i]['item_id'] = str_ireplace('t', '', $value);
            $i++;
        }
        $discount_items->insertBatch($data);

        if (!$db->transStatus()) {
            $db->transRollback();
        }
        $db->transComplete();
        return $db->transStatus();

    }

}