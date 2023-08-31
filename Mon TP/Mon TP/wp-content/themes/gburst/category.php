<?php get_header(); ?>

<main>
    <div class="page-header">
        <?php
        // Titre de la catégorie
        the_archive_title("<h2>", "</h2>");
        the_archive_description("<em>", "</em>");

        ?>
    </div>
    <div class="d-flex">
        <div class="col-sm-8 bloc-main">
            <?php
            if (have_posts()) : while (have_posts()) : the_post();
                    get_template_part('content', 'category', get_post_format());
                endwhile;
            endif;

            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>