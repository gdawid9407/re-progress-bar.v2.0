<?php
// File: includes/api/class-rest-controller.php

namespace ReProgressBar\API;

use WP_REST_Server;
use ReProgressBar\Frontend\ProgressTracker;

defined( 'ABSPATH' ) || exit;

/**
 * Registers REST API routes for the Re Progress Bar plugin.
 */
class RestController {

    /**
     * Register all REST API routes.
     */
    public static function registerRoutes(): void {
        register_rest_route(
            're-progress/v1',
            '/progress-data',
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ ProgressTracker::class, 'get_progress_data' ],
                'permission_callback' => '__return_true',
            ]
        );
    }
}
