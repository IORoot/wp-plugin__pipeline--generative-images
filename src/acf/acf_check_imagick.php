<?php

function acf_check_for_imagick( $field ) {
    
    // $message;
    $result = '<p style="color:red">iMagick NOT installed. SVG->PNG convert will not work.</p>';
    if (extension_loaded('imagick')){
        $result = '<p style="color:green">iMagick v' . phpversion("imagick") .' installed </p>' ;
    }
    
    $message = preg_replace('/\<b\>.*\<\/b\>/', '<b>'.$result.'</b>', $field['message']);

    $field['message'] = $message;

    return $field;
    
}

/**
 * Using 'key' instead of 'name' to reference the field.
 * To find the key, I referenced the higher-level repeater and worked through it's data.
 */
add_filter('acf/load_field/key=field_5fbb9503697bf', 'acf_check_for_imagick');