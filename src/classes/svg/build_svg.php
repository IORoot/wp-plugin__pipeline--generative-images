<?php

namespace genimage\svg;

class build_svg {

    public $svg;

    public function __construct(){
        return $this;
    }

    public function open_svg($viewbox = "0 0 100 100"){
        $this->svg[] = '<svg viewBox="'. $viewbox .'" class="svgwrapper" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
    }

    public function close_svg(){
        $this->svg[] = '</svg>';
    }

    public function open_defs(){
        $this->svg[] = '<defs>';
    }

    public function close_defs(){
        $this->svg[] = '</defs>';
    }

    public function set_image($image){
        $this->svg[] = '<image href="'.$image[0].'" width="'.$image[1].'" height="'.$image[2].'" />';
        return $this;
    }

    public function add_element($element){
        $this->svg[] = $element;
        return $this;
    }

    public function render(){
        return implode('', $this->svg);
    }

}