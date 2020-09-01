<?php

namespace genimage\filters;

use genimage\interfaces\filterInterface;

class image_free implements filterInterface
{
    public $filtername =    'image_free';
    public $filterdesc =    'This Inserts an <image> layer of a specified image with any parameters defined added in.'.PHP_EOL.
                            '<image xlink:href="{{image}}" {{params}}></image>'.PHP_EOL.
                            'Specify both image and parameters in an array.';
    public $example    =    '['.PHP_EOL.
                            ' \'image_location.jpg\' '.PHP_EOL.
                            ' \'filter="url(#myFilter)" width="50%" height="50%"\' '.PHP_EOL.
                            ']';
    public $output     =    '<image xlink:href="/wp-content/uploads/2020/03/my_image.jpg" filter="url(#myFilter)" width="50%" height="50%"></image>';
    
    public $params;

    public function set_params($params)
    {
        $this->params = unserialize($params);
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

        if (!empty($this->params)){ 
            $params = ' xlink:href="'.$this->params[0].'" ';
            $params .= $this->params[1];
        }

        return '<image '.$params.'></image>';
    }

    public function defs(){
        return;
    }

}