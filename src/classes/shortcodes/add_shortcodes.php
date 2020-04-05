<?php

namespace genimage\shortcodes;

use genimage\exporter\convert_to_file as convert;
use genimage\wp\set_image;

class add_shortcodes
{
    public $svg;
    public $png;
    public $jpg;

    public $suffix = '_gi';

    public $source_files;
    public $source_posts;

    public $save_options;

    public function __construct()
    {
        // ┌─────────────────────────────────────────────────────────────────────────┐
        // │   Shortcode runs on page load, and the page is loaded TWICE. This will  │
        // │                    stop the code from running twice.                    │
        // └─────────────────────────────────────────────────────────────────────────┘
        if ($_SERVER['HTTP_ACCEPT'] == "*/*") {
            return;
        }

        add_shortcode('andyp_gen_image', array($this, 'generative_image'));
        return;
    }



    public function generative_image($atts)
    {
        $a = shortcode_atts(
            array(
                'slug' => '',
            ),
            $atts
        );

        $genimage = new article_image;

        $this->svg = $genimage->render();

        if ($this->svg == null) {
            return "No Source File.";
        }

        $this->source_files = $genimage->get_source_files();

        $this->source_posts = $genimage->get_source_posts();

        $this->save_options = $genimage->get_save_values();

        $this->convert_files();

        $this->render_table();

        $this->save_featured_image();

        $this->switch_off_file_write();
        
        return;
    }


    public function switch_off_file_write()
    {
        update_field('gi_save_post', 'none', 'option');
    }


    public function render_table()
    {
        ob_start();

        $svg_data = $this->combine_svgs();

        $output = '<table>';
        $output .= '<thead style="background-color:#fafafa;"><tr><td>SVG Data</td><td>SVG file</td><td>JPG file</td><td>PNG file</td></tr></thead>';
        $output .= '<tr>';
        $output .= '<td style="width:25%;">';
        $output .= $svg_data;
        $output .= '</td>';

        
        $output .= '<td style="width:25%;">';
        if ($this->save_options['svg']) {
            $output .= $this->render_svg();
        }
        $output .= '</td>';
        
        
        $output .= '<td style="width:25%;">';
        if ($this->save_options['jpg']) {
            $output .= $this->render_jpg();
        }
        $output .= '</td>';
        

        
        $output .= '<td style="width:25%;">';
        if ($this->save_options['png']) {
            $output .= $this->render_png();
        }
        $output .= '</td>';
        


        $output .= '</tr>';
        $output .= '</table>';

        echo $output;
        return ob_end_flush();
    }




    public function combine_svgs()
    {
        foreach ($this->svg as $svg) {
            $combined_svg .= '<p>Data</p>' . $svg;
        }
        return $combined_svg;
    }




    public function render_textarea()
    {
        $ta = '<textarea rows="5" style="width:100%;">';
        $ta .= '<?xml version="1.0" encoding="UTF-8"?>';
        $ta .= htmlspecialchars($this->svg);
        $ta .= '</textarea>';

        return $ta;
    }



    public function convert_files()
    {
        $i = 0;
        foreach ($this->svg as $svgfile) {

            // make absolute path to relative.
            $filename = str_replace(get_site_url().'/', '', $this->source_files[$i]);
            $this->convert = new convert($svgfile, $filename, $this->save_options);
            $i++;
        }
        return;
    }





    public function render_jpg()
    {
        $output = '';

        foreach ($this->source_files as $file) {
            $this->jpg = $this->rename_file($file, 'jpg');

            $output .= '<a href="'.$this->jpg.'" target="_blank">Open File';
            $output .= '<img class="pushin" src="';
            $output .= $this->jpg;
            $output .= '" />';
            $output .= '</a>';
        }

        return $output;
    }


    public function render_png()
    {
        $output = '';

        foreach ($this->source_files as $file) {
            $this->png = $this->rename_file($file, 'png');

            $output .= '<a href="'.$this->png.'" target="_blank">Open File';
            $output .= '<img class="pushin" src="';
            $output .= $this->png;
            $output .= '" />';
            $output .= '</a>';
        }

        return $output;
    }


    public function render_svg()
    {
        $output = '';

        foreach ($this->source_files as $file) {

            // substitute source filename.png for a jpeg filename_suffix.jpg
            $this->svg = $this->rename_file($file, 'svg');

            $output .= '<a href="'.$this->svg.'" target="_blank">Open File';
            $output .= '<embed class="pushin" src="';
            $output .= $this->svg;
            $output .= '" />';
            $output .= '</a>';
        }

        return $output;
    }




    public function save_featured_image()
    {
        $save_type = $this->save_options['post'];
        if ($save_type == 'none') {
            return;
        }

        $i = 0;
        foreach ($this->source_files as $file) {
            $file = $this->rename_file($file, $save_type);

            $wp = new set_image;
            $wp->set_filename($file);

            $source_type = get_class($this->source_posts[$i]);

            // Term
            if ($source_type == 'WP_Term') {
                $wp->set_id($this->source_posts[$i]->term_id);
                $wp->update_term_thumbnail();

            // Post / Query
            } else {
                $wp->set_id($this->source_posts[$i]->ID);
                $wp->update_post_thumbnail();
            }

            $i++;
        }
        
        return;
    }


    public function rename_file($file, $format)
    {

        // remove the suffix
        $file = str_replace($this->suffix.'.png', '.png', $file);
        $file = str_replace($this->suffix.'.jpg', '.jpg', $file);

        // Reset the format
        $file = str_replace('.png', $this->suffix.'.'.$format, $file);
        $file = str_replace('.jpg', $this->suffix.'.'.$format, $file);

        return $file;
    }
}
