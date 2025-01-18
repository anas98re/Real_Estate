<?php

namespace App\Actions\ProfileActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\UserAuth\StoreProfileRequest;
use App\Repository\ProfileRepositry;

class ShowMyProfileAction
{
    protected $ProfileRepositry;

    public function __construct(ProfileRepositry $ProfileRepositry)
    {
        return $this->ProfileRepositry = $ProfileRepositry;
    }

    public function __invoke()
    {
        return $this->ProfileRepositry->showMyProfileInfo();
    }
}
