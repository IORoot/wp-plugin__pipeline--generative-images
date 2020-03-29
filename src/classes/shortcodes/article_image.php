<?php

namespace genimage\shortcodes;


use genimage\svg\build_svg as svg;
use genimage\options as options;


class article_image {
    
    //The ACF options
    public $options;

    // The image URL for the article.
    public $image;

    // Generated SVG to output
    public $s;



    public function __construct(){
        return $this;
    }

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                        Start the high-level tasks                       │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function render(){
        $this->get_options();
        $this->get_image_url();
        $this->render_svg();
        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                         Get the options from ACF                        │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function get_options(){
        $options = new options();
        $this->options = $options->get_article_options();
        return $this;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                    Get the selected posts' image URL.                   │
    // │           This will return an array with URL, width & height.           │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function get_image_url(){
        $this->image = wp_get_attachment_image_src( get_post_thumbnail_id( $this->options['article']->ID ), 'full' );
        $this->set_image_filter();
        return $this;
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │  If any of the filters are the 'image' type, then set the parameters to │
    // │                      be the selected posts' image.                      │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function set_image_filter(){
        if (empty($this->options['filter'])){ return $this; }
        foreach ($this->options['filter'] as $key => $filter){
            if ($filter['filter_name'] == 'image'){
                $this->options[filter][$key]['filter_parameters'] = $this->image;
            }
        }
        return $this;   
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │      Builds the SVG up based on the filters added and their order.      │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function render_svg(){
        if (!empty($this->image)){
            $this->s = new svg;
            $this->s->open_svg('0 0 '.$this->image[1].' '.$this->image[2].'');
                $this->s->open_defs();
                    $this->run_defs();
                $this->s->close_defs();
                $this->run_filters();
            $this->s->close_svg();
            echo $this->s->render();
        }

        return $this;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │         Iterate over all of the filters and return their results        │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function run_filters(){
        // check first
        if (empty($this->options['filter'])){ return; }

        foreach ($this->options['filter'] as $filter){
            $filter_object = $this->instantiate_filter($filter);
            $this->s->add_element($filter_object->output());
        }
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │  Iterate over all of the SVG defs for each filter and output within the │
    // │                               <defs> tags.                              │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function run_defs(){
        // check first
        if (empty($this->options['filter'])){ return; }

        // 
        foreach ($this->options['filter'] as $filter){
            $filter_object = $this->instantiate_filter($filter);
            $this->s->add_element($filter_object->defs());
        }
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │     Instantiate the class with the same name as the selected filter.    │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function instantiate_filter($filter){

        // Get namespaced name of the filter
        $filter_name = "genimage\\filters\\" . $filter['filter_name'];

        // Instantiate new object with the filter parameters and the post
        $filter_object = new $filter_name(
            $filter['filter_parameters'], 
            $this->options['article']
        );

        // return object
        return $filter_object;
    }

}