<?php
/*
Plugin Name: Task Plugin
Plugin URI: https://taskplugin.com
Description: This plugin provides a shortcode to list posts, with parameters. It also registers a couple of post types and tacxonomies to work with.
Version: 1.0
Author: Aswin Dahal
Author URI: http://aswin.com
License: ADV3
*/
// register custom post type to work with
add_action('init', 'rmcc_create_post_type');
function rmcc_create_post_type()
{  // clothes custom post type
    // set up labels
    $labels = array(
        'name' => 'Events',
        'singular_name' => 'Event',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Event',
        'edit_item' => 'Edit Event',
        'new_item' => 'New Event',
        'all_items' => 'All Events',
        'view_item' => 'View Event',
        'search_items' => 'Search Events',
        'not_found' =>  'No Events Found',
        'not_found_in_trash' => 'No Events found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Events',
    );
    register_post_type(
        'events',
        array(
            'labels' => $labels,
            'has_archive' => true,
            'public' => true,
            'hierarchical' => true,
            'supports' => array('title', 'editor', 'excerpt', 'custom-fields', 'thumbnail', 'page-attributes'),
            'taxonomies' => array('post_tag', 'category', 'event_type','month'),
            'exclude_from_search' => true,
            'capability_type' => 'post',
        )
    );
}

// Register taxonomies to go with the post type
add_action('init', 'create_taxonomies', 0);
function create_taxonomies()
{
    // Event Type taxonomy
    $labels = array(
        'name'              => _x('Event Types', 'taxonomy general name'),
        'singular_name'     => _x('Event_Type', 'taxonomy singular name'),
        'search_items'      => __('Search Event Types'),
        'all_items'         => __('All Event Types'),
        'parent_item'       => __('Parent Event Type'),
        'parent_item_colon' => __('Parent Event Type:'),
        'edit_item'         => __('Edit Event Type'),
        'update_item'       => __('Update Event Type'),
        'add_new_item'      => __('Add New Event Type'),
        'new_item_name'     => __('New Event Type'),
        'menu_name'         => __('Event Types'),
    );
    register_taxonomy(
        'event_type',
        'events',
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'query_var' => true,
            'rewrite' => true,
            'show_admin_column' => true
        )
    );
    // Month taxonomy
    $labels = array(
      'name'              => _x('Event_Months', 'taxonomy general name'),
      'singular_name'     => _x('Event_Month', 'taxonomy singular name'),
      'search_items'      => __('Search Month'),
      'all_items'         => __('All Month'),
      'parent_item'       => __('Parent Month Type'),
      'parent_item_colon' => __('Parent Month Type:'),
      'edit_item'         => __('Edit Month Type'),
      'update_item'       => __('Update Month Type'),
      'add_new_item'      => __('Add New Month Type'),
      'new_item_name'     => __('New Month Type'),
      'menu_name'         => __('Event Month'),
    );
    register_taxonomy(
      'event_month',
      'events',
      array(
          'hierarchical' => true,
          'labels' => $labels,
          'query_var' => true,
          'rewrite' => true,
          'show_admin_column' => true
      )
    );
}




// create shortcode to list all clothes which come in blue
add_shortcode('list-posts-basic', 'post_listing_shortcode1');
function post_listing_shortcode1($atts)
{
    ob_start();
    $query = new WP_Query(array(
        'post_type' => 'events',
        'event_type' => 'webinar',
        'posts_per_page' => 10,
        'order' => 'ASC',
        'orderby' => 'title',
    ));
    if ($query->have_posts()) { ?>
        <ul class="events-listing">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <li class="uk-heading-divider" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </li>
            <?php endwhile;
            wp_reset_postdata(); ?>
        </ul>
<?php $myvariable = ob_get_clean();
        return $myvariable;
    }
}

/** Rest API */

function custom_endpoint($data)
{
    $posts = get_posts(array(
        'numberposts'   => 10,
        //Here we can get more than one post type. Useful to a home page.
        'post_type'     => 'events',
    ));


    if (empty($posts)) {
        return null;
    }


    $args = array();

    foreach ($posts as $post) {

        //Get informations that is not avaible in get_post() function and store it in variables.
        $category = get_the_category($post->ID);
        $img_thumb = get_the_post_thumbnail_url($post->ID, 'thumbnail');       // Thumbnail (default 150px x 150px max)
        $img_medium = get_the_post_thumbnail_url($post->ID, 'medium');          // Medium resolution (default 300px x 300px max)
        $img_large = get_the_post_thumbnail_url($post->ID, 'large');           // Large resolution (default 640px x 640px max)
        $img_full = get_the_post_thumbnail_url($post->ID, 'full');            // Full resolution (original size uploaded)

        //Adds the informations to the post object.
        $post->category = $category;
        $post->img_tumb = $img_thumb;
        $post->img_medium = $img_medium;
        $post->img_large = $img_large;
        $post->img_full = $img_full;

        array_push($args, $post);
    }
    return $args;
}
add_action('rest_api_init', function () {
    register_rest_route('task/v1', '/events/', array(
        'methods' => 'GET',
        'callback' => 'custom_endpoint',
        'args' => [
            'page' => [
              'description' => 'Current page',
              'type' => "integer",
            ],
            'per_page' => [
              'description' => 'Items per page',
              'type' => "integer",
            ]
          ],
    ));
});
