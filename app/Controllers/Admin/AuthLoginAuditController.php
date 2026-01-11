<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\AuthLoginAuditModel;
use App\Models\Admin\AuthUserModel;
use App\Helpers\CustomHelper;

class AuthLoginAuditController extends Common
{
    protected $session;
    protected $authLoginAuditModel;
    protected $authUserModel;
    protected $customHelper;

    public function __construct()
    {
        $this->session              = session();
        $this->authLoginAuditModel  = new AuthLoginAuditModel();
        $this->authUserModel        = new AuthUserModel();
        $this->customHelper         = new CustomHelper();
    }

    public function login_audit_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Track System Activity | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_auth_login_audit/auth_login_audit_view')
            . view('admin/template/footer');
    }

    public function getAllLoginAuditsAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->authLoginAuditModel->getAllLoginAuditsAJAX();
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'No login audit log(s) found!'
            ]);
        }

        $html = '';
        $i = 1;
        foreach ($result->content as $eachLoginAudit) {

            $fullname          = esc($eachLoginAudit->authUserInfo->authUserName ?? '-');
            $ipAddress         = esc($eachLoginAudit->ipAddress ?? '-');
            $userAgent         = esc($eachLoginAudit->userAgent ?? '-');
            $browser           = esc($eachLoginAudit->browser ?? '-');
            $browserVersion    = esc($eachLoginAudit->browserVersion ?? '-');
            $operatingSystem   = esc($eachLoginAudit->operatingSystem ?? '-');
            $osVersion         = esc($eachLoginAudit->osVersion ?? '-');
            $deviceType        = esc($eachLoginAudit->deviceType ?? '-');
            $deviceModel       = esc($eachLoginAudit->deviceModel ?? '-');

            $possibleIncognito = esc(
                !empty($eachLoginAudit->possibleIncognito) && ($eachLoginAudit->possibleIncognito == 0) ? 'NO' : 'YES'
            );

            $loginStatus   = esc($eachLoginAudit->loginStatus ?? '-');
            $authMethod    = esc($eachLoginAudit->authMethod ?? '-');
            $failureReason = esc($eachLoginAudit->failureReason ?? '-');
            $sessionId     = esc($eachLoginAudit->sessionId ?? '-');
            $referrerUrl   = esc($eachLoginAudit->referrerUrl ?? '-');

            $loginTime = esc(
                $this->customHelper->formatDateTime($eachLoginAudit->loginTime ?? '-')
            );

            $createdAt = esc(
                $this->customHelper->formatDateTime($eachLoginAudit->createdAt ?? '-')
            );

            $html .=
                <<<HTML
                    <tr>
                        <td>#{$i}</td>
                        <td>{$ipAddress}</td>
                        <td>{$userAgent}</td>
                        <td>{$browser}</td>
                        <td>{$browserVersion}</td>
                        <td>{$operatingSystem}</td>
                        <td>{$osVersion}</td>
                        <td>{$deviceType}</td>
                        <td>{$deviceModel}</td>
                        <td>{$possibleIncognito}</td>
                        <td>{$loginStatus}</td>
                        <td>{$authMethod}</td>
                        <td>{$failureReason}</td>
                        <td>{$sessionId}</td>
                        <td>{$referrerUrl}</td>
                        <td>{$loginTime}</td>
                        <td>{$fullname}</td>
                        <td>{$createdAt}</td>
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
