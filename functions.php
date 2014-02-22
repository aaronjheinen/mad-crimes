<?php
/*
Author: Eddie Machado
URL: htp://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, ect.
*/

/************* INCLUDE NEEDED FILES ***************/

/*
1. library/bones.php
	- head cleanup (remove rsd, uri links, junk css, ect)
	- enqueueing scripts & styles
	- theme support functions
	- custom menu output & fallbacks
	- related post function
	- page-navi function
	- removing <p> from around images
	- customizing the post excerpt
	- custom google+ integration
	- adding custom fields to user profiles
*/
require_once( 'library/bones.php' ); // if you remove this, bones will break
/*
2. library/custom-post-type.php
	- an example custom post type
	- example custom taxonomy (like categories)
	- example custom taxonomy (like tags)
*/
require_once( 'library/custom-post-type.php' ); // you can disable this if you like
/*
3. library/admin.php
	- removing some default WordPress dashboard widgets
	- an example custom dashboard widget
	- adding custom login css
	- changing text in footer of admin
*/
// require_once( 'library/admin.php' ); // this comes turned off by default
/*
4. library/translation/translation.php
	- adding support for other languages
*/
// require_once( 'library/translation/translation.php' ); // this comes turned off by default

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );
/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!

/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<header class="comment-author vcard">
				<?php
				/*
					this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
					echo get_avatar($comment,$size='32',$default='<path_to_url>' );
				*/
				?>
				<?php // custom gravatar call ?>
				<?php
					// create variable
					$bgauthemail = get_comment_author_email();
				?>
				<img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
				<?php // end custom gravatar call ?>
				<?php printf(__( '<cite class="fn">%s</cite>', 'bonestheme' ), get_comment_author_link()) ?>
				<time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>
				<?php edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ?>
			</header>
			<?php if ($comment->comment_approved == '0') : ?>
				<div class="alert alert-info">
					<p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
				</div>
			<?php endif; ?>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
			</section>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</article>
	<?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!

/************* SEARCH FORM LAYOUT *****************/

// Search Form
function bones_wpsearch($form) {
	$form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
	<label class="screen-reader-text" for="s">' . __( 'Search for:', 'bonestheme' ) . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' . esc_attr__( 'Search the Site...', 'bonestheme' ) . '" />
	<input type="submit" id="searchsubmit" value="' . esc_attr__( 'Search' ) .'" />
	</form>';
	return $form;
} // don't remove this bracket!

function api_scripts(){
	

	if( get_query_var( 'name' ) == 'pull-json' && get_post_type() == 'api' ){
		require_once("library/includes/pull-json.php");
	} else if( get_query_var( 'name' ) == 'create-crime' && get_post_type() == 'api' ){
		require_once("library/includes/create-crime.php");
	} else if( get_query_var( 'name' ) == 'update-crime' && get_post_type() == 'api' ){
		require_once("library/includes/update-crime.php");
	} else if( get_query_var( 'name' ) == 'geocode-crimes' && get_post_type() == 'api' ){
		require_once("library/includes/geocode-crimes.php");
	} else if( get_query_var( 'name' ) == 'decode-json' && get_post_type() == 'api' ){
		require_once("library/includes/decode-json.php");
	} 
}
// Mapping includes
add_action( 'wp_head', 'api_scripts' );


/* For extracting the title from the content. */
function first_sentence($content) {

    $pos = strpos($content, '.');
    return substr($content, 0, $pos+1);
   
}
 /* Related to showing Point Types in Admin Panel */
function manage_crime_type_columns($out, $column_name, $id) {
    switch ($column_name) {
        case 'icon': 
	        if(get_field('icon', 'crime_type_'.$id)){
	        	//Gets the custom field icon_image for the related point_type
	        	$out .= '<img src="'.get_field('icon', 'crime_type_'.$id).'" alt="'. $id .'" />';
	        }
            break;
 
        default:
            break;
    }
    return $out;    
}
function set_custom_edit_crime_type_columns($columns) {
    unset( $columns['description'] );
    $columns['icon'] = __( 'Image', 'icon' );

    return $columns;
}

/*-------------------------------------------------------------------------------
	Custom Columns
-------------------------------------------------------------------------------*/

function my_page_columns($columns)
{
	var_dump($columns);
	$columns = array(
		'cb'	 	=> '<input type="checkbox" />',
		'title' 	=> 'Title',
		'type'      => 'Type of Crime',
		'latitude'  => 'Latitude',
		'longitude' => 'Longitude',
		'date'		=> 'Date',
	);
	return $columns;
}

