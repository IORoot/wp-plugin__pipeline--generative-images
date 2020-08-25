<?php

namespace genimage;

class instance
{

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




    public function set_config($config)
    {
        $this->config = $config;
    }


    public function run()
    {
        $this->images();
        $this->filters();
        $this->svg();
        $this->convert();

        return;
    }


    /**
     * Gets image data.
     *
     * 'instance_source'  => get_article | get_category | get_query
     *
     * returns :
     * 0 => [
     *      0 => Relative Directory
     *      1 => width
     *      2 => height
     *      3 => false
     *      4 => URL
     * ]
     *
     * @return array
     */
    private function images()
    {
        $images = new images;
        $images->set_instance_source($this->config['instance_source']);
        $images->run();
        $this->images = $images->get_images();
    }



    private function filters()
    {
        $filters = new filters;
        $filters->set_filter_slug($this->config['instance_filter']);
        $filters->run();
        $this->filters = $filters->get_filters();
    }


    private function svg()
    {
        $svg_group = new svg_group;
        $svg_group->set_filters($this->filters);
        $svg_group->set_images($this->images);
        $svg_group->run();

        $this->svg_group = $svg_group->get_svg_group();
    }


    private function convert()
    {

        $convert_group = new convert_group;
        $convert_group->set_svg_group($this->svg_group);
        $convert_group->set_image_group($this->images);
        $convert_group->run();
    }

}
