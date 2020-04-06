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

        $args = replace::switch($args, $post);
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

        $corners = explode(',', $this->params['corners']);
        $output .= $this->random_patchwork_corner(in_array('tl',$corners), in_array('tr',$corners), in_array('br',$corners), in_array('bl',$corners), 80);
        
        return $output;
    }



    public function random_patchwork_corner($tl = null, $tr = null, $br = null, $bl = null, $s = 80)
    {
        $corner_size = 4;
        $width = 1280;
        $height = 720;
        $this->add_colour_to_palette($this->params['additional_colours']);

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

        $colour = explode(',', $this->params['additional_palette']);

        shuffle($colour);

        for ($i = 0; $i < $count; $i++) {
            $this->params['palette'] = $this->params['palette'] . ',' .$colour[$i];
        }
        

        return ;
    }

    
    public function random_palette()
    {

        // Single Colour
        $palette = explode(',', $this->params['palette']);

        $col = new random;
        $col->set_palette($palette);

        return $col->get_palette();
    }



    public function random_shape($x, $y, $w, $h, $c)
    {
        $scale = random_int(1, 2);
        $opacity = 0.5;
        $filter = '';
        if($this->params['opacity']){ $opacity = $this->params['opacity']; }

        $shape_types = [
            'rect',
            'cross',
            'square_cross',
            'square_plus',
            'triangle',
            'right_angled_triangle',
            'circle',
        ];

        $this->shape_args = [
            "x" => $x,
            "y" => $y,
            "width" => $w,
            "height" => $h,
            "fill" => $c,
            "opacity" => $opacity,
            "scale" => $scale,
            "filter" => $filter,
        ];

        $key = array_rand($shape_types);
        $shape_type = $shape_types[$key];

        $shape = new shape($this->shape_args, $shape_type);
        $output = $shape->render();

        return $output;
    }
}
