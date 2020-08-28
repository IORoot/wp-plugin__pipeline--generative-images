<?php

namespace genimage\filters;

class darken {

    public $filtername =    'darken';
    public $filterdesc =    'Adds a <rect> layer with a #000 fill. You can control the opacity from 0 to 1.';
    public $example    =    '0.5';
    public $output     =    '<rect height="100%" width="100%" x="0" y="0" fill-opacity="0.5" fill="#000000"></rect>';

    public $params;

    public $post;

    public function set_params($params)
    {
        $this->params = unserialize($params);
    }

    public function set_post($post)
    {
        $this->post = $post;
    }

    public function run()
    {
        return $this;
    }

    public function output(){
        return '<rect height="100%" width="100%" x="0" y="0" fill-opacity="'.$this->params.'" fill="#000000"></rect>';
    }

    public function defs(){
        return ;
    }

}