<?php

function acf_check_for_inkscape($field)
{
    
    // $message;
    $result = '<p style="color:red">Inkscape NOT installed. PNG->JPG convert will not work.</p>';

    // $return = exit code.
    exec('inkscape --version', $output, $return);

    if ($return == 0) {
        $result = '<p style="color:green">Inkscape '.$output[0].' installed.</p>' ;
    }
    
    $message = preg_replace('/\<b\>.*\<\/b\>/', '<b>'.$result . '</b>', $field['message']);

    $field['message'] = $message;

    return $field;
}

/**
 * Using 'key' instead of 'name' to reference the field.
 * To find the key, I referenced the higher-level repeater and worked through it's data.
 */
add_filter('acf/load_field/key=field_5fbb9544697c0', 'acf_check_for_inkscape');
