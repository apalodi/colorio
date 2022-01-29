<?php

namespace Apalodi\Features;

class Logo {
	/**
	 * Constructor.
	 */
	public function __construct() {
		apalodi()::macro( 'get_logo', [ $this, 'get_logo' ] );

		$this->add_customize_settings();
	}

	/**
	 * Register settings for logos.
	 */
	private function add_customize_settings() {
		apalodi()->customize()->add_field( 'dark_logo', [
			'setting' => [
				'default' => '',
				'transport' => 'postMessage',
				'sanitize_callback' => 'absint',
			],
			'control' => [
				'type' => 'cropped_image',
				'label' => esc_html__( 'Logo for Dark Header (optional)', 'asona' ),
				'section' => 'title_tagline',
				'priority' => 8,
				'flex_width'  => true,
				'flex_height' => false,
				'width' => 300,
				'height' => 100,
			],
		]);
	}

	/**
	 * Returns true if there is selected logo.
	 *
	 * @param string $type Logo Type.
	 *
	 * @return bool
	 */
	public function has_logo( $type ) {
		return $this->get_logo( $type ) ? true : false;
	}

	/**
	 * Get the logo url.
	 *
	 * @param string $type Logo Type.
	 *
	 * @return string|bool Logo url or false.
	 */
	public function get_logo( $type ) {

		$logo = get_theme_mod( $type . '_logo', '' );
		$logo_url = '';

		if ( '' === $logo && 'custom' === $type ) {
			$logo_url = get_template_directory_uri() . '/assets/img/logo.svg';
		} elseif ( '' === get_theme_mod( 'custom_logo', '' ) && 'dark' === $type ) {
			$logo_url = get_template_directory_uri() . '/assets/img/logo-light.svg';
		} else {
			$url = wp_get_attachment_image_src( $logo, 'full' );
			if ( $url ) {
				$logo_url = $url[0];
			}
		}

		if ( '' === $logo_url ) {
			return false;
		} else {
			return $logo_url;
		}
	}
}
