<?php

namespace genimage\shortcodes;



class genimage
{

    public function __construct()
    {
        add_shortcode('genimage', array($this, 'generative_image'));
        return;
    }


    public function generative_image()
    {
        new \genimage\generator;
    }

}