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

// Function to sanitize and escape various fields
if  (!function_exists('learn_get_allowable_html_tags')) {
    function learn_get_allowable_html_tags() {
        $allowableHTML = wp_kses_allowed_html('post');	// Pre-set up post-allowed HTML

        // Add iFrames
        $allowableHTML['iframe'] = array(
            'width' => 1,
            'height' => 1,
            'src' => 1,
            'title' => 1,
            'frameborder' => 1,
            'allow' => 1,
            'allowfullscreen' => 1,
            'aria-describedby' => 1,
            'aria-details' => 1,
            'aria-label' => 1,
            'aria-labelledby' => 1,
            'aria-hidden' => 1,
            'class' => 1,
            'id' => 1,
            'style' => 1,
            'role' => 1,
            'data-*' => 1,
        );

        return $allowableHTML;
    }
}

// Function to integrate a video by  post ID
function learn_embed_video_by_post_id($post_id = 0, $video_meta_id = '') {
    
    $videoService = get_post_meta($post_id, LEARN_PREFIX . 'vid_dat_service', true);
    $videoID = get_post_meta($post_id, LEARN_PREFIX . 'vid_dat_vid_id', true);
    $videoHash = get_post_meta($post_id, LEARN_PREFIX . 'vid_dat_vid_hash', true);
    $videoURL = get_post_meta($post_id, LEARN_PREFIX . $video_meta_id, true);		// Back up, in case the above aren't filled out...

    $videoFrame = learn_render_embed_video($videoService, $videoID, $videoURL, $videoHash);
    $videoFrame = str_replace('||TITLE||', get_the_title($post_id), $videoFrame);

    return $videoFrame;
}

// Function to render a video thumbnail by post ID
function learn_render_video_thumbnail_by_post_id($post_id = 0, $video_meta_id = '') {
    
    $videoService = get_post_meta($post_id, LEARN_PREFIX . 'vid_dat_service', true);
    $videoID = get_post_meta($post_id, LEARN_PREFIX . 'vid_dat_vid_id', true);
    $videoHash = get_post_meta($post_id, LEARN_PREFIX . 'vid_dat_vid_hash', true);
    $videoURL = get_post_meta($post_id, LEARN_PREFIX . $video_meta_id, true);		// Back up, in case the above aren't filled out...

    $videoThumb = learn_render_video_thumbnail($videoService, $videoID, $videoURL, $videoHash);
    $videoThumb = str_replace('||TITLE||', get_the_title($post_id), $videoThumb);

    return $videoThumb;
}

// Function to renders an embed iFrame to play videos from.
// Can be given a YouTube or Vimeo video (Maybe more in future)
// Returns a blank string if it doesn't recognize the video service
if (!function_exists('learn_render_embed_video')) {

    function learn_render_embed_video($video_service = '', $video_id = '', $video_url = '', $video_hash = '') {

        $output = '';

        if ((empty($video_service) || empty($video_id)) && !empty($video_url)) {
            $tempVidData = learn_video_get_id_service($video_url);

            if ($tempVidData) {
                $video_service = $tempVidData['service'];
                $video_id = $tempVidData['video_id'];
                $maybe_hash = (!empty($tempVidData['hash'])) ? $tempVidData['hash'] : '';
            }
        }

        // And finally, we really need this data...
        if (!empty($video_service) && !empty($video_id)) {
            if ($video_service === 'youtube') {
                $output = sprintf(
                    '<iframe src="https://www.youtube.com/embed/%1$s?modestbranding=1" title="||TITLE||" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
					esc_attr($video_id),
                    );
            }
            else if ($video_service === 'vimeo') {
                $output = sprintf(
                    '<iframe src="https://player.vimeo.com/video/%1$s?%2$sbyline=0&portrait=0" title="||TITLE||" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>',
					esc_attr($video_id),
					(!empty($maybe_hash)) ? "h={$maybe_hash}&" : ""
                );
            }
        }

        return $output;
    }
}

