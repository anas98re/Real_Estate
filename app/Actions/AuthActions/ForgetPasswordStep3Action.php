<?php

namespace App\Actions\AuthActions;

use App\Http\Requests\UserAuth\UserForgetPasswordStep3Request;
use App\Repository\AuthRepository;

class ForgetPasswordStep3Action
{
    public function __construct(protected AuthRepository $authRepository) {}

    public function __invoke(UserForgetPasswordStep3Request $request)
    {
        return $this->authRepository->forgetPasswordStep3($request->validated());
    }
}
