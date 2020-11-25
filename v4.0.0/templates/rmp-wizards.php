<?php
/**
 * This is file contain the new menu creation settings markups.
 *
 * @since      4.0.0
 *
 * @package    responsive_menu_pro
 */
?>
<!--- This is icon picker wizard markups -->
<section class="rmp-dialog-overlay rmp-menu-icons-dialog" style="display:none">
    <div class="rmp-dialog-backdrop"></div>
    <div class="rmp-dialog-wrap wp-clearfix">
        <div class="rmp-dialog-header">
            <strong class="title"><?php esc_html_e('Select icon', 'responsive-menu-pro'); ?></strong>
            <span class="close dashicons dashicons-no"></span>
        </div>
        <div class="rmp-dialog-contents wp-clearfix">
            <div id="tabs" class="tabs icon-tabs">
                <ul class="nav-tab-wrapper">
                    <li><a class="nav-tab-active nav-tab" href="#dashicons"><?php esc_html_e('Dashicons', 'responsive-menu-pro'); ?></a></li>
                    <li>
                        <a class="nav-tab" href="#material-icon">
                            <?php esc_html_e('Material Icons (mdi)', 'responsive-menu-pro'); ?>
                            <span class="upgrade-tooltip"> PRO </span>
                        </a>
                    </li> 
                    <li>
                        <a class="nav-tab" href="#fas">
                            <?php esc_html_e('FontAwesome Solid (fas)', 'responsive-menu-pro'); ?>
                            <span class="upgrade-tooltip"> PRO </span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-tab" href="#fab">
                            <?php esc_html_e('FontAwesome Brand (fab)', 'responsive-menu-pro'); ?>
                            <span class="upgrade-tooltip"> PRO </span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-tab" href="#far">
                            <?php esc_html_e('FontAwesome Regular (far)', 'responsive-menu-pro'); ?>
                            <span class="upgrade-tooltip"> PRO </span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-tab" href="#glyphicons">
                            <?php esc_html_e('GlyphIcon', 'responsive-menu-pro'); ?>
                            <span class="upgrade-tooltip"> PRO </span>
                        </a>
                    </li>
                </ul>
                <div class="rmp-icon-tab-contents">
                    <div id="dashicons" style="padding: 20px;">
                        <p> <input type="text" class="medium-text" id="rmp-icon-search" placeholder="Search icons"/> </p>   
                        <?php echo rmp_dashicon_selector(); ?>
                    </div>
                    <div id="fab">
                    <?php
                        printf(
                            '<div class="upgrade-options">
                                <div class="upgrade-notes">
                                    <p> %s </p>
                                    <a target="_blank" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" class="button"> %s </a>
                                </div>
                            </div>',
                            __('FontAwesome brand icons are not available in free version. <br/> Upgrade now to use', 'responsive-menu-pro'),
                            esc_html__('Upgrade to Pro', 'responsive-menu-pro')
                        );
                    ?>
                    </div>
                    <div id="fas">
                    <?php
                        printf(
                            '<div class="upgrade-options">
                                <div class="upgrade-notes">
                                    <p> %s </p>
                                    <a target="_blank" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" class="button"> %s </a>
                                </div>
                            </div>',
                            __('FontAwesome solid icons are not available in free version. <br/> Upgrade now to use', 'responsive-menu-pro'),
                            esc_html__('Upgrade to Pro', 'responsive-menu-pro')
                        );
                    ?>
                    </div>
                    <div id="glyphicons">
                    <?php
                        printf(
                            '<div class="upgrade-options">
                                <div class="upgrade-notes">
                                    <p> %s </p>
                                    <a target="_blank" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" class="button"> %s </a>
                                </div>
                            </div>',
                            __('The  glyphicons are not available in free version. <br/> Upgrade now to use', 'responsive-menu-pro'),
                            esc_html__('Upgrade to Pro', 'responsive-menu-pro')
                        );
                    ?>
                    </div>
                    <div id="material-icon">
                    <?php
                        printf(
                            '<div class="upgrade-options">
                                <div class="upgrade-notes">
                                    <p> %s </p>
                                    <a target="_blank" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" class="button"> %s </a>
                                </div>
                            </div>',
                            __('Material icons are not available in free version. <br/> Upgrade now to use', 'responsive-menu-pro'),
                            esc_html__('Upgrade to Pro', 'responsive-menu-pro')
                        );
                    ?>
                    </div>
                    <div id="far">
                    <?php
                        printf(
                            '<div class="upgrade-options">
                                <div class="upgrade-notes">
                                    <p> %s </p>
                                    <a target="_blank" href="https://responsive.menu/pricing?utm_source=free-plugin&utm_medium=option&utm_campaign=hide_on_mobile" class="button"> %s </a>
                                </div>
                            </div>',
                            __('FontAwesome regular icons are not available in free version. <br/> Upgrade now to use', 'responsive-menu-pro'),
                            esc_html__('Upgrade to Pro', 'responsive-menu-pro')
                        );
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="rmp-dialog-footer">
            <a class="button button-secondary button-large" id="rmp-icon-dialog-clear"><?php esc_html_e('Clear', 'responsive-menu-pro'); ?></a>
            <a class="button button-primary button-large" id="rmp-icon-dialog-select"><?php esc_html_e('Select', 'responsive-menu-pro'); ?></a>
        </div>
    </div>
