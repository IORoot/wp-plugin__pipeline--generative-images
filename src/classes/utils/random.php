<?php

namespace genimage\utils;

class random
{

    public $palette = [];

    public static function colour_hex()
    {
        return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
    }


    public function set_palette($palette){
        $this->palette = $palette;
    }

    public function get_palette()
    {
        $key = array_rand($this->palette);
        return $this->palette[$key];
    }

    
}
