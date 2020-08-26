<?php

namespace genimage;

class render {

    use wp_funcs;

    /**
     * converted variable
     *
     * Files that have been converted.
     * 
     * @var array
     */
    private $converted;
    private $converted_key;
    private $converted_files;

    private $filename_file;
    private $image_file;

    /**
     * Array of Original SVG code for each image.
     * 
     * 0 => '<svg ...>'
     * 1 => '<svg ...>'
     * 2 => '<svg ...>'
     *
     * @var [type]
     */
    private $svg_group;

    /**
     * column_percent variable
     *
     * Percentage width of each column.
     * 
     * @var int
     */
    private $column_percent;


    public function set_converted($converted)
    {
        $this->converted = $converted;
    }


    public function set_svg_group($svg_group)
    {
        $this->svg_group = $svg_group;
    }



    public function run()
    {
        $this->count_columns();
        $this->build_table();

    }

    private function count_columns()
    {
        $this->column_percent = 100 / (count($this->converted[0]) + 1);
    }



    private function build_table()
    {
        ob_start();

        $output = $this->open_table();

        foreach ($this->converted as $this->converted_key => $this->converted_files)
        {
            $output .= $this->rows();
        }

        $output .= $this->close_table();

        echo $output;

        return ob_end_flush();
    }



    private function open_table()
    {
        return '<table>';
    }

    private function close_table()
    {
        return '</table>';
    }
    
    private function open_row()
    {
        return '<tr>';
    }

    private function close_row()
    {
        return '</tr>';
    }
    
    private function open_column()
    {
        return '<td style="width:'.$this->column_percent.'%;">';
    }
    private function close_column()
    {
        return '</td>';
    }



    private function rows()
    {
        $output = $this->open_row();
            $output .= $this->svgdata_filename_column();
            $output .= $this->filename_columns();
        $output .= $this->close_row();

        $output .= $this->open_row();
            $output .= $this->svgdata_image_column();
            $output .= $this->image_columns();
        $output .= $this->close_row();

        return $output;
    }

    private function svgdata_filename_column()
    {
        $output = $this->open_column();
            $output .= 'SVG DATA';
        $output .= $this->close_column();

        return $output;
    }



    private function filename_columns()
    {
        $output = '';

        foreach ($this->converted_files as $filename_key => $this->filename_file)
        {
            $extension = pathinfo($this->filename_file,PATHINFO_EXTENSION);
            $link = $this::wp_site_url() . '/' . $this->filename_file;
            $filesize = filesize($this->filename_file);
            $filesize = (new \genimage\utils\utils)->niceImageSizes($filesize);

            $output .= $this->open_column();
                $output .= '<a href="'.$link.'" target="_blank">';
                    $output .= $extension . ' - ' . $filesize;
                $output .= '</a>';
            $output .= $this->close_column();
        }

        return $output;
    }


    private function svgdata_image_column()
    {
        $output = $this->open_column();
            $output .= $this->svg_group[$this->converted_key];
        $output .= $this->close_column();

        return $output;
    }



    private function image_columns()
    {
        $output = '';

        foreach ($this->converted_files as $image_key => $this->image_file)
        {
            $output .= $this->open_column();
                $output .= $this->render_image();
            $output .= $this->close_column();
        }
        return $output;
    }


    private function render_image()
    {

        $this->link = $this::wp_site_url() . '/' . $this->image_file;

        $output = '<a href="'.$this->link.'" target="_blank">';

        $output .= '<embed src="';

            $output .= $this->link;
        
        $output .= '" />';

        $output .= '</a>';
        

        return $output;
    }

}