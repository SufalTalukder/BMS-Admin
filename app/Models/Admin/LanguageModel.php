<?php

namespace App\Models\Admin;

class LanguageModel
{
    public function addLanguageAJAX($data)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // ADD LANGUAGE API CALL
        $addLanguageAPI = LOCALHOST_8088 . REQUEST_AUTH_MAPPING . '/create-language';

        try {
            $response = $client->post($addLanguageAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'json' => [
                    'languageName'         => $data['languageName'],
                    'languageActive'       => $data['languageActive']
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Language added successfully.'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function getLanguagesAJAX()
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // FETCH LANGUAGES API CALL
        $fetchLangaugesAPI = LOCALHOST_8088 . REQUEST_AUTH_MAPPING . '/get-all-languages';

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

    public function getLanguageDetailsAJAX($getLanguageId)
    {
        $client  = \Config\Services::curlrequest();
        $session = session();

        // FETCH LANGUAGE DETAILS API CALL
        $fetchLanguageDetailsAPI = LOCALHOST_8088 . REQUEST_AUTH_MAPPING . '/get-language';

        try {
            $response = $client->get($fetchLanguageDetailsAPI, [
                'headers' => [
                    'authToken'    => $session->get('admin_auth_token'),
                    'x-api-key'    => XAPIKEY,
                    'x-api-secret' => XAPISECRET,
                    'Accept'       => 'application/json'
                ],
                'query' => [
                    'languageId' => $getLanguageId
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

    public function updateLanguageAJAX($updateLanguageId, $data)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // EDIT LANGUAGE API CALL
        $editLanguageAPI = LOCALHOST_8088 . REQUEST_AUTH_MAPPING . '/update-language-details';

        try {
            $response = $client->put($editLanguageAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'languageId' => $updateLanguageId
                ],
                'json' => [
                    'languageName'         => $data['updateLanguageName'],
                    'languageActive'       => $data['updateLanguageActive']
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Language updated successfully.'
                ];
            }
        } catch (\Throwable $e) {
            return [
                'status'  => false,
                'message' => 'Authentication server is unreachable.'
            ];
        }
    }

    public function deleteLanguageAJAX($deleteLanguageId)
    {
        $client = \Config\Services::curlrequest();
        $session = session();

        // DELETE LANGUAGE API CALL
        $deleteLanguageAPI = LOCALHOST_8088 . REQUEST_AUTH_MAPPING . '/delete-language';

        try {
            $response = $client->delete($deleteLanguageAPI, [
                'headers' => [
                    'authToken'     => $session->get('admin_auth_token'),
                    'x-api-key'     => XAPIKEY,
                    'x-api-secret'  => XAPISECRET,
                    'Accept'        => 'application/json'
                ],
                'query' => [
                    'languageId' => $deleteLanguageId
                ],
                'timeout' => 10
            ]);

            $result = json_decode($response->getBody());
            if (isset($result->status) && $result->status === 'success') {
                return [
                    'status'  => true,
                    'message' => 'Language deleted successfully.'
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
