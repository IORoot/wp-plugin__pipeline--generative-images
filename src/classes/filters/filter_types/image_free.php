<?php

namespace genimage\filters;

use genimage\utils\utils;
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

    public $result;

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

        if (!isset($this->params)) {
            return false;
        }

        // remove linebreaks
        $args = utils::lb($this->params);

        // check for array or string
        if (substr( $args, 0, 1 ) !== "["){ $args = "'" . $args; }
        if (substr( $args, -1, 1 ) !== "]"){ $args = $args . "'"; }

        try {
            // convert string to array
            $args = eval("return $args;");
        } catch (\ParseError $e) {
            $this->result = $e->getMessage();
            return false;
        }

        if (count($args) != 2) { return false; }

        if (!empty($args)){ 
            $params = ' xlink:href="'.$args[0].'" ';
            $params .= $args[1];
        }

        $this->result = '<image '.$params.'></image>';

        return $this;
    }

    public function output(){
        return $this->result;
    }

    public function defs(){
        return;
    }

}