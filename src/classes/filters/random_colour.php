<?php

namespace genimage\filters;

use genimage\utils\random as rand;

class random_colour {

    public $params;

    public function __construct($params){
        $this->params = unserialize($params);
        return $this;
    }

    public function output(){
    }

    public function defs(){

        $r = new rand();
        $def = '<linearGradient id="'.$this->params.'"><stop stop-color="'.$r::colour_hex().'"/></linearGradient>';

        return $def;
    }

}