<?php

/*
    functions available to theme template files
*/
foreach (glob(dirname(__FILE__) . '/api/*.php') as $file) {
    require_once $file;
}