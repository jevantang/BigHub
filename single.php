<?php get_header(); ?>

<div class="container main">
    <div class="col-lg-10 col-lg-offset-1">
        <div class="col-sm-8">
            <ol class="breadcrumb">
                <li><a href="<?php bloginfo('siteurl'); ?>">首页</a></li>
                <li><?php the_category(',') ?></li>
                <li class="active">内容</li>
            </ol>
            <div class="jumbotron">
                <h1><?php the_title(); ?></h1>
                <p><?php the_excerpt(); ?><p>
                <p class="post_meta"><span class="glyphicon glyphicon-time" aria-hidden="true" style="margin-right:10px;"></span> <?php the_time('Y-m-d H:i') ?></p>
                <?php $url = get_post_meta($post->ID, '原文链接', true);
                if ($url) {
                    echo '<p class="post_meta"><span class="glyphicon glyphicon-link" aria-hidden="true"></span> <a href="'.$url.'" rel="nofollow" target="_blank">阅读原文</a></p>';
                } ?>
                <p class="post_meta"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span> <?php the_category(',') ?> <?php the_tags('',' ',''); ?></p>
            </div>
            <?php while ( have_posts() ) : the_post(); ?>
            <div class="text-center bighub_text"><span>正文内容<span></div>
            <div class="bighub_content">
                <?php the_content(); ?>
            </div>
            <?php endwhile; ?>
            <div class="go-home"><a class="btn btn-default" href="<?php bloginfo('siteurl'); ?>" role="button">去 BigHub 查看最新动态</a></div>
        </div>
        <!--左右-->
        <div class="col-sm-4 hidden-xs">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>