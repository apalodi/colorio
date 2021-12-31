<?php

namespace Apalodi\Core;

use Apalodi\Core\Models\Option;
use Apalodi\Core\Models\Term;

class Theme {
	/**
	 * Theme identifier.
	 *
	 * @var string
	 */
	protected $theme_identifier;

	/**
	 * The single instance of the class.
	 *
	 * @var Theme
	 */
	protected static $instance;

	/**
	 * Modules and objects instances list
	 *
	 * @var array
	 */
	protected $factory = [];

	/**
	 * Ensures only one instance is loaded or can be loaded.
	 *
	 * @return object Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Load theme functions and features.
	 *
	 * @param string $theme_identifier Theme identifier.
	 * @param array  $config Theme config.
	 */
	public function setup( $theme_identifier, $config ) {
		$this->theme_identifier = $theme_identifier;

		$config = array_merge([
			'includes' => [],
			'features' => [],
		], $config);

		$this->includes( $config['includes'] );
		$this->features( $config['features'] );
	}

	/**
	 * Get theme identifier.
	 *
	 * @return string Theme identifier.
	 */
	public function get_theme_identifier() {
		return str_replace( '-', '_', $this->theme_identifier );
	}

	/**
	 * Auto include files from defined directories.
	 *
	 * @param array $directories Directories to auto include files from.
	 */
	protected function includes( $directories ) {
		foreach ( $directories as $directory ) {
			foreach ( glob( get_parent_theme_file_path( "includes/{$directory}/*.php" ) ) as $filename ) {
				require_once $filename;
			}
		}
	}

	/**
	 * Load theme features.
	 *
	 * @param array $features Theme features.
	 */
	protected function features( $features ) {
		foreach ( $features as $feature ) {
			new $feature();
		}
	}

	/**
	 * Get Option Model.
	 *
	 * @param string $type Option type.
	 *
	 * @return Option
	 */
	public function option( $type = '' ) {
		if ( ! isset( $this->factory['option'] ) ) {
			$this->factory['option'] = new Option( $type );
		}

		return $this->factory['option'];
	}

	/**
	 * Get Term Model.
	 *
	 * @return Term
	 */
	public function term() {
		if ( ! isset( $this->factory['term'] ) ) {
			$this->factory['term'] = new Term();
		}

		return $this->factory['term'];
	}

	/**
	 * Get the Theme Informations.
	 *
	 * @param string $info What information do we want.
	 * @param bool   $child If false on child theme it returns the parent theme info.
	 *
	 * @return string Desired theme information.
	 */
	public function get_theme_info( $info, $child = false ) {
		$theme = wp_get_theme( get_template() );

		if ( true === $child ) {
			$theme = wp_get_theme();
		}

		switch ( $info ) {
			case 'name':
				$theme_info = $theme->get( 'Name' );
				break;
			case 'version':
				$theme_info = $theme->get( 'Version' );
				break;
			case 'uri':
				$theme_info = $theme->get( 'ThemeURI' );
				break;
			case 'author':
				$theme_info = $theme->get( 'Author' );
				break;
			case 'author_uri':
				$theme_info = $theme->get( 'AuthorURI' );
				break;
			case 'description':
				$theme_info = $theme->get( 'Description' );
				break;
			case 'template':
				$theme_info = $theme->get( 'Template' );
				break;
			case 'status':
				$theme_info = $theme->get( 'Status' );
				break;
			case 'tags':
				$theme_info = $theme->get( 'Tags' );
				break;
			case 'text_domain':
				$theme_info = $theme->get( 'TextDomain' );
				break;
			case 'domain_path':
				$theme_info = $theme->get( 'DomainPath' );
				break;
			default:
				$theme_info = '';
				break;
		}//end switch

		return $theme_info;
	}
}
