<?php
namespace ReProgressBar\Admin;

final class Settings {

	const OPTION = 're_progress_bar_settings';

	/** Rejestracja sekcji i pól Settings API. */
	public static function register(): void {

		register_setting(
			're_progress_bar_group',
			self::OPTION,
			[ 'type' => 'array', 'sanitize_callback' => [ self::class, 'sanitize' ] ]
		);

		add_settings_section(
			'repb_main',
			__( 'Progress Bar Settings', 're-progress-bar' ),
			'__return_false',
			're_progress_bar'
		);

		add_settings_field(
			'color',
			__( 'Bar Color', 're-progress-bar' ),
			[ self::class, 'field_color' ],
			're_progress_bar',
			'repb_main'
		);
	}

	public static function field_color(): void {
		$opts  = get_option( self::OPTION, [] );
		$color = esc_attr( $opts['color'] ?? '#ff0000' );
		printf(
			'<input type="text" name="%1$s[color]" value="%2$s" class="regular-text wp-color-picker-field" data-default-color="#ff0000" />',
			esc_attr( self::OPTION ),
			$color
		);
	}

	public static function sanitize( array $input ): array {
		return [ 'color' => sanitize_hex_color( $input['color'] ?? '#ff0000' ) ];
	}
}
