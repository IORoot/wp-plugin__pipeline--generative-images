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


    /**
     * set_instance_source
     * 
     * wp_post | wp_term | wp_query
     *
     * @param string $instance_source
     * @return void
     */
    public function set_instance_source($instance_source)
    {
        $this->instance_source = $instance_source;
    }

    /**
     * set_source_objects
     * 
     * Optional to override the source objects.
     * Used within the filter 'get_gi_images' to manually
     * input the post to work on.
     *
     * @param array $source_objects
     * @return void
     */
    public function set_source_objects($source_objects)
    {
        $this->source_objects = $source_objects;
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
        /**
         * This is so you can manually override the source objects
         * using $this->set_source_objects() first.
         */
        if ($this->source_objects != null)
        {
            return;
        }
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