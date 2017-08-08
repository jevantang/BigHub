<?php $bigbub_option = get_option('bighub_config'); //获取设置选项 ?>
<?php wp_footer(); ?>
<script>
jQuery(document).ready( function($){
  $("#toTop").scrollToTop();
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover();
});
</script>

<div style="display:none;">
<?php
if( !empty($bigbub_option['bighub_analytics']) ){
  echo htmlspecialchars_decode($bigbub_option['bighub_analytics']);
}
?>
</div>
</body>
</html>