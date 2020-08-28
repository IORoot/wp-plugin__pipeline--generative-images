<?php

namespace genimage\filters;

use genimage\utils\replace as replace;

class text 
{
    public $filtername =    'text';
    public $filterdesc =    'Text that can use moustache {{post_title}}'.PHP_EOL.
                            'tags or any other WP_Post / WP_Term item.'.PHP_EOL.PHP_EOL.
                            'Also have additional PREFIXES to modify the text.'.PHP_EOL.
                            'us: prefix for Upper Case text'.PHP_EOL.
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

    public $post;

    public function set_params($params)
    {
        $this->params = $params;
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
        if (empty($this->params) || empty($this->post)){ return; }

        $replace = new replace;
        $output = $replace->sub($this->params, $this->post);
        $output = replace::switch_acf($output, $this->post);

        return $output;
    }

    public function defs(){
        return;
    }

}