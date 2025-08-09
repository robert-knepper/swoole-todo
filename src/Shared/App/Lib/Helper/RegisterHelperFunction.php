<?php

namespace App\Shared\App\Lib\Helper;

class RegisterHelperFunction
{
    public function loadFilePath(string $filePath): void
    {
        require_once $filePath;
    }

}