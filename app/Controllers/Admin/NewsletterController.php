<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\LanguageModel;
use App\Models\Admin\AuthUserModel;
use App\Models\Admin\UserModel;
use App\Models\Admin\NewsletterModel;
use App\Helpers\CustomHelper;

class NewsletterController extends Common
{
    protected $session;
    protected $languageModel;
    protected $authUserModel;
    protected $userModel;
    protected $newsletterModel;
    protected $customHelper;

    public function __construct()
    {
        $this->session          = session();
        $this->languageModel    = new LanguageModel();
        $this->authUserModel    = new AuthUserModel();
        $this->userModel        = new UserModel();
        $this->newsletterModel  = new NewsletterModel();
        $this->customHelper     = new CustomHelper();
    }

    public function newsletter_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Newsletter Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        // Get Users List
        $data['get_user_list'] = $this->userModel->getAllUsersAJAX();
        $data['user_list'] = $data['get_user_list']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_newsletter/newsletter_view')
            . view('admin/template/footer');
    }

    public function addNewsletterAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $data = [
            'userId'              => trim($this->request->getPost('userId')),
            'addSubscribe'        => $this->request->getPost('addSubscribe')
        ];

        return $this->response->setJSON(
            $this->newsletterModel->addNewsletterAJAX($data)
        );
    }

    public function getAllNewslettersAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->newsletterModel->getAllNewslettersAJAX();
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No newsletter(s) found!'
            ]);
        }

        $html = '';
        $i = 1;
        foreach ($result->content as $eachNewsletter) {

            $newsletterId = isset($eachNewsletter->newsletterId) && $eachNewsletter->newsletterId !== ''
                ? esc($eachNewsletter->newsletterId)
                : '';

            $userId = isset($eachNewsletter->userInfo->userId) && $eachNewsletter->userInfo->userId !== ''
                ? esc($eachNewsletter->userInfo->userId)
                : '';

            $authUserName = isset($eachNewsletter->authUserInfo->authUserName) && $eachNewsletter->authUserInfo->authUserName !== ''
                ? esc($eachNewsletter->authUserInfo->authUserName)
                : '-';

            $userName = isset($eachNewsletter->userInfo->fullName) && $eachNewsletter->userInfo->fullName !== ''
                ? esc($eachNewsletter->userInfo->fullName)
                : '-';

            $newsletterToggledOrNot = isset($eachNewsletter->newsletterToggle) && $eachNewsletter->newsletterToggle !== ''
                ? esc($eachNewsletter->newsletterToggle)
                : '';
            $checked = $newsletterToggledOrNot === 'YES' ? 'checked' : '';
            $toggleData = $newsletterToggledOrNot === 'YES' ? 'NO' : 'YES';

            $html .=
                <<<HTML
                    <tr>
                        <td>#{$i}</td>
                        <td>{$userName}</td>
                        <td>{$authUserName}</td>
                        <td>
                            <div class="form-check form-switch">
                                <input class="form-check-input" data-id={$newsletterId} data-uid="{$userId}" data-toggle="{$toggleData}" type="checkbox" id="toggleNewsletter" {$checked}>
                                <label class="form-check-label" for="toggleNewsletter">{$newsletterToggledOrNot}</label>
                            </div>
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

    public function updateNewsletterAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $newsletterId = $this->request->getPost('newsletterId');
        if (!$newsletterId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Newsletter ID missing.'
            ]);
        }

        $userId = $this->request->getPost('userId');
        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User ID missing.'
            ]);
        }

        $toggleData = $this->request->getPost('toggleData');
        if (!$toggleData) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Toggle data missing.'
            ]);
        }

        return $this->response->setJSON(
            $this->newsletterModel->updateNewsletterAJAX($newsletterId, $userId, $toggleData)
        );
    }
}
