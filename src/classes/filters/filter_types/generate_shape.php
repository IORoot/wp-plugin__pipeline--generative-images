<?php

namespace genimage\filters;

use genimage\utils\utils;
use genimage\utils\replace;
use genimage\utils\random;
use genimage\svg\build_shape as shape;
use genimage\interfaces\filterInterface;

class generate_shape implements filterInterface
{
    public $filtername =    'generate_shape';
    public $filterdesc =    'This is the big \'generative\' part of the plugin.'. PHP_EOL . PHP_EOL .
                            'Allows you to generate shapes onto a patchwork-quilt like space over the image.'. PHP_EOL . PHP_EOL .
                            'Also includes moustache {{}} substitution.'. PHP_EOL . PHP_EOL .
                            'This would be extremely long as aan example because of the generative nature of the function. Instead, here are the descriptions of settings.';

    public $example    =    "[". PHP_EOL .
                            "   'palette' => ['{{taxonomy_colour}}, #FAFAFA'],". PHP_EOL .
                            "   'additional_palette' => ['#000000','#242424','#FFFFFF'],". PHP_EOL .
                            "   'additional_colours' => 1,". PHP_EOL .
                            "   'opacity' => 0.8,". PHP_EOL .
                            "   'corners' => ['tl','tr','bl','br'],". PHP_EOL .
                            "   'corner_size' => 4,". PHP_EOL .
                            "   'shapes' => ['rect', 'cross', 'square_cross', 'square_plus', 'triangle', 'right_angled_triangle'," . PHP_EOL .
                            "                'leaf', 'dots', 'lines', 'wiggles', 'diamond', 'flower', 'stripes', 'bump']',". PHP_EOL .
                            "   'cell_size' => 40,". PHP_EOL .
                            "   'width' => 1280,". PHP_EOL .
                            "   'height' => 1280,". PHP_EOL .
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
                            '"This is the maximum size of each shape in pixels. This represents a shape block, and is used in combination with the corner_size setting. Default is 80"'. PHP_EOL . PHP_EOL .
                            'width'. PHP_EOL .
                            'Redefine the width of the grid. In pixels.'. PHP_EOL .
                            'height'. PHP_EOL . PHP_EOL .
                            'Redefine the height of the grid. In pixels.'. PHP_EOL .
                            '';

    public $params;

    public $shape_args;

    public $image;

    public $dimensions;

    /**
     * result variable
     *
     * This is the output SVG string.
     *
     * @var string
     */
    public $result;

