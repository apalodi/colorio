<?php

namespace Apalodi\Features;

use Apalodi\Core\Utils\Assets;

class Enqueue_Scripts {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_head', [ $this, 'output_check_js_script' ], 0 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Add script to head that changes html class no-js to js.
	 */
	public function output_check_js_script() {
		echo "<script>document.documentElement.classList.replace('no-js', 'js');</script>";
	}

	/**
	 * Register and Enqueue CSS and JS.
	 */
	public function enqueue_scripts() {
		$version = apalodi()->get_theme_info( 'version' );

		wp_register_style( 'asona-style', Assets::get_url( 'style.css' ), [] );

		wp_enqueue_style( 'asona-style' );

		// Deregister CSS.
		wp_deregister_style( 'wp-block-library' );

		// Register CSS.
		wp_register_style( 'asona-style', get_stylesheet_uri(), [], apalodi()->get_theme_info( 'version', true ) );
		wp_register_style( 'asona-parent', get_parent_theme_file_uri( 'style.css' ), [], $version );

		// Register JS.
		wp_register_script( 'asona-main', get_theme_file_uri( '/assets/js/main.js' ), [ 'jquery' ], $version, true );

		// Enqueue CSS.

		// If using a child theme, auto-load the parent theme style.
		if ( is_child_theme() ) {
			wp_enqueue_style( 'asona-parent' );
			wp_enqueue_style( 'asona-style' );
		} else {
			wp_enqueue_style( 'asona-style' );
		}

		// Enqueue JS.
		wp_enqueue_script( 'asona-main' );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Localize scripts.
		wp_localize_script(
			'asona-main',
			'asona_vars',
			[
				'rest_url' => esc_url_raw( rest_url() ),
				'ajax_url' => esc_url_raw( '' ),
			]
		);
	}
}
