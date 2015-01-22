<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta http-equiv="Cache-Control" content="no-siteapp" /><!-- 禁止百度吃屎的移动版转码 -->

<!--[if IE]><html lang="zh-CN">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
<?php if( is_single() || is_page() ) {
    if( function_exists('get_query_var') ) {
        $cpage = intval(get_query_var('cpage'));
        $commentPage = intval(get_query_var('comment-page'));
    }
    if( !empty($cpage) || !empty($commentPage) ) {
        echo '<meta name="robots" content="noindex, nofollow" />';
        echo "\n";
    }
}
//禁止搜索引擎收录评论分页
?>
<title><?php global $page, $paged;wp_title( '&raquo;', true, 'right' );bloginfo( 'name' );$site_description = get_bloginfo( 'description', 'display' );if ( $site_description && ( is_home() || is_front_page() ) ) echo " &raquo; $site_description";if ( $paged >= 2 || $page >= 2 ) echo ' | ' . sprintf( __( '第 %s 页'), max( $paged, $page ) );?></title>

<meta name = "viewport" content ="initial-scale=1.0,maximum-scale=1,user-scalable=no,minimal-ui">
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>"/>
<meta name="format-detection" content="telephone=no" /> <!-- 禁止数字自动识别为电话号码 -->

<!--wp_head-->
<?php wp_head(); ?>
<!--end wp_head-->
</head>

<body <?php body_class(); ?>>

<header class="main-header clear">

<div class="header-titles">
  <h1 id="blog-title" ><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
  <h2 id="blog-description"><?php bloginfo('description'); ?></h2>
</div>

<nav class="header-nav clear">
<?php if ( !has_nav_menu( 'header-menu' ) ) : ?>
  <?php if ( is_user_logged_in() ) : ?>
    <?php echo '<ul class="header-menu"><li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li></ul>' ?>
  <?php endif; ?>
<?php else : ?>
  <ul class="header-menu">
    <?php wp_nav_menu( array('container' => false, 'items_wrap' => '%3$s','menu' => 'header-menu' )); ?>
  </ul>
<?php endif; ?>

</nav>

</header>
