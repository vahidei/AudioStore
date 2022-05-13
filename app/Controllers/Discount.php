<?php namespace App\Controllers;

use App\Models\Main_model;

class Discount extends BaseController
{
    public function index()
    {
        return redirect()->to('discount/list');
    }

    public function single($id){
        if(!is_numeric($id)){
            show_404();
        }
        $main_model = new Main_model();
        $discount = $main_model->get_discount_items($id);
        if(!$discount){
            show_404();
        }
        echo view('includes/header');
        echo view('discount/single', $discount);
        echo view('includes/footer');
    }

    public function list()
    {
        $main_model = new Main_model();
        $data['list'] = $main_model->get_discounts();

        echo view('includes/header');
        echo view('discount/list', ['data'=>$data]);
        echo view('includes/footer');
    }

    //--------------------------------------------------------------------

}
