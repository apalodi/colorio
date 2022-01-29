<?php

namespace Apalodi\Core\Models;

class Post {
	/**
	 * Constructor.
	 */
	public function __construct() {
	}

	/**
	 * Get reading time.
	 *
	 * @return int Reading time in minutes.
	 */
	public function get_reading_time() {
		$characters = [
			// special characters from roman alphabet.
			'äëïöüÄËÏÖÜáǽćéíĺńóŕśúźÁǼĆÉÍĹŃÓŔŚÚŹ',
			'àèìòùÀÈÌÒÙãẽĩõñũÃẼĨÕÑŨâêîôûÂÊÎÔÛăĕğĭŏœ̆ŭĂĔĞĬŎŒ̆Ŭ',
			'āēīōūĀĒĪŌŪőűŐŰąęįųĄĘĮŲåůÅŮæÆøØýÝÿŸþÞẞßđĐıIœŒ',
			'čďěľňřšťžČĎĚĽŇŘŠŤŽƒƑðÐłŁçģķļșțÇĢĶĻȘȚħĦċėġżĊĖĠŻʒƷǯǮŋŊŧŦ',
			// cyrilic.
			'АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя',
			// greek.
			'αβΓγΔδεζηΘθΛλμΞξΠπΣςσΦφΨψΩωίϊΐόάέύϋΰήώΊΪΌΆΈΎΫ',
		];

		$content = get_post_field( 'post_content' );
		$words = str_word_count( wp_strip_all_tags( $content ), 0, implode( '', $characters ) );
		$words_per_minute = apply_filters( 'apalodi_reading_time_words_per_minute', 200 );
		$reading_time = max( 1, floor( $words / $words_per_minute ) );

		return (int) $reading_time;
	}

	/**
	 * Get published time.
	 *
	 * The difference is returned in a human readable format such as "1 hour", "5 mins", "2 days".
	 *
	 * @param int         $number_of_days When to stop showing human readable time and show the date.
	 * @param int|WP_Post $post Post ID or WP_Post object. If null defaults to current post.
	 *
	 * @return string Human readable time difference or date.
	 */
	public function get_published_time( $number_of_days = 30, $post = null ) {
		$post = get_post( $post );

		if ( ! $post ) {
			return false;
		}

		$now = time();
		$date = get_post_time( 'U', true, $post );
		$diff = (int) abs( $now - $date );
		$limit = $number_of_days * DAY_IN_SECONDS;

		if ( $diff < HOUR_IN_SECONDS ) {

			$mins = round( $diff / MINUTE_IN_SECONDS );
			$mins = $mins <= 1 ? 1 : $mins;

			/* Translators: Number of minutes */
			$published = sprintf( _n( '%s min ago', '%s mins ago', $mins, 'asona' ), $mins );

		} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {

			$hours = round( $diff / HOUR_IN_SECONDS );
			$hours = $hours <= 1 ? 1 : $hours;

			/* Translators: Number of hours */
			$published = sprintf( _n( '%s hour ago', '%s hours ago', $hours, 'asona' ), $hours );

		} elseif ( $diff < $limit && $diff >= DAY_IN_SECONDS ) {

			$days = round( $diff / DAY_IN_SECONDS );
			$days = $days <= 1 ? 1 : $days;

			/* Translators: Number of days */
			$published = sprintf( _n( '%s day ago', '%s days ago', $days, 'asona' ), $days );

		} else {

			$published = get_the_date( '', $post );

		}//end if

		return apply_filters( 'apalodi_published_time', $published, $post, $number_of_days, $diff );
	}

	/**
	 * Get related posts ids.
	 *
	 * 1. Get tags number from 70% of posts_per_page
	 * 2. The rest 30% get first from child category and then parent category
	 * 3. If there are less items get the latest posts
	 *
	 * @param int $posts_per_page Posts per page.
	 *
	 * @return array Related posts IDs.
	 */
	public function get_related_posts_ids( $posts_per_page ) {
		$post_id = get_the_ID();
		$tags_number = round( $posts_per_page * 0.7, 0 );
		$related_posts_ids = apalodi()->transient()->get_post_meta( $post_id, "related_posts_ids_{$posts_per_page}" );

		if ( false === $related_posts_ids ) {

			// Tags.
			$tag_ids = wp_get_object_terms( $post_id, 'post_tag', [
				'fields' => 'ids',
				'update_term_meta_cache' => false,
			] );

			$tags_args = [
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => $tags_number + 1,
				'fields' => 'ids',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'suppress_filters' => false,
				'tax_query' => [
					[
						'taxonomy' => 'post_tag',
						'field' => 'term_id',
						'terms' => $tag_ids,
					],
				],
			];

			$tags = get_posts( $tags_args );
			$tags = array_slice( array_diff( $tags, [ $post_id ] ), 0, $tags_number );
			$count_tags = count( $tags );

			// Child categories.
			$child_category_ids = wp_get_object_terms( $post_id, 'category', [
				'fields' => 'ids',
				'update_term_meta_cache' => false,
				'childless' => true,
			] );

			$child_categories_args = [
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => $posts_per_page + 1,
				'fields' => 'ids',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'suppress_filters' => false,
				'tax_query' => [
					[
						'taxonomy' => 'category',
						'field' => 'term_id',
						'terms' => $child_category_ids,
					],
				],
			];

			$child_categories = get_posts( $child_categories_args );
			$child_categories = array_diff( $child_categories, [ $post_id ] );
			$count_child_categories = count( $child_categories );

			// Parent categories.
			$parent_category_ids = wp_get_object_terms( $post_id, 'category', [
				'fields' => 'ids',
				'update_term_meta_cache' => false,
				'parent' => 0,
			] );

			$parent_categories_args = [
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => $posts_per_page + 1,
				'fields' => 'ids',
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'suppress_filters' => false,
				'tax_query' => [
					[
						'taxonomy' => 'category',
						'field' => 'term_id',
						'terms' => $parent_category_ids,
					],
				],
			];

			$parent_categories = get_posts( $parent_categories_args );
			$parent_categories = array_diff( $parent_categories, [ $post_id ] );
			$count_parent_categories = count( $parent_categories );

			// Combine categories and tags.
			$categories = array_values( array_unique( array_merge( $child_categories, $parent_categories ) ) );
			$categories_tags = array_intersect( $categories, $tags );
			$count_categories_tags = count( $categories_tags );

			$categories_slice = $posts_per_page - $count_tags + $count_categories_tags;
			$categories = array_slice( $categories, 0, $categories_slice );

			$related_posts_ids = array_slice( array_values( array_unique( array_merge( $tags, $categories ) ) ), 0, $posts_per_page );
			$count_related = count( $related_posts_ids );

			// If there are less posts get the latests.
			if ( $count_related < $posts_per_page ) {

				$latest_posts_args = [
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => $posts_per_page * 2,
					'fields' => 'ids',
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'suppress_filters' => false,
				];

				$latest_posts = get_posts( $latest_posts_args );
				$latest_posts = array_diff( $latest_posts, [ $post_id ] );
				$related_posts_ids = array_slice( array_values( array_unique( array_merge( $related_posts_ids, $latest_posts ) ) ), 0, $posts_per_page );
			}

			apalodi()->transient()->set_post_meta( $post_id, "related_posts_ids_{$posts_per_page}", $related_posts_ids, 60 * MINUTE_IN_SECONDS );
		}//end if

		return $related_posts_ids;
	}
}
