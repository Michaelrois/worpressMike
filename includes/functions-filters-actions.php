<?php

// What're you doin' in my basement? GET OUTTA HERE!
if ( !defined( 'ABSPATH' ) ) { exit; }


// +------------------------------------------------------+
// | Extraneous functions that are either learnggered by Actions,
// |	or filter content with Filters.
// +------------------------------------------------------+


	// Move Yoast SEO's boxes to the bottom of the page. C'mon guys!
	add_filter( 'wpseo_metabox_prio', function() { return 'low'; } );
	

	// +------------------------------------------------------+
	// | Actually short-circutting the WordPress search...
	// |	The title and meta cannot be searched separately... We'll do it ourselves.
	// +------------------------------------------------------+
	add_action( 'pre_get_posts', function( $query )
	{
		if( $title = $query->get( '_meta_or_title' ) )
		{
			add_filter( 'get_meta_sql', function( $sql ) use ( $title )
			{
				global $wpdb;

				// Only run once:
				static $nr = 0; 
				if( 0 != $nr++ ) return $sql;

				// Modified WHERE
				$sql['where'] = sprintf(
					" AND ( %s OR %s ) ",
					$wpdb->prepare( "{$wpdb->posts}.post_title like '%%%s%%'", $title),
					mb_substr( $sql['where'], 5, mb_strlen( $sql['where'] ) )
				);

				return $sql;
			});
		}
	});

	
	// +------------------------------------------------------+
	// | Build basic theme framework
	// +------------------------------------------------------+
	if ( function_exists( 'add_theme_support' ) )
	{
		//Custom image sizes
		add_image_size( 'learn-admin-image', 50, 0, false );
	}

	// +------------------------------------------------------+
	// | Just the plug-in's own styling for Spectacle pages
	// +------------------------------------------------------+
	function learn_custom_post_styling()
	{		
		wp_enqueue_style( 'learn-custom-style', LEARN_CUSTOM_PLUGIN_URL . 'css/main.css', array(), null );
		
		wp_register_style( 'learn-product-utils-style', LEARN_CUSTOM_PLUGIN_URL . 'css/product-utils.css', array(), null );
	
		wp_register_script( 'learn-plant-type-filter-script', LEARN_CUSTOM_PLUGIN_URL . 'js/learn_plant_type_filter.js', array('jquery'), null );
		wp_register_script( 'learn-plant-single-viewer-script', LEARN_CUSTOM_PLUGIN_URL . 'js/learn_plant_single_viewer.js', array(), null );

		wp_localize_script( 'learn-plant-type-filter-script', 'learnPlant', array(
			'requestPath'	=> esc_url_raw( rest_url( LEARN_REST_NAMESPACE . '/learn_plant' ) ),
			'uber'			=> wp_create_nonce( 'wp_rest' ),
		));		
	}
	add_action( 'wp_enqueue_scripts', 'learn_custom_post_styling' );
	
	
	// +------------------------------------------------------+
	// | Filter for Google Maps API key
	// +------------------------------------------------------+
if ( !function_exists( 'learn_get_google_maps_key' ) ) {
	function learn_get_google_maps_key( $key )
	{
		return get_option( 'learn_google_maps_key', '' );
	}
	add_filter( 'learn_google_maps_api_key', 'learn_get_google_maps_key' );
}


	// +------------------------------------------------------+
	// | Filter for Google Maps Default Latitude
	// +------------------------------------------------------+
if ( !function_exists( 'learn_get_maps_default_lat' ) ) {
	function learn_get_maps_default_lat( $key )
	{
		return get_option( 'learn_maps_default_lat', '' );
	}
	add_filter( 'learn_google_maps_def_lat', 'learn_get_maps_default_lat' );
}


	// +------------------------------------------------------+
	// | Filter for Google Maps Default Longitude
	// +------------------------------------------------------+
if ( !function_exists( 'learn_get_maps_default_long' ) ) {
	function learn_get_maps_default_long( $key )
	{
		return get_option( 'learn_maps_default_long', '' );
	}
	add_filter( 'learn_google_maps_def_long', 'learn_get_maps_default_long' );
}