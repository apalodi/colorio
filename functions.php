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
 * 1. Auto include files from functions directory.
 * 2. Load theme features.
 */
apalodi()->setup( 'colorio', [
	'includes' => [
		'functions',
	],
	'features' => [
		// Theme features.
		Apalodi\Features\Admin::class,
		Apalodi\Features\Customize::class,
		Apalodi\Features\Editor::class,
		Apalodi\Features\Enqueue_Scripts::class,
		Apalodi\Features\Google_Fonts::class,
		Apalodi\Features\Images::class,
		Apalodi\Features\Media::class,
		Apalodi\Features\Menu::class,
		Apalodi\Features\Setup::class,
		Apalodi\Features\Sidebar::class,
		Apalodi\Features\Styles::class,
		Apalodi\Features\Template::class,

		// Supported plugins.
		Apalodi\Plugins\AP_Popular_Posts::class,
		Apalodi\Plugins\AP_Featured_Posts::class,
		Apalodi\Plugins\AP_Share_Buttons::class,
		Apalodi\Plugins\AP_Performance::class,
		Apalodi\Plugins\Contact_Form_7::class,
		Apalodi\Plugins\WooCommerce::class,

		// Schema.
	],
]);
