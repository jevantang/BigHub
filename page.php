<?php get_header(); ?>

<div class="container main">
    <div class="col-lg-10 col-lg-offset-1">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="jumbotron">
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
        </div>
        <?php endwhile; else: ?>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
