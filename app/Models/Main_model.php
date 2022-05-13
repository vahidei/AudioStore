<?php

namespace App\Models;


class Main_model
{
    function get_packages($limit = 10, $offset = 0, $ids=false)
    {
        $db = db_connect();
        $table = $db->table('packages p');
        $table->select('p.title, p.title_fa,p.short_desc,p.short_desc_fa, p.price, p.id, m.coded_name, d.discount, d.discount_type, p.most_used_colors, count(DISTINCT pi.purchase_id) as sold, p.buy_limit', true)
            ->join('media m', 'm.parent_id = p.id', 'inner')
            ->join('discount_items di', 'di.item_id = p.id AND di.type = "package"', 'left')
            ->join('discounts d', 'd.id = di.discount_id AND d.expire_date > NOW()', 'left')
            ->join('purchase_items pi', 'pi.item_id = p.id AND pi.type="package" AND p.buy_limit != "-1"', 'left')
            ->join('purchases ps', 'ps.id = pi.purchase_id AND ps.status = "success"', 'left')
            ->where([
                'p.status' => 'publish',
                'm.type' => 'package_cover'
            ])->groupBy(['p.id']);

        if($ids){
            $table->whereIn('p.id', $ids);
            $items = $table->get()->getResultArray();
        }else{
            $items = $table->get($limit, $offset)->getResultArray();
        }

        return $items;
    }

    function packages_count()
    {
        $db = db_connect();
        $table = $db->table('packages p');
        $item = $table->select('count(p.id) as count', true)
            ->join('media m', 'm.parent_id = p.id', 'inner')
            ->where([
                'p.status' => 'publish',
                'm.type' => 'package_cover'
            ])->get()->getRow(0, 'array');
        return $item['count'];
    }

    function get_discounts(){
        $db = db_connect();
        $table = $db->table('discounts d');
        $items = $table->select('count(t.id) as tracksCount, count(p.id) as packagesCount, m.`coded_name`, d.*')
            ->join('discount_items di', 'di.discount_id = d.id', 'inner')
            ->join('media m', 'm.parent_id = d.id AND m.type = "discount_photo"', 'left')
            ->join('tracks t', 't.id = di.item_id AND di.type = "track" AND t.status="publish"', 'left')
            ->join('packages p', 'p.id = di.item_id AND di.type = "package" AND p.status="publish"', 'left')
            ->where([
                'd.status' => 'publish'
            ])->where('d.expire_date >', 'NOW()', false)->groupBy(['d.id'])->get()->getResult('array');

        return $items;
    }

    function discounts_count()
    {
        $db = db_connect();
        $table = $db->table('discounts d');
        $item = $table->select('count(DISTINCT d.id) as count', true)
            ->join('discount_items di', 'di.discount_id = d.id', 'inner')
            ->join('media m', 'm.parent_id = d.id', 'inner')
            ->where([
                'd.status' => 'publish',
                'm.type' => 'discount_photo'
            ])->where('d.expire_date >', 'NOW()', false)->get()->getRow(0, 'array');

        return $item['count'];
    }

    function valid_items($ids, $table)
    {
        $db = db_connect();
        $table = $db->table($table);
        $items = $table->select('id')->where('status', 'publish')->whereIn('id', $ids)->get()->getResultArray();
        $items = array_column($items, 'id');
        return $items;
    }

    function get_package($id)
    {
        $db = db_connect();
        $table = $db->table('packages p');
        $package = $table
            ->select('p.id, p.title, p.title_fa, p.price, count(pt.package_id) as tracksCount, d.discount, d.discount_type, p.buy_limit, count(DISTINCT pi.purchase_id) as sold')
            ->join('packages_tracks pt', 'pt.package_id = p.id', 'inner')
            ->join('discount_items di', 'di.item_id = p.id AND di.type = "package"', 'left')
            ->join('discounts d', 'd.id = di.discount_id AND d.expire_date > NOW()', 'left')
            ->join('purchase_items pi', 'pi.item_id = p.id AND pi.type = "package" AND p.buy_limit != "-1"', 'left')
            ->join('purchases ps', 'ps.id = pi.purchase_id AND ps.status="success"', 'left')
            ->where([
                'p.id' => $id,
                'p.status' => 'publish'
            ])->get()->getRowArray();
        return $package;
    }

