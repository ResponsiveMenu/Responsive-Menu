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

        wp_enqueue_script('responsive-menu-selectize-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/selectize.js', null, null);
        wp_enqueue_style('responsive-menu-selectize-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/selectize.css', null, null);

        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-draggable');

        wp_register_style('responsive-menu-base-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/base.css', false, null);
        wp_enqueue_style('responsive-menu-base-css');

        wp_register_style('responsive-menu-additional-css', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/additional.css', false, null);
        wp_enqueue_style('responsive-menu-additional-css');

        $options = get_responsive_menu_service('option_manager')->all();
        if(isset($options['admin_theme']) || isset($_POST['menu']['admin_theme'])):
            $theme = isset($_POST['menu']['admin_theme']) ? $_POST['menu']['admin_theme'] : $options['admin_theme'];
            wp_register_style('responsive-menu-admin-css-theme' . $theme, plugin_dir_url(dirname(dirname(__FILE__))) . 'public/css/admin/themes/' . $theme . '.css', false, null);
            wp_enqueue_style('responsive-menu-admin-css-theme' . $theme);
        endif;

        wp_register_script('responsive-menu-base-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/base.js', 'jquery', null);
        wp_localize_script('responsive-menu-base-js', 'WP_HOME_URL', home_url('/'));
        wp_localize_script('responsive-menu-base-js', 'THEMES_FOLDER_URL', wp_upload_dir()['baseurl'] . '/responsive-menu-themes/');
        wp_enqueue_script('responsive-menu-base-js');

        wp_register_script('responsive-menu-additional-js', plugin_dir_url(dirname(dirname(__FILE__))) . 'public/js/admin/additional.js', 'jquery', null);
        wp_localize_script('responsive-menu-additional-js', 'WP_HOME_URL', home_url('/'));
        wp_enqueue_script('responsive-menu-additional-js');

    });
endif;

/* Front End scripts */
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_script('jquery');
});