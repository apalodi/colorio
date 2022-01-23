<?php

namespace Apalodi\Features;

class Template {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'excerpt_more', [ $this, 'excerpt_more' ] );
		add_filter( 'excerpt_length', [ $this, 'excerpt_length' ] );
		add_filter( 'nav_menu_link_attributes', [ $this, 'nav_menu_link_attributes' ] );
		add_filter( 'post_gallery', [ $this, 'gallery_shortcode' ], 10, 2 );
		add_filter( 'img_caption_shortcode', [ $this, 'img_caption_shortcode' ], 10, 3 );
		add_filter( 'wp_link_pages', [ $this, 'wp_link_pages' ], 10, 2 );

		// oembed.
		// images.

		// @codingStandardsIgnoreStart.
		// widgets.
		// add_filter( 'wp_generate_tag_cloud', 'apalodi_remove_style_tag_cloud' );
		// add_filter( 'widget_tag_cloud_args', 'apalodi_widget_tag_cloud_args' );
		// add_filter( 'show_recent_comments_widget_style', '__return_false' );
		// @codingStandardsIgnoreEnd.
	}

	/**
	 * Change the excerpt more.
	 *
	 * @param string $more Excerpt more.
	 *
	 * @return string Excerpt more.
	 */
	public function excerpt_more( $more ) {
		if ( is_single() ) {
			return '';
		}

		return '...';
	}

	/**
	 * Change the excerpt length only for posts.
	 *
	 * @param int $length Excerpt length.
	 *
	 * @return int Excerpt length.
	 */
	public function excerpt_length( $length ) {
		if ( 'post' === get_post_type() ) {
			$length = 20;
		}

		return $length;
	}

	/**
	 * Add tabindex to empty link so it's focusable.
	 *
	 * @param array $atts Menu atts.
	 *
	 * @return array Menu atts.
	 */
	public function nav_menu_link_attributes( $atts ) {
		if ( '' === $atts['href'] ) {
			$atts['tabindex'] = '0';
		}

		return $atts;
	}

	/**
	 * Customize WordPress default gallery shortcode.
	 *
	 * @param string $output Gallery HTML.
	 * @param array  $attr Attributes of the gallery shortcode.
	 *
	 * @return string Gallery HTML.
	 */
	public function gallery_shortcode( $output, $attr ) {
		$post = get_post();

		if ( ! empty( $attr['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attr['orderby'] ) ) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		$atts = shortcode_atts( [
			'order' => 'ASC',
			'orderby' => 'menu_order ID',
			'id' => $post ? $post->ID : 0,
			'itemtag' => 'figure',
			'icontag' => 'div',
			'captiontag' => 'figcaption',
			'columns' => 3,
			'size' => 'thumbnail',
			'include' => '',
			'exclude' => '',
			'link' => '',
		], $attr, 'gallery' );

		$id = intval( $atts['id'] );

		if ( ! empty( $atts['include'] ) ) {

			$_attachments = get_posts( [
				'include' => $atts['include'],
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $atts['order'],
				'orderby' => $atts['orderby'],
				'suppress_filters' => false,
			] );

			$attachments = [];
			foreach ( $_attachments as $key => $val ) {
				$attachments[ $val->ID ] = $_attachments[ $key ];
			}
		} elseif ( ! empty( $atts['exclude'] ) ) {

			$attachments = get_children( [
				'post_parent' => $id,
				'exclude' => $atts['exclude'],
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $atts['order'],
				'orderby' => $atts['orderby'],
			] );

		} else {

			$attachments = get_children( [
				'post_parent' => $id,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $atts['order'],
				'orderby' => $atts['orderby'],
			] );
		}//end if

		if ( empty( $attachments ) ) {
			return '';
		}

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment ) {
				$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
			}
			return $output;
		}

		$columns = absint( $atts['columns'] );
		$columns = 8 < $columns ? 8 : $columns;

		$output = "\n" . '<ul class="wp-block-gallery alignwide columns-' . $columns . ' is-cropped">';

		foreach ( $attachments as $id => $attachment ) {

			$image_caption = isset( $attachment->post_excerpt ) ? wptexturize( $attachment->post_excerpt ) : false;

			$img_full = wp_get_attachment_image_src( $id, 'full' );
			$sizes = sprintf( '%sx%s', absint( $img_full[1] ), absint( $img_full[2] ) );

			$attr = [
				'data-id' => $id,
				'data-full' => $img_full[0],
				'data-full-size' => $sizes,
			];

			$image = wp_get_attachment_image( $id, 'large', false, $attr );

			$output .= '<li class="blocks-gallery-item">';
				$output .= '<figure>';

			if ( 'none' !== $atts['link'] ) {

				$image_url = wp_get_attachment_url( $id );

				$output .= '<a href="' . esc_url( $image_url ) . '">';
				$output .= $image;
				$output .= '</a>';

			} else {
				$output .= $image;
			}

			if ( $image_caption ) {
				$output .= '<figcaption>' . wp_kses_post( $image_caption ) . '</figcaption>';
			}

				$output .= '</figure>';
			$output .= '</li>';
		}//end foreach

		$output .= '</ul>';

		return $output;
	}

	/**
	 * Customize WordPress default image caption shortcode.
	 *
	 * @param string $output Caption HTML.
	 * @param array  $attr Attributes of the image caption shortcode.
	 * @param string $content The image element, possibly wrapped in a hyperlink.
	 *
	 * @return string Caption HTML.
	 */
	public function img_caption_shortcode( $output, $attr, $content ) {
		$atts = shortcode_atts( [
			'id'      => '',
			'caption_id' => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => '',
			'class'   => '',
		], $attr, 'caption' );

		$atts['width'] = (int) $atts['width'];

		if ( $atts['width'] < 1 || empty( $atts['caption'] ) ) {
			return $content;
		}

		$id          = '';
		$caption_id  = '';
		$describedby = '';

		if ( $atts['id'] ) {
			$atts['id'] = sanitize_html_class( $atts['id'] );
			$id         = 'id="' . esc_attr( $atts['id'] ) . '" ';
		}

		if ( $atts['caption_id'] ) {
			$atts['caption_id'] = sanitize_html_class( $atts['caption_id'] );
		} elseif ( $atts['id'] ) {
			$atts['caption_id'] = 'caption-' . str_replace( '_', '-', $atts['id'] );
		}

		if ( $atts['caption_id'] ) {
			$caption_id  = 'id="' . esc_attr( $atts['caption_id'] ) . '" ';
			$describedby = 'aria-describedby="' . esc_attr( $atts['caption_id'] ) . '" ';
		}

		$class = trim( 'wp-caption ' . $atts['align'] . ' ' . $atts['class'] );
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Using default wp hook.
		$caption_width = apply_filters( 'img_caption_shortcode_width', $atts['width'], $atts, $content );

		$style = '';

		if ( $caption_width ) {
			$style = 'style="max-width: ' . (int) $caption_width . 'px" ';
		}

		$html = sprintf(
			'<figure %s%s%sclass="%s">%s%s</figure>',
			$id,
			$describedby,
			$style,
			esc_attr( $class ),
			do_shortcode( $content ),
			sprintf(
				'<figcaption %sclass="wp-caption-text">%s</figcaption>',
				$caption_id,
				$atts['caption']
			)
		);

		return $html;
	}

	/**
	 * Customize output of page links for paginated posts.
	 *
	 * @param string $output Linked pages HTML.
	 * @param array  $args Array of default arguments.
	 *
	 * @return string Linked pages HTML.
	 */
	public function wp_link_pages( $output, $args ) {
		global $page, $numpages, $multipage, $more;

		if ( 'next_and_number' === $args['next_or_number'] ) {

			if ( $multipage ) {

				$prev = $page - 1;
				$next = $page + 1;

				$output = $args['before'];

				$output .= '<ul class="page-numbers">';

				if ( $prev > 0 ) {
					$output .= '<li>';
					$output .= preg_replace( '/class=".*?"/', 'class="page-numbers prev"', _wp_link_page( $prev ) ) . $args['previouspagelink'] . '</a>';
					$output .= '</li>';
				}

				for ( $i = 1; $i <= $numpages; $i++ ) {

					$output .= '<li>';

					if ( $page === $i ) {
						$link = '<span class="page-numbers current">' . esc_html( $i ) . '</span>';
					} else {
						$link = preg_replace( '/class=".*?"/', 'class="page-numbers"', _wp_link_page( $i ) ) . esc_html( $i ) . '</a>';
					}

					$output .= $link;
					$output .= '</li>';
				}

				if ( $next <= $numpages ) {
					$output .= '<li>';
					$output .= preg_replace( '/class=".*?"/', 'class="page-numbers next"', _wp_link_page( $next ) ) . $args['nextpagelink'] . '</a>';
					$output .= '</li>';
				}

				$output .= '</ul>';
				$output .= $args['after'];
			}//end if
		}//end if

		return $output;
	}

	/**
	 * Return a list of allowed tags and attributes for a given context.
	 *
	 * @param string $context The context for which to retrieve tags.
	 *
	 * @return array List of allowed tags and their allowed attributes.
	 */
	public function wp_kses_allowed_html( $context = 'basic' ) {
		switch ( $context ) {
			case 'basic':
			default:
				$allowed_html = [
					'a' => [
						'href' => [],
						'target' => [],
						'title' => [],
					],
					'span' => [
						'style' => [],
					],
					'em' => [],
					'del' => [],
					'p' => [
						'style' => [],
					],
				];
				break;
		}

		return apply_filters( 'apalodi_wp_kses_allowed_html', $allowed_html, $context );
	}
}
