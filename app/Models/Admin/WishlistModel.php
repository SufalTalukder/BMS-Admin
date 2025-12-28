<?php

namespace App\Models\Admin;

class WishlistModel
{
    public function addWishlistAJAX($data)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // ADD WISHLIST API CALL
        $addWishlistAPI = LOCALHOST_8091 . REQUEST_AUTH_MAPPING . '/create-user-add-to-favourite';

        try {
            $response = $client->post($addWishlistAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'query' => [
                    'productId'     => $data['productId'],
                    'userId'        => $data['userId']
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Wishlist added successfully.'
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

    public function getAllWishlistsAJAX()
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // FETCH WISHLISTS API CALL
        $fetchWishlistsAPI = LOCALHOST_8091 . REQUEST_AUTH_MAPPING . '/get-all-user-favourites';

        try {
            $response = $client->get($fetchWishlistsAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            return $result;
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function deleteWishlistAJAX($wishlistId, $userId)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // DELETE WISHLIST API CALL
        $deleteWishlistAPI = LOCALHOST_8091 . REQUEST_AUTH_MAPPING . '/remove-user-favourite';

        try {
            $response = $client->delete($deleteWishlistAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'addToFavouriteId' => $wishlistId,
                    'userId'           => $userId
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Wishlist deleted successfully.'
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
