<?php

namespace Apalodi\Features;

class Images {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'register_image_sizes' ] );
		add_filter( 'wp_calculate_image_sizes', [ $this, 'content_image_sizes_attr' ], 10, 5 );
		add_filter( 'wp_get_attachment_image_attributes', [ $this, 'post_thumbnail_sizes_attr' ], 10, 3 );
		add_filter( 'post_thumbnail_size', [ $this, 'set_full_size_for_gifs' ], 10, 2 );
	}

	/**
	 * Registers image sizes.
	 */
	public function register_image_sizes() {
		// Add custom image sizes.
		// apalodi_add_image_size( 'colorio-blog', 958, 575, true, array( 1916, 1520, 1125, 728, 480 ) );
	}

	/**
	 * Add custom image sizes attribute to enhance responsive image functionality for content images.
	 *
	 * @param string       $sizes A source size value for use in a 'sizes' attribute.
	 * @param array|string $size Requested size. Image size or array of width and height values in pixels (in that order).
	 * @param string|null  $image_src The URL to the image file or null.
	 * @param array|null   $image_meta The image meta data as returned by wp_get_attachment_metadata() or null.
	 * @param int          $attachment_id Image attachment ID of the original image or 0.
	 *
	 * @return string A source size value for use in a content image 'sizes' attribute.
	 */
	public function content_image_sizes_attr( $sizes, $size, $image_src, $image_meta, $attachment_id ) {
		global $content_width;

		$width = 0;

		if ( is_array( $size ) ) {
			$width = absint( $size[0] );
		} elseif ( is_string( $size ) ) {
			if ( ! $image_meta && $attachment_id ) {
				$image_meta = wp_get_attachment_metadata( $attachment_id );
			}

			if ( is_array( $image_meta ) ) {
				$size_array = _wp_get_image_size_from_meta( $size, $image_meta );
				if ( $size_array ) {
					$width = absint( $size_array[0] );
				}
			}
		}

		if ( $width < $content_width ) {
			$sizes = sprintf( '(max-width: %1$dpx) 100vw, %1$dpx', $width );
		} else {
			$sizes = sprintf( '(min-width: %dpx) %1$dpx, 100vw', $content_width );
		}

		return $sizes;
	}

	/**
	 * Add custom image sizes attribute to enhance responsive image functionality.
	 *
	 * @param array $attr Attributes for the image markup.
	 * @param int $attachment Image attachment ID.
	 * @param array $size Registered image size or flat array of height and width dimensions.
	 *
	 * @return array The filtered attributes for the image markup.
	 */
	public function post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
		// bail if the image size isn't string.
		if ( ! is_string( $size ) ) {
			return $attr;
		}

		// bail if it's not our image size.
		if ( 'colorio-blog' !== $size ) {
			return $attr;
		}

		// bail if we didn't set a data type attribute.
		if ( ! isset( $attr['data-type'] ) ) {
			return $attr;
		}

		if ( 'highlight' === $attr['data-type'] ) {
			$attr['sizes'] = '(min-width: 1220px) 760px, (min-width: 980px) calc(66vw - 36px), (min-width: 880px) calc(50vw - 36px), 100vw';
		}

		if ( 'grid-3-columns' === $attr['data-type'] ) {
			$attr['sizes'] = '(min-width: 1220px) 364px, (min-width: 880px) calc(33vw - 32px), (min-width: 600px) calc(50vw - 36px), (min-width: 480px) 244px, 160px';
		}

		if ( 'grid-2-columns' === $attr['data-type'] ) {
			$attr['sizes'] = '(min-width: 1220px) 388px, (min-width: 980px) calc(50vw - 404px), (min-width: 600px) calc(50vw - 36px), (min-width: 480px) 244px, 160px';
		}

		if ( 'single' === $attr['data-type'] ) {
			$attr['sizes'] = '(min-width: 1024px) 958px, 100vw';
		}

		if ( 'widget' === $attr['data-type'] ) {
			$attr['sizes'] = '(min-width: 1220px) 265px, (min-width: 880px) calc(25vw - 30px), (min-width: 600px) calc(50vw - 36px), (min-width: 480px) 244px, 160px';
		}

		return $attr;
	}

	/**
	 * Set post thumbnails of the GIF file type to always use the 'full' size,
	 * so they include animations.
	 *
	 * @param string $size Image size.
	 * @param int    $post_id Post ID.
	 *
	 * @return string Image size.
	 */
	public function set_full_size_for_gifs( $size, $post_id ) {
		$mime_type = get_post_mime_type( get_post_thumbnail_id( $post_id ) );

		if ( $mime_type && 'image/gif' === $mime_type ) {
			return 'full';
		}

		return $size;
	}
}
