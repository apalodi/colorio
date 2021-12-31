<?php
/**
 * The header for our theme.
 *
 * @package     Abc
 * @since       1.0
 * @author      apalodi
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>

	<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'asona' ); ?></a>

	<?php do_action( 'apalodi_before_page' ); ?>

	<div id="page" class="site">

		<header id="masthead" class="site-header">
			<div class="site-header-container flex align-middle">

				<button class="menu-trigger hamburger-menu" aria-label="<?php esc_attr_e( 'Open or close menu', 'asona' ); ?>"><span></span></button>

				<button class="search-trigger site-action-trigger" aria-label="<?php esc_attr_e( 'Open or close search', 'asona' ); ?>"><span></span></button>

				<span class="site-actions-backdrop"></span>

			</div><!-- .site-header-container -->
		</header><!-- #masthead -->

		<main id="main" class="site-main">

			<?php do_action( 'apalodi_before_content' ); ?>
