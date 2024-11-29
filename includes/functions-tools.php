<?php
// What are you doing here? Get the fuck out of here!
if ( !defined( 'ABSPATH' ) ) { exit; }

// The global set of tools used throughout the plugin
// Mostly stuff that doesn't fit anywhere else
// Also repetitive functions refactored from elsewhere

    // QnD associated posts function for me.
    // Always returns an array, even if it's empty
if(!function_exists('learn_get_associated_posts')) {
    function learn_get_associated_posts($post_id = 0, $meta_name = '') {
        if ($post_id == 0 || $meta_name == '') {
            return false;
        }

        $meta_value = get_post_meta($post_id, $meta_name, true);
        return array_filter(explode(",", $meta_value));
    }
}

// Grabbing the URL for the "Generic" image stuff
if(!function_exists('learn_get_default_image')) {
    function learn_get_default_image() {
        return LEARN_CUSTOM_PLUGIN_URL . '/images/default_image.png';
    }
}