    function cart_selected_packages($ids)
    {
        $db = db_connect();
        $table = $db->table('packages p');
        $items = $table->select('p.title, p.title_fa, p.short_desc, p.short_desc_fa, p.price, p.id, m.coded_name, d.discount, d.discount_type, p.most_used_colors, p.buy_limit, count(DISTINCT pi.purchase_id) as sold', true)
            ->join('media m', 'm.parent_id = p.id', 'inner')
            ->join('discount_items di', 'di.item_id = p.id AND di.type = "package"', 'left')
            ->join('discounts d', 'd.id = di.discount_id AND d.expire_date > NOW()', 'left')
            ->join('purchase_items pi', 'pi.item_id = p.id AND pi.type = "package" AND p.buy_limit != "-1"', 'left')
            ->join('purchases ps', 'ps.id = pi.purchase_id AND ps.status="success"', 'left')
            ->where([
                'p.status' => 'publish',
                'm.type' => 'package_cover'
            ])->whereIn('p.id', $ids)->get()->getResult('array');

        return $items;
    }

    function cart_selected_tracks($ids)
    {
        $db = db_connect();
        $table = $db->table('tracks t');
        $items = $table->select('t.title, t.title_fa, t.price, t.id, d.discount, d.discount_type, t.buy_limit, count(DISTINCT pi.purchase_id) as sold', true)
            ->join('discount_items di', 'di.item_id = t.id AND di.type = "track"', 'left')
            ->join('discounts d', 'd.id = di.discount_id AND d.expire_date > NOW()', 'left')
            ->join('purchase_items pi', 'pi.item_id = t.id AND pi.type = "track" AND t.buy_limit != "-1"', 'left')
            ->join('purchases ps', 'ps.id = pi.purchase_id AND ps.status="success"', 'left')
            ->where([
                't.status' => 'publish'
            ])->whereIn('t.id', $ids)->get()->getResult('array');

        return $items;
    }

    function addPurchase($uid, $utype, $token, $price, $data)
    {
        $db = db_connect();
        $table = $db->table('purchases');
        $db->transStart();
        $table->set('created_at', 'NOW()', false);
        $table->insert([
            'user_type' => $utype,
            'user' => $uid,
            'token' => $token,
            'price' => $price,
            'status' => 'pending'
        ]);


        $last_id = $db->insertID();

        $table = $db->table('purchase_items');

        $insData = [];
        if (is_array($data['packages']) && !empty($data['packages'])) {
            foreach ($data['packages'] as $item) {
                $insData[] = ['purchase_id' => $last_id, 'item_id' => $item['id'], 'type' => 'package', 'final_price' => $item['final_price'], 'code' => ''];
            }
        }
        if (is_array($data['tracks']) && !empty($data['tracks'])) {
            foreach ($data['tracks'] as $item) {
                $insData[] = ['purchase_id' => $last_id, 'item_id' => $item['id'], 'type' => 'track', 'final_price' => $item['final_price'], 'code' => ''];
            }
        }

        if (empty($insData)) {
            $db->transRollback();
            return false;
        }

        $table->insertBatch($insData);

        $db->transComplete();
        if (!$db->transStatus()) {
            $db->transRollback();
            return false;
        }
        return $db->transStatus();
    }

    function checkPurchase($uid, $utype, $token)
    {
        $db = db_connect();
        $table = $db->table('purchases');
        $item = $table->select()->where([
            'user_type' => $utype,
            'user' => $uid,
            'status' => 'pending',
            'trans_id' => '',
            'token' => $token
        ])->get()->getRow(0, 'array');
        return $item;
    }

