<?php get_header(); ?>
<div id="map" class="single"></div>
<script type="text/javascript">
    var USER_POSITION;
    var DESTINATION_MARKER;
    var ALL_MARKERS = [];
    var SEARCH_MARKER_ICON = '<?php echo get_stylesheet_directory_uri(); ?>/assets/img/star-3.png';
    var coordinateArray = [];
    var markerImage = [];
    <?php
    	
		if(get_field('latitude') && get_field('longitude')){
				
          $type = wp_get_post_terms($post->ID, 'crime_type');
          $icon = get_field('icon', 'crime_type_'.$type[0]->term_id);
        ?>
				coordinateArray.push({
		            coordinates: new google.maps.LatLng(<?php echo get_field('latitude'); ?>, <?php echo get_field('longitude'); ?>), 
		            id: "<?php echo get_field('id'); ?>", 
		            title: "<a href='<?php echo get_permalink(); ?>'><?php echo sanitize_text_field(get_the_title()); ?></a>", 
		            address_details: "<?php echo sanitize_text_field(get_the_content()); ?>",
                category: "<?php echo $type[0]->name; ?>",
                date: "<?php echo get_the_date(); ?>"
		        });
        
        var tmpMarker = new google.maps.MarkerImage("<?php echo $icon; ?>");
        markerImage.push(tmpMarker);
				<?php
			}
		
		wp_reset_postdata();
    ?>
</script>
<script>
jQuery(document).ready(function(){

//---------------- MAP Options-------------------------------------//
        var styleArray = [
            {
              featureType: "all",
              stylers: [
                { saturation: -80 }
              ]
            },
            {
              featureType: "road.arterial",
              elementType: "geometry",
              stylers: [
                { hue: "#00ffee" },
                { saturation: 50 }
              ]
            },
            {
              featureType: "poi.business",
              elementType: "labels",
              stylers: [
                { visibility: "off" }
              ]
            }
          ];
        var zoomOptions = {
            style: google.maps.ZoomControlStyle.SMALL,
            position: google.maps.ControlPosition.LEFT_CENTER
            };
        var myOptions = {
          center: new google.maps.LatLng(<?php echo get_field('latitude'); ?>, <?php echo get_field('longitude'); ?>),
          zoom: 12,
          zoomControlOptions: zoomOptions,
          streetViewControl: false,
          panControl: false,
          styles: styleArray,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          mapTypeControl: false
        };
        var mapCrimes = new google.maps.Map(document.getElementById("map"), myOptions);

        var directionsRenderOptions = {
            suppressMarkers: true,
            preserveViewport: false
        }
        var directionsRender = new google.maps.DirectionsRenderer(directionsRenderOptions);
        directionsRender.setMap(mapCrimes);
//--------------- END OF MAP OPTIONS--------------------------------//

//--------------- Actual Route Function ----------------------------//
        function getRoute(markerObject, infoBoxObject) { 
            if (USER_POSITION)  {
                var directionsOptions = {
                    origin: USER_POSITION, 
                    destination: markerObject.getPosition(),
                    travelMode: google.maps.DirectionsTravelMode.DRIVING
                };
                var directionsService = new google.maps.DirectionsService();
                directionsService.route(directionsOptions, function(response, status) {
                   if (status == google.maps.DirectionsStatus.OK) {
                        directionsRender.setDirections(response);
                    };
                });
                infoBoxObject.close()
            };
        }
//------------ User's position Marker----------------------------//
        function showPosition(position)
            {
                var userMarkerAnimation = google.maps.Animation.DROP;
                var userMarkerImage =  new google.maps.MarkerImage("<?php echo get_stylesheet_directory_uri(); ?>/assets/img/down.png");
                var userMarkerOptions = {
                    animation: userMarkerAnimation,
                    position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
                    icon: userMarkerImage,
                    map: mapCrimes
                };
                USER_POSITION = userMarkerOptions.position;
                var userMarker = new google.maps.Marker(userMarkerOptions);
                var userBoxText = '<div class="info_box"><div class="crime_name info_details">You Are Here!!!</div><div class="church_details info_details"></div></div>';
                var userInfoOptions = {
                    content: userBoxText
                   ,disableAutoPan: false
                   ,maxWidth: 0
                   ,pixelOffset: new google.maps.Size(0, -100)
                   ,zIndex: null
                   ,boxStyle: { opacity: 0.9 ,width: "330px"}
                   ,closeBoxMargin: ""
                   ,closeBoxURL: ""
                   ,infoBoxClearance: new google.maps.Size(1, 1)
                   ,isHidden: false
                   ,pane: "floatPane"
                   ,enableEventPropagation: true
                };
                var userInfoBox = new InfoBox(userInfoOptions);
                
                (function(marker){
                    google.maps.event.addListener(marker, "mouseover", function(e) {
                        userInfoBox.open(mapCrimes, marker);
                    });
                    google.maps.event.addListener(marker, "mouseout", function(e) {
                        userInfoBox.close();
                    });
                })(userMarker);
            };

            function positionError(error) {
                switch(error.code) 
                    {
                        case error.TIMEOUT:
                            alert ('Timeout');
                            break;
                        case error.POSITION_UNAVAILABLE:
                            alert ('Position unavailable');
                            break;
                        case error.PERMISSION_DENIED:
                            alert ('Permission denied');
                            break;
                        case error.UNKNOWN_ERROR:
                            alert ('Unknown error');
                            break;
                    }
            };
//---------------------- Does the actual work to get the user position-------//
            navigator.geolocation.getCurrentPosition(showPosition, function(error){
                alert("got an error" + error);
                positionError(error)
                });
//------------MARKER OPTIONS--------------------//
            var markerAnimation = google.maps.Animation.DROP

//------------SUPER STYLED INFO BOX-------------//
        var infoOptions = {
                content: 'holding...'
               ,disableAutoPan: false
               ,maxWidth: 0
               ,pixelOffset: new google.maps.Size(-320, 15)
               ,zIndex: null
               ,boxStyle: { opacity: 0.9 ,width: "640px"}
               ,closeBoxMargin: "8px 15px 0px 2px"
               ,closeBoxURL: "<?php echo get_stylesheet_directory_uri(); ?>/assets/img/closebutton-th.png"
               ,infoBoxClearance: new google.maps.Size(1, 1)
               ,isHidden: false
               ,pane: "floatPane"
               ,enableEventPropagation: false
            };
        var ib = new InfoBox(infoOptions);
         var hoverInfoOptions = {
             content: 'holding...'
             ,disableAutoPan: false
             ,maxWidth: 0
             ,pixelOffset: new google.maps.Size(-150, -125)
             ,zIndex: null
             ,boxStyle: { opacity: 0.9 ,width: "300px"}
             ,closeBoxMargin: ""
             ,closeBoxURL: ""
             ,infoBoxClearance: new google.maps.Size(1, 1)
             ,isHidden: false
             ,pane: "floatPane"
             ,enableEventPropagation: true
          };
        var hoverInfoBox = new InfoBox(hoverInfoOptions);
/*-------------------- START THE LOOP THAT BUILDS THE MARKERS-----------------*/
        for(var i in coordinateArray ) {
            var id = coordinateArray[i]['id'];
            var title = coordinateArray[i]['title'];
            var position = coordinateArray[i]['coordinates'];
            var address_details = coordinateArray[i]['address_details'];
            var category = coordinateArray[i]['category'];
            var date = coordinateArray[i]['date'];

            var boxText = [
                        '<div class="info_box">',
                            '<div class="crime_name info_details click">' + title + '</div>',
                            '<div class="crime_date info_details click">' + date + '</div>',
                            '<div class="crime_details info_details click">' + address_details + '</div>',
                            '<div class="crime_category info_details hover">' + category + '</div>',
                            '<div class="crime_date info_details hover">' + date + '</div>',
                            /*
                            '<div class="info_details button_box">',
                                '<input type="hidden" id="' + id + '_lat_"' + position.lat() + '></input>',
                                '<input type="hidden" id="' + id + '_long_"' + position.lng() + '></input>',
                                '<a href="#" id="' + id + '" class="btn find_route_button">Find a Route</a>',
                            '</div>',
                            */
                        '</div>'
                        ].join(" ");

            var markerOptions = {
                animation: markerAnimation,
                position: coordinateArray[i]['coordinates'],
                icon: markerImage[i],
                map: mapCrimes,
                html: boxText,
                id: id
            };

            var newMarker = new google.maps.Marker(markerOptions);

//-------------- Creating a closure to retain the correct markerInfoOptions -------------------//
            
            (function(marker) {
                // Attaching a mouseover event to the current marker
                ALL_MARKERS.push(marker);
                google.maps.event.addListener(marker, "click", function(e) {
                    ib.setContent("<div class='onclick'>" + marker.html + "</div>"); 
                    ib.open(mapCrimes, marker);
                });
                
                google.maps.event.addListener(marker, "mouseover", function(e) {
                    hoverInfoBox.setContent("<div class='onhover'>" + marker.html + "</div>"); 
                    hoverInfoBox.open(mapCrimes, marker);
                });
                google.maps.event.addListener(marker, "mouseout", function(e) {
                    hoverInfoBox.close();
                });
                
            })(newMarker);
            
    /*-------------Add a click listener for the Route Builder ---------------------------*/
            (function(infoBoxObject, markerObject) {
                        google.maps.event.addListener(infoBoxObject, "domready", function() {
                            jQuery("#" + markerObject.id).click(function(){
                                DESTINATION_MARKER = markerObject.getPosition()
                                //alert(DESTINATION_MARKER);
                                if (USER_POSITION != null){
                                    getRoute(markerObject, infoBoxObject);
                                    
                                }
                                else {
                                    alert("Sorry but we need your location to plot a route, and your device cannot currently provide it");
                                };
                            })
                        });
            }(ib, newMarker));
        }
});

