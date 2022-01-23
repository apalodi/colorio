<?php

namespace Apalodi;

/**
 * Autoload Core Classes.
 *
 * Custom autoloader for WordPress file and class names standards.
 *
 * Few examples
 *     Apalodi\Core\Theme - includes/core/class-theme-php
 *     Apalodi\Core\Utilities\Assets - includes/core/utils/class-assets-php
 */
spl_autoload_register( function ( $class_name ) {
	$namespace = 'Apalodi\\';

	if ( strpos( $class_name, $namespace ) !== 0 ) {
		return false;
	}

	$path = get_parent_theme_file_path( 'includes' );
	$parts = explode( '\\', substr( $class_name, strlen( $namespace ) ) );
	$count_parts = count( $parts );

	foreach ( $parts as $key => $part ) {
		$part = str_replace( '_', '-', strtolower( $part ) );
		$prefix = ( $key + 1 === $count_parts ) ? '/class-' : '/';
		$path .= $prefix . $part;
	}

	$path .= '.php';

	if ( ! file_exists( $path ) ) {
		return false;
	}

	require_once $path;

	return true;
});
