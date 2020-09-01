<?php

namespace genimage\filters;

use genimage\utils\utils as utils;
use genimage\interfaces\filterInterface;

class image_grid implements filterInterface
{
    public $filtername =    'image_grid';
    public $filterdesc =    'The image grid will take ALL results (not just the specific post) and'.PHP_EOL.
                            'generate an image grid from their featured images.'.PHP_EOL.
                            'This can be used to generate a scheduled post image of the top 3 posts'.PHP_EOL.
                            'from the past week, for example.'.PHP_EOL.PHP_EOL.
                            'The following settings are available:'.PHP_EOL.

                            '# rows'.PHP_EOL.
                            'This is the number of rows you wish the grid to have.'.PHP_EOL.
                            'Defaults to 1'.PHP_EOL.PHP_EOL.

                            '# columns'.PHP_EOL.
                            'The number of columns in the image grid.'.PHP_EOL.
                            'Defaults to 1'.PHP_EOL.PHP_EOL.

                            '# cell_width'.PHP_EOL.
                            'This is the viewbox width of the SVG container that wraps the <image> element.'.PHP_EOL.
                            'Gives greater control over the clipping and trimming of the main image.'.PHP_EOL.
                            'For example, to convert a 4:3 image to 16:9, you can give a cell_width'.PHP_EOL.
                            'of 100% and a cell_height (see below) of 56.25%. This will trim the image'.PHP_EOL.
                            'to look like a 16:9 one. Similarly, For 50% width, give 28.125% height.'.PHP_EOL.
                            'Defaults to 100%'.PHP_EOL.PHP_EOL.

                            '# cell_height'.PHP_EOL.
                            'Controls the SVG wrapper viewbox height for each <image> tag.'.PHP_EOL.
                            'It will render like this:'.PHP_EOL.
                            '<svg viewbox="0 0 cell_width cell_height"><image></image></svg>'.PHP_EOL.
                            'Defaults to 100%'.PHP_EOL.PHP_EOL.

                            '#image_parameters'.PHP_EOL.
                            'These are any additional parameters you want to set on each of the images in'.PHP_EOL.
                            'the grid. For instance, you can add an ID to call a filter, change widths'.PHP_EOL.
                            'or heights, or add classes.'.PHP_EOL.
                            'defaults to width="100%" height="100%"'.PHP_EOL . PHP_EOL.
                            '';
    public $example    =    '['.PHP_EOL.
                            '   \'rows\' => 2,'.PHP_EOL.
                            '   \'columns\' => 2,'.PHP_EOL.
                            '   \'cell_width\' => \'50%\','.PHP_EOL.
                            '   \'cell_height\' => \'28.125%\','.PHP_EOL.
                            '   \'image_parameters\' => \'width="100%" height="100%" filter="url(#aden)"\''.PHP_EOL.
                            ']';
    public $output     =    '<svg viewBox="0 0 1 1" width="50%" height="28.125%" x="0%" y="0%" preserveAspectRatio="xMidYMid slice"><image xlink:href="/wp-content/uploads/2020/08/AheV97Qj-vM.jpg" width="100%" height="100%" filter="url(#aden)"></image></svg>'.PHP_EOL.
                            '<svg viewBox="0 0 1 1" width="50%" height="28.125%" x="50%" y="0%" preserveAspectRatio="xMidYMid slice"><image xlink:href="/wp-content/uploads/2020/08/hCPplAHkLuo.jpg" width="100%" height="100%" filter="url(#aden)"></image></svg>'.PHP_EOL.
                            '<svg viewBox="0 0 1 1" width="50%" height="28.125%" x="0%" y="28.125%" preserveAspectRatio="xMidYMid slice"><image xlink:href="/wp-content/uploads/2020/08/BFnhevLEL4Q.jpg" width="100%" height="100%" filter="url(#aden)"></image></svg>'.PHP_EOL.
                            '<svg viewBox="0 0 1 1" width="50%" height="28.125%" x="50%" y="28.125%" preserveAspectRatio="xMidYMid slice"><image xlink:href="/wp-content/uploads/2020/08/AheV97Qj-vM.jpg" width="100%" height="100%" filter="url(#aden)"></image></svg>';
    

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
    public $rows;
    public $columns;
    public $image_parameters;

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
        
    public function set_source_object($source_object)
    {
        return;
    }

    public function run()
    {
        $this->params_to_array();
        $this->set_image_paths();
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


    private function set_grid_variables()
    {
        $this->set_image_count();
        $this->set_cell_width();
        $this->set_cell_height();
        $this->set_rows();
        $this->set_cell_columns();
        $this->set_image_parameters();
    }

    private function set_image_count()
    {
        $this->image_count = count($this->params['images']);
    }

    private function set_cell_width()
    {
        if (!isset($this->params['cell_width'])){ $this->cell_width = '100%'; return; }
        $this->cell_width = $this->params['cell_width'];
    }

    private function set_cell_height()
    {
        if (!isset($this->params['cell_height'])){ $this->cell_height = '100%'; return; }
        $this->cell_height = $this->params['cell_height'];
    }

    private function set_rows()
    {
        if (!isset($this->params['rows'])){ $this->rows = 1; return; }
        $this->rows = $this->params['rows'];
    }

    private function set_cell_columns()
    {
        if (!isset($this->params['columns'])){ $this->columns = 1; return; }
        $this->columns = $this->params['columns'];
    }

    private function set_image_parameters()
    {
        if (!isset($this->params['image_parameters'])){ $this->image_parameters = 'width="100%" height="100%"'; return; }
        $this->image_parameters = $this->params['image_parameters'];
    }

    private function params_to_array()
    {
        $args = utils::lb($this->params);
        $this->params = eval("return $args;");
    }

    private function set_image_paths()
    {
        foreach ($this->images as $key => $instance)
        {
            $this->params['images'][$key] = str_replace('../../../..','',$instance[0]);
        }
    }


// ┌─────────────────────────────────────────────────────────────────────────┐
// │                                                                         │
// │                                GENERATE                                 │
// │                                                                         │
// └─────────────────────────────────────────────────────────────────────────┘

    private function iterate_rows()
    {
        for ($this->row_id = 1; $this->row_id <= $this->rows; $this->row_id++) {
            $this->iterate_columns();
        }
    }

    private function iterate_columns()
    {
        for ($this->column_id = 1; $this->column_id <= $this->columns; $this->column_id++) {
            $this->create_image();
        }
    }

    private function create_image()
    {
        $image_url    = $this->params['images'][$this->image_key];

        $x = preg_replace('/[^0-9|\.]/', '', $this->cell_width);
        $y = preg_replace('/[^0-9|\.]/', '', $this->cell_height);
        
        $image = '<svg viewBox="0 0 1 1" 
                    width="'.$this->cell_width.'" 
                    height="'.$this->cell_height.'" 
                    x="'. ($this->column_id - 1) * (float) $x.'%" 
                    y="'. ($this->row_id - 1) * (float) $y.'%" 
                    preserveAspectRatio="xMidYMid slice">';
            $image .= '<image ';
            $image .= ' xlink:href="'. $image_url .'"';
            $image .= ' ' . $this->image_parameters;
            $image .= ' ></image>';
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