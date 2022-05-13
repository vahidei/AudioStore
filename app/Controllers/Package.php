<?php namespace App\Controllers;

use App\Models\Main_model;

class Package extends BaseController
{
    public function index()
    {
        return redirect()->to('package/list');
    }

    public function list($page=1)
    {
        $main_model = new Main_model();
        $data['limit'] = 10;
        $data['page'] = $page;
        $offset = ($page - 1)  *  $data['limit'];
        $data['list'] = $main_model->get_packages( $data['limit'], $offset);
        $data['packages_count'] = $main_model->packages_count();

        echo view('includes/header');
        echo view('package/list', ['data'=>$data]);
        echo view('includes/footer');
    }
    public function single($id){

        $main_model = new Main_model();
        $item = $main_model->get_single_package($id);
        if(!$item){
            show_404();
        }

        echo view('includes/light_header');
        echo view('package/single2', $item);
        echo view('includes/light_footer');
    }

    //--------------------------------------------------------------------

}
