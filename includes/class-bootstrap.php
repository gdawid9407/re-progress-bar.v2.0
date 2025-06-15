<?php
/**
 * Main bootstrap for Re Progress Bar plugin.
 *
 * @package ReProgressBar
 */

namespace ReProgressBar;

defined( 'ABSPATH' ) || exit;

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
        // Add core plugin modules here.
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
     * Initialise registered modules.
     */
    public function run(): void {
        foreach ( $this->modules as $module ) {
            if ( method_exists( $module, 'run' ) ) {
                $module->run();
            }
        }
    }

    /** Prevent cloning. */
    private function __clone() {}

    /** Prevent unserialising. */
    public function __wakeup() {
        throw new \Exception( 'Cannot unserialise Bootstrap singleton.' );
    }
}