</script>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

					<div id="main" class="twelvecol first clearfix" role="main">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<header class="article-header">

									<h1 class="entry-title single-title" itemprop="headline"><?php the_title(); ?></h1>
									<p class="byline vcard"><?php
										printf( __( 'Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time>', 'bonestheme' ), get_the_time( 'Y-m-j' ), get_the_time( get_option('date_format')), bones_get_the_author_posts_link(), get_the_category_list(', ') );
									?></p>

								</header> <?php // end article header ?>

								<section class="entry-content clearfix" itemprop="articleBody">
									<?php the_content(); ?>
								</section> <?php // end article section ?>

								<footer class="article-footer">
									<?php the_tags( '<p class="tags"><span class="tags-title">' . __( 'Tags:', 'bonestheme' ) . '</span> ', ', ', '</p>' ); ?>

								</footer> <?php // end article footer ?>

								<?php comments_template(); ?>

							</article> <?php // end article ?>

						<?php endwhile; ?>

						<?php else : ?>

							<article id="post-not-found" class="hentry clearfix">
									<header class="article-header">
										<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
									</header>
									<section class="entry-content">
										<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
									</section>
									<footer class="article-footer">
											<p><?php _e( 'This is the error message in the single.php template.', 'bonestheme' ); ?></p>
									</footer>
							</article>

						<?php endif; ?>

					</div> <?php // end #main ?>

				</div> <?php // end #inner-content ?>

			</div> <?php // end #content ?>

<?php get_footer(); ?>
