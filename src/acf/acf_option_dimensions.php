<?php

namespace genimage;

use genimage\utils\utils as utils;

trait option_dimensions
{

    public $dimensions;

    public function get_dimensions($filter_slug){

        if (!have_rows('genimage_filters', 'option')) {
            return;
        }

        while (have_rows('genimage_filters', 'option')): $row = the_row();

            if (get_sub_field('genimage_filter_slug') != $filter_slug)
            {
                continue;
            }

            if (get_sub_field('genimage_filter_resize_width') != '')
            {
                $this->dimensions['width'] =  get_sub_field('genimage_filter_resize_width');
            }
    
            if (get_sub_field('genimage_filter_resize_height') != '')
            {
                $this->dimensions['height'] =  get_sub_field('genimage_filter_resize_height');
            }

        endwhile;

        $dimensions = $this->dimensions;
        return $dimensions;
    }

}