<?php
/**
 * Create Crime based on AJAX call
 *
*/

update();

function update() {

/*
  $lat      = $_POST["lat"];
  $lon      = $_POST["lon"];
  $post_id  = $_POST['id'];
  */
  $address  = $_GET['address'];
  $lat      = $_GET["lat"];
  $lon      = $_GET["lon"];
  $post_id  = $_GET['id'];
  $type     = $_GET['incident_type'];

    /*// Chose your method, with or without user info
    //$wsdl = 'http://geocoder.us/dist/eg/clients/GeoCoderPHP.wsdl';
    $wsdl = get_stylesheet_directory_uri() .'/assets/GeoCoderPHP.wsdl';

    // Make the connection
    $client = new SoapClient($wsdl);

    // Use this to see what services are available
    //var_dump($client->__getFunctions());

    // Actually call the service
    $result = $client->geocode("" . $address);
    //var_dump($result[0]);
    $lat = $result[0]->lat;
    $long = $result[0]->long;
  
    // Lat
    update_field( 'field_5271b8c62aa01' , $lat, $post_id );
    // Long
    update_field( 'field_5271b8cf2aa02' , $lon, $post_id );
    */
    $cat = get_term_by( 'name', $type, 'crime_type' );
    $cat->term_id;
    $post_categories = array($cat->term_id);
    $post_categories = array_map('intval', $post_categories);
    $post_categories = array_unique( $post_categories );
    var_dump($post_categories);

    wp_set_post_terms( $post_id, $post_categories, 'crime_type' );

  echo 'Success!';

    exit;
}
