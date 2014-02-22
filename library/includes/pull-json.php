<?php
/**
 * Insert New Crimes into WordPress
 *
*/

ajax();

function ajax() {

	$url = "http://data.cityofmadison.com/resource/d686-rvcw.json?%24limit=50&%24offset=3&%24order=incident_date%20DESC";

	?>
	<script>
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
	<?php

		echo 'Success!';

		exit;
}

?>
