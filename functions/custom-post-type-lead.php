<?php
// Register Custom Post Type
function register_cpt_lead() {

	$labels = array(
		'name'                  => _x( 'Leads', 'Post Type General Name', 'wooleads' ),
		'singular_name'         => _x( 'Lead', 'Post Type Singular Name', 'wooleads' ),
		'menu_name'             => __( 'Leads', 'wooleads' ),
		'name_admin_bar'        => __( 'Leads', 'wooleads' ),
		'archives'              => __( 'Lead Archives', 'wooleads' ),
		'attributes'            => __( 'Lead Attributes', 'wooleads' ),
		'parent_item_colon'     => __( 'Parent Lead:', 'wooleads' ),
		'all_items'             => __( 'All Leads', 'wooleads' ),
		'add_new_item'          => __( 'Add New Lead', 'wooleads' ),
		'add_new'               => __( 'Add New', 'wooleads' ),
		'new_item'              => __( 'New Lead', 'wooleads' ),
		'edit_item'             => __( 'Edit Lead', 'wooleads' ),
		'update_item'           => __( 'Update Lead', 'wooleads' ),
		'view_item'             => __( 'View Lead', 'wooleads' ),
		'view_items'            => __( 'View Leads', 'wooleads' ),
		'search_items'          => __( 'Search Lead', 'wooleads' ),
		'not_found'             => __( 'Not found', 'wooleads' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'wooleads' ),
		'featured_image'        => __( 'Featured Image', 'wooleads' ),
		'set_featured_image'    => __( 'Set featured image', 'wooleads' ),
		'remove_featured_image' => __( 'Remove featured image', 'wooleads' ),
		'use_featured_image'    => __( 'Use as featured image', 'wooleads' ),
		'insert_into_item'      => __( 'Insert into Lead', 'wooleads' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Lead', 'wooleads' ),
		'items_list'            => __( 'Leads list', 'wooleads' ),
		'items_list_navigation' => __( 'Leads list navigation', 'wooleads' ),
		'filter_items_list'     => __( 'Filter Leads list', 'wooleads' ),
	);
	$args = array(
		'label'                 => __( 'Lead', 'wooleads' ),
		'description'           => __( 'Leads', 'wooleads' ),
		'labels'                => $labels,
		'supports'              => array( 'custom-fields' ),
		'taxonomies'            => array( 'type' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
		'rest_base'             => 'lead',
	);
	register_post_type( 'lead', $args );

}
add_action( 'init', 'register_cpt_lead', 0 );