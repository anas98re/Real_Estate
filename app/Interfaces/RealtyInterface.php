<?php

namespace App\Interfaces;

interface RealtyInterface
{
    public function getAllRealties($request);
    public function getRealtyById($id);
    public function addRealty($request);
    public function updateRealty($request, $id);
    public function removeRealty($id);
    public function getUserRealties($request);
    public function Filter($request);
}
