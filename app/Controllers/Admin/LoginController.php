<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Controllers\Admin\Common;
use App\Models\Admin\LoginModel;

class LoginController extends Common
{
    protected $session;
    protected $loginModel;

    public function __construct()
    {
        $this->session    = session();
        $this->loginModel = new LoginModel();
    }

    public function login_view()
    {
        if ($this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/activity-service');
        }

        $data['meta_title'] = 'Login | ' . PROJECT_NAME;

        return view('admin/template/header', $data)
            . view('admin/manage_auth_login/auth_login_view')
            . view('admin/template/footer');
    }

    public function submitLoginAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        return $this->response->setJSON(
            $this->loginModel->submitLoginAJAX($email, $password)
        );
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/admin/login');
    }
}
