<?php

namespace genimage;

trait wp_funcs
{
    public static function wp_upload_dir()
    {
        $dir = wp_upload_dir();
        return $dir['path'];
    }


    public static function wp_site_url()
    {
        return site_url();
    }
}