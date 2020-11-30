<?php

namespace genimage\utils;

trait switch_for_dates
{
    /**
     * substitute any {{date:PHP DATETIME FORMAT}} type matches with their
     * real date values.
     */
    public static function switch_dates($string)
    {
        preg_match_all("/{{date:([\w|\W]+)}}/", $string, $matches);
        
        if (empty($matches[1])){ return $string; }

        foreach ($matches[1] as $key => $match) {

            $date = new \DateTime();
            $string = str_replace('{{date:'.$match.'}}', $date->format($match), $string);
            
        }

        return $string;
    }

}