<?php

namespace genimage\filters;

class image {

    public $params;

    public function __construct($params){
        $this->params = $params;
        return $this;
    }

    public function output(){

        if (!empty($this->params)){ 
            $params = ' xlink:href="'.$this->params[0].'"';
            $params .= ' width="'.$this->params[1].'"';
            $params .= ' height="'.$this->params[2].'"';
        }

        return '<image '.$params.'></image>';
    }

    public function defs(){
        return;
    }

}