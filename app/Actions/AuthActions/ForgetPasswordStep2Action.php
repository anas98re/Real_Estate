<?php

namespace App\Actions\AuthActions;

use App\Http\Requests\UserAuth\UserForgetPasswordStep2Request;
use App\Repository\AuthRepository;

class ForgetPasswordStep2Action
{
    public function __construct(protected AuthRepository $authRepository) {}

    public function __invoke(UserForgetPasswordStep2Request $request)
    {
        return $this->authRepository->forgetPasswordStep2($request->validated());
    }
}
