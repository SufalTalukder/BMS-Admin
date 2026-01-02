<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\AuthUserModel;

class AuthProfileController extends Common
{
    protected $session;
    protected $authUserModel;

    public function __construct()
    {
        $this->session          = session();
        $this->authUserModel    = new AuthUserModel();
    }

    public function auth_profile_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'My Profile Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_auth_profile/profile_view')
            . view('admin/template/footer');
    }
}
