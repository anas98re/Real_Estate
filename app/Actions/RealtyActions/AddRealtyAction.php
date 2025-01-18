<?php

namespace App\Actions\RealtyActions;

use App\ApiHelper\FileHelper;
use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StorerealtyRequest;
use App\Models\Photo;
use App\Repository\RealtyRepository;

class AddRealtyAction
{
    protected $RealtyRepository;

    public function __construct(RealtyRepository $RealtyRepository)
    {
        return $this->RealtyRepository = $RealtyRepository;
    }

    public function __invoke(StorerealtyRequest $request)
    {
        if (!PermissionsHelper::hasPermission('realty.add')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $files = $request['photos'];
        $data = $request->all();
        $data['photos'] = $files;

        return $this->RealtyRepository->addRealty($data);
    }
}
