<?php

$new_version = get_file_data(dirname(__FILE__) . '/responsive-menu-test.php', ['version'])[0];
$old_version = get_option('responsive_menu_test_version');
include dirname(__FILE__) . '/config/default_options.php';
global $wpdb;

$migration = new ResponsiveMenuTest\Database\Migration(
    new ResponsiveMenuTest\Management\OptionManager(
        new ResponsiveMenuTest\Database\Database($wpdb)
    ),
    $old_version,
    $new_version,
    $default_options
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
    $migration->sync();
    update_option('responsive_menu_test_version', $new_version);
}