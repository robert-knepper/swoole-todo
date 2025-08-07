<?php

namespace App\Task\Application\Validation;

use App\Shared\App\Lib\Validation\BaseRequestValidator;

class TaskRequestValidator extends BaseRequestValidator
{

    static function isValidId($id) : bool
    {
        return self::isValidUnsignedInt($id);
    }

    static function isValidTitle($title): bool
    {
        return self::isValidStr($title, 0, 150);
    }

    static function isValidDescription($desc): bool
    {
        return self::isValidStr($desc, 0, 500);
    }

    static function isValidIsDone($isDone): bool
    {
        return self::isValidBool($isDone);
    }
}