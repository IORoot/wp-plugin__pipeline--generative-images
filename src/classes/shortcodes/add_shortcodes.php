<?php

namespace genimage\shortcodes;

class add_shortcodes {

    public function __construct(){
        add_shortcode( 'andyp_gen_image', array($this, 'generative_image') );

        return;
    }


    public function generative_image($atts){

        $a = shortcode_atts( 
            array(
                'slug' => '',
            ), $atts );

        // Create new object.
        $genimage = new article_image;

        // Output results.
        $genimage->render();

        return;
    }


}
