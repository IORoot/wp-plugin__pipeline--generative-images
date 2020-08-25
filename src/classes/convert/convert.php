<?php

namespace genimage;

use Imagick;

class convert
{

    use wp_funcs;

    /**
     * The SVG data to convert
     * 
     * "<svg viewBox="0 0 1280 720" class="svgwrapper" >...</svg>
     *
     * @var string
     */
    private $svg_data;

    /**
     * The relative_filename of the original image.
     * 
     * E.g.
     * "../../../../wp-content/uploads/2020/04/conditioning-pressup-4-fingers-mixed-view-slowmo_KR63mHA-xi8.jpg"
     * 
     * @var string
     */
    private $relative_filename;


    /**
     * Array of which conversions should happen.
     *
     * @var array
     */
    private $save_options;


    /**
     * Current upload directory
     *
     * @var string
     */
    private $upload_dir;


    // The source image relative filepath
    // wp-content/uploads/2020/03/original_file.jpg
    public $source_image;

    // JPG Quality to save as
    public $jpg_quality = 100;




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



    public function set_svg_data($svg_data)
    {
        $this->$svg_data = $svg_data;
    }


    public function set_filename($relative_filename)
    {
        $this->$relative_filename = $relative_filename;
    }




    
    public function __construct()
    {
        if ($svg_data == null || $source_image == null || $save_options == null) {
            return;
        }



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


    public function run()
    {
        $this->set_upload_directory();

    }






    private function set_upload_directory()
    {
        // Set upload directory.
        $this->upload_dir = $this::wp_upload_dir();
    }



    // ┌─────────────────────────────────────────────────────────────────────────┐
    // │                                                                         │
    // │             Add the _gi onto the end of the source filename             │
    // │                                                                         │
    // └─────────────────────────────────────────────────────────────────────────┘
    public function add_filename_suffix(){

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
