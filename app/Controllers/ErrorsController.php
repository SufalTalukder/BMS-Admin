<?php

namespace App\Controllers;

class ErrorsController extends BaseController
{
    public function error404()
    {
        return response()
            ->setStatusCode(404)
            ->setBody(view('errors/custom_404_view'));
    }
}
