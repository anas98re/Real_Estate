<?php

namespace App\Actions\RealtyActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StorerealtyRequest;
use App\Http\Requests\UpdaterealtyRequest;
use App\Repository\RealtyRepository;
use Illuminate\Http\Request;

class GetRealtiesbyUserAction {
    protected $RealtyRepository;

    public function __construct(RealtyRepository $RealtyRepository) {
        return $this->RealtyRepository = $RealtyRepository;
    }

    public function __invoke(Request $request)
    {
        $id = $request->route('userId');
        if (!PermissionsHelper::hasPermission('realty.show')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->RealtyRepository->getRealtiesByUser($request, $id);
    }
}
