<?php

namespace Apalodi\Core\Models;

use Collator;

class Term {
	/**
	 * Constructor.
	 */
	public function __construct() {
	}

	/**
	 * Get number of post tags.
	 *
	 * @return int Number of post tags.
	 */
	public function count_terms_post_tag() {
		return (int) wp_count_terms( 'post_tag' );
	}

	/**
	 * Get number of post categories.
	 *
	 * @return int Number of post categories.
	 */
	public function count_terms_category() {
		return (int) wp_count_terms( 'category' );
	}

	/**
	 * Get page id of term map template.
	 *
	 * @param string $taxonomy Taxonomy slug.
	 *
	 * @return int Page ID.
	 */
	public function get_term_map_page_id( $taxonomy ) {
		return (int) get_option( 'apalodi_term_map_' . $taxonomy . '_page_id', false );
	}

	/**
	 * Get all terms ordered.
	 *
	 * @param string $taxonomy Taxonomy slug.
	 * @param bool   $l10n Wheter to order terms by translated language.
	 *
	 * @return array $terms
	 */
	public function get_all_terms_ordered( $taxonomy, $l10n = true ) {
		$locale = get_locale();

		$terms = get_terms([
			'taxonomy' => $taxonomy,
		]);

		if ( $l10n && strpos( $locale, 'en' ) === false ) {
			$term_names = [];
			$term_keys = [];

			foreach ( $terms as $key => $term ) {
				$term_names[] = $term->name;
				$tkey = sanitize_key( $term->name );
				$term_keys[ $tkey ] = $term;
			}

			$terms = [];
			$collator = new Collator( $locale );
			$collator->sort( $term_names );

			foreach ( $term_names as $key => $name ) {
				$tkey = sanitize_key( $name );
				$terms[] = $term_keys[ $tkey ];
			}
		}

		return $terms;
	}

	/**
	 * Get term map.
	 *
	 * @param string $taxonomy Taxonomy slug.
	 *
	 * @return array Term map.
	 */
	public function get_term_map( $taxonomy ) {
		$term_map = apalodi()->transient()->get( "term_map_{$taxonomy}" );

		if ( false === $term_map ) {
			$terms = $this->get_all_terms_ordered( $taxonomy );
			$term_map = [];

			foreach ( $terms as $key => $term ) {
				$fist_letter = strtolower( mb_substr( $term->name, 0, 1 ) );
				$term_map[ $fist_letter ][] = $term;
			}

			apalodi()->transient()->set( "term_map_{$taxonomy}", $term_map, 6 * HOUR_IN_SECONDS );
		}

		return $term_map;
	}
}
