<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\User\UserDataService;

class UserDataController extends Controller
{
    public function __construct(
        private UserDataService $userDataService
    ) {}

    public function getUserData(): JsonResponse
    {
        try {
           return $this->userDataService->getUserData(auth()->id());
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
