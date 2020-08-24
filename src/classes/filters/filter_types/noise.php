<?php

namespace genimage\filters;

class noise
{
    public $params;

    public function __construct($params)
    {
        $this->params = explode(',', unserialize($params));
        return $this;
    }

    public function output()
    {
        return '<rect height="100%" width="100%" x="0" y="0" fill-opacity="'.$this->params[0].'" fill="url(#pattern-noise)"></rect>';
    }

    public function defs()
    {
        $noise_url = "../../../../wp-content/plugins/andyp_generative_images/src/img/noise.png";
        if ($this->params[1]) {
            $noise_url = $this->absolute_to_relative();
        }

        $def = '<image id="noise"  xlink:href="'.$noise_url.'" height="200px" width="200px"></image>
        <pattern id="pattern-noise" width="200px" height="200px" x="-200" y="-200" patternUnits="userSpaceOnUse">
        <use xlink:href="#noise"></use></pattern>';

        return $def;
    }

    public function absolute_to_relative()
    {
        $this->params[1] = preg_replace('/.*wp-content/','../../../../wp-content', $this->params[1]);
        return $this->params[1];
    }
}
