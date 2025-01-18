<?php

namespace App\Actions\ProfileActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\UserAuth\UserLoginRequest;
use App\Repository\AuthRepository;
use App\Repository\ProfileRepositry;
use Illuminate\Http\Request;

class ShowProfileAction
{
    public function __construct(protected ProfileRepositry $ProfileRepositry) {}

    public function __invoke(Request $request)
    {
        $id = $request->route('id');
        if (!PermissionsHelper::hasPermissionShowProfile($id)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->ProfileRepositry->showProfileInfo($id);
    }
}
