<?php

namespace CodekippleWPTheme\Setup\Sidebars;

class Sidebars
{
    public function __construct()
    {
        add_action('init', array(&$this, 'sidebars_init'));
    }


    /* register sidebars
    ----------------------------------------- */
    public function sidebars_init()
    {
        $this->register_theme_sidebar(array(
            'name' => 'Sidebar',
            'id'     => 'sidebar',
            'description'     => 'sidebar.'
        ));
    }


    /* for sidebar defaults!
    ----------------------------------------- */
    function register_theme_sidebar($args = array())
    {
        if ( !function_exists( 'register_sidebar') )
                return;

        $defaults = array(
            'before_widget' => '<section class="widget %2$s"><div class="widget-wrap">',
            'before_title'    => '<h2 class="title">',
            'after_title'     => '</h2>',
            'after_widget'    => '</div></section>'
        );

        return register_sidebar(wp_parse_args($args, $defaults));
    }
}

new Sidebars;