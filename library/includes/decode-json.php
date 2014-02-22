<?php
/**
 * Insert New Crimes into WordPress
 *
*/

ajax();

function ajax() {

  $num = 50;
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
      
      $address = urlencode($address);
      $geourl = "http://maps.googleapis.com/maps/api/geocode/json?address=". $address ."&sensor=false";
      $geojson = file_get_contents($geourl);
      $json_array = json_decode($geojson);
      
      $status = $json_array->status;
      if ($status == "OK")
      {
        $lat    = $json_array->results[0]->geometry->location->lat;
        update_field( 'field_5271b8c62aa01' , $lat, get_the_id() );
        $lon    = $json_array->results[0]->geometry->location->lng;
        update_field( 'field_5271b8cf2aa02' , $lon, get_the_id() );
        echo 'Lat: '. $lat .' Long: '.$lon.'<br />';
      }

    } else {
      
      $address = urlencode($address);
      $geourl = "http://maps.googleapis.com/maps/api/geocode/json?address=". $address ."&sensor=false";
      $geojson = file_get_contents($geourl);
      $json_array = json_decode($geojson);
      
      $status = $json_array->status;
         
      $date = str_replace("T", " ", $date);
      $postdate = date($date);
      wp_reset_query(); 
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
      echo 'inserted crime id of '. $content .' lat: '. $lat.' long: '.$lon.' <br/>';
    }
  }
	/*=	$decode[$n]ript>
			jQuery(document).ready(function(){
                jQuery.getJSON( "<?php echo $url; ?>", function( data ) {
                  jQuery.each( data, function( key, val ) {
                  	// Create Crime for each
                    jQuery.ajax({
                      type: "POST",
                      url: "<?php echo site_url(); ?>/api/create-crime/",
                      data: { 
                      		    'id'            : val['incident_id'], 
                              'address'       : val['address'], 
                              'date'          : val['incident_date'],
                              'description'   : val['details'],
                              'arrested'      : val['arrested'],
                              'suspect'       : val['suspect'],
                              'victim'        : val['victim'],
                              'type'          : val['incident_type'],
                              'case'          : val['case_number'],
                              'incident_type' : val['incident_type']
                              },
                      success: function (data){
                        console.log('id ' + data);
                      }
                    })
                  });
                });
                
            });
	</script>
  */
		//echo 'Success!';

		exit;
}

?>
