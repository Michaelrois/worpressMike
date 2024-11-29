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