<?php

namespace ReProgressBar\Frontend;

defined( 'ABSPATH' ) || exit;

class Assets {

    public static function enqueue(): void {
    $base_path = plugin_dir_path( __DIR__ . '/../../' );
    $base_url  = plugin_dir_url( __DIR__ . '/../../' );

    // Progress bar tracker script
    $js_file = $base_path . 'assets/js/progress-bar.js';
    wp_enqueue_script(
        're-progress-bar-tracker',
        $base_url . 'assets/js/progress-bar.js',
        [],
        filemtime( $js_file ),
        true
    );

    // Przekazanie ustawień z PHP do JS
    wp_localize_script(
        're-progress-bar-tracker',
        'reProgressConfig',
        [
            'thresholdPercent'  => absint( Settings::instance()->get_threshold_percent() ),
            'popupDelaySeconds' => absint( Settings::instance()->get_popup_delay() ),
            'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
        ]
    );

    // Progress bar styles
    $css_file = $base_path . 'assets/css/progress-bar.css';
    wp_enqueue_style(
        're-progress-bar-style',
        $base_url . 'assets/css/progress-bar.css',
        [],
        filemtime( $css_file )
    );
}
}
