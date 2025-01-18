<?php

namespace App\Actions\RealtyActions;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StorerealtyRequest;
use App\Http\Requests\UpdaterealtyRequest;
use App\Models\Realty;
use App\Repository\RealtyRepository;
use Illuminate\Http\Request;

class RemoveRealtyAction {
    protected $RealtyRepository;

    public function __construct(RealtyRepository $RealtyRepository) {
        return $this->RealtyRepository = $RealtyRepository;
    }

    public function __invoke(Request $request)
    {
        $id = $request->route('id');
        if (
            !PermissionsHelper::hasPermission('realty.remove')
            || !PermissionsHelper::hasPermissionUpdateOrRemoveRealty($id)
        ) {
            $service = Realty::find($id);

            if (!$service) {
                return ApiResponseHelper::sendMessageResponse('Realty not found and Forbidden', 404, false);
            }
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->RealtyRepository->removeRealty($id);
    }
}
