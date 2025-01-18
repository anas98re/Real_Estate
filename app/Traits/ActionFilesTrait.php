<?php

namespace App\Traits;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

trait ActionFilesTrait
{
    public function useActionFiles()
    {
        $directory = new RecursiveDirectoryIterator(base_path('App\Actions'));
        $iterator = new RecursiveIteratorIterator($directory);

        foreach ($iterator as $info) {
            if ($info->isFile() && $info->getExtension() === 'php') {
                require_once $info->getPathname();
            }
        }
    }
}
