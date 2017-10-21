<?php

add_action('init', function() {

    $options_manager = get_responsive_menu_service('option_manager');
    $plugin_data = get_file_data(dirname(__FILE__) . '/responsive-menu.php', ['version']);
    $new_version = $plugin_data[0];

    $old_version = get_option('responsive_menu_version') ? get_option('responsive_menu_version') : get_option('RMVer');

    $migration = new ResponsiveMenu\Database\Migration(
        $options_manager,
        $old_version,
        $new_version,
        get_responsive_menu_default_options()
    );

    if($migration->needsTable()) {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        global $wpdb;
        maybe_create_table(
            $wpdb->prefix . 'responsive_menu',
            "CREATE TABLE " . $wpdb->prefix . "responsive_menu (
              name varchar(50) NOT NULL,
              value LONGTEXT DEFAULT NULL,
              PRIMARY KEY (name)
           ) " . $wpdb->get_charset_collate() . ";"
        );
    }

    if($migration->needsUpdate()) {

        $migration->addNewOptions();
        $migration->tidyUpOptions();

        if($migration->getMigrationClasses()):
            $updated_options = $options_manager->all();
            foreach($migration->getMigrationClasses() as $migration)
                $migrated_options = $migration->migrate($updated_options);
            $options_manager->updateOptions($migrated_options->toArray());
        endif;

        $task = new ResponsiveMenu\Tasks\UpdateOptionsTask();
        $task->run($options_manager->all(), get_responsive_menu_service('view'));
        update_option('responsive_menu_version', $new_version);

    }

});