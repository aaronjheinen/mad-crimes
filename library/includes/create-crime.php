<?php
/**
 * Create Crime based on AJAX call
 *
*/

create();

function create() {

  $id          = $_POST["id"];
  $address     = $_POST["address"] . ', Madison, WI';
  $date        = $_POST["date"];
  $content     = $_POST["description"];
  $arrested    = $_POST["arrested"];
  $suspect     = $_POST["suspect"];
  $victim      = $_POST["victim"];
  $type        = $_POST["type"];
  $case        = $_POST["case"];
  $type        = $_POST['incident_type'];

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
  ?>
  <?php if( $the_query->have_posts() ){

  } else {
    $wsdl = get_stylesheet_directory_uri() .'/assets/GeoCoderPHP.wsdl';

    // Make the connection
    $client = new SoapClient($wsdl, array(
      "trace" => false,  
      "exceptions" => true
    ));

    // Use this to see what services are available
    //var_dump($client->__getFunctions());

    // Actually call the service
    try{
      $result = $client->geocode("" . $address);
      $lat = $result[0]->lat;
      $lon = $result[0]->long;
    } catch (SoapFault $sf){
      $lat = '0';
      $lon = '0';
    }

    wp_reset_query(); 
    $date = str_replace("T", " ", $date);
    $postdate = date($date);
    $post = array(
        'post_type'    => 'crime',
        'post_title'   => first_sentence($content),
        'post_content' => $content,
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
    // Lat
    update_field( 'field_5271b8c62aa01' , $lat, $post_id );
    // Long
    update_field( 'field_5271b8cf2aa02' , $lon, $post_id );

    $cat = get_term_by( 'name', $type, 'crime_type' );
    $cat->term_id;
    $post_categories = array($cat->term_id);
    $post_categories = array_map('intval', $post_categories);
    $post_categories = array_unique( $post_categories );
    var_dump($post_categories);

    wp_set_post_terms( $post_id, $post_categories, 'crime_type' );

  } 
    
  echo $post_id;

    exit;
}
