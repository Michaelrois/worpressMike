<?php
// What are you doing here? Get the fuck out of here!
if ( !defined( 'ABSPATH' ) ) { exit; }


// +------------------------------------------------------+
// | Principals Functions
// | 'file_exists' is used to include files if they exist
// | instead to had to comment lines later.
// +------------------------------------------------------+

// The array list to be used in the theme
if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/arrays.php' ) )
{ include_once CUSTOM_LEARNING_ABSPATH . 'includes/arrays.php'; }

// Set up the sytem Custom Post Types
if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/custom-learnig-taxo.php' ) )
{ include_once CUSTOM_LEARNING_ABSPATH . 'includes/custom-learning-taxo.php'; }

// The common tools used in the theme
if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/functions-tools.php' ) )
{ include_once CUSTOM_LEARNING_ABSPATH . 'includes/functions-tools.php'; }

// A container file to hold Filters and Actions and their respective functions
if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/functions-filters-actions.php' ) )
{ include_once CUSTOM_LEARNING_ABSPATH . 'includes/functions-filters-actions.php'; }

// The AJAX helpers
if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/functions-ajax-helpers.php' ) )
{ include_once CUSTOM_LEARNING_ABSPATH . 'includes/functions-ajax-helpers.php'; }

// The REST helpers
if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/functions-rest-helpers.php' ) )
{ include_once CUSTOM_LEARNING_ABSPATH . 'includes/functions-rest-helpers.php'; }

// Registering the Shortcodes and their respective functions
if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/functions-shortcodes.php' ) )
{ include_once CUSTOM_LEARNING_ABSPATH . 'includes/functions-shortcodes.php'; }

// Generating the menus for the new Custom Post Types and functions
if ( is_admin() && file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/functions-menus.php' ) )
{ include_once CUSTOM_LEARNING_ABSPATH . 'includes/functions-menus.php'; }

// Loading screen-specific functions and scripts
if ( is_admin() && file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/functions-screen-specific.php' ) )
{ include_once CUSTOM_LEARNING_ABSPATH . 'includes/functions-screen-specific.php'; }

// Generating Custom Fields for CPTs - ADMIN SIDE
if (is_admin())
{
    // The common functions
    if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/admin/meta/meta-renderer.php' ) )
        include_once CUSTOM_LEARNING_ABSPATH . 'includes/admin/meta/meta-renderer.php';

    if (file_exists(CUSTOM_LEARNING_ABSPATH . 'includes/admin/meta/meta-taxo-renderer.php'))
        include_once CUSTOM_LEARNING_ABSPATH . 'includes/admin/meta/meta-taxo-renderer.php';
    
    if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/admin/meta/meta-common.php' ) )
        include_once CUSTOM_LEARNING_ABSPATH . 'includes/admin/meta/meta-common.php';

    // The custom post type meta pages
    if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/admin/meta/meta-post-types.php' ) )
        include_once CUSTOM_LEARNING_ABSPATH . 'includes/admin/meta/meta-post-types.php';

    // The taxonomies
    if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/admin/meta/meta-taxo.php' ) )
        include_once CUSTOM_LEARNING_ABSPATH . 'includes/admin/meta/meta-taxo.php';

    // CPT admin lists
    if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/admin/list-tables/admin-list-tables.php' ) )
        include_once CUSTOM_LEARNING_ABSPATH . 'includes/admin/list-tables/admin-list-tables.php';
}