<?php

namespace genimage;

trait wp_funcs
{
    public static function wp_upload_dir()
    {
        return 'wp-content/uploads' . wp_upload_dir()['subdir'];
    }
}