    function verifyPurchase($uid, $utype, $token, $transId, $status)
    {
        $db = db_connect();
        $table = $db->table('purchases');
        $update = $table->where([
            'user_type' => $utype,
            'user' => $uid,
            'token' => $token
        ])
            ->update([
                'status' => $status,
                'trans_id' => $transId
            ]);
        return $update;
    }

    function create_code_purchase_items($purchase_id)
    {
        $db = db_connect();
        $table = $db->table('purchase_items');
        $items = $table->select('item_id, type')->where('purchase_id', $purchase_id)->get()->getResultArray();

        if (empty($items)) return false;

        $db->transStart();

        foreach ($items as $item) {
            $code = generate_code($item['item_id'] . $item['type']);
            $table->where([
                'purchase_id' => $purchase_id,
                'item_id' => $item['item_id'],
                'type' => $item['type']
            ])->update(['code' => $code]);
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            $db->transRollback();
        }

        return $db->transStatus();

    }

    function get_single_package($id)
    {
        $db = db_connect();
        $table = $db->table('packages p');
        $package = $table
            ->select('p.*, count(DISTINCT pi.purchase_id) as sold, m.coded_name, d.discount, d.discount_type')
            ->join('media m', 'm.parent_id = p.id', 'inner')
            ->join('discount_items di', 'di.item_id = p.id AND di.type = "package"', 'left')
            ->join('discounts d', 'd.id = di.discount_id AND d.expire_date > NOW()', 'left')
            ->join('purchase_items pi', 'pi.item_id = p.id AND pi.type = "package" AND p.buy_limit != "-1"', 'left')
            ->join('purchases ps', 'ps.id = pi.purchase_id AND ps.status="success"', 'left')
            ->where([
                'm.is_original' => '0',
                'm.type' => 'package_cover',
                'p.id' => $id,
                'p.status' => 'publish'
            ])->get()->getRowArray();

        if (empty($package)) return false;

        $table = $db->table('packages_tracks pt');
        $tracks = $table
            ->select('t.*, m.coded_name, m.file_duration')
            ->join('tracks t', 't.id = pt.track_id', 'inner')
            ->join('media m', 'm.parent_id = t.id', 'inner')
            ->where([
                'pt.package_id' => $id,
                'm.type' => 'track_file',
                'm.is_original' => '0',
            ])->whereIn('t.status', ['publish', 'only_package'])->get()->getResultArray();
        if (empty($tracks)) return false;

        return ['package' => $package, 'tracks' => $tracks];
    }

    function get_categories()
    {
        $db = db_connect();
        $table = $db->table('categories');
        $items = $table->select('', true)->get()->getResult('array');
        return $items;
    }

    function get_tracks($cat_id, $limit, $offset, $orderBy, $ids=false)
    {

        $db = db_connect();
        $table = $db->table('tracks t');

        if (intval($cat_id) !== 0 && !$ids) {
            $table->where('t.category_id', $cat_id);
        }

        $table->select('t.title, t.title_fa, t.price, d.discount, d.discount_type, t.id, m.coded_name, mo.file_duration, count(DISTINCT pi.purchase_id) as sold, t.buy_limit', true)
            ->join('media m', 'm.parent_id = t.id', 'inner')
            ->join('media mo', 'mo.parent_id = t.id', 'inner')
            ->join('discount_items di', 'di.item_id = t.id AND di.type = "track"', 'left')
            ->join('discounts d', 'd.id = di.discount_id AND d.expire_date > NOW()', 'left')
            ->join('purchase_items pi', 'pi.item_id = t.id AND pi.type="track" AND t.buy_limit != "-1"', 'left')
            ->join('purchases ps', 'ps.id = pi.purchase_id AND ps.status = "success"', 'left')
            ->where([
                't.status' => 'publish',
                'mo.type' => 'track_file',
                'mo.is_original' => '1',
                'm.type' => 'track_file',
                'm.is_original' => '0'
            ])->groupBy('t.id');

        if($ids){
            $table->whereIn('t.id', $ids);
            $items = $table->get()->getResultArray();
        }else{
            $items = $table->orderBy($orderBy)->get($limit, $offset)->getResultArray();
        }

        return $items;
    }

