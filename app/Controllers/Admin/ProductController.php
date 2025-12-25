<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\ProductModel;
use App\Models\Admin\AuthUserModel;
use App\Models\Admin\CategoryModel;
use App\Models\Admin\SubCategoryModel;
use App\Models\Admin\LanguageModel;
use App\Helpers\CustomHelper;

class ProductController extends Common
{
    protected $session;
    protected $productModel;
    protected $authUserModel;
    protected $categoryModel;
    protected $subcategoryModel;
    protected $languageModel;
    protected $customHelper;

    public function __construct()
    {
        $this->session              = session();
        $this->productModel         = new ProductModel();
        $this->authUserModel        = new AuthUserModel();
        $this->categoryModel        = new CategoryModel();
        $this->subcategoryModel     = new SubCategoryModel();
        $this->languageModel        = new LanguageModel();
        $this->customHelper         = new CustomHelper();
    }

    public function product_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Product Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        // Get Category List
        $data['get_category_list'] = $this->categoryModel->getAllCategoriesAJAX();
        $data['category_list'] = $data['get_category_list']->content;

        // Get Sub-Category List
        $data['get_subcategory_list'] = $this->subcategoryModel->getAllSubCategoriesAJAX();
        $data['subcategory_list'] = $data['get_subcategory_list']->content;

