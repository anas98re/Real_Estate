<?php

namespace App\Actions\AuthActions;

use App\Http\Requests\UserAuth\UserRegisterRequest;
use App\Repository\AuthRepository;

class RegisterAction
{
    public function __construct(protected AuthRepository $authRepository) {}

    public function __invoke(UserRegisterRequest $request)
    {
        return $this->authRepository->register($request->validated());
    }
}
