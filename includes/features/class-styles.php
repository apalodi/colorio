<?php

namespace Apalodi\Features;

class Styles {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->add_customize_settings();
	}

	/**
	 * Register settings for styles.
	 */
	private function add_customize_settings() {
		apalodi()->customize()->add_field( 'accent_color', [
			'setting' => [
				'default' => '#101cbc',
				'transport' => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'css_property' => '--colorio--accent-color',
				// 'css_property_callback' => [ apalodi()->customize(), 'transform_hex_to_hsl_color' ],
			],
			'control' => [
				'type' => 'color',
				'label' => esc_html__( 'Accent Color', 'colorio' ),
				'section' => 'colors',
				'priority' => 5,
			],
		]);
	}
}
