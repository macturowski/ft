<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\UserDuelStartRequest;
use App\Http\Services\User\UserDuelStartService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class UserDuelStartController extends Controller
{
    public function __construct(
        private UserDuelStartService $userDuelStartService
    ) {}

    public function storeNewDuel(UserDuelStartRequest $request): JsonResponse
    {
        try {
           return $this->userDuelStartService->storeNewDuel(auth()->id());
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
