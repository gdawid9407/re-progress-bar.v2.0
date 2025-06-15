<?php
namespace ReProgressBar\Frontend;

defined( 'ABSPATH' ) || exit;

use function add_action;
use function wp_enqueue_script;
use function wp_localize_script;
use function get_option;
use function wp_parse_args;
use function plugin_dir_url;
use function plugin_dir_path;
use function filemtime;

final class ProgressTracker {
    private const SCRIPT_HANDLE = 're-progress-bar-tracker';
    private const OPTION_NAME   = 're_progress_bar_options';

    public function run(): void {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'wp_footer',         [ $this, 'render_progress_bar' ] );
    }

    public function enqueue_assets(): void {
        $main_file   = __DIR__ . '/../../re-progress_bar.php';
        $plugin_url  = plugin_dir_url( $main_file );
        $plugin_path = plugin_dir_path( $main_file );

        wp_enqueue_style(
            're-progress-bar-style',
            $plugin_url . 'assets/css/progress-bar.css',
            [],
            filemtime( $plugin_path . 'assets/css/progress-bar.css' )
        );

        wp_enqueue_script(
            self::SCRIPT_HANDLE,
            $plugin_url . 'assets/js/progress-bar.js',
            [], // no dependencies
            filemtime( $plugin_path . 'assets/js/progress-bar.js' ),
            true
        );

        $options  = get_option( self::OPTION_NAME, [] );
        $defaults = [
            'progress_threshold' => 50,
            'delay_seconds'      => 60,
        ];
        $settings = wp_parse_args( $options, $defaults );

        wp_localize_script(
            self::SCRIPT_HANDLE,
            'ReProgressBarSettings',
            $settings
        );
    }

    public function render_progress_bar(): void {
        echo '<div id="re-progress-bar-container">';
        echo   '<div id="re-progress-bar-inner"></div>';
        echo '</div>';
    }
}
