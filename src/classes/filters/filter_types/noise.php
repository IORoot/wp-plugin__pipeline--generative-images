<?php

namespace genimage\filters;

use genimage\utils\utils;
use genimage\interfaces\filterInterface;

class noise implements filterInterface
{
    public $filtername =    'noise';
    public $filterdesc =    'Creates a <rect> Noise layer using a fill from a PNG.'.PHP_EOL.PHP_EOL.
                            'Creates a <defs> Pattern with an ID for the <rect> to use.'.PHP_EOL.PHP_EOL.
                            '[Opacity],[filename]. Opacity level 0 to 1. Filename is (optional) – must be a LOCAL file.';
    public $example    =    '['.PHP_EOL.
                            '    0.2,'.PHP_EOL.
                            '   "../../../../wp-content/plugins/andyp_generative_images/src/img/noise.png"'.PHP_EOL.
                            ']';
    public $output     =    '<defs>'.PHP_EOL.
                            '   <image id="noise"  xlink:href="../../../../wp-content/plugins/andyp_generative_images/src/img/noise.png" height="200px" width="200px"></image>'.PHP_EOL.
                            '   <pattern id="pattern-noise" width="200px" height="200px" x="-200" y="-200" patternUnits="userSpaceOnUse">'.PHP_EOL.
                            '       <use xlink:href="#noise"></use>'.PHP_EOL.
                            '   </pattern>'.PHP_EOL.
                            '</defs>'.PHP_EOL.PHP_EOL.
                            '<rect height="100%" width="100%" x="0" y="0" fill-opacity="0.2" fill="url(#pattern-noise)"></rect>';

    public $params;

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
            unset($this->params);
            return false;
        }
        
        $this->params = $args;

        return $this;
    }


    public function output()
    {
        if (!isset($this->params)) {
            return false;
        }

        return '<rect height="100%" width="100%" x="0" y="0" fill-opacity="'.$this->params[0].'" fill="url(#pattern-noise)"></rect>';
    }

    public function defs()
    {
        if (!isset($this->params)) {
            return false;
        }

        $noise_url = "../../../../wp-content/plugins/andyp_generative_images/src/img/noise.png";
        if ($this->params[1]) {
            $noise_url = $this->absolute_to_relative();
        }

        $def = '<image id="noise" xlink:href="'.$noise_url.'" height="200px" width="200px"></image>';
        $def .= '<pattern id="pattern-noise" width="200px" height="200px" x="-200" y="-200" patternUnits="userSpaceOnUse">';
        $def .= '<use xlink:href="#noise"></use></pattern>';

        return $def;
    }

    public function absolute_to_relative()
    {
        $this->params[1] = preg_replace('/.*wp-content/','../../../../wp-content', $this->params[1]);
        return $this->params[1];
    }
}
