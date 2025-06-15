<?php
namespace ReProgressBar\Admin;

use function add_action;
use function register_setting;
use function add_settings_section;
use function add_settings_field;
use function get_option;
use function absint;
use function esc_attr;
use function __;

final class Settings {
    private const OPTION_NAME  = 're_progress_bar_options';
    private const OPTION_GROUP = 're_progress_bar_group';
    private const PAGE_SLUG     = 're_progress_bar';

    public function run(): void {
        add_action('admin_init', [ $this, 'register_settings' ]);
    }

    public function register_settings(): void {
        register_setting(
            self::OPTION_GROUP,
            self::OPTION_NAME,
            [ $this, 'sanitize_options' ]
        );

        add_settings_section(
            're_pb_main',
            __( 'Ustawienia paska postępu', 're-progress-bar' ),
            '__return_false',
            self::PAGE_SLUG
        );

        add_settings_field(
            'progress_threshold',
            __( 'Próg wyświetlenia popupu (%)', 're-progress-bar' ),
            [ $this, 'render_threshold_field' ],
            self::PAGE_SLUG,
            're_pb_main'
        );

        add_settings_field(
            'delay_seconds',
            __( 'Opóźnienie przed popupem (s)', 're-progress-bar' ),
            [ $this, 'render_delay_field' ],
            self::PAGE_SLUG,
            're_pb_main'
        );
    }

    public function sanitize_options(array $input): array {
        $output = [];
        $output['progress_threshold'] = isset($input['progress_threshold'])
            ? absint($input['progress_threshold'])
            : 50;
        $output['delay_seconds'] = isset($input['delay_seconds'])
            ? absint($input['delay_seconds'])
            : 60;
        return $output;
    }

    public function render_threshold_field(): void {
        $options = get_option(self::OPTION_NAME, []);
        $value   = isset($options['progress_threshold'])
            ? absint($options['progress_threshold'])
            : 50;

        printf(
            '<input type="number" name="%1$s[progress_threshold]" value="%2$d" min="0" max="100" />',
            esc_attr(self::OPTION_NAME),
            $value
        );
    }

    public function render_delay_field(): void {
        $options = get_option(self::OPTION_NAME, []);
        $value   = isset($options['delay_seconds'])
            ? absint($options['delay_seconds'])
            : 60;

        printf(
            '<input type="number" name="%1$s[delay_seconds]" value="%2$d" min="0" />',
            esc_attr(self::OPTION_NAME),
            $value
        );
    }
}
