<?php

// What're you doin' in my basement? GET OUTTA HERE!
if ( !defined( 'ABSPATH' ) ) { exit; }


// +------------------------------------------------------+
// | Ajax Helpers - Keep Ajax functions separate and clean
// +------------------------------------------------------+

	
	// +------------------------------------------------------+
	// | AJAX function add_action lists
	// +------------------------------------------------------+
		
	// Add a new map for multiple address entry - Backend
	add_action( 'wp_ajax_add_remove_map_add', 'learn_add_remove_map_add' );
	add_action( 'wp_ajax_nopriv_add_remove_map_add', 'learn_add_remove_map_add' );
	
	// Search for Posts via association
	add_action( 'wp_ajax_learn_search_posts', 'learn_search_posts' );
	add_action( 'wp_ajax_nopriv_learn_search_posts', 'learn_search_posts' );
	
	// Search for Taxonomies via association
	add_action( 'wp_ajax_learn_search_taxos', 'learn_search_taxos' );
	add_action( 'wp_ajax_nopriv_learn_search_taxos', 'learn_search_taxos' );
	
	
	
	// Search for Taxonomies via association
	add_action( 'wp_ajax_learn_calendar_load_events', 'learn_calendar_load_events' );
	add_action( 'wp_ajax_nopriv_learn_calendar_load_events', 'learn_calendar_load_events' );
	
	
	
	
	// +------------------------------------------------------+
	// | Generates the HTML code to render a new map below an existing one
	// +------------------------------------------------------+
if ( !function_exists( 'learn_add_remove_map_add' ) ) {
	function learn_add_remove_map_add()
	{		
		if ( !isset( $_POST['field'] ) ) { wp_send_json_error( 'No ID sent to ARMA function!' ); }
	
		$fieldID = sanitize_text_field( $_POST['field'] );
		
		// Verify the post UBER data
		if ( check_ajax_referer( 'learn_'. $fieldID, 'uber', false ) == false )
		{ wp_send_json_error( 'Nonce defective' ); }
	
		$currentMaps = ( isset( $_POST['all_maps'] ) ) ? $_POST['all_maps'] : array();
		// Technically, we shouldn't worry if there are no maps. We have the ability to add a new one here if we need to
		/* if ( count( $currentMaps ) == 0 ) { wp_send_json_error( 'Invalid number of maps' ); } */
		// We will, however, make sure that $currentMaps is an array of sorts...
			
		$totalAllowed = intval( $_POST['total_allowed'] );
		if ( $totalAllowed == 0 ) { wp_send_json_error( 'Invalid number of maps allowed' ); }

		if ( ( $totalAllowed - count( $currentMaps ) ) < 1 ) { wp_send_json_error( 'Map limit exceeded' ); }

		$returnedHTML = '';
		$currentMap = 'a';	// For now.
		$address = '';
		$city = '';
		$latitude = '';
		$longitude = '';
		$meta_field['id'] = $fieldID;		
		
		$possibleMaps = range( 'a', 'e' );	// NOTE: "0" to "4", so 5 maps max
		
		
		foreach ( $possibleMaps as $aMap )
		{
			if ( !in_array( $aMap, $currentMaps ) )
			{
				$currentMap = $aMap;
				break;
			}
		}
						
		ob_start();
		
		include TRI_CUSTOM_ABSPATH . 'includes/admin/meta/meta-map.php';
		
		$returnedHTML .= ob_get_clean();							
								
 
		wp_send_json_success( array( 'html' => $returnedHTML, 'current_map' => $currentMap ) );
	}
}
	
	
	// +------------------------------------------------------+
	// | Search for related posts
	// +------------------------------------------------------+
if ( !function_exists( 'learn_search_posts' ) ) {
	function learn_search_posts()
	{
		// Verify the post UBER data
		if ( check_ajax_referer( 'search_for_posts_list', 'uber', false ) == false ) {
			wp_send_json_error( 'Nonce defective' );
		}

		$ajaxPostedKeyword		= trim( sanitize_text_field( $_POST['keyword'] ) );
		$ajaxPostedType			= trim( sanitize_text_field( $_POST['post_type'] ) );
		$ajaxPostedCurrentPost	= intval( $_POST['current_post'] );		
		
		$ajaxPostedType			= array_filter( explode( ",", $ajaxPostedType ) ); // Should always return an array	

		// Min three characters to name
		if ( strlen( utf8_decode( $ajaxPostedKeyword ) ) < 3 ) {
			wp_send_json_error( 'No posts found' );
		}

		$args = array(
			'posts_per_page'	=> 10,
			'post_type'			=> $ajaxPostedType,
			'orderby'			=> 'title',
			'order'				=> 'ASC',
			's'					=> $ajaxPostedKeyword,
			//'post__not_in'		=> $ajaxPostedCurrentPost,	// Make sure our current post isn't included... */
		);
		
		$the_query = new WP_Query( $args );

		if ( $the_query->have_posts() )
		{
			$returnedData = array();
			while ( $the_query->have_posts() )
			{
				$the_query->the_post();

				$postData			= array();
				$postData['id']		= $the_query->post->ID;
				$postData['title']	= $the_query->post->post_title;

				$returnedData[]		= $postData;
			}

			wp_send_json_success( $returnedData );
		}
		wp_reset_postdata();

		wp_send_json_error( 'No data found' );
	}
}
	
	
	// +------------------------------------------------------+
	// | Search for related taxonomies
	// +------------------------------------------------------+
