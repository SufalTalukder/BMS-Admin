<?php

namespace App\Models\Admin;

class NewsletterModel
{
    public function addNewsletterAJAX($data)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // ADD NEWSLETTER API CALL
        $addNewsletterAPI = LOCALHOST_8089 . REQUEST_AUTH_MAPPING . '/create-newsletter';

        try {
            $response = $client->post($addNewsletterAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'query' => [
                    'userId'                    => $data['userId']
                ],
                'json' => [
                    'newsletterToggle'          => $data['addSubscribe']
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status)) {
                return [
                    'status'  => true,
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

    public function getAllNewslettersAJAX()
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // GET ALL NEWSLETTER API CALL
        $getAllNewslettersurl = LOCALHOST_8089 . REQUEST_AUTH_MAPPING . '/get-all-newsletters';

        try {
            $response = $client->get($getAllNewslettersurl, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'timeout' => 10
            ]);

            return json_decode($response->getBody());
        } catch (\Throwable $e) {
            return (object)[
                'status' => 'error',
                'message' => 'Authentication server unreachable'
            ];
        }
    }

    public function updateNewsletterAJAX($newsletterId, $userId, $toggleData)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // EDIT NEWSLETTER API CALL
        $editNewsletterAPI = LOCALHOST_8089 . REQUEST_AUTH_MAPPING . '/update-newsletter-details';

        try {
            $response = $client->patch($editNewsletterAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'newsletterId'              => $newsletterId,
                    'userId'                    => $userId,
                    'newsletterToggle'          => $toggleData
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Newsletter updated successfully.'
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
