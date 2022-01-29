<?php

namespace Apalodi\Features;

class Required_Plugins {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'tgmpa_register', [ $this, 'register_required_plugins' ] );
	}

	/**
	 * Register the required plugins for this theme.
	 */
	public function register_required_plugins() {
		$plugins = [
			[
				'name' => esc_html__( 'One Click Demo Import', 'asona' ),
				'slug' => 'one-click-demo-import',
			],
			[
				'name' => esc_html__( 'Contact Form 7', 'asona' ),
				'slug' => 'contact-form-7',
			],
			[
				'name' => esc_html__( 'MailChimp for WordPress', 'asona' ),
				'slug' => 'mailchimp-for-wp',
			],
			[
				'name' => esc_html__( 'AP Ads', 'asona' ),
				'slug' => 'ap-ads',
				'source' => 'ap-ads.zip',
				'version' => '1.0.0',
			],
			[
				'name' => esc_html__( 'AP Popular Posts', 'asona' ),
				'slug' => 'ap-popular-posts',
				'source' => 'ap-popular-posts.zip',
				'version' => '1.2.1',
			],
			[
				'name' => esc_html__( 'AP Featured Posts', 'asona' ),
				'slug' => 'ap-featured-posts',
				'source' => 'ap-featured-posts.zip',
				'version' => '1.1.0',
			],
			[
				'name' => esc_html__( 'AP Share Buttons', 'asona' ),
				'slug' => 'ap-share-buttons',
				'source' => 'ap-share-buttons.zip',
				'version' => '1.0.1',
			],
			[
				'name' => esc_html__( 'AP Performance', 'asona' ),
				'slug' => 'ap-performance',
				'source' => 'ap-performance.zip',
				'version' => '1.2.0',
			],
			[
				'name' => esc_html__( 'Envato Market', 'asona' ),
				'slug' => 'envato-market',
				'source' => 'envato-market.zip',
				'version' => '2.0.5',
			],
		];

		$id = apalodi()->get_theme_identifier();

		$config = [
			'id'  => $id,
			'default_path' => get_template_directory() . '/plugins-bundled/',
			'menu' => "{$id}-install-plugins",
			'capability' => 'edit_theme_options',
		];

		\tgmpa( $plugins, $config );
	}
}
