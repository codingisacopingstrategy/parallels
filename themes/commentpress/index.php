<?php get_header(); ?>



<!-- index.php -->

<div id="wrapper">



<div id="main_wrapper" class="clearfix">



<div id="page_wrapper">



<div id="content" class="clearfix">

<?php

// init id
$title_page_id = '';

// declare access to globals
global $commentpress_obj;

// if we have the plugin enabled...
if ( is_object( $commentpress_obj ) ) {

	// get ID of title page
	//$title_page_id = $commentpress_obj->db->option_get( 'cp_welcome_page' );
	
}



// have we set a title page?
if ( $title_page_id != '' ) {

	// get title page
	$title = get_page( $title_page_id );
	
	// show content
	setup_postdata( $title );
	
	// enable Wordpress API on page
	$GLOBALS['post'] = $title;

	// show page content
	?><h2><?php the_title(); ?></h2>
	
	<?php the_content('Read the rest of this entry &raquo;'); ?>

	<?php edit_post_link('Edit this entry', '<p class="edit_link">', '</p>'); 
	
} else {

	// show river of news
	if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<small><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></small>

				<div class="entry">
					<?php the_excerpt(); ?>
				</div>

				<!--<p class="readmore"><a href="<?php the_permalink() ?>" rel="bookmark" title="Read more...">Read more...</a></p>-->

				<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>

			</div><!-- /post -->
	
		<?php endwhile; ?>

	<?php else : ?>

		<div class="post">

			<h2>No blog posts found</h2>
			
			<p>There are no blog posts yet.<?php
			
			// if logged in
			if ( is_user_logged_in() ) {
				
				// add a suggestion
				?> <a href="<?php admin_url(); ?>">Go to your dashboard to add one.</a><?php
				
			}
				
			?></p>
			
			<p>If you were looking for something that hasn't been found, try using the search form below.</p>

			<?php get_search_form(); ?>

		</div><!-- /post -->

	<?php endif; ?>

<?php } ?>

</div><!-- /content -->



</div><!-- /page_wrapper -->



</div><!-- /main_wrapper -->



</div><!-- /wrapper -->



<?php get_sidebar(); ?>



<?php get_footer(); ?>