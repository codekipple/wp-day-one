<?php

/**
 * Customisations to setup the theme
 */

foreach (glob(dirname(__FILE__) . '/setup/*.php') as $file) {
    require_once $file;
}