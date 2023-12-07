<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\User\UserDuelActiveService;

class UserDuelActiveController extends Controller
{
    public function __construct(
        private UserDuelActiveService $userDuelActiveService
    ) {}

    public function getUserDuelActive(): JsonResponse
    {
        try {
           return $this->userDuelActiveService->getUserDuelActive(auth()->id());
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
