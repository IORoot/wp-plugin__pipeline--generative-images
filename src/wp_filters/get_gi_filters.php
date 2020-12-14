<?php


add_filter('genimage_get_filters', 'genimage_get_filters', 10, 1);

/**
 * genimage_get_filters
 * 
 * This will return an array of all filters
 * @return array
 */
function genimage_get_filters()
{
    $filters = new \genimage\filters;
    $filters->set_filter_slug($filter_slug);
    $filters->run();
    $result = $filters->get_filters();
    
    return $result;
}