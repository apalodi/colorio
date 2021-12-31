<?php

namespace Apalodi\Features;

class Editor {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'add_editor_features' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'add_block_editor_styles' ] );
		// add_filter( 'pre_http_request', 'eksell_pre_http_request_block_editor_customizer_styles', 10, 3 );

		// add_editor_style( array(
		// './assets/css/editor.css',
		// './assets/css/blocks.css',
		// './assets/css/shared.css',
		// tove_get_google_fonts_url()
		// ) );

		// return apply_filters( 'tove_google_fonts_url', esc_url_raw( 'https://fonts.googleapis.com/css2?' . implode( '&', $font_family_urls ) . '&display=swap' ) );

		// // This URL is filtered by eksell_pre_http_request_block_editor_customizer_styles to load dynamic CSS as inline styles.
		// $inline_styles_url = 'https://eksell-inline-editor-styles';

		// // Build a filterable array of the editor styles to load.
		// $eksell_editor_styles = apply_filters( 'eksell_editor_styles', array(
		// 'assets/css/eksell-editor-styles.css',
		// $google_fonts_url,
		// $inline_styles_url
		// ) );
	}

	/**
	 * Registers support for editor features.
	 */
	public function add_editor_features() {
		// Add support for wide alignment.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add support for custom color scheme.
		add_theme_support( 'editor-color-palette', [
			[
				'name' => esc_html__( 'Accent Color', 'asona' ),
				'slug' => 'accent',
				'color' => get_theme_mod( 'accent_color', '#101cbc' ),
			],
		] );

		// Add support for custom font sizes.
		add_theme_support( 'editor-font-sizes', [
			[
				'name' => esc_html_x( 'Small', 'editor font size', 'asona' ),
				'size' => 15,
				'slug' => 'small',
			],
			[
				'name' => esc_html_x( 'Normal', 'editor font size', 'asona' ),
				'size' => 18,
				'slug' => 'normal',
			],
			[
				'name' => esc_html_x( 'Medium', 'editor font size', 'asona' ),
				'size' => 20,
				'slug' => 'medium',
			],
			[
				'name' => esc_html_x( 'Large', 'editor font size', 'asona' ),
				'size' => 24,
				'slug' => 'large',
			],
			[
				'name' => esc_html_x( 'Huge', 'editor font size', 'asona' ),
				'size' => 32,
				'slug' => 'huge',
			],
		] );
	}

	/**
	 * Enqueue styles for editor.
	 *
	 * @since   1.0
	 * @access  public
	 */
	public function add_block_editor_styles() {

		$version = apalodi_get_theme_info( 'version' );
		$gfonts_url = esc_url_raw( apalodi_get_gfonts_url() );

		wp_enqueue_style( 'asona-editor-fonts', $gfonts_url, [], $version );
		wp_add_inline_style( 'asona-editor-fonts', apalodi_custom_colors_css() );
	}

	public function eksell_pre_http_request_block_editor_customizer_styles( $response, $parsed_args, $url ) {
		if ( $url === 'https://eksell-inline-editor-styles' ) {
			$response = [
				'body' => Eksell_Custom_CSS::get_customizer_css( 'editor' ),
				'headers' => new Requests_Utility_CaseInsensitiveDictionary(),
				'response' => [
					'code' => 200,
					'message' => 'OK',
				],
				'cookies' => [],
				'filename' => null,
			];
		}

		return $response;
	}
}