if ( !function_exists( 'learn_search_taxos' ) ) {
	function learn_search_taxos()
	{
		// Verify the post UBER data
		if ( check_ajax_referer( 'search_for_taxos_list', 'uber', false ) == false )
		{ wp_send_json_error( 'Nonce defective' ); }

		$ajaxPostedKeyword = trim( sanitize_text_field( $_POST['keyword'] ) );
		$ajaxPostedType = trim( sanitize_text_field( $_POST['taxo_type'] ) );

		// Min three characters to name
		if ( strlen( utf8_decode( $ajaxPostedKeyword ) ) < 3 )
		{ wp_send_json_error( 'No taxonomies found' ); }

		$tagsList = get_terms( array(
			'taxonomy'		=> array( $ajaxPostedType ),
			'orderby'		=> 'name',			// It's default, but here in case we want to change it later
			'order'			=> 'ASC',
			'number'		=> 5,
			'hide_empty'	=> false,
			'fields'		=> 'id=>name',
			'name__like'	=> $ajaxPostedKeyword,
		) );
		
		if ( count( $tagsList ) > 0 )
		{
			$returnedData = array();
			foreach ( $tagsList as $tagID => $tagName )
			{	
				$taxoData = array();
				$taxoData['id'] = $tagID;
				$taxoData['name'] = $tagName;

				$returnedData[] = $taxoData;
			}
			wp_send_json_success( $returnedData );
		}

		wp_send_json_error( 'No data found' );
	}
}	
	
	// +------------------------------------------------------+
	// | Search for related taxonomies
	// +------------------------------------------------------+
