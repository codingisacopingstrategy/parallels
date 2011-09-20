<!-- footer.php -->

<div>
    <?php
    echo "[";
    $the_pages = get_pages();
    foreach ($the_pages as $page) {
        echo "[";
        $id = $page->ID;
        foreach (wp_get_post_tags( $id, array( 'fields' => 'names' ) ) as $tag ) {
            echo '"' . $tag . '", ';
        }
        echo "],\n";
    }
    echo "]";
    ?>
</div>



</div><!-- /container -->



</body>



</html>