// Function to renders an embed iFrame to play videos from.
// Can be given a YouTube or Vimeo video (Maybe more in future)
// Returns a blank string if it doesn't recognize the video service (video_thumbnail)
if (!function_exists('learn_render_video_thumbnail')) {
    
    function learn_render_video_thumbnail($video_service = '', $video_id = '', $video_url = '', $video_hash = '') {
        
        $output = '';

        if ((empty($video_service) || empty($video_id)) && !empty($video_url)) {
            $tempVidData = learn_video_get_id_service($video_url);

            if ($tempVidData) {
                $video_service = $tempVidData['service'];
                $video_id = $tempVidData['video_id'];
                $maybe_hash = (!empty($tempVidData['hash'])) ? $tempVidData['hash'] : '';
            }
        }

        // And finally, we really need this data...
        if (!empty($video_service) && !empty($video_id)) {
            if ($video_service === 'youtube') {
                $output = sprintf(
                    '<img srcset="https://img.youtube.com/vi/%1$s/maxresdefault.jpg 640w, https://img.youtube.com/vi/%1$s/hqdefault.jpg 640w, https://img.youtube.com/vi/%1$s/mqdefault.jpg 200w, https://img.youtube.com/vi/%1$s/sddefault.jpg 100w" sizes="(max-width: 640px) 100vw, 640px" src="https://img.youtube.com/vi/%1$s/maxresdefault.jpg" alt="||TITLE||" />',
					esc_attr($video_id),
                    );
            }
            else if ($video_service === 'vimeo') {
                $output = sprintf(
                    '<img srcset="https://vumbnail.com/%1$s.jpg 640w, https://vumbnail.com/%1$s_large.jpg 640w, https://vumbnail.com/%1$s_medium.jpg 200w, https://vumbnail.com/%1$s_small.jpg 100w" sizes="(max-width: 640px) 100vw, 640px" src="https://vumbnail.com/%1$s.jpg" alt="||TITLE||" />',
					esc_attr($video_id),
                );
            }
        }

        return $output;
    }
}

// Function that takes a URL, identifies the service, and isolates the video ID.
// And  it's returns false if it could not identify the service. 
if(!function_exists('learn_video_get_id_service')) {

    function learn_video_get_id_service($video_url = '') {

        $output = false;

        if (strpos(strtolower($video_url), 'youtu') !== false) {	// There's a youtube video here!

            if (preg_match("#(?<=v=|v\/|vi=|vi\/|youtu.be\/|embed\/)[a-zA-Z0-9_-]{11}#", $video_url, $youtube_id))
            {
                $output['service'] = 'youtube';
                $output['video_id'] = $youtube_id[0];
                $output['full_url'] = sprintf('https://www.youtube.com/watch?v=%s', $youtube_id[0]);
            }
        } else if (strpos(strtolower($video_url), 'vimeo') !== false) {	// There's a Vimeo video here!
        
            if (preg_match('%(?:<iframe [^>]*src=")?(?:https?:\/\/(?:[\w]+\.)*vimeo\.com(?:[\/\w:]*(?:\/videos)?)?\/([0-9]+)[^\s]*)"?(?:[^>]*><\/iframe>)?(?:<p>.*<\/p>)?%', $video_url, $vimeo_id)) {
                $output['service'] = 'vimeo';
                $output['video_id'] = $vimeo_id[1];
                $output['full_url'] = sprintf('https://vimeo.com/%s', $vimeo_id[1]);
            }

            // SUUUUUPER hacky way of testing to see if the URL contains a hash... This parameter is used for private videos to be shared. It's required in the iFrame.
            $parameters = parse_url($video_url, PHP_URL_QUERY);
            if (!empty($parameters)) {
                parse_str($parameters, $possibleResults);
                if (!empty($possibleResults['h'])) {
                    $output['hash'] = $possibleResults['h'];
                }
            }
        }

        return $output;
    }
}

// Function to renders a numerical pagination system
if (!function_exists('learn_get_pagination')) {

    function learn_get_pagination($the_query = null) {

        if ($the_query == null) {
            global $wp_query;
            $the_query = $wp_query;
        }

        // 'paged' is used in ARCHIVE pages, and 'page' elsewhere, for some reason	
        if (get_query_var('paged')) {
            $paged = get_query_var('paged');
        } elseif (get_query_var('page')) {
            $paged = get_query_var('page');
        } else {
            $paged = 1;
        }

        $big = 999999999;
        $paginatedLinks = paginate_links(array(
            'base' => str_replace($big, '%#%', get_pagenum_link($big)),
            'format' => '?paged=%#%',
            'current' => max(1, $paged),
            'total' => $the_query->max_num_pages,
            'end_size' => 5,
            'mid_size' => 5,
            'type' => 'array',
            'show_all' => false,
            'prev_text' => '«',
            'next_text' => '»',
        ));

        ob_start();
        if (is_array($paginatedLinks)) {
            echo '<div class="page-navigation" role="navigation">';

            foreach ($paginatedLinks as $linky) {
                echo $linky;
            }

            echo '</div>';
        }

        return ob_get_clean();
    }
}

