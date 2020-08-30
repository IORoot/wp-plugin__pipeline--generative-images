<?php

namespace genimage\interfaces;


interface filterInterface 
{ 


    /**
     * Contains an array of each filter layer for filter group
     *
     * 0 => [
     *          'filter_name' => "image"
     *          'filter_parameters => "s:19:"filter="url(#aden)"";"
     *      ]
     * @var array
     */
    public function set_params($params);

    /**
     * set_image function
     * 
    * Contains an array current images' metadata.
     *      0 => Relative Directory
     *      1 => width
     *      2 => height
     *      3 => false
     *      4 => URL
     * 
     *
     * @param array $image
     * @return void
     */
    public function set_image($image);

    /**
     * set_all_images function
     * 
     * Contains an array of the all returned posts featured images.
     * 0 => [
     *      0 => Relative directory of image
     *      1 => width
     *      2 => height
     * ]
     *
     * @param array $images
     * @return void
     */
    public function set_all_images($images);

    /**
     * run function
     * 
     * The run function will return an instance of itself as an 
     * object. This is so the output() and defs() methods can
     * be run at the appropriate time.
     *
     * @return object
     */
    public function run();

    /**
     * output function
     * 
     * The output function returns anything to be placed within the
     * main SVG as a layer.
     *
     * @return string
     */
    public function output();

    /**
     * defs function
     * 
     * The defs function will return any SVG definitions if the
     * filter has any to place within the top <defs></defs> section
     * of the main SVG.
     *
     * @return string
     */
    public function defs();

}