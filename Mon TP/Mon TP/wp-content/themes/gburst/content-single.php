<!-- Valeurs renvoyées par post -->

<a href="<?php the_permalink() ?>">
    <h2 class="blog-post-title"> <?php the_title() ?> </h2>
</a>
<div class="mt-3">
    <?php the_date() ?> par <a href="#"> <?php the_author() ?> </a>
</div>
<div class="mt-3">
    <p>Catégories : <?php the_category() ?> </p>
</div>
<?php if (has_tag()) : ?>
    <p>Tags : <?php the_tags() ?> </p>
<?php endif; ?>
<div class="mt-3">
    <?php the_content() ?>
</div>