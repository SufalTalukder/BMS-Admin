<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\AuthUserModel;

class AuthUserController extends Common
{
    protected $session;
    protected $authUserModel;
    protected $customHelper;

    public function __construct()
    {
        $this->session          = session();
        $this->authUserModel    = new AuthUserModel();
        $this->customHelper     = new \CustomHelper();
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
                'message' => 'No auth user(s) found!'
            ]);
        }

        $html = '';
        $i = 1;
        foreach ($result->content as $eachAuthUser) {
            $img = !empty($eachAuthUser->authUserImage) ? base_url($eachAuthUser->authUserImage) : base_url('assets/img/admin.jfif');

            list($typeText, $typeClass) = $this->customHelper->getUserTypeDetails($eachAuthUser->authUserType);
            list($statusText, $statusClass) = $this->customHelper->getStatusDetails($eachAuthUser->authUserActive);

            $html .=
                <<<HTML
                    <tr>
                        <td>#{$i}</td>
                        <td>
                            <a href="{$img}" target="_blank">
                                <img src="{$img}" style="height:60px;width:90px;">
                            </a>
                        </td>
                        <td>{esc($eachAuthUser->authUserName)}</td>
                        <td>{esc($eachAuthUser->authUserEmailAddress)}</td>
                        <td>{esc($eachAuthUser->authUserPhoneNumber)}</td>
                        <td>
                            <span class="{$typeClass}">{$typeText}</span>
                        </td>
                        <td>{$this->customHelper->formatDateTime($eachAuthUser->authUserCreatedAt)}</td>
                        <td>{$this->customHelper->formatDateTime($eachAuthUser->authUserUpdatedAt)}</td>
                        <td>
                            <span class="{$statusClass}">{$statusText}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info rounded-pill" onclick="getAuth('{$eachAuthUser->authUserId}')">✏️</button>
                            <button class="btn btn-sm btn-danger rounded-pill" onclick="deleteAuth('{$eachAuthUser->authUserId}')">🗑</button>
                        </td>
                    </tr>
                HTML;
            $i++;
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
        $authId = htmlspecialchars(strip_tags($authId));
        if (!$authId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Auth user ID missing.'
            ]);
        }

        $result = $this->authUserModel->getAuthUserDetailsAJAX($authId);
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No auth user details found!'
            ]);
        }

        $updateAuthId = htmlspecialchars($result->content->authUserId);
        $updateAuthName = htmlspecialchars($result->content->authUserName);
        $updateAuthEmail = htmlspecialchars($result->content->authUserEmailAddress);
        $updatePhoneNumber = htmlspecialchars($result->content->authUserPhoneNumber);
        $updateAuthType = htmlspecialchars($result->content->authUserType);
        $updateAuthActive = htmlspecialchars($result->content->authUserActive);
        $previousAuthImage = (!empty($result->content->authUserImage) || $result->content->authUserImage !== null) ? $result->content->authUserImage : base_url('assets/img/admin.jfif');

        $html =
            <<<HTML
                <input type="hidden" id="updateAuthId" name="updateAuthId" value="{$updateAuthId}">
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                    <div class="col-sm-12">
                    <input type="text" class="form-control" name="updateAuthName" id="updateAuthName" value="{$updateAuthName}" maxlength="100" autocomplete="new-name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Email Address *</label>
                    <div class="col-sm-12">
                    <input type="email" class="form-control" name="updateAuthEmail" id="updateAuthEmail" value="{$updateAuthEmail}" maxlength="50" autocomplete="new-email" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Phone Number *</label>
                    <div class="col-sm-12">
                    <input type="text" class="form-control" name="updatePhoneNumber" id="updatePhoneNumber" value="{$updatePhoneNumber}" minlength="10" maxlength="10" autocomplete="new-phone-number" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Password</label>
                    <div class="col-sm-12">
                    <input type="password" class="form-control" name="updatePassword" id="updatePassword" value="" maxlength="50" autocomplete="new-password" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Type *</label>
                    <div class="col-sm-12">
                    <select class="form-select" name="updateAuthType" id="updateAuthType" required>
                        <option value="">-- Select --</option>
                        <option value="SUPER_ADMIN" {$this->customHelper->isSelected($updateAuthType, 'SUPER_ADMIN')}>Super Admin</option>
                        <option value="ADMIN" {$this->customHelper->isSelected($updateAuthType, 'ADMIN')}>Admin</option>
                    </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                    <div class="col-sm-12">
                    <select class="form-select" name="updateAuthActive" id="updateAuthActive" required>
                        <option value="">-- Select --</option>
                        <option value="YES" {$this->customHelper->isSelected($updateAuthActive, 'YES')}>Yes</option>
                        <option value="NO" {$this->customHelper->isSelected($updateAuthActive, 'YES')}>No</option>
                    </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputFile" class="col-sm-12 col-form-label">Upload Image</label>
                    <div class="col-sm-12">
                    <input type="file" class="form-control" name="image" id="updateImageFile" accept="png, jpg, jpeg">
                    <center>
                        <img id="previousAuthImage" src="{$previousAuthImage}" alt="Image" style="max-width: 100px; max-height: 100px; margin-top: 10px;">
                    </center>
                    </div>
                </div>
            HTML;

        return $this->response->setJSON([
            'status' => true,
            'html'   => $html
        ]);
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
