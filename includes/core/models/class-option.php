<?php

namespace Apalodi\Core\Models;

class Option {
	/**
	 * Constructor.
	 */
	public function __construct() {
	}

	/**
	 * Retrieve theme modification value for the current theme.
	 *
	 * @param string $name Theme modification name.
	 * @param mixed  $default Default value.
	 *
	 * @return mixed Value.
	 */
	public function get_theme_mod( $name, $default = false ) {
		$mods = get_theme_mods();

		if ( isset( $mods[ $name ] ) ) {
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Using default wp hook.
			return apply_filters( "theme_mod_{$name}", $mods[ $name ] );
		}

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Using default wp hook.
		return apply_filters( "theme_mod_{$name}", $default );
	}

	/**
	 * Get sticky posts.
	 *
	 * @return array Sticky posts.
	 */
	public function get_sticky_posts() {
		return get_option( 'sticky_posts' );
	}

	/**
	 * Get number of sticky posts.
	 *
	 * @return int Number of sticky posts.
	 */
	public function count_sticky_posts() {
		return count( $this->get_sticky_posts() );
	}
}
