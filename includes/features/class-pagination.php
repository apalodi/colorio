<?php

namespace Apalodi\Features;

class Pagination {
	/**
	 * Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Get the load more pagination options.
	 *
	 * @return array Load more pagination options.
	 */
	public function get_load_more_options() {
		$columns = apalodi_get_post_columns();

		$options = [
			'excerpt' => has_apalodi_post_excerpt() ? 'true' : 'false',
			'thumbnail' => [
				'data-type' => 'grid-' . $columns . '-columns',
			],
		];

		return $options;
	}

	/**
	 * Get the load more pagination args.
	 *
	 * @param object $query WP_Query.
	 *
	 * @return array Load more args.
	 */
	public function get_load_more_args( $query ) {
		$posts_per_page = $query->get( 'posts_per_page' );
		$highlight_posts_per_page = (int) $query->get( 'apalodi_highlight_posts_per_page' );

		$posts_per_page = $posts_per_page - $highlight_posts_per_page;

		if ( $posts_per_page < 1 ) {
			$posts_per_page = 12;
		}

		if ( $posts_per_page > 24 ) {
			$posts_per_page = 24;
		}

		$found_posts = $query->found_posts;
		$offset = $posts_per_page + $highlight_posts_per_page;

		$query_args = [
			'posts_per_page' => $posts_per_page * 2,
			'offset' => $offset,
		];

		if ( ! is_home() ) {
			$query_args = wp_parse_args( $query_args, $query->query );
		}

		$options = apalodi_get_load_more_options();

		$args = [
			'found-posts' => $query->found_posts,
			'post-count' => $query->post_count,
			'query' => esc_attr( wp_json_encode( $query_args ) ),
			'options' => esc_attr( wp_json_encode( $options ) ),
		];

		return apply_filters( 'apalodi_load_more_args', $args, $query );
	}
}
