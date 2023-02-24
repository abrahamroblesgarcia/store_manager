<?php

namespace App\Contexts;

class Utils
{
    public static function generate_hash()
    {
        return md5(microtime(true));
    }
}