// Function to renders a numerical pagination system with URL
if (!function_exists('learn_get_pagination_with_url')) {

    function learn_get_pagination_with_url($the_query = null, $url = "", $args_to_keep = array()) {
        global $wp_rewrite;

        $big = 999999999;

        if ($the_query == null) {
            global $wp_query;
            $the_query = $wp_query;
        }

        // Checking for existing /page/X url and removing it if necessary
        $isPaged = strpos($url, '/page');
        if ($isPaged !== false) {
            $url = substr($url, 0, $isPaged);
        }

        // Adding a trailing slash, just in case...
        if (substr($url, (strlen($url) - 1), 1) !== '/') {
            $url = $url . '/';
        }

        // 'paged' is used in ARCHIVE pages, and 'page' elsewhere, for some reason	
        if (get_query_var('paged')) {
            $paged = get_query_var('paged');
        } elseif (get_query_var('page')) {
            $paged = get_query_var('page');
        } else {
            $paged = 1;
        }

        $paginatedLinks = paginate_links(array(
            'base' => str_replace($big, '%#%', get_pagenum_link($big)),
            'format' => '?paged=%#%',
            'current' => max(1, $paged),
            'total' => $the_query->max_num_pages,
            'end_size' => 5,
            'mid_size' => 5,
            'type' => 'array',
            'show_all' => false,
            'prev_text' => '«',
            'next_text' => '»',
        ));

        ob_start();

        if (is_array($paginatedLinks)) {

			echo '<div class="page-navigation" role="navigation">';

			foreach ($paginatedLinks as $linky) {
				echo $linky;
			}

			echo '</div>';
		}

		return ob_get_clean();
    }
}

// A recursive function to get the top-most parent from a subcategory
if (!function_exists('learn_get_parent_taxonomy')) {
    
    function learn_get_parent_taxonomy($current_tax = null, $taxonomy_type = '') {

        if ($current_tax && !is_wp_error($current_tax) && $current_tax instanceof WP_Term && !empty($taxonomy_type)) {
            if ($current_tax->parent === 0) {
                return $current_tax;
            } else {
                return learn_get_parent_taxonomy(get_term_by('term_id', $current_tax->parent, $taxonomy_type));
            }
        }

        return $current_tax;
    }
}

// The re-written functions WordPress cause they suck

if (!function_exists('learn_get_pagenum_link')) {

    function learn_get_pagenum_link($pagenum = 1, $requested_url = null. $escape = true) {
        global $wp_rewrite;

        $pagenum = (int) $pagenum;

        // CFD Note: Uses the submitted URL and falls back to default functionality if none exists
        $request = $requested_url ? remove_query_arg('page', parse_url($requested_url, PHP_URL_PATH)) : remove_query_arg('paged');

        $home_root = parse_url(home_url());
        $home_root = (isset($home_root['path'])) ? $home_root['path'] : '';
        $home_root = preg_quote($home_root, '|');

        $request = preg_replace('|^' . $home_root . '|i', '', $request);
        $request = preg_replace('|^/+|', '', $request);

        if (!$wp_rewrite -> using_permalinks() || is_admin()) {
            $base = trailingslashit(get_bloginfo('url'));

			if ($pagenum > 1) {
				$result = add_query_arg('paged', $pagenum, $base . $request);
			} else {
				$result = $base . $request;
			}
		} else {
			$qs_regex = '|\?.*?$|';
			preg_match($qs_regex, $request, $qs_match);

			if (!empty($qs_match[0])) {
				$query_string = $qs_match[0];
				$request = preg_replace($qs_regex, '', $request);
			} else {
				$query_string = '';
			}

			$request = preg_replace("|$wp_rewrite->pagination_base/\d+/?$|", '', $request);
			$request = preg_replace('|^' . preg_quote($wp_rewrite->index, '|') . '|i', '', $request);
			$request = ltrim($request, '/');

			$base = trailingslashit(get_bloginfo('url'));

			if ($wp_rewrite->using_index_permalinks() && ($pagenum > 1 || '' !== $request)) {
				$base .= $wp_rewrite->index . '/';
			}

			if ($pagenum > 1) {
				$request = ((!empty($request)) ? trailingslashit($request) : $request) . user_trailingslashit($wp_rewrite->pagination_base . '/' . $pagenum, 'paged');
			}

			$result = $base . $request . $query_string;
        }

        /**
		 * Filters the page number link for the current request.
		 *
		 * @since 2.5.0
		 * @since 5.2.0 Added the `$pagenum` argument.
		 *
		 * @param string $result  The page number link.
		 * @param int    $pagenum The page number.
		 */
		$result = apply_filters('get_pagenum_link', $result, $pagenum);

		if ($escape) {
			return esc_url($result);
		} else {
			return esc_url_raw($result);
		}
    }
}


