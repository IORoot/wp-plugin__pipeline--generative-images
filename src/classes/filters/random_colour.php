<?php

namespace genimage\filters;

use genimage\utils\random as rand;

class random_colour
{
    public $params;

    /**
     * __construct
     *
     * @param mixed $params
     * @return void
     */
    public function __construct($params)
    {
        $this->params = unserialize($params);
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
