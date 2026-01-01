<?php

namespace App\Models\Admin;

class CartModel
{
    public function getAllCartsAJAX($userId)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        $getAllCartsurl = LOCALHOST_8090 . REQUEST_AUTH_MAPPING . '/get-all-carts';

        try {
            $response = $client->get($getAllCartsurl, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'query' => [
                    'userId' => $userId
                ],
                'timeout' => 10
            ]);

            return json_decode($response->getBody());
        } catch (\Throwable $e) {
            return (object)[
                'status' => 'error',
                'message' => 'Authentication server unreachable'
            ];
        }
    }


    public function deleteCartAJAX($cartId, $userId)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // DELETE CART API CALL
        $deleteCartAPI = LOCALHOST_8090 . REQUEST_AUTH_MAPPING . '/delete-user-cart';

        try {
            $response = $client->delete($deleteCartAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'addToCartId'       => $cartId,
                    'userId'            => $userId
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Cart deleted successfully.'
                ];
            } else {
                return [
                    'status'  => false,
                    'message' => $result->message
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }
}
