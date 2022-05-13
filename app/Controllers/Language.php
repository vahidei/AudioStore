<?php

namespace App\Controllers;

use App\Controllers\MyController;

class Language extends MyController
{
    public function index()
    {
        unset($_COOKIE['lang']);
        setcookie('lang', null, -1, '/');

        $locale = $this->request->getLocale();

        $language = \Config\Services::language();
        $language->setLocale($locale);
        setcookie('lang', $locale, time() + 2592000, '/');
        $url = base_url();

        return redirect()->to($url);
    }
}