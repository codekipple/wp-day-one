<?php

namespace CodekippleWordPressTheme\Api\Assets;

use CodekippleWordPressTheme\Api\Environment as Environment;

function live_reload()
{
    if (Environment\is_dev()) {
        return '<script src="http://'. $_SERVER['SERVER_ADDR'] .':35729/livereload.js"></script>';
    }
}

function script($script_url)
{
    $script_path = get_bloginfo('template_directory') .'/js';
    $script_path .= (!Environment\is_dev()) ? '-built' : '';
    $script_path .= '/';

    return $script_path . $script_url;
}