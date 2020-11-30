<?php

namespace genimage;

class filter_catalog
{

    public static function list()
    {
        $catalog = null;

        $files = scandir(__DIR__ . '/filter_types');

        foreach ($files as $file){

            if ($file == '.' || $file == '..'){ continue; }

            $name = str_replace('.php', '', $file);
            $classname = '\\genimage\\filters\\'.$name;

            $instance = new $classname;

            $catalog["${name}"]['genimage_library_filter_name'] = $instance->filtername;
            $catalog["${name}"]['genimage_library_filter_description'] = $instance->filterdesc;
            $catalog["${name}"]['genimage_library_filter_example'] = $instance->example;
            $catalog["${name}"]['genimage_library_filter_example_output'] = $instance->output;

        }

        return $catalog;
    }

}