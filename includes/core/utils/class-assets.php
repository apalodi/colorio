<?php
/**
 * Theme assets.
 *
 * @package Colorio\Core\Utils
 */

namespace Apalodi\Core\Utils;

/**
 * Assets.
 */
class Assets {

	/**
	 * Manifest.
	 *
	 * @var array
	 */
	protected static $manifest = [];

	/**
	 * Gets the asset's url.
	 *
	 * @param string $asset The filename.
	 *
	 * @return string Returns the asset url.
	 */
	public static function get_url( $asset ) {
		return self::get( 'url', $asset );
	}

	/**
	 * Gets the asset's absolute path.
	 *
	 * @param string $asset The filename.
	 *
	 * @return string Returns the asset absolute path.
	 */
	public static function get_path( $asset ) {
		return self::get( 'path', $asset );
	}

	/**
	 * Gets the asset's absolute path or url.
	 *
	 * @param string $return_type Return path or url.
	 * @param string $asset The filename.
	 *
	 * @return string Returns the absolute path.
	 */
	protected static function get( $return_type, $asset ) {
		if ( ! isset( self::$manifest ) ) {
			$manifest_path = get_parent_theme_file_path( 'assets/manifest.json' );
			if ( is_file( $manifest_path ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				self::$manifest = json_decode( file_get_contents( $manifest_path ), true );
			}
		}

		if ( array_key_exists( $asset, self::$manifest ) ) {
			$file = self::$manifest[ $asset ];
		} else {
			$file = $asset;
		}

		if ( 'path' === $return_type ) {
			return get_template_directory() . '/' . $file;
		} elseif ( 'url' === $return_type ) {
			return get_template_directory_uri() . '/' . $file;
		}
	}
}
