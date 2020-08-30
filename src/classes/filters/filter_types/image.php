<?php

namespace genimage\filters;

use genimage\interfaces\filterInterface;

class image implements filterInterface
{
    public $filtername =    'image';
    public $filterdesc =    'This will create an <image> tag with the image of the source post.'.PHP_EOL.PHP_EOL.
                            'This will be the basis of the SVG size, so is needed to define the width/height of the SVG data.'.PHP_EOL.PHP_EOL.
                            'Therefore, the base image has its height/width automatically set based on the source, which you cannot change.';
    public $example    =    'filter="url(#myFilter)"';
    public $output     =    '<image xlink:href="../../../../wp-content/uploads/2020/03/my_image.jpg" width="1280" height="720" filter="url(#myFilter)"></image>';
    
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

        if (!empty($this->params)){ 
            $params = ' xlink:href="'.$this->image[0].'"';
            $params .= ' width="'.$this->image[1].'"';
            $params .= ' height="'.$this->image[2].'" ';
            $params .= unserialize($this->params);
        }

        return '<image '.$params.'></image>';
    }

    public function defs(){
        return;
    }

}