<?php

namespace App\Models\Admin;

class BannerModel
{
    public function addBannerAJAX(array $imageFiles)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // ADD BANNER API CALL
        $addBannerAPI = LOCALHOST_8083 . REQUEST_AUTH_MAPPING . '/upload-multi-images';

        $multipart = [];

        foreach ($imageFiles as $file) {

            if (!$file instanceof \CodeIgniter\HTTP\Files\UploadedFile) {
                continue;
            }

            if ($file->isValid() && !$file->hasMoved()) {
                $multipart[] = [
                    'name'     => 'appBannerImage',
                    'contents' => fopen($file->getTempName(), 'rb'),
                    'filename' => $file->getName()
                ];
            }
        }

        if (empty($multipart)) {
            return [
                'status'  => false,
                'message' => 'No valid image files found'
            ];
        }

        try {
            $response = $client->post($addBannerAPI, [
                'headers' => [
                    'authToken'    => (string) $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'multipart' => $multipart,
                'timeout'   => 30
            ]);

            $result = json_decode($response->getBody());
            return [
                'status'  => isset($result->status) && $result->status === 'success',
                'message' => $result->message ?? 'Upload completed'
            ];
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function getAllBannersAJAX()
    {
        $client     = \Config\Services::curlrequest();
        $session    = session();

        // FETCH ALL BANNERS API CALL
        $fetchAllBannersAPI = LOCALHOST_8083 . REQUEST_AUTH_MAPPING . '/get-all-banner-images';

        try {
            $response = $client->get($fetchAllBannersAPI, [
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

    public function deleteBannerAJAX(array $bannerIds)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // DELETE BANNER API CALL
        $deleteBannerAPI = LOCALHOST_8083 . REQUEST_AUTH_MAPPING . '/delete-multi-images';

        try {
            $response = $client->delete($deleteBannerAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'json' => array_map('intval', $bannerIds),
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());

            return [
                'status'  => isset($result->status) && $result->status === 'success',
                'message' => $result->message ?? 'Banner(s) deleted successfully.'
            ];
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }
}
