<?php

if(is_admin()):

    $loader = new Twig_Loader_Filesystem([
        dirname(dirname(__FILE__)) . '/public',
        dirname(dirname(__FILE__)) . '/views',
    ]);
    $twig = new Twig_Environment($loader);

    global $wpdb;
    $controller = new ResponsiveMenuTest\Controllers\AdminController(
        new ResponsiveMenuTest\Management\OptionManager(
            new ResponsiveMenuTest\Database\Database($wpdb)
        ),
        new ResponsiveMenuTest\View\AdminView($twig)
    );

    if(isset($_POST['responsive-menu-export'])):
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=export.json');
        echo $controller->export();
        exit();
    endif;

    add_action('admin_menu', function() use($controller) {

        if(isset($_GET['page']) && $_GET['page'] == 'responsive-menu-test'):
            wp_enqueue_media();

            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');

            wp_enqueue_script('responsive-menu-test-bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', null, null);
            wp_enqueue_style('responsive-menu-test-bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', null, null);

            wp_enqueue_script('postbox');

            wp_enqueue_script('jquery-ui-core');

            wp_register_style('responsive-menu-test-admin-css',  plugin_dir_url(dirname(__FILE__)) . 'public/css/admin/admin.css', false, null);
            wp_enqueue_style('responsive-menu-test-admin-css');

            wp_register_script('responsive-menu-test-admin-js',  plugin_dir_url(dirname(__FILE__)) . 'public/js/admin/admin.js', 'jquery', null);
            wp_enqueue_script('responsive-menu-test-admin-js');
        endif;

        add_menu_page(
            'Responsive Menu Test',
            'Responsive Menu Test',
            'manage_options',
            'responsive-menu-test',
            function() use($controller) {
                if(isset($_POST['responsive-menu-submit'])):
                    echo $controller->update($_POST['menu']);
                elseif(isset($_POST['responsive-menu-reset'])):
                    include dirname(__FILE__) . '/default_options.php';
                    echo $controller->reset($default_options);
                elseif(isset($_POST['responsive-menu-import'])):
                    $file = $_FILES['responsive_menu_import_file'];
                    $file_options = isset($file['tmp_name']) ? (array) json_decode(file_get_contents($file['tmp_name'])) : null;
                    echo $controller->import($file_options);
                else:
                    echo $controller->index();
                endif;
            },
            'dashicons-menu');
    });
else:
    if(isset($_GET['responsive-menu-preview']) && isset($_POST['menu'])):
        add_action('template_redirect', function() use($container) {
            $container['front_controller']->preview();
        });
    else:
        add_action('template_redirect', function() use($container) {
            $container['front_controller']->index();
        });
    endif;
endif;
