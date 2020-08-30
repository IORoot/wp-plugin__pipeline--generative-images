<?php

namespace genimage\filters;

use genimage\utils\random as rand;
use genimage\interfaces\filterInterface;

class random_colour implements filterInterface
{
    public $filtername =    'random_colour';
    public $filterdesc =    'Generates a <linearGradient> definition with the specified parameter as an ID.'.PHP_EOL.
                            'You can then use this random colour by referencing this ID.';
    public $example    =    'myRandomColour';
    public $output     =    '<linearGradient id="myRandomColour"><stop stop-color="#62f6c0"/></linearGradient>';

    public $params;

    public function set_params($params)
    {
        $this->params = unserialize($params);
    }

    public function set_image($image)
    {
        return;
    }

    public function set_all_images($images)
    {
        return;
    }
    
    public function run()
    {
        return $this;
    }

    public function output()
    {
        return;
    }

    public function defs()
    {
        $r = new rand();
        $def = '<linearGradient id="'.$this->params.'"><stop stop-color="'.$r::colour_hex().'"/></linearGradient>';

        return $def;
    }
}
