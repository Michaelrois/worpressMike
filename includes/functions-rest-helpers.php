<?php

// What're you doin' in my basement? GET OUTTA HERE!
if ( !defined( 'ABSPATH' ) ) { exit; }


// +------------------------------------------------------+
// | Rest API setup for "Ajax" functions
// +------------------------------------------------------+

	define( 'LEARN_REST_NAMESPACE', 'learning/v1.1' );


	add_action( 'rest_api_init', 'learn_custom_register_rest_endpoints' );

// +------------------------------------------------------+

// Function to register the REST API endpoints
	function learn_custom_register_rest_endpoints()
	{
		// Listening for archive ajax queries
		register_rest_route( LEARN_REST_NAMESPACE, '/learn_plant', array(
			'methods'		=> WP_REST_Server::READABLE,
			'callback'		=> 'learn_custom_plant_search',
			'args'			=> array(
				// NOTE: Don't list any of the other form arguments... If it's here, it'll expect it to be there, even when it's not.
			),
			'permission_callback'	=> function () {
				return true;	// Makes sure the server will accept content sent via our form
			}
		) );
	}

// +------------------------------------------------------+

// The function for custom element search
	function learn_custom_plant_search( WP_REST_Request $request )
	{
 		$submittedVars	= $request->get_params();
		$referer		= $request->get_header( 'referer' );	// Has a / on the end
		$submittedURL	= "";
		
		
		if ( isset( $submittedVars['action_url'] ) )	// Doesn't normally have a / on the end, the way we collect it. But we're forcing it
		{ $submittedURL = sanitize_text_field( $submittedVars['action_url'] ); }
		
		
		// Now to check if the user is logged in... We sent a Nonce cookie earlier, so this should work
		//$loggedInUser	= wp_get_current_user();	// NOTE: This might not work if not using actual WordPress users to log into... Private Content Bundle by Luca doesn't use WP Users by default... Needs to be enabled
		
		// Super quick and dirty referer checking... Not entirely tight.
	/* 	if ( $referer !== $submittedURL || !$loggedInUser )
		{ 
			return new WP_Error(
				'not_allowed',
				__( "You are not authorized to view this.", 'learning-custom-posts' ),
				array( 'status' => rest_authorization_required_code() )
			);
		} */
	
		// 'data' is the shortcode attributes sent to the output wrapper to start. Basically, we can re-create it this way.
		// Refactored filtering of submitted attributes
		$filtredData	= learn_custom_filter_type_sub_att( $submittedVars['data'] );
		//wp_mail( 'elessard@triaxe.ca', 'Test', 'filtredData: ' . var_export( $filtredData, true ) );
		
		// Pre-setting up our Arguments so we can run our other data through it later
		$args			= learn_custom_filter_prepare_query( $filtredData );
		//wp_mail( 'elessard@triaxe.ca', 'Test', 'Args: ' . var_export( $args, true ) );
		
		$submittedVars	= learn_custom_filter_ajax_submitted_vars( $submittedVars );
		//wp_mail( 'elessard@triaxe.ca', 'Test', 'SubmittedVars: ' . var_export( $submittedVars, true ) );
		
		$args = learn_custom_filter_alter_query_with_submitted_vars( $args, $submittedVars );
		//wp_mail( 'elessard@triaxe.ca', 'Test', 'Args after alter: ' . var_export( $args, true ) );

		
		
		$args_to_keep = array();
		
		foreach ( array( 'plant_type', 'search' ) as $aaaaaargs )
		{
			if ( isset( $submittedVars[$aaaaaargs] ) )
			{
				$args_to_keep[$aaaaaargs] = urlencode( ( is_array( $submittedVars[$aaaaaargs] ) ) ? implode( "|", $submittedVars[$aaaaaargs] ) : $submittedVars[$aaaaaargs] );
			}
		}


		// We should have everything prepared at this point...
		$postQuery		= new WP_Query( $args );
		$output			= ""; 
	
		ob_start();		
		
		if ( !is_wp_error( $postQuery ) && $postQuery->have_posts() ) {
			while( $postQuery->have_posts() ) {
				$postQuery->the_post();

				$postTemplate = LEARN_CUSTOM_ABSPATH . "templates/learn-plant-card-template.php";

				if ( file_exists( $postTemplate ) )
				{ include $postTemplate; }
			}
			wp_reset_postdata(); ?>
			<section class="learn-pagination"><?php
				echo learn_get_pagination_with_url( $postQuery, $submittedVars['action_url'], $args_to_keep  );
			?></section><?php
		} else {
			echo "<h3>" . __( "Aucun articles.", 'learn-custom' ) . "</h3>";
		}

		$output			= ob_get_clean();
		
		// Return the data!
		return new WP_REST_Response( $output, 200 );
	}
	