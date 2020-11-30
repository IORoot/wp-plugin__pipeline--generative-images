<?php

namespace genimage\filters;

use genimage\utils\replace as replace;
use genimage\interfaces\filterInterface;
/**
 * Used only on the single post & WP_Query sources.
 * NOT the taxonomy source.
 */
class acf_post_tax_field implements filterInterface
{

    public $filtername =    'acf_post_tax_field';
    public $filterdesc =    'Uses "articlecategory" Taxonomy.'.PHP_EOL.PHP_EOL.
                            '1. This gets any "articlecategory" terms for the specific Post.'.PHP_EOL.
                            '2. Switch any {{post_fields}} for the post value'.PHP_EOL.
                            '3. Switch any {{taxonomy_fields}} for the taxonomy value'.PHP_EOL.PHP_EOL.
                            'Example: Use {{taxonomy_colour}} or {{uc:post_title}} tags.'.PHP_EOL.PHP_EOL.
                            'Can also use HY: and UC: on the tags.'.PHP_EOL.
                            'HY: hypen replacement to newlines.'.PHP_EOL.
                            'UC: UPPERCASE.'.PHP_EOL.PHP_EOL;

    public $example    =    '<text id="taxcolour" style="font-size: 58px; color:{{taxonomy_colour}};" >'.PHP_EOL.'{{uc:post_title}}'.PHP_EOL.'</text>';
    public $output     =    '<text id="taxcolour" style="font-size: 58px; color:#FF0000;" >BLOG POST 4</text>';

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
        if (empty($this->params) || empty($this->image)){ return; }

        // Get any taxonomy terms for post
        $taxonomy = get_the_terms($this->image, 'articlecategory');

        // Switch the {{moustaches}} for the post value.
        $output = replace::switch($this->params, $this->image);

        // Switch the {{moustaches}} for the taxonomy value.
        $output = replace::switch_acf($output, $taxonomy[0]);

        return $output;
    }

    public function defs(){
        return;
    }

}