<?php namespace App\Controllers;

use App\Models\Main_model;
use App\Models\User_model;

class Api extends BaseController
{
    public function __construct()
    {
        helper('funcs');
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
             show_404();
        }
    }

    public function index()
    {

    }

    public function get_tracks()
    {
        $main_model = new Main_model();

        if (!isset($_POST['cat_id']) || !is_numeric($_POST['cat_id'])) {
            $cat_id = 0;
        } else {
            $cat_id = intval($_POST['cat_id']);
        }

        if(!isset($_POST['page']) || !is_numeric($_POST['page'])){
            $page = 1;
        }else{
            $page = $_POST['page'];
        }

        $limit = 20;
        $offset = ($page - 1)  *  $limit;

        $ob = ($_POST['orderBy'] < 3) ? $_POST['orderBy'] : 0;
        $tracks_count = $main_model->tracks_count($cat_id);
        $orderBy = ['t.created_at DESC', 't.price ASC', 't.price DESC'];

        $tracks = $main_model->get_tracks($cat_id, $limit, $offset, $orderBy[$ob]);
        send_json(['tracks'=>$tracks, 'tracks_count'=>$tracks_count, 'limit'=>$limit, 'page'=>$page, 'links'=>5]);
    }

    public function buy(){

        $cookie = get_cookie('cart');
        if(!isJson($cookie)){
            clearCartCookie();
        }
        if(empty($cookie)) {
            cart_empty:
            send_json(['error'=>lang('main.any_cart_item_exists'), 'success'=>false]);
            die();
        }

        $cookie = json_decode($cookie, 'array');
        if(!is_array($cookie)){
            goto cart_empty;
        }

        $packages_id = $tracks_id = [];
        foreach($cookie as $item){
            if(!isset($item['type']) || ($item['type'] !== 'track' && $item['type'] !== 'package')
                || !isset($item['id']) || !is_numeric($item['id'])
            ){
                goto next;
            }

            if($item['type'] == 'package'){
                $packages_id[] = $item['id'];
            }else{
                $tracks_id[] = $item['id'];
            }
            next:
        }
        $main_model = new Main_model();
        $tracks_id = $main_model->valid_items($tracks_id, 'tracks');
        $packages_id = $main_model->valid_items($packages_id, 'packages');

        if(empty($tracks_id) && empty($packages_id)){
            goto cart_empty;
        }

        $_SESSION['cart'] = [];
        $_SESSION['cart']['selected_items']['tracks'] = $tracks_id;
        $_SESSION['cart']['selected_items']['packages'] = $packages_id;

        $_SESSION['fromCart'] = true;
        send_json(['error'=>((isset($_SESSION['user'])) ? 0 : 2), 'success'=>true]);
    }

    public function subscribe_email(){
        if(!isset($_POST['email']) || !valid_email($_POST['email']) || !isset($_POST['recaptcha_code']) || empty($_POST['recaptcha_code'])){
            show_404();
        }

        if(!isValidRecaptcha($_POST['recaptcha_code'])){
            return send_json(['error'=>lang('main.recaptcha_is_wrong')]);
        }

        $main_model = new Main_model();

       // if($main_model->is_subscribed($_POST['email'])){
      //      return send_json(['exists'=>true, 'message'=>'You have already subscribed. Your subscription has been reactivated.']);
      //  }

        $code = rand(10000, 99999);
        send_email($_POST['email'], 'Verify your email to subscribe.', 'Code: '.$code);
        if($main_model->addSubscribeCode($_POST['email'], $code)){
           return send_json(['success'=>true]);
        }else{
            return send_json(['error'=>lang('main.subscribing_error')]);
        }

    }

    public function subscribe_code_verification(){
        if(!isset($_POST['email']) || !valid_email($_POST['email']) || !isset($_POST['code']) || empty($_POST['code'])){
            show_404();
        }
        $main_model = new Main_model();
        $verify = $main_model->verifySubscribe($_POST['email'], $_POST['code']);
        if($verify){
            return send_json(['success'=>true, 'message'=>lang('main.subscription_success')]);
        }else{
            return send_json(['error'=>lang('main.invalid_verification_code')]);
        }
    }

    public function get_track(){
        if (!isset($_POST['track_id']) || !is_numeric($_POST['track_id'])) {
            show_404();
        }

        $main_model = new Main_model();

        $track = $main_model->get_track($_POST['track_id']);

        send_json($track);
    }

    public function add_to_cart(){
        if (!isset($_POST['item_id']) || !isset($_POST['type']) || !is_numeric($_POST['item_id']) || !in_array($_POST['type'], ['track', 'package'])) {
            show_404();
        }
        unset($_SESSION['cart']);
        $main_model = new Main_model();
        if($_POST['type'] == 'track'){
            $item = $main_model->get_track($_POST['item_id']);
            unset($item['cat_title']);
        }else{
            $item = $main_model->get_package($_POST['item_id']);
        }

        if(empty($item)){
            show_404();
        }

        $price = $item['price'];
        if (!empty($item['discount'])) {
            $price = calcDiscount($item['price'], $item['discount'], $item['discount_type']);
        }

        if ($price <= 0) {
            show_404();
        }

        if($item['buy_limit'] !== '-1'){
            if(intval($item['buy_limit']) - $item['sold'] <= 0){
                show_404();
            }
        }

        unset($item['buy_limit']);
        unset($item['sold']);

        $item['type'] = $_POST['type'];

        $cookie = get_cookie('cart');
        if(!isJson($cookie)){
            clearCartCookie();
        }
        if(!empty($cookie)){
            $cookie = json_decode($cookie, 'array');
            $ids = array_column($cookie, 'id');

            if(!in_array($item['id'], $ids)){
                $cookie[] = $item;
            }else{
                if($cookie[array_search($item['id'], $ids)]['type'] !== $_POST['type']){
                    $cookie[] = $item;
                }
            }
        }else{
            $cookie = [$item];
        }
        setcookie('cart', json_encode($cookie), time() + 2592000, '/');

        send_json(['success'=>true]);
    }

    public function display_cart(){
        $cookie = get_cookie('cart');
        if(!isJson($cookie)){
            clearCartCookie();
        }
        if(!empty($cookie)) {
            $cookie = json_decode($cookie, 'array');
        }else{
            $cookie = [];
        }
        send_json($cookie);
    }

    public function update_cart_amount(){
        $cookie = get_cookie('cart');
        if(!isJson($cookie)){
            clearCartCookie();
        }
        if(!empty($cookie)) {
            $cookie = json_decode($cookie, 'array');
            $value = array_sum(array_column($cookie,'price'));
        }else{
            $cookie = [];
            $value = 0;
        }
        send_json(['value'=>$value]);
    }

    public function delete_cart_item(){
        if (!isset($_POST['index']) || !is_numeric($_POST['index'])) {
            show_404();
        }
        $cookie = get_cookie('cart');
        if(!isJson($cookie)){
            clearCartCookie();
        }
        if(!empty($cookie)) {
            $cookie = json_decode($cookie, 'array');
            array_splice($cookie, intval($_POST['index']), 1);

        }else{
            $cookie = [];
        }

        setcookie('cart', json_encode($cookie), time() + 2592000, '/');

        send_json(['success'=>true]);
    }

    public function save_item(){

        if(!isset($_POST['item_id']) || !isset($_POST['type']) || !in_array($_POST['type'], ['package', 'track']) || !is_numeric($_POST['item_id'])){
            show_404();
        }

        if(!isset($_SESSION['user'])){
            return send_json(['success'=>false, 'error'=>lang('main.to_save_should_login')]);
        }

        $main_model = new Main_model();
        if(!$main_model->can_be_save($_POST['item_id'], $_POST['type'])){
            show_404();
        }

        if($main_model->save_item($_POST['item_id'], $_POST['type'], $_SESSION['user']['id'])){
            return send_json(['success'=>true]);
        }else{
            send_json(['success'=>false, 'error'=>lang('main.could_not_save')]);
        }
    }

    public function remove_saved_item(){

        if(!isset($_POST['item_id']) || !isset($_POST['type']) || !in_array($_POST['type'], ['package', 'track']) || !is_numeric($_POST['item_id'])){
            show_404();
        }

        if(!isset($_SESSION['user'])){
            return send_json(['success'=>false, 'error'=>lang('main.to_save_should_login')]);
        }

        $main_model = new Main_model();
        if(!$main_model->can_be_save($_POST['item_id'], $_POST['type'])){
            show_404();
        }

        if($main_model->remove_saved_item($_POST['item_id'], $_POST['type'], $_SESSION['user']['id'])){
            return send_json(['success'=>true]);
        }else{
            send_json(['success'=>false, 'error'=>lang('main.could_not_remove')]);
        }
    }

}
