<?php

namespace App\Models\Admin;

class LoginModel
{
    public function submitLoginAJAX($email, $password)
    {
        if (empty($email) || empty($password)) {
            return [
                'status'  => false,
                'message' => 'Email and password are required.'
            ];
        }

        $client = \Config\Services::curlrequest();

        // LOGIN API CALL
        $loginAPI = LOCALHOST_8082 . REQUEST_AUTH_MAPPING . '/login';

        try {
            $response = $client->post($loginAPI, [
                'form_params' => [
                    'authUserEmailAddress' => $email,
                    'authUserPassword'     => $password
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }

        if (!isset($result->status) || $result->status !== 'success') {
            return [
                'status'  => false,
                'message' => $result->message ?? 'Invalid credentials.'
            ];
        }

        // STORE SESSION
        $session = session();
        $session->set([
            'admin_logged_true' => true,
            'admin_auth_token'  => $result->content->authToken
        ]);

        // FETCH AUTH USER DETAILS
        try {
            $getAuthURL = LOCALHOST_8082 . REQUEST_AUTH_MAPPING . '/get-auth';

            $authResponse = $client->get($getAuthURL, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'timeout' => 10
            ]);

            $authUser = json_decode($authResponse->getBody());

            if (isset($authUser->status) && $authUser->status === 'success') {
                $session->set('admin_auth_user_details', $authUser->content);
            }
        } catch (\Throwable $e) {
        }

        return [
            'status'  => true,
            'message' => 'Login successful.'
        ];
    }
}
