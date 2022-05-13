<?php namespace App\Controllers;

use App\Models\Main_model;
use App\Libraries\Pay;
use App\Models\User_model;

class Buy extends BaseController
{

    public function __construct()
    {
        helper('funcs');
        @session_start();

        if ((!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) && !isset($_SESSION['last_order'])) {
            show_404();
        }
    }

    public function not_logged_in()
    {
        $user_model = new User_model();

        if (isset($_POST['submit'])) {

            if (empty($_POST['email']) || !valid_email($_POST['email'])) {
                goto view;
            }

            $usr = $user_model->get_user_by_email($_POST['email']);
            if (!empty($usr)) {
                $_SESSION['email_force'] = $_POST['email'];
                return redirect()->to(base_url('user/signin?shopping=true'));
            } else {
                $_SESSION['cart']['user_email'] = $_POST['email'];
                return redirect()->to(base_url('buy/final'));
            }

        }

        view:
        echo view('includes/light_header');
        echo view('buy/not_logged_in');
        echo view('includes/light_footer');
    }


    public function final()
    {
        if ((!isset($_SESSION['user']) && !isset($_SESSION['cart']['user_email'])) || !isset($_SESSION['cart'])) {
            show_404();
        }

        $main_model = new Main_model();
        $data = [];

        clearCartcookie();

        if(isset($_GET['remove']) && is_numeric($_GET['remove'])
            && isset($_GET['type']) && in_array($_GET['type'], ['track', 'package'])){
            if($_GET['type'] == 'package'){
                if (($key = array_search($_GET['remove'], $_SESSION['cart']['selected_items']['packages'])) !== false) {
                    unset($_SESSION['cart']['selected_items']['tracks'][$key]);
                }
            }elseif($_GET['type'] == 'track'){
                if (($key = array_search($_GET['remove'], $_SESSION['cart']['selected_items']['tracks'])) !== false) {
                    unset($_SESSION['cart']['selected_items']['tracks'][$key]);
                }
            }
        }

        if(empty($_SESSION['cart']['selected_items']['packages'])
         && empty($_SESSION['cart']['selected_items']['tracks'])){
           // unset($_SESSION['cart']);
            unset($_SESSION['fromCart']);
            $data['is_empty'] = true;
            goto view;
        }


        $data['packages'] = $main_model->cart_selected_packages($_SESSION['cart']['selected_items']['packages']);
        $data['tracks'] = $main_model->cart_selected_tracks($_SESSION['cart']['selected_items']['tracks']);

        $pack_ids = array_column($data['packages'], 'id');
        $track_ids = array_column($data['tracks'], 'id');

        $price = 0;

        foreach ($data['packages'] as $key => $item) {
            $p = $item['price'];
            if (!empty($item['discount'])) {
                $p = calcDiscount($item['price'], $item['discount'], $item['discount_type']);
            }
            if($p < 1){
                unset($data['packages'][$key]);
            }else{

                if($item['buy_limit'] !== '-1'){
                    if(intval($item['buy_limit']) - $item['sold'] <= 0){
                        unset($data['packages'][$key]);
                    }
                }else{
                    $data['packages'][$key]['final_price'] = $p;
                    $price += $p;
                }

            }
        }
        foreach ($data['tracks'] as $key => $item) {
            $p = $item['price'];
            if (!empty($item['discount'])) {
                $p = calcDiscount($item['price'], $item['discount'], $item['discount_type']);
            }
            if($p < 1){
                unset($data['tracks'][$key]);
            }else{
                if($item['buy_limit'] !== '-1'){
                    if(intval($item['buy_limit']) - $item['sold'] <= 0){
                        unset($data['tracks'][$key]);
                    }
                }else{
                    $data['tracks'][$key]['final_price'] = $p;
                    $price += $p;
                }
            }
        }

        if(empty($data['packages']) && empty($data['tracks'])){
            unset($_SESSION['cart']);
            show_404();
        }

        if (isset($_POST['submit'])) {

            if ((isset($_POST['packages']) && $_POST['packages'] !== $pack_ids)
                || (isset($_POST['tracks']) && $_POST['tracks'] !== $track_ids)
            ) {
                goto view;
            }

            if (isset($_SESSION['cart']['user_email'])) {
                $uid = $_SESSION['cart']['user_email'];
                $utype = 'guest';
            } else {
                $uid = $_SESSION['user']['id'];
                $utype = 'user';
            }


            $gateway = new Pay();
            $result = $gateway->send($price, base_url('/buy/callback'));
            $result = json_decode($result);
            if ($result->status) {
                if ($main_model->addPurchase($uid, $utype, $result->token, $price, $data)) {
                    $go = "https://pay.ir/pg/$result->token";
                    header("Location: $go");
                    die();
                } else {
                    $data['alert'] = lang('main.make_purchase_error');
                }
            } else {
                $data['alert'] = lang('main.make_purchase_error');
            }


        }


        view:
        echo view('includes/light_header');
        echo view('buy/final', ['data' => $data]);
        echo view('includes/light_footer');
    }