function my_crime_columns($column)
{
	global $post;
	if($column == 'latitude')
	{
		if(get_field('latitude'))
		{
			echo get_field('latitude');
		}
		else
		{
			echo '';
		}
	} 
	elseif($column == 'longitude')
	{
		if(get_field('longitude'))
		{
			echo get_field('longitude');
		}
		else
		{
			echo '';
		}
	}
	elseif($column == 'type')
	{

		/* Get the genres for the post. */
		$terms = get_the_terms( $post_id, 'crime_type' );

		/* If terms were found. */
		if ( !empty( $terms ) ) {

			$out = array();

			/* Loop through each term, linking to the 'edit posts' page for the specific term. */
			foreach ( $terms as $term ) {
				$out[] = sprintf( '<a href="%s">%s</a>',
					esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'crime_type' => $term->slug ), 'edit.php' ) ),
					esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'crime_type', 'display' ) )
				);
			}

			/* Join the terms, separating them with a comma. */
			echo join( ', ', $out );
		}

		/* If no terms were found, output a default message. */
		else {
			_e( 'No Genres' );
		}

	}
}

add_action("manage_posts_custom_column", "my_crime_columns");
add_filter("manage_edit-crime_columns", "my_page_columns");
// Custom address field for generating latitude / longitude from an address
add_action('acf/register_fields', 'my_register_fields');

function my_register_fields()
{
	include_once('fields/map.php');
}

function nightly_script() {

    do_action('nightly_script');

}

add_action( 'nightly_script', 'run_nightly_script' );

function run_nightly_script() {
  $num = 13;
  $url = "http://data.cityofmadison.com/resource/d686-rvcw.json?%24limit=". $num ."&%24offset=3&%24order=incident_date%20DESC";
  $json = file_get_contents($url);
  $decode = json_decode($json, true);
  //var_dump(json_decode($json, true));
  for($n = 0; $n <= $num; $n++){
    $id            = $decode[$n]['incident_id'];
    $address       = $decode[$n]['address']. ', Madison, WI';
    $date          = $decode[$n]['incident_date'];
    $description   = $decode[$n]['details'];
    $arrested      = $decode[$n]['arrested'];
    $suspect       = $decode[$n]['suspect'];
    $victim        = $decode[$n]['victim'];
    $type          = $decode[$n]['incident_type'];
    $case          = $decode[$n]['case_number'];
    $incident_type = $decode[$n]['incident_type'];

    // Check if Crime exists 
    $args = array(
    'numberposts' => -1,
    'post_type' => 'crime',
    'meta_key' => 'id',
    'meta_value' => $id
    );
     
    // get results
    $the_query = new WP_Query( $args );
     
    // The Loop
    if( $the_query->have_posts() ){
        //Do nothing if crime exists

    } else {
          
      $date = str_replace("T", " ", $date);
      $postdate = date($date);
      $post = array(
          'post_type'    => 'crime',
          'post_title'   => first_sentence($description),
          'post_content' => $description,
          'post_date'    => $postdate,
          'post_status'  => 'publish'
        );
      $post_id = wp_insert_post( $post );
      // ID
      update_field( 'field_5271b83a2a9fe' , $id, $post_id );
      // Arrested
      update_field( 'field_5271b85b2a9ff' , $arrested, $post_id );
      // Suspect
      update_field( 'field_5271bcef340de' , $suspect, $post_id );
      // Victim
      update_field( 'field_5271bf2c9fff0' , $victim, $post_id );
      // Address
      update_field( 'field_5271b8c02aa00' , $address, $post_id );

      $geoaddress = urlencode($address);
      $geourl = "http://maps.googleapis.com/maps/api/geocode/json?address=". $geoaddress ."&sensor=false";
      $geojson = file_get_contents($geourl);
      $json_array = json_decode($geojson);
      
      $status = $json_array->status;
      //Status
      update_field( 'field_52f670c45854b' , $status, $post_id );

      if ($status == "OK")
      {
        $lat    = $json_array->results[0]->geometry->location->lat;
        // Lat
        update_field( 'field_5271b8c62aa01' , $lat, $post_id );
        $lon    = $json_array->results[0]->geometry->location->lng;
        // Long
        update_field( 'field_5271b8cf2aa02' , $lon, $post_id );
      }

      $cat = get_term_by( 'name', $type, 'crime_type' );
      $cat->term_id;
      $post_categories = array($cat->term_id);
      $post_categories = array_map('intval', $post_categories);
      $post_categories = array_unique( $post_categories );

      wp_set_post_terms( $post_id, $post_categories, 'crime_type' );
    }
  }
}

?>
