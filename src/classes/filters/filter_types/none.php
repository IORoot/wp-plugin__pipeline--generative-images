<?php

namespace genimage\filters;

class none
{

    public $filtername =    'None';
    public $filterdesc =    'Returns nothing - Use to disable another filter.';
    public $example    =    'None';
    public $output     =    '';

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

    public function output()
    {
        return;
    }

    public function defs()
    {
        return;
    }
}
