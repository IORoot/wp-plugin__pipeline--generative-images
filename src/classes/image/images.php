<?php

namespace genimage;

class images
{

    private $instance_source;

    /**
     * Array of WP_Posts or WP_Term
     * 
     * 0 => WP_Post
     * 1 => WP_Post
     * 2 => WP_Post
     *
     * @var array
     */
    private $source_objects;  

        /**
     * Contains an array of instances of current images' metadata.
     * 0 => [
     *      0 => Relative Directory
     *      1 => width
     *      2 => height
     *      3 => false
     *      4 => URL
     * ]
     *
     * @var array
     */
    private $images;


    public function set_instance_source($instance_source)
    {
        $this->instance_source = $instance_source;
    }

    public function get_images()
    {
        return $this->images;
    }


    public function run()
    {
        $this->get_source_wpposts();
        $this->loop_over_collection();
    }


    private function get_source_wpposts()
    {
        $source_type = $this->instance_source;
        $this->source_objects = (new options)->get_source($source_type);
    }


    private function loop_over_collection()
    {
        foreach($this->source_objects as $source_data)
        {
            $this->images[] = (new image_details)::get_image_meta($source_data);
        }
    }



}