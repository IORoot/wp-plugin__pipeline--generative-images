<?php

namespace genimage;

trait wp_funcs
{
    public static function wp_upload_dir()
    {
        return 'wp-content/uploads' . wp_upload_dir()['subdir'];
    }


    public static function wp_site_url()
    {
        return site_url();
    }
}