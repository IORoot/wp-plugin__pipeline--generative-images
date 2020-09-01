<?php

namespace genimage\utils;

trait switch_for_dates
{
    /**
     * substitute any {{date:PHP FORMAT}} type matches with their
     * real date values.
     */
    public static function switch_dates($string)
    {
        preg_match_all("/{{date:([\w|\s]+)}}/", $string, $matches);
        
        foreach ($matches[1] as $key => $match) {

            $string = str_replace('{{date:'.$match.'}}',date($match),$string);
            
        }

        return $string;
    }

}