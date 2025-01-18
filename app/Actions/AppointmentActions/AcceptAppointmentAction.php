<?php

namespace App\Actions\AppointmentActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\AcceptAppointmentRequest;
use App\Http\Requests\StoreappointmentRequest;
use App\Http\Requests\UserAuth\UserForgetPasswordStep1Request;
use App\Repository\AppointmentRepository;
use App\Repository\AuthRepository;
use Illuminate\Http\Request;

class AcceptAppointmentAction
{
    protected $AppointmentRepository;

    public function __construct(AppointmentRepository $AppointmentRepository) {
        return $this->AppointmentRepository = $AppointmentRepository;
    }

    public function __invoke(AcceptAppointmentRequest $request)
    {
        $id = $request->route('id');
        return $this->AppointmentRepository->acceptAppointment($request->validated(), $id);
    }
}
