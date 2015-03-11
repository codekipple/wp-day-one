<?php

namespace CodekippleWPTheme\Api\Assets;

use CodekippleWPTheme\Api\Environment as Environment;

function live_reload()
{
    if (Environment\is_dev()) {
        return '<script src="http://'. $_SERVER['SERVER_ADDR'] .':35729/livereload.js"></script>';
    }
}

function script($asset_url)
{
    $path = 'js';
    $path .= (!Environment\is_dev()) ? '-built' : '';
    $path .= '/';
    $path = release_path() . $path . $asset_url;

    return $path;
}

function css($asset_url)
{
    $path = 'css/';
    $path = release_path() . $path . $asset_url;

    return $path;
}

function release_path()
{
    $release = json_decode(file_get_contents(WEB_DIR . '/../release.js'));
    $release_path = get_bloginfo('stylesheet_directory') . '/release/' . $release->path .'/';

    return $release_path;
}