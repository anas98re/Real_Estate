<?php

namespace App\Actions\AppointmentActions;

use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\UserAuth\UserForgetPasswordStep1Request;
use App\Repository\AppointmentRepository;
use App\Repository\AuthRepository;
use Illuminate\Http\Request;

class UserAppointmentsAction
{
    protected $AppointmentRepository;

    public function __construct(AppointmentRepository $AppointmentRepository) {
        return $this->AppointmentRepository = $AppointmentRepository;
    }

    public function __invoke()
    {
        if (!PermissionsHelper::hasPermission('appointment.show')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->AppointmentRepository->getMyAppointments();
    }
}
