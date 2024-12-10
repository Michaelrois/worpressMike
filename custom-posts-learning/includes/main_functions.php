<?php

// What are you doing here? Get out of here!
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// +------------------------------------------------------------------+
// |  A list of arrays to be used throughout the theme                                                  
// +------------------------------------------------------------------+

if ( file_exists( CUSTOM_LEARNING_ABSPATH . 'includes/arrays.php' ) ) {
    require_once CUSTOM_LEARNING_ABSPATH . 'includes/arrays.php';
}