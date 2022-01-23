<header id="masthead" class="site-header">
	<div class="site-header-container flex align-middle">

		<button class="menu-trigger hamburger-menu" aria-label="<?php esc_attr_e( 'Open or close menu', 'asona' ); ?>"><span></span></button>

		<?php apalodi()->template( 'site-logo' ); ?>
		<?php apalodi()->template( 'site-navigation' ); ?>
		<?php //apalodi()->template( 'search' ); ?>

		<button class="search-trigger site-action-trigger" aria-label="<?php esc_attr_e( 'Open or close search', 'asona' ); ?>"><span></span></button>

		<span class="site-actions-backdrop"></span>

	</div><!-- .site-header-container -->
</header><!-- #masthead -->
