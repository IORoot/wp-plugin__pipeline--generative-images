<?php

namespace genimage\utils;

class replace
{

    public $UC;
    public $HY;
    public $W1;
    public $W2;
    public $W3;
    public $W4;

    public $string_to_manipulate;
    public $post_object;

    /**
     * substitute any %post_title% type matches with their WP_Post
     * real values.
     */
    public function sub($string, $post_object)
    {
        $this->string_to_manipulate = $string;
        $this->post_object = $post_object;

        // Search for any matches or words,digits or :
        preg_match_all("/{{([\w|\d|_|:]+)}}/", $string, $matches);
        
        // For each result, iterate over them.
        foreach ($matches[1] as $key => $match) {

            // toggle is matched.
            $this->UC = $this->check_text('uc:', $match);
            $this->HY = $this->check_text('hy:', $match);
            $this->W1 = $this->check_text('w1:', $match);
            $this->W2 = $this->check_text('w2:', $match);
            $this->W3 = $this->check_text('w3:', $match);
            $this->W4 = $this->check_text('w4:', $match);

            // remove manipulations
            $string = $this->remove_from_string(['uc:', 'hy:', 'w1:', 'w2:', 'w3:', 'w4:'], $string);
            $match = $this->remove_from_string(['uc:', 'hy:', 'w1:', 'w2:', 'w3:', 'w4:'], $match);

            // If there is a match in the string, manipulate it.
            if (property_exists($post_object, $match)) {

                $field = $post_object->$match;

                if ($this->UC == true){ $field = $this->turn_to_uppercase($field); }
                if ($this->HY == true){  $field = $this->remove_before_hypen($field); }
                if ($this->W1 == true){  $field = $this->word($field, 0); }
                if ($this->W2 == true){  $field = $this->word($field, 1); }
                if ($this->W3 == true){  $field = $this->word($field, 2); }
                if ($this->W4 == true){  $field = $this->word($field, 3); }

                $string = str_replace('{{'.$match.'}}', $field, $string);
            }

            
        }

        return $string;
    }


    public function check_text($needle, $haystack){
        if (strpos($haystack, $needle) !== false ){
            return true;
        }
        return false;
    }


    public function remove_from_string($remove_these, $from_this){

            foreach ($remove_these as $remove_this){
                $string = str_replace($remove_these,'',$from_this);
            }
            
            return $string;
    }


    public function turn_to_uppercase($field){
        return strtoupper($field);
    }


    public function remove_before_hypen($field){
        return preg_replace('/.* - /', '', $field );
    }


    public function word($field, $number){

        preg_match_all("/([\w|\d]*)/i", $field, $matches);

        $output = '';
        foreach($matches[0] as $match){
            if ($match != ""){
                $output[] = $match;
            }
        }

        return $output[$number];
    }






















    /**
     * substitute any %post_title% type matches with their WP_Post
     * real values.
     */
    public static function switch($string, $post_object)
    {
        preg_match_all("/{{([\w|_|:]+)}}/", $string, $matches);
        
        foreach ($matches[1] as $key => $match) {


            // Check for UC: (uppercase)
            if (strpos($match, 'uc:') !== false ){
                $UC = true;
                $string = str_replace('uc:','',$string);
                $match = str_replace('uc:','',$match);
            }


            // Check for HY: (hypen becomes a line-break)
            if (strpos($match, 'hy:') !== false ){
                $HY = true;
                $string = str_replace('hy:','',$string);
                $match = str_replace('hy:','',$match);
            }


            if (property_exists($post_object, $match)) {

                $field = $post_object->$match;

                if ($UC == true){ $field = strtoupper($field); }

                if ($HY == true){  $field = preg_replace('/.* - /', '', $field ); }

                $string = str_replace('{{'.$match.'}}', $field, $string);
            }

            
        }

        return $string;
    }



    /**
     * Look for any acf fields in the taxonomy associated with the post.
     */
    public static function switch_acf($string, $post_object)
    {
        // get a post's ACF fields.
        $acf = get_fields($post_object->ID);

        // get the ACF fields of the term linked to a post.
        $terms = wp_get_post_terms($post_object->ID, 'articlecategory');
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



    /**
     * Look for any ACF fields in the taxonomy 'term' and replace
     */
    public static function switch_term_acf($string, $post_object)
    {
        $acf = get_fields('term_'.$post_object->term_id);
        
        preg_match_all("/{{([\w|_]+)}}/", $string, $matches);

        foreach ($matches[1] as $key => $match) {
            $string = str_replace('{{'.$match.'}}', $acf[$match], $string);
        }

        return $string;
    }
}
