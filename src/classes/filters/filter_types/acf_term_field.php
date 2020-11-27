<?php

namespace genimage\filters;

use genimage\utils\replace as replace;
use genimage\interfaces\filterInterface;

class acf_term_field implements filterInterface
{

    public $filtername =    'acf_term_field';
    public $filterdesc =    'Only available on the \'Category\' source.'. PHP_EOL. PHP_EOL.
                            'Moushache brackets, same as the text layer type.'. PHP_EOL. PHP_EOL.
                            'Allows you to specify an ACF field attached to the taxonomy and output the value.'. PHP_EOL.PHP_EOL.
                            'Example creates coloured logo.';

    public $example    =    '<svg x="50%" overflow="visible">'. PHP_EOL.
                            '    <g id="Logo" transform="translate(-70, 500) scale(0.3)">'. PHP_EOL.
                            '        <polygon id="Logo_-_Left" fill="{{taxonomy_colour}}" points="0 0 130 0 290 250 0 250"></polygon>'. PHP_EOL.
                            '        <polygon id="Logo_-_Right" fill="{{taxonomy_colour}}" points="160 0 450 0 450 250 320 250"></polygon>'. PHP_EOL.
                            '    </g>'. PHP_EOL.
                            '</svg>';

    public $output     =    '<svg x="50%" overflow="visible">'. PHP_EOL.
                            '    <g id="Logo" transform="translate(-70, 500) scale(0.3)">'. PHP_EOL.
                            '        <polygon id="Logo_-_Left" fill="#00FF00" points="0 0 130 0 290 250 0 250"></polygon>'. PHP_EOL.
                            '        <polygon id="Logo_-_Right" fill="#00FF00" points="160 0 450 0 450 250 320 250"></polygon>'. PHP_EOL.
                            '    </g>'. PHP_EOL.
                            '</svg>';
    
    public $params;

    public $image;

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
        $this->image = $image;
    }

    public function set_all_images($images)
    {
        $this->images = $images;
    }

    public function set_source_object($source_object)
    {
        $this->source_object = $source_object;
    }

    public function run()
    {
        return $this;
    }
    

    public function output(){
        if (empty($this->params) || empty($this->image)){ return; }

        $output = replace::switch_term_acf($this->params, $this->image);

        return $output;
    }

    public function defs(){
        return;
    }

}