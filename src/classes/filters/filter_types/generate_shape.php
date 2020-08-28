<?php

namespace genimage\filters;

use genimage\utils\utils as utils;
use genimage\utils\replace as replace;
use genimage\utils\random as random;
use genimage\svg\build_shape as shape;

class generate_shape
{
    public $filtername =    'generate_shape';
    public $filterdesc =    'This is the big \'generative\' part of the plugin.'. PHP_EOL . PHP_EOL .
                            'Allows you to generate shapes onto a patchwork-quilt like space over the image.'. PHP_EOL . PHP_EOL .
                            'Also includes moustache {{}} substitution.'. PHP_EOL . PHP_EOL .
                            'This would be extremely long as aan example because of the generative nature of the function. Instead, here are the descriptions of settings.';

    public $example    =    "[". PHP_EOL .
                            "   'palette' => '{{taxonomy_colour}}, #FAFAFA',". PHP_EOL .
                            "   'additional_palette' => '#000000,#242424,#424242,#757575,#E0E0E0,#F5F5F5,#FAFAFA,#FFFFFF',". PHP_EOL .
                            "   'additional_colours' => 1,". PHP_EOL .
                            "   'opacity' => 0.8,". PHP_EOL .
                            "   'corners' => 'tl,tr,bl,br',". PHP_EOL .
                            "   'corner_size' => 4,". PHP_EOL .
                            "   'shapes' => 'rect, cross, square_cross, square_plus, triangle, right_angled_triangle,". PHP_EOL .
                            "                leaf, dots, lines, wiggles, diamond, flower, stripes, bump',". PHP_EOL .
                            "   'cell_size' => 40,". PHP_EOL .
                            "]";
    public $output     =    'palette'. PHP_EOL .
                            '"The palette setting tells the generator which base colours to add to its primary palette. It will randomly select a colour from this palette.'. PHP_EOL .
                            'As you can see in the example, it also has {{moustache}} replacement for post/term fields too."'. PHP_EOL . PHP_EOL .
                            'additional_palette'. PHP_EOL .
                            '"You can define a secondary palette that the generator can select from and add to the primary palette.'. PHP_EOL .
                            'This is used alongside the "additional_colours" setting to specify how many of these colours should be randomly added to the primary palette."'. PHP_EOL . PHP_EOL .
                            'additional_colours'. PHP_EOL .
                            '"The number of colours to pick from the additional_palette to add into the main palette. Randomly selects them. Useful if you want a single'. PHP_EOL .
                            'main colour paired a random secondary colour."'. PHP_EOL . PHP_EOL .
                            'opacity'. PHP_EOL .
                            '"The opacity of each shape that is generated"'. PHP_EOL . PHP_EOL .
                            'corners'. PHP_EOL .
                            '"The generated shapes are placed into one of the corners of the image. You can have TL, TR, BL, BR. '. PHP_EOL .
                            'Top-Left, Top-Right, Bottom-Left, Bottom-Right."'. PHP_EOL . PHP_EOL .
                            'corner_size'. PHP_EOL .
                            '"This is the number of blocks of shapes that are built coming away from the corner."'. PHP_EOL . PHP_EOL .
                            'shapes'. PHP_EOL .
                            '"The type of SVG shapes to use in the generation process. If the setting is not specifieed, then all shapes are used."'. PHP_EOL . PHP_EOL .
                            'cell_size'. PHP_EOL .
                            '"This is the maximum size of each shape in pixels. This represents a shape block, and is used in combination with the corner_size setting. Default is 80"'. PHP_EOL .
                            '';

    public $params;

    public $shape_args;


    public function set_params($params)
    {
        $this->params = unserialize($params);
    }

    public function set_post($post)
    {
        $this->post = $post;
    }


    public function run()
    {
        $args = $this->params;
        $post = $this->post;

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
