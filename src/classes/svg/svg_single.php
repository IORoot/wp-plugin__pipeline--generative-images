<?php

namespace genimage;

class svg_single
{

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
     * Contains an array of each filter layer for filter group
     *
     * 0 => [
     *          'filter_name' => "image"
     *          'filter_parameters => "s:19:"filter="url(#aden)"";"
     *      ]
     * @var [type]
     */
    private $filters;


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
     * Currently being processed SVG image.
     *
     * @var array
     */
    private $svg;


    /**
     * SVG Code for image
     *
     * @var string
     */
    private $result;



    public function set_filters($filters)
    {
        $this->filters = $filters;
    }


    public function set_image($image)
    {
        $this->image = $image;
    }


    public function run()
    {
        if (empty($this->image)) {
            return;
        }
        
        $this->build_array_of_filter_objects();
        $this->build_svg();
    }


    public function get_svg()
    {
        return $this->result;
    }


//      ┌─────────────────────────────────────────────────────────────────────────┐
//      │                                                                         │░
//      │                                                                         │░
//      │                                 PRIVATE METHODS                         │░
//      │                                                                         │░
//      │                                                                         │░
//      └─────────────────────────────────────────────────────────────────────────┘░
//       ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░



    private function build_array_of_filter_objects()
    {
        $filter_objects = new filter_objects();
        $filter_objects->set_filters($this->filters);
        $filter_objects->set_image($this->image);
        $this->filter_objects = $filter_objects->run();
    }




    private function build_svg()
    {
        $this->svg = new svg_parts;

        $this->svg->open_svg('0 0 '.$this->image[1].' '.$this->image[2].'');
            $this->render_filter_definitions();
            $this->render_filters();
        $this->svg->close_svg();
        
        $this->result = $this->svg->render();
    }




    private function render_filter_definitions()
    {
        $this->svg->open_defs();

        foreach($this->filter_objects as $filter_object)
        {
            $this->svg->add_element($filter_object->defs());
        }

        $this->svg->close_defs();
    }



    private function render_filters()
    {
        foreach($this->filter_objects as $filter_object)
        {
            $this->svg->add_element($filter_object->output());
        }
    }


}