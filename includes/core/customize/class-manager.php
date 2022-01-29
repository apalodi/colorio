<?php

namespace Apalodi\Core\Customize;

use Apalodi\Core\Utilities\Color_Helpers;
use Apalodi\Core\Utilities\String_Helpers;
use WP_Customize_Manager;
use WP_Customize_Control;

class Manager {
	/**
	 * Panels.
	 *
	 * @var array
	 */
	protected $panels = [];

	/**
	 * Sections
	 *
	 * @var array
	 */
	protected $sections = [];

	/**
	 * Settings.
	 *
	 * @var array
	 */
	protected $settings = [];

	/**
	 * Controls.
	 *
	 * @var array
	 */
	protected $controls = [];

	/**
	 * Selective refresh partials.
	 *
	 * @var array
	 */
	protected $selective_refresh_partials = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		add_action( 'wp_head', [ $this, 'output_css_properties' ] );
	}

	/**
	 * Get custom controls.
	 *
	 * @return array Controls.
	 */
	private function get_custom_controlls() {
		$controls = [
			'apalodi_socials' => 'Apalodi\Core\Customize\Socials\Control',
		];

		return apply_filters( 'apalodi_custom_customize_controls', $controls );
	}

	/**
	 * Add customize options.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	public function customize_register( $wp_customize ) {
		$this->register_control_types( $wp_customize );
		$this->add_panels( $wp_customize );
		$this->add_sections( $wp_customize );
		$this->add_settings( $wp_customize );
		$this->add_controls( $wp_customize );
		$this->add_selective_refresh_partials( $wp_customize );
	}

	/**
	 * Output CSS properties to head.
	 */
	public function output_css_properties() {
		$css_properties = $this->get_css_properties();
		$properties = [];

		if ( empty( $css_properties ) ) {
			return;
		}

		foreach ( $css_properties as $property => $value ) {
			$properties[] = "{$property}: {$value};";
		}

		$selectors = [
			sprintf( ':root { %s }', String_Helpers::format_lines( $properties, 2 ) ),
		];

		echo sprintf( "<style type='text/css'>%s</style>\n", esc_html( String_Helpers::format_lines( $selectors ) ) );
	}

	/**
	 * Get option.
	 *
	 * @param string $id Customize setting ID.
	 *
	 * @return mixed Option value.
	 */
	public function get_option( $id ) {
		$default_value = isset( $this->settings[ $id ] ) ? $this->settings[ $id ]['default'] : false;
		return get_theme_mod( $id, $default_value );
	}

	/**
	 * Get CSS properties.
	 *
	 * @return array CSS properties.
	 */
	public function get_css_properties() {
		$css_properties = [];

		foreach ( $this->settings as $id => $args ) {
			if ( isset( $args['css_property'] ) ) {
				$property_name = $args['css_property'];
				$value = $this->get_option( $id );

				$properties = [
					$property_name => $this->get_option( $id ),
				];

				if ( isset( $args['css_property_callback'] ) ) {
					add_filter( "apalodi_customize_css_property_{$id}", $args['css_property_callback'], 10, 3 );
				} else {
					$default_callbacks = [
						'color' => [ $this, 'transform_hex_to_hsl_color' ],
					];

					$type = $this->controls[ $id ]['type'];

					if ( isset( $default_callbacks[ $type ] ) ) {
						add_filter( "apalodi_customize_css_property_{$id}", $default_callbacks[ $type ], 10, 3 );
					}
				}

				$css_properties = array_merge(
					$css_properties,
					/**
					 * Filters a Customize CSS properties.
					 *
					 * @param array  $properties CSS properties with values.
					 * @param string $property_name Property name.
					 * @param mixed  $value Setting value.
					 */
					apply_filters( "apalodi_customize_css_property_{$id}", $properties, $property_name, $value )
				);
			}//end if
		}//end foreach

		return $css_properties;
	}

	/**
	 * Add all customize panels.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	private function register_control_types( $wp_customize ) {
		$controls = $this->get_custom_controlls();

		foreach ( $controls as $control ) {
			$wp_customize->register_control_type( $control );
		}
	}

	/**
	 * Add all customize panels.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	private function add_panels( $wp_customize ) {
		foreach ( $this->panels as $id => $args ) {
			$wp_customize->add_panel( $id, $args );
		}
	}

	/**
	 * Add all customize sections.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	private function add_sections( $wp_customize ) {
		foreach ( $this->sections as $id => $args ) {
			$wp_customize->add_section( $id, $args );
		}
	}

	/**
	 * Add all customize settings.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	private function add_settings( $wp_customize ) {
		foreach ( $this->settings as $id => $args ) {
			$wp_customize->add_setting( $id, $args );
		}
	}

	/**
	 * Add all customize controls.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	private function add_controls( $wp_customize ) {
		foreach ( $this->controls as $id => $args ) {
			$controll_class = $this->get_controll_class( $args['type'] );
			$control = new $controll_class( $wp_customize, $id, $args );
			$wp_customize->add_control( $control );
		}
	}

	/**
	 * Add all selective refresh partials.
	 *
	 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
	 */
	private function add_selective_refresh_partials( $wp_customize ) {
		foreach ( $this->selective_refresh_partials as $id => $args ) {
			$wp_customize->selective_refresh->add_partial( $id, $args );
		}
	}

	/**
	 * Add a customize panel.
	 *
	 * @param string $id Customize panel ID.
	 * @param array  $args Array of properties for the new panel object.
	 */
	public function add_panel( $id, $args = [] ) {
		$this->panels[ $id ] = $args;
	}

	/**
	 * Add a customize section.
	 *
	 * @param string $id Customize section ID.
	 * @param array  $args Array of properties for the new section object.
	 */
	public function add_section( $id, $args = [] ) {
		$this->sections[ $id ] = $args;
	}

	/**
	 * Add a customize setting, control and potentially selective refresh partial.
	 *
	 * @param string $id Customize setting ID.
	 * @param array  $args Array of properties for the new setting and control object.
	 */
	public function add_field( $id, $args = [] ) {
		$this->settings[ $id ] = $args['setting'];
		$this->controls[ $id ] = $args['control'];

		if ( isset( $args['selective-refresh'] ) ) {
			$this->selective_refresh_partials[ $id ] = $args['selective-refresh'];
		}
	}

	/**
	 * Get the control class from the passed type.
	 *
	 * @param string $type Control type.
	 *
	 * @return WP_Customize_Control The instance of the control.
	 */
	private function get_controll_class( $type ) {
		$default_controls = [
			'background_position' => 'WP_Customize_Background_Position_Control',
			'background' => 'WP_Customize_Background_Image_Control',
			'checkbox' => 'WP_Customize_Control',
			'code_editor' => 'WP_Customize_Code_Editor_Control',
			'color' => 'WP_Customize_Color_Control',
			'cropped_image' => 'WP_Customize_Cropped_Image_Control',
			'date_time' => 'WP_Customize_Date_Time_Control',
			'dropdown-pages' => 'WP_Customize_Control',
			'header' => 'WP_Customize_Header_Image_Control',
			'image' => 'WP_Customize_Image_Control',
			'media' => 'WP_Customize_Media_Control',
			'nav_menu_auto_add' => 'WP_Customize_Nav_Menu_Auto_Add_Control',
			'nav_menu_item' => 'WP_Customize_Nav_Menu_Item_Control',
			'nav_menu_location' => 'WP_Customize_Nav_Menu_Location_Control',
			'nav_menu_locations' => 'WP_Customize_Nav_Menu_Locations_Control',
			'nav_menu' => 'WP_Customize_Nav_Menu_Control',
			'new_menu' => 'WP_Customize_New_Menu_Control',
			'radio' => 'WP_Customize_Control',
			'select' => 'WP_Customize_Control',
			'sidebar_block_editor' => 'WP_Sidebar_Block_Editor_Control',
			'sidebar_widgets' => 'WP_Widget_Area_Customize_Control',
			'site_icon' => 'WP_Customize_Site_Icon_Control',
			'text' => 'WP_Customize_Control',
			'textarea' => 'WP_Customize_Control',
			'theme' => 'WP_Customize_Theme_Control',
			'themes' => 'WP_Customize_Themes_Control',
			'upload' => 'WP_Customize_Upload_Control',
			'widget_form' => 'WP_Widget_Form_Customize_Control',
		];

		$custom_controls = $this->get_custom_controlls();

		$controls = array_merge( $default_controls, $custom_controls );
		$controls = apply_filters( 'apalodi_customize_controls', $controls );

		return isset( $controls[ $type ] ) ? $controls[ $type ] : 'WP_Customize_Control';
	}

	/**
	 * Transform HEX to HSL color.
	 *
	 * @param array  $properties CSS properties with values.
	 * @param string $property_name Property name.
	 * @param mixed  $value Setting value.
	 *
	 * @return array CSS properties with values.
	 */
	public function transform_hex_to_hsl_color( $properties, $property_name, $value ) {
		$color_hsla = Color_Helpers::hex_to_hsla( $value );

		$properties[ "{$property_name}-h" ] = $color_hsla[0];
		$properties[ "{$property_name}-s" ] = $color_hsla[1];
		$properties[ "{$property_name}-l" ] = $color_hsla[2];

		return $properties;
	}
}