        // Get Language List
        $data['get_language_list'] = $this->languageModel->getLanguagesAJAX();
        $data['language_list'] = $data['get_language_list']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_product/product_view')
            . view('admin/template/footer');
    }

    public function addProductAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $data = [
            'productName'         => trim($this->request->getPost('productName')),
            'productCategory'     => $this->request->getPost('productCategory'),
            'productSubCategory'  => $this->request->getPost('productSubCategory'),
            'productLanguage'     => $this->request->getPost('productLanguage'),
            'productBrand'        => $this->request->getPost('productBrand'),
            'productCode'         => trim($this->request->getPost('productCode')),
            'productAvailability' => $this->request->getPost('productAvailability'),
            'productPrice'        => trim($this->request->getPost('productPrice')),
            'productDetails'      => trim($this->request->getPost('productDetails')),
            'productStock'        => trim($this->request->getPost('productStock')),
            'productActive'       => $this->request->getPost('productActive')
        ];

        return $this->response->setJSON(
            $this->productModel->addProductAJAX($data)
        );
    }

    public function getAllProductsAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->productModel->getAllProductsAJAX();
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No product(s) found!'
            ]);
        }

        $html = '';
        $i = 1;
        foreach ($result->content as $eachProduct) {
            list($statusText, $statusClass) = $this->customHelper->getStatusDetails($eachProduct->productActive);

            $productId = isset($eachProduct->productId) && $eachProduct->productId !== '' ? esc($eachProduct->productId) : '';

            $categoryName = isset($eachProduct->categoryInfo->categoryName) && $eachProduct->categoryInfo->categoryName !== '' ?
                esc($eachProduct->categoryInfo->categoryName) : '-';

            $subCategoryName = isset($eachProduct->subCategoryInfo->subCategoryName) && $eachProduct->subCategoryInfo->subCategoryName !== '' ?
                esc($eachProduct->subCategoryInfo->subCategoryName) : '-';

            $languageName = isset($eachProduct->languageInfo->languageName) && $eachProduct->languageInfo->languageName !== '' ? esc($eachProduct->languageInfo->languageName) : '-';

            $productBrand = isset($eachProduct->productBrand) && $eachProduct->productBrand !== '' ? esc($eachProduct->productBrand) : '-';

            $productAvailability = isset($eachProduct->productAvailability) && $eachProduct->productAvailability !== '' ? esc($eachProduct->productAvailability) : '0';

            $productPrice = isset($eachProduct->productPrice) && $eachProduct->productPrice !== '' ? esc($eachProduct->productPrice) : '0.00';

            $productDeatils = isset($eachProduct->productDetails) && $eachProduct->productDetails !== '' ? esc($eachProduct->productDetails) : '-';

            $productImage = isset($eachProduct->productImage) && $eachProduct->productImage !== '' ? esc($eachProduct->productImage) : base_url('assets/img/admin.jfif');

            list($stockText, $stockClass) = $this->customHelper->getProductStock($eachProduct->productStock);

            $authUserName = isset($eachProduct->authUserInfo->authUserName) && $eachProduct->authUserInfo->authUserName !== '' ?
                esc($eachProduct->authUserInfo->authUserName) : '-';

            $html .=
                <<<HTML
                    <tr>
                        <td>#{$i}</td>
                        <td>{$eachProduct->productCode}</td>
                        <td>{$eachProduct->productName}</td>
                        <td>{$categoryName}</td>
                        <td>{$subCategoryName}</td>
                        <td>{$languageName}</td>
                        <td>{$productBrand}</td>
                        <td>{$productAvailability}</td>
                        <td>{$productPrice}</td>
                        <td>{$productDeatils}</td>
                        <td><img src="{$productImage}"></td>
                        <td>
                            <span class="{$stockClass}">{$stockText}</span>
                        </td>
                        <td>{$authUserName}</td>
                        <td>{$this->customHelper->formatDateTime($eachProduct->productCreatedAt)}</td>
                        <td>{$this->customHelper->formatDateTime($eachProduct->productUpdatedAt)}</td>
                        <td>
                            <span class="{$statusClass}">{$statusText}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info rounded-pill"
                                    onclick="getProduct('{$productId}')">
                                ✏️
                            </button>
                            <button class="btn btn-sm btn-danger rounded-pill"
                                    onclick="deleteProduct('{$productId}')">
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

    public function getProductDetailsAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $productId = $this->request->getPost('productId');
        if (!$productId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Product ID missing.'
            ]);
        }

        $productId = htmlspecialchars(strip_tags($productId));
        $result = $this->productModel->getProductDetailsAJAX($productId);
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Product details not found.'
            ]);
        }

        $productId = htmlspecialchars($result->content->productId);
        $ProductName = htmlspecialchars($result->content->ProductName);
        $ProductActive = htmlspecialchars($result->content->ProductActive);

        $html =
            <<<HTML
                <input type="hidden" id="updateProductId" name="updateProductId" value="{$productId}">
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Name *</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="updateProductName" id="updateProductName" value="{$ProductName}" maxlength="100" autocomplete="new-name" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Category *</label>
                    <div class="col-sm-12">
                        <select class="form-select" name="updateProductCategory" id="updateProductCategory" required>
                            <option value="">-- Select --</option>
                            <option value="YES">Yes</option>
                            <option value="NO">No</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Subcategory *</label>
                    <div class="col-sm-12">
                        <select class="form-select" name="updateProductSubcategory" id="updateProductSubcategory" required>
                            <option value="">-- Select --</option>
                            <option value="YES">Yes</option>
                            <option value="NO">No</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Language *</label>
                    <div class="col-sm-12">
                        <select class="form-select" name="updateProductLanguage" id="updateProductLanguage" required>
                            <option value="">-- Select --</option>
                            <option value="YES">Yes</option>
                            <option value="NO">No</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Brand *</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="updateProductBrand" id="updateProductBrand" maxlength="50" autocomplete="new-brand" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">code *</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="updateProductCode" id="updateProductCode" maxlength="50" autocomplete="new-code" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Availability *</label>
                    <div class="col-sm-12">
                        <input type="number" class="form-control" name="updateProductAvailability" id="updateProductAvailability" autocomplete="new-availability" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Price *</label>
                    <div class="col-sm-12">
                        <input type="number" class="form-control" name="updateProductPrice" id="updateProductPrice" autocomplete="new-price" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Stock *</label>
                    <div class="col-sm-12">
                        <select class="form-select" name="updateProductStock" id="updateProductStock" required>
                            <option value="">-- Select --</option>
                            <option value="IN_STOCK">In Stock</option>
                            <option value="OUT_OF_STOCK">Out of Stock</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="inputText" class="col-sm-12 col-form-label">Active *</label>
                    <div class="col-sm-12">
                        <select class="form-select" name="updateProductActive" id="updateProductActive" required>
                            <option value="">-- Select --</option>
                            <option value="YES" {$this->customHelper->getStatusDetails($ProductActive, 'YES')}>Yes</option>
                            <option value="NO" {$this->customHelper->getStatusDetails($ProductActive, 'NO')}>No</option>
                        </select>
                    </div>
                </div>
            HTML;

        return $this->response->setJSON([
            'status' => true,
            'html'   => $html
        ]);
    }

    public function updateProductAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $updateProductId = $this->request->getPost('updateProductId');
        if (!$updateProductId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Category ID missing.'
            ]);
        }

        $data = [
            'updateProductName'         => trim($this->request->getPost('updateProductName')),
            'updateProductActive'       => $this->request->getPost('updateProductActive')
        ];

        return $this->response->setJSON(
            $this->productModel->updateProductAJAX($updateProductId, $data)
        );
    }

    public function deleteProductAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $deleteProductId = $this->request->getPost('deleteProductId');
        if (!$deleteProductId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Product ID missing.'
            ]);
        }

        return $this->response->setJSON(
            $this->productModel->deleteProductAJAX($deleteProductId)
        );
    }
}
