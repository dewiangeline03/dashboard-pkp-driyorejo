<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Verified implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $current_url = current_url();
        if (session('userInfo')['verified'] == 'f') {
            if ($current_url !== base_url("/validate-password")) {
                return redirect()->to(base_url("/validate-password"));
            } 
        }else {
            if ($current_url === base_url("/validate-password")) {
                return redirect()->to(base_url("/user/home"));
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
