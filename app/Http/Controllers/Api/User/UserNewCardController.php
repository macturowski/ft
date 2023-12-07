<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\User\UserCardService;

class UserNewCardController extends Controller
{
    public function __construct(
        private UserCardService $userCardService
    ) {}

    public function storeNewCard(): JsonResponse
    {
        try {
           return $this->userCardService->storeNewCard(auth()->id());
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
