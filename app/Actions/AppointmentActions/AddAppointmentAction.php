<?php

namespace App\Actions\AppointmentActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\StoreappointmentRequest;
use App\Http\Requests\UserAuth\UserForgetPasswordStep1Request;
use App\Repository\AppointmentRepository;
use App\Repository\AuthRepository;
use Illuminate\Http\Request;

class AddAppointmentAction
{
    protected $AppointmentRepository;

    public function __construct(AppointmentRepository $AppointmentRepository) {
        return $this->AppointmentRepository = $AppointmentRepository;
    }

    public function __invoke(StoreappointmentRequest $request)
    {
        $id = $request->route('id');
        if (!PermissionsHelper::hasPermission('appointment.add')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->AppointmentRepository->addAppointment($request->validated(), $id);
    }
}
