<?php
/**
 * Main bootstrap for Re Progress Bar plugin.
 *
 * @package ReProgressBar
 */

namespace ReProgressBar;

defined( 'ABSPATH' ) || exit;

use function add_action;
use function load_plugin_textdomain;
use function plugin_basename;
use ReProgressBar\API\RestController;
use ReProgressBar\Assets;
use ReProgressBar\Admin\Assets as AdminAssets;

final class Bootstrap {
    /** @var self|null */
    private static ?self $instance = null;

    /** @var array<object> */
    private array $modules = [];

    private function __construct() {
        // Core modules
        $this->modules = [
            'admin_settings'   => new Admin\Settings(),
            'progress_tracker' => new Frontend\ProgressTracker(),
        ];
    }

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function run(): void {
        // Load translations
        add_action( 'init', [ $this, 'loadTextDomain' ] );

        // Register REST API routes
        add_action( 'rest_api_init', [ RestController::class, 'registerRoutes' ] );

        // Enqueue frontend assets
        add_action( 'wp_enqueue_scripts', [ Assets::class, 'enqueue' ] );

        // Enqueue admin assets
        add_action( 'admin_enqueue_scripts', [ AdminAssets::class, 'enqueue' ] );

        // Initialize modules
        foreach ( $this->modules as $module ) {
            if ( method_exists( $module, 'run' ) ) {
                $module->run();
            }
        }
    }

    public function loadTextDomain(): void {
        load_plugin_textdomain(
            're-progress-bar',
            false,
            dirname( plugin_basename( __DIR__ ) ) . '/languages'
        );
    }

    private function __clone() {}
    public function __wakeup() {
        throw new \Exception( 'Cannot unserialise Bootstrap singleton.' );
    }
}

// Bootstrap plugin
add_action( 'plugins_loaded', [ Bootstrap::class, 'instance' ] );
add_action( 'plugins_loaded', [ function() { Bootstrap::instance()->run(); } ] );
