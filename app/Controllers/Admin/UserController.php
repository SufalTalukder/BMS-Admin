<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\AuthUserModel;
use App\Models\Admin\UserModel;
use App\Helpers\CustomHelper;

class UserController extends Common
{
    protected $session;
    protected $authUserModel;
    protected $userModel;
    protected $customHelper;

    public function __construct()
    {
        $this->session          = session();
        $this->userModel        = new UserModel();
        $this->authUserModel    = new AuthUserModel();
        $this->customHelper     = new CustomHelper();
    }

    public function users_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Users Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_users/users_view')
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

    public function addUserAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $data = [
            'addName'           => trim($this->request->getPost('addName')),
            'addEmail'          => trim($this->request->getPost('addEmail')),
            'addPhoneNumber'    => trim($this->request->getPost('addPhoneNumber')),
            'addDOB'            => $this->request->getPost('addDOB'),
            'addAddress'        => $this->request->getPost('addAddress'),
            'addActive'         => $this->request->getPost('addActive')
        ];

        return $this->response->setJSON(
            $this->userModel->addUserAJAX($data)
        );
    }

    public function getAllUsersAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->userModel->getAllUsersAJAX();
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No user(s) found!'
            ]);
        }

        $html = '';
        $i = 1;
        foreach ($result->content as $eachUser) {
            $img = !empty($eachUser->userImage) ? base_url($eachUser->userImage) : base_url('assets/img/admin.jfif');

            list($statusText, $statusClass) = $this->customHelper->getStatusDetails($eachUser->userActive);

            $actionBy = isset($eachUser->authUserInfo->authUserName) && $eachUser->authUserInfo->authUserName !== '' ? esc($eachUser->authUserInfo->authUserName) : '-';

            $referralCodeClass = isset($eachUser->userReferralCode) && $eachUser->userReferralCode !== '' ? "<span class='badge bg-success'><i class='bi bi-check-circle me-1'></i> '" . $eachUser->userReferralCode . "'</span>" : "-";

            $html .=
                <<<HTML
                    <tr>
                        <td>#{$i}</td>
                        <td>
                            <a href="{$img}" target="_blank">
                                <img src="{$img}" style="height:60px;width:90px;">
                            </a>
                        </td>
                        <td>$eachUser->fullName</td>
                        <td>$eachUser->phoneNumber</td>
                        <td>$eachUser->emailAddress</td>
                        <td>$eachUser->dob</td>
                        <td>$eachUser->userAddress</td>
                        <td>$referralCodeClass</td>
                        <td>$actionBy</td>
                        <td>{$this->customHelper->formatDateTime($eachUser->userCreatedAt)}</td>
                        <td>{$this->customHelper->formatDateTime($eachUser->userUpdatedAt)}</td>
                        <td>
                            <span class="{$statusClass}">{$statusText}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info rounded-pill" onclick="getUser('{$eachUser->userId}')">✏️</button>
                            <button class="btn btn-sm btn-danger rounded-pill" onclick="deleteUser('{$eachUser->userId}')">🗑</button>
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

    public function getUserDetailsAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $userId = $this->request->getPost('userId');
        $userId = htmlspecialchars(strip_tags($userId));
        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User ID missing.'
            ]);
        }

        $result = $this->userModel->getUserDetailsAJAX($userId);
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No user details found!'
            ]);
        }

        $updateUserId = htmlspecialchars($result->content->userId);
        $updateName = htmlspecialchars($result->content->fullName);
        $updateEmail = htmlspecialchars($result->content->emailAddress);
        $updatePhoneNumber = htmlspecialchars($result->content->phoneNumber);
        $updateDOB = htmlspecialchars($result->content->dob);
        $updateAddress = htmlspecialchars($result->content->userAddress);
        $updateActive = htmlspecialchars($result->content->userActive);
        $previousUserImage = (!empty($result->content->userImage) || $result->content->userImage !== null) ? $result->content->userImage : base_url('assets/img/admin.jfif');

        $html =
            <<<HTML
                <input type="hidden" id="updateUserId" name="updateUserId" value="{$updateUserId}">
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                    <div class="col-sm-12">
                    <input type="text" class="form-control" name="updateName" id="updateName" value="{$updateName}" maxlength="100" autocomplete="new-name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Email Address *</label>
                    <div class="col-sm-12">
                    <input type="email" class="form-control" name="updateEmail" id="updateEmail" value="{$updateEmail}" maxlength="50" autocomplete="new-email" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Phone Number *</label>
                    <div class="col-sm-12">
                    <input type="text" class="form-control" name="updatePhoneNumber" id="updatePhoneNumber" value="{$updatePhoneNumber}" minlength="10" maxlength="10" autocomplete="new-phone-number" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Date of Birth *</label>
                    <div class="col-sm-12">
                    <input type="date" class="form-control" name="updateDOB" id="updateDOB" value="{$updateDOB}" maxlength="50" autocomplete="new-dob" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Address *</label>
                    <div class="col-sm-12">
                    <input type="text" class="form-control" name="updateAddress" id="updateAddress" value="{$updateAddress}" maxlength="50" autocomplete="new-address" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                    <div class="col-sm-12">
                    <select class="form-select" name="updateActive" id="updateActive" required>
                        <option value="">-- Select --</option>
                        <option value="YES" {$this->customHelper->isSelected($updateActive, 'YES')}>Yes</option>
                        <option value="NO" {$this->customHelper->isSelected($updateActive, 'NO')}>No</option>
                    </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputFile" class="col-sm-12 col-form-label">Upload Image</label>
                    <div class="col-sm-12">
                    <input type="file" class="form-control" name="image" id="updateImageFile" accept="png, jpg, jpeg">
                    <center>
                        <img id="previousUserImage" src="{$previousUserImage}" alt="Image" style="max-width: 100px; max-height: 100px; margin-top: 10px;">
                    </center>
                    </div>
                </div>
            HTML;

        return $this->response->setJSON([
            'status' => true,
            'html'   => $html
        ]);
    }

    public function updateUserAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $userId = $this->request->getPost('userId');
        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User ID missing.'
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
            'updateName'            => trim($this->request->getPost('updateName')),
            'updateEmail'           => trim($this->request->getPost('updateEmail')),
            'updatePhoneNumber'     => trim($this->request->getPost('updatePhoneNumber')),
            'updateDOB'             => $this->request->getPost('updateDOB'),
            'updateAddress'         => $this->request->getPost('updateAddress'),
            'updateActive'          => $this->request->getPost('updateActive'),
            'userImage'             => $imageBase64
        ];

        return $this->response->setJSON(
            $this->userModel->updateUserAJAX($userId, $data)
        );
    }

    public function deleteUserAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $userId = $this->request->getPost('userId');
        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User ID missing.'
            ]);
        }

        return $this->response->setJSON(
            $this->userModel->deleteUserAJAX($userId)
        );
    }
}
