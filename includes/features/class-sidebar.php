<?php

namespace Apalodi\Features;

class Sidebar {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'body_class', [ $this, 'set_body_classes' ] );
		add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @param array $classes Classes for the element.
	 *
	 * @return array $classes
	 */
	public function set_body_classes( $classes ) {
		// Add class if sidebar is used in comments.
		if ( is_active_sidebar( 'sidebar-comments' ) ) {
			$classes[] = 'has-comments-sidebar';
		}

		// Add class if sidebar is used in posts.
		if ( is_active_sidebar( 'sidebar-posts-sidebar' ) ) {
			$classes[] = 'has-post-sidebar';
		}

		// Add class if sidebar is used in blog.
		if ( is_active_sidebar( 'sidebar-blog' ) ) {
			$classes[] = 'has-blog-sidebar';
		}

		return array_filter( $classes );
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
