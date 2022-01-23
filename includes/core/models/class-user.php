<?php

namespace Apalodi\Core\Models;

class User {
	/**
	 * Constructor.
	 */
	public function __construct() {
	}

	/**
	 * Get number of posts from author.
	 *
	 * @param int|bool     $user_id User ID.
	 * @param array|string $post_type Single post type or array of post types to count the number of posts for.
	 * @param bool         $public_only Whether to only return counts for public posts.
	 *
	 * @return int Number of posts from author.
	 */
	public function count_user_posts( $user_id = false, $post_type = 'post', $public_only = true ) {
		if ( ! $user_id ) {
			$user_id = get_the_author_meta( 'ID' );
		}

		return (int) count_user_posts( $user_id, $post_type, $public_only );
	}
}
