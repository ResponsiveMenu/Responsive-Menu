<?php
/**
 * This is file contain the admin setting part.
 *
 * @since      4.0.0
 *
 * @package    responsive_menu_pro
 */

$global_settings = get_option( 'rmp_global_setting_options' );

if ( empty( $global_settings )  ) {
    $global_settings = rmp_global_default_setting_options();  
    update_option( 'rmp_global_setting_options', $global_settings  );
}

$rmp_custom_css = '';
if ( ! empty(  $global_settings['rmp_custom_css'] ) ) {
    $rmp_custom_css = $global_settings['rmp_custom_css'];

}

$wp_header = 'none';
if ( ! empty( $global_settings['menu_adjust_for_wp_admin_bar'] ) )  {
    $wp_header = $global_settings['menu_adjust_for_wp_admin_bar'];    
}

?>  
<div class="wrap rmp-container rmp-setting-page">
    <h1 class="wp-heading-inline"> <?php esc_html_e( 'Responsive Menu', 'responsive-menu-pro' ); ?> </h1>
    <form method="post" enctype="multipart/form-data" id="rmp-global-settings">

        <div id="rmp-setting-tabs">
            <ul class="nav-tab-wrapper">
                <?php if ( ! empty( get_option('responsive_menu_version') ) ) { ?>
                <li><a class="nav-tab nav-tab-active" href="#rmp-settings-general"><?php esc_html_e('General', 'responsive-menu-pro'); ?></a></li>
                <?php } ?>

                <li><a class="nav-tab" href="#rmp-settings-advanced"><?php esc_html_e('Advance', 'responsive-menu-pro'); ?></a></li>
                <li><a class="nav-tab" href="#rmp-settings-style"><?php esc_html_e('Style', 'responsive-menu-pro'); ?></a></li>
                <li><a class="nav-tab" href="#rmp-settings-import-and-export"><?php esc_html_e('Import/Export', 'responsive-menu-pro'); ?></a></li>
            </ul>

            <?php if ( ! empty( get_option('responsive_menu_version') ) ) { ?>

            <div id="rmp-settings-general">
                <table  class="form-table" role="presentation">
                    <tbody>
                     
                        <tr>
                            <th scope="row"> <?php esc_html_e( 'Rollback Version', 'responsive-menu-pro'); ?></th>
                            <td>
                                <fieldset>
                                    <p>
                                        <select class="" aria-describedby="Rollback Version" id="rmp-versions" name="rmp-versions">
                                            <option value="4.0.0" selected> v4.x</option>
                                            <option value="3.1.30"> v3.1.30</option>
                                        </select>
                                        <button id="rmp-rollback-version" class="button button-primary button-large"><?php esc_html_e( 'Rollback', 'responsive-menu-pro'); ?></button>
                                    </p>
                                    <p class="description"><?php esc_html_e( 'Experiencing an issue with latest version 4.0.0? Rollback to a previous version before the issue appeared.', 'responsive-menu-pro'); ?></p>
                                </fieldset>
                            </td>
                        </tr>

                    </tbody>
                </table>
                <button class="button button-primary button-large rmp-save-global-settings-button" type="button">
                    <?php esc_html_e( 'Save Settings', 'responsive-menu-pro'); ?>
                </button>
                <span class="spinner"></span>
            </div>

            <?php } ?>

            <div id="rmp-settings-advanced" >
                <table class="form-table" role="presentation">
                    <tbody>                                
                        <tr>
                            <th scope="row"> <?php esc_html_e( 'Adjust for WP Admin Bar', 'responsive-menu-pro'); ?></th>
                            <td>
                                <label>
                                    <p>
                                        <select name="menu_adjust_for_wp_admin_bar" value="on" id="rmp-menu_adjust-wp-admin-bar">
                                            <option value="none" <?php echo ( $wp_header == 'none' ? 'selected' : '' ); ?>><?php esc_attr_e( 'None', 'responsive-menu-pro'); ?></option>
                                            <option value="adjust" <?php echo ( $wp_header == 'adjust' ? 'selected' : '' ); ?>><?php esc_attr_e( 'Adjust', 'responsive-menu-pro'); ?></option>
                                            <option value="hide" <?php echo ( $wp_header == 'hide' ? 'selected' : '' ); ?>><?php esc_attr_e( 'Hide', 'responsive-menu-pro'); ?></option>
                                        </select>
                                        <label for="rmp-menu_adjust-wp-admin-bar" class="description">
                                            <?php esc_html_e( 'If you use the WP Admin bar when logged in, this will help you to adjust the admin bar.', 'responsive-menu-pro'); ?>
                                        </label>
                                    </p>
                                </label>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"> <?php esc_html_e( 'Use wp_footer hook', 'responsive-menu-pro'); ?></th>
                            <td>
                                <fieldset>
                                    <p>
                                        <input type="checkbox" name="rmp_wp_footer_hook" value="on" id="rmp-wp-footer-hook" <?php echo is_rmp_option_checked( 'on', $global_settings, 'rmp_wp_footer_hook' );?> > 
                                        <label for="rmp-wp-footer-hook" class="description">
                                            <?php esc_html_e( 'Enable this option if your theme does not support wp_body_open hook.', 'responsive-menu-pro'); ?>
                                        </label>
                                    </p>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"> <?php esc_html_e( 'Use external files', 'responsive-menu-pro'); ?></th>
                            <td>
                                <fieldset>
                                    <p>
                                        <input type="checkbox" name="rmp_external_files" value="on" id="rmp-use-external-files" <?php echo is_rmp_option_checked( 'on', $global_settings, 'rmp_external_files' );?> > 
                                        <label for="rmp-use-external-files" class="description">
                                            <?php esc_html_e( 'Create external files for the CSS and JavaScript created by this plugin.', 'responsive-menu-pro'); ?>
                                        </label>
                                    </p>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"> <?php esc_html_e( 'Minify scripts', 'responsive-menu-pro'); ?> </th>
                            <td>
                                <fieldset>
                                    <p>
                                        <input type="checkbox" name="rmp_minify_scripts" value="on" id="rmp-use-minify-scripts" <?php echo is_rmp_option_checked( 'on', $global_settings, 'rmp_minify_scripts' );?>>
                                        <label for="rmp-use-minify-scripts" class="description">
                                            <?php esc_html_e( 'Minify the CSS and JavaScript created by this plugin.', 'responsive-menu-pro'); ?>
                                        </label>
                                    </p>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>    
                            <th scope="row"><?php esc_html_e( 'Place scripts in footer', 'responsive-menu-pro'); ?>  </th>
                            <td>
                                <fieldset>
                                    <p>
                                        <input type="checkbox" name="rmp_scripts_in_footer" value="on" id="rmp-footer-scripts" <?php echo is_rmp_option_checked( 'on', $global_settings, 'rmp_scripts_in_footer' );?>>
                                        <label for="rmp-footer-scripts" class="description">
                                            <?php esc_html_e( 'Place the JavaScript created by this plugin in the footer.', 'responsive-menu-pro'); ?>
                                        </label>
                                    </p>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"> <?php esc_html_e( 'Remove Dashicons', 'responsive-menu-pro'); ?> </th>
                            <td>
                                <fieldset>
                                    <p>
                                        <input type="checkbox" name="rmp_remove_dashicons" value="on" id="rmp-remove-dashicons" <?php echo is_rmp_option_checked( 'on', $global_settings, 'rmp_remove_dashicons' );?>>
                                        <label for="rmp-remove-dashicons" class="description">
                                            <?php esc_html_e( 'Stop this plugin\'s dashicons scripts from being load at frontend.', 'responsive-menu-pro'); ?>
                                        </label>
                                    </p>
                                </fieldset>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button class="button button-primary button-large rmp-save-global-settings-button" type="button">
                    <?php esc_html_e( 'Save Settings', 'responsive-menu-pro'); ?>
                </button>
                <span class="spinner"></span>
            </div>
 
            <div id="rmp-settings-style" >
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"> <?php esc_html_e( 'Custom CSS', 'responsive-menu-pro'); ?> </th>
                            <td>
                                <label for="rmp-custom-css" class="description">
                                    <?php esc_html_e( 'You can place any Custom CSS you want here. Very useful if you want to make minor tweaks to some margins, paddings or colours or even for whole new layouts or designs.', 'responsive-menu-pro'); ?>
                                </label>
                                <p>
                                    <textarea class="large-text code" id="rmp-custom-css" name="rmp_custom_css"><?php echo $rmp_custom_css; ?></textarea>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button class="button button-primary button-large rmp-save-global-settings-button" type="button">
                    <?php esc_html_e( 'Save Settings', 'responsive-menu-pro'); ?>
                </button>
                <span class="spinner"></span>
            </div>

            <div id="rmp-settings-import-and-export" >
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"> <?php esc_html_e( 'Export Menu', 'responsive-menu-pro'); ?> </th>
                            <td>
                               <select id="rmp_export_menu_list">
                                <?php
                                    $menus = rmp_get_all_menus();
                                    foreach( $menus as $id => $title ) {
                                        printf(
                                            '<option value="%s">%s</option>',
                                            esc_attr( $id ),
                                            esc_html( $title )
                                        );
                                    }
                                ?>
                               </select>
                               <button type="button" class="button button-primary button-large" id="rmp-export-menu-button">
                                   <?php esc_html_e( 'Export', 'responsive-menu-pro'); ?>
                               </button>
                               <p class="description">
                                    <?php esc_html_e( 'This will create an export file for selected menu transferring to other sites.', 'responsive-menu-pro'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"> <?php esc_html_e( 'Import Menu', 'responsive-menu-pro'); ?> </th>
                            <td>

                                <div class="rmp-import-file-container">
                                    <input type="file" id="rmp_input_import_file" />
                                </div>

                                <div class=rmp-menu-import-options>
                                    <select id="rmp_import_menu_list">
                                    <?php
                                        $menus = rmp_get_all_menus();
                                        foreach( $menus as $id => $title ) {
                                            printf(
                                                '<option value="%s">%s</option>',
                                                esc_attr( $id ),
                                                esc_html( $title )
                                            );
                                        }
                                    ?>
                                    </select>

                                    <button type="button" class="button button-primary button-large" id="rmp-import-menu-button">
                                        <?php esc_html_e( 'Import', 'responsive-menu-pro'); ?>
                                    </button>
                                </div>

                                <p class="description">
                                    <?php esc_html_e( 'This will import settings in selected menu created via the export process above.', 'responsive-menu-pro'); ?>
                                </p>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
