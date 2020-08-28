<?php

namespace genimage\filters;

use genimage\utils\replace as replace;

class acf_term_field_definition {

    public $filtername =    'acf_term_field_definition';
    public $filterdesc =    'Only available on the \'Category\' source.'. PHP_EOL. PHP_EOL.
                            'Moushache brackets, same as the text layer type.'. PHP_EOL. PHP_EOL.
                            'Allows you to specify an ACF field attached to the taxonomy and output the value to the <defs></defs> section of the SVG.'. PHP_EOL.PHP_EOL.
                            'Example creates coloured logo.';

    public $example    =    '<filter  id="solidTextBG" x="-0.1" y="-0.75" width="1.2" height="4">'. PHP_EOL.
                            '   <feFlood flood-color="{{taxonomy_colour}}" flood-opacity="0.5"></feFlood>'. PHP_EOL.
                            '   <feComposite in="SourceGraphic"></feComposite>'. PHP_EOL.
                            '</filter>';

    public $output     =    '<filter  id="solidTextBG" x="-0.1" y="-0.75" width="1.2" height="4">'. PHP_EOL.
                            '   <feFlood flood-color="#242424" flood-opacity="0.5"></feFlood>'. PHP_EOL.
                            '   <feComposite in="SourceGraphic"></feComposite>'. PHP_EOL.
                            '</filter>';

    public $params;

    public $post;

    public function set_params($params)
    {
        $this->params = unserialize($params);
    }

    public function set_post($post)
    {
        $this->post = $post;
    }

    public function run()
    {
        return $this;
    }
    

    public function output(){
        return;
    }

    public function defs(){
        if (empty($this->params) || empty($this->post)){ return; }

        $output = replace::switch_term_acf($this->params, $this->post);

        return $output;
    }

}