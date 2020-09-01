<?php

namespace genimage\filters;

use genimage\interfaces\filterInterface;

class svg_element implements filterInterface
{
    public $filtername = 'svg_element';
    public $filterdesc = 'Add anything into the SVG object at this layer.';
    public $example    = '<text id="hardcoded" x="50%" y="59%" dominant-baseline="middle" text-anchor="middle" style="font-size: 29px; fill:#fafafa;" >SVG ELEMENT</text>';
    public $output     = '<text id="hardcoded" x="50%" y="59%" dominant-baseline="middle" text-anchor="middle" style="font-size: 29px; fill:#fafafa;" >SVG ELEMENT</text>';

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
        if (empty($this->params)){ return; }
        return $this->params;
    }

    public function defs(){
        return;
    }

}