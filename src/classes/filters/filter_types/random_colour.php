<?php

namespace genimage\filters;

use genimage\utils\random as rand;

class random_colour
{
    public $filtername =    'random_colour';
    public $filterdesc =    'Generates a <linearGradient> definition with the specified parameter as an ID.'.PHP_EOL.
                            'You can then use this random colour by referencing this ID.';
    public $example    =    'myRandomColour';
    public $output     =    '<linearGradient id="myRandomColour"><stop stop-color="#62f6c0"/></linearGradient>';

    public $params;

    public $post;

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
        return $this;
    }

    /**
     * output
     *
     * @return void
     */
    public function output()
    {
    }

    /**
     * defs
     *
     * @return void
     */
    public function defs()
    {
        $r = new rand();
        $def = '<linearGradient id="'.$this->params.'"><stop stop-color="'.$r::colour_hex().'"/></linearGradient>';

        return $def;
    }
}
