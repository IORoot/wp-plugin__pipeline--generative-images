<?php


add_filter('genimage_get_instance', 'genimage_get_instance', 10, 4);


/**
 * genimage_get_instance function
 * 
 * Use this to run a genimage filter on image(s) and return result.
 *
 * @param string $filter_slug
 * @param array $source_objects
 * @param array $saves_array
 * @return void
 */
function genimage_get_instance($filter_slug, $source_objects, $saves_array, $dimensions = null)
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

    /**
     * source_objects variable
     * 
     * The source objects is an array of all input posts/terms
     * you wish to use in the image generation. 
     * This is an array of: WP_Posts / WP_Terms or an array with
     * an 'ID' field to get the WP_Post from.
     * 
     * [
     *      0 => WP_POST,
     *      1 => WP_POST,
     *      2 => [
     *              'ID' => 123,
     *           ],
     *      3 => WP_TERM,
     * ]
     *
     * @var array
     */
    $instance->set_source_objects($source_objects);

    /**
     * dimensions variable
     * 
     * Optional variable that will change the size of the output SVG.
     * Width, Height in pixels.
     * 
     * [
     *  0 => '640',
     *  1 => '480'
     * ]
     *
     * @var array
     */
    $instance->set_dimensions($dimensions);

    $instance->run();

    $result = $instance->get_converted();

    return $result;
}