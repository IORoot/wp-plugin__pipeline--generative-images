<?php

namespace genimage\filters;

class image {

    public $params;

    public $image;

    public function __construct($params, $image){
        $this->params = $params;
        $this->image = $image;
        return $this;
    }

    public function output(){

        if (!empty($this->params)){ 
            $params = ' xlink:href="'.$this->image[0].'"';
            $params .= ' width="'.$this->image[1].'"';
            $params .= ' height="'.$this->image[2].'" ';
            $params .= unserialize($this->params);
        }

        return '<image '.$params.'></image>';
    }

    public function defs(){
        return;
    }

}