<?php

/*
* Admin scripts
*/
if(isset($_GET['page']) && $_GET['page'] == 'responsive-menu-test'):
    add_action('admin_enqueue_scripts', function() {
        wp_enqueue_media();

        wp_enqueue_script('responsive-menu-test-bootstrap-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/bootstrap.js', null, null);
        wp_enqueue_style('responsive-menu-test-bootstrap-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/bootstrap.css', null, null);

        wp_enqueue_script('responsive-menu-test-select-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/bootstrap-select.js', null, null);
        wp_enqueue_style('responsive-menu-test-select-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/bootstrap-select.css', null, null);

        wp_enqueue_script('responsive-menu-test-checkbox-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/bootstrap-toggle.js', null, null);
        wp_enqueue_style('responsive-menu-test-checkbox-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/bootstrap-toggle.css', null, null);

        wp_enqueue_script('responsive-menu-test-minicolours-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/minicolours.js', null, null);
        wp_enqueue_style('responsive-menu-test-minicolours-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/minicolours.css', null, null);

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