    public function callback()
    {
        if (!isset($_GET['status']) && !isset($_GET['token'])) {
            show_404();
        }

       /* if($_GET['status'] == 0){
            $data['alert'] = 'There is an error making the purchase. code: 3';
            goto view;
        }*/

        $data = [];
        $main_model = new Main_model();

        if (isset($_SESSION['cart']['user_email'])) {
            $uid = $_SESSION['cart']['user_email'];
            $utype = 'guest';
        } else {
            $uid = $_SESSION['user']['id'];
            $utype = 'user';
        }

        $purchase = $main_model->checkPurchase($uid, $utype, $_GET['token']);
        if(!$purchase){
            show_404();
        }

        $gateway = new Pay();
        $result = $gateway->verify($_GET['token']);
        $result = json_decode($result);
        if($result->status == 1){
            if($main_model->verifyPurchase($uid, $utype, $_GET['token'], $result->transId, 'success')
                && $main_model->create_code_purchase_items($purchase['id'])
            ){
                $_SESSION['last_order'] = ['uid'=>$uid, 'trans_id'=>$result->transId, 'purchase_id'=>$purchase['id']];
                unset($_SESSION['cart']);
                return redirect()->to(base_url('buy/last_order'));
            }else{
                $data['alert'] = lang('main.recording_purchase_error');
                $data['alert'] .= '<br/><h5>'.lang('main.transaction_number').': '.$result->transId.'</h5>';
            }
        }else{
            if($main_model->verifyPurchase($uid, $utype, $_GET['token'],'' , 'failed')){
                $data['alert'] = lang('main.payment_error');
            }
        }

        view:
        echo view('includes/light_header');
        echo view('buy/callback', ['data' => $data]);
        echo view('includes/light_footer');

    }

    public function last_order(){
        if(!isset($_SESSION['last_order'])){
            show_404();
        }

        $main_model = new Main_model();
        $data = [];

        $d = $main_model->get_purchased_items($_SESSION['last_order']['purchase_id']);

        foreach($d['purchased_items'] as $key => $item){
            if($item['type'] == 'track'){
                foreach($d['tracks'] as $value){
                    if($value['id'] == $item['item_id']){
                        $data[] = [
                            'title'=>$value['title'],
                            'title_fa'=>$value['title_fa'],
                            'type'=>$item['type'],
                            'final_price'=>$item['final_price'],
                            'code'=>$item['code']
                        ];
                    }
                }
            }
            if($item['type'] == 'package'){
                foreach($d['packages'] as $value){
                    if($value['id'] == $item['item_id']){
                        $data[] = [
                            'title'=>$value['title'],
                            'title_fa'=>$value['title_fa'],
                            'type'=>$item['type'],
                            'final_price'=>$item['final_price'],
                            'code'=>$item['code']
                        ];
                    }
                }
            }
        }

        view:
        echo view('includes/header');
        echo view('buy/last_order', ['data' => $data]);
        echo view('includes/footer');
    }

    //--------------------------------------------------------------------

}
