<?php

namespace genimage;

use genimage\utils\utils as utils;

trait option_filters
{

    public $filters;

    public function get_filter_group($filter_slug){

        if (!have_rows('genimage_filters', 'option')) {
            return;
        }

        while (have_rows('genimage_filters', 'option')) {

            $row = the_row();

            $group = get_sub_field('genimage_filter_group');

            if ($group['genimage_filter_slug'] != $filter_slug) {
                continue;
            }

            $this->get_layers($filter_slug);
            
        }

        $filters = $this->filters;
        return $filters;
    }


    public function get_layers($filter_slug){

        if(!have_rows('genimage_filter', 'option') ) {
            return;
        }

        while( have_rows('genimage_filter', 'option') ): $row = the_row();

            $this->filters[] = array ( 
                'filter_name'       => get_sub_field('filter_name'),
                'filter_parameters' => serialize(utils::lb(get_sub_field('filter_parameters'))),
            );

        endwhile;

    }

}