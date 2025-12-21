<?php

namespace App\Models\Admin;

class CategoryModel
{
    public function addCategoryAJAX($data)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // ADD CATEGORY API CALL
        $addCategoryAPI = LOCALHOST_8084 . REQUEST_AUTH_MAPPING . '/create-category';

        try {
            $response = $client->post($addCategoryAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'json' => [
                    'categoryName'         => $data['categoryName'],
                    'categoryActive'       => $data['categoryActive']
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Category added successfully.'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function getAllCategoriesAJAX()
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // FETCH CATEGORIES API CALL
        $fetchLangaugesAPI = LOCALHOST_8084 . REQUEST_AUTH_MAPPING . '/get-all-categories';

        try {
            $response = $client->get($fetchLangaugesAPI, [
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

    public function getCategoryDetailsAJAX($getCategoryId)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // FETCH CATEGORY DETAILS API CALL
        $fetchCategoryDetailsAPI = LOCALHOST_8084 . REQUEST_AUTH_MAPPING . '/get-category';

        try {
            $response = $client->get($fetchCategoryDetailsAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'query' => [
                    'categoryId' => $getCategoryId
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

    public function updateCategoryAJAX($updateCategoryId, $data)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // EDIT CATEGORY API CALL
        $editCategoryAPI = LOCALHOST_8084 . REQUEST_AUTH_MAPPING . '/update-category-details';

        try {
            $response = $client->put($editCategoryAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'categoryId' => $updateCategoryId
                ],
                'json' => [
                    'categoryName'         => $data['updateCategoryName'],
                    'categoryActive'       => $data['updateCategoryActive']
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Category updated successfully.'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function deleteCategoryAJAX($deleteCategoryId)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // DELETE CATEGORY API CALL
        $deleteCategoryAPI = LOCALHOST_8084 . REQUEST_AUTH_MAPPING . '/delete-category';

        try {
            $response = $client->delete($deleteCategoryAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'categoryId' => $deleteCategoryId
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Category deleted successfully.'
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
