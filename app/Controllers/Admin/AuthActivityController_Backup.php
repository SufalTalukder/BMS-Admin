<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\AuthActivityModel;
use App\Models\Admin\AuthUserModel;
use App\Helpers\CustomHelper;

class AuthActivityController extends Common
{
    protected $session;
    protected $authActivityModel;
    protected $authUserModel;
    protected $customHelper;

    public function __construct()
    {
        $this->session           = session();
        $this->authActivityModel = new AuthActivityModel();
        $this->authUserModel     = new AuthUserModel();
        $this->customHelper      = new CustomHelper();
    }

    public function auth_activity_list_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Activity Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_auth_activity/activity_view')
            . view('admin/template/footer');
    }

    public function getAllAuthActivityAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->authActivityModel->getAllAuthActivityAJAX();
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No activitie(s) found!'
            ]);
        }

        $html = '';
        $i = 1;
        foreach ($result->content as $activity) {
            list($methodText, $methodClass) = $this->customHelper->getMethodDetails($activity->actionLogMethod);

            $html .=
                <<<HTML
                    <tr>
                        <td>#{$i}</td>
                        <td>
                            <span class="{$methodClass}">{$methodText}</span>
                        </td>
                        <td>$activity->actionLogMessage</td>
                        <td>{$this->customHelper->formatDateTime($activity->actionLogCreatedAt)}</td>
                    </tr>
                HTML;
            $i++;
        }

        return $this->response->setJSON([
            'status' => true,
            'html'   => $html
        ]);
    }
}
