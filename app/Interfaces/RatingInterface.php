<?php

namespace App\Interfaces;

interface RatingInterface
{
    public function addRating($request, $id);
    public function updateRating($request, $id);
    public function deleteRating($id);
    public function RealtyRatings($id);
}
