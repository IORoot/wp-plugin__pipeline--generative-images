<?php

namespace genimage\utils;

class replace {

    /**
     * substitute any %post_title% type matches with their WP_Post
     * real values.
     */
    public function switch($string, $post_object){

        preg_match_all("/%([\w|_]+)%/", $string, $matches);
        
        foreach($matches[1] as $key => $match){
            $string = str_replace('%'.$match.'%', $post_object->$match, $string);
        }

        return $string;
    }



}