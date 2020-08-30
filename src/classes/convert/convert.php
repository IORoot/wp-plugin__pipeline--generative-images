<?php

namespace genimage;

class convert
{

    use wp_funcs;
    use debug;

    /**
     * The SVG data to convert
     * 
     * "<svg viewBox="0 0 1280 720" class="svgwrapper" >...</svg>
     *
     * @var string
     */
    private $svg_data;


    /**
     * The relative_filepath of the original image.
     * 
     * E.g.
     * "wp-content/uploads/2020/04/conditioning-pressup-4-fingers-mixed-view-slowmo_KR63mHA-xi8.jpg"
     * 
     * @var string
     */
    private $filepath;


    /**
     * What type to save file to.
     * 
     * svg
     * jpg
     * png
     *
     * @var string
     */
    private $save_type;


    /**
     * Current upload directory
     * 
     * "wp-content/uploads/2020/08"
     *
     * @var string
     */
    private $upload_dir;


    /**
     * This is the suffix to add onto
     * any temporary files to keep them
     * separate from original files.
     */
    private $file_suffix = '_gi';


    /**
     * target_filepath
     * 
     * This is where the target file should be written to.
     *
     * @var string
     */
    private $target_filepath;


    /**
     * intermediate_svg_file
     *
     * An intermediate SVG file needs to be created so that
     * any other file can be created from it.
     * 
     * @var string
     */
    private $intermediate_svg_file;


    /**
     * result variable
     *
     * Contains the filepath of the result image.
     * 
     * @var string
     */
    private $result;
    

    public function set_svg_data($svg_data)
    {
        $this->svg_data = $svg_data;
    }


    public function set_filepath($filepath)
    {
        $wpcontent_filepath = str_replace('../../../../', '', $filepath);
        $this->filepath = pathinfo($wpcontent_filepath);
    }


    public function set_savetype($save_type)
    {
        $this->save_type = $save_type;
    }

    public function get_result()
    {
        return $this->result;
    }



    public function run()
    {

        $this->set_upload_directory();
        $this->set_intermediate_svg_filepath();
        $this->set_target_filepath();
        $this->rewrite_SVG_file_with_no_paths();
        $this->create_intermediate_svg();
        $this->convert_to_file();

        $this::debug(
            [
                'input file' => $this->filepath,
                'save as' => $this->save_type,
                'intermediate file' => $this->intermediate_svg_file,
                'target file' => $this->target_filepath
            ], static::class);

        return;
    }


    public function cleanup(){
        unlink($this->intermediate_svg_file);
    }


    private function set_upload_directory()
    {
        $this->upload_dir = $this::wp_upload_dir();
    }
    
    private function set_target_filepath()
    {
        $this->target_filepath = $this->filepath['dirname']. '/' . $this->filepath['filename'] . $this->file_suffix . '.' . $this->save_type;
    }
    
    private function set_intermediate_svg_filepath()
    {
        $this->intermediate_svg_file = $this->filepath['dirname']. '/' . $this->filepath['filename'] . $this->file_suffix . '_intermediate.svg';
    }

    private function rewrite_SVG_file_with_no_paths()
    { 

        $this->svg_data = str_replace('/wp-content', '../../../../wp-content', $this->svg_data);
        
    }

    private function create_intermediate_svg()
    {
        file_put_contents($this->intermediate_svg_file, $this->svg_data);
        return;
    }


    private function convert_to_file()
    {
        $convert_type = '\genimage\convert\\' . $this->save_type;
        $convert = new $convert_type;
        $convert->target($this->target_filepath);
        $convert->in($this->intermediate_svg_file);
        $this->result = $convert->out();
    }


}
