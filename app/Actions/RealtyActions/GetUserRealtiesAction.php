<?php

namespace App\Actions\RealtyActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StorerealtyRequest;
use App\Http\Requests\UpdaterealtyRequest;
use App\Repository\RealtyRepository;
use Illuminate\Http\Request;

class GetUserRealtiesAction {
    protected $RealtyRepository;

    public function __construct(RealtyRepository $RealtyRepository) {
        return $this->RealtyRepository = $RealtyRepository;
    }

    public function __invoke(Request $request)
    {
        return $this->RealtyRepository->getUserRealties($request);
    }
}
