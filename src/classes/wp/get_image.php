<?php

namespace genimage\wp;

class get_image {

    use \genimage\debug;

    public $image;

    public $item;


    public function get_image_url($item){

        $this->item = $item;

        $type = get_class($item);

        /**
         * If the $item is NOT a WP_Post or WP_Term object
         * but instead, is an array representation of a 
         * WP_Post (Which happens when it's passed from the
         * Universal Exporter), then convert it first and
         * then pass it in.
         */
        if($type == false && isset($this->item["ID"]))
        {
            $this->item = get_post($this->item['ID']);
            $type = 'WP_Post';
        }

        $this->$type();

        return $this->image;
    }

    

    public function WP_Post(){
        $thumnail_id = get_post_thumbnail_id( $this->item->ID );

        if ($thumnail_id == 0){ 
            $this::debug('No Thumbnail found for Post: '. $this->item->post_title, static::class); 
            return;
        }

        $image_array = wp_get_attachment_image_src($thumnail_id , 'full' );

        // HTTPS:
        $domain = get_site_url();
        $image_array[0] = str_replace( $domain, '', $image_array[0] );

        // HTTP:
        $domain = str_replace('https:','http:', $domain);
        $image_array[0] = str_replace( $domain, '', $image_array[0] );

        // RELATIVE
        $image_array[0] = '../../../..'.$image_array[0];


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