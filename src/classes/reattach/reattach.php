<?php

namespace genimage;

class reattach
{

    use \genimage\option_reattach;
    
    /**
     * reattach_option variable
     * 
     * none | jpg
     *
     * @var string
     */
    private $reattach_option;



    /**
     * instance_source variable
     *
     * post | term | wp_query
     * 
     * @var string
     */
    private $instance_source;


    /**
     * Array of WP_Posts or WP_Term
     * [
     *      0 => WP_Post
     *      1 => WP_Post
     *      2 => WP_Post
     * ]
     *
     * @var array
     */
    private $source_objects;
    private $source_object;
    private $source_key;
    private $source_type;


    /**
     * converted variable
     *
     * Array of every file thats been converted.
     * 
     * 0 => [
     *          0 => "file.jpg",
     *          1 => "file.png",
     *          2 => "file.svg",
     *      ],
     * 1 => [
     *          0 => "file2.jpg",
     *          1 => "file2.png",
     *          2 => "file2.svg",
     *      ]    
     * 
     * 
     * @var array
     */
    private $converted;
    private $new_featured_image;



    public function set_instance_source($instance_source)
    {
        $this->instance_source = $instance_source;
    }


    public function set_converted($converted)
    {
        $this->converted = $converted;
    }


    public function run()
    {
        $this->get_reattach_option();
        if ($this->reattach_option['reattach'] == 'none'){ return; }
        $this->get_source_objects();
        $this->loop_over_source_objects();
        return;
    }






    private function get_reattach_option()
    {
        $this->reattach_option = $this->get_reattach(); // trait
    }


    private function get_source_objects()
    {
        $this->source_objects = (new options)->get_source($this->instance_source);
    }


    private function loop_over_source_objects()
    {
        foreach($this->source_objects as $this->source_key => $this->source_object)
        {   
            $this->set_source_type();
            $this->set_new_featured_image();
            $this->update_featured_image();
        }
    }

    private function set_source_type()
    {
        $this->source_type = get_class($this->source_object);
    }


    private function set_new_featured_image()
    {
        $this->new_featured_image = $this->converted[$this->source_key][1]; // 1 => JPG
    }


    private function update_featured_image()
    {
        if ($this->source_type == 'WP_Term') {
            $this->update_term();
        } 
        
        if ($this->source_type == 'WP_Post') {
            $this->update_post();
        }
    }


    private function update_term()
    {
        $set = new \genimage\wp\set_image;
        $set->set_filename($this->new_featured_image);
        $set->set_id($this->source_object->term_id);
        $new_attachment_id = $set->update_term_thumbnail();
        $this::debug('New TERM attachment_id: '.$new_attachment_id.' created and attached to Term_ID'.$this->source_object->ID , static::class); 
    }

    private function update_post()
    {
        $set = new \genimage\wp\set_image;
        $set->set_filename($this->new_featured_image);
        $set->set_id($this->source_object->ID);
        $new_attachment_id = $set->update_post_thumbnail();
        $this::debug('New POST attachment_id: '.$new_attachment_id.' created and attached to Post_ID'.$this->source_object->ID , static::class); 
    }


}