<?php

/**
 * Display the pagination.
 *
 * @param object $query WP_Query.
 */
function apalodi_pagination( $query = false ) {
	$query = apalodi()->query()->get_current_query( $query );
	$load_more_args = apalodi_get_load_more_args( $query );

	if ( is_home() ) {
		$posts_per_page = get_option( 'posts_per_page' );
		$found_posts = $query->found_posts - 4;
		$query->max_num_pages = ceil( $found_posts / $posts_per_page );
	}

	$args = apply_filters( 'apalodi_pagination_args', [
		'type' => apalodi_get_theme_mod( 'blog_pagination', 'load-more' ),
		'max_num_pages' => $query->max_num_pages,
		'is_paged' => $query->is_paged,
		'load_more_args' => $load_more_args,
	], $query );

	apalodi()->template( 'pagination', $args );
}

/**
 * Display the term map.
 *
 * @param string $taxonomy Taxonomy slug.
 */
function apalodi_term_map( $taxonomy ) {
	apalodi()->template( 'term-map', [ 'taxonomy' => $taxonomy ] );
}
