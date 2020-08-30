<?php

namespace genimage\filters;

use genimage\interfaces\filterInterface;

class darken implements filterInterface
{

    
    public $filtername =    'darken';
    public $filterdesc =    'Adds a <rect> layer with a #000 fill. You can control the opacity from 0 to 1.';
    public $example    =    '0.5';
    public $output     =    '<rect height="100%" width="100%" x="0" y="0" fill-opacity="0.5" fill="#000000"></rect>';

    public $params;

    public $image;

    public function set_params($params)
    {
        $this->params = unserialize($params);
    }

    public function set_image($image)
    {
        $this->image = $image;
    }

    public function set_all_images($images)
    {
        return;
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