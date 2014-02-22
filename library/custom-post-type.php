<?php
/* Bones Crime Type Example
This page walks you through creating 
a Crime type and taxonomies. You
can edit this one or copy the following code 
to create another one. 

I put this in a separate file so as to 
keep it organized. I find it easier to edit
and change things if they are concentrated
in their own file.

Developed by: Eddie Machado
URL: http://themble.com/bones/
*/

// Flush rewrite rules for Crime types
add_action( 'after_switch_theme', 'bones_flush_rewrite_rules' );

// Flush your rewrite rules
function bones_flush_rewrite_rules() {
	flush_rewrite_rules();
}

// let's create the function for the custom type
function crime() { 
	// creating (registering) the custom type 
	register_post_type( 'crime', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this Crime
		array( 'labels' => array(
			'name' => __( 'Crimes', 'bonestheme' ), /* This is the Title of the Group */
			'singular_name' => __( 'Crime', 'bonestheme' ), /* This is the individual type */
			'all_items' => __( 'All Crimes', 'bonestheme' ), /* the all items menu item */
			'add_new' => __( 'Add New', 'bonestheme' ), /* The add new menu item */
			'add_new_item' => __( 'Add New Crime', 'bonestheme' ), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __( 'Edit Crimes', 'bonestheme' ), /* Edit Display Title */
			'new_item' => __( 'New Crime', 'bonestheme' ), /* New Display Title */
			'view_item' => __( 'View Crime', 'bonestheme' ), /* View Display Title */
			'search_items' => __( 'Search Crime', 'bonestheme' ), /* Search Custom Type Title */ 
			'not_found' =>  __( 'Nothing found in the Database.', 'bonestheme' ), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __( 'Nothing found in Trash', 'bonestheme' ), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'This is the example Crime type', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the Crime type menu */
			'rewrite'	=> array( 'slug' => 'crime', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'crime', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'sticky')
		) /* end of options */
	); /* end of register Crime */
	
	/* this adds your post categories to your Crime type */
	register_taxonomy_for_object_type( 'category', 'custom_type' );
	
}

	// adding the function to the Wordpress init
	add_action( 'init', 'crime');
	
	/*
	for more information on taxonomies, go here:
	http://codex.wordpress.org/Function_Reference/register_taxonomy
	*/
	
	// now let's add Type of Crimes (these act like categories)
	register_taxonomy( 'crime_type', 
		array('crime'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
		array('hierarchical' => true,     /* if this is true, it acts like categories */
			'labels' => array(
				'name' => __( 'Type of Crime', 'bonestheme' ), /* name of the custom taxonomy */
				'singular_name' => __( 'Type of Crime', 'bonestheme' ), /* single taxonomy name */
				'search_items' =>  __( 'Search Type of Crimes', 'bonestheme' ), /* search title for taxomony */
				'all_items' => __( 'All Type of Crimes', 'bonestheme' ), /* all title for taxonomies */
				'parent_item' => __( 'Parent Type of Crime', 'bonestheme' ), /* parent title for taxonomy */
				'parent_item_colon' => __( 'Parent Type of Crime:', 'bonestheme' ), /* parent taxonomy title */
				'edit_item' => __( 'Edit Type of Crime', 'bonestheme' ), /* edit custom taxonomy title */
				'update_item' => __( 'Update Type of Crime', 'bonestheme' ), /* update title for taxonomy */
				'add_new_item' => __( 'Add New Type of Crime', 'bonestheme' ), /* add new title for taxonomy */
				'new_item_name' => __( 'New Type of Crime Name', 'bonestheme' ) /* name title for taxonomy */
			),
			'show_admin_column' => true, 
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'crime_type' ),
		)
	);
 
    //Edit the Category List
	add_filter( 'manage_edit-crime_type_columns', 'set_custom_edit_crime_type_columns' );
	// Add to admin_init function   
	add_filter( 'manage_crime_type_custom_column', 'manage_crime_type_columns', 10, 3);

	// API hidden from user
function api() { 
	// creating (registering) the custom type 
	register_post_type( 'api', 
	 	// let's now add all the options for this post type
		array('labels' => array(
			'name' => __('API', 'bonestheme'), /* This is the Title of the Group */
			'singular_name' => __('API', 'bonestheme'), /* This is the individual type */
			'all_items' => __('All APIs', 'bonestheme'), /* the all items menu item */
			'add_new' => __('Add New', 'bonestheme'), /* The add new menu item */
			'add_new_item' => __('Add New API', 'bonestheme'), /* Add New Display Title */
			'edit' => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
			'edit_item' => __('Edit API', 'bonestheme'), /* Edit Display Title */
			'new_item' => __('New API', 'bonestheme'), /* New Display Title */
			'view_item' => __('View API', 'bonestheme'), /* View Display Title */
			'search_items' => __('Search API', 'bonestheme'), /* Search Custom Type Title */ 
			'not_found' =>  __('Nothing found in the Database.', 'bonestheme'), /* This displays if there are no entries yet */ 
			'not_found_in_trash' => __('Nothing found in Trash', 'bonestheme'), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
			), /* end of arrays */
			'description' => __( 'API', 'bonestheme' ), /* Custom Type Description */
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the custom post type menu */
			'rewrite'	=> array( 'slug' => 'api', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'api', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'sticky')
	 	) /* end of options */
	); /* end of register post type */
	
} 
add_action( 'init', 'api');
?>
