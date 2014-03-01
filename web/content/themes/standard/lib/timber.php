<?php

// define('THEME_URL', get_template_directory_uri());

/*
    twig filters, functions
*/
foreach (glob(dirname(__FILE__) . '/timber/*.php') as $file) {
    require_once $file;
}