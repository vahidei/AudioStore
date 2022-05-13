<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Controllers\BaseController;
use App\Modules\Admin\Models\Track_model;
use App\Modules\Admin\Models\Package_model;
use App\Libraries\ImageSampler;

class Package extends BaseController
{
    public function index()
    {
        return redirect()->to('package/list');
    }

    public function ajaxItems()
    {
        if(!isset($_GET['value']) || empty(trim($_GET['value']))){
            return $this->response->setJSON([]);
        }
        $package_model = new Package_model();
        $packages = $package_model->ajaxItems($_GET['value'], isset($_GET['discount']));
        return $this->response->setJSON($packages);
    }

    public function list()
    {
        $package_model = new Package_model();

        $data['alert_class'] = '';
        $data['alert_message'] = '';

        if(isset($_POST['delete']) && !empty($_POST['deleteItems']) && is_array($_POST['deleteItems'])){
            if($package_model->delete_rows(array_keys($_POST['deleteItems']), $_SESSION['admin']['id'])){
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = 'Selected packages were successfully deleted.';
            }
        }

        view:
        $data['items'] = $package_model->list($_SESSION['admin']['id']);
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\package\list', ['data'=>$data]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }

    public function add()
    {
        $track_model = new Track_model();
        $data = [
            'alert-class' => '',
            'alert_message' => ''
        ];
        $success = false;

        if (isset($_POST['submit'])) {

            $status = 'publish';

            if (isset($_POST['saveindrafts']) && $_POST['saveindrafts'] == 'on') {
                $status = 'draft';
            }

            if (!is_array($_POST['tracks']) || count($_POST['tracks']) < 2) {
                $data['alert_message'] = '<li>Please select at least two tracks.</li>';
            }

            if (empty($_POST['title'])) {
                $data['alert_message'] .= '<li>Please enter a title.</li>';
            }
            if (empty($_POST['short_desc'])) {
                $data['alert_message'] .= '<li>Please enter a short description.</li>';
            }
            if (empty($_FILES['cover']['name'])) {
                $data['alert_message'] .= '<li>Please upload a cover photo.</li>';
            }
            if (empty($_POST['price'])) {
                $data['alert_message'] .= '<li>Please enter a price.</li>';
            }
            if (isset($_POST['buy_limit']) && (!is_numeric($_POST['buy_limit']) || (empty($_POST['buy_limit']) && $_POST['buy_limit'] !== '0'))) {
                $data['alert_message'] .= '<li>Please enter the buy limit correctly.</li>';
            }

            if (!isset($_POST['buy_limit']) || empty($_POST['buy_limit'])) {
                $_POST['buy_limit'] = '-1';
            }

            if (!empty($data['alert_message'])) goto view;
            $cover = upload_media($_FILES['cover'], 'packages/covers', false);
            if (!$cover) {
                $data['alert_message'] = '<li>The cover photo could not be uploaded.</li>';
                goto view;
            }


            $sampler = new ImageSampler(base_url()."/public/demos/packages/covers/".$cover['code']);
            $sampler->set_steps(2);
            $sampler->init();
            $_POST['most_used_colors'] = json_encode($sampler->sample());

            $package_model = new Package_model();

            if ($package_model->add($_POST, $cover, $status, $_SESSION['admin']['id'])) {
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = 'Package added successfully.';
                $success = true;
            } else {
                $data['alert_message'] = '<li>An error occurred while adding the package.</li>';
            }
        }

        view:
        $data['tracks'] = $track_model->list($_SESSION['admin']['id']);

        if (!empty($data['alert_message']) && !$success) {
            $data['alert_class'] = 'alert-danger';
            $data['alert_message'] = '<h6>The following errors were found:</h6><ul>' . $data['alert_message'] . '</ul>';
        }

        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\package\add', ['data' => $data, 'post' => $_POST]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }

    public function edit($id){
        $package_model = new Package_model();
        $data = [
            'alert_class' => '',
            'alert_message' => ''
        ];
        $success = false;
        $cover = '';
        if(isset($_POST['submit'])){
            $status = 'publish';
            $_POST['most_used_colors'] = '';
            if (isset($_POST['saveindrafts']) && $_POST['saveindrafts'] == 'on') {
                $status = 'draft';
            }

            if (!is_array($_POST['tracks']) || count($_POST['tracks']) < 2) {
                $data['alert_message'] = '<li>Please select at least two tracks.</li>';
            }

            if (empty($_POST['title'])) {
                $data['alert_message'] .= '<li>Please enter a title.</li>';
            }
            if (empty($_POST['short_desc'])) {
                $data['alert_message'] .= '<li>Please enter a short description.</li>';
            }
            if (empty($_POST['price'])) {
                $data['alert_message'] .= '<li>Please enter a price.</li>';
            }
            if (isset($_POST['buy_limit']) && (!is_numeric($_POST['buy_limit']) || (empty($_POST['buy_limit']) && $_POST['buy_limit'] !== '0'))) {
                $data['alert_message'] .= '<li>Please enter the buy limit correctly.</li>';
            }

            if (!isset($_POST['buy_limit']) || empty($_POST['buy_limit'])) {
                $_POST['buy_limit'] = '-1';
            }

            if (!empty($data['alert_message'])) goto view;

            if (!empty($_FILES['cover']['name'])) {
                $cover = upload_media($_FILES['cover'], 'packages/covers', false);
                if (!$cover) {
                    $data['alert_message'] = '<li>The cover photo could not be uploaded.</li>';
                    goto view;
                }
                $sampler = new ImageSampler(base_url()."/public/demos/packages/covers/".$cover['code']);
                $sampler->set_steps(2);
                $sampler->init();
                $_POST['most_used_colors'] = json_encode($sampler->sample());
            }

            if($package_model->edit($id, $_POST, $cover, $status, $_SESSION['admin']['id'])){
                $success = true;
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = 'Package edited successfully.';
                $_POST = [];
            }else{
                $data['alert_message'] = '<li>An error occurred while editing the package.</li>';
            }

        }
        view:
        $item = $package_model->get($id, $_SESSION['admin']['id']);
        $data['selected_tracks'] = $package_model->selected_tracks($id);
        if(!empty($data['alert_message'])){
            if(!$success){
                $data['alert_class'] = 'alert-danger';
                $data['alert_message'] = '<h6>The following errors were found:</h6><ul>'.$data['alert_message'].'</ul>';
            }
        }
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\package\edit', ['data'=>$data, 'item'=>$item]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }
}