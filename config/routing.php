<?php

if(is_admin()):

    $controller = new ResponsiveMenuTest\Controllers\AdminController(
        ResponsiveMenuTest\Factories\Factory::OptionManager(),
        ResponsiveMenuTest\Factories\Factory::View()
    );

    if(isset($_GET['page']) && $_GET['page'] == 'responsive-menu-test'):

        if(isset($_POST['responsive-menu-export'])):
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename=export.json');
            echo $controller->export();
            exit();
        endif;
    endif;

    add_action('admin_menu', function() use($controller) {
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

    $controller = new ResponsiveMenuTest\Controllers\FrontController(
        ResponsiveMenuTest\Factories\Factory::OptionManager(),
        ResponsiveMenuTest\Factories\Factory::View()
    );

    add_action('template_redirect', function() use($controller) {

        if(isset($_GET['responsive-menu-preview']) && isset($_POST['menu']))
            $controller->preview();
        else
            $controller->index(plugins_url(), get_current_blog_id());
    });
endif;
