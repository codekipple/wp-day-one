<?php

add_action("wp_ajax_my_example_action", "my_example_action");
add_action("wp_ajax_nopriv_my_example_action", "my_example_action");

function my_example_action()
{
    die();
}