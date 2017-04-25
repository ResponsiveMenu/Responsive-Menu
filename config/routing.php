<?php

if(is_admin()):
    add_action('admin_menu', function() {
        if(isset($_POST['responsive-menu-export'])):
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename=responsive-menu-settings.json');
            $controller = get_responsive_menu_service('admin_controller');
            echo $controller->export();
            exit();
        elseif(isset($_POST['responsive-menu-rebuild-db'])):
            update_option('responsive_menu_version', '2.8.9');
        endif;
        add_menu_page(
            'Responsive Menu',
            'Responsive Menu',
            'manage_options',
            'responsive-menu',
            function() {
                $controller = get_responsive_menu_service('admin_controller');
                $menus_array = [];
                $location_menus = ['' => 'None'];
                foreach(get_terms('nav_menu') as $menu) $menus_array[$menu->slug] = $menu->name;
                foreach(get_registered_nav_menus() as $location => $menu) $location_menus[$location] = $menu;

                if(isset($_POST['responsive-menu-current-page']))
                    update_option('responsive_menu_current_page', $_POST['responsive-menu-current-page']);

                if(isset($_POST['responsive-menu-submit'])):
                    echo $controller->update($_POST['menu'], $menus_array, $location_menus);
                elseif(isset($_POST['responsive-menu-reset'])):
                    echo $controller->reset(get_responsive_menu_default_options(), $menus_array, $location_menus);
                elseif(isset($_POST['responsive-menu-import'])):
                    $file = $_FILES['responsive-menu-import-file'];
                    $file_options = isset($file['tmp_name']) ? (array) json_decode(file_get_contents($file['tmp_name'])) : null;
                    echo $controller->import($file_options, $menus_array, $location_menus);
                elseif(isset($_POST['responsive-menu-rebuild-db'])):
                    echo $controller->rebuild($menus_array, $location_menus);
                else:
                    echo $controller->index($menus_array, $location_menus);
                endif;
            },
            'dashicons-menu');
    });
else:
    add_action('template_redirect', function() {
        $controller = get_responsive_menu_service('front_controller');
        if(isset($_GET['responsive-menu-preview']) && isset($_POST['menu']))
            echo $controller->preview();
        else
            $controller->index();
    });
endif;
