<?php

/*
    add path to TwigBridge views folder so twig can locate
    the form_div_layout.html.twig file
*/
if (class_exists('Timber')) {
    $vendorDir = realpath(__DIR__ . '/../../../../../../vendor');

    $vendorTwigBridgeDir = $vendorDir . '/symfony/twig-bridge/Symfony/Bridge/Twig/Resources/views/Form';

    \Timber::$locations = $vendorTwigBridgeDir;
}