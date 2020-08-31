<?php


add_filter('genimage_get_svgdata', 'genimage_get_svgdata', 10, 2);

/**
 * genimage_get_svgdata function
 * 
 * $filter_slug is just the name of the filter you wish to run. It will
 * run the separate wordpress apply_filters('genimage_get_filters') 
 * to get the genimage filter data from the slug.
 * 
 * 'my_filter_slug_name'
 * 
 * $image_group Contains an array of instances of current images' metadata.
 * This is a result of running the function wp_get_attachment_image_src().
 * The universal exporter already does this and puts it into the 'content' 
 * source date under the field '_wp_attachment_src'.
 * 
 * 0 => [
 *      0 => Relative Directory
 *      1 => width
 *      2 => height
 *      3 => false
 *      4 => URL
 * ]
 *
 * @param string $filter_slug
 * @param array $image_group
 * @return void
 */
function genimage_get_svgdata($filter_slug, $image_group)
{

    $filter_data = apply_filters('genimage_get_filters', $filter_slug);

    $svg_group = new \genimage\svg_group;
    $svg_group->set_filters($filter_data);
    $svg_group->set_images($image_group);
    $svg_group->run();

    $result = $svg_group->get_svg_group();
    
    return $result;
}