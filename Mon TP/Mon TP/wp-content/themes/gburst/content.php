<!-- Valeurs renvoyées par post -->

<a href="<?php the_permalink() ?>">
    <h4 class="blog-post-title"> <?php the_title() ?> </h4>
</a>
<p>
    <!-- <?php // the_date() 
            ?> par <a href="#"> <?php // the_author() 
                                                    ?> </a> -->
</p>
<?php the_content() ?>