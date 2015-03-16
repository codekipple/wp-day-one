<?php
if (!class_exists('Timber')) {
    add_action('admin_notices', function() {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url( 'plugins.php#timber')) . '">' . esc_url(admin_url( 'plugins.php' )) . '</a></p></div>';
    } );

    return;
}

require VENDORS_DIR . '/autoload.php';

/* include functions local to this theme */
foreach (glob(get_template_directory() . '/lib/*.php') as $function) {
    locate_template(trim(str_replace(get_template_directory(), '', $function), '/'), true);
}