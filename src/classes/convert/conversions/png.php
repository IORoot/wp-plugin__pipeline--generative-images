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
        // dbus = https://gitlab.com/inkscape/inkscape/-/issues/294
        // 2>/dev/null will suppress errors
        exec('dbus-run-session inkscape --without-gui '. $this->input.' -e ' . $this->target . ' 2>/dev/null', $output, $return);

        if ($return > 0) {
            $this::debug([
                'message' => 'Inkscape did not execute correctly.',
                'result' => $return,
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