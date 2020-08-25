<?php

namespace genimage;

class convert_group
{

    /**
     * Array of SVG code for each image.
     * 
     * 0 => '<svg ...>'
     * 1 => '<svg ...>'
     * 2 => '<svg ...>'
     *
     * @var [type]
     */
    public $svg_group;


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
    public $image_group;

    public $svg_key;
    public $svg_data;

    public $result;


    public function set_svg_group($svg_group)
    {
        $this->svg_group = $svg_group;
    }

    public function set_image_group($image_group)
    {
        $this->image_group = $image_group;
    }



    public function run()
    {
        foreach ($this->svg_group as $this->svg_key => $this->svg_data) {
            $this->convert_svg_data_to_file();
        }
    }


    public function convert_svg_data_to_file()
    {
        $convert = new convert();
        $convert->set_svg_data($this->svg_data);
        $convert->set_filename($this->image_group[$this->svg_key][0]);
        $convert->run();
    }



}