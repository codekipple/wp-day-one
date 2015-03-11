<?php

namespace CodekippleWPTheme\Api\Environment;

/*
    WP_ENV is defined in wp-config.php
*/
function is_dev()
{
    return (WP_ENV =='dev') ? true : false;
}