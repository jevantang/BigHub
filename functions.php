<?php
/*
Theme Name: BigHub
Theme URI: https://tangjie.me/bighub
Author: Jarvis Tang
Author URI: https://tangjie.me/
Description: A responsible theme for WordPress.
Version: 1.0
License: GNU General Public License v3.0
*/

function tang_theme_setup() {
    tang_clean_theme();
}
add_action( 'after_setup_theme', 'tang_theme_setup' );

//去除头部无用代码
function tang_clean_theme(){
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
    remove_action( 'wp_head', 'locale_stylesheet' );
    remove_action( 'wp_head', 'noindex', 1 );
    remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'rel_canonical' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
    remove_action( 'wp_head', 'wp_oembed_add_host_js');
    remove_action( 'wp_head', 'wp_resource_hints', 2 );
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
    remove_action( 'publish_future_post', 'check_and_publish_future_post', 10, 1 );
    remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
    remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
    remove_action( 'rest_api_init', 'wp_oembed_register_route');
    remove_filter( 'rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10);
    remove_filter( 'oembed_response_data', 'get_oembed_response_data_rich', 10, 4);
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');
}

//移除菜单的多余CSS选择器
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function my_css_attributes_filter($var) {
    return is_array($var) ? array() : '';
}

//禁用Emoji表情
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );
function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    }
    else {
        return array();
    }
}

//禁用修订版本
add_filter( 'wp_revisions_to_keep', 'specs_wp_revisions_to_keep', 10, 2 );
function specs_wp_revisions_to_keep( $post ) {
    return 0;
}

//启动友情链接
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

//替换Gravatar服务器
function replace_gravatar($avatar) {
    $avatar = str_replace(array("//gravatar.com/", "//secure.gravatar.com/", "//www.gravatar.com/", "//0.gravatar.com/", "//1.gravatar.com/", "//2.gravatar.com/", "//cn.gravatar.com/"), "//sdn.geekzu.org/", $avatar);
    return $avatar;
}
add_filter( 'get_avatar', 'replace_gravatar' );

//分页
function pagination($query_string){
    global $posts_per_page, $paged;
    $my_query = new WP_Query($query_string ."&posts_per_page=-1");
    $total_posts = $my_query->post_count;
    if(empty($paged))$paged = 1;
    $prev = $paged - 1;
    $next = $paged + 1;
    $range = 5; //分页数设置
    $showitems = ($range * 2)+1;
    $pages = ceil($total_posts/$posts_per_page);
    if(1 != $pages){
        echo "<ul class='pagination'>";
        echo ($paged > 2 && $paged+$range+1 > $pages && $showitems < $pages)? "<li><a href='".get_pagenum_link(1)."'><i class='fa fa-angle-double-left' aria-hidden='true'></i></a></li>":"";
        echo ($paged > 1 && $showitems < $pages)? "<li><a href='".get_pagenum_link($prev)."'><i class='fa fa-angle-left' aria-hidden='true'></i></a></li>":"";
        for ($i=1; $i <= $pages; $i++){
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
                echo ($paged == $i)? "<li class='active'><a href='".get_pagenum_link($i)."'>".$i."<span class='sr-only'>(current)</span></a></li>":"<li><a href='".get_pagenum_link($i)."'>".$i."</a></li>";
            }
        }
    echo ($paged < $pages && $showitems < $pages) ? "<li><a href='".get_pagenum_link($next)."'><i class='fa fa-angle-right' aria-hidden='true'></i></a></li>" :"";
    echo ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) ? "<li><a href='".get_pagenum_link($pages)."'><i class='fa fa-angle-double-right' aria-hidden='true'></i></a></li>":"";
    echo "</ul>";
    }
}

//过滤HTML格式
function utf8Substr($str, $from, $len) {
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s','$1',$str);
}

//定义标题内容和格式
function tang_wp_title( $title, $sep, $seplocation ) {
    global $paged, $page,$post;
    $bigbub_option = get_option('bighub_config');

    if ( is_feed() )
        return $title;
    $sep = '_';

    $title .= get_bloginfo( 'name' );
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
    $title = "$title$sep$site_description";

    if(is_home() || is_front_page()){
        if(!empty($bigbub_option['bighub_title'])){
            $title = $bigbub_option['bighub_title'];
        }
    }
    if ( $paged >= 2 || $page >= 2 )
    $title = "$title$sep" . sprintf( 'Page %s', max( $paged, $page ) );

    if( is_tag()){
        $title = single_tag_title('',false).' - '.get_bloginfo( 'name' );
    }

    if( is_single() ){
        $currentpost = get_queried_object();
        $title = $currentpost->post_title;
    }

    if( is_category() || is_tax()){
        $title = single_cat_title('',false).' - '.get_bloginfo( 'name' );
    }

    if ( is_search() ) {
        $title = '搜索结果 - '.get_bloginfo( 'name' );
    }

    if ( is_day() ) {
        $title = get_the_time('Y年n月j日').' - '.get_bloginfo( 'name' );
    }
    if ( is_month() ) {
        $title = get_the_time('Y年M').' - '.get_bloginfo( 'name' );
    }
    if ( is_year() ) {
        $title = get_the_time('Y年').' - '.get_bloginfo( 'name' );
    }

    return $title;
}
add_filter( 'wp_title', 'tang_wp_title', 10, 3 );

