<?php

namespace Apalodi\Core\Traits;

trait Tag_Attributes {
	/**
	 * Output the classes for various elements.
	 *
	 * It needs to be called like this:
	 *      class( 'page_class' )
	 *
	 * To add classes with filter
	 *
	 * function apalodi_get_page_classes( $classes ) {
	 *      $classes[] = 'hfeed';
	 *      $classes[] = 'site';
	 *      return $classes;
	 * }
	 * add_filter('apalodi_page_class', 'apalodi_get_page_classes', 10 );
	 *
	 * To add default classes use it like this:
	 *      class( 'apalodi_page_class', [ 'site', 'hfeed' ] ).
	 *
	 * @param string $filter Name of the custom filter. It's automatically prefixed with apalodi_.
	 * @param array  $classes One or more classes to add to the class list.
	 */
	public function class( $filter, $classes = [] ) { // phpcs:ignore PHPCompatibility.Keywords.ForbiddenNames.classFound -- We will use it.
		// Separates classes with a single space.
		echo 'class="' . esc_attr( implode( ' ', $this->get_class( $filter, $classes ) ) ) . '"';
	}

	/**
	 * Get the classes for various elements.
	 *
	 * @param string $filter Name of the custom filter.
	 * @param array  $classes One or more classes to add to the class list.
	 *
	 * @return array Array of class names.
	 */
	public function get_class( $filter, $classes = [] ) {
		/**
		 * Filters the list of class names.
		 *
		 * @param array $classes An array of class names.
		 */
		$classes = apply_filters( "apalodi_{$filter}", $classes );

		$classes = array_map( 'esc_attr', $classes );

		return array_unique( $classes );

	}

	/**
	 * Output the data attributs for various elements.
	 *
	 * To add classes without filter use it like this
	 *      data_attr( 'load_more_data_attr', [ 'posts-per-page' => '12', 'type' => 'example' ] ).
	 *
	 * @param string $filter Name of the custom filter or false to not apply_filters.
	 * @param array  $data One or more data attr to add to the list.
	 */
	public function data_attr( $filter, $data = [] ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- It's escaped.
		echo implode( ' ', $this->get_data_attr( $filter, $data ) );
	}

	/**
	 * Get the data attributs for various elements.
	 *
	 * @param string $filter Name of the custom filter.
	 * @param array  $data One or more data attr to add to the list.

	 * @return array An array of data attributes.
	 */
	public function get_data_attr( $filter, $data = [] ) {
		$data_attr = [];

		/**
		 * Filters the list of data attributes.
		 *
		 * @param array $data An array of data attributes.
		 */
		$data = apply_filters( "apalodi_{$filter}", $data );

		foreach ( $data as $key => $value ) {
			$data_attr[ 'data-' . esc_attr( $key ) ] = esc_attr( $value );
		}

		return $data_attr;
	}

	/**
	 * Output the tag attributs for various elements.
	 *
	 * To add classes without filter use it like this
	 *      - tag_attr( 'link_tag_attr', true, [ 'class' => [ 'apalodi-icon', 'apalodi-link-icon' ], 'href' => 'http://example.com', 'target => '_blank' ] )
	 *
	 * @param string $filter Name of the custom filter.
	 * @param array  $data One or more tag attr to add to the list.
	 */
	public function tag_attr( $filter, $data = [] ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- It's escaped.
		echo implode( ' ', $this->get_tag_attr( $filter, $data ) );
	}

	/**
	 * Get the tag attributs for various elements.
	 *
	 * @param string $filter Name of the custom filter.
	 * @param array  $data One or more tag attr to add to the list.
	 *
	 * @return array Tag attributes.
	 */
	public function get_tag_attr( $filter, $data = [] ) {
		$tags = [];
		$attr = [];

		/**
		 * Filters the list of attributes.
		 *
		 * @param array $data An array attributes and values.
		 */
		$data = apply_filters( "apalodi_{$filter}", $data );

		foreach ( $data as $tag => $value ) {
			if ( is_array( $value ) ) {
				$attr[ $tag ] = implode( ' ', $value );
			} else {
				$attr[ $tag ] = $value;
			}
		}

		foreach ( $attr as $tag => $value ) {
			$tags[] = esc_attr( $tag ) . '="' . esc_attr( $value ) . '"';
		}

		return $tags;
	}
}
