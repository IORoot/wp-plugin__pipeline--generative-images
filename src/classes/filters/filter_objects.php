<?php

namespace genimage;


class filter_objects
{


    /**
     * Contains an array of instances of current images' metadata.
     * 0 => [
     *      0 => Relative Directory
     *      1 => width
     *      2 => height
     *      3 => false
     *      4 => URL
     * ]
     *
     * @var array
     */
    private $images;


    /**
     * Contains an array current images' metadata.
     *      0 => Relative Directory
     *      1 => width
     *      2 => height
     *      3 => false
     *      4 => URL
     * 
     *
     * @var array
     */
    private $image;


    /**
     * This is an array of filter objects
     * 
     * This array contains instances of each filter and its associated
     * parameters / arguments / images. Later this will be used to run
     * the out() method to create the svg filter / svg definitions.
     * 
     * 0 => genimage\filters\image
     *      [
     *          params => "s:19:"filter="url(#aden)"";"
     *          image_url => "https://dev.londonparkour.com/wp-content/uploads/2020/04/con.jpg"
     *      ]
     * 1 => genimage\filters\generate_shape
     *      [  
     *          params => [ palette: "#ff0000", opacity: 0.4, corners: "bl" ]
     *          shape_args => null
     *      ]
     *
     * @var array
     */
    private $filter_objects;

    /**
     * Single WP_Post or WP_Term
     * (Used primarily for {{moustache}}
     * replacement in the text-based
     * filters.
     * 
     * WP_Post
     *
     * @var object
     */
    private $source_object;  

    /**
     * Contains an array of each filter layer for filter group
     *
     * 0 => [
     *          'filter_name' => "image"
     *          'filter_parameters => "s:19:"filter="url(#aden)"";"
     *      ]
     * @var array
     */
    private $filters;
    private $current_filter;


    public function set_filters($filters)
    {
        $this->filters = $filters;
    }

    public function set_image($image)
    {
        $this->image = $image;
    }

    public function set_all_images($images)
    {
        $this->images = $images;
    }

    public function set_source_object($source_object)
    {
        $this->source_object = $source_object;
    }

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │         Iterate over all of the filters and return their results        │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function run()
    {
        // check first
        if (empty($this->filters)) {
            return;
        }

        foreach ($this->filters as $this->current_filter) {
            $this->filter_objects[] = $this->instantiate_filter();
        }

        return $this->filter_objects;
    }

    


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │     Instantiate the class with the same name as the selected filter.    │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function instantiate_filter()
    {
        // Get namespaced name of the filter
        $filter_name = "genimage\\filters\\" . $this->current_filter['filter_name'];

        // Instantiate new object with the filter parameters and the post image
        $filter_object = new $filter_name;
        $filter_object->set_params($this->current_filter['filter_parameters']);
        $filter_object->set_image($this->image);
        $filter_object->set_all_images($this->images);
        $filter_object->set_source_object($this->source_object);
        $filter_object->run();

        // return object
        return $filter_object;
    }


}
