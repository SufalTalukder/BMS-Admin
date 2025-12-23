<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\SubCategoryModel;
use App\Models\Admin\AuthUserModel;

class SubCategoryController extends Common
{
    protected $session;
    protected $subCategoryModel;
    protected $authUserModel;
    protected $customHelper;

    public function __construct()
    {
        $this->session        = session();
        $this->subCategoryModel  = new SubCategoryModel();
        $this->authUserModel  = new AuthUserModel();
        $this->customHelper   = new \CustomHelper();
    }

    public function sub_category_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Sub-category Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_product/subcategory_view')
            . view('admin/template/footer');
    }

    public function addSubCategoryAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $data = [
            'subCategoryName'         => trim($this->request->getPost('subCategoryName')),
            'subCategoryActive'       => $this->request->getPost('subCategoryActive')
        ];

        return $this->response->setJSON(
            $this->subCategoryModel->addSubCategoryAJAX($data)
        );
    }

    public function getAllSubCategoriesAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->subCategoryModel->getAllSubCategoriesAJAX();
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No subcategorie(s) found!'
            ]);
        }

        $html = '';
        $i = 1;
        foreach ($result->content as $eachSubCategory) {
            list($statusText, $statusClass) = $this->customHelper->getStatusDetails($eachSubCategory->subCategoryActive);

            $html .=
                <<<HTML
                    <tr>
                        <td>#{$i}</td>
                        <td>{esc($eachSubCategory->subCategoryName)}</td>
                        <td>{esc($eachSubCategory->authUserInfo->authUserName)}</td>
                        <td>{$this->customHelper->formatDateTime($eachSubCategory->subCategoryCreatedAt)}</td>
                        <td>{$this->customHelper->formatDateTime($eachSubCategory->subCategoryUpdatedAt)}</td>
                        <td>
                            <span class="{$statusClass}">{$statusText}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info rounded-pill"
                                    onclick="getSubCategory('{$eachSubCategory->subCategoryId}')">
                                ✏️
                            </button>
                            <button class="btn btn-sm btn-danger rounded-pill"
                                    onclick="deleteSubCategory('{$eachSubCategory->subCategoryId}')">
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

    public function getSubCategoryDetailsAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $getSubCategoryId = $this->request->getPost('getSubCategoryId');
        if (!$getSubCategoryId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Category ID missing.'
            ]);
        }

        $getSubCategoryId = htmlspecialchars(strip_tags($getSubCategoryId));
        $result = $this->subCategoryModel->getSubCategoryDetailsAJAX($getSubCategoryId);
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Subcategory details not found.'
            ]);
        }

        $subCategoryId = htmlspecialchars($result->content->subCategoryId);
        $subCategoryName = htmlspecialchars($result->content->subCategoryName);
        $subCategoryActive = htmlspecialchars($result->content->subCategoryActive);

        $html =
            <<<HTML
                <input type="hidden" id="updateSubcategoryId" name="updateSubcategoryId" value="{$subCategoryId}">
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="updateSubcategoryName" id="updateSubcategoryName" value="{$subCategoryName}" maxlength="100" autocomplete="new-name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                    <div class="col-sm-12">
                        <select class="form-select" name="updateSubcategoryActive" id="updateSubcategoryActive" required>
                            <option value="">-- Select --</option>
                            <option value="YES" {$this->customHelper->getStatusDetails($subCategoryActive, 'YES')}>Yes</option>
                            <option value="NO" {$this->customHelper->getStatusDetails($subCategoryActive, 'NO')}>No</option>
                        </select>
                    </div>
                </div>
            HTML;

        return $this->response->setJSON([
            'status' => true,
            'html'   => $html
        ]);
    }

    public function updateSubCategoryAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $updateSubcategoryId = $this->request->getPost('updateSubcategoryId');
        if (!$updateSubcategoryId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Category ID missing.'
            ]);
        }

        $data = [
            'updateSubcategoryName'         => trim($this->request->getPost('updateSubcategoryName')),
            'updateSubcategoryActive'       => $this->request->getPost('updateSubcategoryActive')
        ];

        return $this->response->setJSON(
            $this->subCategoryModel->updateSubCategoryAJAX($updateSubcategoryId, $data)
        );
    }

    public function deleteSubCategoryAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $deleteSubCategoryId = $this->request->getPost('deleteSubCategoryId');
        if (!$deleteSubCategoryId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Subcategory ID missing.'
            ]);
        }

        return $this->response->setJSON(
            $this->subCategoryModel->deleteSubCategoryAJAX($deleteSubCategoryId)
        );
    }
}
