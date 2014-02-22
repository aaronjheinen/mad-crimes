<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // Google Chrome Frame for IE ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title><?php wp_title(''); ?></title>
        <!--script type="text/javascript" src="https://getfirebug.com/firebug-lite.js"></script-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="" content="">
        <meta name="Aaron Heinen, aaronjheinen@gmail.com" content="">

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-icon-touch.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.9.0-beta.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/bootstrap-responsive.css">
        <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/map.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/iThing.css" />

		<?php // drop Google Analytics Here ?>
		<?php // end analytics ?>
		<!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <script type="text/javascript"src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7Xrma6pn0XhLrUuwEag36eH5vYitGOG0&sensor=true">
        </script>
        <script type="text/javascript" src="http://code.jquery.com/ui/1.9.0-beta.1/jquery-ui.js"></script>

        <!--script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/gmap3.js"></script-->
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/infoBoxStyles.js"></script>
        <!-- Le javascript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-transition.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-alert.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-modal.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-dropdown.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-scrollspy.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-tab.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-tooltip.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-popover.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-button.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-collapse.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-carousel.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/bootstrap-typeahead.js"></script>

        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery.mousewheel.min.js"></script>
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jQAllRangeSliders-withRuler-min.js"></script>

	</head>

	<body <?php body_class(); ?>>
		<div class="navbar navbar-fixed-top" id="menu">
          <div class="navbar-inner">
            <div class="container">
              <a class="brand" href="<?php echo site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.png" /> Madison Crimeline</a>
              <div id="total"></div>
            </div>
          </div>
        </div>
        