<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

 
$option_name = 'sds_gfb';
delete_option('$option_name');
 
// for site options in Multisite
//delete_site_option($option_name);
 
// drop a custom database table
global $wpdb;
$wpdb->query("DELETE FROM wp_options WHERE wp_options.option_name = 'sds_gfb'");
                
$sql1 = "DROP TABLE IF EXISTS gbf_test_1";		
$wpdb->query( $sql1 );