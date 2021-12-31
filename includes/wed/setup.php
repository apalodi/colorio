<?php

/**
 * Layouts for the CoBlocks layout selector.
 */
foreach ( glob( get_parent_theme_file_path( 'partials/layouts/*.php' ) ) as $filename ) {
	require_once $filename;
}

/**
 * Run setup functions.
 */
Go\AMP\setup();
Go\Core\setup();
Go\TGM\setup();
Go\Customizer\setup();
Go\WooCommerce\setup();
Go\Title_Meta\setup();


\add_action('after_setup_theme', [$this, 'registerServices']);

add_theme_support(
    'html5',
    array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    )
);

add_theme_support(
    'custom-logo',
    array(
        'height'      => 190,
        'width'       => 190,
        'flex-width'  => true,
        'flex-height' => true,
    )
);

// Indicate that the theme works well in both Standard and Transitional template modes of the AMP plugin.
add_theme_support(
    'amp',
    array(
        // The `paired` flag means that the theme retains logic to be fully functional when AMP is disabled.
        'paired' => true,
    )
);

// Add support for WooCommerce.
add_theme_support( 'woocommerce' );
add_theme_support( 'wc-product-gallery-slider' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-zoom' );

// Add support for responsive embedded content.
add_theme_support( 'responsive-embeds' );

// Add support for full and wide align images.
add_theme_support( 'align-wide' );

// Add support for editor styles.
add_theme_support( 'editor-styles' );

// Add support for experimental link colors.
add_theme_support( 'experimental-link-color' );

// Add custom editor font sizes.
add_theme_support(
    'editor-font-sizes',
    array(
        array(
            'name'      => esc_html_x( 'Small', 'font size option label', 'go' ),
            'shortName' => esc_html_x( 'S', 'abbreviation of the font size option label', 'go' ),
            'size'      => 17,
            'slug'      => 'small',
        ),
        array(
            'name'      => esc_html_x( 'Medium', 'font size option label', 'go' ),
            'shortName' => esc_html_x( 'M', 'abbreviation of the font size option label', 'go' ),
            'size'      => 21,
            'slug'      => 'medium',
        ),
        array(
            'name'      => esc_html_x( 'Large', 'font size option label', 'go' ),
            'shortName' => esc_html_x( 'L', 'abbreviation of the font size option label', 'go' ),
            'size'      => 24,
            'slug'      => 'large',
        ),
        array(
            'name'      => esc_html_x( 'Huge', 'font size option label', 'go' ),
            'shortName' => esc_html_x( 'XL', 'abbreviation of the font size option label', 'go' ),
            'size'      => 30,
            'slug'      => 'huge',
        ),
    )
);
