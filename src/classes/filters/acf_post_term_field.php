<?php

namespace genimage\filters;

use genimage\utils\replace as replace;

/**
 * Used only on the single post & WP_Query sources.
 * NOT the taxonomy source.
 * 
 * Get the article TERM, not category.
 */
class acf_post_term_field {

    public $params;

    public $post;

    public function __construct($params, $post){
        $this->params = unserialize($params);
        $this->post = $post;
        return $this;
    }
    

    public function output(){
        if (empty($this->params) || empty($this->post)){ return; }

        $term = get_the_terms($this->post, 'articletags');

        $output = replace::switch($this->params, $this->post);
        $output = replace::switch_acf($output, $term[0]);

        return $output;
    }

    public function defs(){
        return;
    }

}