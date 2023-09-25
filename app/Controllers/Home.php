<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $brandModel = model('BrandModel');

        $R['categories'] = $brandModel->findAll();

        return view('home',$R);
    }
}
