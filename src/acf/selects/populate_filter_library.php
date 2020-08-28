<?php

function genimage_library($value)
{
    $value = [];

    $filters = new \genimage\filter_catalog;
    $filter_list = $filters->list();

    foreach($filter_list as $item)
    {
        $entry = [
            "field_5f4778c73c940" => $item['genimage_library_filter_name'],
            "field_5f4778ec3c941" => $item['genimage_library_filter_description'],
            "field_5f4779123c942" => $item['genimage_library_filter_example'],
            "field_5f479f850bcc5" => $item['genimage_library_filter_example_output'],
        ];
    
        $value[] = $entry;
    }

    return $value;

}

add_filter('acf/load_value/name=genimage_library', 'genimage_library');