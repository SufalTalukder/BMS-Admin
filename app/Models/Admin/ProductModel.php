<?php

namespace App\Models\Admin;

class ProductModel
{
    public function addProductAJAX($data)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // ADD PRODUCT API CALL
        $addCategoryAPI = LOCALHOST_8086 . REQUEST_AUTH_MAPPING . '/create-product';

        try {
            $response = $client->post($addCategoryAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'query' => [
                    'categoryId'    => $data['productCategory'],
                    'subCategoryId' => $data['productSubCategory'],
                    'languageId'    => $data['productLanguage']
                ],
                'json' => [
                    'productName'               => $data['productName'],
                    'productBrand'              => $data['productBrand'],
                    'productCode'               => $data['productCode'],
                    'productAvailability'       => $data['productAvailability'],
                    'productPrice'              => $data['productPrice'],
                    'productDetails'            => $data['productDetails'],
                    'productStock'              => $data['productStock'],
                    'productActive'             => $data['productActive']
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Product added successfully.'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function getAllProductsAJAX()
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // FETCH SUBCATEGORIES API CALL
        $fetchLangaugesAPI = LOCALHOST_8086 . REQUEST_AUTH_MAPPING . '/get-all-products';

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

    public function getProductDetailsAJAX($getSubCategoryId)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // FETCH PRODUCT DETAILS API CALL
        $fetchCategoryDetailsAPI = LOCALHOST_8086 . REQUEST_AUTH_MAPPING . '/get-product';

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

    public function updateProductAJAX($updateSubcategoryId, $data)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // EDIT PRODUCT API CALL
        $editCategoryAPI = LOCALHOST_8086 . REQUEST_AUTH_MAPPING . '/update-product-details';

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
                    'message' => 'Product updated successfully.'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function deleteProductAJAX($deleteSubCategoryId)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // DELETE PRODUCT API CALL
        $deleteCategoryAPI = LOCALHOST_8086 . REQUEST_AUTH_MAPPING . '/delete-product';

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
                    'message' => 'Product deleted successfully.'
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
