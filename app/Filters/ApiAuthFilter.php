<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $apiKey = $request->getHeaderLine("X-API-KEY");
        if (empty($apiKey)) {
            $response = Services::response();
            return $response->setJSON(["status" => false, "message" => "Please enter X-API-KEY to access API!"])->setStatusCode(401);
        } else if (!$this->validate_API_Key($apiKey)) {
            $response = Services::response();
            return $response->setJSON(["status" => false, "message" => "X-API-KEY is invalid!"])->setStatusCode(401);
        }

        return $request;
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}

    private function validate_API_Key($apiKey)
    {
        return ($apiKey == API_AUTHENTICATION) ? true : false;
    }
}
