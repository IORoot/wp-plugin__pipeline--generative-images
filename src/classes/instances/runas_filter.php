<?php

namespace genimage;


class runas_filter
{

    use debug;

    /**
     * filter_slug variable
     * 
     * The slug of the filter group you want to process.
     *
     * @var string
     */
    private $filter_slug;

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
     * save_types
     * 
     * Array of what to save the file as.
     * 
     * [
     *      svg : true,
     *      png : false,
     *      jpg : true,
     * ]
     *
     * @var array
     */
    private $save_types;




    public function set_images($images)
    {
        $this->images = $images;
    }

    public function set_save_types($save_types)
    {
        $this->save_types = $save_types;
    }

    public function set_filter_slug($filter_slug)
    {
        $this->filter_slug = $filter_slug;
    }

    public function get_converted()
    {
        return $this->converted;
    }

    public function run()
    {
        $this::debug_clear();
        $this->filters();
        $this->svg();
        $this->convert();
    }


    private function filters()
    {
        $filters = new filters;
        $filters->set_filter_slug($this->filter_slug);
        $filters->run();
        $this->filters = $filters->get_filters();

        $this->continue($this->filters, 'filters');
    }


    private function svg()
    {
        $svg_group = new svg_group;
        $svg_group->set_filters($this->filters);
        $svg_group->set_images($this->images);
        $svg_group->run();
        $this->svg_group = $svg_group->get_svg_group();

        $this->continue($this->svg_group, 'svg_group');
    }


    private function convert()
    {
        $convert_group = new convert_group;
        $convert_group->set_svg_group($this->svg_group);
        $convert_group->set_image_group($this->images);
        $convert_group->set_save_types($this->save_types);
        $convert_group->run();
        $this->converted = $convert_group->get_converted();

        $this->continue($this->converted, 'converted');
    }



    private function continue($stage, $name)
    {
        if ($stage == null || $stage[0] == null)
        {
            $json = json_encode($stage, JSON_PRETTY_PRINT);
            die ('no results in ' . $name . ' : '. $json);
        }
    }



}
