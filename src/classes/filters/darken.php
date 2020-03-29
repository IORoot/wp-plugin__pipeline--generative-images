<?php

namespace genimage\filters;

class darken {

    public function __construct(){
        return $this;
    }

    public function output(){
        return '<rect height="100%" width="100%" x="0" y="0" fill-opacity="0.3" fill="#000000"></rect>';
    }

    public function defs(){
        return ;
    }

}