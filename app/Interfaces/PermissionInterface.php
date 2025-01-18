<?php

namespace App\Interfaces;

interface PermissionInterface
{
    public function getAllPermission($perPage);
    public function addPermission($request);
    public function updatePermission($request, $id);
    public function removePermission($id);
    public function getPermissionsByRole($role);
}
