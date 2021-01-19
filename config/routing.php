<?php

if(is_admin()):
    add_action('admin_menu', function() {

        if(isset($_POST['responsive-menu-export']) && isset($_GET['page']) && $_GET['page'] == 'responsive-menu'):
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: attachment; filename=responsive-menu-settings.json');
            $controller = get_responsive_menu_service('admin_controller');
            echo $controller->export();
            exit();

        endif;

        add_menu_page(
            'Responsive Menu',
            'Responsive Menu',
            'manage_options',
            'responsive-menu',
            function() {
                $controller = get_responsive_menu_service('admin_controller');

                if(isset($_POST['responsive-menu-current-page']))
                    update_option('responsive_menu_current_page', $_POST['responsive-menu-current-page']);

                if(isset($_POST['responsive-menu-submit'])):
                    update_option('hide_pro_options', isset($_POST['hide-pro-options']) ? 'yes' : 'no');
                    $valid_nonce = wp_verify_nonce($_POST['responsive-menu-nonce'], 'update');
                    echo $controller->update($valid_nonce, wp_unslash($_POST['menu']));

                elseif(isset($_POST['responsive-menu-reset'])):
                    $valid_nonce = wp_verify_nonce( $_POST['responsive-menu-nonce'], 'update' );

                    echo $controller->reset(get_responsive_menu_default_options(), $valid_nonce );

                elseif(isset($_POST['responsive-menu-theme'])):
                    $valid_nonce = wp_verify_nonce( $_POST['responsive-menu-nonce'], 'update' );

                    echo $controller->apply_theme($_POST['menu']['menu_theme'], $valid_nonce );

                elseif(isset($_POST['responsive-menu-import'])):
                    $valid_nonce = wp_verify_nonce( $_POST['responsive-menu-nonce'], 'update' );

                    $file = $_FILES['responsive-menu-import-file'];
                    $file_options = isset($file['tmp_name']) ? (array) json_decode(file_get_contents($file['tmp_name'])) : null;
                    echo $controller->import( $file_options, $valid_nonce );

                elseif(isset($_POST['responsive-menu-import-theme'])):
                    $valid_nonce = wp_verify_nonce( $_POST['responsive-menu-nonce'], 'update' );

                    $file = $_FILES['responsive-menu-import-theme-file'];
                    $theme = isset($file['tmp_name']) && $file['tmp_name'] ? $file['tmp_name'] : null;

                    echo $controller->import_theme( $theme, $valid_nonce );

                elseif(isset($_POST['responsive-menu-rebuild-db'])):
                    $valid_nonce = wp_verify_nonce( $_POST['responsive-menu-nonce'], 'update' );

                    echo $controller->rebuild( $valid_nonce );

                else:
                    echo $controller->index();

                endif;
            },
            'dashicons-menu');
    });

    /** Function to move to the new version */
    add_action('wp_ajax_rmp_switch_version', function() {

		if ( empty ( update_option( 'is_rmp_new_version', 1 ) ) ) {
			add_option( 'is_rmp_new_version', 1 );
		}

        update_option( 'rm_upgrade_admin_notice', true );
		wp_send_json_success( ['redirect' => admin_url('edit.php?post_type=rmp_menu')] );

    });

    add_action( "wp_ajax_rmp_version_admin_notice_dismiss", "rmp_version_admin_notice_dismiss");
    /**
     * Function to hide the version upgrade admin notice permanent.
     */
    function rmp_version_admin_notice_dismiss() {
        update_option( 'rm_upgrade_admin_notice', true );
    }


else:
    add_action('template_redirect', function() {
        $controller = get_responsive_menu_service('front_controller');
        if(isset($_GET['responsive-menu-preview']) && isset($_POST['menu']))
            echo $controller->preview();
        else
            $controller->index();
    });
endif;
