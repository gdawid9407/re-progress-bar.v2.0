<?php
// Exit if accessed directly or if uninstall not called by WP
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// --- Remove plugin options ---
delete_option( 're_progress_bar_settings' );
delete_site_option( 're_progress_bar_settings' );

// --- Remove all transients created by the plugin ---
global $wpdb;

// Single-site transients
$wpdb->query(
    "DELETE FROM {$wpdb->options}
     WHERE option_name LIKE '_transient_re_progress_bar_%'
       OR option_name LIKE '_transient_timeout_re_progress_bar_%'"
);

// Multisite (network) transients
$wpdb->query(
    "DELETE FROM {$wpdb->sitemeta}
     WHERE meta_key LIKE '_site_transient_re_progress_bar_%'
       OR meta_key LIKE '_site_transient_timeout_re_progress_bar_%'"
);

// You can add additional cleanup below, e.g.:
// delete_option( 'another_plugin_option' );
// $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name = 'some_other_entry'" );
