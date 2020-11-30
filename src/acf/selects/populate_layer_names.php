<?php

function acf_populate_genimage_filters_layer_names_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();
    
    $filters = new \genimage\filter_catalog;
    $filter_list = $filters->list();

    foreach($filter_list as $key => $item)
    {
        $value[ $item['genimage_library_filter_name'] ] = $item['genimage_library_filter_name'];
    }

    $field['choices'] = $value;
    
    // return the field
    return $field;
    
}

/**
 * Using 'key' instead of 'name' to reference the field.
 * To find the key, I referenced the higher-level repeater and worked through it's data.
 */
add_filter('acf/load_field/key=field_5e8047497ef48', 'acf_populate_genimage_filters_layer_names_choices');