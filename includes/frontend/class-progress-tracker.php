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
use WP_REST_Server;
use WP_REST_Request;
use function rest_ensure_response;

final class ProgressTracker {
    private const SCRIPT_HANDLE = 're-progress-bar-tracker';
    private const OPTION_NAME   = 're_progress_bar_options';

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'wp_body_open',       [ $this, 'render_progress_bar' ] );
        add_action( 'rest_api_init',      [ __CLASS__, 'register_rest_route' ] );
    }

    public function enqueue_scripts(): void {
        $plugin_path = plugin_dir_path( __DIR__ . '/../../' );
        $plugin_url  = plugin_dir_url(  __DIR__ . '/../../' );

        wp_enqueue_script(
            self::SCRIPT_HANDLE,
            $plugin_url . 'assets/js/progress-bar.js',
            [],
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

    public static function register_rest_route(): void {
        register_rest_route(
            're-progress/v1',
            '/progress-data',
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ __CLASS__, 'get_progress_data' ],
                'permission_callback' => '__return_true',
            ]
        );
    }

    public static function get_progress_data( WP_REST_Request $request ) {
        $options  = get_option( self::OPTION_NAME, [] );
        $defaults = [
            'progress_threshold' => 50,
            'delay_seconds'      => 60,
        ];
        $settings = wp_parse_args( $options, $defaults );
        return rest_ensure_response( $settings );
    }
}
