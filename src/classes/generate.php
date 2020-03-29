<?php

namespace genimage;

class generate {


    public function __construct(){

        // ┌──────────────────────────────────────┐
        // │        Register the shortcodes       │
        // └──────────────────────────────────────┘
        new shortcodes\add_shortcodes;

        return;
    }

}