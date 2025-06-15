<?php
// Fallback PSR-4 autoloader (gdy Composer nieużywany).
spl_autoload_register(
	static function ( string $class ) {
		if ( 0 !== strpos( $class, 'ReProgressBar\\' ) ) {
			return;
		}
		$path = REPB_PATH . 'includes/' . str_replace( ['ReProgressBar\\', '\\'], ['', '/'], $class ) . '.php';
		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
);