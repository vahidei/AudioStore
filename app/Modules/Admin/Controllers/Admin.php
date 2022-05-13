<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Models\Main_model;
use CodeIgniter\HTTP\URI;

class Admin extends BaseController
{

    public function __construct()
    {

    }

    public function signin()
    {

        if (isset($_POST['submit'])) {
            $data = ['alert-class' => '', 'alert-message' => ''];
            if (empty($_POST['username']) || empty($_POST['username'])) {
                $data['alert-class'] = 'alert-warning';
                $data['alert-message'] = 'Please enter your username and password.';
                goto view;
            }

            $main_model = new Main_model();
            $_POST['password'] = admin_password_hash($_POST['password']);

            $admin = $main_model->login($_POST);
            print_r($admin);
            if ($admin) {
                $_SESSION['admin'] = $admin;
                return redirect()->to(admin_base_url('dashboard'));
            } else {
                $data['alert-class'] = 'alert-danger';
                $data['alert-message'] = 'Incorrect username or password.';
            }

        }

        view:
        echo view('App\Modules\Admin\Views\signin', ['data'=>$data]);
    }

    public function dashboard()
    {
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\dashboard');
        echo view('App\Modules\Admin\Views\includes\footer');
    }

    public function logout(){
        unset($_SESSION['admin']);
        return redirect()->to(admin_base_url('signin'));
    }

}