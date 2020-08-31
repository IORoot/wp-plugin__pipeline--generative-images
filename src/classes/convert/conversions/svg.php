<?php

namespace genimage\convert;

use genimage\interfaces\convertInterface;

class svg implements convertInterface
{

    use \genimage\debug;
    
    private $target;
    private $input;
    private $output;

    public function target($target)
    {
        $this->target = $target;
    }

    public function in($input)
    {
        $this->input = $input;
    }

    public function out()
    {
        rename($this->input, $this->target);
        $this::debug($this->target, static::class);
        $this->target_path_to_relative();
        return $this->target;
    }

    private function target_path_to_relative()
    {
        $this->target = str_replace(ABSPATH, '', $this->target);
    }

}