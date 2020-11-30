<?php

namespace genimage\filters;

use genimage\utils\replace as replace;
use genimage\interfaces\filterInterface;

class text implements filterInterface
{
    public $filtername =    'text';
    public $filterdesc =    'Text that can use moustache {{post_title}}'.PHP_EOL.
                            'tags or any other WP_Post / WP_Term item.'.PHP_EOL.PHP_EOL.
                            'Also have additional PREFIXES to modify the text.'.PHP_EOL.
                            'uc: prefix for Upper Case text'.PHP_EOL.
                            'hy: Removes anything BEFORE a " - " hypen. "HELLO - THERE" will become "THERE"'.PHP_EOL.
                            'w1: Matches the first word of the match."'.PHP_EOL.
                            'w2: Matches the second word of the match."'.PHP_EOL.
                            'w3: Matches the third word of the match."'.PHP_EOL.
                            'w4: Matches the fourth word of the match."';

    public $example    =    '<text filter="url(#solidTextBG)" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" style="fill:#fafafa;" >{{uc:post_title}}</text>'.PHP_EOL.
                            '<text filter="url(#solidTextBG)" style="fill:#fafafa;" >{{uc:w1:w3:post_title}}</text>';
    
    public $output     =    '<text filter="url(#solidTextBG)" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" style="letter-spacing:-5px; font-size: 87px; fill:#fafafa;" >BEST - POST - EVER</text>'.PHP_EOL.
                            '<text filter="url(#solidTextBG)" style="fill:#fafafa;" >BEST EVER</text>';

    
    public $params;

    public $image;

    public function set_params($params)
    {
        if (is_serialized($params)){
            $this->params = unserialize($params);
            return;
        }
        $this->params = $params;
    }
    
    public function set_image($image)
    {
        $this->image = $image;
    }

    public function set_all_images($images)
    {
        return;
    }
        
    public function set_source_object($source_object)
    {
        $this->source_object = $source_object;
    }

    public function run()
    {
        return $this;
    }
    
    public function output()
    {
        if (empty($this->params) || empty($this->image) || empty($this->source_object)) {
            return;
        }

        $replace = new replace;
        $output = $replace->sub($this->params, $this->source_object);
        $output = replace::switch_acf($output, $this->source_object);
        $output = replace::switch_dates($output);

        return $output;
    }

    public function defs()
    {
        return;
    }
}
