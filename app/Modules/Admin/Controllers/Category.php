<?php
namespace App\Modules\Admin\Controllers;
use App\Controllers\BaseController;
use App\Modules\Admin\Models\Category_model;

class Category extends BaseController {
    public function index() {
        return redirect()->to('category/list');
    }

    public function list(){
        $cat_model = new Category_model();

        $data['alert_class'] = '';
        $data['alert_message'] = '';

        if(isset($_POST['delete']) && !empty($_POST['deleteItems']) && is_array($_POST['deleteItems'])){
            if($cat_model->delete_rows(array_keys($_POST['deleteItems']))){
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = 'Selected categories were successfully deleted.';
            }
        }

        view:
        $data['items'] = $cat_model->list();
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\category\list', ['data'=>$data]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }
    public function add(){
        $data = [
            'alert_class' => '',
            'alert_message'=>''
        ];
        $success = false;
        if(isset($_POST['submit'])){
            if(empty($_POST['title'])){
                $data['alert_message'] = '<li>Please enter a title.</li>';
                goto view;
            }
            if(empty($_POST['color'])){
                $_POST['color'] = '#6c757d';
            }

            $cat_model = new Category_model();
            if($cat_model->add($_POST['title'], $_POST['title_fa'], $_POST['color'])){
                $success = true;
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = 'Category added successfully.';
                $_POST = [];
            }else{
                $data['alert_message'] = '<li>An error occurred while adding the category.</li>';
            }
        }

        view:
        if(!empty($data['alert_message'])){
            if(!$success){
                $data['alert_class'] = 'alert-danger';
                $data['alert_message'] = '<h6>The following errors were found:</h6><ul>'.$data['alert_message'].'</ul>';
            }
        }
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\category\add', ['data'=>$data, 'post'=>$_POST]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }
    public function edit($id){
        $cat_model = new Category_model();

        $data = [
            'alert_class' => '',
            'alert_message'=>''
        ];
        $success = false;
        if(isset($_POST['submit'])){
            if(empty($_POST['title'])){
                $data['alert_message'] = '<li>Please enter a title.</li>';
                goto view;
            }
            if(empty($_POST['color'])){
                $_POST['color'] = '#6c757d';
            }


            if($cat_model->edit($id, $_POST['title'], $_POST['title_fa'], $_POST['color'])){
                $success = true;
                $data['alert_class'] = 'alert-success';
                $data['alert_message'] = 'Category edited successfully.';
                $_POST = [];
            }else{
                $data['alert_message'] = '<li>An error occurred while editing the category.</li>';
            }
        }
        view:
        $item = $cat_model->get($id);
        if(!empty($data['alert_message'])){
            if(!$success){
                $data['alert_class'] = 'alert-danger';
                $data['alert_message'] = '<h6>The following errors were found:</h6><ul>'.$data['alert_message'].'</ul>';
            }
        }
        echo view('App\Modules\Admin\Views\includes\header');
        echo view('App\Modules\Admin\Views\includes\sidebar');
        echo view('App\Modules\Admin\Views\category\edit', ['data'=>$data, 'item'=>$item]);
        echo view('App\Modules\Admin\Views\includes\footer');
    }
}