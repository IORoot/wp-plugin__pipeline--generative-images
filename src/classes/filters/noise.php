<?php

namespace genimage\filters;

class noise {

    public function __construct(){
        return $this;
    }

    public function output(){
        return '<rect height="100%" width="100%" x="0" y="0" fill-opacity="0.8" fill="url(#pattern-noise)"></rect>';
    }

    public function defs(){

        $def = '<image id="noise" href="https://londonparkour.com/wp-content/uploads/2020/03/noisy-texture-200x200_10pc.png" height="200px" width="200px"></image>
        <pattern id="pattern-noise" width="200px" height="200px" x="-200" y="-200" patternUnits="userSpaceOnUse">
        <use xlink:href="#noise"></use>';

        return $def;
    }

}