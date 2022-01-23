<?php

namespace Apalodi\Features;

class Ajax {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'template_redirect', [ $this, 'do_ajax' ], 99 );
	}

	/**
	 * AJAX Event Handlers.
	 *
	 * Trigger right after_setup_theme because here only 4 SQL queries are executed and with
	 * later actions like template_redirect over 20 SQL queries are executed loading all posts.
	 *
	 * Note: user isn't loaded yet here, only option
	 *
	 * @return string Result of the action.
	 */
	public function do_ajax() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This isn't form submition.
		if ( empty( $_REQUEST['apalodi-ajax'] ) ) {
			return;
		}

		$this->ajax_headers();

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This isn't form submition.
		$action = sanitize_text_field( wp_unslash( $_REQUEST['apalodi-ajax'] ) );
		$action = str_replace( '-', '_', $action );

		// If no action is registered, return a Bad Request response.
		if ( ! has_action( 'apalodi_ajax_' . $action ) ) {
			wp_die( '', 400 );
		}

		do_action( 'apalodi_ajax_' . $action );
		do_action( 'apalodi_ajax_after' );
		wp_die();
	}

	/**
	 * Send a JSON response back to an Ajax request.
	 *
	 * @param mixed $response Variable (usually an array or object) to encode as JSON.
	 * @param int   $status_code The HTTP status code to output.
	 */
	public function send_json( $response, $status_code = null ) {
		header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );

		if ( null !== $status_code ) {
			status_header( $status_code );
		}

		echo wp_json_encode( $response );
	}

	/**
	 * Send headers for Ajax Requests.
	 */
	public function ajax_headers() {
		apalodi_maybe_define_constant( 'DOING_AJAX', true );

		send_origin_headers();

		header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
		header( 'X-Robots-Tag: noindex' );

		send_nosniff_header();
		nocache_headers();

		apalodi_maybe_define_constant( 'DONOTCACHEPAGE', true );
		apalodi_maybe_define_constant( 'DONOTCACHEOBJECT', true );
		apalodi_maybe_define_constant( 'DONOTCACHEDB', true );

		status_header( 200 );
	}

	/**
	 * Get Ajax url.
	 *
	 * @param string $action Action name.
	 *
	 * @return string Ajax url.
	 */
	public function get_ajax_url( $action = '%%action%%' ) {
		return add_query_arg( 'apalodi-ajax', $action, home_url( '/', 'relative' ) );
	}
}
