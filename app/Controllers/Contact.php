<?php namespace App\Controllers;

class Contact extends BaseController
{
    public function index()
    {

        echo view('includes/header');
        echo view('contact');
        echo view('includes/footer');

    }

    //--------------------------------------------------------------------

}
