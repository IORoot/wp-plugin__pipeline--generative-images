<?php

namespace genimage\utils;

class random
{
    public static function colour_hex()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }
}
