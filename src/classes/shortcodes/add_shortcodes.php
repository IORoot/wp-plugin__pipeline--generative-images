<?php

namespace genimage\shortcodes;

use genimage\utils\convert_to_png as convert;

class add_shortcodes {

    public $svg;
    public $png;
    public $jpg;

    public $source_file;

    public function __construct(){
        add_shortcode( 'andyp_gen_image', array($this, 'generative_image') );

        return;
    }



    public function generative_image($atts){

        $a = shortcode_atts( 
            array(
                'slug' => '',
            ), $atts );

        // Create new object.
        $genimage = new article_image;

        // set returned results.
        $this->svg = $genimage->render();

        $this->source_file = $genimage->get_source_file();
        $this->source_files = $genimage->get_source_files();

        $this->render_table();

        return;
    }




    public function render_table(){
        $output = '<table>';
            $output .= '<thead><tr><td>SVG</td><td>JPG</td></tr></thead>';
            $output .= '<tr>';
                $output .= '<td style="width:50%;">';
                    $output .= $this->render_svg(); 
                    $this->render_png();
                $output .= '</td>';

                $output .= '<td style="width:50%;">';
                    $output .= $this->render_jpg();
                $output .= '</td>';
            $output .= '</tr>';
        $output .= '</table>';

        echo $output;
        return;
    }


    public function render_svg(){
        foreach ($this->svg as $svg){
            $combined_svg .= $svg;
        }
        return $combined_svg;
        // return;
    }

    public function render_textarea(){
        
        $ta = '<textarea rows="5" style="width:100%;">';
            $ta .= '<?xml version="1.0" encoding="UTF-8"?>';
            $ta .= htmlspecialchars($this->svg);
        $ta .= '</textarea>';

        return $ta;
    }


    public function render_png(){
        $i = 0;
        foreach($this->svg as $svgfile){
            $svg = '<?xml version="1.0" encoding="UTF-8"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'.$this->svgfile;
            $this->png = new convert($svgfile,  $this->source_files[$i]);
            $i++;
        }
        return;
    }



    
    public function render_jpg(){

        $output = '';

        foreach($this->source_files as $file){
            // substitute;
            $this->jpg = str_replace('.png', '_gi.png', $file);
            $this->jpg = str_replace('.jpg', '_gi.jpg', $file);

            $output .= '<img src="/';
                $output .= $this->jpg;
            $output .= '"/>';

        }

        return $output;
    }

    

}
