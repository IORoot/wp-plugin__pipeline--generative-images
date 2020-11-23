<?php

function acf_load_shortcode( $field ) {
    
    $field['message'] = do_shortcode('[genimage]');

    return $field;
    
}

/**
 * Using 'key' instead of 'name' to reference the field.
 * To find the key, I referenced the higher-level repeater and worked through it's data.
 */
add_filter('acf/load_field/key=field_5fbbc2cb11d5c', 'acf_load_shortcode');