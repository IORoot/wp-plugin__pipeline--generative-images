<?php

namespace genimage\filters;

class none
{
    public function __construct()
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