    private $cell_size;
    private $image_width;
    private $image_height;
    



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
        return;
    }
    
    public function set_source_object($source_object)
    {
        return;
    }

    public function run()
    {
        $this->set_parameters();
        $this->get_image_width();
        $this->get_image_height();
        $this->add_additional_colours();
        $this->result = $this->random_patchwork_corner();
        return $this;
    }

    public function defs()
    {
        return;
    }


    public function output()
    {
        return $this->result;
    }

    //  ┌─────────────────────────────────────────────────────────────────────────┐
    //  │                                                                         │░
    //  │                                                                         │░
    //  │                                 PRIVATE                                 │░
    //  │                                                                         │░
    //  │                                                                         │░
    //  └─────────────────────────────────────────────────────────────────────────┘░
    //   ░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░

    /**
     * Convert any {{moustaches}}, substitutions, linebreaks, etc...
     * Then convert the string into a proper array.
     */
    private function set_parameters()
    {
        // {{moustaches}} to values
        $args = (new replace)->sub($this->params, $this->image);

        // acf fields in posts
        $args = replace::switch_acf($args, $this->image);

        // acf fields in taxonomies
        $args = replace::switch_term_acf($args, $this->image);

        // remove linebreaks
        $args = utils::lb($args);

        // check for array or string
        if (substr( $args, 0, 1 ) !== "["){ $args = "'" . $args; }
        if (substr( $args, -1, 1 ) !== "]"){ $args = $args . "'"; }

        // convert string to array
        $args = eval("return $args;");

        if (is_string($args)){
            $array_args = [$args];
            unset($args);
            $args = $array_args;
        }

        $this->params = $args;
    }

    /**
     * Manual or detected width
     */
    private function get_image_width()
    {
        if (isset($this->params['width'])) {
            $this->image_width = $this->params['width'];
            return;
        }
        if (!isset($this->image[1])) {
            $sizes = getimagesize($this->image[0]);
            $this->image[1] = $sizes[0];
            $this->params['width'] = $sizes[0];
        }
        $this->image_width = $this->image[1];
    }


    /**
     * Manual or detected height
     */
    private function get_image_height()
    {
        if (isset($this->params['height'])) {
            $this->image_height = $this->params['height'];
            return;
        }
        // detect if missing
        if (!isset($this->image[2])) {
            $sizes = getimagesize($this->image[0]);
            $this->image[2] = $sizes[1];
            $this->params['height'] = $sizes[1];
        }
        $this->image_height = $this->image[2];
    }




    public function get_value_or_set_default($name, $default)
    {
        if (!array_key_exists($name, $this->params)) {
            $this->params[$name] = $default;
        }

        $value = $this->params[$name];
        return $value;
    }



    private function add_additional_colours()
    {
        if (!isset($this->params['additional_colours']) || $this->params['additional_colours'] < 1) {
            return;
        }
        if (!isset($this->params['additional_palette']) || $this->params['additional_palette'] == '') {
            return;
        }

        $colour = $this->params['additional_palette'];

        shuffle($colour);

        for ($i = 0; $i < $this->params['additional_colours']; $i++) {
            array_push($this->params['palette'], $colour[$i]);
        }
    }


    private function corner_is_set($corner)
    {
        // default
        if (!isset($this->params['corners'])) {
            $this->params['corners'] = ['tl', 'tr', 'bl', 'br' ];
        }
        return in_array($corner, $this->params['corners']);
    }





    

    

    private function random_patchwork_corner()
    {
        $corner_size = $this->get_value_or_set_default('corner_size', 4);

        $this->cell_size = $cell_size = $this->get_value_or_set_default('cell_size', 80);
        $this->cell_divide = 80 / $this->cell_size;
        $width = $this->image_width / $this->cell_divide;
        $height = $this->image_height / $this->cell_divide;

        $output = '';

        for ($y=0; $y<=$height; $y+=$this->cell_size) {
            for ($x=0; $x<=$width; $x+=$this->cell_size) {

                // top-left
                if ($x+$y < $this->cell_size*$corner_size && $this->corner_is_set('tl')) {
                    $output .= $this->random_shape($x, $y);
                }

                // top-right
                if ($x-$y > $this->cell_size*(15-$corner_size) && $this->corner_is_set('tr')) {
                    $output .= $this->random_shape($x, $y);
                }

                // bottom-left
                if ($x-$y < -$this->cell_size*(8 - $corner_size) && $this->corner_is_set('bl')) {
                    $output .= $this->random_shape($x, $y);
                }

                // bottom-right
                if ($x+$y > $this->cell_size*(23-$corner_size) && $this->corner_is_set('br')) {
                    $output .= $this->random_shape($x, $y);
                }
            }
        }
        return $output;
    }




    

    public function random_shape($x, $y)
    {
        $shape_types = $this->get_value_or_set_default('shapes', ['triangle']);
        $shape_type = shuffle($shape_types);
        
        $this->shape_args = [
            "x" => $x,
            "y" => $y,
            "width" => $this->cell_size,
            "height" => $this->cell_size,
            "fill" => $this->random_palette(),
            "opacity" => $this->get_value_or_set_default('opacity', 0.5),
            "scale" => random_int(1, 2),
            "filter" => '',
            "rotate" => (random_int(1, 3)*90),
            "shape_type" => $shape_types[0],
        ];


        $shape = new shape($this->shape_args);
        $output = $shape->render();

        return $output;
    }



    public function random_palette()
    {
        $palette = $this->get_value_or_set_default('palette', ['#ffffff', '#000000']);

        $col = new random;
        $col->set_palette($palette);

        return $col->get_palette();
    }
}
