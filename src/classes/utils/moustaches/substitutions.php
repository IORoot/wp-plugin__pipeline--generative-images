<?php

namespace genimage\utils;

trait substitutions
{
    /**
     * substitute any %post_title% type matches with their WP_Post
     * real values.
     */
    public function sub($string, $post_object)
    {

        // Search for any matches or words,digits or :
        preg_match_all("/{{([\w|\d|_|:]+)}}/", $string, $matches);
        
        // For each result, iterate over them.
        foreach ($matches[1] as $key => $match) {

            // toggle is matched.
            $UC = $this->check_text('uc:', $match);
            $HY = $this->check_text('hy:', $match);
            $W1 = $this->check_text('w1:', $match);
            $W2 = $this->check_text('w2:', $match);
            $W3 = $this->check_text('w3:', $match);
            $W4 = $this->check_text('w4:', $match);

            // remove manipulations
            $string = $this->remove_from_string(['uc:', 'hy:', 'w1:', 'w2:', 'w3:', 'w4:'], $string);
            $match = $this->remove_from_string(['uc:', 'hy:', 'w1:', 'w2:', 'w3:', 'w4:'], $match);

            // If there is a match in the string, manipulate it.
            if (property_exists($post_object, $match)) {

                $field = $post_object->$match;

                if ($UC == true){ $field = $this->turn_to_uppercase($field); }
                if ($HY == true){  $field = $this->remove_before_hypen($field); }
                if ($W1 == true){  $field = $this->word($field, 0); }
                if ($W2 == true){  $field = $this->word($field, 1); }
                if ($W3 == true){  $field = $this->word($field, 2); }
                if ($W4 == true){  $field = $this->word($field, 3); }

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

}