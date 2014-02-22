<?php

class acf_field_map extends acf_field
{
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options


	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13 
	*/

	function __construct()
	{
		// vars
		$this->name = 'map';
		$this->label = __('Map');
		$this->category = __("Mapping",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			// add default here to merge into your field.
			// This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
			//'preview_size' => 'thumbnail'
		);


		// do not delete!
    parent::__construct();


    // settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.0'
		);

	}


	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/

	function create_options($field)
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/

		// key is needed in the field names to correctly save the data
		$key = $field['name'];


		// Create Field Options HTML
		/*
		?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Preview Size", 'acf'); ?></label>
		<p class="description"><?php _e("Thumbnail is advised", 'acf'); ?></p>
	</td>
	<td>
		<?php

		do_action('acf/create_field', array(
			'type'    =>  'radio',
			'name'    =>  'fields[' . $key . '][preview_size]',
			'value'   =>  $field['preview_size'],
			'layout'  =>  'horizontal',
			'choices' =>  array(
				'thumbnail' => __('Thumbnail'),
				'something_else' => __('Something Else'),
			)
		));

		?>
	</td>
</tr>
		<?php
		*/

	}


	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function create_field( $field )
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/

		// perhaps use $field['preview_size'] to alter the markup?


		// create Field HTML
		echo '<a onClick="getLatLng();" class="button button-primary button-large" style="margin-right: 10px;">Get Coordinates from Address</a>';
		echo '<p></p>';
		echo '<div id="map-selector" style="width: 1423px; height: 300px;"></div>';
		$lat = (get_field('latitude') != false) ? get_field('latitude') : 40;
		$lng = (get_field('longitude') != false) ? get_field('longitude') : -95;
		$zoom = (get_field('longitude') == false || (get_field('latitude') == false)) ? 4 : 12;
		?>
		<div style="clear: both;"></div>
		<h2>Marker dropped: <span id="latLngMessage"></span></h2>
		<input type="hidden" id="latField" value="<?=$lat?>">
		<input type="hidden" id="lngField" value="<?=$lng?>">
		<script type="text/javascript"src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7Xrma6pn0XhLrUuwEag36eH5vYitGOG0&sensor=true">
        </script>
		<script type="text/javascript">
			var myLatlng = new google.maps.LatLng(<?=$lat?>, <?=$lng?>);
			var marker;
			var zoomLevel = <?=$zoom?>;
			function initialize() {
				var mapOptions = {
					center: myLatlng,
					zoom: zoomLevel,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				var map = new google.maps.Map(document.getElementById("map-selector"), mapOptions);
				marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					title: "Location",
					draggable: true
				});

				google.maps.event.addListener(marker, 'dragend', function(evt){
					document.getElementById('latLngMessage').innerHTML = evt.latLng.lat().toFixed(4) + ' : ' + evt.latLng.lng().toFixed(4);
					jQuery("#latField").val(evt.latLng.lat().toFixed(7));
					jQuery("#lngField").val(evt.latLng.lng().toFixed(7));
				});
				google.maps.event.addListener(marker, 'dragstart', function(evt){
					document.getElementById('latLngMessage').innerHTML = 'Currently dragging marker...';
				
				});
				document.getElementById('latLngMessage').innerHTML = myLatlng.lat().toFixed(4) + ' : ' + myLatlng.lng().toFixed(4);
			}
			google.maps.event.addDomListener(window, 'load', initialize);

			function setLatLng() {
				latValue = jQuery("#latField").val();
				lngValue = jQuery("#lngField").val();
				jQuery("#acf-field-latitude").val(latValue);
				jQuery("#acf-field-longitude").val(lngValue);
			}
			function getLatLng() {
				var address = " " + jQuery("#acf-field-address").val();
				var geocoder = new google.maps.Geocoder();		
				geocoder.geocode({address: address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						zoomLevel = 12;
						jQuery("#latField").val(results[0].geometry.location.lat());
						jQuery("#lngField").val(results[0].geometry.location.lng());
						myLatlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
						initialize();
					}
				});
			}

		</script>
		<?php
		echo '<a onClick="setLatLng();" class="button button-primary button-large">Update Coordinates from Map</a>';
	}


	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add css + javascript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used


		// register acf scripts
		//wp_register_script('acf-input-address', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version']);
		//wp_register_style('acf-input-address', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version']);


		// scripts
		/*wp_enqueue_script(array(
			'acf-input-address',
		));

		// styles
		wp_enqueue_style(array(
			'acf-input-address',
		)); */

	}


	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add css and javascript to assist your create_field() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_head()
	{
		// Note: This function can be removed if not used
	}


	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add css + javascript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_enqueue_scripts()
	{
		// Note: This function can be removed if not used
	}


	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add css and javascript to assist your create_field_options() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_head()
	{
		// Note: This function can be removed if not used
	}


	/*
	*  load_value()
	*
	*  This filter is appied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value found in the database
	*  @param	$post_id - the $post_id from which the value was loaded from
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the value to be saved in te database
	*/

	function load_value($value, $post_id, $field)
	{
		// Note: This function can be removed if not used
		return $value;
	}


	/*
	*  update_value()
	*
	*  This filter is appied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/

	function update_value($value, $post_id, $field)
	{
		// Note: This function can be removed if not used
		return $value;
	}


	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed to the create_field action
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/

	function format_value($value, $post_id, $field)
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/

		// perhaps use $field['preview_size'] to alter the $value?


		// Note: This function can be removed if not used
		return $value;
	}


	/*
	*  format_value_for_api()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/

	function format_value_for_api($value, $post_id, $field)
	{
		// defaults?
		/*
		$field = array_merge($this->defaults, $field);
		*/

		// perhaps use $field['preview_size'] to alter the $value?


		// Note: This function can be removed if not used
		return $value;
	}


	/*
	*  load_field()
	*
	*  This filter is appied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/

	function load_field($field)
	{
		// Note: This function can be removed if not used
		return $field;
	}


	/*
	*  update_field()
	*
	*  This filter is appied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field($field, $post_id)
	{
		// Note: This function can be removed if not used
		return $field;
	}


}


// create field
new acf_field_map();

?>