    function tracks_count($cat_id=0)
    {

        $db = db_connect();
        $table = $db->table('tracks t');
        if($cat_id !== 0){
            $item = $table->select('count(t.id) as count')
                ->join('media m', 'm.parent_id = t.id', 'inner')
                ->join('categories c', 'c.id = t.cat_id', 'inner')
                ->where([
                    't.status' => 'publish',
                    'm.type' => 'track_file',
                    'm.is_original' => '0'
                ])->get()->getRow(0, 'array');
        }else{
            $item = $table->select('count(t.id) as count')
                ->join('media m', 'm.parent_id = t.id', 'inner')
                ->where([
                    't.status' => 'publish',
                    'm.type' => 'track_file',
                    'm.is_original' => '0'
                ])->get()->getRow(0, 'array');
        }

        return $item['count'];
    }

    function get_track($id)
    {
        $db = db_connect();
        $table = $db->table('tracks t');
        $item = $table->select('t.id, t.title, t.title_fa, t.price, d.discount, d.discount_type, m.coded_name as code, m.file_duration as duration, c.title as cat_title, t.buy_limit, count(DISTINCT pi.purchase_id) as sold')
            ->join('media m', 'm.parent_id = t.id', 'inner')
            ->join('categories c', 'c.id = t.category_id', 'inner')
            ->join('discount_items di', 'di.item_id = t.id AND di.type = "track"', 'left')
            ->join('discounts d', 'd.id = di.discount_id AND d.expire_date > NOW()', 'left')
            ->join('purchase_items pi', 'pi.item_id = t.id AND pi.type = "track" AND t.buy_limit != "-1"', 'left')
            ->join('purchases ps', 'ps.id = pi.purchase_id AND ps.status="success"', 'left')
            ->where([
                't.id' => $id,
                'm.is_original' => '0',
                't.status' => 'publish'
            ])->get()->getRow(0, 'array');

        return $item;
    }

    function get_purchased_items($purchase_id)
    {
        $db = db_connect();
        $table = $db->table('purchase_items pi');
        $items = $table->select('pi.*')
            ->join('purchases p', 'p.id = pi.purchase_id', 'inner')
            ->where([
                'purchase_id' => $purchase_id,
                'p.status' => 'success'
            ])->get()->getResultArray();

        $packages = $tracks = [];

        foreach ($items as $item) {
            if ($item['type'] == 'package') {
                $packages[] = $item['item_id'];
            }
            if ($item['type'] == 'track') {
                $tracks[] = $item['item_id'];
            }
        }

        $table = $db->table('tracks t');
        $tracks_items = $table->select('t.id, t.title, t.title_fa')
            ->where('t.status', 'publish')->whereIn('id', $tracks)->get()->getResultArray();

        $table = $db->table('packages p');
        $packages_items = $table->select('p.id, p.title, p.title_fa')
            ->where('p.status', 'publish')->whereIn('id', $packages)->get()->getResultArray();

        return ['purchased_items' => $items, 'packages' => $packages_items, 'tracks' => $tracks_items];

    }

    function get_dl_file_code($code)
    {
        $db = db_connect();
        $table = $db->table('purchase_items');
        $item = $table->select()->where('code', $code)->get()->getRow(0, 'array');
        return $item;
    }

    function get_original_track($track_id)
    {
        $db = db_connect();
        $table = $db->table('media');
        $item = $table->select()->where(['parent_id' => $track_id, 'type' => 'track_file', 'is_original' => '1'])->get()->getRow(0, 'array');
        return $item;
    }