//加载css和js
function tang_scripts_styles() {
    //css
    wp_enqueue_style( 'bootstrap_min', get_template_directory_uri().'/css/bootstrap.min.css', array(), '1.0' );
    wp_enqueue_style( 'theme_custom_style', get_stylesheet_uri(), array(), '1.0' );

    //js
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'bootstrap_min', get_template_directory_uri().'/js/bootstrap.min.js', array(), '1.0', true);
    wp_enqueue_script( 'scrolltotop', get_template_directory_uri().'/js/jquery_scrolltotop.js', array(), '1.0', true);

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'tang_scripts_styles' );

//后台设置框架
class tang_framework_core {
    public function enqueue_css_js() {
        wp_enqueue_style( 'bootstrap_min', get_template_directory_uri().'/css/bootstrap.min.css', array(), '1.0' );
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'scrolltotop', get_template_directory_uri().'/js/bootstrap.min.js', array(), '1.0' );
    }
    function before_tags($values){
        $class = array('tang_field');
        $class[] = 'form-group';
        if( !empty($values['class']) ){
            $class[] = $values['class'];
        }
        $name = '';
        if( !empty($values['name']) ){
            $name = $values['name'];
        }
        if($values['type']=='title'){
            $values['id'] = '';
        }
        echo '<div class="'.implode(' ', $class).'">';
        if( !empty($name) ){
            echo '<label class="col-sm-2 control-label" for="'.$values['id'].'">'.$name.'</label>';
        }
        echo '<div class="col-sm-10">';
    }
    function after_tags(){
        echo '</div></div>';
    }

    /*hr*/
    public function hr($values) {
        echo '<hr>';
    }
    /**input type=text**/
    public function text($values) {
        if( empty($values['id']) )
        return;
        if( empty($values['std']) ){
            $values['std'] = '';
        }
        if( !empty($values['desc']) ){
            $values['desc'] = ''.$values['desc'].'';
        }else{
            $values['desc'] = '';
        }
        $format = '<input type="text" id="%s" name="%s" value="%s" class="form-control" placeholder="%s">';
        $this->before_tags($values);
        echo sprintf( $format, $values['id'], $values['id'], $values['std'], $values['desc'] );
        $this->after_tags();
    }
    /**input type=color**/
    public function color($values) {
        if( empty($values['id']) )
        return;
        if( empty($values['std']) ){
            $values['std'] = '';
        }
        if( !empty($values['desc']) ){
            $values['desc'] = '<div style="padding-top:9px;padding-left:50px;overflow:hidden">'.$values['desc'].'</div>';
        }else{
            $values['desc'] = '';
        }
        $format = '<div style="float:left!important;padding-top:5px"><input type="text" id="%s" name="%s" value="%s" class="input-color"></div>%s';
        $this->before_tags($values);
        echo sprintf( $format, $values['id'], $values['id'], $values['std'], $values['desc'] );
        $this->after_tags();
    }
    /*textarea*/
    public function textarea($values) {
        if( empty($values['id']) )
        return;
        if( empty($values['std']) ){
            $values['std'] = '';
        }
        if( !empty($values['desc']) ){
            $values['desc'] = ''.$values['desc'].'';
        }else{
            $values['desc'] = '';
        }
        $format = '<textarea id="%s" name="%s" class="form-control" placeholder="%s">%s</textarea>';
        $this->before_tags($values);
        echo sprintf( $format, $values['id'], $values['id'], $values['desc'], $values['std'] );
        $this->after_tags();
    }
    /*select*/
    public function select($values) {
        if( empty($values['id']) )
        return;
        if( !empty($values['desc']) ){
            $values['desc'] = ''.$values['desc'].'';
        }else{
            $values['desc'] = '';
        }
        $entries = $values['subtype'];
        $format = '<option value="%s" %s >%s</option>';
        $this->before_tags($values);
        echo '<select class="tang_field_select" id="'. $values['id'] .'" name="'. $values['id'] .'"> ';
            foreach ($entries as $id => $title) {
                if ($values['std'] == $id ) {
                    $selected = "selected='selected'";
                }else{
                    $selected = "";
                }
                echo '<option '.$selected.' value="'. $id.'">'. $title.'</option>';
            }
        echo '</select>';
        echo $values['desc'];
        $this->after_tags();
    }
}

