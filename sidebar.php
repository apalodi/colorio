<?php

if ( ! is_active_sidebar( 'sidebar-posts' ) ) {
	return; } ?>

<div id="secondary" class="main-widget-area widget-area" role="complementary">
	<?php dynamic_sidebar( 'sidebar-posts' ); ?>
</div><!-- #secondary -->
