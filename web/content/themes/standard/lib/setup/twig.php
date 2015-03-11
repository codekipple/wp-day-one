<?php

namespace CodekippleWPTheme\Setup\Twig;

use CodekippleWPTheme\Api\Assets as Assets;
use CodekippleWPTheme\Api\Theme as Theme;

add_filter('get_twig', function($twig) {
    /* this is where you can add your own functions to twig */
    $twig->addExtension(new \Twig_Extension_StringLoader());
    $twig->addFunction(new \Twig_SimpleFunction('script', 'CodekippleWPTheme\Setup\Twig\script'));
    $twig->addFunction(new \Twig_SimpleFunction('css', 'CodekippleWPTheme\Setup\Twig\css'));
    $twig->addFilter('body_class', new \Twig_Filter_Function('CodekippleWPTheme\Setup\Twig\body_class'));

    return $twig;
});

function script($asset_url){
    return Assets\script($asset_url);
}

function css($asset_url){
    return Assets\css($asset_url);
}

function body_class($class) {
    $class .= ' ' . WP_ENV;
    return $class;
}