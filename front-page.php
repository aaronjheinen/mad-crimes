<?php get_header(); ?>
<div id="crime-wrap" class="span3">
  <div id="crime-control">
    <div id="crime"></div>
    <ul>
      <li><a href="#" onclick="next();">Prev</a></li>
      <li><a href="#" onclick="prev();">Next</a></li>
    </ul>
  </div>
</div>
<div id="map"></div>
<div id="slider-wrap">
  <div id="date-ruler"></div>
</div>
<script type="text/javascript">
    var USER_POSITION;
    var DESTINATION_MARKER;
    var ALL_MARKERS = [];
    var SEARCH_MARKER_ICON = '<?php echo get_stylesheet_directory_uri(); ?>/assets/img/star-3.png';
    var coordinateArray = [];
    var markerImage = [];
    var selectedImage = [];
    // So first click goes to first marker
    var currID = -1;
    var latArray = [];
    var lonArray = [];
    var mapCrimes;
    var ib;
    var numShowing = 0;
    <?php
    	$args = array(
					'post_type' => 'crime', 
					'posts_per_page' => -1
					);
		$query = new WP_Query( $args );
    global $post;
		while( $query->have_posts())
		{
			$query->the_post();
			if(get_field('latitude') && get_field('longitude')){
				
          $type = wp_get_post_terms($post->ID, 'crime_type');
          $icon = get_field('icon', 'crime_type_'.$type[0]->term_id);
          $unselected_icon = get_field('icon_unselected', 'crime_type_'.$type[0]->term_id);
        ?>
				coordinateArray.push({
		            coordinates: new google.maps.LatLng(<?php echo get_field('latitude'); ?>, <?php echo get_field('longitude'); ?>), 
		            id: "<?php echo get_field('id'); ?>", 
		            title: "<a href='<?php echo get_permalink(); ?>'><?php echo addslashes(sanitize_text_field(get_the_title())); ?></a>", 
		            address_details: "<?php echo addslashes(sanitize_text_field(get_the_content())); ?>",
                category: "<?php echo $type[0]->name; ?>",
                date: "<?php echo get_the_date(); ?>",
                dateobj: new Date("<?php echo get_the_date(); ?>")
		        });
        latArray.push(<?php echo get_field('latitude'); ?>);
        lonArray.push(<?php echo get_field('longitude'); ?>);
        var tmpMarker = new google.maps.MarkerImage("<?php echo $icon; ?>");
        var unSelectedMarker = new google.maps.MarkerImage("<?php echo $unselected_icon; ?>");
        markerImage.push(tmpMarker);
        selectedImage.push(unSelectedMarker);
				<?php
			}
		}
		wp_reset_postdata();
    ?>
</script>
<script>
jQuery(document).ready(function(){
  resize();
jQuery( window ).resize(function() {
  resize();
  });
function resize(){
  if(window.innerWidth > 780){
    jQuery('#crime-wrap').css('height' , window.innerHeight + "px");
    jQuery('#crime-control').css('height' , window.innerHeight -45 + "px");
  }
}
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
          center: new google.maps.LatLng(43.0833196, -89.37247689999998),
          zoom: 13,
          zoomControlOptions: zoomOptions,
          streetViewControl: false,
          panControl: false,
          styles: styleArray,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          mapTypeControl: false
        };
        mapCrimes = new google.maps.Map(document.getElementById("map"), myOptions);

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
        ib = new InfoBox(infoOptions);

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
                id: i
            };

            var newMarker = new google.maps.Marker(markerOptions);

//-------------- Creating a closure to retain the correct markerInfoOptions -------------------//
            
            (function(marker) {
                // Attaching a mouseover event to the current marker
                ALL_MARKERS.push(marker);
                google.maps.event.addListener(marker, "click", function(e) {
                    //ib.setContent("<div class='onclick'>" + marker.html + "</div>"); 
                    //ib.open(mapCrimes, marker);
                    gotoCrime(marker.position, marker.id);
                    showCrime(marker.id);
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
                              alert(markerObject.id);
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
        function resetMarkers(min, max){
          numShowing = 0;
           for(var i in coordinateArray ) {
              if(coordinateArray[i]['dateobj'] < min || coordinateArray[i]['dateobj'] > max){
                console.log('if');
                ALL_MARKERS[i].setVisible(false);
              } else {
                numShowing++;
                console.log('else');
                ALL_MARKERS[i].setVisible(true);
              }
           }
           updatePos();
           /* End of jQuery ready */
        }
mapCrimes.panTo(new google.maps.LatLng(latArray[0], lonArray[0]));
          updatePos(0);

var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];

var today = new Date();

var last_month = new Date();
last_month.setDate(today.getDate()-30);

var last_year = new Date();
last_year.setDate(today.getDate()-365);
resetMarkers(last_month, today);
  jQuery("#date-ruler").dateRangeSlider({
    arrows: false,
    wheelMode: "zoom",
    wheelSpeed: 30,
    bounds: {min: last_year, max: today},
    defaultValues: {min: last_month, max: today},
    scales: [{
      first: function(value){ return value; },
      end: function(value) {return value; },
      next: function(value){
        var next = new Date(value);
        return new Date(next.setMonth(value.getMonth() + 1));
      },
      label: function(value){
        return months[value.getMonth()];
      },
      format: function(tickContainer, tickStart, tickEnd){
        tickContainer.addClass("myCustomClass");
      }
    }]
  });

  jQuery("#date-ruler").bind("valuesChanged", function(e, data){
    console.log("Something moved. min: " + data.values.min + " max: " + data.values.max);
    resetMarkers(data.values.min, data.values.max);
  });
});



function next(){
  currID++;
  if(currID >= lonArray.length){
    currID = 0;
  }
  mapCrimes.panTo(new google.maps.LatLng(latArray[currID], lonArray[currID]));
  updatePos(currID);

}
function prev(){
  currID--;
  if(currID < 0){
    currID = lonArray.length - 1;
  }
  mapCrimes.panTo(new google.maps.LatLng(latArray[currID], lonArray[currID]));
  updatePos(currID);
}
function showCrimeWrap(){
  jQuery("#crime-wrap").toggleClass('visible');
}
function updatePos(id){
  //document.getElementById('counter').innerHTML = "" + (id + 1) + " / " + lonArray.length + "";
  document.getElementById('total').innerHTML =  "<p>" + numShowing + " Crimes showing</p>";
  for(var i=0; i < ALL_MARKERS.length; i++){
    ALL_MARKERS[i].setIcon(markerImage[i]);
  }
  if(id != null){
    ALL_MARKERS[id].setIcon(selectedImage[id]);
    //ib.setContent("<div class='onclick'>" + ALL_MARKERS[id].html + "</div>"); 
    //ib.open(mapCrimes, ALL_MARKERS[id]);
    currID = id;
    showCrime(id);
  }
}
function gotoCrime(pos, id){
  mapCrimes.panTo(pos);
  updatePos(id);
}
function showCrime(id){
  document.getElementById('crime').innerHTML = "<h1>" + coordinateArray[id]["title"] + "</h1><h4>" + coordinateArray[id]["date"] + "</h4><p>" + coordinateArray[id]["address_details"] + "</p>";
}
</script>
<?php get_footer(); ?>
