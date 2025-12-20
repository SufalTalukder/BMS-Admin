<?php

namespace App\Models\Admin;

class AuthActivityModel
{
    public function getAllAuthActivityAJAX()
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        $fetchAuthActivityAPI = LOCALHOST_8095 . REQUEST_AUTH_MAPPING . '/get-auth-action-logs';

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
