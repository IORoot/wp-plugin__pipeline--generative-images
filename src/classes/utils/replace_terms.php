<?php

namespace genimage\utils;

class replace_terms
{

    public $params;

    public $terms;

    public $term_key = 0;
    public $term_field;




    public function __construct($terms, $params){
        $this->terms = $terms;
        $this->params = $params;
        return;
    }

    public function terms_to_term()
    {
        // find all moustaches
        $matches = $this->find_moustaches();

        foreach ($matches[1] as $key => $match) {
            $this->set_term_key_and_field($match);
            $this->sub($match);
        }

        return $this->params;
    }

    /**
     * /w   words
     * /d   digits
     * _    underscores
     * :    colons
     * \[   open square brackets
     * \]   close square brackets
     * {{}} within curlies
    */
    public function find_moustaches(){

        preg_match_all("/{{([\w|\d|_|:|\[|\]]+)}}/", $this->params, $matches);
        unset($matches[0]);
        return $matches;
    }


    /**
     * /d   digits
     * \[   open square brackets
     * \]   close square brackets
     */
    public function set_term_key_and_field($match){
        preg_match("/([\w|\d|_]*)\[(\d)\]/", $match, $matches);
        $this->term_field = $matches[1];
        $this->term_key = intval($matches[2]);
        return;
    }


    public function sub($match){

        $field = $this->term_field;
        $term_key = $this->term_key;

        $this->params = str_replace('{{'.$match.'}}', strtoupper($this->terms[$term_key]->$field), $this->params);
        return;
    }


}