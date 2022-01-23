<?php

add_filter('apalodi_component_header', function ($data) {

	return $data;
});

// add ajax load-more callback
apalodi()->ajax('load-more', function ($request) {

}, 'nopriv');

add_action('apalodi_ajax_load_more', function() {

});
