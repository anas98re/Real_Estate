<?php

namespace App\Actions\RealtyActions;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StorerealtyRequest;
use App\Http\Requests\UpdaterealtyRequest;
use App\Models\Realty;
use App\Repository\RealtyRepository;

class UpdateRealtyAction {
    protected $RealtyRepository;

    public function __construct(RealtyRepository $RealtyRepository) {
        return $this->RealtyRepository = $RealtyRepository;
    }

    public function __invoke(UpdaterealtyRequest $request, $id)
    {
        $id = $request->route('id');
        if (
            !PermissionsHelper::hasPermission('realty.update')
            || !PermissionsHelper::hasPermissionUpdateOrRemoveRealty($id)
        ) {
            $service = Realty::find($id);

            if (!$service) {
                return ApiResponseHelper::sendMessageResponse('Realty not found and Forbidden', 404, false);
            }
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $this->RealtyRepository->updateRealty($request, $id);
    }
}
