<?php
// Enqueue styles
function load_stylesheets()
{

	wp_enqueue_style('uikit-css', get_stylesheet_directory_uri() . '/assets/css/uikit.css');
	wp_enqueue_style('style', get_stylesheet_uri());
	wp_enqueue_script('main_js', get_template_directory_uri() . '/js/main.js', array(), '1.0.0', true);
	wp_enqueue_script('uikit-js', get_stylesheet_directory_uri() . '/assets/js/uikit.min.js', 'true');
	wp_enqueue_script('uikit-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', 'true');
	wp_enqueue_script('uikit-icon', get_stylesheet_directory_uri() . '/assets/js/uikit-icons.min.js', 'true');
}
add_action('wp_enqueue_scripts', 'load_stylesheets');


add_theme_support('post-thumbnails');
add_post_type_support('news', 'thumbnail');


/** REST API */

add_action('rest_api_init', function () {
	register_rest_route('task/v2', 'events', [
		'methods' => 'GET',
		'callback' => 'wl_posts'
	]);
});

function wl_posts()
{
	$args = [];

	$args['post_type'] = 'events';

	$get_posts = new WP_Query;
	$posts = $get_posts->query($args);

	$data = [];
	$i = 0;

	foreach ($posts as $post) {
		$data[$i]['id'] = $post->ID;
		$data[$i]['title'] = $post->post_title;
		$data[$i]['content'] = $post->post_content;
		$data[$i]['slug'] = $post->post_name;
		$data[$i]['featured_image']['thumbnail'] =
			get_the_post_thumbnail_url($post->ID, 'thumbnail');

		$i++;
	}
	return $data;
}
