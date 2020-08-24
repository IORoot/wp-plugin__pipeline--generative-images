<?php

namespace genimage;

class generator
{

    public $instances;
    public $instance_key;
    public $instance_config;
    public $current_instance;

    public function __construct()
    {
        $this->run();
    }


    public function run()
    {
        $this->get_control_options();
        $this->iterate_over_all_instances();
    }



    private function get_control_options()
    {
        $this->instances = (new options)->get_instances();
    }



    private function iterate_over_all_instances()
    {
        foreach ($this->instances as $this->instance_key => $this->instance_config)
        {
            $this->process_single_instance();
        }
        return;
    }



    private function process_single_instance()
    {
        $this->current_instance = new instance;
        $this->current_instance->set_config($this->instance_config);
        $this->current_instance->run();

    }




}