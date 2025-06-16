<?php

namespace ReProgressBar\Frontend;

defined( 'ABSPATH' ) || exit;

class Assets {

    public static function enqueue(): void {
        $file = plugin_dir_path( __DIR__ . '/../../' ) . 'assets/js/progress-bar.js';
        wp_enqueue_script(
            're-progress-bar-tracker',
            plugin_dir_url( __DIR__ . '/../../' ) . 'assets/js/progress-bar.js',
            [],
            filemtime( $file ),
            true
        );
    }
}
