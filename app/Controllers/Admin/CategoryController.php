<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\CategoryModel;
use App\Models\Admin\AuthUserModel;

class CategoryController extends Common
{
    protected $session;
    protected $categoryModel;
    protected $authUserModel;

    public function __construct()
    {
        $this->session        = session();
        $this->categoryModel  = new CategoryModel();
        $this->authUserModel  = new AuthUserModel();
    }

    public function category_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Category Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_product/category_view')
            . view('admin/template/footer');
    }

    public function addCategoryAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $data = [
            'categoryName'         => trim($this->request->getPost('categoryName')),
            'categoryActive'       => $this->request->getPost('categoryActive')
        ];

        return $this->response->setJSON(
            $this->categoryModel->addCategoryAJAX($data)
        );
    }

    public function getAllCategoriesAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->categoryModel->getAllCategoriesAJAX();

        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No categorie(s) found!'
            ]);
        }

        $html = '';
        $i = 1;

        foreach ($result->content as $eachCategory) {

            if ($eachCategory->categoryActive === 'YES') {
                $statusText  = 'Yes';
                $statusClass = 'badge bg-primary rounded';
            } else {
                $statusText  = 'No';
                $statusClass = 'badge bg-warning rounded';
            }

            $html .= '
            <tr>
                <td>' . "#" . $i++ . '</td>
                <td>' . esc($eachCategory->categoryName) . '</td>
                <td>' . $eachCategory->categoryCreatedAt . '</td>
                <td>' . $eachCategory->categoryUpdatedAt . '</td>
                <td>
                    <span class="' . $statusClass . '">' . $statusText . '</span>
                </td>
                <td>' . esc($eachCategory->authUserInfo->authUserName) . '</td>
                <td>
                    <button class="btn btn-sm btn-info rounded-pill"
                        onclick="getCategory(\'' . $eachCategory->categoryId . '\')">
                        ✏️
                    </button>
                    <button class="btn btn-sm btn-danger rounded-pill"
                        onclick="deleteCategory(\'' . $eachCategory->categoryId . '\')">
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

    public function getCategoryDetailsAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $getCategoryId = $this->request->getPost('getCategoryId');
        if (!$getCategoryId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Category ID missing.'
            ]);
        }

        return $this->response->setJSON(
            $this->categoryModel->getCategoryDetailsAJAX($getCategoryId)
        );
    }

    public function updateCategoryAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $updateCategoryId = $this->request->getPost('updateCategoryId');
        if (!$updateCategoryId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Category ID missing.'
            ]);
        }

        $data = [
            'updateCategoryName'         => trim($this->request->getPost('updateCategoryName')),
            'updateCategoryActive'       => $this->request->getPost('updateCategoryActive')
        ];

        return $this->response->setJSON(
            $this->categoryModel->updateCategoryAJAX($updateCategoryId, $data)
        );
    }

    public function deleteCategoryAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $deleteCategoryId = $this->request->getPost('deleteCategoryId');
        if (!$deleteCategoryId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Category ID missing.'
            ]);
        }

        return $this->response->setJSON(
            $this->categoryModel->deleteCategoryAJAX($deleteCategoryId)
        );
    }
}
