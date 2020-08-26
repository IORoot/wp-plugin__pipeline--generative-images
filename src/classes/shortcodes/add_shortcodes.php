<?php

namespace genimage\shortcodes;

use genimage\exporter\convert_to_file as convert;
use genimage\wp\set_image;
use genimage\output\screen;
use genimage\utils\rename_file;
use genimage\options;

class add_shortcodes
{
    public $svg;
    public $png;
    public $jpg;

    public $suffix = '_gi';

    public $source_files;
    public $source_posts;

    public $options;
    public $save_options;

    public function __construct()
    {
        add_shortcode('andyp_gen_image', array($this, 'generative_image'));
        return;
    }


    public function generative_image()
    {

        // $this->get_acf_options();

        // $this->build_svg();

        // $this->convert_files();

        // $this->render_table();

        $this->save_featured_image();

        $this->switch_off_file_write();
        
        return;
    }





    // private function get_acf_options()
    // {
    //     $this->options = (new options)->get_article_options();
    //     $this->save_options = $this->options['save'];
    //     return $this;
    // }





    // public function build_svg()
    // {
    //     $genimage = new image_collection;
    //     $genimage->set_options($this->options);
    //     $this->svg = $genimage->run();
        
    //     $this->source_files = $genimage->get_source_files();
    //     $this->source_posts = $genimage->get_source_posts();
    // }





    // public function convert_files()
    // {
    //     $i = 0;
    //     foreach ($this->svg as $svgfile) {

    //         // make absolute path to relative.
    //         $filename = str_replace(get_site_url().'/', '', $this->source_files[$i]);
    //         $this->convert = new convert($svgfile, $filename, $this->save_options);
    //         $i++;
    //     }
    //     return;
    // }




    public function render_table()
    {
        $table = new screen;
        $table->svg = $this->svg;
        $table->png = $this->png;
        $table->jpg = $this->jpg;
        $table->save_options = $this->save_options;
        $table->source_files = $this->source_files;

        echo $table->render_table();

    }




    public function save_featured_image()
    {
        $save_type = $this->save_options['post'];
        if ($save_type == 'none' || $save_type == null ) {
            return;
        }

        $i = 0;
        foreach ($this->source_files as $file) {
            $file = (new rename_file)->rename_file($file, $save_type);

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



    public function switch_off_file_write()
    {
        update_field('gi_save_post', 'none', 'option');
    }

}
