<?php

function acf_populate_genimage_instance_source_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();
    
    $choices = get_field('genimage_source', 'option', true);

    if( is_array($choices) ) {
        
        foreach( $choices as $choice ) {
            $choice_name = $choice['genimage_source_slug'];
            $field['choices'][ $choice_name ] = $choice_name;
        }   
    }

    // return the field
    return $field;
    
}

add_filter('acf/load_field/name=genimage_instance_source', 'acf_populate_genimage_instance_source_choices');