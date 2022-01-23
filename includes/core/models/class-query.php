<?php

namespace Apalodi\Core\Models;

class Query {
	/**
	 * Constructor.
	 */
	public function __construct() {
	}

	/**
	 * Get paged value for pagination.
	 *
	 * Looking for get_query_var('paged') and get_query_var('page') in case shortcodes are used
	 * on static front page which has problems returning the right paged number.
	 *
	 * @return int Current page number.
	 */
	public function get_paged() {
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		return intval( $paged );
	}

	/**
	 * Get current query object.
	 *
	 * @param object $query WP_Query object.
	 *
	 * @return object Current WP_Query object.
	 */
	public function get_current_query( $query = false ) {
		if ( ! $query ) {
			global $wp_query;
			$query = $wp_query;
		}

		return $query;
	}

	/**
	 * Get current loop index.
	 *
	 * @param object $query WP_Query object.
	 *
	 * @return int Current loop index.
	 */
	public function get_loop_index( $query = false ) {
		$query = $this->get_current_query( $query );
		return $query->current_post + 1;
	}

	/**
	 * Get the number of found posts.
	 *
	 * @param object $query WP_Query object.
	 *
	 * @return int Number of found posts.
	 */
	public function get_found_posts( $query = false ) {
		$query = $this->get_current_query( $query );
		return $query->found_posts;
	}

	/**
	 * Get the number of loaded posts.
	 *
	 * @param object $query WP_Query object.
	 *
	 * @return int Number of loaded posts.
	 */
	public function get_post_count( $query = false ) {
		$query = $this->get_current_query( $query );
		return $query->post_count;
	}

	/**
	 * Get the maximum number of pages.
	 *
	 * @param object $query WP_Query object.
	 *
	 * @return int Max number of pages.
	 */
	public function get_max_num_pages( $query = false ) {
		$query = $this->get_current_query( $query );
		return $query->max_num_pages;
	}

	/**
	 * Get the number of posts per page.
	 *
	 * @return int Number of posts per page.
	 */
	public function get_posts_per_page() {
		return (int) get_option( 'posts_per_page' );
	}

	/**
	 * Get the number of highlight posts per page.
	 *
	 * @param object $query WP_Query object.
	 *
	 * @return int Number of highlight posts per page.
	 */
	public function get_highlight_posts_per_page( $query = false ) {
		$query = $this->get_current_query( $query );
		return (int) $query->get( 'apalodi_highlight_posts_per_page' );
	}

	/**
	 * Get offset for the query pagination.
	 *
	 * @param array $featured_ids Featured IDs.
	 * @param array $post_ids Post IDs.
	 * @param int   $posts_per_page Posts per page.
	 *
	 * @return int Offset.
	 */
	public function get_offset( $featured_ids, $post_ids, $posts_per_page ) {
		$kicked_out_posts = array_values( array_unique( array_merge( $featured_ids, $post_ids ) ) );
		$kicked_out_posts = array_slice( $kicked_out_posts, $posts_per_page );

		if ( $kicked_out_posts ) {
			$offset = array_search( $kicked_out_posts[0], $post_ids, true );
		} else {
			$offset = $posts_per_page;
		}

		return $offset;
	}

	/**
	 * Returns true when section loop is within constraints and isn't paged.
	 *
	 * @param int         $posts_per_page Posts per page.
	 * @param object|bool $query WP_Query object.
	 *
	 * @return bool
	 */
	public function section_have_posts( $posts_per_page, $query = false ) {
		$query = $this->get_current_query();
		$index = $this->get_loop_index( $query );

		if ( $query->is_paged() ) {
			return false;
		}

		return ( $index < $posts_per_page && $index < $query->post_count ) ? true : false;
	}

	/**
	 * Returns true when highlight loop is within constraints and it isn't paged.
	 *
	 * @param object $query WP_Query object.
	 *
	 * @return bool
	 */
	public function highlight_have_posts( $query = false ) {
		$query = $this->get_current_query( $query );

		if ( ! $query->get( 'apalodi_highlight_posts_per_page' ) || $query->is_paged() ) {
			return false;
		}

		$index = $query->current_post + 1;
		$posts_per_page = $query->get( 'apalodi_highlight_posts_per_page' );

		return ( $index < $posts_per_page && $index < $query->post_count ) ? true : false;
	}

	/**
	 * Returns true if it's posts archives.
	 *
	 * @return bool
	 */
	public function is_posts_archive() {
		return ( is_category() || is_tag() || is_author() || is_date() );
	}

	/**
	 * Returns true if it's main blog page or an archive.
	 *
	 * @return bool
	 */
	public function is_posts_page() {
		return ( is_home() || $this->is_posts_archive() );
	}

	/**
	 * Returns true if it's posts paged page.
	 *
	 * @return bool
	 */
	public function is_posts_page_paged() {
		return ( $this->is_posts_page() && is_paged() );
	}

	/**
	 * Returns true if it's blog archive paged page.
	 *
	 * @return bool
	 */
	public function is_posts_archive_paged() {
		return ( $this->is_posts_archive() && is_paged() );
	}
}
