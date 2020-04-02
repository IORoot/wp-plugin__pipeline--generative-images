<?php

namespace genimage\filters;

class svg_definition {

    public $params;

    public function __construct($params){
        $this->params = unserialize($params);
        return $this;
    }

    public function output(){
        return;
    }

    public function defs(){
        return $this->params;
    }

}