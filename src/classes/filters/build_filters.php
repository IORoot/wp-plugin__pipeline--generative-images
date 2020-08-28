<?php

namespace genimage;


class filter_objects
{

    public $filters;
    public $current_filter;

    /**
     * An array of filter objects to return back.
     * 
     * 
     *
     * @var [type]
     */
    public $filter_objects;

    public $image;



    public function set_filters($filters)
    {
        $this->filters = $filters;
    }

    public function set_image($image)
    {
        $this->image = $image;
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

        // Instantiate new object with the filter parameters and the post
        $filter_object = new $filter_name;
        $filter_object->set_params($this->current_filter['filter_parameters']);
        $filter_object->set_post($this->image);
        $filter_object->run();

        // return object
        return $filter_object;
    }


}
