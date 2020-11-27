<?php

namespace genimage\utils;

trait switch_for_tax_acf_term_field
{

    /**
     * Look for any ACF fields in the taxonomy 'term' and replace
     */
    public static function switch_term_acf($string, $post_object)
    {
        if (!is_object($post_object)) {
            return $string;
        }
        if (!property_exists($post_object, 'term_id')) {
            return $string;
        }

        $acf = get_fields('term_'.$post_object->term_id);
        
        preg_match_all("/{{([\w|_]+)}}/", $string, $matches);

        foreach ($matches[1] as $key => $match) {
            $string = str_replace('{{'.$match.'}}', $acf[$match], $string);
        }

        return $string;
    }
}
