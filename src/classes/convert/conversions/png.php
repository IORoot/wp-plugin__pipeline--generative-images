<?php

namespace genimage\convert;

use genimage\interfaces\convertInterface;

class png implements convertInterface
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
        exec('inkscape --without-gui '. $this->input.' -e '.$this->target, $output, $return);

        if ($return > 0) {
            $this::debug([
                'message' => 'Inkscape did not execute correctly.',
                'result' => $result,
                'output' => $output,
            ], static::class);

            return false;
        }

        $this::debug($output, static::class);

        return $this->target;
    }

}