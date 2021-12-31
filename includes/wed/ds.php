<?php
/**
 * Theme functions, definitions and includes.
 */

namespace Apalodi\Functions;

// echo "loaded core\n";
/**
 * Returns true if debug enabled.
 *
 * @return  bool
 */
function is_debug() {
	return apply_filters( 'apalodi_debug', false );
}

function bar() {
	// echo foo();
	echo 'bar';
}

// bar();

// Display upgrade notice if requirements aren't met.
if ( version_compare( $GLOBALS['wp_version'], wp_get_theme()->get( 'RequiresWP' ), '<' ) ) {
	add_action(
		'admin_notices',
		function () {
			$message = sprintf(
			/* translators: 1. Required WordPress Version. 2. Used WordPress Version. */
				__( 'This theme requires WordPress %1$s or newer. You are running version %2$s. Please upgrade.', 'colorio' ),
				wp_get_theme()->get( 'RequiresWP' ),
				$GLOBALS['wp_version']
			);
			printf( '<div class="error"><p>%s</p></div>', esc_html( $message ) );
		}
	);
}


/*
conditionals and get option is just one function

option is slug in class register - see is the vscode able to follow the code
if class meta has function transitent check can vscode follow get
chaining FTW

when we are changing default type option('meta') see what happens with other options
	do we need to init instance of class on every option check

-------------

abc()->option()->get('color') - by default it loads the default value from the settings

abc()->option()->is('color', 'blue')
abc()->option()->is('color', ['blue', 'red']) -- isIn
abc()->option()->set('color', 'blue')

$accent_color = abc()->option()->get('accent-color')
abc()->option()->is('background', $$accent_color)
abc()->option()->is(
	'background',
	abc()->option->get('accent-color')
);
abc()->option()->is(
	'background',
	'accent-color',
	true -- or option, meta etc. so we can check cross different option types
); -- look automatically for option with 'accent-color' key

abc()->option('site-option')->is('color', 'blue') -- option, site-option, theme-mod(default)
abc()->option('meta')->get('color')
abc()->option('site-option')->get('color')

abc()->transient->get('color')
abc()->transient->set('color', 'blue', 20) -- by default minutes
abc()->transient->set('color', 'blue', 20, HOURS_IN_SECONDS) -- use other value

abc()->transient('meta')->get('color')
abc()->transient('site')->get('color')
Transient->meta->get('color')

abc()->meta()->get('color') -- current post id in loop by default
abc()->meta(23)->get('color') -- post by default
abc()->meta(23, 'user')->get('color')
abc()->meta(23, 'term')->get('color')
abc()->option('meta')->get('color')

abc()->post(23)->get('title')
abc()->post(23)->get('date')
abc()->post(23)->meta()->get('color')
abc()->post(23)->title()
abc()->post(23)->thumbnail()
abc()->post(23)->content()
abc()->post(23)->excerpt()

abc()->term(23)->get('title')
abc()->term(23)->get('date')
abc()->term(23)->meta()->get('color')

abc()->user(23)->get('title')
abc()->user(23)->get('date')
abc()->user(23)->meta()->get('color')

-------------

abc()->is('debug') -- 'lazy-load-media', 'posts-archive'
abc()->is_debug()
abc()->check()->is('debug')
abc()->is()->debug()

abc()->get('load-more-args') -- 'social-icons', 'post-columns'
abc()->get()->load_more_args()

vscode follow code?

-------------

abc()->settings()->add('color', [
	-- options
])

abc()->settings('panel')->add('color', [
	-- options
])

-------------

try to use blog/page/2, category/page/2 etc

abc()->ajax()->add('load-more', function() {
	-- callback
})

abc()->ajax('logged-in')->add('load-more', function() {
	-- callback
})

abc()->ajax('logged-out')->add('load-more', function() {
	-- callback
})

abc()->ajax()->hook('init')->add('load-more', function() {
	-- if we need more wp data
	-- callback
})

-------------

abc()->images()->add('blog', [1200, 900, 600])

-------------



