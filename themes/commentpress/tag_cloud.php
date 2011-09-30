<?php
/*
Template Name: Tag Cloud
*/
?>

<?php get_header(); ?>

<div style="position: absolute; top:0; left:0;">]
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php global $more; $more = false; the_content('', true); ?>
<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>
</div>

<?php get_footer(); ?>