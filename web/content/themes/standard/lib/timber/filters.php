<?php

namespace CodekippleWordPressTheme\Timber\Filters;

use CodekippleWordPressTheme\Api\Assets as Assets;
use CodekippleWordPressTheme\Api\Theme as Theme;

add_filter('get_twig', function($twig) {
    /* this is where you can add your own functions to twig */
    $twig->addExtension(new \Twig_Extension_StringLoader());

    $twig->addFilter('script', new \Twig_Filter_Function('CodekippleWordPressTheme\Timber\Filters\script'));

    $twig->addFilter('body_class', new \Twig_Filter_Function('CodekippleWordPressTheme\Timber\Filters\body_class'));

    return $twig;
});

function script($script_url){
    return Assets\script($script_url);
}

function body_class($class) {
    $class .= ' ' . WP_ENV;
    return $class;
}