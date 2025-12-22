<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\BannerModel;
use App\Models\Admin\AuthUserModel;

class BannerController extends Common
{
    protected $session;
    protected $bannerModel;
    protected $authUserModel;

    public function __construct()
    {
        $this->session          = session();
        $this->bannerModel      = new BannerModel();
        $this->authUserModel    = new AuthUserModel();
    }

    public function banner_list_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Banner Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_banner/banner_view')
            . view('admin/template/footer');
    }

    public function addBannerAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $imageFiles = $this->request->getFileMultiple('appBannerImage');

        if (empty($imageFiles)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No image files received!'
            ]);
        }

        return $this->response->setJSON(
            $this->bannerModel->addBannerAJAX($imageFiles)
        );
    }

    public function getAllBannersAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->bannerModel->getAllBannersAJAX();
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No banner(s) found!'
            ]);
        }

        $html = '';
        $i = 1;
        foreach ($result->content as $eachBanner) {
            $img = !empty($eachBanner->appBannerImage) ? base_url($eachBanner->appBannerImage) : base_url('assets/img/admin.jfif');

            $html .=
                <<<HTML
                    <tr>
                        <td>#{$i}</td>
                        <td>
                            <a href="{$img}" target="_blank">
                                <img src="{$img}" style="height:60px; width:90px;">
                            </a>
                        </td>
                        <td>{esc($eachBanner->authUserInfo->authUserName)}</td>
                        <td>{$eachBanner->appBannerCreatedAt}</td>
                        <td>
                            <button class="btn btn-sm btn-danger rounded-pill" onclick="deleteBanner('{$eachBanner->appBannerId}')">🗑</button>
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

    public function deleteBannerAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $bannerIds = $this->request->getPost('bannerIds');

        if (empty($bannerIds) || !is_array($bannerIds)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Banner IDs are required!'
            ]);
        }

        return $this->response->setJSON(
            $this->bannerModel->deleteBannerAJAX($bannerIds)
        );
    }
}
