<?php

namespace Apalodi\Features;

class Customize {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'add_customize_features' ] );
	}

	/**
	 * Registers support for customize features.
	 */
	public function add_customize_features() {
		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}
}
