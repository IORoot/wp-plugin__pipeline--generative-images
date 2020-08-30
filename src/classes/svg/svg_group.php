<?php

namespace genimage;

class svg_group
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
     * Image currently being processed.
     * 
     *  0 => Relative Directory
     *  1 => width
     *  2 => height
     *  3 => false
     *  4 => URL
     *
     * @var array
     */
    private $current_image;

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
     * Array of SVG Code for each image.
     *
     * @var string
     */
    private $result;



    public function set_filters($filters)
    {
        $this->filters = $filters;
    }


    public function set_images($images)
    {
        $this->images = $images;
    }


    public function run()
    {
        foreach ($this->images as $this->current_image)
        {
            $this->process_image();
        }
    }


    public function get_svg_group()
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


    private function process_image()
    {
        if (empty($this->current_image)) {
            return;
        }
        
        $svg_single = new svg_single;
        $svg_single->set_filters($this->filters);
        $svg_single->set_image($this->current_image);
        $svg_single->set_all_images($this->images);
        $svg_single->run();

        $this->result[] = $svg_single->get_svg();
    }

}