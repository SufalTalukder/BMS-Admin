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

            $productAvailability = isset($eachProduct->productAvailability) && $eachProduct->productAvailability !== '' ? number_format((int)$eachProduct->productAvailability) : number_format((int)0);

            $productPrice = isset($eachProduct->productPrice) && $eachProduct->productPrice !== '' ? number_format((float)$eachProduct->productPrice, 2) : number_format((float)0, 2);

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

        $product = $this->productModel->getProductDetailsAJAX($productId);

        if (!$product) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Product details not found.'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'productId'        => $product->content->productId,
                'productName'      => $product->content->productName,
                'productBrand'     => $product->content->productBrand,
                'productCode'      => $product->content->productCode,
                'productAvailability' => $product->content->productAvailability,
                'productPrice'     => $product->content->productPrice,
                'productDetails'   => $product->content->productDetails,
                'productStock'     => $product->content->productStock,
                'productActive'    => $product->content->productActive,
                'categoryId'       => $product->content->categoryInfo->categoryId,
                'subCategoryId'    => $product->content->subCategoryInfo->subCategoryId,
                'languageId'       => $product->content->languageInfo->languageId,
            ]
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

        $productId = $this->request->getPost('updateProductId');
        if (!$productId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Product ID missing.'
            ]);
        }

        $data = [
            'productName'        => trim($this->request->getPost('updateProductName')),
            'categoryId'         => $this->request->getPost('updateProductCategory'),
            'subCategoryId'      => $this->request->getPost('updateProductSubCategory'),
            'languageId'         => $this->request->getPost('updateProductLanguage'),
            'productBrand'       => trim($this->request->getPost('updateProductBrand')),
            'productCode'        => trim($this->request->getPost('updateProductCode')),
            'productAvailability' => $this->request->getPost('updateProductAvailability'),
            'productPrice'       => $this->request->getPost('updateProductPrice'),
            'productDetails'     => trim($this->request->getPost('updateProductDetails')),
            'productStock'       => $this->request->getPost('updateProductStock'),
            'productActive'      => $this->request->getPost('updateProductActive')
        ];

        return $this->response->setJSON(
            $this->productModel->updateProductAJAX($productId, $data)
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
