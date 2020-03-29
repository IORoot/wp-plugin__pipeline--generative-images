<?php

namespace genimage\utils;

class utils {

    /**
     * remove_linebreaks
     */
    public static function lb($in){
        return preg_replace( "/\r|\n/", "", $in );
    }

}