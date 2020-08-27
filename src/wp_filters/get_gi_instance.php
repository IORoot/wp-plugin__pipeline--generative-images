<?php


add_filter('genimage_get_instance', 'genimage_get_instance', 10, 3);


function genimage_get_instance($filter_slug, $images_array, $saves_array)
{

    /**
     * runas_filter
     * 
     * This is a modified version of the genimage that
     * does not require a config parameter or images/render
     * methods. The output will be an array of converted files.
     * 
     */
    $instance = new \genimage\runas_filter;

    /**
     * filter_slug variable
     * 
     * The slug of the filter group you want to process.
     *
     * @var string
     */
    $instance->set_filter_slug($filter_slug);

    /**
     * Contains an array of instances of current images' metadata.
     * 0 => [
     *      0 => Relative directory of image
     *      1 => width
     *      2 => height
     * ]
     *
     * @var array
     */
    $instance->set_images($images_array);

    /**
     * save_types
     * 
     * Array of what to save the file as.
     * 
     * [
     *      svg : true,
     *      png : false,
     *      jpg : true,
     * ]
     *
     * @var array
     */
    $instance->set_save_types($saves_array);


    $instance->run();


    $result = $instance->get_converted();


    
    return $result;
}