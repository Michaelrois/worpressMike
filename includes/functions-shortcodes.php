<?php
// What are you doing here? Get the fuck out of here!
if ( !defined( 'ABSPATH' ) ) { 
    exit; 
}

// Shortcodes for the theme and any related functions
// In each function plant can be replaced by any other element

// Sets up shortcodes in the WP Init action, to better let WordPress initialize properly first.

// This functions add the shortcodes function in WordPress Init action.
function learn_custom_setup_shortcodes() {

    add_shortcode('learn_plants', 'learn_plants');
    add_shortcode('learn_plant_single', 'learn_plant_single');
	add_shortcode('learn_plant_types_list', 'learn_plant_types_list');
}

add_action('init', 'learn_custom_setup_shortcodes');

// Shortcode for the plants
function learn_plants($atts) {

    global $wp;

    $filteredData = learn_custom_filter_type_sub_att($atts);
    $submit = learn_custom_filter_ajax_submitted_vars($_REQUEST);

	$args = learn_custom_filter_prepare_query($filteredData);

	$args = learn_custom_filter_alter_query_with_submitted_vars($args, $submit);

	$query = new WP_Query($args);

	$args_to_keep = array();

	foreach (array('plant_type', 'search') as $aaaaaargs) {
		if (isset($submit[$aaaaaargs])) {
			$args_to_keep[$aaaaaargs] = urlencode((is_array($submit[$aaaaaargs])) ? implode("|", $submit[$aaaaaargs]) : $submit[$aaaaaargs]);
		}
	}

    ob_start();
	if (!is_wp_error($query) && $query->have_posts()) {
		?>

		<div class="learn-plant-list learn-plant-result-list">
			<?php
			while ($query->have_posts()) {
				$query->the_post();
				include(LEARN_CUSTOM_ABSPATH . "templates/learn-plant-card-template.php");
				?>

				<?php
			}
			?>
			<section class="learn-pagination">
				<?php
				if (is_front_page()) {
					echo learn_get_pagination($query);
				} else {
					echo learn_get_pagination_with_url($query, home_url($wp->request), $args_to_keep);
				}
				?>
			</section>
		</div>
		<?php

		wp_reset_postdata();
	}

	return ob_get_clean();
}

// The shortcode for the plant types list (that's create the filter menu)
function learn_plant_types_list()
{
	wp_enqueue_script('learn-plant-type-filter-script');
	ob_start();

	$termIds = get_terms(array(
		'taxonomy' => 'learn_plant_type',
		'hide_empty' => true,
		'orderby' => 'title',
		'order' => 'ASC',
		'hierarchical' => false,
		'fields' => 'ids'
	));

	if (!empty($termIds) && count($termIds) > 0) {
		echo '<div class="learn-plant-type-list-search">';
		echo '<input type="text" id="learn-plant-type-list-search" placeholder="Recherche">';
		echo '<i class="fa-solid fa-magnifying-glass"></i>';
		echo '</div>';
		echo '<div class="learn-plant-type-list">';
		echo '<h3>Type : <span id="tri-plant-type-list-selected">Tous<span></h3><hr>';
		echo '<div class="learn-plant-type-list-buttons">';
		echo '<p class="learn-plant-type-list-button" data-slug="all">Tous</p>';
		foreach ($termIds as $termId) {
			$term = get_term($termId);
			?>
			<p class="learn-plant-type-list-button" data-slug="<?php echo esc_attr($term->slug) ?>">
				<?php echo esc_html($term->name) ?>
			</p>
			<style>
				.learn-plant-type-list-button {
					cursor: pointer;
				}

				.fa-magnifying-glass {
					font-size: 1.2rem;
				}

				#learn-plant-type-list-search {
					outline: none;
					padding: 0.4rem 0.8rem;
					border: none;


				}

				.learn-plant-type-list-search {
					display: flex;
					align-items: center;
					justify-content: space-between;
					border-bottom: 1px #000 solid;
					margin-bottom: 3rem;

				}
			</style>
			<?php
		}
		echo '</div>';
		echo '</div>';
	}

	return ob_get_clean();
}

// This the shortcode for the single plant element
function learn_plant_single()
{
	/* wp_enqueue_script('learn-single-plant-javascript'); */
	$id = get_the_ID();
	$plant_name_scientific = get_post_meta($id, LEARN_PREFIX . 'plant_name_scientific', true);
	$plant_name_french = get_post_meta($id, LEARN_PREFIX . 'plant_name_french', true);
	$plant_name_english = get_post_meta($id, LEARN_PREFIX . 'plant_name_english', true);
	$main_image = get_post_meta($id, LEARN_PREFIX . 'plant_image', true);
	$main_image = wp_get_attachment_url($main_image);
	$secondary_images = get_post_meta($id, LEARN_PREFIX . 'plant_secondary_images', true);
	//var_dump($secondary_images);
	$secondary_images_list = array();
	if (isset($secondary_images) && is_array($secondary_images)) {
		if (count($secondary_images) > 0) {
			foreach ($secondary_images as $image) {
				$image_src = wp_get_attachment_URL($image['secondary_image'], 'thumbnail');
				array_push($secondary_images_list, $image_src);
			}
		}
	}
	$term_ids = wp_get_post_terms($id, 'learn_plant_type', array('fields' => 'ids'));
	if ($term_ids) {
		$term_id = $term_ids[0];
		$plant_type = get_term($term_id, 'learn_plant_type')->name;
	} else {
		$plant_type = 'Type Inconnu'; // or some other default value
	}


	$plant_description = get_post_meta($id, LEARN_PREFIX . 'plant_description', true);
	$plant_description = html_entity_decode($plant_description);
	$plant_species = get_post_meta($id, LEARN_PREFIX . 'plant_species', true);
	$plant_family = get_post_meta($id, LEARN_PREFIX . 'plant_family', true);
	$plant_height = get_post_meta($id, LEARN_PREFIX . 'plant_height', true);
	$plant_width = get_post_meta($id, LEARN_PREFIX . 'plant_width', true);
	$plant_spacing = get_post_meta($id, LEARN_PREFIX . 'plant_spacing', true);
	$plant_genus = get_post_meta($id, LEARN_PREFIX . 'plant_genus', true);
	$plant_zone = get_post_meta($id, LEARN_PREFIX . 'plant_zone', true);
	$plant_origin = get_post_meta($id, LEARN_PREFIX . 'plant_origin', true);
	$plant_exposition = get_post_meta($id, LEARN_PREFIX . 'plant_exposition', true);
	$plant_color = get_post_meta($id, LEARN_PREFIX . 'plant_color', true);
	$plant_bloom = get_post_meta($id, LEARN_PREFIX . 'plant_bloom', true);
	$plant_soil = get_post_meta($id, LEARN_PREFIX . 'plant_soil', true);
	$plant_exposition_options = array(
		'soleil' => 'Soleil',
		'mi-ombre' => 'Mi-Ombre',
		'ombre' => 'Ombre',
	);
	$plant_exposition_output = '';
	foreach ($plant_exposition as $key => $value) {
		if ($value == 1) {
			$plant_exposition_output = implode(', ', array_filter($plant_exposition_options, function ($key) use ($plant_exposition) {
				return $plant_exposition[$key] == 1;
			}, ARRAY_FILTER_USE_KEY));
		}
	}
	$plant_exposition = $plant_exposition_output;
	
	ob_start();
	include(LEARN_CUSTOM_ABSPATH . "templates/learn-plant-single-template.php");
	return ob_get_clean();
}