//后台设置功能
class tang_options_feild extends tang_framework_core {
    private $options;
    private $old_value;
    private $pageinfo;
    private $saved_optionname;

    function __construct($ashu_option_conf, $pageinfo) {
        $this->options = $ashu_option_conf;
        $this->pageinfo = $pageinfo;
        $this->saved_optionname = 'bighub_'.$this->pageinfo['optionname'];
        add_action( 'admin_menu', array(&$this, 'add_admin_menu') );
        if( isset($_GET['page']) && ($_GET['page'] == $this->pageinfo['filename']) ) {
            add_action('admin_enqueue_scripts', array(&$this, 'enqueue_css_js'));
        }
    }

    function add_admin_menu() {
        if($this->pageinfo['child']) {
            $parent_slug = $this->pageinfo['parent_slug'];
            add_submenu_page($parent_slug, $this->pageinfo['full_name'], $this->pageinfo['full_name'], 'manage_options', $this->pageinfo['filename'], array(&$this, 'initialize'));
        }else{
            $page_hook = add_menu_page($this->pageinfo['full_name'], $this->pageinfo['full_name'], 'manage_options', $this->pageinfo['filename'], array(&$this, 'initialize'),'',26);
            add_action( 'admin_print_footer_scripts-' . $page_hook, 'admin_inline_js' );
        }
    }

    function make_data_available() {
        $this->old_value[$this->pageinfo['optionname']] = get_option($this->saved_optionname);
        $this->old_value = $this->htmlspecialchars_deep($this->old_value,ENT_QUOTES,'UTF-8');
        foreach ($this->options as $key => $option) {
            if( isset($option['id']) && isset($this->old_value[$this->pageinfo['optionname']][$option['id']])){
                $this->options[$key]['std'] = $this->old_value[$this->pageinfo['optionname']][$option['id']];
            }
        }
    }

    function htmlspecialchars_deep ($mixed, $quote_style=ENT_QUOTES, $charset='UTF-8') {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->htmlspecialchars_deep($value, $quote_style, $charset);
            }
        } elseif (is_string($mixed)) {
            $mixed = htmlspecialchars_decode($mixed, $quote_style);
        }
        return $mixed;
    }

    function initialize() {
        $this->get_save_options();
        $this->make_data_available();
        $this->display();
    }

    function display() {
        $saveoption = false;
        echo '<div class="container-fluid">';
        echo '<h2 class="text-primary">'.$this->pageinfo['full_name'].' <a href="https://tangjie.me/bighub" target="_blank" data-toggle="tooltip" data-placement="bottom" title="点击查看更新"><span class="badge">v1.0</span></a></h2>';
        echo '<hr class="wp-header-end"><hr>';
        echo '<form method="post" class="form-horizontal" action="">';

        foreach ($this->options as $option) {
            if( ( $option['type']=='open' || $option['type']=='close' || $option['type']=='title') || ( isset($option['id']) && method_exists($this, $option['type']) ) ) {
                if( !isset($option['std']) )
                $option['std'] = '';
                if( in_array($option['type'],array('text','textarea',)) && ( !empty($option['multiple']) && $option['multiple']==false ) )
                $option['std'] = htmlspecialchars($option['std'],ENT_COMPAT, 'UTF-8');
                $this->{$option['type']}($option);
                $saveoption = true;
            }
        }
        if($saveoption) {
            echo '<div class="form-group"><div class="col-sm-offset-2 col-sm-10">';
            wp_nonce_field( 'tang_framework_action','tang_framework_field' );
            echo '<input type="submit" name="tang_framework_submit" class="btn btn-primary autowidth" value=" 保 存 " /></div></div>';
        }
        echo '</form></div>';
    }

    function get_save_options() {
        $newoptions  = $this->old_value;
        if( isset($_REQUEST['tang_framework_field']) && check_admin_referer('tang_framework_action', 'tang_framework_field') ) {
            if(!empty($_POST['tang_framework_reset'])){
                echo '<div class="updated notice is-dismissible" id="message" style=""><p><strong>Option reseted.</strong></p></div>';
                delete_option( $this->saved_optionname );
            }
            if(!empty($_POST['tang_framework_submit'])){
                echo '<div class="updated notice is-dismissible" id="message" style=""><p><strong>保存成功</strong></p></div>';
                foreach($this->options as $option) {
                    if( in_array( $option['type'], array('text','textarea') ) ){
                        $value = stripslashes( $_POST[$option['id']] );
                        $value = htmlspecialchars($value,ENT_COMPAT, 'UTF-8');
                        $newoptions[$option['id']] = $value;
                    }else{
                        $value = htmlspecialchars($_POST[$option['id']], ENT_QUOTES,"UTF-8");
                        $newoptions[$option['id']] = $value;
                    }
                }
                if ( $this->old_value != $newoptions ) {
                    update_option($this->saved_optionname, $newoptions);
                }
            }
            do_action('tang_custom_saved');
        }
    }
}

