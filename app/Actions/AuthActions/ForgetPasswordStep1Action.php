<?php

namespace App\Actions\AuthActions;

use App\Http\Requests\UserAuth\UserForgetPasswordStep1Request;
use App\Repository\AuthRepository;

class ForgetPasswordStep1Action
{
    public function __construct(protected AuthRepository $authRepository) {}

    public function __invoke(UserForgetPasswordStep1Request $request)
    {

        return $this->authRepository->forgetPasswordStep1($request->validated());
    }
}
