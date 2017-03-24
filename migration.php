<?php

$options_manager = get_responsive_menu_test_service('option_manager');
$migration = new ResponsiveMenuTest\Database\Migration(
    $options_manager,
    get_option('responsive_menu_test_version'),
    '3.0.1',
    get_responsive_menu_test_default_options()
);

if($migration->needsTable()) {

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    maybe_create_table(
        $wpdb->prefix . 'responsive_menu_test',
        "CREATE TABLE " . $wpdb->prefix . "responsive_menu_test (
          name varchar(50) NOT NULL,
          value varchar(5000) DEFAULT NULL,
          PRIMARY KEY (name)
       ) " . $wpdb->get_charset_collate() . ";"
    );
}

if($migration->needsUpdate()) {
    $migration->addNewOptions();
    $migration->tidyUpOptions();
    $task = new ResponsiveMenuTest\Tasks\UpdateOptionsTask();
    $task->run($$options_manager->all(), get_responsive_menu_test_service('view'));

    update_option('responsive_menu_test_version', $new_version);
}