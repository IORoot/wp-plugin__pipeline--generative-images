<?php

namespace genimage\filters;

class whiten 
{
    public $filtername =    'whiten';
    public $filterdesc =    'Adds a <rect> layer with a #fff fill. You can control the opacity from 0 to 1.';
    public $example    =    '0.5';
    public $output     =    '<rect height="100%" width="100%" x="0" y="0" fill-opacity="0.5" fill="#ffffff"></rect>';


    public $params = '0.5';

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
        return '<rect height="100%" width="100%" x="0" y="0" fill-opacity="'.$this->params.'" fill="#ffffff"></rect>';
    }

    public function defs(){
        return ;
    }

}