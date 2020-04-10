<?php

namespace genimage\filters;

use genimage\utils\utils as utils;
use genimage\utils\replace as replace;
use genimage\utils\random as random;
use genimage\svg\build_shape as shape;

class generate_shape
{
    public $params;

    public $shape_args;

    public function __construct($params, $post)
    {
        $args = unserialize($params);

        $replace = new replace;
        $args = $replace->sub($args, $post);
        $args = replace::switch_acf($args, $post);
        $args = replace::switch_term_acf($args, $post);
        $args = utils::lb($args);
        $args = eval("return $args;");

        $this->params = $args;

        return $this;
    }

    public function defs()
    {
        return;
    }


    public function output()
    {
        $corners = $this->get_array_param('corners', 'tl,tr,br,bl');
        $output = $this->random_patchwork_corner(in_array('tl',$corners), in_array('tr',$corners), in_array('br',$corners), in_array('bl',$corners), $this->get_param('cell_size', 80));
        return $output;
    }



    public function random_patchwork_corner($tl = null, $tr = null, $br = null, $bl = null, $s = 80)
    {
        $corner_size = $this->get_param('corner_size', 4);

        $cell_size = $this->get_param('cell_size', 80);
        $cell_divide = 80 / $cell_size; 

        $width = 1280 / $cell_divide;
        $height = 720 / $cell_divide;

        $colour_count = $this->get_param('additional_colours', 0);
        $colour_palette = $this->get_array_param('additional_palette', '');
        if ($colour_count >= 1 && $colour_palette != ''){
            $this->add_colour_to_palette($colour_count);
        }
        
        $output = '';

        for ($y=0; $y<=$height; $y+=$s) {
            for ($x=0; $x<=$width; $x+=$s) {

                $c = $this->random_palette();

                // top-left
                if ($x+$y < $s*$corner_size && $tl) {
                    $output .= $this->random_shape($x, $y, $s, $s, $c);
                }

                // top-right
                if ($x-$y > $s*(15-$corner_size)  && $tr) {
                    $output .= $this->random_shape($x, $y, $s, $s, $c);
                }

                // bottom-left
                if ($x-$y < -$s*(8 - $corner_size) && $bl) {
                    $output .= $this->random_shape($x, $y, $s, $s, $c);
                }

                // bottom-right
                if ($x+$y > $s*(23-$corner_size) && $br) {
                    $output .= $this->random_shape($x, $y, $s, $s, $c);
                }
            }
        }
        return $output;
    }


    public function add_colour_to_palette($count = 1)
    {

        $colour = $this->get_array_param('additional_palette', '');
        if($colour != ''){ shuffle($colour); }

        for ($i = 0; $i < $count; $i++) {
            $this->params['palette'] = $this->params['palette'] . ',' .$colour[$i];
        }
        

        return ;
    }

    
    public function random_palette()
    {
        $palette = $this->get_array_param('palette', '');

        $col = new random;
        $col->set_palette($palette);

        return $col->get_palette();
    }



    public function random_shape($x, $y, $w, $h, $c)
    {
        $cell_size = $this->get_param('cell_size', 80);
        $cell_divide = 80 / $cell_size; 

        $scale = random_int(1, 2);
        $rotate = (random_int(1,3)*90);
        $filter = '';

        $opacity = $this->get_param('opacity', 0.5);

        $shape_types = [
            'rect',
            'cross',
            'square_cross',
            'square_plus',
            'triangle',
            'right_angled_triangle',
            'circle',
            'leaf',
            'dots',
            'lines',
            'wiggles',
            'diamond',
            'flower',
            'stripes',
            'bump',
        ];

        $shape_types = $this->get_array_param('shapes', $shape_types);
        
        $this->shape_args = [
            "x" => $x,
            "y" => $y,
            "width" => $w,
            "height" => $h,
            "fill" => $c,
            "opacity" => $opacity,
            "scale" => $scale,
            "filter" => $filter,
            "rotate" => $rotate,
        ];

        $key = array_rand($shape_types);
        $shape_type = $shape_types[$key];

        $shape = new shape($this->shape_args, $shape_type);
        $output = $shape->render();

        return $output;
    }


    public function get_param($name, $default){

        if (array_key_exists($name, $this->params)) {
            $value = $this->params[$name];          // Set value
            $value = str_replace(' ', '', $value);  // Remove spaces
            return $value;
        }
        return $default;
    }


    public function get_array_param($name, $default){

        if (array_key_exists($name, $this->params)) {
            $value = $this->params[$name];          // Set value
            $value = str_replace(' ', '', $value);  // Remove spaces
            $value = explode(',', $value);          // CSV
            return $value;
        }
        return $default;
    }

    
}