if ( !function_exists( 'learn_calendar_load_events' ) ) {
	function learn_calendar_load_events()
	{
		// Verify the post UBER data
		if ( check_ajax_referer( 'learnaxe_events_calendar', 'uber', false ) == false )
		{ wp_send_json_error( 'Nonce defective' ); }

		$ajaxMonth		= intval( $_POST['month'] );		// REQUIRED
		$ajaxYear		= intval( $_POST['year'] );		// REQUIRED
		$ajaxCatType	= ( isset( $_POST['category_type'] ) ) ? trim( sanitize_text_field( $_POST['category_type'] ) ) : "learn_activite_type";	// NOT REQUIRED
		$ajaxCat		= ( isset( $_POST['category'] ) ) ? $_POST['category'] : "";	// NOT REQUIRED
		$category 		= array();
		if ( is_array( $ajaxCat ) )
		{ foreach( $ajaxCat as $cat ) { $category[] = sanitize_text_field( $cat ); } }
		$category 		= array_filter( $category );
		$ajaxAltCatType	= ( isset( $_POST['alt_category_type'] ) ) ? trim( sanitize_text_field( $_POST['alt_category_type'] ) ) : "";	// NOT REQUIRED
		$ajaxAltCat		= ( isset( $_POST['alt_category'] ) ) ? $_POST['alt_category'] : "";	// NOT REQUIRED
		$altCategory 	= array();
		if ( is_array( $ajaxAltCat ) )
		{ foreach( $ajaxAltCat as $cat ) { $altCategory[] = sanitize_text_field( $cat ); } }
		$altCategory 	= array_filter( $altCategory );
		$ajaxMetaField	= ( isset( $_POST['meta_field'] ) ) ? trim( sanitize_text_field( $_POST['meta_field'] ) ) : "";	// NOT REQUIRED
		$ajaxMetaValue	= ( isset( $_POST['meta_value'] ) ) ? trim( sanitize_text_field( $_POST['meta_value'] ) ) : "";	// NOT REQUIRED
		
		$onlyParentCats	= ( isset( $_POST['parent_cats'] ) ) ? boolval( $_POST['parent_cats'] ) : false;
		
		
		if ( $ajaxMonth < 0 && $ajaxMonth > 12 )
		{ wp_send_json_error( 'Invalid month' ); }
		
		if ( $ajaxYear <= 0 )
		{ wp_send_json_error( 'Invalid year' ); }
	

		// Setup default $args
		$args = array(
			'post_type'			=> 'learn_activite',
			'post_status'		=> array( 'publish' ),
			'posts_per_page'	=> -1,
			'fields'			=> 'ids',
		);
		
		$metaQuery	= array();
		
			
		// Doing some logic to try and grab events that span between months (and maybe years)
		$hasPrevYearWrap	= false;
		$hasNextYearWrap	= false;
		$prevMonth		= $ajaxMonth-1;
		$nextMonth		= $ajaxMonth+1;
		
		if ( $prevMonth < 1 ) { $prevMonth = 12; $hasPrevYearWrap = true; }
		if ( $nextMonth > 12 ) { $nextMonth = 1; $hasNextYearWrap = true; }
		
		$startDate	= sprintf(
			"%04d-%02d-01",
			$ajaxYear,
			$prevMonth
		);
		$endDate	= sprintf(
			"%04d-%02d",
			$ajaxYear,
			$nextMonth
		);
		
		if ( $hasPrevYearWrap )
		{
			$startDate	= ($ajaxYear-1)."-$prevMonth-01";
		}
		
		if ( $hasNextYearWrap )
		{
			$endDate	= ($ajaxYear+1)."-$nextMonth";
		}
		
		$startDate	= ( new DateTime( $startDate, wp_timezone() ) )->format("Y-m-d");
		$endDate	= ( new DateTime( $endDate, wp_timezone() ) )->format("Y-m-t");
		
		
		$metaQuery[] = array(
			'key'		=> TRI_PREFIX . 'act_date_start',
			'value'		=> array( $startDate, $endDate ),
			'type'		=> 'date',
			'compare'	=> 'BETWEEN',
		);
		
		
			
		// Now to add the other atlearnbutes...
		if ( !empty( $ajaxMetaField ) && !empty( $ajaxMetaValue ) )
		{
			$metaQuery[] = array(
				'key'		=> $ajaxMetaField,
				'value'		=> $ajaxMetaValue
			);
		}
		
		// If we have more than one meta query... Make sure it selects ALL of the variables
		if ( count( $metaQuery ) > 1 )
		{
			$metaQuery['relation'] = 'AND';
		}
		
		// Now to add it to our query...
		if ( !empty( $metaQuery ) )
		{
			$args['meta_query'] = $metaQuery;
		}
		

		$taxQuery = array();
		
		// Adding categories, if any
		if ( !empty( $category ) && !empty( $ajaxCatType ) )
		{
			$taxQuery[] = array(
				'taxonomy'	=> $ajaxCatType,
				'field'		=> 'slug',
				'terms'		=> $category,
			);
		}
		
		
		// Adding categories, if any
		if ( !empty( $altCategory ) && !empty( $ajaxAltCatType ) )
		{
			$taxQuery[] = array(
				'taxonomy'	=> $ajaxAltCatType,
				'field'		=> 'slug',
				'terms'		=> $altCategory,
			);
		}
		
		if ( count( $taxQuery ) > 1 )
		{
			$taxQuery['relation'] = 'AND';
		}
		
		if ( !empty( $taxQuery ) )
		{
			$args['tax_query']	= $taxQuery;
		}
		
		// We should have everything prepared at this point...
		$eventQuery = new WP_Query( $args );
		$events		= array();
		
		
		if ( !is_wp_error( $eventQuery ) && $eventQuery->have_posts() )
		{
			foreach ( $eventQuery->posts as $eventID ) {
				$startDate	= get_post_meta( $eventID, TRI_PREFIX . 'act_date_start', true );
				$endDate	= get_post_meta( $eventID, TRI_PREFIX . 'act_date_end', true );
				
				if ( empty( $endDate ) )
				{ $endDate = $startDate; }
			
				$startDate	= new DateTime( $startDate, wp_timezone() );
				$endDate	= new DateTime( $endDate, wp_timezone() );
				
				$content 	= wp_trim_excerpt( get_the_content( null, false, $eventID ) ) ;				
				$content	= slearnp_shortcodes( $content );
				$content	= excerpt_remove_blocks( $content );
				$content	= apply_filters( 'the_content', $content );
				$content	= str_replace( ']]>', ']]&gt;', $content );
				
				$excerpt	= wp_trim_words( $content, 15, ' [&hellip;]' );
				
				$eventLink	= get_the_permalink( $eventID );
				
				$eventCats	= get_the_terms( $eventID, $ajaxCatType );
				
				if ( $eventCats && !is_wp_error( $eventCats ) && count( $eventCats ) > 0 )
				{
					if ( $onlyParentCats )
					{
						$parentTerm = learn_get_parent_taxonomy( $eventCats[0], $ajaxCatType );
						if ( $parentTerm && !is_wp_error( $parentTerm ) )
						{
							$eventCats = $parentTerm->slug;	// We really only need one category, and we want the slug to apply to stylings
						}
					}
					else
					{
						$eventCats = $eventCats[0]->slug;	// We really only need one category, and we want the slug to apply to stylings
					}
				}
				
				$events[] = array(
					'startDate'	=> $startDate->format( 'Y-m-d\T00:00:00P' ),
					'endDate'	=> $endDate->format( 'Y-m-d\T00:00:00P' ),
					'summary'	=> $excerpt,
					'title'		=> get_the_title( $eventID ),
					'link'		=> $eventLink,
					'category'	=> $eventCats
				);
			}
		}
		
		wp_send_json_success( $events );
	}
}