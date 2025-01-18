<?php

namespace App\Actions\AuthActions;

use App\Http\Requests\UserAuth\UserLoginRequest;
use App\Repository\AuthRepository;

class LoginAction
{
    public function __construct(protected AuthRepository $authRepository) {}

    public function __invoke(UserLoginRequest $request)
    {
        return $this->authRepository->loginUser($request->validated());
    }
}