// This function creates a custom filter for the sub-attributes in the shortcode (the plant type can be changed for an another element type, 
//by example car type for a car shortcode)
function learn_custom_filter_type_sub_att($attributes = array())
{
	$rd = shortcode_atts(array(
		'type_post' => 'learn_plant',
		'nombre' => -1,
		'ordre' => 'DESC',
		'trier' => 'DATE',
		'offset' => '0',
		'plant_type' => '',
		'search' => ''
	), $attributes);
	
	$rd['plant_type'] = strtolower(sanitize_text_field($rd['plant_type']));

	if (get_query_var('paged')) {
		$paged = get_query_var('paged');
	} elseif (get_query_var('page')) {
		$paged = get_query_var('page');
	} else {
		$paged = 1;
	}

	$rd['page'] = $paged;

	return $rd;
}


// This function creates a custom filter for the submitted variables in the ajax function (the plant type can be changed for an another element type, 
//by example car type for a car ajax function)
function learn_custom_filter_ajax_submitted_vars($submittedVars)
{
	if (empty($submittedVars)) {
		return array();
	}

	if (!empty($submittedVars['plant_type'])) {
		if (!is_array($submittedVars['plant_type'])) {
			$submittedVars['plant_type'] = array_filter(explode("|", $submittedVars['plant_type']));
		}
		foreach ($submittedVars['plant_type'] as $data => $val) {
			$submittedVars['plant_type'][$data] = sanitize_text_field($val);
		}
	}

	if (!empty($submittedVars['search'])) {

		$submittedVars['search'][$data] = sanitize_text_field($submittedVars['search']);

	}
	return $submittedVars;
}

// This function creates a custom filter for prepare the query
function learn_custom_filter_prepare_query($filteredData)
{

	if (empty($filteredData)) {
		return array();
	}
	$args = array(
		'post_type' => 'tri_plant',
		'post_status' => array('publish'),
		'orderby' => 'TITLE',
		'order' => 'ASC',
		'posts_per_page' => 30,
		'fields' => 'ids',
		'paged' => $filteredData['page'],
	);
	$taxQuery = array();
	$metaQuery = array();


	if (!empty($submittedVars['plant_type'])) {
		if ($submittedVars['plant_type'][0] != 'all') {
			$taxQuery[] = array(
				'taxonomy' => 'plant_type',
				'terms' => $submittedVars['plant_type'],
				'field' => 'slug',
			);
		} else {
			$taxQuery = array();
		}
	}

	if (count($metaQuery) > 1) {
		$metaQuery['relation'] = 'OR';
	}

	// Now to add it to our query...
	if (count($metaQuery) > 0) {
		$args['meta_query'] = $metaQuery;
	}

	// If we have more than one taxonomy query... Make sure it selects ALL of the variables
	if (count($taxQuery) > 1) {
		$taxQuery['relation'] = 'AND';
	}

	// Now to add it to our query...
	if (count($taxQuery) > 0) {
		$args['tax_query'] = $taxQuery;
	}
	return $args;

}