function admin_inline_js(){
    echo '<script type="text/javascript">';
    echo 'jQuery(document).ready( function($){';
    echo '$(\'[data-toggle="tooltip"]\').tooltip();';
    echo '$(\'[class="input-color"]\').wpColorPicker();';
    echo '});';
    echo '</script>';
}

//后台设置配置
$page_info = array(
    'full_name' => 'BigHub',
    'optionname'=>'config',
    'child'=>false,
    'filename' => 'bighub'
);
$ashu_options = array();
$ashu_options[] = array(
    'name' => '首页标题 Title',
    'id'   => 'bighub_title',
    'type' => 'text',
    'desc' => '它将显示在首页的 title 标签里'
);
$ashu_options[] = array(
    'name' => '首页描述 Description',
    'id' => 'bighub_description',
    'type' => 'textarea',
    'desc' => '它将显示在首页的 meta 标签的 description 属性里'
);
$ashu_options[] = array(
    'name' => '首页关键字 KeyWords',
    'id' => 'bighub_keywords',
    'type' => 'textarea',
    'desc' => '它将显示在首页的 meta 标签的 keywords 属性里。多个关键字以英文逗号隔开，例如: 标签,关键字,关键词'
);
$ashu_options[] = array(
    'id' => 'hr1',
    'type' => 'hr'
);
$ashu_options[] = array(
    'name' => 'LOGO 1x',
    'id' => 'bighub_logo',
    'type' => 'text',
    'std' => 'https://bighub.me/media/logo.png'
);
$ashu_options[] = array(
    'name' => 'LOGO 2x',
    'id' => 'bighub_logo_2x',
    'type' => 'text',
    'std' => 'https://bighub.me/media/logo@2x.png'
);
$ashu_options[] = array(
    'id' => 'hr2',
    'type' => 'hr'
);
$ashu_options[] = array(
    'name' => '版权年份(页脚)',
    'id' => 'bighub_years',
    'type' => 'text',
    'std' => '2017'
);
$ashu_options[] = array(
    'name' => '版权公司(页脚)',
    'id' => 'bighub_company',
    'type' => 'text',
    'std' => '产品经理@唐杰'
);
$ashu_options[] = array(
    'name' => '统计代码(页脚)',
    'id' => 'bighub_analytics',
    'type' => 'textarea',
    'desc' => '代码在页面底部，统计标识不会显示，但不影响统计效果'
);
$ashu_options[] = array(
    'id' => 'hr3',
    'type' => 'hr'
);
$ashu_options[] = array(
    'name' => '是否显示搜索框',
    'id' => 'bighub_search',
    'type' => 'select',
    'std' => 'hideall',
    'subtype'=>array(
        'hideall'=>'全部隐藏',
        'showall'=>'电脑端和手机端都显示',
        'pc'=>'仅电脑端显示',
        'phone'=>'仅手机端显示',
    ),
);
$ashu_options[] = array(
    'id' => 'hr4',
    'type' => 'hr'
);
$ashu_options[] = array(
    'name' => '是否显示微信公众号',
    'id' => 'bighub_wechat',
    'type' => 'select',
    'std' => 'hide',
    'subtype'=>array(
        'hide'=>'隐藏',
        'show'=>'显示'
    ),
);
$ashu_options[] = array(
    'name' => '公众号二维码图片',
    'id' => 'bighub_wechat_img',
    'type' => 'text',
    'std' => 'https://tangjie.me/media/weixin.jpg'
);
$ashu_options[] = array(
    'name' => '微信公众号介绍',
    'id' => 'bighub_wechat_info',
    'type' => 'text',
    'std' => '每周推送一次精选汇总'
);
$option_page = new tang_options_feild($ashu_options, $page_info);