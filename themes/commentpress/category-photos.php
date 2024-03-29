<?php get_header(); ?>



<!-- archive.php -->

<div id="wrapper">



<div id="main_wrapper" class="clearfix">



<div id="page_wrapper">



<div id="content" class="clearfix">

<div class="post">



<?php if (have_posts()) : ?>

    <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
    <?php /* If this is a category archive */ if (is_category()) { ?>
    <!--<h2>Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>-->
    <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
    <h2><?php single_tag_title(); ?></h2>
    <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
    <h2>Blog Archive for <?php the_time('F jS, Y'); ?></h2>
    <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
    <h2>Blog Archive for <?php the_time('F, Y'); ?></h2>
    <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
    <h2>Blog Archive for <?php the_time('Y'); ?></h2>
    <?php /* If this is an author archive */ } elseif (is_author()) { ?>
    <h2>Author Archive</h2>
    <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
    <h2>Blog Archives</h2>
    <?php } ?>

    <?php while (have_posts()) : the_post(); ?>

        <div class="archive_item photo_archive_item">
        
        <!--<p class="postname"><?php the_time('l, F jS, Y') ?></p>-->
        
        <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_excerpt() ?></a>
        
        <p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
        
        </div><!-- /archive_item -->
    
    <?php endwhile; ?>


    
<?php else : ?>

    <h2>Not Found</h2>

    <?php get_search_form(); ?>

<?php endif; ?>



</div><!-- /post -->

</div><!-- /content -->



</div><!-- /page_wrapper -->



</div><!-- /main_wrapper -->



</div><!-- /wrapper -->



<?php get_sidebar(); ?>



<?php get_footer(); ?>