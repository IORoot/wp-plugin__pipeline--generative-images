<?php

namespace genimage\filters;

use genimage\interfaces\filterInterface;

class whiten implements filterInterface
{
    public $filtername =    'whiten';
    public $filterdesc =    'Adds a <rect> layer with a #fff fill. You can control the opacity from 0 to 1.';
    public $example    =    '0.5';
    public $output     =    '<rect height="100%" width="100%" x="0" y="0" fill-opacity="0.5" fill="#ffffff"></rect>';

    public $params = 1;

    public function set_params($params)
    {
        if (is_serialized($params)){
            $this->params = unserialize($params);
            return;
        }
        $this->params = $params;
    }

    public function set_image($image)
    {
        return;
    }

    public function set_all_images($images)
    {
        return;
    }
        
    public function set_source_object($source_object)
    {
        return;
    }

    public function run()
    {
        return $this;
    }

    public function output(){

        if (!is_numeric($this->params)){
            $this->params = 1;
        }

        return '<rect height="100%" width="100%" x="0" y="0" fill-opacity="'.$this->params.'" fill="#ffffff"></rect>';
    }

    public function defs(){
        return ;
    }

}