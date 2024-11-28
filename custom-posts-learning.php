<?php 
// Test to better understant the custom posts plugin

// Register the variables to be used throughout the plugin

// Definte the plugin absolute path, for use in INCLUDE and REQUIRE functions
if (!defined('CUSTOM_LEARNING_ABSPATH'))
{ define( 'CUSTOM_LEARNING_ABSPATH', dirname( __FILE__ ) . '/' ); }

// Define the prefix for Options data - Shared across all custom learning plugins.
if (!defined('LEARN_PREFIX'))
{ define( 'LEARN_PREFIX', 'learn_' ); }

// Set up any administration panel data to use with the custom learning plugin
$learnAdminVariables = array(
    'settings_group'		=> 'custom_learning',
    'settings_section'      => 'tcp_main_settings',
    'settings_page_slug'	=> 'tcp_settings',
);

// Initialize the main plugin functions
require_once __DIR__ . '/includes/principals-functions.php';