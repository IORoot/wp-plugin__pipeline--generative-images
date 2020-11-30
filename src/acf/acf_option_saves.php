<?php

namespace genimage;

trait option_saves
{

    public function get_saves()
    {
        return array(
            'svg'  => get_field('gi_save_svg' , 'option'),
            'jpg'  => get_field('gi_save_jpg' , 'option'), 
            'png'  => get_field('gi_save_png' , 'option'), 
        );
    }

}