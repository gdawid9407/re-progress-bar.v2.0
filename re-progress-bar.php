<?php
/**
 * Plugin Name:       Re Progress Bar
 * Description:       Reading progress bar with popup and article recommendations.
 * Version:           1.0.0
 * Author:            Dawid Gołis
 * License:           GPL-2.0-or-later
 * Text Domain:       re-progress-bar
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

/**
 * Załadowanie autoloadera (Composer lub własny) i uruchomienie bootstrapu.
 */
function re_progress_bar_bootstrap(): void {
    // Ścieżka do autoloadera Composera
    $composer_autoload = __DIR__ . '/vendor/autoload.php';

    if ( file_exists( $composer_autoload ) ) {
        require_once $composer_autoload;
    } else {
        // Fallback PSR-4 autoloader
        require_once __DIR__ . '/includes/autoload.php';
    }

    // Uruchomienie głównej klasy bootstrap, jeśli istnieje
    if ( class_exists( \ReProgressBar\Bootstrap::class ) ) {
        \ReProgressBar\Bootstrap::instance()->run();
    }
}

// Rejestracja na hooku plugins_loaded, priorytet 0 aby uruchomić jak najwcześniej
add_action( 'plugins_loaded', 're_progress_bar_bootstrap', 0 );