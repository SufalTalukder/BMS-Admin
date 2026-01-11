<?php

namespace App\Models\Admin;

class AuthLoginAuditModel
{
    public function getAllLoginAuditsAJAX()
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        if (!$session->has('admin_auth_token')) {
            return (object) [
                'status'  => 'error',
                'message' => 'Unauthorized request.',
            ];
        }

        // FETCH AUTH LOGIN AUDIT API CALL
        $fetchAuthLoginAuditAPI = LOCALHOST_8082 . REQUEST_AUTH_MAPPING . '/get-auth-login-audit-details';

        try {
            $response = $client->get($fetchAuthLoginAuditAPI, [
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
            // print_r($result);
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }
}
