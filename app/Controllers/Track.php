<?php namespace App\Controllers;

use App\Models\Main_model;

class Track extends BaseController
{
    public function index()
    {
        return redirect()->to('track/list');
    }

    public function list()
    {
        $data = [];
        $main_model = new Main_model();
        $data['categories'] = $main_model->get_categories();

        $data['tracks_count'] = $main_model->tracks_count();

        echo view('includes/header');
        echo view('track/list', ['data'=>$data, 'scripts'=>[base_url('public/js/tracks_list.js')]]);
        echo view('includes/footer');
    }

    public function single($id)
    {
        echo view('includes/header');
        echo view('track/single');
        echo view('includes/footer');
    }
    //--------------------------------------------------------------------

}
