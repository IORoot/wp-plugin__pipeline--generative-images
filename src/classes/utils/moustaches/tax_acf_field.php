<?php

namespace genimage\utils;

trait switch_for_tax_acf_field
{
    /**
     * 1. Use the Post Object's -> ID to get the WP_TERM
     * 2. Using the WP_Term, find any ACF fields in it.
     * 3. Substitute any moustache brackets for those fields.
     */
    public static function switch_acf($string, $post_object)
    {
        if (!is_object($post_object)){
            return $string;
        }

        // get a post's ACF fields.
        $acf = get_fields($post_object->ID);

        // get the ACF fields of the term linked to a post.
        $terms = wp_get_post_terms($post_object->ID, 'articlecategory');

        if (is_wp_error($terms)){ return $string; }

        $acf = get_fields('term_'.$terms[0]->term_id);

        // get a terms ACF fields
        if (get_class($post_object) == 'WP_Term') {
            $acf = get_fields('term_'.$post_object->term_id);
        }
        
        preg_match_all("/{{([\w|_]+)}}/", $string, $matches);

        foreach ($matches[1] as $key => $match) {
            $string = str_replace('{{'.$match.'}}', $acf[$match], $string);
        }

        return $string;
    }

}