<?php

/**
 * On save of options page, run.
 */
function save_genimage_options()
{
    $result = '';
    $screen = get_current_screen();

    if ($screen->id != "pipeline_page_generativeimages") {
        return;
    }
        
    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                           Kick off the program                          │
    // └─────────────────────────────────────────────────────────────────────────┘
    $generator = new \genimage\generator;
    $generator->run();
    $image_results = $generator->result(); 
    
    foreach ($image_results as $image)
    {
        $result .= $image;
    }

    /**
     * Update results 'message' field.
     */

     // not working?
    $field = new update_acf_options_field;
    $field->set_field('field_5fbbc2cb11d5c');
    $field->set_value('message', $result);
    $field->run();

}

// MUST be in a hook
add_action('acf/save_post', 'save_genimage_options', 20);