<?php

if (!function_exists('logger')) {
    function logger(): \App\Shared\Log\Lib\Logger
    {
        return app()->get('logger');
    }
}

