<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ErrorsController extends Controller
{
    protected $session;

    public function __construct()
    {
        $this->session           = session();
    }

    public function error404()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        return view('errors/custom_404_view');
    }
}