    function create_temp_download($item_id, $type, $code, $file_name, $file_address, $ip, $expire)
    {
        $db = db_connect();
        $table = $db->table('download_links');
        $table->set('created_at', 'NOW()', false);
        return $table->insert([
            'item_id' => $item_id,
            'type' => $type,
            'code' => $code,
            'file_name' => $file_name,
            'ip' => $ip,
            'file_address' => $file_address,
            'expire_date' => $expire
        ]);
    }

    function get_temp_download($code)
    {
        $db = db_connect();
        $table = $db->table('download_links');
        $item = $table->select()->where(['code' => $code, 'ip' => getIp()])->get()->getRow(0, 'array');
        return $item;
    }

    function get_packages_tracks($package_id)
    {
        $db = db_connect();
        $table = $db->table('packages_tracks pt');
        $items = $table->select('m.coded_name,m.file_name, p.title, p.title_fa, pt.track_id')
            ->join('packages p', 'p.id = pt.package_id', 'inner')
            ->join('media m', 'm.parent_id = pt.track_id', 'inner')
            ->where([
                'pt.package_id' => $package_id,
                'm.type' => 'track_file',
                'm.is_original' => '1'
            ])
            ->get()->getResultArray();

        return $items;
    }

    function addSubscribeCode($email, $code)
    {
        $db = db_connect();
        $table = $db->table('subscribers_codes');

        $item = $table->select('count(email) as count')->where('email', $email)->get()->getRowArray('0');
        if ($item['count'] == 0) {
            $table->set('created_at', 'NOW()', false);
            return $table->insert([
                'email' => $email,
                'code' => $code
            ]);

        } else {
            $table->set('created_at', 'NOW()', false);
            return $table->where('email', $email)->update([
                'code' => $code
            ]);
        }
    }

    function verifySubscribe($email, $code){
        $db = db_connect();
        $table = $db->table('subscribers_codes');
        $item = $table->select()->where(['email'=>$email, 'code'=>$code])->get()->getRowArray('0');
        $table->where(['email'=> $email])->delete();
        if(!empty($item)){
            $table = $db->table('subscribers');
            $item = $table->select()->where(['email'=>$email])->get()->getRowArray('0');
            if(!empty($item)){
                if($item['status'] == 'unsubscribe'){
                    return $table->where(['email'=>$email])->update([
                        'status'=>'subscribe'
                    ]);
                }
                return true;
            }else{
                $table->set('created_at', 'NOW()', false);
                return $table->ignore()->insert([
                    'email'=>$item['email'],
                    'status'=>'subscribe'
                ]);
            }
        }else{
            return false;
        }
    }

    function is_subscribed($email){
        $db = db_connect();
        $table = $db->table('subscribers');

        $item = $table->select()->where('email', $email)->get()->getRowArray('0');
        if(!empty($item)){
            $table->where('email', $email)->update(['status'=>'subscribe']);
            return true;
        }

        return false;


    }

    function get_free_download($type, $id){
        $db = db_connect();
        if($type == 'track'){
            $table = $db->table('tracks t');
            $item = $table->select('t.id, t.price, m.coded_name, m.file_name, m.file_size, d.discount, d.discount_type')
                    ->join('discount_items di', 'di.item_id = t.id AND di.type = "track"', 'left')
                    ->join('discounts d', 'd.id = di.discount_id AND d.expire_date > NOW()', 'left')
                    ->join('media m', 'm.parent_id = t.id', 'inner')
                    ->where([
                        't.id'=>$id,
                        't.status'=>'publish',
                        'm.type'=>'track_file',
                        'm.is_original'=>'1'
                    ])
                    ->get()->getRowArray('0');
        }else{
            $table = $db->table('packages p');
            $item = $table->select('p.id, p.price, d.discount, d.discount_type')
                ->join('discount_items di', 'di.item_id = p.id AND di.type = "package"', 'left')
                ->join('discounts d', 'd.id = di.discount_id AND d.expire_date > NOW()', 'left')
                ->join('packages_tracks pt', 'pt.package_id = p.id', 'inner')
                ->join('tracks t', 't.id = pt.track_id', 'inner')
                ->where([
                    'p.id'=>$id,
                    't.status'=>'publish',
                    'p.status'=>'publish'
                ])
                ->get()->getRowArray('0');

        }

        return $item;
    }

