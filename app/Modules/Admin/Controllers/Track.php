<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Models\Track_model;
use App\Modules\Admin\Models\Category_model;
use App\Modules\Admin\Controllers\BaseController;


class Track extends BaseController
{

    public function index()
    {
        return redirect()->to('track/list');
    }

    public function ajaxItems()
    {
        if(!isset($_GET['value']) || empty(trim($_GET['value']))){
            return $this->response->setJSON([]);
        }

        $track_model = new Track_model();
        $tracks = $track_model->ajaxItems($_GET['value'], isset($_GET['discount']), $_SESSION['admin']['id']);

        return $this->response->setJSON($tracks);
    }

    public function list()
    {
        $track_model = new Track_model();

        $data['alert_class'] = '';
        $data['alert_message'] = '';

        if(isset($_POST['delete']) && !empty($_POST['deleteItems']) && is_array($_POST['deleteItems'])){
            $ids = array_keys($_POST['deleteItems']);
            $tids = $track_model->get_packaged_tracks($ids);
            $ids = array_diff($ids, $tids);

            if(!empty($ids)){
                if($track_model->delete_rows($ids, $_SESSION['admin']['id'])){
                    $data['alert_class'] = 'alert-success';
                    $data['alert_message'] = 'Selected tracks were successfully deleted.';
                }
            }else{
                $data['alert_class'] = 'alert-warning';
                $data['alert_message'] = 'Cannot be deleted because this item(s) is/are packaged.';
            }

        }

        view:
        $data['items'] = $track_model->list($_SESSION['admin']['id']);
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\track\list', ['data'=>$data]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }

    public function add()
    {
        $track_model = new Track_model();
        $cat_model = new Category_model();
        $category_list = $cat_model->list();
        $success = false;

        $data = [
            'category_list' => $category_list,
            'alert_class' => '',
            'alert_message' => ''
        ];
        if (isset($_POST['submit'])) {
            if (!in_array($_POST['status'], ['publish', 'scheduling', 'draft', 'only_package'])) {
                $data['alert_message'] .= '<li>Please select a status.</li>';
            }
            if (empty($_POST['title'])) {
                $data['alert_message'] .= '<li>Please enter a title.</li>';
            }
            if (empty($_FILES['original_file']['name'])) {
                $data['alert_message'] .= '<li>Please choose an original file.</li>';
            }
            if (empty($_FILES['demo_file']['name'])) {
                $data['alert_message'] .= '<li>Please choose a demo file.</li>';
            }
            if (empty($_POST['price'])) {
                $data['alert_message'] .= '<li>Please enter a price.</li>';
            }
            if (isset($_POST['buy_limit']) && (!is_numeric($_POST['buy_limit']) || (empty($_POST['buy_limit']) && $_POST['buy_limit'] !== '0'))) {
                $data['alert_message'] .= '<li>Please enter the buy limit correctly.</li>';
            }
            if (empty($_POST['category'])) {
                $data['alert_message'] .= '<li>Please select a category.</li>';
            }
            if($_POST['status'] == 'scheduling'){
                if(empty($_POST['action_time']) || empty($_POST['action_type']) || !in_array($_POST['action_type'], ['draft', 'publish'])){
                    $data['alert_message'] .= '<li>Please select a date and action type.</li>';
                }
            }

            if (!empty($data['alert_message'])) goto view;

            if (!isset($_POST['buy_limit']) || empty($_POST['buy_limit'])) {
                $_POST['buy_limit'] = '-1';
            }

            if($_POST['status'] == 'scheduling'){
                $_POST['action_time'] = date('Y-m-d H:i:00', strtotime($_POST['action_time']));
            }


            $original_file = upload_media($_FILES['original_file'], 'tracks', true);
            if (!$original_file) {
                $data['alert_message'] .= '<li>The original file could not be uploaded.</li>';
                goto view;
            }
            $demo_file = upload_media($_FILES['demo_file'], 'tracks', false);
            if (!$demo_file) {
                $data['alert_message'] .= '<li>The demo file could not be uploaded.</li>';
                if ($original_file) {
                    unlink(FCPATH . 'originals\tracks\\' . $original_file);
                }
                goto view;
            }

            if (!empty($_POST['original_file_duration'])) {
                $original_file['duration'] = $_POST['original_file_duration'];
            }
            if (!empty($_POST['demo_file_duration'])) {
                $demo_file['duration'] = $_POST['demo_file_duration'];
            }

            if ($track_model->add($_POST, $original_file, $demo_file, $_SESSION['admin']['id'])) {
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = 'Track added successfully.';
                $success = true;
            } else {
                $data['alert_message'] = '<li>An error occurred while adding the track.</li>';
            }

        }

        view:

        if (!empty($data['alert_message']) && !$success) {
            $data['alert_class'] = 'alert-danger';
            $data['alert_message'] = '<h6>The following errors were found:</h6><ul>' . $data['alert_message'] . '</ul>';
        }

        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\track\add', ['data' => $data, 'post' => $_POST]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }

    public function edit($id){
        $track_model = new Track_model();
        $cat_model = new Category_model();
        $category_list = $cat_model->list();

        $data = [
            'category_list' => $category_list,
            'alert_class' => '',
            'alert_message' => ''
        ];
        $success = false;
        $demo_file = '';
        $original_file = '';
        if(isset($_POST['submit'])){
            if (!in_array($_POST['status'], ['publish', 'draft', 'scheduling', 'only_package'])) {
                $data['alert_message'] .= '<li>Please select a status.</li>';
            }
            if (empty($_POST['title'])) {
                $data['alert_message'] .= '<li>Please enter a title.</li>';
            }
            if (empty($_POST['price'])) {
                $data['alert_message'] .= '<li>Please enter a price.</li>';
            }
            if (isset($_POST['buy_limit']) && (!is_numeric($_POST['buy_limit']) || (empty($_POST['buy_limit']) && $_POST['buy_limit'] !== '0'))) {
                $data['alert_message'] .= '<li>Please enter the buy limit correctly.</li>';
            }
            if (empty($_POST['category'])) {
                $data['alert_message'] .= '<li>Please select a category.</li>';
            }
            if($_POST['status'] == 'scheduling'){
                if(empty($_POST['action_time']) || empty($_POST['action_type']) || !in_array($_POST['action_type'], ['draft', 'publish'])){
                    $data['alert_message'] .= '<li>Please select a date and action type.</li>';
                }
            }

            if (!empty($data['alert_message'])) goto view;

            if (!isset($_POST['buy_limit']) || empty($_POST['buy_limit'])) {
                $_POST['buy_limit'] = '-1';
            }

            if($_POST['status'] == 'scheduling'){
                $_POST['action_time'] = date('Y-m-d H:i:00', strtotime($_POST['action_time']));
            }

            if (!empty($_FILES['original_file']['name'])) {
                $original_file = upload_media($_FILES['original_file'], 'tracks', true);
                if (!$original_file) {
                    $data['alert_message'] .= '<li>The original file could not be uploaded.</li>';
                    goto view;
                }
            }

            if (!empty($_FILES['demo_file']['name'])) {
                $demo_file = upload_media($_FILES['demo_file'], 'tracks', false);
                if (!$demo_file) {
                    $data['alert_message'] .= '<li>The demo file could not be uploaded.</li>';
                    if ($original_file) {
                        unlink(FCPATH . 'originals\tracks\\' . $original_file);
                    }
                    goto view;
                }
            }

            if($track_model->edit($id, $_POST, $original_file, $demo_file, $_SESSION['admin']['id'])){
                $success = true;
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = 'Track edited successfully.';
                $_POST = [];
            }else{
                $data['alert_message'] = '<li>An error occurred while editing the track.</li>';
            }

        }
        view:
        $item = $track_model->get($id, $_SESSION['admin']['id']);
        if(!empty($data['alert_message'])){
            if(!$success){
                $data['alert_class'] = 'alert-danger';
                $data['alert_message'] = '<h6>The following errors were found:</h6><ul>'.$data['alert_message'].'</ul>';
            }
        }
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\track\edit', ['data'=>$data, 'item'=>$item]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }
}