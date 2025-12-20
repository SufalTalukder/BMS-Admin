<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;

class SellerApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $apiKey = $request->getHeaderLine('X-API-KEY');
        if (empty($apiKey)) {
            $response =  Services::response();
            return  $response->setJSON(["status"  => false, "Message" => "Please Enter You X-API-KEY Excess This API !! "])->setStatusCode(401);
        } elseif (!$this->validationKey($apiKey)) {
            $response = Services::response();
            return $response->setJSON(["status" => false, "message" => "X-API-KEY is invalid!"])->setStatusCode(401);
        } else {
            return $request;
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}

    private function validationKey($apiKey)
    {
        return ($apiKey == SELLER_API_AUTHENTICATION) ? true : false;
    }
}
