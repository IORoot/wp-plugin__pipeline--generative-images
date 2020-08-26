<?php

namespace genimage;

trait debug
{

    public static function debug($message, $title)
    {
        $old_message = get_field('genimage_debug', 'option');

        $message = json_encode($message, JSON_PRETTY_PRINT);
        $message = utf8_encode($message);
        $title   = str_replace('\\', '|', $title);

        $output = $old_message . PHP_EOL . '// ' . $title . ' --- ' . date('r') . PHP_EOL . $message . PHP_EOL;
        update_field('genimage_debug', $output, 'option');
    }

    public static function debug_clear()
    {
        update_field('genimage_debug', '', 'option');
    }

}