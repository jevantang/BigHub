<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="nofollow">
<meta name="format-detection" content="telephone=no">
<meta name="referrer" content="unsafe-url">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
$bigbub_option = get_option('bighub_config'); //获取设置选项
$template_uri = get_template_directory_uri(); //主题文件夹的uri
?>
<title><?php wp_title( ' - ', true, 'right' ); ?></title>
<?php
if ( is_home() ) {
  //先定义变量，在未设置的时候不报错。
  $description = '';
  if(!empty($bigbub_option['bighub_description'])){
    $description = $bigbub_option['bighub_description'];
  }
  $keywords = '';
  if(!empty($bigbub_option['bighub_keywords'])){
    $keywords = $bigbub_option['bighub_keywords'];
  }
?>
<meta name="description" content="<?php echo $description; ?>" />
<meta name="keywords" content="<?php echo $keywords; ?>" />
<meta property="og:url" content="<?php echo home_url(); ?>">
<meta property="og:type" content="article">
<meta property="og:title" content="<?php bloginfo('name'); ?>">
<meta property="og:description" content="<?php echo $description; ?>">
<meta property="og:image" content="<?php echo $template_uri; ?>/images/bighub_180.png">
<?php } ?>
<?php
if ( is_single() ){
    if ($post->post_excerpt) {
        $description  = $post->post_excerpt;
    } else {
        if(preg_match('/<p>(.*)<\/p>/iU',trim(strip_tags($post->post_content,"<p>")),$result)){
            $post_content = $result['1'];
        } else {
            $post_content_r = explode("\n",trim(strip_tags($post->post_content)));
            $post_content = $post_content_r['0'];
        }
        $description = utf8Substr($post_content,0,220);
    }
    $keywords = "";
    $tags = wp_get_post_tags($post->ID);
    foreach ($tags as $tag ) {
        $keywords = $keywords . $tag->name . ",";
    }
}
?>
<?php if ( is_single() ) { ?>
<meta name="description" content="<?php echo trim($description); ?>" />
<meta name="keywords" content="<?php echo rtrim($keywords,','); ?>" />
<meta property="og:url" content="<?php the_permalink() ?>">
<meta property="og:type" content="article">
<meta property="og:title" content="<?php echo trim(wp_title('',0)); ?>">
<meta property="og:description" content="<?php echo trim($description); ?>">
<meta property="og:image" content="<?php echo $template_uri; ?>/images/bighub_180.png">
<?php } ?>
<link rel="apple-touch-icon" href="<?php echo $template_uri; ?>/images/bighub_32.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo $template_uri; ?>/images/bighub_152.png">
<link rel="apple-touch-icon" sizes="167x167" href="<?php echo $template_uri; ?>/images/bighub_167.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $template_uri; ?>/images/bighub_180.png">
<link rel="icon" href="<?php echo $template_uri; ?>/images/bighub_32.png" type="image/x-icon">
<?php wp_head(); ?>
</head>

<body>
<?php
$bighub_logo = !empty($bigbub_option['bighub_logo']) ? $bigbub_option['bighub_logo'] : $template_uri.'https://bighub.me/media/logo@2x.png';
$bighub_logo_2x = !empty($bigbub_option['bighub_logo_2x']) ? $bigbub_option['bighub_logo_2x'] : $template_uri.'https://bighub.me/media/logo@2x.png';
?>
<header class="container">
    <div class="col-lg-10 col-lg-offset-1">
      <a href="<?php echo site_url(); ?>" title="<?php bloginfo('name'); ?>"><img src="<?php echo $bighub_logo; ?>" alt="logo" srcset="<?php echo $bighub_logo; ?> 1x, <?php echo $bighub_logo_2x; ?> 2x"></a>
    </div>
</header>
<nav class="navbar navbar-default">
    <div class="container">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand visible-xs" href="<?php echo site_url();; ?>">首页</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php if ( is_home() ) { ?>
                    <li class="current-cat"><a href="<?php echo site_url(); ?>">全部最新</a></li>
                    <?php } else { ?>
                    <li><a href="<?php echo site_url();; ?>">全部最新</a></li>
                    <?php } ?>
                    <?php wp_list_categories('depth=1&title_li=0&orderby=id&show_count=0&hide_empty=0'); ?>
                </ul>
                
                <?php if (get_option('bighub_rss') == '显示') { ?>
                <a href="<?php bloginfo('rss2_url'); ?>" target="_blank" rel="nofollow" title="RSS"><i class="fa fa-rss" aria-hidden="true"></i></a>
                <?php } ?>

                <?php
                $bighub_search = 'hideall';
                if( !empty($bigbub_option['bighub_search']) ){
                  $bighub_search = $bigbub_option['bighub_search'];
                }
                ?>
                <?php if ( $bighub_search == 'showall' ) { ?>
                <form class="navbar-form navbar-right" id="searchform" action="<?php echo site_url(); ?>">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="搜索…" value="<?php the_search_query(); ?>" name="s">
                        <span class="input-group-btn"><button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button></span>
                    </div>
                </form>
                <?php } elseif ( $bighub_search == 'pc' ) { ?>
                <form class="navbar-form navbar-right hidden-xs hidden-sm" id="searchform" action="<?php echo site_url(); ?>">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="搜索…" value="<?php the_search_query(); ?>" name="s">
                        <span class="input-group-btn"><button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button></span>
                    </div>
                </form>
                <?php } elseif ( $bighub_search == 'phone' ) { ?>
                <form class="navbar-form navbar-right visible-xs" id="searchform" action="<?php echo site_url();; ?>">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="搜索…" value="<?php the_search_query(); ?>" name="s">
                        <span class="input-group-btn"><button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button></span>
                    </div>
                </form>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>
<a href="#top" id="toTop" style="display: inline;"></a>