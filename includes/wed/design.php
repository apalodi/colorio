<?php
/**
 * Returns the current design style.
 *
 * @return array
 */
function get_design_style() {

	$design_style = get_theme_mod( 'design_style', get_default_design_style() );

	$supported_design_styles = get_available_design_styles();

	if ( in_array( $design_style, array_keys( $supported_design_styles ), true ) ) {

		return $supported_design_styles[ $design_style ];

	}

	return false;

}

/**
 * Returns the avaliable design styles.
 *
 * @return array
 */
function get_available_design_styles() {

	$suffix = SCRIPT_DEBUG ? '' : '.min';
	$rtl    = ! is_rtl() ? '' : '-rtl';

	$default_design_styles = array(
		'traditional' => array(
			'slug'           => 'traditional',
			'label'          => _x( 'Traditional', 'design style name', 'go' ),
			'url'            => get_theme_file_uri( "dist/css/design-styles/style-traditional{$rtl}{$suffix}.css" ),
			'editor_style'   => "dist/css/design-styles/style-traditional-editor{$rtl}{$suffix}.css",
			'color_schemes'  => array(
				'one'   => array(
					'label'      => _x( 'Apricot', 'color palette name', 'go' ),
					'primary'    => '#c76919',
					'secondary'  => '#122538',
					'tertiary'   => '#f8f8f8',
					'background' => '#ffffff',
				),
				'two'   => array(
					'label'      => _x( 'Emerald', 'color palette name', 'go' ),
					'primary'    => '#165153',
					'secondary'  => '#212121',
					'tertiary'   => '#f3f1f0',
					'background' => '#ffffff',
				),
				'three' => array(
					'label'      => _x( 'Brick', 'color palette name', 'go' ),
					'primary'    => '#87200e',
					'secondary'  => '#242611',
					'tertiary'   => '#f9f2ef',
					'background' => '#ffffff',
				),
				'four'  => array(
					'label'      => _x( 'Bronze', 'color palette name', 'go' ),
					'primary'    => '#a88548',
					'secondary'  => '#05212d',
					'tertiary'   => '#f9f4ef',
					'background' => '#ffffff',
				),
			),
			'fonts'          => array(
				'Crimson Text' => array(
					'400',
					'400i',
					'700',
					'700i',
				),
				'Nunito Sans'  => array(
					'400',
					'400i',
					'600',
					'700',
				),
			),
			'font_size'      => '1.05rem',
			'type_ratio'     => '1.275',
			'viewport_basis' => '900',
		),
		'modern'      => array(
			'slug'           => 'modern',
			'label'          => _x( 'Modern', 'design style name', 'go' ),
			'url'            => get_theme_file_uri( "dist/css/design-styles/style-modern{$rtl}{$suffix}.css" ),
			'editor_style'   => "dist/css/design-styles/style-modern-editor{$rtl}{$suffix}.css",
			'color_schemes'  => array(
				'one'   => array(
					'label'      => _x( 'Shade', 'color palette name', 'go' ),
					'primary'    => '#000000',
					'secondary'  => '#455a64',
					'tertiary'   => '#eceff1',
					'background' => '#ffffff',
				),
				'two'   => array(
					'label'      => _x( 'Blush', 'color palette name', 'go' ),
					'primary'    => '#c2185b',
					'secondary'  => '#ec407a',
					'tertiary'   => '#fce4ec',
					'background' => '#ffffff',
				),
				'three' => array(
					'label'      => _x( 'Indigo', 'color palette name', 'go' ),
					'primary'    => '#303f9f',
					'secondary'  => '#5c6bc0',
					'tertiary'   => '#e8eaf6',
					'background' => '#ffffff',
				),
				'four'  => array(
					'label'      => _x( 'Pacific', 'color palette name', 'go' ),
					'primary'    => '#00796b',
					'secondary'  => '#26a69a',
					'tertiary'   => '#e0f2f1',
					'background' => '#ffffff',
				),
			),
			'fonts'          => array(
				'Heebo'      => array(
					'400',
					'800',
				),
				'Fira Code'  => array(
					'400',
					'400i',
					'700',
				),
				'Montserrat' => array(
					'400',
					'700',
				),
			),
			'font_size'      => '0.85rem',
			'type_ratio'     => '1.3',
			'viewport_basis' => '950',
		),
		'trendy'      => array(
			'slug'           => 'trendy',
			'label'          => _x( 'Trendy', 'design style name', 'go' ),
			'url'            => get_theme_file_uri( "dist/css/design-styles/style-trendy{$rtl}{$suffix}.css" ),
			'editor_style'   => "dist/css/design-styles/style-trendy-editor{$rtl}{$suffix}.css",
			'color_schemes'  => array(
				'one'   => array(
					'label'             => _x( 'Plum', 'color palette name', 'go' ),
					'primary'           => '#000000',
					'secondary'         => '#4d0859',
					'tertiary'          => '#ded9e2',
					'background'        => '#ffffff',
					'footer_background' => '#000000',
				),

				'two'   => array(
					'label'             => _x( 'Steel', 'color palette name', 'go' ),
					'primary'           => '#000000',
					'secondary'         => '#003c68',
					'tertiary'          => '#c0c9d0',
					'background'        => '#ffffff',
					'footer_background' => '#000000',
				),
				'three' => array(
					'label'             => _x( 'Avocado', 'color palette name', 'go' ),
					'primary'           => '#000000',
					'secondary'         => '#02493b',
					'tertiary'          => '#b4c6af',
					'background'        => '#ffffff',
					'footer_background' => '#000000',
				),
				'four'  => array(
					'label'             => _x( 'Champagne', 'color palette name', 'go' ),
					'primary'           => '#000000',
					'secondary'         => '#cc224f',
					'tertiary'          => '#e5dede',
					'background'        => '#ffffff',
					'footer_background' => '#000000',
				),
			),
			'fonts'          => array(
				'Trocchi'         => array(
					'400',
					'600',
				),
				'Noto Sans'       => array(
					'400',
					'400i',
					'700',
				),
				'Source Code Pro' => array(
					'400',
					'700',
				),
			),
			'font_size'      => '1.1rem',
			'type_ratio'     => '1.2',
			'viewport_basis' => '850',
		),
		'welcoming'   => array(
			'slug'           => 'welcoming',
			'label'          => _x( 'Welcoming', 'design style name', 'go' ),
			'url'            => get_theme_file_uri( "dist/css/design-styles/style-welcoming{$rtl}{$suffix}.css" ),
			'editor_style'   => "dist/css/design-styles/style-welcoming-editor{$rtl}{$suffix}.css",
			'color_schemes'  => array(
				'one'   => array(
					'label'             => _x( 'Forest', 'color palette name', 'go' ),
					'primary'           => '#165144',
					'secondary'         => '#01332e',
					'tertiary'          => '#c9c9c9',
					'background'        => '#eeeeee',
					'header_background' => '#ffffff',
				),
				'two'   => array(
					'label'             => _x( 'Spruce', 'color palette name', 'go' ),
					'primary'           => '#233a6b',
					'secondary'         => '#01133d',
					'tertiary'          => '#c9c9c9',
					'background'        => '#eeeeee',
					'header_background' => '#ffffff',
				),
				'three' => array(
					'label'             => _x( 'Mocha', 'color palette name', 'go' ),
					'primary'           => '#5b3f20',
					'secondary'         => '#3f2404',
					'tertiary'          => '#c9c9c9',
					'background'        => '#eeeeee',
					'header_background' => '#ffffff',
				),
				'four'  => array(
					'label'             => _x( 'Lavender', 'color palette name', 'go' ),
					'primary'           => '#443a82',
					'secondary'         => '#2b226b',
					'tertiary'          => '#c9c9c9',
					'background'        => '#eeeeee',
					'header_background' => '#ffffff',
				),
			),
			'fonts'          => array(
				'Work Sans' => array(
					'300',
					'700',
				),
				'Karla'     => array(
					'400',
					'400i',
					'700',
				),
			),
			'font_size'      => '1rem',
			'type_ratio'     => '1.235',
			'viewport_basis' => '750',
		),
		'playful'     => array(
			'slug'           => 'playful',
			'label'          => _x( 'Playful', 'design style name', 'go' ),
			'url'            => get_theme_file_uri( "dist/css/design-styles/style-playful{$rtl}{$suffix}.css" ),
			'editor_style'   => "dist/css/design-styles/style-playful-editor{$rtl}{$suffix}.css",
			'color_schemes'  => array(
				'one'   => array(
					'label'             => _x( 'Frolic', 'color palette name', 'go' ),
					'primary'           => '#3f46ae',
					'secondary'         => '#ecb43d',
					'tertiary'          => '#f7fbff',
					'background'        => '#ffffff',
					'header_background' => '#3f46ae',
					'header_text'       => '#fafafa',
					'footer_background' => '#3f46ae',
				),
				'two'   => array(
					'label'             => _x( 'Coral', 'color palette name', 'go' ),
					'primary'           => '#e06b6d',
					'secondary'         => '#40896e',
					'tertiary'          => '#fff7f7',
					'background'        => '#ffffff',
					'header_background' => '#eb616a',
					'header_text'       => '#fafafa',
					'footer_background' => '#eb616a',
				),
				'three' => array(
					'label'             => _x( 'Organic', 'color palette name', 'go' ),
					'primary'           => '#3c896d',
					'secondary'         => '#6b0369',
					'tertiary'          => '#f2f9f7',
					'background'        => '#ffffff',
					'header_background' => '#3c896d',
					'header_text'       => '#fafafa',
					'footer_background' => '#3c896d',
				),
				'four'  => array(
					'label'             => _x( 'Berry', 'color palette name', 'go' ),
					'primary'           => '#117495',
					'secondary'         => '#d691c1',
					'tertiary'          => '#f7feff',
					'background'        => '#ffffff',
					'header_background' => '#117495',
					'header_text'       => '#fafafa',
					'footer_background' => '#117495',
				),
			),
			'fonts'          => array(
				'Poppins'   => array(
					'600',
				),
				'Quicksand' => array(
					'400',
					'600',
				),
			),
			'font_size'      => '1.1rem',
			'type_ratio'     => '1.215',
			'viewport_basis' => '950',
		),
	);

	/**
	 * Filters the supported design styles.
	 *
	 * @since 0.1.0
	 *
	 * @param array $design_styles Array containings the supported design styles,
	 * where the index is the slug of design style and value an array of options that sets up the design styles.
	 */
	$supported_design_styles = (array) apply_filters( 'go_design_styles', $default_design_styles );

	return $supported_design_styles;

}
