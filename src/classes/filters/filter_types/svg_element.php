<?php

namespace genimage\filters;

class svg_element 
{
    public $filtername = 'svg_element';
    public $filterdesc = 'Add anything into the SVG object at this layer.';
    public $example    = '<text id="hardcoded" x="50%" y="59%" dominant-baseline="middle" text-anchor="middle" style="font-size: 29px; fill:#fafafa;" >SVG ELEMENT</text>';
    public $output     = '<text id="hardcoded" x="50%" y="59%" dominant-baseline="middle" text-anchor="middle" style="font-size: 29px; fill:#fafafa;" >SVG ELEMENT</text>';

    
    public $params;

    public $post;

    public function set_params($params)
    {
        $this->params = $params;
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
        if (empty($this->params)){ return; }
        return $this->params;
    }

    public function defs(){
        return;
    }

}