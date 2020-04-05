<?php

namespace genimage\shortcodes;

use genimage\svg\build_svg as svg;
use genimage\options as options;
use genimage\wp\get_image as wp;
use genimage\utils\replace as replace;

class article_image
{
    
    //The ACF options
    public $options;

    // The image URL for the article.
    public $image;

    // SVG object
    public $s;

    // Multi SVG arrays;
    public $multi_s;

    // need to store all source images for conversion
    public $image_url_collection;

    public function __construct()
    {
        return $this;
    }

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                        Start the high-level tasks                       │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function render()
    {
        $this->get_options();

        // if a single result
        if (!is_array($this->options['article'])) {
            $this->get_image_url();
            // Make sure that a source image is returned!
            if ($this->image[0] != "../../../.."){
                $this->multi_s[] .= $this->render_svg();
            }
            return $this->multi_s;
        }

        // else an array of results
        $this->options['collection'] = $this->options['article'];
        foreach ($this->options['collection'] as $article) {
            $this->options['article'] = $article;
            $this->get_image_url();
            // Make sure that a source image is returned!
            if ($this->image[0] != "../../../.."){
                $this->multi_s[] .= $this->render_svg();
            }
            
        }
        
        return $this->multi_s;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                         Get the options from ACF                        │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function get_options()
    {
        $options = new options();
        $this->options = $options->get_article_options();
        return $this;
    }


    public function get_source_files()
    {
        return $this->image_url_collection;
    }

    public function get_source_posts()
    {
        // query
        $return_posts = $this->options['collection'];

        // term
        if (get_class($this->options['article']) == 'WP_Term') {
            $return_posts[] = $this->options['article'];
        }

        // post
        if (get_class($this->options['article']) == 'WP_Post') {
            $return_posts[] = $this->options['article'];
        }

        return $return_posts;
    }


    public function get_save_values()
    {
        return $this->options['save'];
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                    Get the selected posts' image URL.                   │
    // │           This will return an array with URL, width & height.           │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function get_image_url()
    {
        $wp = new wp;
        $domain = get_site_url();
        $this->image = $wp->get_image_url($this->options['article']);
        $this->image_url_collection[] = str_replace('../../../..', $domain, $this->image[0]);
        $this->set_image_filter();
        return $this;
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │  If any of the filters are the 'image' type, then set the parameters to │
    // │                      be the selected posts' image.                      │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function set_image_filter()
    {
        if (empty($this->options['filter'])) {
            return $this;
        }
        foreach ($this->options['filter'] as $key => $filter) {
            if ($filter['filter_name'] == 'image' || $filter['filter_name'] == 'image_free') {
                // Whatever is in the 'filter_parameters textbox, add it on as part of the image array item [4].
                $this->image[4] = $this->options['filter'][$key]['filter_parameters'];
                $this->options['filter'][$key]['filter_parameters'] = $this->image;
            }
        }
        return $this;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │      Builds the SVG up based on the filters added and their order.      │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function render_svg()
    {
        if ($this->image[1] == null) {
            return false;
        }

        if (!empty($this->image)) {
            $this->s = new svg;
            $this->s->open_svg('0 0 '.$this->image[1].' '.$this->image[2].'');
            $this->s->open_defs();
            $this->run_defs();
            $this->s->close_defs();
            $this->run_filters();
            $this->s->close_svg();
        }

        return $this->s->render();
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │         Iterate over all of the filters and return their results        │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function run_filters()
    {
        // check first
        if (empty($this->options['filter'])) {
            return;
        }

        foreach ($this->options['filter'] as $filter) {
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
    public function run_defs()
    {
        // check first
        if (empty($this->options['filter'])) {
            return;
        }

        foreach ($this->options['filter'] as $filter) {
            $filter_object = $this->instantiate_filter($filter);
            $this->s->add_element($filter_object->defs());
        }
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │     Instantiate the class with the same name as the selected filter.    │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function instantiate_filter($filter)
    {
                
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
