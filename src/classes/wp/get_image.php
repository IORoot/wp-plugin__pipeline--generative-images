<?php

namespace genimage\wp;

class get_image {

    public $image;

    public $item;


    public function get_image_url($item){

        $this->item = $item;

         // What was returned?
        $type = get_class($item);
        $this->$type();

        return $this->image;
    }

    public function WP_Post(){
        $this->image = wp_get_attachment_image_src( get_post_thumbnail_id( $this->item->ID ), 'full' );
    }

    public function WP_Term(){
        $image = get_field("article_category_image", 'term_'.$this->item->term_id);
        $this->image[0] = $image['url'];
        $this->image[1] = $image['width'];
        $this->image[2] = $image['height'];
    }

}