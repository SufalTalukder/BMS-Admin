<?php

namespace App\Models\Admin;

class UserModel
{
    public function addUserAJAX($data)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // ADD USER API CALL
        $addUserAPI = LOCALHOST_8081 . REQUEST_AUTH_MAPPING . '/create-user';

        try {
            $response = $client->post($addUserAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'json' => [
                    'fullName'              => $data['addName'],
                    'emailAddress'          => $data['addEmail'],
                    'phoneNumber'           => $data['addPhoneNumber'],
                    'dob'                   => $data['addDOB'],
                    'userAddress'           => $data['addAddress'],
                    'userActive'            => $data['addActive']
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'User added successfully.'
                ];
            } else if (isset($result->status) && $result->status !== 'success') {
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

    public function getAllUsersAJAX()
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // FETCH ALL USERS API CALL
        $fetchAllUsersAPI = LOCALHOST_8081 . REQUEST_AUTH_MAPPING . '/get-users-list';

        try {
            $response = $client->get($fetchAllUsersAPI, [
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

    public function getUserDetailsAJAX($userId)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // FETCH USER DETAILS API CALL
        $fetchUserDetailsAPI = LOCALHOST_8081 . REQUEST_AUTH_MAPPING . '/get-user-details';

        try {
            $response = $client->get($fetchUserDetailsAPI, [
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

            $result = json_decode($response->getBody());
            return $result;
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function updateUserAJAX($userId, $data)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // EDIT USER API CALL
        $editUserAPI = LOCALHOST_8081 . REQUEST_AUTH_MAPPING . '/update-user-details';

        try {
            $response = $client->patch($editUserAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'userId' => $userId
                ],
                'json' => [
                    'fullName'          => $data['updateName'],
                    'emailAddress'      => $data['updateEmail'],
                    'phoneNumber'       => $data['updatePhoneNumber'],
                    'dob'               => $data['updateDOB'],
                    'userAddress'       => $data['updateAddress'],
                    'userActive'        => $data['updateActive'],
                    'userImage'         => $data['userImage'] ?? null
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'User updated successfully.'
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

    public function deleteUserAJAX($userId)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // DELETE USER API CALL
        $deleteUserAPI = LOCALHOST_8081 . REQUEST_AUTH_MAPPING . '/delete-user';

        try {
            $response = $client->delete($deleteUserAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'userId' => $userId
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'User deleted successfully.'
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
