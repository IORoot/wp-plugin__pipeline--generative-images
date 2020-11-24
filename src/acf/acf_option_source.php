<?php

namespace genimage;

use genimage\utils\utils as utils;

trait option_source
{

    public $source;

    public function get_source($source_slug){

        if (!have_rows('genimage_source', 'option')) {
            return;
        }

        while (have_rows('genimage_source', 'option')) {

            $row = the_row();
            
            if (get_sub_field('genimage_source_slug') != $source_slug) {
                continue;
            }
            
            $type = get_sub_field('genimage_source_type');
            $type_value = $type['value'];
            $this->$type_value();
        }

        return $this->source;

    }


    public function get_post(){
        $this->source = get_sub_field('genimage_post' , 'option');
    }


    public function get_taxonomy(){
        $this->source =  [get_sub_field('genimage_taxonomy' , 'option')];
    }


    public function get_query(){
        $args = utils::lb(get_sub_field('genimage_query' , 'option'));
        $args = eval("return $args;");
        $this->source = get_posts($args);
    }

}