<?php

namespace App\Actions\AuthActions;

use App\Http\Requests\UserAuth\UserResendVerificationRequest;
use App\Repository\AuthRepository;

class ForgetPaswordResendVerificationAction
{
    public function __construct(protected AuthRepository $authRepository) {}

    public function __invoke(UserResendVerificationRequest $request)
    {
        return $this->authRepository->OTPResendVerification($request->validated()['email']);
    }
}
