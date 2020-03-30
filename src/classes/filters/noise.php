<?php

namespace genimage\filters;

class noise {

    public $params;

    public function __construct($params){
        $this->params = unserialize($params);
        return $this;
    }

    public function output(){
        return '<rect height="100%" width="100%" x="0" y="0" fill-opacity="'.$this->params.'" fill="url(#pattern-noise)"></rect>';
    }

    public function defs(){

        $def = '<image id="noise"  xlink:href="../../../../wp-content/plugins/andyp_generative_images/src/img/noise.png" height="200px" width="200px"></image>
        <pattern id="pattern-noise" width="200px" height="200px" x="-200" y="-200" patternUnits="userSpaceOnUse">
        <use xlink:href="#noise"></use></pattern>';

        return $def;
    }

}