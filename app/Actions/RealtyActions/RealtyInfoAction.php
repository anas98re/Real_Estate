<?php

namespace App\Actions\RealtyActions;

use App\ApiHelper\FileHelper;
use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StorerealtyRequest;
use App\Models\Photo;
use App\Repository\RealtyRepository;
use Illuminate\Http\Request;

class RealtyInfoAction
{
    protected $RealtyRepository;

    public function __construct(RealtyRepository $RealtyRepository)
    {
        return $this->RealtyRepository = $RealtyRepository;
    }

    public function __invoke(Request $request)
    {
        if (!PermissionsHelper::hasPermission('realty.show')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $this->RealtyRepository->getRealtyById($id = $request->route('id'));
    }
}
