<?php

namespace Core\Api;

use Core\Controller\Controller;
use Core\Http\Response;

class ApiController extends Controller
{
    protected function success(array $data = [], string $message = 'Success', int $statusCode = 200): Response
    {
        return Response::json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    protected function error(string $message = 'Error', int $statusCode = 400, array $errors = []): Response
    {
        return Response::json([
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}

