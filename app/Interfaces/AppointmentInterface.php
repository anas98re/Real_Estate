<?php

namespace App\Interfaces;

interface AppointmentInterface
{
    public function AddAppointment($request, $id);
    public function updateAppointment($request, $id);
    public function removeAppointment($id);
    public function getRealtyAppointments($id);
}
