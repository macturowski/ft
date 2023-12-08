<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserDuelActionRequest;
use App\Http\Services\User\UserDuelActionService;


class UserDuelActionController extends Controller
{
    public function __construct(
        private UserDuelActionService $userDuelActionService
    ) {}

    public function storeUserDuelAction(UserDuelActionRequest $request): JsonResponse
    {
        try {
           return $this->userDuelActionService->storeUserDuelAction(auth()->id(), $request->get('id', null));
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
