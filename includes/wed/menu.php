<?php
/**
 * Add a dropdown icon to top-level menu items
 *
 * @param string $title The menu item's title.
 * @param object $item  The current menu item.
 * @param object $args  An object of wp_nav_menu() arguments.
 * @param int    $depth Depth of menu item (used for padding).
 *
 * Add a dropdown icon to top-level menu items.
 */
function add_dropdown_icons( $title, $item, $args, $depth ) {

	ob_start();

	load_inline_svg( 'arrow-down.svg' );

	$icon = ob_get_clean();

	// Only add class to 'top level' items on the 'primary' menu.
	if ( 'primary' === $args->theme_location && 0 === $depth ) {
		foreach ( $item->classes as $value ) {
			if ( 'menu-item-has-children' === $value || 'page_item_has_children' === $value ) {
				$title = $title . $icon;
			}
		}
	}

	return $title;

}
