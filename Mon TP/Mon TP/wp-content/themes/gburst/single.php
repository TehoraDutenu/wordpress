<?php get_header(); ?>

<main>
    <div class="d-flex">
        <div class="col-sm-8 bloc-main">
            <h2>C'est mon article</h2>
            <?php
            if (have_posts()) : while (have_posts()) : the_post();
                    get_template_part('content', 'single', get_post_format());
                endwhile;
            endif;

            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>