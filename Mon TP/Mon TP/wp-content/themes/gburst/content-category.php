<!-- Afficher les articles d'une catégorie -->
<div>
    <h3>
        <a class="custom_a" href="<?php the_permalink() ?>"> <?php the_title() ?> </a>
    </h3>

    <?php if ('post' == get_post_type()) : ?>
        <div class="blog-postmeta">
            <p class="post-date">
                <?php echo get_the_date() ?>
            </p>
        </div>
    <?php endif; ?>
</div>
<div class="entry-summary">
    <?php the_excerpt() ?>
    <a class="custom_a" href="<?php the_permalink() ?>">
        <?php esc_html_e(("Lire la suite &rarr;")) ?>
    </a>
</div>