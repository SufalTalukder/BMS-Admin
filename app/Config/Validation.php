<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public array $verify_signup = [
        'name'     => 'required',
        'password'     => 'required|max_length[255]',
        'confirmPassword' => 'required|max_length[255]|matches[password]',
        'email'        => 'required|max_length[254]|valid_email|is_unique[user_accounts.email]',
        'mobileNo'        => 'required|is_unique[user_accounts.mobileNo]',
        // 'mobileNo'        => 'required',
        'gender'     => 'required'
    ];

    public array $verify_login = [
        'password'     => 'required',
        'emailMobile'        => 'required',
    ];

    public array $otp_verification = [
        'emailMobile' => 'required',
        'otp'     => 'required',
    ];

    public array $forgot_password = [
        'emailMobile'     => 'required',
    ];

    public array $update_password = [
        'password'     => 'required',
        'confirmPassword' => 'required|max_length[255]|matches[password]',
        'userId'     => 'required',
    ];


    public array $search_by_storeId = [
        'storeId'     => 'required',
    ];

    public array $search_by_productId = [
        'productId'     => 'required',
        'userId'    => 'required',
    ];

    public array $search_by_userId = [
        'userId'     => 'required',
    ];

    public array $pay_bill = [
        'userId'     => 'required',
        'storeId'     => 'required',
        'payAmount'     => 'required',
    ];

    public array $get_store_referral_code = [
        'userId' => 'required',
        'storeId' => 'required'
    ];

    ////:::::::::::::FOR SELLER :::::::////

    public array $verify_signup_seller = [
        'name'     => 'required',
        'password'     => 'required|max_length[255]',
        'confirmPassword' => 'required|max_length[255]|matches[password]',
        'email'        => 'required|max_length[254]|valid_email|is_unique[user_accounts.email]',
        'mobileNo'        => 'required|is_unique[user_accounts.mobileNo]',
        // 'address'        => 'required'


    ];
    public array $edit_user_profile = [
        'userId'            => 'required',
        // 'userType'       => 'required',
        // 'name'              => 'required',
        // 'mobileNo'          => 'required',
        // 'email'             => 'required',
        // 'password'          => 'required',
        // 'address'           => 'required' ,
        // 'profileImageLink'  => 'required'

    ];

    public array $add_store = [
        'userId' => 'required',
        'gstNo' => 'required',
        'name' => 'required',
        'catId' => 'required',
        'panNo' => 'required',
        'address' => 'required',
        'pinCode' => 'required',
        'stateId' => 'required',
        'cityId' => 'required',
        'latitude' => 'required',
        'longitude' => 'required',
    ];

    public array $verify_seller_document = [
        'type'     => 'required',
        'variation'     => 'required',
        'docImage'     => 'required',
    ];


    public array $add_bank_account = [
        'userId'     => 'required',
        'accountHolderName'     => 'required',
        'accountNumber'     => 'required',
        'ifscCode'     => 'required',
    ];


    public array $add_new_product = [
        'storeId'       => 'required',
        'categoryId'    => 'required',
        'typeId'        => 'required',
        'name'          => 'required',
        'brandName'     => 'required',
        'listingstatus' => 'required',
        'title'         => 'required',
        'description'   => 'required',
        'mrpPrice'      => 'required',
        'sellingPrice'  => 'required',

    ];

    public array $change_product_listing_status = [
        'storeId' => 'required',
        'productId' => 'required',
        'listingStatus' => 'required'
    ];

    public array $add_new_offer = [
        'storeId'     => 'required',
        'title'     => 'required',
        'applicableOnProduct'     => 'required',
        'description'     => 'required',
        'mrpPrice'     => 'required',
        'dicountType'     => 'required',
        'discount'     => 'required',
        'startDate'     => 'required',
        'startTime'     => 'required',
        'endDate'     => 'required',
        'endTime'     => 'required',
        'minAmount'     => 'required',
        // 'state'     => 'required',
    ];


    public array $edit_offer = [
        'offerId'     => 'required',
        'title'     => 'required',
        'applicableOnProduct'     => 'required',
        'description'     => 'required',
        'mrpPrice'     => 'required',
        'dicountType'     => 'required',
        'discount'     => 'required',
        'startDate'     => 'required',
        'startTime'     => 'required',
        'endDate'     => 'required',
        'endTime'     => 'required',
        'minAmount'     => 'required',
        // 'offerImage'     => 'required',
        'state'     => 'required',
    ];

    public array $change_offer_status = [
        'storeId' => 'required',
        'offerId' => 'required',
        'offerStatus' => 'required'
    ];

    public array $create_push_notification = [
        'storeId' => 'required',
        'userType' => 'required',
        'distanceUnder' => 'required',
        'notificationTitle' => 'required',
        'notificationDescription' => 'required'
    ];

    public array $edit_push_notification = [
        'notificationId' => 'required',
        'userType' => 'required',
        'distanceUnder' => 'required',
        'notificationTitle' => 'required',
        'notificationDescription' => 'required',

    ];

    public array $delete_push_notification = [
        'storeId' => 'required',
        'notificationId' => 'required'
    ];

    public array $create_seller_referral_reward = [
        'referredUserId' =>  'required',
        'referralRequestId' => 'required',
        'referrerRewardType' => 'required',
        'referrerReward' => 'required',
        'referredRewardType' => 'required',
        'referredReward' => 'required'
    ];

    public array $delete_store_offer = [
        'storeId' => 'required',
        'offerId' => 'required',
    ];
    public array $deactivate_store_offer = [

        'storeId' => 'required',
        'offerList' => 'required',
    ];
    public array $activate_store_offer = [

        'storeId' => 'required',
        'offerList' => 'required',
    ];

    public array $sign_out = [
        'userId' => 'required'
    ];

    public array $delete_product = [
        'productId' => 'required'
    ];

    public array $stock_status = [

        'storeId'     => 'required',
        'productId'   => 'required',
        'stockStatus' => 'required'
    ];

    public array $user_online_status_change = [
        'userId'       => 'required',
        'OnlineStatus' => 'required'
    ];
    public array   $save_new_product = [
        'name' => 'required',
        'storeId' => 'required',
        'categoryId' => 'required',
        'typeId' => 'required',
        'name' => 'required',
        'brandName' => 'required',
        // 'listingstatus' => 'required',
        'title' => 'required',
        'description' => 'required',
        'color' => 'required',
        // 'idealFor' => 'required',
        'availableSize' => 'required',
        'mrpPrice' => 'required',
        'sellingPrice' => 'required',
        'stockQty' => 'required',
        // 'stock_status' => 'required', // Assuming 'ON' or 'OFF'
        'countryOfOrigin' => 'required',
        'manufacturerDetails' => 'required',
        'hsnCode' => 'required',
        'gstPercent' => 'required',

    ];

    public array   $get_user_by_id =  [
        'uId' => 'required',
        // 'name' => 'required',
        // 'email' => 'required',
        // 'mobileNo' => 'required',
        // 'address' => 'required',
        // 'password' => 'required'
    ];

    public array $save_stock_status_change = [
        'saveId' => 'required',
        'storeId' => 'required',
        'stockStatus' => 'required'
    ];

    public array $save_product_listingStatus_change = [
        'saveId' => 'required',
        'storeId' => 'required',
        'listingstatus' => 'required'
    ];

    public array $get_product_by_uid =  [
        'productId' => 'required',
    ];

    public  array  $update_product = [];

    public array $authenticate_admin = [
        'email' => 'required',
        'password' => 'required',
    ];

    public array $add_store_category = [
        'name' => 'required'
    ];

    public array $deleteAll_push_notification = [
        'storeId' =>  'required',
        'notificationId' => 'required'
    ];
    public array $get_product_list = [
        'storeId' =>  'required'
    ];
    public array $upload_store_images = [
        'storeId' =>  'required',
    ];
    public array $send_friend_request = [
        'userId'    => 'required',
        'friendId'  => 'required'
    ];
    public array $save_product_publish = [
        'saveId' =>  'required'
    ];
    public array $send_chat_message = [
        'chatId' => 'required',
        'senderUserId' =>  'required',
        'receiverUserId' =>  'required',
        'content' =>  'required',
        'type' => 'required'

    ];
    public array $forget_password = [
        'PhoneEmail' => 'required'
    ];
    public array $forget_password_otp_validation = [
        'PhoneEmail' => 'required',
        'otp' => 'required'

    ];
    public array $send_again_push_notification = [
        'uId' => 'required'
    ];
    public array $save_fcm_token = [
        'userId' => 'required',
        'fcm_token' => 'required'
    ];
    public array $edit_buyer_profile = [
        'userId' => 'required'
    ];
    public array $buyer_sign_out = [
        'userId' => 'required'
    ];
    public array $add_favorites_product = [
        'userId' => 'required',
        'productId' => 'required',
        'storeId' => 'required'
    ];
    public array $favorites_product_list = [
        'userId' => 'required',
    ];
    public array $remove_product_favorites_list = [
        'productId' => 'required',
        'userId' => 'required',
        'storeId' => 'required'
    ];
    public array $save_user_location = [
        'userId' => 'required',
        'address' =>  'required',
        'latitude' => 'required',
        'longitude' => 'required'
    ];
    public array $store_wish_popular_product = [
        'storeId' => 'required',
    ];

    public array $only_for_product_image_upload = [
        'productId' => 'required'
    ];

    public array $notify_seller = [
        'storeId' => 'required',
        'userId' => 'required',
        'productId' => 'required',
    ];
    public array $search_location = [
        'searchLocation' => 'required',
        'latitude' => 'required',
        'longitude' => 'required',
        'radius' => 'required',
    ];

    public array $buyer_store_details = [
        'storeId' => 'required',
        'userId' => 'required'

    ];
}
