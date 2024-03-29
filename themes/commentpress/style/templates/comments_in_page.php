<?php /*
===============================================================
Commentpress Theme Comments in Page
===============================================================
AUTHOR			: Christian Wach <needle@haystack.co.uk>
LAST MODIFIED	: 12/10/2009
---------------------------------------------------------------
NOTES

---------------------------------------------------------------
*/

?>



<!-- comments_in_page.php -->

<div id="comments_in_page_wrapper">



<div class="comments_container">



<?php if ('open' != $post->comment_status) : ?>

	<!-- comments are closed. -->
	<h3 class="nocomments">Comments are closed</h3>

<?php endif; ?>



<?php if ( have_comments() ) : ?>



	<h3><?php 
	
	comments_number(
		'<span>0</span> general comments', 
		'<span>1</span> general comment', 
		'<span>%</span> general comments' 
	); 
	
	?></h3>



	<div class="paragraph_wrapper">

		<ol class="commentlist">
	
		<?php wp_list_comments(
		
			array(
			
				// list comments params
				'type'=> 'comment', 
				'reply_text' => 'Reply to this comment',
				'callback' => 'cp_comments'
				
			)
			
		); ?>
	
		</ol>

	</div><!-- /paragraph_wrapper -->



<?php else : // this is displayed if there are no comments so far ?>



	<?php if ('open' == $post->comment_status) : ?>

		<!-- comments are open, but there are no comments. -->
		<h3 class="nocomments">No general comments yet</h3>

	<?php endif; ?>



<?php endif; ?>



</div><!-- /comments_container -->



</div><!-- /comments_in_page_wrapper -->



<?php

// include comment form
include( TEMPLATEPATH . '/style/templates/comment_form.php');

?>