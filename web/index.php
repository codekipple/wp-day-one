<?php
/*
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

$cli = php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR']);

/*
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */

define('WP_USE_THEMES', !$cli);

/* Loads the WordPress Environment and Template */
require 'wordpress/wp-blog-header.php';

if ($cli) {
    /* Correct database absolute paths */
    require realpath(__DIR__ . '/../app/migration') . '/environment.php';
}