<?php
/**
 * This is file contain the themes.
 *
 * @since      4.0.0
 *
 * @package    responsive_menu_pro
 */

use RMP\Features\Inc\Theme_Manager;
$theme_manager  = Theme_Manager::get_instance();

?>  
<div class="wrap rmp-container">

    <!-- Theme page title -->
    <h1 class="wp-heading-inline"> <?php esc_html_e( 'Themes', 'responsive-menu-pro' ); ?> </h1>

    <!-- New theme upload button -->
    <a href="javascript:void(0)" id="rmp-upload-new-theme">
        <?php esc_html_e( 'Upload theme', 'responsive-menu-pro' ); ?>
    </a>

    <!-- Theme drop and upload location -->
    <div id="rmp-menu-library-import" class="hide">
        <form action="<?php echo admin_url( 'admin-post.php' ); ?>" id="rmp-menu-library-import-form" method="post" enctype="multipart/form-data">
            <input type="hidden" id="rmp_theme_upload_nonce" name="rmp_theme_upload_nonce" value="<?php echo wp_create_nonce('rmp_nonce'); ?>"/>
            <a class="cancel">
                <span class="dashicons dashicons-no-alt "></span>
            </a>

            <span class="rmp-menu-library-blank-icon  dashicons dashicons-cloud-upload"></span>

            <h3 class="rmp-menu-library-title"> <?php esc_html_e( 'Import Menu Theme To Your Library', 'responsive-menu-pro' ); ?> </h3>

            <p class="rmp-menu-library-message"> <?php esc_html_e( 'Drop zip files here or click to upload.', 'responsive-menu-pro' ); ?>  </p>
         
            <span class="progress-text"></span>
         
            <label class="button upload-button"><?php esc_html_e( 'Select Files', 'responsive-menu-pro' ); ?> </label>

            <input type='hidden' name='action' value='rmp_upload_theme_file'>
        </form>
    </div>

    <!--- Theme grids --->
    <div class="rmp-theme-page" >
        <ul class="rmp_theme_grids">
             <?php
                $themes = $theme_manager->all_theme_combine_list();
                
                if ( empty( $themes ) ) {
                    $themes = [];
                }

                foreach( $themes as $theme ) {
                    
                    $id          = 'rmp-theme-' . preg_replace('/\s+/', '', $theme['name'] );
                    $preview_url =  RMP_PLUGIN_URL_V4 .'/assets/images/no-preview.jpeg';

                    if ( ! empty( $theme['preview_url'] ) ) {
                        $preview_url = $theme['preview_url'];
                    }
            ?>

            <li class="rmp_theme_grid_item">
                <div class="rmp-item-card">
                    <!--- Theme preview image -->
                    <figure class="rmp-item-card_image">
                        <img src="<?php echo esc_url( $preview_url );?>" alt="" loading="lazy"/>
                    </figure>
                    
                    <!--- Theme titlw -->
                    <div class="rmp-item-card_contents">
                        <h4> <?php echo esc_html( $theme['name'] ); ?> </h4>
                    </div>

                    <!-- Theme actions -->
                    <div class="rmp-item-card_action">
                        <button class="button rmp-theme-delete" data-theme="<?php echo $theme['name']; ?>" data-theme-type="<?php echo $theme['type']; ?> "> Delete </button>
                    </div>

                </div>
            </li>

            <?php }  ?>

        </ul>
    </div>
</div>
