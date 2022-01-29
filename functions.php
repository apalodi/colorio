<?php

use Apalodi\Core\Theme;

// Autoloader via Composer if exists.
if ( file_exists( get_parent_theme_file_path( 'vendor/autoload.php' ) ) ) {
	require get_parent_theme_file_path( 'vendor/autoload.php' );
}

// Custom autoloader.
require get_parent_theme_file_path( 'includes/autoload.php' );

/**
 * Main function responsible for accesing all theme functionalities.
 *
 * @return Theme Class instance.
 */
function apalodi() {
	return Theme::get_instance();
}

/**
 * Theme Setup.
 *
 * 1. Auto include files.
 * 2. Load and init theme features.
 */
apalodi()->setup( 'colorio', [
	'includes' => [
		'includes/core/libraries',
		'includes/functions',
	],
	'features' => [
		'Apalodi\\Features\\' => 'includes/features',
		'Apalodi\\Plugins\\' => 'includes/plugins',
	],
]);
