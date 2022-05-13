<?php

namespace App\Modules\Admin\Controllers;

use App\Controllers\BaseController;
use App\Modules\Admin\Models\Discount_model;

class Discount extends BaseController
{
    public function index()
    {
        return redirect()->to('discount/list');
    }

    public function list()
    {
        $discount_model = new Discount_model();

        $data['alert_class'] = '';
        $data['alert_message'] = '';

        if(isset($_POST['delete']) && !empty($_POST['deleteItems']) && is_array($_POST['deleteItems'])){
            if($discount_model->delete_rows(array_keys($_POST['deleteItems']), $_SESSION['admin']['id'])){
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = 'Selected discounts were successfully deleted.';
            }
        }

        view:
        $data['items'] = $discount_model->list($_SESSION['admin']['id']);
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\discount\list', ['data'=>$data]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }

    public function add()
    {
        $discount_model = new Discount_model();
        $data = [
            'alert-class' => '',
            'alert_message' => '',
            'selected_items' => ''
        ];
        $success = false;

        if (isset($_POST['submit'])) {

            $status = 'publish';

            if(!empty($_POST['items'])){
                $data['selected_items'] = $discount_model->items_detail($_POST['type'], $_POST['items']);
            }

            if (isset($_POST['saveindrafts']) && $_POST['saveindrafts'] == 'on') {
                $status = 'draft';
            }

            if (empty($_POST['title'])) {
                $data['alert_message'] .= '<li>Please enter a title.</li>';
            }
            if (empty($_POST['type'])) {
                $data['alert_message'] = '<li>Please select a type.</li>';
            }
            if (!is_array($_POST['items']) || empty($_POST['items'])) {
                $data['alert_message'] = '<li>Please select items.</li>';
            }
            if (empty($_POST['discount_type'])) {
                $data['alert_message'] .= '<li>Please enter a discount type.</li>';
            }
            if (empty($_POST['discount'])) {
                $data['alert_message'] = '<li>Please enter discount value.</li>';
            }
            if (empty($_POST['expire'])) {
                $data['alert_message'] = '<li>Please enter the number of days to expire.</li>';
            }

            if (!empty($data['alert_message'])) goto view;
            if (!empty($_FILES['photo']['name'])) {
                $photo = upload_media($_FILES['photo'], 'discounts/photos', false);
                if (!$photo) {
                    $data['alert_message'] = '<li>The photo could not be uploaded.</li>';
                    goto view;
                }
            }else{
                $photo = '';
            }

            if ($discount_model->add($_POST, $photo, $status, $_SESSION['admin']['id'])) {
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = 'Discount added successfully.';
                $success = true;
            } else {
                $data['alert_message'] = '<li>An error occurred while adding the discount.</li>';
            }
        }

        view:

        if (!empty($data['alert_message']) && !$success) {
            $data['alert_class'] = 'alert-danger';
            $data['alert_message'] = '<h6>The following errors were found:</h6><ul>' . $data['alert_message'] . '</ul>';
        }

        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\discount\add',['data' => $data, 'post' => $_POST]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }

    public function edit($id){
        $discount_model = new Discount_model();
        $data = [
            'alert_class' => '',
            'alert_message' => ''
        ];
        $success = false;
        $photo = '';
        if(isset($_POST['submit'])){
            $status = 'publish';

            if (isset($_POST['saveindrafts']) && $_POST['saveindrafts'] == 'on') {
                $status = 'draft';
            }

            if (empty($_POST['title'])) {
                $data['alert_message'] .= '<li>Please enter a title.</li>';
            }
            if (empty($_POST['type'])) {
                $data['alert_message'] = '<li>Please select a type.</li>';
            }
            if (!is_array($_POST['items']) || empty($_POST['items'])) {
                $data['alert_message'] = '<li>Please select items.</li>';
            }
            if (empty($_POST['discount_type'])) {
                $data['alert_message'] .= '<li>Please enter a discount type.</li>';
            }
            if (empty($_POST['discount'])) {
                $data['alert_message'] = '<li>Please enter discount value.</li>';
            }
            if (empty($_POST['expire'])) {
                $data['alert_message'] = '<li>Please enter the number of days to expire.</li>';
            }

            if (!empty($data['alert_message'])) goto view;

            if (!empty($_FILES['photo']['name'])) {
                $photo = upload_media($_FILES['photo'], 'discounts/photos', false);
                if (!$photo) {
                    $data['alert_message'] = '<li>The photo could not be uploaded.</li>';
                    goto view;
                }
            }

            if($discount_model->edit($id, $_POST, $photo, $status, $_SESSION['admin']['id'])){
                $success = true;
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = 'Discount edited successfully.';
                $_POST = [];
            }else{
                $data['alert_message'] = '<li>An error occurred while editing the discount.</li>';
            }

        }
        view:
        $item = $discount_model->get($id, $_SESSION['admin']['id']);
        $data['selected_items'] = $discount_model->selected_items($id, $item['type']);

        if(!empty($data['alert_message'])){
            if(!$success){
                $data['alert_class'] = 'alert-danger';
                $data['alert_message'] = '<h6>The following errors were found:</h6><ul>'.$data['alert_message'].'</ul>';
            }
        }
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\discount\edit', ['data'=>$data, 'item'=>$item]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }
}