<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\ProductModel;
use App\Models\Admin\AuthUserModel;
use App\Models\Admin\UserModel;
use App\Models\Admin\CartModel;
use App\Helpers\CustomHelper;

class CartController extends Common
{
    protected $session;
    protected $productModel;
    protected $authUserModel;
    protected $userModel;
    protected $customHelper;
    protected $cartModel;

    public function __construct()
    {
        $this->session              = session();
        $this->productModel         = new ProductModel();
        $this->authUserModel        = new AuthUserModel();
        $this->userModel            = new UserModel();
        $this->cartModel            = new CartModel();
        $this->customHelper         = new CustomHelper();
    }

    public function cart_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Cart Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        // Get Products List
        $data['get_product_list'] = $this->productModel->getAllProductsAJAX();
        $data['product_list'] = $data['get_product_list']->content;

        // Get Users List
        $data['get_user_list'] = $this->userModel->getAllUsersAJAX();
        $data['user_list'] = $data['get_user_list']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_add_to_cart/add_to_cart_view')
            . view('admin/template/footer');
    }

    public function getAllCartsAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $userId = (int) ($this->request->getGet('userId') ?? 0);
        $result = $this->cartModel->getAllCartsAJAX($userId);
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No cart(s) found!'
            ]);
        }

        $html = '';
        $i = 1;

        foreach ($result->content as $eachCart) {

            $cartId   = esc($eachCart->addToCartId ?? '');
            $userId   = esc($eachCart->userInfo->userId ?? '');
            $userName = esc($eachCart->userInfo->fullName ?? '-');
            $authUser = esc($eachCart->authUserInfo->authUserName ?? '-');
            $product  = esc($eachCart->productInfo->productName ?? '-');
            $price    = esc($eachCart->eachProductTotalPrice ?? '0') . '.00';
            $qty      = esc($eachCart->quantity ?? '1');
            $created  = $this->customHelper->formatDateTime($eachCart->cartCreatedAt);

            $html .=
                "<tr>
                    <td>#{$i}</td>
                    <td>{$product}</td>
                    <td>{$price}</td>
                    <td>{$qty}</td>
                    <td>{$userName}</td>
                    <td>{$authUser}</td>
                    <td>{$created}</td>
                    <td>
                        <button class='btn btn-sm btn-danger rounded-pill'
                        onclick='deleteCart({$cartId}, {$userId})'>
                        🗑
                        </button>
                    </td>
                </tr>";
            $i++;
        }

        return $this->response->setJSON([
            'status' => true,
            'html'   => $html
        ]);
    }


    public function deleteCartAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $cartId = $this->request->getPost('cartId');
        $userId = $this->request->getPost('userId');
        if (!$cartId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Cart ID missing.'
            ]);
        }
        if (!$userId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User ID missing.'
            ]);
        }

        return $this->response->setJSON(
            $this->cartModel->deleteCartAJAX($cartId, $userId)
        );
    }
}
