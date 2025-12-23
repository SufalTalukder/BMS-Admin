<?php

namespace App\Models\Admin;

class SubCategoryModel
{
    public function addSubCategoryAJAX($data)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // ADD SUBCATEGORY API CALL
        $addCategoryAPI = LOCALHOST_8085 . REQUEST_AUTH_MAPPING . '/create-sub-category';

        try {
            $response = $client->post($addCategoryAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'json' => [
                    'subCategoryName'         => $data['subCategoryName'],
                    'subCategoryActive'       => $data['subCategoryActive']
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Subcategory added successfully.'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function getAllSubCategoriesAJAX()
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // FETCH SUBCATEGORIES API CALL
        $fetchLangaugesAPI = LOCALHOST_8085 . REQUEST_AUTH_MAPPING . '/get-all-sub-categories';

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

    public function getSubCategoryDetailsAJAX($getSubCategoryId)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // FETCH SUBCATEGORY DETAILS API CALL
        $fetchCategoryDetailsAPI = LOCALHOST_8085 . REQUEST_AUTH_MAPPING . '/get-sub-category';

        try {
            $response = $client->get($fetchCategoryDetailsAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'query' => [
                    'subCategoryId' => $getSubCategoryId
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

    public function updateSubCategoryAJAX($updateSubcategoryId, $data)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // EDIT SUBCATEGORY API CALL
        $editCategoryAPI = LOCALHOST_8085 . REQUEST_AUTH_MAPPING . '/update-sub-category-details';

        try {
            $response = $client->put($editCategoryAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'subCategoryId' => $updateSubcategoryId
                ],
                'json' => [
                    'subCategoryName'         => $data['updateCategoryName'],
                    'subCategoryActive'       => $data['updateCategoryActive']
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Subcategory updated successfully.'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function deleteSubCategoryAJAX($deleteSubCategoryId)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // DELETE SUBCATEGORY API CALL
        $deleteCategoryAPI = LOCALHOST_8085 . REQUEST_AUTH_MAPPING . '/delete-sub-category';

        try {
            $response = $client->delete($deleteCategoryAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'subCategoryId' => $deleteSubCategoryId
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Subcategory deleted successfully.'
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
