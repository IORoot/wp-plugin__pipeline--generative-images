<?php

namespace genimage\filters;

use genimage\interfaces\filterInterface;

class none implements filterInterface
{

    public $filtername =    'None';
    public $filterdesc =    'Returns nothing - Use to disable another filter.';
    public $example    =    'None';
    public $output     =    '';

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
        
    public function set_source_object($source_object)
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
        return;
    }
}
