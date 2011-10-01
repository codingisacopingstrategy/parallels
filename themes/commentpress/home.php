<?php get_header(); ?>



<!-- index.php -->

<div id="wrapper">



<div id="main_wrapper" class="clearfix">



<div id="page_wrapper">



<div id="content" class="clearfix">

<?php $map = rand(0,9); ?>
<img src="/wp-content/home_cloud_<?php echo $map; ?>.png" usemap="#mainmap"/>
<?php echo file_get_contents('/home/s/apps/parallels.schr.fr/public/wp-content/home_cloud_' . $map . '.map'); ?>
</div><!-- /content -->



</div><!-- /page_wrapper -->



</div><!-- /main_wrapper -->



</div><!-- /wrapper -->



<?php get_sidebar(); ?>



<?php get_footer(); ?>