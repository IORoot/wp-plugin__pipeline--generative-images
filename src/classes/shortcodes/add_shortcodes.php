<?php

namespace genimage\shortcodes;

use genimage\exporter\convert_to_file as convert;

class add_shortcodes
{
    public $svg;
    public $png;
    public $jpg;

    public $suffix = '_gi';

    public $source_files ;

    public $save_options;

    public function __construct()
    {
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

        // Create new object.
        $genimage = new article_image;

        // set returned results.
        $this->svg = $genimage->render();

        // $this->source_file = $genimage->get_source_file();
        $this->source_files = $genimage->get_source_files();

        // Get which save options have been
        $this->save_options = $genimage->get_save_values();

        // Do the conversions
        $this->convert_files();

        $this->render_table();

        return;
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
        $output .= $this->render_svg();
        $output .= '</td>';

        $output .= '<td style="width:25%;">';
        $output .= $this->render_jpg();
        $output .= '</td>';

        $output .= '<td style="width:25%;">';
        $output .= $this->render_png();
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

            // substitute source filename.png for a jpeg filename_suffix.jpg
            $this->jpg = str_replace('.png', $this->suffix.'.jpg', $file);
            $this->jpg = str_replace('.jpg', $this->suffix.'.jpg', $file);

            $output .= '<a href="'.$this->jpg.'" target="_blank">Open File</a>';
            $output .= '<img src="';
            $output .= $this->jpg;
            $output .= '" />';
            
        }

        return $output;
    }


    public function render_png()
    {
        $output = '';

        foreach ($this->source_files as $file) {

            // substitute source filename.png for a jpeg filename_suffix.jpg
            $this->png = str_replace('.png', $this->suffix.'.png', $file);
            $this->png = str_replace('.jpg', $this->suffix.'.png', $file);

            $output .= '<a href="'.$this->png.'" target="_blank">Open File</a>';
            $output .= '<img src="';
            $output .= $this->png;
            $output .= '" />';
            
        }

        return $output;
    }


    public function render_svg()
    {
        $output = '';

        foreach ($this->source_files as $file) {

            // substitute source filename.png for a jpeg filename_suffix.jpg
            $this->svg = str_replace('.png', $this->suffix.'.svg', $file);
            $this->svg = str_replace('.jpg', $this->suffix.'.svg', $file);

            $output .= '<a href="'.$this->svg.'" target="_blank">Open File</a>';
            $output .= '<embed src="';
            $output .= $this->svg;
            $output .= '" />';
            
        }

        return $output;
    }

}
