<?php

namespace Apalodi\Features;

class Social {
	/**
	 * Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Get the social icons.
	 *
	 * @return array Social icons.
	 */
	public function get_social_icons() {
		$social_icons = get_theme_mod( 'social_icons', [] );
		return apply_filters( 'apalodi_social_icons', $social_icons );
	}

	/**
	 * Returns true if there are social icons selected.
	 *
	 * @return bool
	 */
	public function has_social_icons() {
		$social_buttons = $this->get_social_icons();
		return ( $social_buttons ) ? true : false;
	}
}
