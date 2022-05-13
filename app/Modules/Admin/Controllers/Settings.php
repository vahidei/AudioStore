<?php
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;
use App\Modules\Admin\Models\Main_model;

class Settings extends BaseController {
    public function index() {
        return redirect()->to('user/list');
    }

    public function list(){
        $main_model = new Main_model();
        $data = [
            'alert-class'=>'',
            'alert-message'=>''
        ];
        if(isset($_POST['submit'])){
            if(!isset($_POST['key']) || !isset($_POST['value'])){
                $data['alert-class'] = 'alert-warning';
                $data['alert-message'] = 'Wrong inputs!';
                goto view;
            }
            if($main_model->set_setting($_POST['key'], $_POST['value'])){
                $data['alert-class'] = 'alert-success';
                $data['alert-message'] = 'Setting has been updated successfuly.';
            }else{
                $data['alert-class'] = 'alert-danger';
                $data['alert-message'] = 'There is an error updating settings.';
            }
        }

        view:
        $data['settings'] = $main_model->get_settings();
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\user\list', ['data'=>$data]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }

}