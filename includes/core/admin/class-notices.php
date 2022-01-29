<?php

namespace Apalodi\Core\Admin;

use Apalodi\Core\Traits\Singleton;

class Notices {
	use Singleton;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'load_script' ] );
		add_action( 'wp_ajax_apalodi_dismiss_admin_notice', [ $this, 'dismiss_admin_notice' ] );
	}

	/**
	 * Enqueue javascript and variables.
	 */
	public function load_script() {
		if ( is_customize_preview() ) {
			return;
		}

		$version = apalodi()->get_theme_info( 'version' );
		$admin_assets_dir = get_template_directory_uri() . '/admin/assets/';

		// Register JS.
		wp_register_script( 'apalodi-dismissible-notices', $admin_assets_dir . 'js/dismiss-notice.js', [ 'jquery', 'common' ], $version, true );

		// Enqueue JS.
		wp_enqueue_script( 'apalodi-dismissible-notices' );

		// Localize scripts.
		wp_localize_script(
			'apalodi-dismissible-notices',
			'apalodi_dismissible_notices',
			[
				'nonce' => wp_create_nonce( 'apalodi-dismissible-notice' ),
			]
		);
	}

	/**
	 * Handles Ajax request to persist notices dismissal.
	 */
	public function dismiss_admin_notice() {
		$notice = isset( $_POST['notice'] ) ? sanitize_text_field( wp_unslash( $_POST['notice'] ) ) : '';
		$expiration = isset( $_POST['expiration'] ) ? sanitize_text_field( wp_unslash( $_POST['expiration'] ) ) : '';

		check_ajax_referer( 'apalodi-dismissible-notice', 'nonce' );

		$expiration = is_numeric( $expiration ) ? $expiration : 1;
		$expiration = $expiration < 0 ? 1 : $expiration;
		$expiration = $expiration * DAY_IN_SECONDS;

		set_transient( "asona_admin_notice_{$notice}", '1', $expiration );
		wp_die();
	}

	/**
	 * Is admin notice dismissed.
	 *
	 * @param string $notice Notice option name.
	 *
	 * @return bool
	 */
	public function is_dismissed( $notice ) {
		return ( get_transient( "asona_admin_notice_{$notice}" ) );
	}

	/**
	 * Display admin notice.
	 *
	 * @param string $id Notice ID.
	 * @param string $type Notice type: info, warning, error.
	 * @param string $notice Notice to display.
	 * @param bool   $should_display Pass conditional logic when to show the notice.
	 *
	 * @return bool
	 */
	public function display( $id, $type, $notice, $should_display = true ) {
		if ( $this->is_dismissed( $notice ) ) {
			return;
		}

		if ( ! $should_display ) {
			return;
		}

		?>
		<div
			class="notice notice-<?php echo esc_attr( $type ); ?> is-dismissible"
			data-apalodi-dismissible="<?php echo esc_attr( $id ); ?>"
			data-expiration="0">
			<p><?php echo wp_kses_post( $notice ); ?></p>
		</div>
		<?php
	}
}
