<?php

namespace genimage\filters;

class image_free {

    public $params;

    public function __construct($params){
        $this->params = $params;
        return $this;
    }

    public function output(){

        if (!empty($this->params)){ 
            $params = ' xlink:href="'.$this->params[0].'" ';
            $params .= unserialize($this->params[4]);
        }

        return '<image '.$params.'></image>';
    }

    public function defs(){
        return;
    }

}