<?php

namespace genimage\filters;

use genimage\interfaces\filterInterface;

class svg_definition implements filterInterface
{
    public $filtername =    'svg_definition';
    public $filterdesc =    'Add a new definition into the SVG <defs> section of the object.';
    public $example    =    '<filter  id="solidTextBG" x="-0.1" y="-0.75" width="1.2" height="3.5">'.PHP_EOL.
                            '   <feFlood flood-color="#242424" flood-opacity="0.5"></feFlood>'.PHP_EOL.
                            '   <feComposite in="SourceGraphic"></feComposite>'.PHP_EOL.
                            '</filter>';
    public $output     =    '<defs>'.PHP_EOL.
                            '   <filter  id="solidTextBG" x="-0.1" y="-0.75" width="1.2" height="3.5">'.PHP_EOL.
                            '       <feFlood flood-color="#242424" flood-opacity="0.5"></feFlood>'.PHP_EOL.
                            '       <feComposite in="SourceGraphic"></feComposite>'.PHP_EOL.
                            '   </filter>'.PHP_EOL.
                            '</defs>';
    
    public $params;

    public function set_params($params)
    {
        $this->params = unserialize($params);
    }

    public function set_image($image)
    {
        return;
    }

    public function set_all_images($images)
    {
        return;
    }
        
    public function set_source_object($source_object)
    {
        return;
    }

    public function run()
    {
        return $this;
    }

    public function output(){
        return;
    }

    public function defs(){
        return $this->params;
    }

}