    function add_to_downloaded($id, $type, $is_free, $user, $ip){
        $db = db_connect();
        $table = $db->table('downloaded_products');
        $table->set('created_at', 'NOW()', false);
        return $table->insert([
            'item_id'=>$id,
            'type'=>$type,
            'is_free'=>$is_free,
            'user'=>$user,
            'ip'=>$ip
        ]);
    }

    function get_discount_items($discount_id){
        $db = db_connect();
        $table = $db->table('discounts');
        $discount = $table->select()->where(['id'=>$discount_id, 'status'=>'publish'])
            ->where('expire_date >', 'NOW()', false)
            ->get()->getRowArray('0');
        if(!$discount){
            return false;
        }

        $table = $db->table('discount_items di');

        if($discount['type'] == 'package'){
            $items = $table->select('p.*, m.coded_name')
                        ->join('packages p', 'p.id = di.item_id', 'inner')
                        ->join('media m', 'm.parent_id = p.id AND m.`type`="package_cover"', 'inner')
                        ->where([
                            'di.discount_id'=> $discount_id,
                            'p.status'=>'publish']
                        )->groupBy('p.id')->get()->getResultArray();

        }elseif($discount['type'] == 'track'){
            $items = $table->select('t.*, m.coded_name, m.file_duration')
                ->join('tracks t', 't.id = di.item_id', 'inner')
                ->join('media m', 'm.parent_id = t.id AND m.`type`="track_file"', 'inner')
                ->where([
                        'di.discount_id'=> $discount_id,
                        't.status'=>'publish',
                        'm.is_original'=>'0'
                    ]
                )->get()->getResultArray();
        }

        return ['discount'=>$discount, 'items'=>$items];
    }

    function can_be_save($item_id, $type){
        $db = db_connect();
        if($type == 'package'){
            $table = $db->table('packages');
        }elseif($type == 'track'){
            $table = $db->table('tracks');
        }

        return $table->select()->where(['status'=>'publish', 'id'=>$item_id])->get()->getRowArray('0');

    }

    function save_item($item_id, $type, $user_id){
        $db = db_connect();
        $table = $db->table('user_saved_items');
        $table->set('created_at', 'NOW()', false);
        return $table->insert([
            'user_id'=> $user_id,
            'item_id'=>$item_id,
            'type'=>$type
        ]);
    }

    function remove_saved_item($item_id, $type, $user_id){
        $db = db_connect();
        $table = $db->table('user_saved_items');
        return $table->where([
            'user_id'=> $user_id,
            'item_id'=>$item_id,
            'type'=>$type
        ])->delete();
    }

    function get_saved($type, $limit, $offset, $user_id){
        $db = db_connect();
        $table = $db->table('user_saved_items');
        $items = $table->select('item_id')->where(['user_id'=>$user_id, 'type'=>$type])->get($limit, $offset)->getResultArray();
        if(empty($items)){
            return [];
        }
        $ids = array_column($items, 'item_id');

        if($type == 'package'){
            $items = $this->get_packages(0,0, $ids);
        }else{

            $items = $this->get_tracks(0,0, 0, 0, $ids);
        }

        return $items;
    }

    function saved_items_count($type, $user_id){
        $db = db_connect();
        $table = $db->table('user_saved_items');
        $item = $table->select('count(*) as count')->where(['type'=>$type, 'user_id'=>$user_id])->get()->getRowArray('0');
        return $item['count'];
    }
}