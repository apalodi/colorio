<?php

conditionals and get option is just one function

option is slug in class register - see is the vscode able to follow the code
if class meta has function transitent check can vscode follow get
chaining FTW

when we are changing default type option( 'meta' ) see what happens with other options
do {
	we need to init instance of class on every option check

	------------ -

	abc()->option()->get( 'color' ) - by default it loads the default value from the settings

	abc()->option()->is( 'color', 'blue' )
	abc()->option()->is( 'color', [ 'blue', 'red' ] ) -- isIn
	abc()->option()->set( 'color', 'blue' )

	$accent_color = abc()->option()->get( 'accent-color' )
	abc()->option()->is( 'background', $$accent_color )
	abc()->option()->is(
		'background',
		abc()->option->get( 'accent-color' )
	);
}
abc()->option()->is(
	'background',
	'accent-color',
	true -- or option,
	meta etc . so we can check cross different option types
); -- look automatically for option with 'accent-color' key

abc() {
	->option( 'site-option' )->is( 'color', 'blue' ) -- option, site - option, theme - mod( default )
	abc()->option( 'meta' )->get( 'color' )
	abc()->option( 'site-option' )->get( 'color' )

	abc()->transient->get( 'color' )
	abc()->transient->set( 'color', 'blue', 20 ) -- by default minutes
	abc()->transient->set( 'color', 'blue', 20, HOURS_IN_SECONDS ) -- use other value

	abc()->transient( 'meta' )->get( 'color' )
	abc()->transient( 'site' )->get( 'color' )

	abc()->meta()->get( 'color' ) -- current post id in loop by default
	abc()->meta( 23 )->get( 'color' ) -- post by default
	abc()->meta( 23, 'user' )->get( 'color' )
	abc()->meta( 23, 'term' )->get( 'color' )
	abc()->option( 'meta' )->get( 'color' )

	abc()->post( 23 )->get( 'title' )
	abc()->post( 23 )->get( 'date' )
	abc()->post( 23 )->meta()->get( 'color' )
	abc()->post( 23 )->title()
	abc()->post( 23 )->thumbnail()
	abc()->post( 23 )->content()
	abc()->post( 23 )->excerpt()

	abc()->term( 23 )->get( 'title' )
	abc()->term( 23 )->get( 'date' )
	abc()->term( 23 )->meta()->get( 'color' )

	abc()->user( 23 )->get( 'title' )
	abc()->user( 23 )->get( 'date' )
	abc()->user( 23 )->meta()->get( 'color' )

	------------ -

	abc()->is( 'debug' ) -- 'lazy-load-media', 'posts-archive'
	abc()->is_debug()
	abc()->check()->is( 'debug' )
	abc()->is()->debug()

	abc()->get( 'load-more-args' ) -- 'social-icons', 'post-columns'
	abc()->get()->load_more_args()

	vscode follow code ?

	------------ -

	abc()->settings()->add(
		'color',
		[
			-- options,
		]
	)

	abc()->settings( 'panel' )->add(
		'color',
		[
			-- options,
		]
	)

	------------ -

	try to use blog / page / 2, category / page / 2 etc

	abc()->ajax()->add(
		'load-more',
		function() {
			-- callback
		}
	)

	abc()->ajax( 'logged-in' )->add(
		'load-more',
		function() {
			-- callback
		}
	)

	abc()->ajax( 'logged-out' )->add(
		'load-more',
		function() {
			-- callback
		}
	)

	abc()->ajax()->hook( 'init' )->add(
		'load-more',
		function() {
			-- if we need more wp data
			-- callback
		}
	)

	------------ -

	abc() {
		->images()->add( 'blog', [ 1200, 900, 600 ] )

		------------ - ;
	}
}





<div class="dark-mode-button">
    <div class="dark-mode-button-inner-left">
    </div><div class="dark-mode-button-inner"></div>
</div>


#masthead .dark-mode-button {
    position: absolute;
    right: 50px;
    top: 16px
}

.dark-mode-button {
    font-size: 16px
}

.dark-mode-button-inner-left:empty {
    margin-left: -.625em
}

.dark-mode-button-inner-left:before,.dark-mode-button-inner-left:after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    -webkit-transition: .4s ease-in-out;
    -moz-transition: .4s ease-in-out;
    -o-transition: .4s ease-in-out;
    transition: .4s ease-in-out;
    outline: none
}

.dark-mode-button .dark-mode-button-inner,.dark-mode-button .dark-mode-button-inner-left {
    display: inline-block;
    font-size: .875em;
    position: relative;
    padding: 0;
    line-height: 1em;
    cursor: pointer;
    color: rgba(149,149,149,.51);
    font-weight: 400
}

.dark-mode-button .dark-mode-button-inner-left:before {
    content: '';
    display: block;
    position: absolute;
    z-index: 1;
    line-height: 2.125em;
    text-indent: 2.5em;
    height: 1em;
    width: 1em;
    margin: .25em;
    -webkit-border-radius: 100%;
    -moz-border-radius: 100%;
    border-radius: 100%;
    right: 1.625em;
    bottom: 0;
    background: #666;
    transform: rotate(-45deg);
    box-shadow: 0 0 .625em #fff
}

.dark-mode-button .dark-mode-button-inner-left:after {
    content: "";
    display: inline-block;
    width: 2.5em;
    height: 1.5em;
    -webkit-border-radius: 1em;
    -moz-border-radius: 1em;
    border-radius: 1em;
    background: rgba(255,255,255,.15);
    vertical-align: middle;
    margin: 0 .625em;
    border: .125em solid #666
}

.dark-mode-button.active .dark-mode-button-inner-left:before {
    right: 1.0625em;
    box-shadow: .3125em .3125em 0 0 #eee;
    background: 0 0
}

.dark-mode-button.active .dark-mode-button-inner-left:after {
    background: rgba(0,0,0,.15);
    border: .125em solid #fff
}

.dark-mode-button .dark-mode-button-inner-left {
    color: rgba(250,250,250,.51);
    font-weight: 700
}

.dark-mode-button.active .dark-mode-button-inner-left {
    color: rgba(149,149,149,.51);
    font-weight: 400
+

.dark-mode-button.active .dark-mode-button-inner-left+.dark-mode-button-inner {
    color: rgba(250,250,250,.51);
    font-weight: 700
}
