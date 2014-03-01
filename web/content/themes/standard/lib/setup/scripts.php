<?php

use CodekippleWordPressTheme\Api\Assets as Assets;

// add global js variable
add_action('wp_head', function(){
    global $post; ?>
    <script type="text/javascript">
    /* <![CDATA[ */
    var codekipple = {
        ajaxurl: "<?php echo admin_url('admin-ajax.php'); ?>"
    };
    /* ]]> */
    </script>
    <?php
}, 1000);

add_action('wp_enqueue_scripts', function(){
    // we don't need this on admin pages, so...
    if(is_admin()) return;

    // unregister this script unless you are using it
    wp_dequeue_script('comment-reply');

    wp_enqueue_script('main', Assets\script('main-pkg.js'), false, '1.0', true);

});