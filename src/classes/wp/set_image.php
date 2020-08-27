<?php

namespace genimage\wp;

class set_image
{

    /**
     * This is the new file to add to the post
     */
    public $filename;

    /**
     * Post ID to attach the new image to.
     */
    public $id;


    public $attachment_id;



    public function set_filename($filename)
    {
        $this->filename = $filename;
    }

    
    public function set_id($id)
    {
        $this->id = $id;
    }

    
    public function update_post_thumbnail()
    {
        $this->create_attachment();
        set_post_thumbnail($this->id, $this->attachment_id);
        return $this->attachment_id;
    }



    public function update_term_thumbnail()
    {
        $this->create_attachment();
        update_field('article_category_image', $this->attachment_id, 'term_'.$this->id);
        return $this->attachment_id;
    }



    public function create_attachment(){

        // Check the type of file. We'll use this as the 'post_mime_type'.
        $filetype = wp_check_filetype(basename($this->filename), null);
        
        // Get the path to the upload directory.
        $wp_upload_dir = wp_upload_dir();
        
        // Prepare an array of post data for the attachment.
        $attachment = array(
            'guid'           => $wp_upload_dir['url'] . '/' . basename($this->filename),
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace('/\.[^.]+$/', '', basename($this->filename)),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );
        
        $new_file = str_replace(get_site_url() . '/', '', $this->filename);

        // Insert the attachment.
        $this->attachment_id = $attach_id = wp_insert_attachment($attachment, $new_file, $this->id);
        
        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        
        // Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata($attach_id, $new_file);


        wp_update_attachment_metadata($attach_id, $attach_data);
    }

}
