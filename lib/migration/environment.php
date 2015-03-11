<?php

global $wpdb;

$wp_home = $wpdb->get_var("SELECT option_value FROM $wpdb->options WHERE option_name = 'home'");

if($wp_home != WP_HOME){
  WIRED_migrate($wp_home);
}

function WIRED_migrate($old_domain) {
  global $wpdb;

  //update home option
  $wpdb->query($wpdb->prepare(
    "
      update $wpdb->options set option_value = '%s'
      where option_name = 'home'
    ",
    WP_HOME
 ));

  //update siteurl option
  $wpdb->query($wpdb->prepare(
    "
      update $wpdb->options set option_value = '%s'
      where option_name = 'siteurl'
    ",
    WP_SITEURL
 ));

  // update guid's
  $wpdb->query($wpdb->prepare(
    "
      update $wpdb->posts set guid = replace(guid, '%s', '%s')
    ",
    $old_domain,
    WP_HOME
 ));

  // update any image attachments
  $wpdb->query($wpdb->prepare(
    "
      update $wpdb->posts set post_content = replace(post_content, '%s', '%s')
    ",
    $old_domain,
    WP_HOME
 ));
}