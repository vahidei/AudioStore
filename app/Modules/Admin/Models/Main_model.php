<?php

namespace App\Modules\Admin\Models;

class Main_model
{
    function login($data){
        $db = db_connect();
        $table = $db->table('admins');
        $item = $table->select()->where(['username'=>$data['username'], 'password'=>$data['password']])
                ->orWhere(['email'=> $data['username'], 'password'=>$data['password']])->get()->getRowArray('0');
        return $item;
    }

    function addToTrash($file_address){
        $db = db_connect();
        $table = $db->table('cron_files_delete');
        if(!is_array($file_address)){
            return $table->insert(['file_address'=>$file_address]);
        }
        return $table->insertBatch($file_address);
    }

    function set_admin_product($pid, $type, $db=''){
        if(empty($db)){
            $db = db_connect();
        }
        $table = $db->table('admins_products');
        return $table->insert([
           'admin_id'=>$_SESSION['admin']['id'],
           'product_id'=>$pid,
           'type'=>$type
        ]);
    }

    function delete_admin_product($pid, $type, $db=''){
        if(empty($db)){
            $db = db_connect();
        }
        $table = $db->table('admins_products');
        if(!is_array($pid)){
            return $table->where([
                'product_id'=>$pid,
                'type'=>$type
            ])->delete();
        }else{
            return $table->where([
                'type'=>$type
            ])->whereIn('product_id', $pid)->delete();
        }
    }

    function get_settings(){
        $db = db_connect();
        $table = $db->table('settings');
        $items = $table->select()->get()->getResultArray();
        return $items;
    }

    function get_setting($key){
        $db = db_connect();
        $table = $db->table('settings');
        if(is_array($key)){
            $items = $table->select()->whereIn('key', $key)->get()->getResultArray();
        }else{
            $items = $table->select()->where('key', $key)->get()->getRowArray('0');
        }

        return $items;
    }

    function set_setting($key, $value){
        $db = db_connect();
        $table = $db->table('settings');
        return $table->where(['key'=>$key])->update(['value'=>$value]);
    }
}