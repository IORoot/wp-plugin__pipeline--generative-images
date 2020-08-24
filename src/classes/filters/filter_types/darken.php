<?php

namespace genimage\filters;

class darken {

    public $params;

    public function __construct($params){
        $this->params = unserialize($params);
        return $this;
    }

    public function output(){
        return '<rect height="100%" width="100%" x="0" y="0" fill-opacity="'.$this->params.'" fill="#000000"></rect>';
    }

    public function defs(){
        return ;
    }

}