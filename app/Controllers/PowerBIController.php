<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PowerBIController extends BaseController
{
    public function index()
    {
        $data =[
            'title' => 'Home'
        ] ;
        return view('user/home', $data);
    }
}
