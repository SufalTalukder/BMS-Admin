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

    public function __construct()
    {
        $this->session        = session();
        $this->languageModel  = new LanguageModel();
        $this->authUserModel  = new AuthUserModel();
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

            if ($eachLanguage->languageActive === 'YES') {
                $statusText  = 'Yes';
                $statusClass = 'badge bg-primary rounded';
            } else {
                $statusText  = 'No';
                $statusClass = 'badge bg-warning rounded';
            }

            $html .= '
            <tr>
                <td>' . "#" . $i++ . '</td>
                <td>' . esc($eachLanguage->languageName) . '</td>
                <td>' . $eachLanguage->languageCreatedAt . '</td>
                <td>' . $eachLanguage->languageUpdatedAt . '</td>
                <td>
                    <span class="' . $statusClass . '">' . $statusText . '</span>
                </td>
                <td>' . esc($eachLanguage->authUserInfo->authUserName) . '</td>
                <td>
                    <button class="btn btn-sm btn-info rounded-pill"
                        onclick="getLanguage(\'' . $eachLanguage->languageId . '\')">
                        ✏️
                    </button>
                    <button class="btn btn-sm btn-danger rounded-pill"
                        onclick="deleteLanguage(\'' . $eachLanguage->languageId . '\')">
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

        return $this->response->setJSON(
            $this->languageModel->getLanguageDetailsAJAX($getLanguageId)
        );
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
