<?php

namespace genimage;

trait option_reattach
{

    public function get_reattach()
    {
        return array(
            'reattach' => get_field('gi_save_post' , 'option'),
        );
    }

}