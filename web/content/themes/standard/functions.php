<?php

require VENDORS_DIR . '/autoload.php';

/* include functions local to this theme */
foreach (glob(get_template_directory() . '/lib/*.php') as $function) {
    locate_template(trim(str_replace(get_template_directory(), '', $function), '/'), true);
}