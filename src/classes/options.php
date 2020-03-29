<?php

namespace genimage;

use genimage\utils\utils as utils;

class options {

    public $result;

    // defaults
    public $source = 'get_article';
    public $filtersource = 'genimage_filters';

    

    public function __construct(){
        return ;
    }



    public function get_article_options(){

        // which source is selected ?
        $this->source = $source = get_field('genimage_source' , 'option'); 

        // get_article or get_category
        $this->$source(); 
        
        $this->get_filters();

        return $this->result;

    }



    public function get_article(){
        // set the new filtersource name.
        $this->source = 'get_article';
        $this->filtersource = 'genimage_filters';
        $this->result['article'] = get_field('genimage_article' , 'option'); 
        return $this;
    }

    public function get_category(){
        // set the new filtersource name.
        $this->source = 'get_category';
        $this->filtersource = 'genimage_category_filters';
        $this->result['article'] = get_field('genimage_category' , 'option'); 
        return $this;
    }


    

    public function get_filters(){

        // // If field exists as an option
        if( have_rows($this->filtersource, 'option') ) {

            // Go through all rows of 'repeater' genimage_filters
            while( have_rows($this->filtersource, 'option') ): $row = the_row();

                // iterate over each filter list
                if (get_sub_field('enabled') == true){
                    $this->get_layers();
                }

            endwhile;
        }

        return $this;

    }



    public function get_layers(){

        // If field exists as an option
        if( have_rows('genimage_filter', 'option') ) {

            // Go through all rows of 'repeater'
            while( have_rows('genimage_filter', 'option') ): $row = the_row();

                // Fields to retrieve from repeater
                $this->result['filter'][] = array ( 
                    'filter_name'       => get_sub_field('filter_name'),
                    'filter_parameters' => serialize(utils::lb(get_sub_field('filter_parameters'))),
                );

            endwhile;
        }

        return $this;
    }


}