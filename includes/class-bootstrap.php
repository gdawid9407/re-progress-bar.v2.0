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

/**
 * Bootstraps all plugin modules.
 */
final class Bootstrap {
    /**
     * Singleton instance.
     *
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * Registered modules.
     *
     * @var array<object>
     */
    private array $modules = [];

    /**
     * Register modules.
     * Keep heavy logic out of constructor.
     */
    private function __construct() {
        $this->modules = [
            'admin_settings'   => new Admin\Settings(),
            'progress_tracker' => new Frontend\ProgressTracker(),
        ];
    }

    /**
     * Get singleton instance.
     */
    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialise plugin: load translations, register routes, enqueue assets, then modules.
     */
    public function run(): void {
        add_action('init', [ $this, 'loadTextDomain' ]);
        add_action('rest_api_init', [ RestController::class, 'registerRoutes' ]);
        add_action('wp_enqueue_scripts', [ Assets::class, 'enqueue' ]);
        add_action('admin_enqueue_scripts', [ AdminAssets::class, 'enqueue' ]);

        foreach ( $this->modules as $module ) {
            if ( method_exists( $module, 'run' ) ) {
                $module->run();
            }
        }
    }

    /**
     * Load plugin textdomain for translations.
     */
    public function loadTextDomain(): void {
        load_plugin_textdomain(
            're-progress-bar',
            false,
            dirname( plugin_basename( __DIR__ ) ) . '/languages'
        );
    }

    /** Prevent cloning. */
    private function __clone() {}

    /** Prevent unserialising. */
    public function __wakeup() {
        throw new \Exception( 'Cannot unserialise Bootstrap singleton.' );
    }
}
