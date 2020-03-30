<?php

namespace genimage\filters;

class whiten {

    public $params = '0.5';

    public function __construct($params){
        $this->params = unserialize($params);
        return $this;
    }

    public function output(){
        return '<rect height="100%" width="100%" x="0" y="0" fill-opacity="'.$this->params.'" fill="#ffffff"></rect>';
    }

    public function defs(){
        return ;
    }

}