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

<div id="page" class="site">

<?php
	/**
	 * Triggered after the opening #page tag.
	 *
	 * @hooked apalodi_site_header - 10
	 */
	do_action( 'apalodi_page_start' );
