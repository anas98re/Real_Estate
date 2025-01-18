<?php

namespace App\Interfaces;

interface ServiceInterface
{
    public function getAllServices($perPage);
    public function addService($request);
    public function updateService($request, $id);
    public function removeService($id);
    public function getUserServices($perPage);
}
