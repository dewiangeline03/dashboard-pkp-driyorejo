<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class NoAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
       if(session()->get('userInfo')){
        return redirect()->to(base_url("/user/home"));
       }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}