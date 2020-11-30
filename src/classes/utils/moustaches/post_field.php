<?php

namespace genimage\utils;

trait switch_for_post_field
{
    /**
     * substitute any {{post_title}} type matches with their WP_Post
     * real values.
     */
    public static function switch($string, $post_object)
    {
        preg_match_all("/{{([\w|_|:]+)}}/", $string, $matches);
        
        foreach ($matches[1] as $key => $match) {


            // Check for UC: (uppercase)
            if (strpos($match, 'uc:') !== false ){
                $UC = true;
                $string = str_replace('uc:','',$string);
                $match = str_replace('uc:','',$match);
            }


            // Check for HY: (hypen becomes a line-break)
            if (strpos($match, 'hy:') !== false ){
                $HY = true;
                $string = str_replace('hy:','',$string);
                $match = str_replace('hy:','',$match);
            }


            if (property_exists($post_object, $match)) {

                $field = $post_object->$match;

                if ($UC == true){ $field = strtoupper($field); }

                if ($HY == true){  $field = preg_replace('/.* - /', '', $field ); }

                $string = str_replace('{{'.$match.'}}', $field, $string);
            }

            
        }

        return $string;
    }

}