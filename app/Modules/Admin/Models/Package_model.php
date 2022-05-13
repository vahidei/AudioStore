<?php

namespace App\Modules\Admin\Models;


class Package_model extends Main_model
{
    public function ajaxItems($value, $discount=false, $admin_id)
    {
        $db = db_connect();
        if($discount){
            $table = $db->table('packages p');
            $items = $table->select('p.id, p.title, count(di.type) as discounted')
                ->join('discount_items di', 'di.item_id = p.id AND di.type = "package"', 'left')
                ->where(['p.status'=> 'publish', 'p.admin_id'=>$admin_id])->like('p.title', $value)->groupBy('p.id')->get()->getResult('array');
        }else{
            $table = $db->table('packages');
            $items = $table->where(['status'=> 'publish', 'admin_id'=>$admin_id])->select(['id', 'title'])->like('title', $value)->get()->getResult('array');
        }
        return $items;
    }

    public function selected_tracks($package_id){
        $db = db_connect();
        $table = $db->table('packages_tracks p');
        $items = $table->select('t.id as id, t.title as title', true)
            ->join('tracks t', 't.id = p.track_id', 'inner')
            ->where('p.package_id', $package_id)
            ->get()->getResultArray();
        return $items;
    }

    public function get($id, $admin_id){
        $db = db_connect();
        $table = $db->table('packages');
        $item = $table->select()->where(['id'=> $id, 'admin_id'=>$admin_id])->get()->getRow(0,'array');

        return $item;
    }

    public function list($admin_id)
    {
        $db = db_connect();
        $table = $db->table('packages');
        return $table->where('admin_id', $admin_id)->select()->get()->getResult('array');
    }

    public function delete_rows($items, $admin_id){
        $db = db_connect();
        $db->transStart();
        $table = $db->table('packages');
        $table->whereIn('id', $items)->where('admin_id', $admin_id)->delete();

        $table = $db->table('packages_tracks');
        $table->whereIn('package_id', $items)->delete();

        $table = $db->table('discount_items');
        $table->where(['type'=>'package'])->whereIn('item_id', $items)->delete();

        $table = $db->table('media');
        $items = $table->select('coded_name')->whereIn('parent_id', $items)
                ->where('type','package_cover')->get()->getResultArray();

        $data = [];
        foreach($items as $item){
            $data[] = [
                'file_address'=>'public/demos/packages/covers/'.$item['coded_name']
            ];
        }
        $table = $db->table('cron_files_delete');
        $table->insertBatch($data);

        $db->transComplete();
        if(!$db->transStatus()){
            $db->transRollback();
        }
        return $db->transStatus();
    }

    public function add($item, $cover, $status, $admin_id)
    {

        $db = db_connect();
        $db->transStart();

        $packages = $db->table('packages');

        $packages->set('last_update', 'NOW()', false);
        $packages->set('created_at', 'NOW()', false);
        $packages->insert(['title' => $item['title'],
            'title_fa' => $item['title_fa'],
            'short_desc' => $item['short_desc'],
            'short_desc_fa' => $item['short_desc_fa'],
            'more_desc' => $item['more_desc'],
            'more_desc_fa' => $item['more_desc_fa'],
            'price' => $item['price'],
            'buy_limit' => $item['buy_limit'],
            'most_used_colors' => $item['most_used_colors'],
            'status' => $status,
            'admin_id'=>$admin_id
        ]);


        $last_id = $db->insertID();

        $this->set_admin_product($last_id, 'package', $db);

        $media = $db->table('media');
        $media->set('created_at', 'NOW()', false);
        $media->insert(['coded_name' => $cover['code'],
            'type' => 'package_cover',
            'parent_id' => $last_id,
            'file_name' => $cover['file_name'],
            'file_duration' => $cover['duration'],
            'file_size' => $cover['size'],
            'is_original' => '0'
        ]);

        $packages_tracks = $db->table('packages_tracks');
        $data = [];
        $i = 0;
        foreach ($item['tracks'] as $key => $value) {
            $data[$i]['package_id'] = $last_id;
            $data[$i]['track_id'] = $value;
            $i++;
        }
        $packages_tracks->insertBatch($data);

        if (!$db->transStatus()) {
            $db->transRollback();
        }
        $db->transComplete();
        return $db->transStatus();

    }

    private function get_package_cover($parent_id){
        $db = db_connect();
        $table = $db->table('media');
        $table->where('parent_id', $parent_id);
        $table->where('type', 'package_cover');
        $item = $table->select()->get()->getRow(0,'array');
        return $item;
    }

    public function edit($id, $item, $cover, $status, $admin_id)
    {

        $db = db_connect();
        $db->transStart();

        $packages = $db->table('packages');

        $packages->set('last_update', 'NOW()', false);
        $packages->set('created_at', 'NOW()', false);


        $data = ['title' => $item['title'],
            'title_fa' => $item['title_fa'],
            'short_desc' => $item['short_desc'],
            'short_desc_fa' => $item['short_desc_fa'],
            'more_desc' => $item['more_desc'],
            'more_desc_fa' => $item['more_desc_fa'],
            'price' => $item['price'],
            'buy_limit' => $item['buy_limit'],
            'status' => $status
        ];
        if(!empty($item['most_used_colors'])){
            $data['most_used_colors'] = $item['most_used_colors'];
        }
        $packages->where(['id'=> $id, 'admin_id'=>$admin_id])->update($data);

        $media = $db->table('media');

        $file = $this->get_package_cover($id);
        if(!empty($cover)){
            if(!empty($file)){
                $this->addToTrash('public/demos/packages/covers/'.$file['coded_name']);
            }

            $media->where(['parent_id'=>$id, 'type'=>'package_cover', 'is_original'=>'0'])->update([
                'coded_name' => $cover['code'],
                'file_name' => $cover['file_name'],
                'file_duration' => $cover['duration'],
                'file_size' => $cover['size']
            ]);
        }

        $packages_tracks = $db->table('packages_tracks');
        $packages_tracks->where('package_id', $id)->delete();
        $data = [];
        $i = 0;
        foreach ($item['tracks'] as $key => $value) {
            $data[$i]['package_id'] = $id;
            $data[$i]['track_id'] = $value;
            $i++;
        }
        $packages_tracks->insertBatch($data);

        if (!$db->transStatus()) {
            $db->transRollback();
        }
        $db->transComplete();
        return $db->transStatus();

    }

}