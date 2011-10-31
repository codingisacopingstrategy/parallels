<!-- comments_sidebar.php -->

<div id="comments_sidebar">

<div class="site-nav">
<ul>
  <li><a href="/report"><img src="<?php bloginfo('template_directory'); ?>/style/images/report.png" style="width:48px;height:48px;"/></a></li>
  <li><a href="/sounds"><img src="<?php bloginfo('template_directory'); ?>/style/images/sounds.png" style="width:48px;height:48px;"/></a></li>
  <li><a href="/texts/about"><img src="<?php bloginfo('template_directory'); ?>/style/images/texts.png" style="width:48px;height:48px;"/></a></li>
  <li><a href="/photos"><img src="<?php bloginfo('template_directory'); ?>/style/images/photos.png" style="width:48px;height:48px;"/></a></li>
  <li><a href="/tags"><img src="<?php bloginfo('template_directory'); ?>/style/images/tags.png" style="width:48px;height:48px;"/></a></li>
</ul>
</div>

<div class="sidebar_header">
<?php

// declare access to globals
global $commentpress_obj;

// if we have the plugin enabled...
if ( is_object( $commentpress_obj ) ) {

	// show the minimise button
	echo $commentpress_obj->get_minimise_button( 'comments' );

	// show the minimise all button
	echo $commentpress_obj->get_minimise_all_button( 'comments' );

}

?>

<h2>Comments</h2>

</div>



<div class="sidebar_minimiser">

<?php comments_template(); ?>
	
</div><!-- /sidebar_minimiser -->



</div><!-- /comments_sidebar -->



