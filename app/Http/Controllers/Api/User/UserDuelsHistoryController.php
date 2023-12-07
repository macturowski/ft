<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\User\UserDuelsHistoryService;

class UserDuelsHistoryController extends Controller
{
    public function __construct(
        private UserDuelsHistoryService $userDuelsHistoryervice
    ) {}

    public function getDuelsHistory(): JsonResponse
    {
        try {
           return $this->userDuelsHistoryervice->getDuelsHistory(auth()->id());
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
