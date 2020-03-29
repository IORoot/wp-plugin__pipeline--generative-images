<?php

/**
 * Include ACF into plugin.
 * 
 */

  // Create Parent Menu
if( function_exists('acf_add_options_page') ) {
    
    $argsparent = array(
        'page_title' => 'ANDYP',
        'menu_title' => 'ANDYP',
        'menu_slug' => 'andyp',
        'capability' => 'manage_options',
        'position' => '99.11',
        'parent_slug' => '',
        'icon_url' => 'dashicons-screenoptions',
        'redirect' => true,
        'post_id' => 'options',
        'autoload' => false,
        'update_button'		=> __('Update', 'acf'),
        'updated_message'	=> __("Options Updated", 'acf'),
    );
	acf_add_options_page($argsparent);
	acf_add_options_sub_page(array(
        'menu_title'	=> 'AndyP Plugins',
        'parent_slug'	=> 'andyp',
        )
    );
}

if( function_exists('acf_add_options_page') ) {
    
    $args = array(
	
        /* (string) The title displayed on the options page. Required. */
        'page_title' => 'Generative Images',
        
        /* (string) The title displayed in the wp-admin sidebar. Defaults to page_title */
        'menu_title' => 'Generative Images',
        
        /* (string) The URL slug used to uniquely identify this options page. 
        Defaults to a url friendly version of menu_title */
        'menu_slug' => 'generativeimages',
        
        /* (string) The capability required for this menu to be displayed to the user. Defaults to edit_posts.
        Read more about capability here: http://codex.wordpress.org/Roles_and_Capabilities */
        'capability' => 'manage_options',
        
        /* (int|string) The position in the menu order this menu should appear. 
        WARNING: if two menu items use the same position attribute, one of the items may be overwritten so that only one item displays!
        Risk of conflict can be reduced by using decimal instead of integer values, e.g. '63.3' instead of 63 (must use quotes).
        Defaults to bottom of utility menu items */
        'position' => '100.5',
        
        /* (string) The slug of another WP admin page. if set, this will become a child page. */
        'parent_slug' => 'andyp',
        
        /* (string) The icon class for this menu. Defaults to default WordPress gear.
        Read more about dashicons here: https://developer.wordpress.org/resource/dashicons/ */
        'icon_url' => 'dashicons-screenoptions',
        
        /* (boolean) If set to true, this options page will redirect to the first child page (if a child page exists). 
        If set to false, this parent page will appear alongside any child pages. Defaults to true */
        'redirect' => true,
        
        /* (int|string) The '$post_id' to save/load data to/from. Can be set to a numeric post ID (123), or a string ('user_2'). 
        Defaults to 'options'. Added in v5.2.7 */
        'post_id' => 'options',
        
        /* (boolean)  Whether to load the option (values saved from this options page) when WordPress starts up. 
        Defaults to false. Added in v5.2.8. */
        'autoload' => false,
        
        /* (string) The update button text. Added in v5.3.7. */
        'update_button'		=> __('Update', 'acf'),
        
        /* (string) The message shown above the form on submit. Added in v5.6.0. */
        'updated_message'	=> __("Options Updated", 'acf'),
                
    );

    /**
     * Create a new options page.
     */
	acf_add_options_sub_page($args);
	
}