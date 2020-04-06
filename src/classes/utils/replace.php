<?php

namespace genimage\utils;

class replace
{

    public $UC = false; // Set Upper-Case


    /**
     * substitute any %post_title% type matches with their WP_Post
     * real values.
     */
    public static function switch($string, $post_object)
    {
        preg_match_all("/{{([\w|_|:]+)}}/", $string, $matches);
        
        foreach ($matches[1] as $key => $match) {

            // Do we need to check for uppercase filter?
            if (strpos($match, 'uc:') !== false ){
                $UC = true;
                $string = str_replace('uc:','',$string);
                $match = str_replace('uc:','',$match);
            }

            if (property_exists($post_object, $match)) {

                $field = $post_object->$match;
                if ($UC == true){ $field = strtoupper($field); }

                $string = str_replace('{{'.$match.'}}', $field, $string);
            }

            
        }

        return $string;
    }



    /**
     * Look for any acf fields in the taxonomy associated with the post.
     */
    public static function switch_acf($string, $post_object)
    {
        $acf = get_fields($post_object->ID);
        if (get_class($post_object) == 'WP_Term') {
            $acf = get_fields('term_'.$post_object->term_id);
        }
        
        preg_match_all("/{{([\w|_]+)}}/", $string, $matches);

        foreach ($matches[1] as $key => $match) {
            $string = str_replace('{{'.$match.'}}', $acf[$match], $string);
        }

        return $string;
    }



    /**
     * Look for any ACF fields in the taxonomy 'term' and replace
     */
    public static function switch_term_acf($string, $post_object)
    {
        $acf = get_fields('term_'.$post_object->term_id);
        
        preg_match_all("/{{([\w|_]+)}}/", $string, $matches);

        foreach ($matches[1] as $key => $match) {
            $string = str_replace('{{'.$match.'}}', $acf[$match], $string);
        }

        return $string;
    }
}
