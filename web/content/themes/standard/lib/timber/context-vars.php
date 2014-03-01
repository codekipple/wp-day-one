<?php

namespace CodekippleWordPressTheme\Timber\ContextVars;

use CodekippleWordPressTheme\Api\Assets as Assets;
use CodekippleWordPressTheme\Api\BrowsingContext as BrowsingContext;

add_filter('timber_context', function($data) {
    // this is where you can add your own data to Timber's context object
    $data['WP_ENV'] = WP_ENV;
    $data['menu_main'] = new \TimberMenu('main');
    $data['menu_header'] = new \TimberMenu('header');
    $data['menu_footer'] = new \TimberMenu('footer');
    $data['live_reload'] = live_reload();
    return $data;
});

function live_reload()
{
    return Assets\live_reload();
}