// This function creates a custom filter for alter the query with the submitted variables (the plant type can be changed for an another element type, 
//by example car type)
function learn_custom_filter_alter_query_with_submitted_vars($args, $submittedVars)
{
	if (empty($submittedVars)) {
		return $args;
	}

	$taxQuery = array();
	$metaQuery = array();

	if (!empty($submittedVars['plant_type'])) {
		if ($submittedVars['plant_type'][0] != 'all') {
			$taxQuery[] = array(
				'taxonomy' => 'tri_plant_type',
				'terms' => $submittedVars['plant_type'],
				'field' => 'slug',
			);
		} else {
			$taxQuery = array();
		}
	}

	if (count($taxQuery) > 1) {
		$taxQuery['relation'] = 'AND';
	}

	if (count($taxQuery) > 0) {
		$args['tax_query'][] = $taxQuery;
	}

	if (!empty($submittedVars['search'])) {
		//wp_mail('elessard@triaxe.ca', 'Search', $submittedVars['search']);
		$metaQuery[] = array(
			'key' => 'title',
			'value' => $submittedVars['search'],
			'compare' => 'LIKE'
		);

		$metaQuery[] = array(
			'key' => LEARN_PREFIX . 'plant_name',
			'value' => $submittedVars['search'],
			'compare' => 'LIKE'
		);

		$metaQuery[] = array(
			'key' => LEARN_PREFIX . 'plant_name_scientific',
			'value' => $submittedVars['search'],
			'compare' => 'LIKE'
		);

		$metaQuery[] = array(
			'key' => LEARN_PREFIX . 'plant_name_french',
			'value' => $submittedVars['search'],
			'compare' => 'LIKE'
		);

		$metaQuery[] = array(
			'key' => LEARN_PREFIX . 'plant_name_english',
			'value' => $submittedVars['search'],
			'compare' => 'LIKE'
		);

		$metaQuery[] = array(
			'key' => LEARN_PREFIX . 'plant_unique_number',
			'value' => $submittedVars['search'],
			'compare' => 'LIKE'
		);
	}

	if (count($metaQuery) > 1) {
		$metaQuery['relation'] = 'OR';
	}

	// Now to add it to our query...
	if (count($metaQuery) > 0) {
		$args['meta_query'] = $metaQuery;
	}
	
	return $args;
}


