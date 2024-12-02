<?php

// What're you doin' in my basement? GET OUTTA HERE!
if ( !defined( 'ABSPATH' ) ) { exit; }


// +------------------------------------------------------+
// | Main Learning Plugin functionality
// |	Using 'file_exists' when including files instead of
// |	commenting out files that don't exist... Saving on
// |	headache for later.
// +------------------------------------------------------+


// +------------------------------------------------------+
// | Lists of Arrays used throughout the theme
// +------------------------------------------------------+
	if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/arrays.php' ) )
		include_once LEARN_CUSTOM_ABSPATH . 'includes/arrays.php';
	

// +------------------------------------------------------+
// | Setup system Custom Post Types
// +------------------------------------------------------+
	if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/custom-learning-taxo.php' ) )
		include_once LEARN_CUSTOM_ABSPATH . 'includes/custom-learning-taxo.php';


// +------------------------------------------------------+
// | Common tools used in the theme
// +------------------------------------------------------+
	if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/functions-tools.php' ) )
		include_once LEARN_CUSTOM_ABSPATH . 'includes/functions-tools.php';


// +------------------------------------------------------+
// | A container file holding Filters and Actions and their respective functions
// +------------------------------------------------------+
	if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/functions-filters-actions.php' ) )
		include_once LEARN_CUSTOM_ABSPATH . 'includes/functions-filters-actions.php';


// +------------------------------------------------------+
// | AJAX helpers
// +------------------------------------------------------+
	if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/functions-ajax-helpers.php' ) )
		include_once LEARN_CUSTOM_ABSPATH . 'includes/functions-ajax-helpers.php';


// +------------------------------------------------------+
// | REST helpers
// +------------------------------------------------------+
	if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/functions-rest-helpers.php' ) )
		include_once LEARN_CUSTOM_ABSPATH . 'includes/functions-rest-helpers.php';


// +------------------------------------------------------+
// | Registering Shortcodes and their respective functions
// +------------------------------------------------------+
	if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/functions-shortcodes.php' ) )
		include_once LEARN_CUSTOM_ABSPATH . 'includes/functions-shortcodes.php';
		
	
// +------------------------------------------------------+
// | Generate menus for the new Custom Post Types and functions
// +------------------------------------------------------+
	if ( is_admin() && file_exists( LEARN_CUSTOM_ABSPATH . 'includes/admin/functions-menus.php' ) )
	{
		include_once LEARN_CUSTOM_ABSPATH . 'includes/admin/functions-menus.php';
	}
	

// +------------------------------------------------------+
// | Load screen-specific functions and scripts
// +------------------------------------------------------+
	if ( is_admin() && file_exists( LEARN_CUSTOM_ABSPATH . 'includes/admin/functions-screen-specific.php' ) )
	{
		include_once LEARN_CUSTOM_ABSPATH . 'includes/admin/functions-screen-specific.php';
	}
	

// +------------------------------------------------------+
// | Generate Custom Fields for CPTs - ADMIN SIDE
// +------------------------------------------------------+
	if ( is_admin() )
	{
		// Common functions
		if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/admin/meta/meta-renderer.php' ) )
				include_once LEARN_CUSTOM_ABSPATH . 'includes/admin/meta/meta-renderer.php';
				
		if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/admin/meta/meta-taxo-renderer.php' ) )
				include_once LEARN_CUSTOM_ABSPATH . 'includes/admin/meta/meta-taxo-renderer.php';
				
		if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/admin/meta/meta-common.php' ) )
				include_once LEARN_CUSTOM_ABSPATH . 'includes/admin/meta/meta-common.php';
		
		// Custom post type meta pages
		if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/admin/meta/meta-post_types.php' ) )
				include_once LEARN_CUSTOM_ABSPATH . 'includes/admin/meta/meta-post_types.php';

		// Taxonomies
		if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/admin/meta/meta-taxo.php' ) )
				include_once LEARN_CUSTOM_ABSPATH . 'includes/admin/meta/meta-taxo.php';

		// CPT admin lists
		if ( file_exists( LEARN_CUSTOM_ABSPATH . 'includes/admin/list-tables/admin-list-tables.php' ) )
				include_once LEARN_CUSTOM_ABSPATH . 'includes/admin/list-tables/admin-list-tables.php';
		
	}
