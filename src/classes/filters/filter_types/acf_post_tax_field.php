<?php

namespace genimage\filters;

use genimage\utils\replace as replace;

/**
 * Used only on the single post & WP_Query sources.
 * NOT the taxonomy source.
 */
class acf_post_tax_field {

    public $params;

    public $post;

    public function __construct($params, $post){
        $this->params = unserialize($params);
        $this->post = $post;
        return $this;
    }
    

    public function output(){
        if (empty($this->params) || empty($this->post)){ return; }

        $taxonomy = get_the_terms($this->post, 'articlecategory');

        $output = replace::switch($this->params, $this->post);
        $output = replace::switch_acf($output, $taxonomy[0]);

        return $output;
    }

    public function defs(){
        return;
    }

}