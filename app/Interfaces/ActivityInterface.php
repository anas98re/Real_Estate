<?php

namespace App\Interfaces;

interface ActivityInterface
{
    public function getAllActivities();
    public function addActivity($request);
    public function updateActivity($request, $id);
    public function removeActivity($id);
    public function getUserActivities();

}
