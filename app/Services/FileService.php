<?php

namespace App\Services;

class FileService
{
    public function handlingImageName($File)
    {
        $originalFilename = $File->getClientOriginalName();
        $fileExtension = $File->getClientOriginalExtension();
        $randomNumber = mt_rand(10000, 99999);

        $filenameWithoutExtension = pathinfo($originalFilename, PATHINFO_FILENAME);

        $modifiedFilename = str_replace(' ', '_', $filenameWithoutExtension) . '_' . $randomNumber;

        $generatedFilename = $modifiedFilename . '.' . $fileExtension;

        $generatedPath = $File->storeAs('Files', $generatedFilename,'public');
        return $generatedPath;
    }
}
