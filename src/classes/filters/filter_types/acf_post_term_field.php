<?php

namespace genimage\filters;

use genimage\utils\replace_terms as replace;
use genimage\interfaces\filterInterface;

/**
 * Used only on the single post & WP_Query sources.
 * NOT the taxonomy source.
 * 
 * Get the article TERM, not category.
 */
class acf_post_term_field implements filterInterface
{

    public $filtername =    'acf_post_term_field';
    public $filterdesc =    'Uses "articletags" Taxonomy.'.PHP_EOL.PHP_EOL.
                            '1. This gets any "articletags" terms for the specific Post.'.PHP_EOL.
                            '2. Gets the "View" type of the article'.PHP_EOL.
                            '3. Adds the SLOWMO tag on if it exists.'.PHP_EOL.
                            '4. Creates a list of each tag name to print out.'.PHP_EOL.PHP_EOL.
                            'Example: Use {{name[0]}} or {{name[1]}} tags.';

    public $example    =    '<text id="cameraview" x="50%" y="59%" dominant-baseline="middle" text-anchor="middle" style="font-size: 29px; fill:#fafafa;" >{{name[1]}}</text>';
    public $output     =    '<text id="cameraview" x="50%" y="59%" dominant-baseline="middle" text-anchor="middle" style="font-size: 29px; fill:#fafafa;" >Front View</text>';
    
    public $params;

    public $image;

    public $terms;

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
        $this->images = $images;
    }

    public function set_source_object($source_object)
    {
        $this->source_object = $source_object;
    }

    public function run()
    {
        return $this;
    }
    

    public function output(){
        if (empty($this->image)){ return; }

        $this->terms = get_the_terms($this->image, 'articletags');
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