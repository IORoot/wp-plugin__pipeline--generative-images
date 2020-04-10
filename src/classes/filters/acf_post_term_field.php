<?php

namespace genimage\filters;

use genimage\utils\replace_terms as replace;

/**
 * Used only on the single post & WP_Query sources.
 * NOT the taxonomy source.
 * 
 * Get the article TERM, not category.
 */
class acf_post_term_field {

    public $params;

    public $post;

    public $terms;

    public function __construct($params, $post){
        $this->params = unserialize($params);
        $this->post = $post;
        return $this;
    }
    

    public function output(){
        if (empty($this->post)){ return; }

        $this->terms = get_the_terms($this->post, 'articletags');
        $this->view_term_last();
        $output = $this->slowmo_last();


        $sub = new replace($this->terms, $this->params);
        
        $output = $sub->terms_to_term();

        return $output;
    }



    public function defs(){
        return;
    }


    public function view_term_last(){

        $slug = $this->terms[0]->slug;

        if (strpos($slug, 'view') !== false) {
            $this->terms = array_reverse($this->terms);
        }

        return;

    }

    public function slowmo_last(){

        foreach ($this->terms as $key => $term){

            if ($term->slug == "slowmotion"){
                $this->terms[] = $term;     // add to end
                unset($this->terms[$key]); // delete current.
                $this->terms = array_values($this->terms); // rekey 0,1,2
            }

        }

        return;

    }


}