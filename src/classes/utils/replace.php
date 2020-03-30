<?php

namespace genimage\utils;

class replace {

    /**
     * substitute any %post_title% type matches with their WP_Post
     * real values.
     */
    public static function switch($string, $post_object){

        preg_match_all("/{{([\w|_]+)}}/", $string, $matches);
        
        foreach($matches[1] as $key => $match){
            if(property_exists($post_object, $match)){
                $string = str_replace('{{'.$match.'}}', $post_object->$match, $string);
            }  
        }

        return $string;
    }


    public static function switch_acf($string, $post_object){

        $acf = get_fields($post_object->ID);
        if(get_class($post_object) == 'WP_Term'){
            $acf = get_fields('term_'.$post_object->term_id);
        }
        
        preg_match_all("/{{([\w|_]+)}}/", $string, $matches);

        foreach($matches[1] as $key => $match){
            $string = str_replace('{{'.$match.'}}', $acf[$match], $string);
        }

        return $string;

    }

}