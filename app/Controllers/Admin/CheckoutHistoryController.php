<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\Common;
use App\Models\Admin\AuthUserModel;
use App\Models\Admin\CheckoutHistoryModel;
use App\Helpers\CustomHelper;

class CheckoutHistoryController extends Common
{
    protected $session;
    protected $authUserModel;
    protected $checkoutHistoryModel;
    protected $customHelper;

    public function __construct()
    {
        $this->session                  = session();
        $this->authUserModel            = new AuthUserModel();
        $this->checkoutHistoryModel     = new CheckoutHistoryModel();
        $this->customHelper             = new CustomHelper();
    }

    public function checkout_view()
    {
        if (!$this->session->has('admin_logged_true')) {
            return redirect()->to('/admin/login');
        }

        $data['meta_title'] = 'Checkout Service | ' . PROJECT_NAME;
        $data['auth_user_details'] = $this->session->get('admin_auth_user_details');
        $data['auth_user_details_by_api'] = $this->authUserModel->getAuthUserDetailsAJAX(
            $data['auth_user_details']->authUserId
        ) ?? null;
        $data['extracted_auth_user_details'] = $data['auth_user_details_by_api']->content;

        return view('admin/template/header', $data)
            . view('admin/template/sidebar')
            . view('admin/template/nav')
            . view('admin/manage_checkout_history/checkout_history_view')
            . view('admin/template/footer');
    }

    public function getAllCheckoutHistoriesAJAX()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid request!'
            ]);
        }

        $result = $this->checkoutHistoryModel->getAllCheckoutHistoriesAJAX();

        if (
            empty($result) ||
            !isset($result->status) ||
            $result->status !== 'success' ||
            empty($result->content)
        ) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'No checkout historie(s) found!'
            ]);
        }

        $html = '';
        $i = 1;

        foreach ($result->content as $row) {

            $userName = !empty($row->userInfo->fullName)
                ? esc($row->userInfo->fullName)
                : '-';

            $cartIds = !empty($row->addToCartIds)
                ? esc(implode(', ', $row->addToCartIds))
                : '-';

            $productsList = '-';
            if (!empty($row->products)) {
                $products = [];

                foreach ($row->products as $product) {
                    $products[] = '
                            <div class="mb-2 pb-2 border-bottom">
                                <div><strong>Name:</strong> ' . esc($product->productName) . '</div>
                                <div><strong>Brand:</strong> ' . esc($product->productBrand) . '</div>
                                <div><strong>Price:</strong> ₹' . number_format((float)$product->productPrice, 2) . '</div>
                                <div><strong>#Code:</strong> ' . esc($product->productCode) . '</div>
                            </div>
                        ';
                }
                $productsList = implode('', $products);
            }

            $paymentAddress  = !empty($row->paymentAddress) ? esc($row->paymentAddress) : '-';
            $shippingAddress = !empty($row->shippingAddress) ? esc($row->shippingAddress) : '-';

            $shippingMethod = '<span class="badge bg-primary">' . esc($row->shippingMethod ?? '-') . '</span>';
            $paymentMethod  = '<span class="badge bg-secondary">' . esc($row->paymentMethod ?? '-') . '</span>';
            $paymentAmount  = '₹' . number_format((float)$row->paymentAmount, 2);

            list($orderStatus, $orderStatusClass) = $this->customHelper->getOrderStatusDetails($row->orderStatus);
            $orderStatusSpan = '<span class="' . $orderStatusClass . '">' . esc($orderStatus) . '</span>';

            $paymentDate = !empty($row->paymentDateTime)
                ? $this->customHelper->formatDateTime($row->paymentDateTime)
                : '-';

            $deliveryInDays = !empty($row->deliveryInDays)
                ? esc($row->deliveryInDays)
                : '-';

            $createdAt = $this->customHelper->formatDateTime($row->checkOutHistoryCreatedAt);

            $actionBy = !empty($row->authUserInfo->authUserName)
                ? esc($row->authUserInfo->authUserName)
                : '-';

            $html .=
                <<<HTML
                    <tr>
                        <td>{$i}</td>
                        <td>{$userName}</td>
                        <td>{$cartIds}</td>
                        <td>{$productsList}</td>
                        <td>{$paymentAddress}</td>
                        <td>{$shippingAddress}</td>
                        <td>{$shippingMethod}</td>
                        <td>{$paymentMethod}</td>
                        <td>{$paymentAmount}</td>
                        <td>{$orderStatusSpan}</td>
                        <td>{$paymentDate}</td>
                        <td>{$deliveryInDays}</td>
                        <td>{$actionBy}</td>
                        <td>{$createdAt}</td>
                    </tr>
                HTML;
            $i++;
        }

        return $this->response->setJSON([
            'status' => true,
            'html'   => $html
        ]);
    }
}
