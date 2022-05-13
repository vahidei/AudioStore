<?php

namespace App\Models;


class User_model
{
    function isEmailRegistered($email)
    {
        $db = db_connect();
        $table = $db->table('users');
        return $table->select()->where('email', $email)->countAllResults();
    }

    function isPending($username, $email){
        $db = db_connect();
        $table = $db->table('users');
        return $table->select()->where(['email'=> $email, 'username'=>$username, 'status'=>'pending'])->countAllResults();
    }

    function check_verify_code($username, $code){
        $db = db_connect();
        $table = $db->table('users_verify_codes');
        return $table->select()->where(['code'=> $code, 'username'=>$username])->countAllResults();
    }

    function verify_user($username){
        $db = db_connect();
        $table = $db->table('users');
        return $table->where('username', $username)->update(['status'=>'verified']);
    }

    function get_user_by_username($username){
        $db = db_connect();
        $table = $db->table('users');
        return $table->select()->where('username', $username)->get()->getRow(0,'array');
    }

    function get_user_by_email_password($email, $password){
        $db = db_connect();
        $table = $db->table('users');
        $password = encode_password($password, $email);
        return $table->select()->where(['email'=>$email, 'password'=>$password])->get()->getRow(0,'array');
    }

    function update_code($username, $code){
        $db = db_connect();
        $table = $db->table('users_verify_codes');
        $table->set('created_at', 'NOW()', false);

         return  $table->where('username', $username)->update(['code'=> $code]);
    }

    function get_user_by_email($email){
        $db = db_connect();
        $table = $db->table('users');
        return $table->select()->where('email', $email)->get()->getRow(0,'array');
    }

    function get_user_by_session($session){
        $db = db_connect();
        $table = $db->table('users_login_session uls');
        $item = $table->select('u.*')
            ->join('users u', 'u.id = uls.user_id', 'inner')
            ->where(['uls.session'=>$session])->where('uls.expire_date >', 'NOW()', false)
            ->get()->getRowArray('0');
        return $item;
    }

    function revival_user_login_session($user_id){
        $db = db_connect();
        $expire_date = date('Y-m-d H:i:s', time() + (14 * 24 * 60 * 60));
        $table = $db->table('users_login_session');
        return $table->where('user_id', $user_id)->update([
            'expire_date' => $expire_date
        ]);
    }

    function add($item, $code)
    {
        $db = db_connect();
        $table = $db->table('users');
        $db->transStart();
        $username = md5($item['email'] . time());
        $password = encode_password($item['password']);
        $table->set('last_signin', 'NOW()', false);
        $table->set('created_at', 'NOW()', false);
        $table->insert([
            'username' => $username,
            'name' => $item['name'],
            'email' => $item['email'],
            'password'=>$password,
            'mobile' => '',
            'status' => 'pending',
            'subscribe' => 1
        ]);

        $table = $db->table('users_verify_codes');
        $table->set('created_at', 'NOW()', false);
        $table->insert([
            'username' => $username,
            'code' => $code
        ]);


        if (!$db->transStatus()) {
            $db->transRollback();
            $username = false;
        }
        $db->transComplete();
        return $username;
    }

    function update_settings($item, $pw_set, $user_id){
        $db = db_connect();
        $table = $db->table('users');
        if($pw_set){
            return $table->where('id', $user_id)
                ->update([
                    'name' => $item['name'],
                    'mobile'=>$item['mobile'],
                    'subscribe'=>$item['subscribe'],
                    'password'=>$item['password']
                ]);
        }else{
            return $table->where('id', $user_id)
                ->update([
                    'name' => $item['name'],
                    'mobile'=>$item['mobile'],
                    'subscribe'=>$item['subscribe']
                ]);
        }
    }

    function get_purchases($limit, $offset, $user_id, $user_email){
        $db = db_connect();
        $table = $db->table('purchases p');
        $items = $table->select('p.created_at as buy_date, t.title as track_title, t.title_fa track_title_fa, pc.title as package_title, pc.title_fa as package_title_fa, pi.code, pi.final_price')
                    ->join('purchase_items pi', 'pi.purchase_id = p.id', 'inner')
                    ->join('packages pc', 'pc.id = pi.item_id AND pi.type="package"', 'left')
                    ->join('tracks t', 't.id = pi.item_id AND pi.type="track"', 'left')

            ->where([
            'p.status'=>'success'
        ])->whereIn('p.user', [$user_email, $user_id])->limit($limit)->offset($offset)->get()->getResultArray();

        return $items;
    }

    function purchases_count($type, $user_id, $user_email){
        $db = db_connect();
        $table = $db->table('purchases');
        $item = $table->select('count(id) as count')
            ->where([
                'status'=>$type
            ])->whereIn('user', [$user_email, $user_id])->get()->getRowArray('0');
        return $item['count'];
    }

    function google_user_update($user){
        $db = db_connect();
        $table = $db->table('users');
        return $table->where(['email'=>$user['email']])
                ->update([
                    'name'=> $user['name'],
                    'status'=>'verified'
                ]);
    }

    function google_user_insert($user){
        $db = db_connect();
        $table = $db->table('users');
        $username = md5($user['email'] . time());
        $table->set('last_signin', 'NOW()', false);
        $table->set('created_at', 'NOW()', false);
        return $table->insert([
            'username' => $username,
            'name' => $user['name'],
            'email' => $user['email'],
            'password'=> 'SIGNUP_BY_EMAIL',
            'mobile' => '',
            'status' => 'verified',
            'subscribe' => '1'
        ]);

    }

    function set_login_session($user_id, $lsession){
        $db = db_connect();
        $expire_date = date('Y-m-d H:i:s', time() + (14 * 24 * 60 * 60));
        $table = $db->table('users_login_session');
        $table->where('user_id', $user_id)->delete();
        $table->set('created_at', 'NOW()', false);
        return $table->insert([
            'user_id' => $user_id,
            'session' => $lsession,
            'expire_date' => $expire_date
        ]);
    }

    function add_reset_password($email, $code){
        $db = db_connect();
        $expire_date = date('Y-m-d H:i:s', time() + (60 * 60));
        $table = $db->table('users_reset_password');
        $table->where('email', $email)->delete();
        $table->set('created_at', 'NOW()', false);
        return $table->insert([
            'email'=>$email,
            'code'=>$code,
            'expire_date'=>$expire_date
        ]);
    }

    function get_rp_email($code){  // get_reset_password_email_by_code
        $db = db_connect();
        $table = $db->table('users_reset_password');
        return $table->select('email')
                ->where('code', $code)
                ->where('expire_date >', 'NOW()', false)
                ->get()->getRowArray('0');
    }

    function update_user_password($email, $password){
        $db = db_connect();
        $table = $db->table('users');
        $password = encode_password($password, $email);
        $db->transStart();
        $table->where('email', $email)->update(['password'=> $password]);
        $table = $db->table('users_reset_password');
        $table->where('email', $email)->delete();
        $db->transComplete();
        if(!$db->transStatus()){
            $db->transRollback();
        }
        return $db->transStatus();
    }
}