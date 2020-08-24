<?php

namespace genimage\utils;

class rename_file
{

    public $suffix = '_gi';
    
    public function rename_file($file, $format)
    {

        // remove the suffix
        $file = str_replace($this->suffix.'.png', '.png', $file);
        $file = str_replace($this->suffix.'.jpg', '.jpg', $file);

        // Reset the format
        $file = str_replace('.png', $this->suffix.'.'.$format, $file);
        $file = str_replace('.jpg', $this->suffix.'.'.$format, $file);

        return $file;
    }

    
}
