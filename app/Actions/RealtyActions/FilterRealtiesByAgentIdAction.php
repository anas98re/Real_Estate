<?php

namespace App\Actions\RealtyActions;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\FilterRealtyRequest;
use App\Http\Requests\StorerealtyRequest;
use App\Http\Requests\UpdaterealtyRequest;
use App\Models\Realty;
use App\Repository\RealtyRepository;
use Illuminate\Http\Request;

class FilterRealtiesByAgentIdAction
{
    protected $RealtyRepository;

    public function __construct(RealtyRepository $RealtyRepository)
    {
        return $this->RealtyRepository = $RealtyRepository;
    }

    public function __invoke(Request $request)
    {
        $id = $request->route('userId');
        return $this->RealtyRepository->FilterRealtiesByAgentId($request, $id );
    }
}
