<?php

namespace Apalodi\Features;

class Fonts {
	/**
	 * Fonts source.
	 *
	 * @var string
	 */
	protected $source = 'google';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_filter( 'wp_head', [ $this, 'preload_fonts' ], 5 );
		add_filter( 'wp_resource_hints', [ $this, 'add_preconnect' ], 10, 2 );
	}

	/**
	 * Get fonts source type.
	 *
	 * @return string Fonts Source.
	 */
	private function get_fonts_source() {
		return apply_filters( 'apalodi_fonts_source', $this->source );
	}

	/**
	 * Register and enqueue fonts.
	 */
	public function enqueue_scripts() {
		$version = apalodi()->get_theme_info( 'version' );
		$fonts_url = $this->get_fonts_url();

		if ( $fonts_url ) {
			wp_register_style( 'asona-fonts', esc_url_raw( $fonts_url ), [], $version );
			wp_enqueue_style( 'asona-fonts' );
		}
	}

	/**
	 * Get fonts URL.
	 *
	 * @return string Fonts URL.
	 */
	private function get_fonts_url() {
		if ( 'google' === $this->get_fonts_source() ) {
			return $this->get_google_fonts_url();
		}

		return false;
	}

	/**
	 * Get Google Fonts URL.
	 *
	 * @return string Google Fonts URL.
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
	 * Add preload for Google Fonts.
	 */
	public function preload_fonts() {
		$fonts_url = $this->get_fonts_url();

		if ( $fonts_url ) {
			echo "<link rel='preload' as='style' href='" . esc_url_raw( $fonts_url ) . "' />\n";
		}
	}

	/**
	 * Add preconnect for fonts.
	 *
	 * @param array  $urls URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed.
	 *
	 * @return array URLs to print for resource hints.
	 */
	public function add_preconnect( $urls, $relation_type ) {
		if (
			wp_style_is( 'asona-fonts', 'queue' )
			&& 'preconnect' === $relation_type
			&& 'google' === $this->get_fonts_source()
		) {
			$urls[] = [
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			];
		}

		return $urls;
	}
}
