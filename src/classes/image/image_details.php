<?php
namespace genimage;

use genimage\wp\get_image;

class image_details
{

    /**
     * Returns array on image
     * 
     * e.g.
     * 0 => Relative Directory
     * 1 => width
     * 2 => height
     * 3 => false
     * 4 => URL
     */
    public static function get_image_meta($wp_post_or_term)
    {
        $wp = new get_image;
        $image = $wp->get_image_url($wp_post_or_term);

        $domain = get_site_url();
        $image[4] = str_replace('../../../..', $domain, $image[0]);

        return $image;
    }




}