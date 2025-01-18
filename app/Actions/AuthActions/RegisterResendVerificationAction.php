<?php

namespace App\Actions\AuthActions;

use App\Http\Requests\UserAuth\UserResendVerificationRequest;
use App\Repository\AuthRepository;

class RegisterResendVerificationAction
{
    public function __construct(protected AuthRepository $authRepository) {}

    public function __invoke(UserResendVerificationRequest $request)
    {
        return $this->authRepository->registerResendVerification($request->validated()['email']);
    }
}
