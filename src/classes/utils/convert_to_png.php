<?php

namespace genimage\utils;

use Imagick;

class convert_to_png {

    // The SVG data
    public $svg_data;

    // The source image relative filepath
    public $source_image;

    // path to upload dir.
    public $upload_dir = 'wp-content/uploads/';

    // Latest WP upload directory.
    public $subdir;

    // filename of the temporary SVG file.
    public $tmp_file = '/1_temporary_genimage.svg';

    // Upload Path + Filename
    public $svg_file;

    // Name of output png file
    public $png_file = '/1_output_png.png';

    // Upload dir + output png file
    public $png_dir;

    // Name of output jpg file
    public $jpg_file = '/1_output_jpg.jpg';

    // Upload dir + output jpg file
    public $jpg_dir;

    // JPG Quality 
    public $jpg_quality = 100;
    
    



    public function __construct($svg_data, $source_image){

        if ($svg_data == null || $source_image == null){ return; }
        $this->svg_data = $svg_data;
        $this->source_image = $source_image;



        // Set upload directory.
        $this->subdir = $this->upload_dir . wp_upload_dir()['subdir'];
        // Set tmp SVG file
        $this->svg_file = $this->subdir .  $this->tmp_file;
        // Set output png
        $this->png_dir = $this->subdir . $this->png_file;
        // Set output jpg
        $this->source_image = str_replace('.jpg', '_gi.jpg', $this->source_image);
        $this->source_image = str_replace('.png', '_gi.png', $this->source_image);

        $this->jpg_dir = $this->source_image;


        
        $this->create_SVG_file();

        $this->convert_to_png();

        $this->png_to_jpg();
        
        return $this;
    }



    public function create_SVG_file(){

        file_put_contents($this->svg_file, $this->svg_data);

        return;
    }




    public function convert_to_png(){

        exec('inkscape -z '. $this->svg_file.' -e '.$this->png_dir, $output, $return);

        if($result > 0)
        {
            die('Inkscape did not execute correctly. $result = '.$result. ' | $output = '. $output);
        }   

        return;
    }



    public function png_to_jpg(){

        try {
            $im = new Imagick($this->png_dir);
        } catch (ImagickException $e) {
            $im = null;
        }

        if ($im){
            $im->setImageCompressionQuality($this->jpg_quality);
            $im->setImageFormat("jpg");
            $im->writeImage($this->jpg_dir);
            $im->clear();
            $im->destroy();
        }
        
        return;
    }

}