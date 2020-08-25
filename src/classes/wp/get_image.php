<?php

namespace genimage\wp;

class get_image {

    public $image;

    public $item;


    public function get_image_url($item){

        $this->item = $item;

        $type = get_class($item);
        $this->$type();

        return $this->image;
    }

    public function WP_Post(){
        $thumnail_id = get_post_thumbnail_id( $this->item->ID );
        $image_array = wp_get_attachment_image_src($thumnail_id , 'full' );
        $domain = get_site_url();
        // Rewrite the absolute to relative path.
        $image_array[0] = str_replace( $domain, '', '../../../..'.$image_array[0] );
        unset($image_array[3]);

        $this->image = $image_array;

        return;
    }

    public function WP_Term(){
        $image = get_field("article_category_image", 'term_'.$this->item->term_id);
        $domain = get_site_url();
        $image['url'] = str_replace( $domain, '', $image['url'] );

        $this->image[0] = '../../../..'. $image['url'];
        $this->image[1] = $image['width'];
        $this->image[2] = $image['height'];

        return;
    }

}