</section>

<!--- This is theme saving form wizard markups -->
<section id="rmp-menu-save-theme-wizard" class="rmp-dialog-overlay" style="display:none">
    <div class="rmp-dialog-backdrop"></div>
    <div class="rmp-dialog-wrap wp-clearfix">
        <span class="close dashicons dashicons-no"></span>
        <div class="rmp-dialog-contents wp-clearfix">
            <span class="rmp-menu-library-blank-icon  fas fa-save"></span>
            <h3 class="rmp-menu-library-title"><?php esc_html_e('Save menu options as theme template', 'responsive-menu-pro'); ?></h3>
            <p class="rmp-menu-library-message"><?php esc_html_e('Your designs will be available for export and reuse on any menu or website.', 'responsive-menu-pro'); ?></p>
            <div class="rmp-save-menu-input">
                <input type="text" id="rmp-save-theme-name" name="rmp_theme_name" placeholder="Enter Template Name"/>
                <button type="button" class="button save-button" id="rmp-save-theme"><?php esc_html_e('Save Theme', 'responsive-menu-pro'); ?></button>
            </div>
        </div>
    </div>
</section>

<!--- Theme list wizard markups -->
<section id="rmp-new-menu-wizard" class="rmp-dialog-overlay rmp-new-menu-wizard" style="display:none">
    <div class="rmp-dialog-backdrop"></div>
    <div class="rmp-dialog-wrap wp-clearfix">
        <div class="rmp-dialog-header">
            <strong class="title"><?php esc_html_e('Use theme', 'responsive-menu-pro'); ?></strong>
            <button class="close dashicons dashicons-no"></button>
        </div>
        <div class="rmp-dialog-contents wp-clearfix tabs" id="tabs" >  
            <div id="select-themes" class="rmp-new-menu-themes">
                <div id="tabs" class="tabs">
                    <ul class="nav-tab-wrapper">
                        <li><a class="nav-tab" href="#tabs-1"><?php esc_html_e('Premium', 'responsive-menu-pro'); ?></a></li>
                        <li><a class="nav-tab" href="#tabs-2"><?php esc_html_e('My Downloads', 'responsive-menu-pro'); ?></a></li>
                        <li><a class="nav-tab" href="#tabs-3"><?php esc_html_e('Templates', 'responsive-menu-pro'); ?></a></li>
                    </ul>

                    <div id="tabs-1" class="rmp-themes"> 
                        <ul class="rmp_theme_grids">
                            <?php 
                                echo $theme_manager->get_themes_from_theme_store( true );
                            ?>
                        </ul>
                    </div>

                    <div id="tabs-2" class="rmp-themes">
                       <ul class="rmp_theme_grids">
                        <?php
                            $downloaded_themes = $theme_manager->get_themes_from_uploads();
                            foreach( $downloaded_themes as $theme ) {
                                $id = 'rmp-theme-' . preg_replace('/\s+/', '', $theme['theme_name'] );
                        ?>
                            <li class="rmp_theme_grid_item">
                                <div class="rmp-item-card">
                                    <figure class="rmp-item-card_image">
                                        <img src="<?php echo esc_url( $theme['theme_preview_url'] );?>" alt="" loading="lazy"/>
                                        <figcaption class="rmp-item-card_label <?php echo $theme['status']; ?>">
                                            <span class="dashicons dashicons-star-filled "></span> <?php echo $theme['status']; ?>
                                        </figcaption>
                                    </figure>
                                    <div class="rmp-item-card_contents">
                                        <h4> <?php echo esc_html( $theme['theme_name'] ); ?> </h4>
                                    </div>
                                    <div class="rmp-item-card_action">
                                        <button class="button rmp-theme-apply" theme-name="<?php echo esc_attr( $theme['theme_name'] ); ?>" theme-type="downloaded">
                                         <?php esc_html_e('Apply', 'responsive-menu-pro'); ?>
                                        </button>
                                    </div>
                                </div>
                            </li>

                        <?php } ?>
                        </ul>
                    </div>
                    <div id="tabs-3" class="rmp-themes">
                        <?php 
                            echo $theme_manager->rmp_saves_theme_list_html();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
