<?php

if(is_admin()):
    add_action('admin_menu', function() {
        add_menu_page(
            'Responsive Menu Test',
            'Responsive Menu Test',
            'manage_options',
            'responsive-menu-test',
            function() {
                $controller = get_responsive_menu_test_service('admin_controller');
                if(isset($_POST['responsive-menu-submit'])):
                    echo $controller->update($_POST['menu']);
                elseif(isset($_POST['responsive-menu-reset'])):
                    echo $controller->reset(get_responsive_menu_test_default_options());
                elseif(isset($_POST['responsive-menu-import'])):
                    $file = $_FILES['responsive_menu_import_file'];
                    $file_options = isset($file['tmp_name']) ? (array) json_decode(file_get_contents($file['tmp_name'])) : null;
                    echo $controller->import($file_options);
                elseif(isset($_POST['responsive-menu-export'])):
                    header('Cache-Control: no-cache, no-store, must-revalidate');
                    header('Pragma: no-cache');
                    header('Expires: 0');
                    header('Content-Type: application/json; charset=utf-8');
                    header('Content-Disposition: attachment; filename=export.json');
                    echo $controller->export();
                    exit();
                else:
                    echo $controller->index();
                endif;
            },
            'dashicons-menu');
    });
else:
    add_action('template_redirect', function() {
        $controller = get_responsive_menu_test_service('front_controller');
        if(isset($_GET['responsive-menu-preview']) && isset($_POST['menu']))
            $controller->preview();
        else
            $controller->index(plugins_url(), get_current_blog_id());
    });
endif;
