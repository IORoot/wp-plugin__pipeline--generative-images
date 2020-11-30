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

        $this->rewrite_paths_in_intermediate_file_to_ABSPATH();

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

    /**
     * Inkscape needs all images to be absolute to work correctly.
     * This is because it sits outside of apache/php and will have
     * a different working path.
     */
    private function rewrite_paths_in_intermediate_file_to_ABSPATH()
    {
        $file_contents = file_get_contents($this->input);
        $file_contents = str_replace('/wp-content', ABSPATH.'/wp-content',$file_contents);
        file_put_contents($this->input, $file_contents);
    }

}