<?php

namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\ApiHelper\ApiResponseHelper;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\AppointmentResource;
use App\Interfaces\ActivityInterface;
use App\Interfaces\AppointmentInterface;
use App\Interfaces\AuthInterface;
use App\Mail\EmailAboutAppointement;
use App\Mail\EmailVerification;
use App\Models\Activity;
use App\Models\Activity_user;
use App\Models\Appointment;
use App\Models\Realty;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppointmentRepository extends BaseRepositoryImplementation implements AppointmentInterface
{
    public function model()
    {
        return Appointment::class;
    }

    public function addAppointment($request, $id)
    {
        try {
            DB::beginTransaction();


            $appointmentDate = $request['appointment_date'];

            // Get all appointments on the given date
            $appointmentsOnDate = Appointment::whereDate(
                'appointment_date',
                Carbon::parse($appointmentDate)->format('Y-n-j')
            )->get();

            // Check if the appointment date is already booked
            $appointmentAtSameHour = $appointmentsOnDate->filter(function ($appointment) use ($appointmentDate) {
                return Carbon::parse($appointment->appointment_date)->format('H') == Carbon::parse($appointmentDate)->format('H');
            })->first();
            $AddingPermission = true;

            if ($appointmentAtSameHour) {
                if ($appointmentAtSameHour->status === 'accept') {
                    return ApiResponseHelper::sendMessageResponse('Appointment date is already booked', 409, false);
                } else {
                    $AddingPermission = 'alsoTrue';
                }
            }

            if ($AddingPermission && $AddingPermission == 'alsoTrue') {
                $userId = auth('sanctum')->user()->id;
                $data = [
                    'user_id' => $userId,
                    'agent_id' => $request['agent_id'],
                    'realty_id' => $id,
                    'appointment_type_id' => $request['appointment_type_id'],
                    'appointment_date' => $request['appointment_date'],
                    'status' => 'accept'
                ];

                $appointment = $this->create($data);
                $appointmentResources = new AppointmentResource($appointment);

                // Sending an email to the agent or owner
                $UserAgent = User::find($request['agent_id']);
                $UserSender = User::find($userId);
                $Realty = Realty::find($id);
                Mail::to($UserAgent->email)->send(new EmailAboutAppointement($request['appointment_date'], $UserSender->name, $UserAgent->name, $Realty->name));


                DB::commit();

                return ApiResponseHelper::sendResponseNew($appointmentResources, "Appointment added successfully");
            }
        } catch (Exception $e) {
            DB::rollBack();
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function updateAppointment($request, $id)
    {
        try {
            $appointment = Appointment::find($id);

            if ($appointment) {
                $data = [
                    'agent_id' => $request->agent_id,
                    'appointment_type_id' => $request->appointment_type_id,
                    'appointment_date' => $request->appointment_date
                ];

                $appointment->update($data);

                $appointmentResources = new AppointmentResource($appointment);
                return ApiResponseHelper::sendResponseNew($appointmentResources, "Appointment updated successfully");
            }

            return null;
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function removeAppointment($id)
    {
        try {
            $appointment = Appointment::find($id);

            if ($appointment) {
                $appointment->delete($appointment);
                return Response()->json('Deleted successfully');
            }
            return Response()->json('Not Found');
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function getRealtyAppointments($id)
    {
        try {
            $realtyAppointments = Appointment::where('realty_id', $id)->get();
            $appointmentResources = AppointmentResource::collection($realtyAppointments);
            return ApiResponseHelper::sendResponseCollection($appointmentResources, 'These are all realty appointments');
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function getMyAppointments()
    {
        try {
            $userId = auth('sanctum')->user()->id;
            $userAppointments = Appointment::where('user_id', $userId)->get();
            $appointmentResources = AppointmentResource::collection($userAppointments);
            return ApiResponseHelper::sendResponseCollection($appointmentResources, 'These are all user appointments');
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }

    public function acceptAppointment($request, $id)
    {
        try {
            $Appointment = Appointment::find($id);
            if (! $Appointment) {
                return response()->json('Appointment not found');
            }
            $Appointment->status = $request['status'];
            $Appointment->save();

            return $Appointment->status == 'accept'
                ? ApiResponseHelper::sendResponseNew('Done', 'accepted')
                : ApiResponseHelper::sendResponseNew('Done', 'rejected');
        } catch (\Exception $e) {
            return ApiResponseHelper::sendMessageResponse($e->getMessage(), 500, false);
        }
    }
}
