<?php

namespace App\Actions\ProfileActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StoreProfileRequest;
use App\Repository\ProfileRepositry;

class AddOrUpdateProfileInfoActoion
{
    protected $ProfileRepositry;

    public function __construct(ProfileRepositry $ProfileRepositry)
    {
        return $this->ProfileRepositry = $ProfileRepositry;
    }

    public function __invoke(StoreProfileRequest $request)
    {
        return $this->ProfileRepositry->addOrUpdateProfileInfo($request);
    }
}
