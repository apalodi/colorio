<?php

namespace Apalodi\Features;

class Setup {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'set_content_width' ], 0 );
		add_action( 'after_setup_theme', [ $this, 'setup_theme' ] );

		add_action( 'wp_head', [ $this, 'output_meta_theme_color' ] );

		add_filter( 'http_request_args', [ $this, 'dont_update_theme' ], 5, 2 );
	}

	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * Priority 0 to make it available to lower priority callbacks.
	 */
	public function set_content_width() {
		$GLOBALS['content_width'] = apply_filters( 'apalodi_content_width', 958 );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	public function setup_theme() {
		/**
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'colorio', get_template_directory() . '/languages' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails.
		add_theme_support( 'post-thumbnails' );

		// Add excerpts to pages.
		add_post_type_support( 'page', 'excerpt' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add theme support for Custom Logo.
		add_theme_support(
			'custom-logo',
			[
				'width' => 300,
				'height' => 80,
				'flex-width' => true,
				'flex-height' => true,
			]
		);

		/**
		 * Output valid HTML5 for search form, comment form, comments,
		 * gallery, captions, styles and scripts.
		 */
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			]
		);
	}

	/**
	 * Add meta tag to head for theme color.
	 */
	public function output_meta_theme_color() {
		$color = get_theme_mod( 'theme_color', '#ffffff' );
		echo "<meta name='theme-color' content='" . esc_attr( $color ) . "'>\n";
	}

	/**
	 * If there is a theme in the repo with the same name prevent WP from prompting an update.
	 *
	 * @param array  $args Existing request arguments.
	 * @param string $url Request URL.
	 *
	 * @return array Amended request arguments.
	 */
	public function dont_update_theme( $args, $url ) {
		if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
			// Not a theme update request. Bail immediately.
			return $args;
		}

		$themes = json_decode( $args['body']['themes'] );
		$child  = get_option( 'stylesheet' );
		unset( $themes->themes->$child );
		$args['body']['themes'] = wp_json_encode( $themes );

		return $args;
	}
}
