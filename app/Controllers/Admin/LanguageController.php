<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\LanguageModel;
use App\Models\Admin\AuthUserModel;

class LanguageController extends Common
{
    protected $session;
    protected $languageModel;
    protected $authUserModel;
    protected $customHelper;

    public function __construct()
    {
        $this->session        = session();
        $this->languageModel  = new LanguageModel();
        $this->authUserModel  = new AuthUserModel();
        $this->customHelper   = new \CustomHelper();
    }

    public function language_settings_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Language Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_language/language_settings_view')
            . view('admin/template/footer');
    }

    public function addLanguageAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $data = [
            'languageName'         => trim($this->request->getPost('languageName')),
            'languageActive'       => $this->request->getPost('languageActive')
        ];

        return $this->response->setJSON(
            $this->languageModel->addLanguageAJAX($data)
        );
    }

    public function getLanguagesAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->languageModel->getLanguagesAJAX();
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No language(s) found!'
            ]);
        }

        $html = '';
        $i = 1;
        foreach ($result->content as $eachLanguage) {
            list($statusText, $statusClass) = $this->customHelper->getStatusDetails($eachLanguage->languageActive);

            // Heredoc syntax of HTML
            $html .=
                <<<HTML
                    <tr>
                        <td>#{$i}</td>
                        <td>{esc($eachLanguage->languageName)}</td>
                        <td>{$eachLanguage->languageCreatedAt}</td>
                        <td>{$eachLanguage->languageUpdatedAt}</td>
                        <td>
                            <span class="{$statusClass}">{$statusText}</span>
                        </td>
                        <td>{esc($eachLanguage->authUserInfo->authUserName)}</td>
                        <td>
                            <button class="btn btn-sm btn-info rounded-pill"
                                    onclick="getLanguage('{$eachLanguage->languageId}')">
                                ✏️
                            </button>
                            <button class="btn btn-sm btn-danger rounded-pill"
                                    onclick="deleteLanguage('{$eachLanguage->languageId}')">
                                🗑
                            </button>
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

    public function getLanguageDetailsAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $getLanguageId = $this->request->getPost('getLanguageId');
        if (!$getLanguageId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Language ID missing.'
            ]);
        }

        // Input sanitization
        $getLanguageId = htmlspecialchars(strip_tags($getLanguageId));
        $result = $this->languageModel->getLanguageDetailsAJAX($getLanguageId);
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Language details not found.'
            ]);
        }

        // Output encoding for security
        $languageId = htmlspecialchars($result->content->languageId);
        $languageName = htmlspecialchars($result->content->languageName);
        $languageActive = htmlspecialchars($result->content->languageActive);

        // Heredoc syntax of HTML
        $html =
            <<<HTML
                <input type="hidden" id="updateLanguageId" name="updateLanguageId" value="{$languageId}">
                <div class="row mb-3">
                    <label for="updateLanguageName" class="col-sm-12 col-form-label">Name *</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="updateLanguageName" id="updateLanguageName" value="{$languageName}" maxlength="100" autocomplete="new-name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="updateLanguageActive" class="col-sm-12 col-form-label">Active *</label>
                    <div class="col-sm-12">
                        <select class="form-select" name="updateLanguageActive" id="updateLanguageActive" required>
                            <option value="">-- Select --</option>
                            <option value="YES" {$this->customHelper->isSelected($languageActive, 'YES')}>Yes</option>
                            <option value="NO" {$this->customHelper->isSelected($languageActive, 'NO')}>No</option>
                        </select>
                    </div>
                </div>
            HTML;

        return $this->response->setJSON([
            'status' => true,
            'html'   => $html
        ]);
    }

    public function updateLanguageAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $updateLanguageId = $this->request->getPost('updateLanguageId');
        if (!$updateLanguageId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Language ID missing.'
            ]);
        }

        $data = [
            'updateLanguageName'         => trim($this->request->getPost('updateLanguageName')),
            'updateLanguageActive'       => $this->request->getPost('updateLanguageActive')
        ];

        return $this->response->setJSON(
            $this->languageModel->updateLanguageAJAX($updateLanguageId, $data)
        );
    }

    public function deleteLanguageAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $deleteLanguageId = $this->request->getPost('deleteLanguageId');
        if (!$deleteLanguageId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Language ID missing.'
            ]);
        }

        return $this->response->setJSON(
            $this->languageModel->deleteLanguageAJAX($deleteLanguageId)
        );
    }
}
