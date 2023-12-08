<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function errorResponse(mixed $message = [], int $code): JsonResponse
    {   
        $errorCode = ! in_array((int) $code, ['400', '401', '403', '422', '429', '404', '500']) ? 400 : $code;

        return response()->json(['error' => $message], $errorCode);
    }
}
