<?php

namespace genimage\output;

use genimage\utils\rename_file;

class screen {

    public $svg;
    public $png;
    public $jpg;

    public $save_options;
    public $source_files;

    public $suffix = '_gi';

    

    public function render_table()
    {
        ob_start();

        $width_on = 100 / (count(array_filter($this->save_options)));

        $svg_data = $this->combine_svgs();

        $output = '<table>';
            $output .= $this->table_head($width_on,$svg_data);

        
        if ($this->save_options['svg']) {
            $output .= '<td style="width:'.$width_on.'%;">';
                $output .= $this->render_image('svg');
            $output .= '</td>';
        }
        
        if ($this->save_options['jpg']) {
            $output .= '<td style="width:'.$width_on.'%;">';
                $output .= $this->render_image('jpg');
            $output .= '</td>';
        }
        
        if ($this->save_options['png']) {
            $output .= '<td style="width:'.$width_on.'%;">';
                $output .= $this->render_image('png');
            $output .= '</td>';
        }

        $output .= '</tr>';
        $output .= '</table>';

        echo $output;
        return ob_end_flush();
    }



    public function combine_svgs()
    {
        $combined_svg = '';
        foreach ($this->svg as $svg) {
            $combined_svg .= '<p>Data</p>' . $svg;
        }
        return $combined_svg;
    }



    public function table_head($width_on,$svg_data)
    {
        $output = '<thead style="background-color:#fafafa;"><tr><td>SVG Data</td>';
        if ($this->save_options['svg']) {
            $output .= '<td>SVG file</td>';
        }
        if ($this->save_options['jpg']) {
            $output .= '<td>JPG file</td>';
        }
        if ($this->save_options['png']) {
            $output .= '<td>PNG file</td>';
        }
        $output .= '</thead>';
        $output .= '<tr>';
        $output .= '<td style="width:'.$width_on.'%;">';
        $output .= $svg_data;
        $output .= '</td>';

        return $output;
    }


    public function render_image($image_type)
    {
        $output = '';

        foreach ($this->source_files as $file) {

            $this->$image_type = (new rename_file)->rename_file($file, $image_type);

            $output .= '<a href="'.$this->$image_type.'" target="_blank">Open File';
            $output .= '<embed class="pushin" src="';
            $output .= $this->$image_type;
            $output .= '" />';
            $output .= '</a>';
        }

        return $output;
    }

}