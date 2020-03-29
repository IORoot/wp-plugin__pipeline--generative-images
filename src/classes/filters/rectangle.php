<?php

namespace genimage\filters;

class rectangle {

    public function __construct(){
        return $this;
    }

    public function output(){
        return '<rect height="40px" width="1900px" x="405" y="303" class="shadow"></rect>';
    }

    public function defs(){
        return;
    }

    public function set_params(){
        return ;
    }  
}