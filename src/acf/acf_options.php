<?php

namespace genimage;

class options {

    public $options;

    // Option Traits
    use \genimage\option_instances;
    use \genimage\option_saves;
    use \genimage\option_reattach;

    // Data Traits
    use \genimage\option_filters;
    use \genimage\option_source;

}
