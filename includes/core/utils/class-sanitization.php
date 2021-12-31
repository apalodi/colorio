<?php

namespace Apalodi\Core\Utils;

trait Sanitization {
	/**
	 * Checkbox sanitization callback.
	 *
	 * @param bool $checked Whether the checkbox is checked.
	 *
	 * @return bool Whether the checkbox is checked.
	 */
	public function sanitize_checkbox( $checked ) {
		return $checked ? true : false;
	}

	/**
	 * CSS sanitization callback.
	 *
	 * @param string $css CSS to sanitize.
	 *
	 * @return string  Sanitized CSS.
	 */
	public function sanitize_css( $css ) {
		return wp_strip_all_tags( $css );
	}

	/**
	 * Drop-down Pages sanitization callback.
	 *
	 * @param int    $page_id    Page ID.
	 * @param object $setting Setting instance.
	 *
	 * @return int|string Page ID if the page is published; otherwise, the setting default.
	 */
	public function sanitize_dropdown_pages( $page_id, $setting ) {
		// Ensure $input is an absolute integer.
		$page_id = absint( $page_id );

		// If $page_id is an ID of a published page, return it; otherwise, return the default.
		return ( 'publish' === get_post_status( $page_id ) ? $page_id : $setting->default );
	}

	/**
	 * Email sanitization callback.
	 *
	 * @param string $email   Email address to sanitize.
	 * @param object $setting Setting instance.
	 *
	 * @return string  The sanitized email if not null; otherwise, the setting default.
	 */
	public function sanitize_email( $email, $setting ) {
		// Strips out all characters that are not allowable in an email address.
		$email = sanitize_email( $email );

		// If $email is a valid email, return it; otherwise, return the default.
		return ( ! is_null( $email ) ? $email : $setting->default );
	}

	/**
	 * HEX Color sanitization callback.
	 *
	 * @param string $hex_color HEX color to sanitize.
	 * @param object $setting   Setting instance.
	 *
	 * @return string  The sanitized hex color if not null; otherwise, the setting default.
	 */
	public function sanitize_hex_color( $hex_color, $setting ) {
		// Sanitize $input as a hex value without the hash prefix.
		$hex_color = sanitize_hex_color( $hex_color );

		// If $input is a valid hex value, return it; otherwise, return the default.
		return ( ! is_null( $hex_color ) ? $hex_color : $setting->default );
	}

	/**
	 * HTML sanitization callback.
	 *
	 * @param string $html HTML to sanitize.
	 *
	 * @return string  Sanitized HTML.
	 */
	public function sanitize_html( $html ) {
		return wp_filter_post_kses( $html );
	}

	/**
	 * Image sanitization callback.
	 *
	 * @param string $image   Image filename.
	 * @param object $setting Setting instance.
	 *
	 * @return string  The image filename if the extension is allowed; otherwise, the setting default.
	 */
	public function sanitize_image( $image, $setting ) {
		/*
		* Array of valid image file types.
		*
		* The array includes image mime types that are included in wp_get_mime_types()
		*/
		$mimes = [
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tif|tiff'     => 'image/tiff',
			'ico'          => 'image/x-icon',
		];

		// Return an array with file extension and mime_type.
		$file = wp_check_filetype( $image, $mimes );

		// If $image has a valid mime_type, return it; otherwise, return the default.
		return ( $file['ext'] ? $image : $setting->default );
	}

	/**
	 * No-HTML sanitization callback.
	 *
	 * @param string $nohtml The no-HTML content to sanitize.
	 *
	 * @return string  Sanitized no-HTML content.
	 */
	public function sanitize_nohtml( $nohtml ) {
		return wp_filter_nohtml_kses( $nohtml );
	}

	/**
	 * Number sanitization callback.
	 *
	 * @param int    $number  Number to sanitize.
	 * @param object $setting Setting instance.
	 *
	 * @return int     Sanitized number; otherwise, the setting default.
	 */
	public function sanitize_number_absint( $number, $setting ) {
		// Ensure $number is an absolute integer (whole number, zero or greater).
		$number = absint( $number );

		// If the input is an absolute integer, return it; otherwise, return the default.
		return ( $number ? $number : $setting->default );
	}

	/**
	 * Number Range sanitization callback.
	 *
	 * @param int    $number  Number to check within the numeric range defined by the setting.
	 * @param object $setting Setting instance.
	 *
	 * @return int     The number if falls within the defined range; otherwise, the setting default.
	 */
	public function sanitize_number_range( $number, $setting ) {
		// Ensure input is an absolute integer.
		$number = absint( $number );

		// Get the input attributes associated with the setting.
		$atts = $setting->manager->get_control( $setting->id )->input_attrs;

		// Get minimum number in the range.
		$min = ( isset( $atts['min'] ) ? $atts['min'] : $number );

		// Get maximum number in the range.
		$max = ( isset( $atts['max'] ) ? $atts['max'] : $number );

		// Get step.
		$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );

		// If the number is within the valid range, return it; otherwise, return the default.
		return ( $min <= $number && $number <= $max && is_int( $number / $step ) ? $number : $setting->default );
	}

	/**
	 * Select sanitization callback.
	 *
	 * @param string $input   Slug to sanitize.
	 * @param object $setting Setting instance.
	 *
	 * @return string  Sanitized slug if it is a valid choice; otherwise, the setting default.
	 */
	public function sanitize_select( $input, $setting ) {
		// Ensure input is a slug.
		$input = sanitize_key( $input );

		// Get list of choices from the control associated with the setting.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it; otherwise, return the default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	/**
	 * URL sanitization callback.
	 *
	 * @param string $url URL to sanitize.
	 *
	 * @return string Sanitized URL.
	 */
	public function sanitize_url( $url ) {
		return esc_url_raw( $url );
	}

	/**
	 * Sanitize the social icons.
	 *
	 * @param array  $icons Icons.
	 * @param object $setting Setting instance.
	 *
	 * @return string  $column
	 */
	public function sanitize_social_icons( $icons, $setting ) {
		$_icons = [];
		$choices = $setting->manager->get_control( $setting->id )->choices;
		$_choices = [];

		foreach ( $choices as $ckey => $group ) {
			foreach ( $group['icons'] as $icon => $name ) {
				$_choices[] = $icon;
			}
		}

		foreach ( $icons as $key => $icon ) {
			if ( in_array( $icon['type'], $_choices, true ) ) {
				$_icons[] = $icon;
			}
		}

		return $_icons;
	}
}
