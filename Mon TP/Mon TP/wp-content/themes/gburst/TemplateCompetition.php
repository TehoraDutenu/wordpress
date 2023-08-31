<?php
/*
Template Name: Compétition
Template Post Type: page
*/
get_header();

/* -- WIDGET -- */
if (is_active_sidebar('new-widget-area')) : ?>
    <div id="secondary-sidebar" class="new-widget-area">
        <?php dynamic_sidebar("new-widget-area") ?>
    </div>
<?php endif;

get_footer();
?>