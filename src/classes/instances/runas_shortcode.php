<?php

namespace genimage;

class runas_shortcode
{

    use debug;

    /**
     * Config Array
     *
     * 'instance_slug'    => SLUG,
     * 'instance_source'  => get_article | get_category | get_query,
     * 'instance_filter'  => filter slug
     * 'instance_enabled' => true | false
     *
     * @var array
     */
    private $config;


    /**
     * Contains an array of instances of current images' metadata.
     * 0 => [
     *      0 => Relative directory of image
     *      1 => width
     *      2 => height
     * ]
     *
     * @var array
     */
    private $images;


    /**
     * Contains an array of each filter layer for filter group
     *
     * 0 => [
     *          'filter_name' => "image"
     *          'filter_parameters => "s:19:"filter="url(#aden)"";"
     *      ]
     * @var [type]
     */
    private $filters;



    /**
     * Array of SVG code for each image.
     * 
     * 0 => '<svg ...>'
     * 1 => '<svg ...>'
     * 2 => '<svg ...>'
     *
     * @var [type]
     */
    private $svg_group;



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


    public function set_config($config)
    {
        $this->config = $config;
    }


    public function run()
    {
        $this::debug_clear();
        $this->images();
        $this->filters();
        $this->svg();
        $this->convert();
        $this->render();
        $this->reattach();
        $this->reset();

        return;
    }



    private function images()
    {
        $images = new images;
        $images->set_instance_source($this->config['instance_source']);
        $images->run();
        $this->images = $images->get_images();
        $this->source_objects = $images->get_source_objects();

        $this->continue($this->images, 'images');
    }


    private function filters()
    {
        $filters = new filters;
        $filters->set_filter_slug($this->config['instance_filter']);
        $filters->run();
        $this->filters = $filters->get_filters();

        $this->continue($this->filters, 'filters');
    }


    private function svg()
    {
        $svg_group = new svg_group;
        $svg_group->set_filters($this->filters);
        $svg_group->set_images($this->images);
        $dimensions = (new options)->get_dimensions($this->config['instance_filter']);
        $svg_group->set_dimensions($dimensions);
        $svg_group->set_source_objects($this->source_objects);
        $svg_group->run();
        $this->svg_group = $svg_group->get_svg_group();

        $this->continue($this->svg_group, 'svg_group');
    }


    private function convert()
    {
        $convert_group = new convert_group;
        $convert_group->set_svg_group($this->svg_group);
        $convert_group->set_image_group($this->images);
        $convert_group->run();
        $this->converted = $convert_group->get_converted();

        $this->continue($this->converted, 'converted');
    }


    private function render()
    {
        $render = new render;
        $render->set_converted($this->converted);
        $render->set_svg_group($this->svg_group);
        $render->run();
    }


    private function reattach()
    {
        $reattach = new reattach;
        $reattach->set_instance_source($this->config['instance_source']);
        $reattach->set_converted($this->converted);
        $reattach->run();
    }

    

    private function reset()
    {
        update_field('gi_save_post', 'none', 'option');
    }


    private function continue($stage, $name)
    {
        if ($stage == null || $stage[0] == null)
        {
            $json = json_encode($stage, JSON_PRETTY_PRINT);
            $this::debug($name . ' is NULL : '. $json, static::class); 
            die ('no results in ' . $name . ' : '. $json);
        }
    }



}
