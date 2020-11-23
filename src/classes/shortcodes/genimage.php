<?php

namespace genimage\shortcodes;


/**
 * Create a shortcode 'genimage' and run the generator class.
 */
class genimage
{

    public function __construct()
    {
        add_shortcode('genimage', array($this, 'generative_image'));
        return;
    }


    public function generative_image()
    {
        $generator = new \genimage\generator;
        $generator->run();
    }

}