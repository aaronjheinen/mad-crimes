		<div id="search_box" class="modal fade">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <h3>Search for a crime!</h3>
            </div>
            <div class="modal-body">
                <form id="search_form" class="well form-search">
                    <input type="text" class="input-medium search-query" id="search_input">
                    <button type="submit" class="btn" id="search_button">Search</button>
                </form>
            </div>
        </div>


		<?php // all js scripts are loaded in library/bones.php ?>
		<?php wp_footer(); ?>
		
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/search.js"></script>
        <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-45913106-1', 'mapcrimes.com');
      ga('send', 'pageview');

    </script>
	</body>

</html> <?php // end page. what a ride! ?>
