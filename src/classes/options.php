<?php

namespace genimage;

use genimage\utils\utils as utils;

class options {

    public $result;



    
    public function __construct(){
        return ;
    }



    /**
     * get_options
     *
     * @return void
     */
    public function get_article_options(){

        $this->get_article();
        $this->get_filters();

        return $this->result;

    }



    public function get_article(){
        $this->result['article'] = get_field('genimage_article' , 'option'); 
        return $this;
    }



    public function get_filters(){

        // // If field exists as an option
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