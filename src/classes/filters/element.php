<?php

namespace genimage\filters;

class element {

    public $params;

    public function __construct($params){
        $this->params = unserialize($params);
        return $this;
    }

    public function output(){
        if (empty($this->params)){ return; }
        return $this->params;
    }

    public function defs(){
        return;
    }

}