<?php $bigbub_option = get_option('bighub_config'); //获取设置选项 ?>
<?php
$bighub_wechat = 'hide';
if( !empty($bigbub_option['bighub_wechat']) ){
  $bighub_wechat = $bigbub_option['bighub_wechat'];
}
if ( $bighub_wechat == 'show') {
?>
<div class="widget-wechat">
  <?php
  $bighub_wechat_img = !empty($bigbub_option['bighub_wechat_img']) ? $bigbub_option['bighub_wechat_img'] : get_template_directory_uri().'https://tangjie.me/media/weixin.jpg';
  ?>
  <p><img src="<?php echo $bighub_wechat_img; ?>" alt="<?php bloginfo('name'); ?> 微信公众号"></p>
  <?php if( !empty($bigbub_option['bighub_wechat_info']) ){ ?>
    <p><?php echo $bigbub_option['bighub_wechat_info']; ?></p>
  <?php } ?>
</div>
<?php } ?>

<h3 class="widget-Sponsors">值得关注</h3>
<div class="row Sponsors-list">
    <?php $bookmarks=get_bookmarks('orderby=rating&order=desc');
    if ( !empty($bookmarks) ){
        foreach ($bookmarks as $bookmark) {
            echo '<div class="col-xs-6"><a href="'.$bookmark->link_url.'" target="_blank"><img src="'.$bookmark->link_image.'" alt="'.$bookmark->link_name.'" data-toggle="tooltip" data-placement="top" title="'.$bookmark->link_description.'"></a></div>';
        }
    } ?>
</div>

<div class="widget-footer">
    <p>Powered by WordPress. Theme by <a href="https://tangjie.me/bighub" data-toggle="tooltip" data-placement="top" title="WordPress 主题模板" target="_blank">BigHub v1.0</a></p>
    <?php
    $bighub_years = !empty($bigbub_option['bighub_years']) ? $bigbub_option['bighub_years'] : '2017';
    $bighub_company = !empty($bigbub_option['bighub_company']) ? $bigbub_option['bighub_company'] : '产品经理@唐杰';
    ?>
    <p>&copy;<?php echo $bighub_years; ?> <?php echo $bighub_company; ?> | <?php echo get_option( 'zh_cn_l10n_icp_num' );?></p>
</div>