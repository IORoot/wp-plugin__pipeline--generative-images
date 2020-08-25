<?php


add_filter('genimage_get_filters', 'genimage_get_filters', 10, 1);

/**
 * genimage_get_filters
 * 
 * This will return an array of all filter layers
 * associated with specific filter_slug.
 * 
 * Returns:
 * 
 * [
 *  0 => [
 *          filter_name => 'image'
 *          filter_parameters => "s:19:"filter="url(#aden)"";"
 *       ]
 *  1 => [
 *          filter_name => 'generate_shape'
 *          filter_parameters => "s:95:"[ 'palette' => '#ff0000', 'corners' => 'bl',]";"
 *       ]
 * ]
 *
 * @param [type] $filter_slug   This is the slug name of the filter group you want results for.
 * @return array
 */
function genimage_get_filters($filter_slug)
{
    $filters = new \genimage\filters;
    $filters->set_filter_slug($filter_slug);
    $filters->run();
    $result = $filters->get_filters();
    
    return $result;
}