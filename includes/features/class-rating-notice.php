<?php

namespace Apalodi\Features;

class Rating_Notice {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'after_switch_theme', [ $this, 'on_theme_activation' ] );
		add_action( 'switch_theme', [ $this, 'on_theme_deactivation' ] );
		add_action( 'admin_notices', [ $this, 'theme_rating_admin_notice' ] );
	}

	/**
	 * Save default option on theme activation.
	 */
	public function on_theme_activation() {
		add_option( 'asona_theme_activated', time() );
	}

	/**
	 * Delete theme option on theme deactivation.
	 */
	public function on_theme_deactivation() {
		delete_option( 'asona_theme_activated' );
	}

	/**
	 * Check has 3 days passed after the theme is activated.
	 *
	 * @return bool
	 */
	public function has_three_days_passed_after_activation() {
		$activated_on = get_option( 'asona_theme_activated', false );

		if ( false === $activated_on ) {
			$activated_on = time();
			add_option( 'asona_theme_activated', $activated_on );
			return false;
		}

		$check = strtotime( '-3 day' );

		if ( $check < $activated_on ) {
			return false;
		}

		return true;
	}

	/**
	 * Display notice for theme rating.
	 */
	public function theme_rating_admin_notice() {
		apalodi()->admin_notice()->display(
			'theme_rating',
			'info',
			// Translators: %s is the link to themeforest.
			sprintf( __( 'If you like the Asona theme please consider going to %s and rate it by selecting 5 stars.', 'asona' ), '<a href="https://themeforest.net/downloads" target="_blank" rel="noopener">https://themeforest.net/downloads</a>' ),
			$this->has_three_days_passed_after_activation()
		);
	}
}
