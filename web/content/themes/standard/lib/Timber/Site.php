<?php
namespace CodekippleWPTheme\Timber\Site;

use CodekippleWPTheme\Api\Assets as Assets;

class Site extends \TimberSite {

    function __construct() {
        add_filter('get_twig', array($this, 'add_to_twig'));
        add_filter('timber_context', array( $this, 'add_to_context'));
        parent::__construct();
    }

    function add_to_context($context) {
        $context['live_reload'] = Assets\live_reload();

        // menus
        $context['menu_header'] = new \TimberMenu('header');
        $context['menu_main'] = new \TimberMenu('main');
        $context['menu_footer'] = new \TimberMenu('footer');

        $context['site'] = $this;

        return $context;
    }

    function add_to_twig($twig) {
        /* this is where you can add your own fuctions to twig */
        $twig->addExtension(new \Twig_Extension_StringLoader());
        $twig->addFunction(new \Twig_SimpleFunction('script', 'CodekippleWPTheme\Timber\Site\script'));
        $twig->addFunction(new \Twig_SimpleFunction('css', 'CodekippleWPTheme\Timber\Site\css'));
        $twig->addFilter('body_class', new \Twig_Filter_Function('CodekippleWPTheme\Timber\Site\body_class'));

        return $twig;
    }

}

new Site();

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