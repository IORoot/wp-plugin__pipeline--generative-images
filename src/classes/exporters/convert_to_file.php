<?php

namespace genimage\exporter;

use Imagick;

class convert_to_file
{

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                               Source Data                               │
    // └─────────────────────────────────────────────────────────────────────────┘

    // The SVG data
    public $svg_data;

    // The source image relative filepath
    // wp-content/uploads/2020/03/original_file.jpg
    public $source_image;

    // JPG Quality to save as
    public $jpg_quality = 100;

    // Save Options
    public $save_options;

    // Upload directory of sourcefile
    public $upload_dir;

    public $file_suffix = '_gi';

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                SVG Files                                │
    // └─────────────────────────────────────────────────────────────────────────┘

    // filename of the temporary SVG file if we're not saving to SVG.
    public $tmp_svg_filename = '/1_temporary_genimage.svg';

    // Absolute_Path + Filename.svg
    public $svg_file;

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                PNG Files                                │
    // └─────────────────────────────────────────────────────────────────────────┘

    // Name of output png file
    public $tmp_png_filename = '/1_temporary_genimage.png';

    // Upload dir + output png file
    public $png_dir;

    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                JPG Files                                │
    // └─────────────────────────────────────────────────────────────────────────┘

    // Name of output jpg file
    public $tmp_jpg_filename = '/1_temporary_genimage.jpg';

    // Upload dir + output jpg file
    public $jpg_dir;




    public function __construct($svg_data, $source_image, $save_options)
    {
        if ($svg_data == null || $source_image == null || $save_options == null) {
            return;
        }
        $this->svg_data = $svg_data;
        $this->source_image = $source_image;
        $this->save_options = $save_options;

        // Set upload directory.
        $this->upload_dir = 'wp-content/uploads' . wp_upload_dir()['subdir'];


        $this->add_filename_suffix();

        $this->set_svg_filename();

        $this->set_png_filename();

        $this->set_jpg_filename();

        $this->create_SVG_file_for_conversion();

        $this->convert_to_png();

        $this->png_to_jpg();

        $this->rewrite_SVG_file_with_absolutes();

        $this->cleanup();
        
        return $this;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │             Add the _gi onto the end of the source filename             │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function add_filename_suffix(){

        // Remove the suffix off the end (to stop nesting of the name _gi_gi_gi...)
        $this->source_image = str_replace( $this->file_suffix.'.jpg', '.jpg', $this->source_image);
        $this->source_image = str_replace( $this->file_suffix.'.png', '.png', $this->source_image);

        // Add the suffix on. ( _gi )
        $this->source_image = str_replace('.jpg', $this->file_suffix.'.jpg', $this->source_image);
        $this->source_image = str_replace('.png', $this->file_suffix.'.png', $this->source_image);
        
        return;
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                         Set the output filenames                        │
    // │                              1. Use Default                             │
    // │            2. Override to sourcefile name if 'save as' is set           │
    // └─────────────────────────────────────────────────────────────────────────┘

    public function set_svg_filename(){
        
        $this->svg_file = $this->upload_dir .  $this->tmp_svg_filename;

        if ($this->save_options['svg'] == true){
            $svg_filename = str_replace('.png', '.svg', $this->source_image);
            $svg_filename = str_replace('.jpg', '.svg', $this->source_image);
            $this->svg_file = $svg_filename;
        }

        return;

    }


    public function set_png_filename(){
        $this->png_dir = $this->upload_dir . $this->tmp_png_filename;

        if ($this->save_options['png'] == true){
            $this->png_dir = str_replace('.jpg', '.png', $this->source_image);
        }

        return;
    }

    
    public function set_jpg_filename(){
        $this->jpg_dir = $this->source_image;

        if ($this->save_options['jpg'] == true){
            $this->jpg_dir = str_replace('.png', '.jpg', $this->source_image);
        }

        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                            Write to SVG file                            │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function create_SVG_file_for_conversion()
    {
        file_put_contents($this->svg_file, $this->svg_data);
        return;
    }

    public function rewrite_SVG_file_with_absolutes()
    {
        $svg_date_abs = str_replace('../../../..', get_site_url(), $this->svg_data);
        file_put_contents($this->svg_file, $svg_date_abs);
        return;
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                      INKSCAPE : Convert SVG --> PNG                     │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function convert_to_png()
    {
        
        
        exec('inkscape -z '. $this->svg_file.' -e '.$this->png_dir, $output, $return);

        if ($return > 0) {
            die('Inkscape did not execute correctly. $result = '.$result. ' | $output = '. $output);
        }

        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                    IMAGEMAGICK : Convert PNG --> JPG                    │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function png_to_jpg()
    {
        try {
            $im = new Imagick($this->png_dir);
        } catch (ImagickException $e) {
            $im = null;
        }

        if ($im) {
            $im->setImageCompressionQuality($this->jpg_quality);
            $im->setImageFormat("jpg");
            $im->writeImage($this->jpg_dir);
            $im->clear();
            $im->destroy();
        }
        
        return;
    }


    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │                Remove any files not specified to be saved               │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function cleanup(){

        if ($this->save_options['svg'] == false && file_exists($this->save_options['svg'])){
            $svg_del = unlink($this->svg_dir);
        }

        if ($this->save_options['png'] == false && file_exists($this->save_options['png'])){
            $png_del = unlink($this->png_dir);
        }

        if ($this->save_options['jpg'] == false && file_exists($this->save_options['jpg'])){
            $jpg_del = unlink($this->jpg_dir);
        }

        return;
    }


}
