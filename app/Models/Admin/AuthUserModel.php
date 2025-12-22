<?php

namespace App\Models\Admin;

class AuthUserModel
{
    public function addAuthUserAJAX($data)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // ADD AUTH USER API CALL
        $addAuthUserAPI = LOCALHOST_8082 . REQUEST_AUTH_MAPPING . '/create';

        try {
            $response = $client->post($addAuthUserAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'json' => [
                    'authUserName'         => $data['authUserName'],
                    'authUserEmailAddress' => $data['authUserEmailAddress'],
                    'authUserPhoneNumber'  => $data['authUserPhoneNumber'],
                    'authUserPassword'     => $data['authUserPassword'],
                    'authUserType'         => $data['authUserType'],
                    'authUserActive'       => $data['authUserActive'],
                    'authUserImage'        => $data['authUserImage'] ?? null
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Auth user added successfully.'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function getAllAuthUsersAJAX()
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // FETCH ALL AUTH USERS API CALL
        $fetchAllAuthUsersAPI = LOCALHOST_8082 . REQUEST_AUTH_MAPPING . '/get-all-auth-users';

        try {
            $response = $client->get($fetchAllAuthUsersAPI, [
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

    public function getAuthUserDetailsAJAX($authId)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // FETCH AUTH USER DETAILS API CALL
        $fetchAuthUserDetailsAPI = LOCALHOST_8082 . REQUEST_AUTH_MAPPING . '/get-auth-details';

        try {
            $response = $client->get($fetchAuthUserDetailsAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'query' => [
                    'authUserId' => $authId
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

    public function updateAuthUserAJAX($authId, $data)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // EDIT AUTH USER API CALL
        $editAuthUserAPI = LOCALHOST_8082 . REQUEST_AUTH_MAPPING . '/update-details';

        try {
            $response = $client->put($editAuthUserAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'authUserId' => $authId
                ],
                'json' => [
                    'authUserId'           => $authId,
                    'authUserName'         => $data['authUserName'],
                    'authUserEmailAddress' => $data['authUserEmailAddress'],
                    'authUserPhoneNumber'  => $data['authUserPhoneNumber'],
                    'authUserPassword'     => $data['authUserPassword'],
                    'authUserType'         => $data['authUserType'],
                    'authUserActive'       => $data['authUserActive'],
                    'authUserImage'        => $data['authUserImage'] ?? null
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Auth user updated successfully.'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function deleteAuthUserAJAX($authId)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // DELETE AUTH USER API CALL
        $deleteAuthUserAPI = LOCALHOST_8082 . REQUEST_AUTH_MAPPING . '/delete';

        try {
            $response = $client->delete($deleteAuthUserAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'rqstAuthUserId' => $authId
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Auth user deleted successfully.'
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
