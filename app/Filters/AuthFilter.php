<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        $base_url = base_url();
        if (!$session->get('admin_logged_true')) {
            $uri = $request->getPath();
            // print_r($uri) ; die ; 
            if (!in_array($uri, ['admin', 'admin/login', 'admin/sing-out'])) {
                return redirect()->to($base_url . 'admin/login');
            }
            return;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}
