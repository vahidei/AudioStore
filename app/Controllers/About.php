<?php namespace App\Controllers;

class About extends BaseController
{
    public function index()
    {

        echo view('includes/header');
        echo view('about');
        echo view('includes/footer');

    }

    //--------------------------------------------------------------------

}
