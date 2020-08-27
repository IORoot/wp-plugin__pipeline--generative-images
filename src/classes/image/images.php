<?php

namespace genimage;

class images
{

    use debug;

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

        $this::debug( ['posts' => $this->images], static::class);
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

        $this->source_objects = (new options)->get_source($this->instance_source);
    }


    private function loop_over_collection()
    {
        foreach($this->source_objects as $source_data)
        {
            $this->images[] = $this->get_image_meta($source_data);
        }
    }


    /**
     * Returns array on image
     * 
     * e.g.
     * 0 => Relative Directory
     * 1 => width
     * 2 => height
     * 3 => false
     * 4 => URL
     */
    private function get_image_meta($wp_post_or_term)
    {
        $wp = new \genimage\wp\get_image;
        $image = $wp->get_image_url($wp_post_or_term);
        return $image;
    }



}