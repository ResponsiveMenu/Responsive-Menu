<?php

/*
* Admin scripts
*/
if(isset($_GET['page']) && $_GET['page'] == 'responsive-menu-test'):
    add_action('admin_enqueue_scripts', function() {
        wp_enqueue_media();

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');

        wp_enqueue_script('responsive-menu-test-bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', null, null);
        wp_enqueue_style('responsive-menu-test-bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', null, null);

        wp_enqueue_script('postbox');

        wp_enqueue_script('jquery-ui-core');

        wp_register_style('responsive-menu-test-admin-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/admin.css', false, null);
        wp_enqueue_style('responsive-menu-test-admin-css');

        wp_register_script('responsive-menu-test-admin-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/admin.js', 'jquery', null);
        wp_enqueue_script('responsive-menu-test-admin-js');
    });
endif;

/* Front End scripts */
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('jquery');
});