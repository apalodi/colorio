<?php

namespace Apalodi\Features;

class Sidebar {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
	}

	/**
	 * Register widget areas.
	 */
	public function register_sidebars() {
		register_sidebar(
			[
				'id' => 'sidebar-posts',
				'name' => esc_html__( 'Posts Widget Area', 'asona' ),
				'description' => esc_html__( 'Add widgets here to appear below posts content.', 'asona' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' => '</div></aside>',
				'before_title' => '<h3 class="widget-title meta-title"><span>',
				'after_title' => '</span></h3>',
			]
		);
	}
}
