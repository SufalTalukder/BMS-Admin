<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\AuthUserModel;

class AuthUserController extends Common
{
    protected $session;
    protected $authUserModel;

    public function __construct()
    {
        $this->session          = session();
        $this->authUserModel    = new AuthUserModel();
    }

    public function auth_user_list_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Auth Users Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_auth_users/auth_users_view')
            . view('admin/template/footer');
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

    public function addAuthUserAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $imageFile = $this->request->getFile('imageFile');

        $imageBase64 = null;
        if ($imageFile && $imageFile->isValid()) {
            $imageBase64 = base64_encode(
                file_get_contents($imageFile->getTempName())
            );
        }

        $data = [
            'authUserName'         => trim($this->request->getPost('authName')),
            'authUserEmailAddress' => trim($this->request->getPost('authEmail')),
            'authUserPhoneNumber'  => trim($this->request->getPost('phoneNumber')),
            'authUserPassword'     => $this->request->getPost('password'),
            'authUserType'         => $this->request->getPost('authType'),
            'authUserActive'       => $this->request->getPost('authActive'),
            'authUserImage'        => $imageBase64
        ];

        return $this->response->setJSON(
            $this->authUserModel->addAuthUserAJAX($data)
        );
    }

    public function getAllAuthUsersAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->authUserModel->getAllAuthUsersAJAX();

        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No auth users found!'
            ]);
        }

        $html = '';
        $i = 1;

        foreach ($result->content as $eachAuthUser) {

            $img = !empty($eachAuthUser->authUserImage)
                ? base_url($eachAuthUser->authUserImage)
                : base_url('assets/img/admin.jfif');

            if ($eachAuthUser->authUserType === 'SUPER_ADMIN') {
                $typeText  = 'Super Admin';
                $typeClass = 'badge bg-success rounded';
            } else {
                $typeText  = 'Admin';
                $typeClass = 'badge bg-secondary rounded';
            }

            if ($eachAuthUser->authUserActive === 'YES') {
                $statusText  = 'Yes';
                $statusClass = 'badge bg-primary rounded';
            } else {
                $statusText  = 'No';
                $statusClass = 'badge bg-warning rounded';
            }

            $html .= '
            <tr>
                <td>' . "#" . $i++ . '</td>
                <td>
                    <a href="' . $img . '" target="_blank">
                        <img src="' . $img . '" style="height:60px;width:90px;">
                    </a>
                </td>
                <td>' . esc($eachAuthUser->authUserName) . '</td>
                <td>' . esc($eachAuthUser->authUserEmailAddress) . '</td>
                <td>' . esc($eachAuthUser->authUserPhoneNumber) . '</td>
                <td>' . $eachAuthUser->authUserCreatedAt . '</td>
                <td>' . $eachAuthUser->authUserUpdatedAt . '</td>
                <td>
                    <span class="' . $typeClass . '">' . $typeText . '</span>
                </td>
                <td>
                    <span class="' . $statusClass . '">' . $statusText . '</span>
                </td>
                <td>
                    <button class="btn btn-sm btn-info rounded-pill"
                        onclick="getAuth(\'' . $eachAuthUser->authUserId . '\')">
                        ✏️
                    </button>
                    <button class="btn btn-sm btn-danger rounded-pill"
                        onclick="deleteAuth(\'' . $eachAuthUser->authUserId . '\')">
                        🗑
                    </button>
                </td>
            </tr>
        ';
        }

        return $this->response->setJSON([
            'status' => true,
            'html'   => $html
        ]);
    }

    public function getAuthUserDetailsAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $authId = $this->request->getPost('authId');
        if (!$authId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Auth user ID missing.'
            ]);
        }

        return $this->response->setJSON(
            $this->authUserModel->getAuthUserDetailsAJAX($authId)
        );
    }

    public function updateAuthUserAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $authId = $this->request->getPost('authId');
        if (!$authId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Auth user ID missing.'
            ]);
        }

        $imageFile = $this->request->getFile('updateImageFile');
        $imageBase64 = null;
        if ($imageFile && $imageFile->isValid()) {
            $imageBase64 = base64_encode(
                file_get_contents($imageFile->getTempName())
            );
        }
        $data = [
            'authUserName'         => trim($this->request->getPost('authName')),
            'authUserEmailAddress' => trim($this->request->getPost('authEmail')),
            'authUserPhoneNumber'  => trim($this->request->getPost('phoneNumber')),
            'authUserPassword'     => $this->request->getPost('password'),
            'authUserType'         => $this->request->getPost('authType'),
            'authUserActive'       => $this->request->getPost('authActive'),
            'authUserImage'        => $imageBase64
        ];

        return $this->response->setJSON(
            $this->authUserModel->updateAuthUserAJAX($authId, $data)
        );
    }

    public function deleteAuthUserAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $authId = $this->request->getPost('authId');
        if (!$authId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Auth user ID missing.'
            ]);
        }

        return $this->response->setJSON(
            $this->authUserModel->deleteAuthUserAJAX($authId)
        );
    }
}
