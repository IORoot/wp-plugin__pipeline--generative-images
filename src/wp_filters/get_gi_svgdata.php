<?php


add_filter('genimage_get_svgdata', 'genimage_get_svgdata', 10, 2);


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