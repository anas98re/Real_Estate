<?php

namespace App\Actions\AppointmentActions;

use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\PermissionsHelper;
use App\Http\Requests\UserAuth\UserForgetPasswordStep1Request;
use App\Models\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\AuthRepository;
use Illuminate\Http\Request;

class UpdateAppointmentAction
{
    protected $AppointmentRepository;

    public function __construct(AppointmentRepository $AppointmentRepository) {
        return $this->AppointmentRepository = $AppointmentRepository;
    }

    public function __invoke(Request $request)
    {
        $id = $request->route('id');
        if (
            !PermissionsHelper::hasPermission('appointment.update')
            || !PermissionsHelper::hasPermissionUpdateOrRemoveAppointment($id)
        ) {
            $service = Appointment::find($id);

            if (!$service) {
                return ApiResponseHelper::sendMessageResponse('Appointment not found and Forbidden', 404, false);
            }
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $this->AppointmentRepository->updateAppointment($request, $id);
    }
}
