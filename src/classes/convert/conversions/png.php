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
        // PWD = "/var/www/vhosts/dev.londonparkour.com"
        exec('inkscape --without-gui '. $this->input.' -e '.$this->target, $output, $return);

        if ($return > 0) {
            $this::debug([
                'message' => 'Inkscape did not execute correctly.',
                'result' => $result,
                'output' => $output,
            ], static::class);

            return false;
        }
        $this->target_path_to_relative();
        $this::debug($output, static::class);

        return $this->target;
    }

    private function target_path_to_relative()
    {
        $this->target = str_replace(ABSPATH, '', $this->target);
    }
}