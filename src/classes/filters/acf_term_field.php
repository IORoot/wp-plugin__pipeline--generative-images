<?php

namespace genimage\filters;

use genimage\utils\replace as replace;

class acf_term_field {

    public $params;

    public $post;

    public function __construct($params, $post){
        $this->params = unserialize($params);
        $this->post = $post;
        return $this;
    }
    

    public function output(){
        if (empty($this->params) || empty($this->post)){ return; }

        $output = replace::switch_term_acf($this->params, $this->post);


        return $output;
    }

    public function defs(){
        return;
    }

}