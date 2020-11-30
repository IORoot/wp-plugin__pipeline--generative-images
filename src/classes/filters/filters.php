<?php

namespace genimage;

class filters
{

    private $filter_slug;

    private $filters;


    public function set_filter_slug($filter_slug)
    {
        $this->filter_slug = $filter_slug;
    }

    public function run()
    {
        $this->get_filter_group_by_slug();
    }

    public function get_filters()
    {
        return $this->filters;
    }


    private function get_filter_group_by_slug()
    {
        $this->filters = (new options)->get_filter_group($this->filter_slug);
    }

}