if (!function_exists('learn_paginate_links')) {
	
    // Custom pagination function
    function learn_paginate_links($args = '')
	{
		global $wp_query, $wp_rewrite;

		// Setting up default values based on the current URL.
		$pagenum_link = (isset($args['pagenum_link'])) ? html_entity_decode(learn_get_pagenum_link(1, $args['pagenum_link'])) : html_entity_decode(get_pagenum_link());
		$url_parts = explode('?', $pagenum_link);

		// Get max pages and current page out of the current query, if available.
		$total = isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1;
		$current = get_query_var('paged') ? (int) get_query_var('paged') : 1;

		// Append the format placeholder to the base URL.
		$pagenum_link = trailingslashit($url_parts[0]) . '%_%';

		// URL base depends on permalink settings.
		$format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit($wp_rewrite->pagination_base . '/%#%', 'paged') : '?paged=%#%';

		$defaults = array(
			'base' => $pagenum_link, // http://example.com/all_posts.php%_% : %_% is replaced by format (below).
			'format' => $format, // ?page=%#% : %#% is replaced by the page number.
			'total' => $total,
			'current' => $current,
			'aria_current' => 'page',
			'show_all' => false,
			'prev_next' => true,
			'prev_text' => __("&laquo; Précédent", 'triade-custom-posts'),
			'next_text' => __("Suivant &raquo;", 'triade-custom-posts'),
			'end_size' => 1,
			'mid_size' => 2,
			'type' => 'plain',
			'add_args' => array(), // Array of query args to add.
			'add_fragment' => '',
			'before_page_number' => '',
			'after_page_number' => '',
		);

		$args = wp_parse_args($args, $defaults);

		if (!is_array($args['add_args'])) {
			$args['add_args'] = array();
		}

		// Merge additional query vars found in the original URL into 'add_args' array.
		if (isset($url_parts[1])) {
			// Find the format argument.
			$format = explode('?', str_replace('%_%', $args['format'], $args['base']));
			$format_query = isset($format[1]) ? $format[1] : '';
			wp_parse_str($format_query, $format_args);

			// Find the query args of the requested URL.
			wp_parse_str($url_parts[1], $url_query_args);

			// Remove the format argument from the array of query arguments, to avoid overwriting custom format.
			foreach ($format_args as $format_arg => $format_arg_value) {
				unset($url_query_args[$format_arg]);
			}

			$args['add_args'] = array_merge($args['add_args'], urlencode_deep($url_query_args));
		}

		// Who knows what else people pass in $args.
		$total = (int) $args['total'];
		if ($total < 2) {
			return;
		}
		$current = (int) $args['current'];
		$end_size = (int) $args['end_size']; // Out of bounds? Make it the default.
		if ($end_size < 1) {
			$end_size = 1;
		}
		$mid_size = (int) $args['mid_size'];
		if ($mid_size < 0) {
			$mid_size = 2;
		}

		$add_args = $args['add_args'];
		$r = '';
		$page_links = array();
		$dots = false;

		if ($args['prev_next'] && $current && 1 < $current):
			$link = str_replace('%_%', 2 == $current ? '' : $args['format'], $args['base']);
			$link = str_replace('%#%', $current - 1, $link);
			if ($add_args) {
				$link = add_query_arg($add_args, $link);
			}
			$link .= $args['add_fragment'];

			$page_links[] = sprintf(
				'<a class="prev page-numbers" href="%s">%s</a>',
				/**
				 * Filters the paginated links for the given archive pages.
				 *
				 * @since 3.0.0
				 *
				 * @param string $link The paginated link URL.
				 */
				esc_url(apply_filters('paginate_links', $link)),
				$args['prev_text']
			);
		endif;

		for ($n = 1; $n <= $total; $n++):
			if ($n == $current):
				$page_links[] = sprintf(
					'<span aria-current="%s" class="page-numbers current">%s</span>',
					esc_attr($args['aria_current']),
					$args['before_page_number'] . number_format_i18n($n) . $args['after_page_number']
				);

				$dots = true;
			else:
				if ($args['show_all'] || ($n <= $end_size || ($current && $n >= $current - $mid_size && $n <= $current + $mid_size) || $n > $total - $end_size)):
					$link = str_replace('%_%', 1 == $n ? '' : $args['format'], $args['base']);
					$link = str_replace('%#%', $n, $link);
					if ($add_args) {
						$link = add_query_arg($add_args, $link);
					}
					$link .= $args['add_fragment'];

					$page_links[] = sprintf(
						'<a class="page-numbers" href="%s">%s</a>',
						/** This filter is documented in wp-includes/general-template.php */
						esc_url(apply_filters('paginate_links', $link)),
						$args['before_page_number'] . number_format_i18n($n) . $args['after_page_number']
					);

					$dots = true;
				elseif ($dots && !$args['show_all']):
					$page_links[] = '<span class="page-numbers dots">' . __("&hellip;", 'triade-custom-posts') . '</span>';

					$dots = false;
				endif;
			endif;
		endfor;

		if ($args['prev_next'] && $current && $current < $total):
			$link = str_replace('%_%', $args['format'], $args['base']);
			$link = str_replace('%#%', $current + 1, $link);
			if ($add_args) {
				$link = add_query_arg($add_args, $link);
			}
			$link .= $args['add_fragment'];

			$page_links[] = sprintf(
				'<a class="next page-numbers" href="%s">%s</a>',
				/** This filter is documented in wp-includes/general-template.php */
				esc_url(apply_filters('paginate_links', $link)),
				$args['next_text']
			);
		endif;

		switch ($args['type']) {
			case 'array':
				return $page_links;

			case 'list':
				$r .= "<ul class='page-numbers'>\n\t<li>";
				$r .= implode("</li>\n\t<li>", $page_links);
				$r .= "</li>\n</ul>\n";
				break;

			default:
				$r = implode("\n", $page_links);
				break;
		}

		/**
		 * Filters the HTML output of paginated links for archives.
		 *
		 * @since 5.7.0
		 *
		 * @param string $r    HTML output.
		 * @param array  $args An array of arguments. See paginate_links()
		 *                     for information on accepted arguments.
		 */
		$r = apply_filters('paginate_links_output', $r, $args);

		return $r;
	}
}

// +------------------------------------------------------+
// | Sets up a trigger on various WP actions in order to clear the cache ourselves
// +------------------------------------------------------+
function learn_setup_pre_clear_cache_scripts()
{
	// Pre-configure a set of WP Actions in which to force cache clearing
	$actions = array(
		'save_post',
		'edit_post',
		'deleted_post',
		'trashed_post',
		'delete_attachment',
		'switch_theme',
		'saved_term',
		'edited_term',
		'delete_term',
	);

	// QnD action generator
	foreach ($actions as $event) {
		add_action($event, 'learn_clear_all_caches_possible');
	}
}
add_action('init', 'learn_setup_pre_clear_cache_scripts');



// +------------------------------------------------------+
// | Actually deletes the cache once a trigger from above has been... triggered
// +------------------------------------------------------+
function learn_clear_all_caches_possible()
{
	// If WP Rocket exists, clear it's cache manually
	if (defined('WP_ROCKET_VERSION')) {
		rocket_clean_domain();    // Nuke it from orbit. It's the only way to be sure.

	}
}