<?php

namespace genimage;

use genimage\utils\utils as utils;

trait option_source
{

    public function get_source($source_type){
        return $this->$source_type(); 
    }


    public function get_article(){
        return [get_field('genimage_source_group_genimage_article' , 'option')]; 
    }


    public function get_category(){
        return [get_field('genimage_source_group_genimage_category' , 'option')];
    }


    public function get_query(){
        $args = utils::lb(get_field('genimage_source_group_genimage_wpquery' , 'option'));
        $args = eval("return $args;");
        return get_posts($args);
    }

}