<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\AuthActivityModel;

class AuthActivityController extends Common
{
    protected $session;
    protected $authActivityModel;

    public function __construct()
    {
        $this->session           = session();
        $this->authActivityModel = new AuthActivityModel();
    }

    public function auth_activity_list_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Activity | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');

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
                'message' => 'No action log(s) found!'
            ]);
        }

        $html = '';
        $i = 1;

        foreach ($result->content as $activity) {

            if ($activity->actionLogMethod === 'POST') {
                $methodText  = 'POST';
                $methodClass = 'badge bg-warning rounded';
            } else if ($activity->actionLogMethod === 'GET') {
                $methodText  = 'GET';
                $methodClass = 'badge bg-success rounded';
            } else if ($activity->actionLogMethod === 'DELETE') {
                $methodText  = 'DELETE';
                $methodClass = 'badge bg-danger rounded';
            } else if ($activity->actionLogMethod === 'PUT') {
                $methodText  = 'PUT';
                $methodClass = 'badge bg-info rounded';
            } else {
                $methodText  = 'PATCH';
                $methodClass = 'badge bg-secondary rounded';
            }

            $html .= '
            <tr>
                <td>' . "#" . $i++ . '</td>
                <td>
                    <span class="' . $methodClass . '">' . $methodText . '</span>
                </td>
                <td>' . esc($activity->actionLogMessage) . '</td>
                <td>' . $activity->actionLogCreatedAt . '</td>
            </tr>
        ';
        }

        return $this->response->setJSON([
            'status' => true,
            'html'   => $html
        ]);
    }
}
