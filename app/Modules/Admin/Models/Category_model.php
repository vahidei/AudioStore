<?php

namespace App\Modules\Admin\Models;

class Category_model extends Main_model
{
    public function list(){
        $db = db_connect();
        $table = $db->table('categories');
        $items = $table->select()->get()->getResult('array');
        return $items;
    }

    public function add($title, $title_fa, $color){
        $db = db_connect();
        $table = $db->table('categories');
        $table->set('last_update', 'NOW()', false);
        $table->set('created_at', 'NOW()', false);
        $insert = $table->insert(['title'=>$title,
                                    'title_fa'=>$title_fa,
                                    'color'=>$color]);
        $this->set_admin_product($db->insertID(), 'category', $db);
        return $insert;
    }

    public function delete_rows($items){
        $db = db_connect();
        $table = $db->table('categories');
        $delete = $table->whereIn('id', $items);
        $this->delete_admin_product($items, 'category', $db);
        return $delete->delete();
    }

    public function get($id){
        $db = db_connect();
        $table = $db->table('categories');
        $item = $table->select()->where('id', $id)->get()->getRow(0,'array');
        return $item;
    }

    public function edit($id, $title, $title_fa, $color){
        $db = db_connect();
        $table = $db->table('categories');
        $update = $table->where('id', $id)->update(['title'=>$title, 'title_fa'=>$title_fa, 'color'=>$color]);
        return $update;
    }
}