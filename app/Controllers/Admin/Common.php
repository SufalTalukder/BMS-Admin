<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Common extends BaseController
{
    protected $db;
    protected $current_datetime;
    protected $validation;
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->validation = \Config\Services::validation();
        $this->current_datetime = date('Y-m-d H:i:s');
        // $this->db = \Config\Database::connect();
    }

    public function GUID($prefix = "")
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        $unique_code = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));

        if (!empty($prefix)) {
            return $prefix . "_" . $unique_code . "_" . time();
        } else {
            return $unique_code;
        }
    }

    public function send_response($data, $status_code)
    {
        return $this->response->setJSON($data)->setStatusCode($status_code);
    }

    public function init_admin_model()
    {
        $adminModel = new \App\Models\Admin\AdminModel();
        return $adminModel;
    }

    public function init_user_model()
    {
        $userModel = new \App\Models\Admin\UserModel();
        return $userModel;
    }

    public function init_product_model()
    {
        $productModel = new \App\Models\Admin\ProductModel();
        return $productModel;
    }
    public function init_store_model()
    {
        $storeModel = new \App\Models\Admin\StoreModel();

        return  $storeModel;
    }

    public function init_chat_model()
    {
        $chatModel = new \App\Models\Seller\ChatFriendModel;
        return $chatModel;
    }
    public function init_chat_friend_model()
    {
        $chatFriendModel = new \App\Models\Seller\ChatFriendModel;
        return $chatFriendModel;
    }
}
