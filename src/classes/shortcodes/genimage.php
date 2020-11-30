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
        $result = null;

        $generator = new \genimage\generator;
        $generator->run();
        $image_results = $generator->result(); 
        
        foreach ($image_results as $image)
        {
            $result .= $image;
        }

        return $result;
    }

}