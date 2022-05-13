<?php
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;

class User extends BaseController {
    public function index() {
        return redirect()->to('user/list');
    }

    public function list(){
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\user\list');
        echo view('App\Modules\Admin\Views\includes\footer');
    }

}