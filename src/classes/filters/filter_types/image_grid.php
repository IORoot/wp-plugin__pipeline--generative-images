<?php

namespace genimage\filters;

use genimage\utils\utils as utils;
use genimage\interfaces\filterInterface;

class image_grid implements filterInterface
{
    public $filtername =    'image_grid';
    public $filterdesc =    ''.PHP_EOL.PHP_EOL.
                            ''.PHP_EOL.PHP_EOL.
                            '';
    public $example    =    '';
    public $output     =    '';
    

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
    
    public $params;

    public $image;
    public $image_key = 0;

    public $result;

    public $image_count;
    public $cell_width;
    public $cell_height;

    public $row_id;
    public $column_id;

    public function set_params($params)
    {
        $this->params = unserialize($params);
    }

    public function set_image($image)
    {
        $this->image = $image;
    }

    public function set_all_images($images)
    {
        $this->images = $images;
    }
    
    public function run()
    {
        $this->params_to_array();
        $this->set_grid_variables();
        $this->iterate_rows();
        return $this;
    }

    public function output(){
        return $this->result;
    }

    public function defs(){
        return;
    }

//  ┌─────────────────────────────────────────────────────────────────────────┐
//  │                                                                         │░
//  │                                                                         │░
//  │                                 PRIVATE                                 │░
//  │                                                                         │░
//  │                                                                         │░
//  └─────────────────────────────────────────────────────────────────────────┘░
//   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░


    private function params_to_array()
    {
        $args = utils::lb($this->params);
        $this->params = eval("return $args;");
    }


    private function set_grid_variables()
    {
        foreach ($this->images as $key => $instance)
        {
            $this->params['images'][$key] = str_replace('../../../..','',$instance[0]);
        }

        $this->image_count = count($this->params['images']);
        $this->cell_width = 100 / $this->params['columns'];
        $this->cell_height = 100 / $this->params['rows'];
    }


    private function iterate_rows()
    {
        for ($this->row_id = 1; $this->row_id <= $this->params['rows']; $this->row_id++) {
            $this->iterate_columns();
        }
    }

    private function iterate_columns()
    {
        for ($this->column_id = 1; $this->column_id <= $this->params['columns']; $this->column_id++) {
            $this->create_image();
        }
    }



    private function create_image()
    {
        $image_url    = $this->params['images'][$this->image_key];
        
        $image = '<svg viewBox="0 0 1 1" 
                    width="'.$this->cell_width.'%" 
                    height="'.$this->cell_height.'%" 
                    x="'. ($this->column_id - 1) * $this->cell_width.'%" 
                    y="'. ($this->row_id - 1) * $this->cell_height.'%" 
                    preserveAspectRatio="xMidYMid slice">';
            $image .= '<image ';
            $image .= ' xlink:href="'. $image_url .'"';
            $image .= ' width="100%"';
            $image .= ' height="100%" ';
            $image .= '></image>';
        $image .= '</svg>';
        
        $this->result .= $image;

        $this->update_iterator_key();
    }




    private function update_iterator_key()
    {
        // Loop back around to beginning of images
        if ($this->image_key == ($this->image_count - 1) )
        {
            $this->image_key = 0;
            return;
        }

        $this->image_key++;
    }

}