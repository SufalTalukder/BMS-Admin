<?php

namespace App\Models\Admin;

class CheckoutHistoryModel
{
    public function getAllCheckoutHistoriesAJAX()
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // FETCH CHECKOUT HISTORIES API CALL
        $fetchAuthActivityAPI = LOCALHOST_8087 . REQUEST_AUTH_MAPPING . '/get-all-checkout-histories';

        try {
            $response = $client->get($fetchAuthActivityAPI, [
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
}
