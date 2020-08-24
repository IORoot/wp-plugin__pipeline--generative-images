<?php

function gi_acf_admin_head() {
    ?>
    <style type="text/css">
        <?php include 'css/admin.css'; ?>
    </style>
    <?php
}

add_action('acf/input/admin_head', 'gi_acf_admin_head');