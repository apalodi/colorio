<?php

namespace Apalodi\Core;

use Apalodi\Core\Admin\Notices;
use Apalodi\Core\Customize\Manager;
use Apalodi\Core\Models\Option;
use Apalodi\Core\Models\Post;
use Apalodi\Core\Models\Term;
use Apalodi\Core\Models\Query;
use Apalodi\Core\Models\Transient;
use Apalodi\Core\Models\User;
use Apalodi\Core\Traits\Singleton;
use Apalodi\Core\Traits\Macroable;
use Apalodi\Core\Traits\Tag_Attributes;
use Exception;

/**
 * Main class responsible for defining all theme functionalities.
 *
 * It has methods that are definied in other classes and made available here using the Macroable.
 *
 * @method string thisMenuHasMacro() Added in Apalodi\Features\Menu
 */
class Theme {
	use Singleton;
	use Macroable;
	use Tag_Attributes;

	/**
	 * Theme identifier.
	 *
	 * @var string
	 */
	protected $theme_identifier;

	/**
	 * Modules and objects instances list
	 *
	 * @var array
	 */
	protected $factory = [];

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
			foreach ( glob( get_parent_theme_file_path( "{$directory}/*.php" ) ) as $filename ) {
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
		foreach ( $features as $namespace => $directory ) {
			foreach ( glob( get_parent_theme_file_path( "{$directory}/class-*.php" ) ) as $filename ) {
				$dir = get_parent_theme_file_path( $directory );
				$filename = str_replace( $dir, '', $filename );
				$class_name = str_replace( [ '/class-', '.php' ], '', $filename );
				$class_name = ucwords( $class_name, '-' );
				$class_name = str_replace( '-', '_', $class_name );
				$class_name = $namespace . $class_name;
				new $class_name();
			}
		}
	}

	/**
	 * Create objects.
	 *
	 * @param string $key Array key that the object will be accessible.
	 * @param object $object object that needs to created.
	 *
	 * @return object Created object.
	 */
	protected function factory( $key, $object ) {
		if ( ! isset( $this->factory[ $key ] ) ) {
			$this->factory[ $key ] = $object;
		}

		return $this->factory[ $key ];
	}

	/**
	 * Get admin notices.
	 *
	 * @return Notices Admin notices.
	 */
	public function admin_notice() {
		return Notices::get_instance();
	}

	/**
	 * Get Customize Manager.
	 *
	 * @return Manager Customize Manager.
	 */
	public function customize() {
		return $this->factory( 'customize', new Manager() );
	}

	/**
	 * Get Transient model.
	 *
	 * @return Transient Model.
	 */
	public function transient() {
		return $this->factory( 'transient', new Transient() );
	}

	/**
	 * Get Option model.
	 *
	 * @param string $type Option type.
	 *
	 * @return Option Model.
	 */
	public function option( $type = '' ) {
		return $this->factory( 'option', new Option( $type ) );
	}

	/**
	 * Get Post model.
	 *
	 * @return Post Model.
	 */
	public function post() {
		return $this->factory( 'post', new Post() );
	}

	/**
	 * Get Term model.
	 *
	 * @return Term Model.
	 */
	public function term() {
		return $this->factory( 'term', new Term() );
	}

	/**
	 * Get User model.
	 *
	 * @return User Model.
	 */
	public function user() {
		return $this->factory( 'user', new User() );
	}

	/**
	 * Get Query model.
	 *
	 * @return Query Model.
	 */
	public function query() {
		return $this->factory( 'query', new Query() );
	}

	/**
	 * Load a template part with passing arguments.
	 *
	 * Makes it easy for a theme to reuse sections of code in a easy to overload way
	 * for child themes.
	 *
	 * @param string $slug The slug name for the generic template.
	 * @param array  $args Pass args with the template load.
	 *
	 * @throws Exception If there is no file found.
	 */
	public function template( $slug, $args = [] ) {
		$templates = [ "theme/templates/{$slug}/index.php" ];
		$template = locate_template( $templates, false, false, $args );

		if ( $template ) {
			include $template;
			return;
		}

		throw new Exception( "Template file 'theme/templates/{$slug}/index.php' cannot be located" );
	}

	/**
	 * Get the theme informations.
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

		$infos = [
			'name' => 'Name',
			'version' => 'Version',
			'uri' => 'ThemeURI',
			'author' => 'Author',
			'author_uri' => 'AuthorURI',
			'description' => 'Description',
			'template' => 'Template',
			'status' => 'Status',
			'tags' => 'Tags',
			'text_domain' => 'TextDomain',
			'domain_path' => 'DomainPath',
		];

		return isset( $infos[ $info ] ) ? $theme->get( $infos[ $info ] ) : '';
	}

	/**
	 * Output the translated text.
	 *
	 * @param string $option Option id for text.
	 * @param string $text Text in gettext function.
	 * @param bool   $escape If set escape attr, html or wp_kses allowed html.
	 * @param string $allowed_html If the escape type is wp_kses choose what html is allowed.
	 */
	public function translate( $option, $text, $escape = 'html', $allowed_html = [] ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- It's escaped.
		echo $this->get_translate( $option, $text, $escape, $allowed_html );
	}

	/**
	 * Get the translated text.
	 *
	 * If the translation is disabled use the text from gettext function.
	 *
	 * @param string $option Option id for text.
	 * @param string $text Text in gettext function.
	 * @param bool   $escape If set escape attr, html or wp_kses allowed html.
	 * @param string $allowed_html If the escape type is wp_kses choose what html is allowed.
	 *
	 * @return  string  Text.
	 */
	public function get_translate( $option, $text, $escape = 'html', $allowed_html = [] ) {
		$i18n = apalodi_get_theme_mod( 'i18n', false );
		$text_escaped = '';

		if ( ! $i18n ) {
			$text = apalodi_get_theme_mod( $option, $text );
		}

		if ( $escape ) {
			switch ( $escape ) {
				case 'attr':
					$text_escaped = esc_attr( $text );
					break;
				case 'wp_kses':
					if ( empty( $allowed_html ) ) {
						$allowed_html = wp_kses_allowed_html();
					}
					$text_escaped = wp_kses( $text, $allowed_html );
					break;
				default:
					$text_escaped = esc_html( $text );
					break;
			}
		}

		return $text_escaped;
	}
}
