<?php

namespace App\Interfaces;

interface ProfileInterface
{
    public function addOrUpdateProfileInfo($request);
    public function showProfileInfo($id);
    public function showMyProfileInfo();
}
