<?php

namespace Apalodi\Features;

class Highlight_Posts {
	/**
	 * Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Get the featued section tabs.
	 *
	 * @return array Featured sections.
	 */
	public function get_featured_sections() {
		$sections = apply_filters( 'apalodi_featured_sections', [] );
		ksort( $sections );
		return $sections;
	}

	/**
	 * Get the highlight posts query args.
	 *
	 * @return array Query args.
	 */
	public function get_highlight_posts_query_args() {
		$args = [];
		$style = get_theme_mod( 'blog_highlight_posts_type', 'modern' );

		switch ( $style ) {
			case 'classic':
				$args['apalodi_highlight_posts_per_page'] = 4;
				break;
			case 'modern':
			default:
				$args['apalodi_highlight_posts_per_page'] = 3;
				break;
		}

		return apply_filters( 'apalodi_highlight_posts_query_args', $args );
	}
}
