<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\ProductModel;
use App\Models\Admin\AuthUserModel;
use App\Models\Admin\UserModel;
use App\Models\Admin\WishlistModel;
use App\Helpers\CustomHelper;

class WishlistController extends Common
{
    protected $session;
    protected $productModel;
    protected $authUserModel;
    protected $userModel;
    protected $customHelper;
    protected $wishlistModel;

    public function __construct()
    {
        $this->session              = session();
        $this->productModel         = new ProductModel();
        $this->authUserModel        = new AuthUserModel();
        $this->userModel            = new UserModel();
        $this->wishlistModel        = new WishlistModel();
        $this->customHelper         = new CustomHelper();
    }

    public function wishlist_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Wishlist Service | ' . PROJECT_NAME;
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
            . view('admin/manage_add_to_favourite/add_to_favourite_view')
            . view('admin/template/footer');
    }

    public function addWishlistAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $data = [
            'productId'             => $this->request->getPost('productId'),
            'userId'                => $this->request->getPost('userId')
        ];

        return $this->response->setJSON(
            $this->wishlistModel->addWishlistAJAX($data)
        );
    }

    public function getAllWishlistsAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->wishlistModel->getAllWishlistsAJAX();
        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No wishlist(s) found!'
            ]);
        }

        $html = '';
        $i = 1;
        foreach ($result->content as $eachWishlist) {
            $wishlistId = isset($eachWishlist->addToFavouriteId) && $eachWishlist->addToFavouriteId !== '' ? esc($eachWishlist->addToFavouriteId) : '';

            $authUser = isset($eachWishlist->authUserInfo->authUserName) && $eachWishlist->authUserInfo->authUserName !== '' ?
                esc($eachWishlist->authUserInfo->authUserName) : '-';

            $userId = isset($eachWishlist->userInfo->userId) && $eachWishlist->userInfo->userId !== '' ?
                esc($eachWishlist->userInfo->userId) : '';

            $userName = isset($eachWishlist->userInfo->fullName) && $eachWishlist->userInfo->fullName !== '' ?
                esc($eachWishlist->userInfo->fullName) : '-';

            $productName = isset($eachWishlist->productInfo->productName) && $eachWishlist->productInfo->productName !== '' ?
                esc($eachWishlist->productInfo->productName) : '-';

            $html .=
                <<<HTML
                    <tr>
                        <td>#{$i}</td>
                        <td>{$productName}</td>
                        <td>{$userName}</td>
                        <td>{$authUser}</td>
                        <td>{$this->customHelper->formatDateTime($eachWishlist->favouriteCreatedAt)}</td>
                        <td>
                            <button class="btn btn-sm btn-danger rounded-pill"
                            onclick="deleteWishlist({$wishlistId}, {$userId})">
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

    public function deleteWishlistAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $wishlistId = $this->request->getPost('wishlistId');
        $userId = $this->request->getPost('userId');
        if (!$wishlistId) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Wishlist ID missing.'
            ]);
        }

        return $this->response->setJSON(
            $this->wishlistModel->deleteWishlistAJAX($wishlistId, $userId)
        );
    }
}
