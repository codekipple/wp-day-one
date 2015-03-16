<?php

// Timber
require 'Timber/Site.php';

// Forms
require 'Form/Factory.php';
require 'Form/TwigBridge.php';
require 'Form/Twig.php';
require 'Form/Translations.php';
require 'Form/Crsf.php';

// functions available to theme template files
foreach (glob(dirname(__FILE__) . '/Api/*.php') as $file) {
    require_once $file;
}

// Theme setup, things like menus locations, sidebars ect.
foreach (glob(dirname(__FILE__) . '/Setup/*.php') as $file) {
    require_once $file;
}

// AJAX
// require 'Ajax/Example.php';

// Post Types
// require 'PostTypes/MyPostType.php';

// Taxonomies
// require 'Taxonomies/MyTaxonomy.php';

// Widgets
// require 'Widgets/MyPostWidget.php';

// Shortcodes
// require 'Shortcodes/MyShortcode.php';