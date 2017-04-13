<?php

/*
* Admin scripts
*/
if(isset($_GET['page']) && $_GET['page'] == 'responsive-menu'):
    add_action('admin_enqueue_scripts', function() {
        wp_enqueue_media();

        wp_enqueue_script('responsive-menu-bootstrap-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/bootstrap.js', null, null);
        wp_enqueue_style('responsive-menu-bootstrap-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/bootstrap.css', null, null);

        wp_enqueue_script('responsive-menu-select-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/bootstrap-select.js', null, null);
        wp_enqueue_style('responsive-menu-select-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/bootstrap-select.css', null, null);

        wp_enqueue_script('responsive-menu-checkbox-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/bootstrap-toggle.js', null, null);
        wp_enqueue_style('responsive-menu-checkbox-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/bootstrap-toggle.css', null, null);

        wp_enqueue_script('responsive-menu-file-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/bootstrap-file.js', null, null);

        wp_enqueue_script('responsive-menu-minicolours-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/minicolours.js', null, null);
        wp_enqueue_style('responsive-menu-minicolours-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/minicolours.css', null, null);

        wp_enqueue_script('jquery-ui-core');

        wp_register_style('responsive-menu-admin-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/admin.css', false, null);
        wp_enqueue_style('responsive-menu-admin-css');

        wp_register_script('responsive-menu-admin-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/admin.js', 'jquery', null);
        wp_localize_script('responsive-menu-admin-js', 'WP_HOME_URL', home_url('/'));
        wp_enqueue_script('responsive-menu-admin-js');
    });
endif;

/* Front End scripts */
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('jquery');
});