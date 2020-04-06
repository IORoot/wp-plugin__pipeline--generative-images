<?php

namespace genimage\svg;

class build_shape
{
    public $shape;

    public $args;

    public $shape_type;



    public function __construct($args, $shape_type)
    {
        $this->args = $args;
        $this->shape_type = $shape_type;
    }


    public function render()
    {
        $switch = $this->shape_type;
        $this->$switch();

        return $this->shape;
    }



    public function rect()
    {
        $shape = '<rect';
        foreach ($this->args as $key => $value) {
            $shape .= ' '.$key.'="'.$value.'" ';
        }
        $shape .= ' ></rect>';

        $this->shape = $shape;

        return;
    }


    
    public function circle()
    {
        $r = $this->args['width']/2;
        $cx = $this->args['x'] - $r+($r*2);
        $cy = $this->args['y'] - $r+($r*2);

        $shape = '<circle';
        $shape .= ' cx="' . $cx. '" ';
        $shape .= ' cy="' . $cy. '" ';
        $shape .= ' r="' . $r*$this->args['scale']. '" ';
        $shape .= ' fill="' . $this->args['fill']. '" ';
        $shape .= ' opacity="' . $this->args['opacity']. '" ';
        $shape .= ' filter="' . $this->args['filter']. '" ';
        $shape .= ' ></circle>';

        $this->shape = $shape;

        return;
    }


    public function square_cross()
    {
        $shape .= '<svg viewBox="0 0 5 5"  ';
        foreach ($this->args as $key => $value) {
            $shape .= ' '.$key.'="'.$value.'" ';
        }
        $shape .= ' ><path d="M2 1 h1 v1 h1 v1 h-1 v1 h-1 v-1 h-1 v-1 h1 z"></path></svg>';


        $this->shape = $shape;

        return;
    }



    public function triangle()
    {
        $points = '80 80 40 10 0 80';
        $this->shape = $this->polygon($points);

        return;
    }



    public function cross()
    {
        $points = '28.75 -5 28.75 28.75 -5 28.75 -5 51.25 28.75 51.25 28.75 85 51.25 85 51.25 51.25 85 51.25 85 28.75 51.25 28.75 51.25 -5';
        $poly_params = ' transform="translate(40.000000, 40.000000) rotate(-315.000000) translate(-40.000000, -40.000000)" ';
        $this->shape = $this->polygon($points, null, $poly_params);

        return;
    }



    public function square_plus()
    {
        $points = '30 0 30 30 0 30 0 50 30 50 30 80 50 80 50 50 80 50 80 30 50 30 50 0';
        $this->shape = $this->polygon($points);

        return;
    }



    public function right_angled_triangle()
    {
        $triangle = [
            'tl' => '0 0 ',
            'tr' => '80 0 ',
            'bl' => '80 80 ',
            'br' => '0 80 '
        ];

        shuffle($triangle);
        $points = $triangle[0] . $triangle[1]. $triangle[2];
        $this->shape = $this->polygon($points);

        return;
    }



    public function polygon($points, $svg_params = null, $poly_params = null)
    {
        $shape .= '<svg viewBox="0 0 80 80" ';
        $shape .= ' x="' . $this->args['x']. '" ';
        $shape .= ' y="' . $this->args['y']. '" ';
        $shape .= ' width="' . $this->args['width']*$this->args['scale']. '" ';
        $shape .= ' height="' . $this->args['height']*$this->args['scale']. '" ';
        $shape .= ' filter="' . $this->args['filter']. '" ';
        $shape .= $svg_params;
        $shape .= '>';

        $shape .= '<polygon ';
        $shape .= ' points="' .  $points . '" ';
        $shape .= ' fill="' . $this->args['fill']. '" ';
        $shape .= ' fill-opacity="' . $this->args['opacity']. '" ';
        $shape .= $poly_params;
        $shape .= '></polygon>';

        $shape .= '</svg>';

        return $shape;
    }
}
