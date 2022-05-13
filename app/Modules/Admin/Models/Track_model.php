<?php

namespace App\Modules\Admin\Models;


class Track_model extends Main_model
{
    public function ajaxItems($value, $discount=false, $admin_id)
    {
        $db = db_connect();
        if($discount){
            $table = $db->table('tracks t');
            $items = $table->select('t.id, t.title, count(di.type) as discounted')
                ->join('discount_items di', 'di.item_id = t.id AND di.type = "track"', 'left')
                ->whereNotIn('t.status', ['draft'])->like('t.title', $value)->groupBy('t.id')->get()->getResult('array');
        }else{
            $table = $db->table('tracks');
            $items=  $table->where('admin_id', $admin_id)->select(['id', 'title'])->whereNotIn('status', ['draft'])->like('title', $value)->get()->getResult('array');
        }

        return $items;
    }

    public function list($admin_id)
    {
        $db = db_connect();
        $table = $db->table('tracks');
        return $table->select()
            ->where([
                'admin_id'=>$admin_id
            ])
            ->get()->getResult('array');
    }

    public function get_packaged_tracks($ids){
        $db = db_connect();
        $table = $db->table('packages_tracks');
        $items = $table->select('track_id as id')->whereIn('track_id', $ids)->get()->getResultArray();
        $ids = array_column($items, 'id');
        return $ids;
    }

    public function delete_rows($items, $admin_id){
        $db = db_connect();
        $db->transStart();
        $table = $db->table('tracks');
        $table->whereIn('id', $items)->where('admin_id', $admin_id)->delete();

        $table = $db->table('packages_tracks');
        $table->whereIn('track_id', $items)->delete();

        $table = $db->table('discount_items');
        $table->where(['type'=>'track'])->whereIn('item_id', $items)->delete();

        $table = $db->table('items_scheduling');
        $table->where(['type'=>'track'])->whereIn('item_id', $items)->delete();

        $table = $db->table('media');
        $items = $table->select('coded_name')->whereIn('parent_id', $items)
            ->where('type','track_file')->get()->getResultArray();

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

    public function add($item, $original_file, $demo_file, $admin_id)
    {

        $db = db_connect();
        $db->transStart();

        if($item['status'] == 'scheduling'){
            if($item['action_type'] == 'publish'){
                $status = 'draft';
            }else{
                $status = 'publish';
            }
        }else{
            $status = $item['status'];
        }

        $tracks = $db->table('tracks');

        $tracks->set('last_update', 'NOW()', false);
        $tracks->set('created_at', 'NOW()', false);
        $tracks->insert(['title' => $item['title'],
            'title_fa' => $item['title_fa'],
            'price' => $item['price'],
            'buy_limit' => $item['buy_limit'],
            'category_id' => $item['category'],
            'status' => $status,
            'admin_id'=>$admin_id
        ]);

        $last_id = $db->insertID();

        $media = $db->table('media');
        $media->set('created_at', 'NOW()', false);
        $media->insert(['coded_name' => $original_file['code'],
            'type' => 'track_file',
            'parent_id' => $last_id,
            'file_name' => $original_file['file_name'],
            'file_duration' => $original_file['duration'],
            'file_size' => $original_file['size'],
            'is_original' => '1'
        ]);

        $media->set('created_at', 'NOW()', false);
        $media->insert(['coded_name' => $demo_file['code'],
            'type' => 'track_file',
            'parent_id' => $last_id,
            'file_name' => $demo_file['file_name'],
            'file_duration' => $demo_file['duration'],
            'file_size' => $demo_file['size'],
            'is_original' => '0'
        ]);

        if($item['status'] == 'scheduling') {
            $table = $db->table('items_scheduling');
            $table->set('created_at', 'NOW()', false);
            $table->insert([
                'item_id'=>$last_id,
                'type'=>'track',
                'action_type'=>$item['action_type'],
                'action_time'=>$item['action_time']
            ]);
        }

        if (!$db->transStatus()) {
            $db->transRollback();
        }
        $db->transComplete();
        return $db->transStatus();

    }

    public function get($id, $admin_id){
        $db = db_connect();
        $table = $db->table('tracks t');
        $item = $table->select('t.*, count(DISTINCT is.id) as scheduled, is.action_type, is.action_time')
            ->join('items_scheduling is', 'is.item_id = t.id AND is.type="track"', 'left')
            ->where(['t.id'=> $id, 't.admin_id'=>$admin_id])->get()->getRow(0,'array');

        return $item;
    }

    private function get_track_media($parent_id){
        $db = db_connect();
        $table = $db->table('media');
        $items = $table->select()->where('parent_id', $parent_id)->get()->getResult('array');
        $data = ['original_file'=>false, 'demo_file'=>false];
        if(count($items) > 1){
            if($items[0]['is_original'] == '1'){
                $data['original_file'] = $items[0];
                $data['demo_file'] = $items[1];
            }else{
                $data['demo_file'] = $items[0];
                $data['original_file'] = $items[1];
            }
        }else{
            if(count($items) > 0){
                if($items[0]['is_original'] == '1'){
                    $data['original_file'] = $items[0];
                    $data['demo_file'] = false;
                }else{
                    $data['demo_file'] = $items[0];
                    $data['original_file'] = false;
                }
            }
        }
        return $data;
    }

    public function edit($id, $item, $original_file, $demo_file, $admin_id){
        $db = db_connect();
        $db->transStart();

        if($item['status'] == 'scheduling'){
            if($item['action_type'] == 'publish'){
                $status = 'draft';
            }else{
                $status = 'publish';
            }
        }else{
            $status = $item['status'];
        }

        $table = $db->table('tracks');

        $table->set('last_update', 'NOW()', false);
        $table->where(['id'=> $id, 'admin_id'=>$admin_id])->update([
            'title'=>$item['title'],
            'title_fa'=>$item['title_fa'],
            'price' => $item['price'],
            'buy_limit' => $item['buy_limit'],
            'category_id' => $item['category'],
            'status' => $status
        ]);

        $media = $db->table('media');

        $files = $this->get_track_media($id);

        if(!empty($original_file)){
            if($files['original_file'] !== false){
                $this->addToTrash('originals/tracks/'.$files['original_file']['coded_name']);
            }
            $media->where(['parent_id'=>$id, 'type'=>'track_file', 'is_original'=>'1'])->update([
                'coded_name' => $original_file['code'],
                'file_name' => $original_file['file_name'],
                'file_duration' => $original_file['duration'],
                'file_size' => $original_file['size']
            ]);
        }

        if(!empty($demo_file)){
            if($files['demo_file'] !== false){
                $this->addToTrash('public/demos/tracks/'.$files['demo_file']['coded_name']);
            }

            $media->where(['parent_id'=>$id, 'type'=>'track_file', 'is_original'=>'0'])->update([
                'coded_name' => $demo_file['code'],
                'file_name' => $demo_file['file_name'],
                'file_duration' => $demo_file['duration'],
                'file_size' => $demo_file['size']
            ]);

        }

        $table = $db->table('items_scheduling');
        $table->where(['item_id'=>$id, 'type'=>'track'])->delete();

        if($item['status'] == 'scheduling') {
            $table->set('created_at', 'NOW()', false);
            $table->insert([
                'item_id'=>$id,
                'type'=>'track',
                'action_type'=>$item['action_type'],
                'action_time'=>$item['action_time']
            ]);
        }

        if (!$db->transStatus()) {
            $db->transRollback();
        }
        $db->transComplete();
        return $db->transStatus();
    }

}