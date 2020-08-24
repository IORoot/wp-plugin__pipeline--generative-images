<?php

namespace genimage\filters;

use genimage\utils\replace as replace;

class text {

    public $params;

    public $post;

    public function __construct($params, $post){
        $this->params = unserialize($params);
        $this->post = $post;
        return $this;
    }
    

    public function output(){
        if (empty($this->params) || empty($this->post)){ return; }

        $replace = new replace;
        $output = $replace->sub($this->params, $this->post);
        $output = replace::switch_acf($output, $this->post);

        return $output;
    }

    public function defs(){
        return;
    }

}