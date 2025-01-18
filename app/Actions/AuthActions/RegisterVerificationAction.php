<?php

namespace App\Actions\AuthActions;

use App\Http\Requests\UserAuth\UserRegisterVerificationRequest;
use App\Repository\AuthRepository;

class RegisterVerificationAction
{
    public function __construct(protected AuthRepository $authRepository) {}

    public function __invoke(UserRegisterVerificationRequest $request)
    {
        return $this->authRepository->registerVerification($request->validated());
    }
}
