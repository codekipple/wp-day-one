<?php

add_action('init', function(){
    register_nav_menus(
        array(
            'main' => __('Main'),
            'header' => __('Header'),
            'footer' => __('Footer')
        )
    );
});