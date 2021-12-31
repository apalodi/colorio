<?php

namespace Apalodi\Features;

class Google_Fonts {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_filter( 'wp_head', [ $this, 'preload_google_fonts' ], 5 );
		add_filter( 'wp_resource_hints', [ $this, 'resource_hints' ], 10, 2 );
	}

	/**
	 * Get Google Fonts URL.
	 *
	 * @return string $fonts_url Google Fonts URL.
	 */
	private function get_google_fonts_url() {
		$fonts = [
			'Encode+Sans+Semi+Condensed:600,700',
			'Jost:400,400i,500,500i,600,600i',
		];

		$fonts_url = add_query_arg( [
			'family' => implode( '%7C', $fonts ),
		], 'https://fonts.googleapis.com/css' );

		return apply_filters( 'apalodi_google_fonts_url', $fonts_url );
	}

	/**
	 * Register and Enqueue Google Fonts.
	 */
	public function enqueue_scripts() {
		$gfonts_url = $this->get_google_fonts_url();

		wp_register_style( 'asona-gfonts', esc_url_raw( $gfonts_url ), [], null );
		wp_enqueue_style( 'asona-gfonts' );
	}

	/**
	 * Add preload for Google Fonts.
	 */
	public function preload_google_fonts() {
		$gfonts_url = $this->get_google_fonts_url();
		echo "<link rel='preload' as='style' href='" . esc_url_raw( $gfonts_url ) . "' />\n";
	}

	/**
	 * Add preconnect for Google Fonts.
	 *
	 * @param array  $urls URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed.
	 *
	 * @return array $urls URLs to print for resource hints.
	 */
	public function resource_hints( $urls, $relation_type ) {
		if ( wp_style_is( 'asona-gfonts', 'queue' ) && 'preconnect' === $relation_type ) {
			$urls[] = [
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			];
		}

		return $urls;
	}
}
