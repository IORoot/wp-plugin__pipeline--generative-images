<?php

namespace genimage;

trait option_instances
{

    public function get_instances(){

        if (!have_rows('genimage_instance', 'option')) {
            return;
        }

        while (have_rows('genimage_instance', 'option')): $row = the_row();

            $instances[] = array ( 
                'instance_slug'       => get_sub_field('genimage_instance_slug'),
                'instance_source'     => get_sub_field('genimage_instance_source'),
                'instance_filter'     => get_sub_field('genimage_instance_filter'),
                'instance_enabled'    => get_sub_field('genimage_instance_enabled')
            );
            
        endwhile;

        return $instances;
        
    }


}