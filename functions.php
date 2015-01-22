<?php
// SSL Gravatar
function get_ssl_avatar($avatar) {
   $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://secure.gravatar.com/avatar/$1?s=$2" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
   return $avatar;
}
add_filter('get_avatar', 'get_ssl_avatar');

//自定义表情路径
function custom_smilies_src($src, $img){
    return get_template_directory_uri() . '/images/smilies/' . $img;
}
add_filter('smilies_src', 'custom_smilies_src', 10, 2);

//注册各种
function croquis_scripts() {
  wp_enqueue_style( 'croquis_style', get_stylesheet_uri() );
  wp_register_script( 'ajax_url_all', get_template_directory_uri() . '/script/script.js',array( 'jquery' ) );
  wp_localize_script( 'ajax_url_all', 'ajaxurl', array(
        'ajax_url'   => admin_url('admin-ajax.php')
    ) );
  wp_enqueue_script( 'ajax_url_all' );
}
add_action( 'wp_enqueue_scripts', 'croquis_scripts' );

//注册头部导航
if ( function_exists('register_nav_menus') ) {
	register_nav_menus(array('header-menu' => '头部导航栏'));
}

//去除链接的版本号
if(!function_exists('cwp_remove_script_version')){
    function cwp_remove_script_version( $src ){  return remove_query_arg( 'ver', $src ); }
    add_filter( 'script_loader_src', 'cwp_remove_script_version' );
    add_filter( 'style_loader_src', 'cwp_remove_script_version' );
}

//恢复自带链接管理器
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// 只搜索文章，排除页面
add_filter('pre_get_posts','search_filter');
function search_filter($query) {
if ($query->is_search) {$query->set('post_type', 'post');}
return $query;}

// 新窗口打开评论链接
function hu_popuplinks($text) {
  $text = preg_replace('/<a (.+?)>/i', "<a $1 target='_blank'>", $text);
  return $text;
}
add_filter('get_comment_author_link', 'hu_popuplinks', 6);	

//移除评论信息中的网站地址
function remove_comment_fields($fields) {
    unset($fields['url']);
    return $fields;
}
add_filter('comment_form_default_fields','remove_comment_fields');

//去除评论中的链接
remove_filter('comment_text', 'make_clickable', 9);

add_filter('comment_text', 'auto_nofollow');

//给评论中的链接自动加上nofollow
function auto_nofollow($content) {
    //return stripslashes(wp_rel_nofollow($content));

    return preg_replace_callback('/<a>]+/', 'auto_nofollow_callback', $content);
}

function auto_nofollow_callback($matches) {
    $link = $matches[0];
    $site_link = get_bloginfo('url');

    if (strpos($link, 'rel') === false) {
        $link = preg_replace("%(href=S(?!$site_link))%i", 'rel="nofollow" $1', $link);
    } elseif (preg_match("%href=S(?!$site_link)%i", $link)) {
        $link = preg_replace('/rel=S(?!nofollow)S*/i', 'rel="nofollow"', $link);
    }
    return $link;
}

//文章浏览数 By Bigfa
//http://fatesinger.com/73950
function the_view( $zero = false, $one = false, $more = false ){
    echo get_the_view( $zero, $one, $more );
}

function get_the_view( $zero = false, $one = false, $more = false ){
    $views = get_the_view_num();
    if ( $views > 1 ) {
        $output = str_replace( '%', restyle_text( $views ), ( false === $more ) ? '% reads' : $more );
    } elseif ( $views == 0 ) {
        $output = ( false === $zero ) ? 'No reads' : $zero;
    } else {
        $output = ( false === $one ) ? '1 read' : $one;
    }
    return $output;
}

function get_the_view_num( $post = 0 ){
    $post = get_post( $post );
    $views = isset( $post->post_views ) ? $post->post_views : '';
    if( is_singular() ) $views = $views + 1;
    return $views;
}

function restyle_text($number) {
    if($number >= 1000) {
       return round($number/1000,2) . "k";   // NB: you will want to round this
    }
    else {
        return $number;
    }
}

function fa_set_post_view() {
    global $post;
    $post_id = intval($post->ID);
    $views = intval($post->post_views);
    if (is_singular() && !is_spider()) {
        fa_update_post_meta($post_id,'post_views',($views + 1));
    }
}
add_action('get_header', 'fa_set_post_view');

function fa_update_post_meta($id,$meta_type,$value){
    global $wpdb;
    $wpdb->update(
        $wpdb->posts,
        array(
            $meta_type => $value
        ),
        array( 'ID' => $id ),
        array(
            '%d'
        ),
        array( '%d' )
    );
}

function fa_get_total_views(){
    global $wpdb;
    $total_views = $wpdb->get_var("SELECT SUM(post_views) FROM $wpdb->posts WHERE post_status = 'publish'");
    return restyle_text( $total_views );
}

add_action( 'after_switch_theme', 'tgthemes_init' );
function tgthemes_init(){
    global $wpdb;   
    $tableposts = $wpdb->posts;
    $wpdb->query("ALTER TABLE $wpdb->posts 
    ADD post_views BIGINT(20) NOT NULL;");

}
function is_spider(){
    $is_spider = false;
    $bots = array('Google Bot' => 'googlebot', 'Google Bot' => 'google', 'MSN' => 'msnbot', 'Alex' => 'ia_archiver', 'Lycos' => 'lycos', 'Ask Jeeves' => 'jeeves', 'Altavista' => 'scooter', 'AllTheWeb' => 'fast-webcrawler', 'Inktomi' => 'slurp@inktomi', 'Turnitin.com' => 'turnitinbot', 'Technorati' => 'technorati', 'Yahoo' => 'yahoo', 'Findexa' => 'findexa', 'NextLinks' => 'findlinks', 'Gais' => 'gaisbo', 'WiseNut' => 'zyborg', 'WhoisSource' => 'surveybot', 'Bloglines' => 'bloglines', 'BlogSearch' => 'blogsearch', 'PubSub' => 'pubsub', 'Syndic8' => 'syndic8', 'RadioUserland' => 'userland', 'Gigabot' => 'gigabot', 'Become.com' => 'become.com', 'Baidu' => 'baiduspider', 'so.com' => '360spider', 'Sogou' => 'spider', 'soso.com' => 'sosospider', 'Yandex' => 'yandex');
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    foreach ( $bots as $name => $lookfor ) {
        if ( stristr( $useragent, $lookfor ) !== false ) {
            $is_spider = true;
            break;
        }
    }
    return $is_spider;
}

//Ajax 文章加载
add_action('wp_ajax_nopriv_ajax_index_post', 'ajax_index_post');
add_action('wp_ajax_ajax_index_post', 'ajax_index_post');
function ajax_index_post(){
    $paged = $_POST["paged"];
    $total = $_POST["total"];
    $category = $_POST["category"];
    $author = $_POST["author"];
    $tag = $_POST["tag"];
    $search = $_POST["search"];
    $the_query = new WP_Query( array("posts_per_page"=>get_option('posts_per_page'),"cat"=>$category,"tag"=>$tag,"author"=>$author,"post_status"=>"publish","post_type"=>"post","paged"=>$paged,"s"=>$search) );
    while ( $the_query->have_posts() ){
        $the_query->the_post();
        get_template_part( 'content', get_post_format() );//这里是内容输出，如果你的首页是直接用的代码输出，则直接写在这里，注意PHP的开始结束符

    }
    wp_reset_postdata();
    $nav = '';
    if($category) $cat_id = ' data-cate="'.$category.'"';
    if($author) $author = ' data-author="'.$author.'"';
    if($tag) $tag = ' data-tag="'.$tag.'"';
    if($search) $search = ' data-search="'.$search.'"';
    if ( $total > $paged )  {  $nav = '<div id="page-navi-index" class="page-navi index"><span id="show-more"'.$cat_id.$author.$search.' data-total="'.$total.'" data-paged = "'.($paged + 1).'" class="show-more m-feed--loader">More</span></div>';
      }else{
        $nav = '<div id="page-navi-index" class="page-navi index"><span class="show-more m-feed--loader load-fin">- Fin -</span></div>';
      }
    echo $nav;
    
    die;
}
//翻页按钮
function ajax_show_more_button(){
    global $wp_query;
    if( 2 > $GLOBALS["wp_query"]->max_num_pages){
        return;
    }
    if(is_category()) $cat_id = ' data-cate="'.get_query_var( 'cat' ).'"';    
    if(is_author()) $author = ' data-author="'.get_query_var('author').'"';
    if(is_tag()) $tag = ' data-tag="'.get_query_var('tag').'"';
    if(is_search()) $search = ' data-search="'.get_query_var('s').'"';
    echo '<div id="page-navi-index" class="page-navi index"><span id="show-more"'.$cat_id.' data-paged = "2"'.$author.$tag.$search.' data-total="'.$GLOBALS["wp_query"]->max_num_pages.'" class="show-more m-feed--loader">More</span></div>';

}

//WordPress Ajax 赞 By Bigfa http://fatesinger.com/115
add_action('wp_ajax_nopriv_bigfa_like', 'bigfa_like');
add_action('wp_ajax_bigfa_like', 'bigfa_like');
function bigfa_like(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'ding'){
    $bigfa_raters = get_post_meta($id,'bigfa_ding',true);
    $expire = time() + 99999999;
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost' ) ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
    setcookie('bigfa_ding_'.$id,$id,$expire,'/',$domain,false);
    if (!$bigfa_raters || !is_numeric($bigfa_raters)) {
        update_post_meta($id, 'bigfa_ding', 1);
    } 
    else {
            update_post_meta($id, 'bigfa_ding', ($bigfa_raters + 1));
        }
   
    echo get_post_meta($id,'bigfa_ding',true);
    
    } 
    
    die;
}

//comment_popup_links只统计评论数
if (function_exists('wp_list_comments')) {
	// comment count
	add_filter('get_comments_number', 'comment_count', 0);
	function comment_count( $commentcount ) {
		global $id;
		$_commnets = get_comments('post_id=' . $id);
		$comments_by_type = &separate_comments($_commnets);
		return count($comments_by_type['comment']);
	}
}
 
//自动转义邮箱地址 By Ludou
function security_remove_emails($content) {
    $pattern = '/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})/i';
    $fix = preg_replace_callback($pattern, "security_remove_emails_logic", $content);

    return $fix;
}

function security_remove_emails_logic($result) {
    return antispambot($result[1]);
}

add_filter( 'the_content', 'security_remove_emails', 20 );
add_filter( 'comment_text', 'security_remove_emails', 20 );

// 反全英文垃圾评论
	function scp_comment_post( $incoming_comment ) {
		$pattern = '/[一-龥]/u';
		
		if(!preg_match($pattern, $incoming_comment['comment_content'])) {
			ajax_comment_err( "You should type some Chinese word (like \"你好\") in your comment to pass the spam-check, thanks for your patience! 您的评论中必须包含汉字!" );
		}
		return( $incoming_comment );
	}
	add_filter('preprocess_comment', 'scp_comment_post');
	
	/**
	 * when comment check the comment_author comment_author_email
	 * @param unknown_type $comment_author
	 * @param unknown_type $comment_author_email
	 * @return unknown_type
	 *防止访客冒充博主发表评论 by Winy
	 */
	function CheckEmailAndName(){
		global $wpdb;
		$comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
		$comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
		if(!$comment_author || !$comment_author_email){
			return;
		}
		$result_set = $wpdb->get_results("SELECT display_name, user_email FROM $wpdb->users WHERE display_name = '" . $comment_author . "' OR user_email = '" . $comment_author_email . "'");
		if ($result_set) {
			if ($result_set[0]->display_name == $comment_author){
				ajax_comment_err(__('You CANNOT use this name.'));//昵称
			}else{
				ajax_comment_err(__('You CANNOT use this email.'));//邮箱
			}
			fail($errorMessage);
		}
	}
	add_action('pre_comment_on_post', 'CheckEmailAndName');


// 去掉Category函数里的rel泛滥的HTML5错误
foreach(array(
    'rsd_link',//rel="EditURI"
    'index_rel_link',//rel="index"
    'start_post_rel_link',//rel="start"
    'wlwmanifest_link'//rel="wlwmanifest"
  ) as $xx)
  remove_action('wp_head',$xx);//X掉以上
  //rel="category"或rel="category tag", 这个最巨量
  function the_category_filter($thelist){
    return preg_replace('/rel=".*?"/','rel="tag"',$thelist);
  } 
  add_filter('the_category','the_category_filter');

//时间显示xx前
//http://www.daqianduan.com/4198.html
function the_timeago(){
  $suffix=' Ago';
  $endtime='3456000';
  $year = ' Year';
  $years = ' Years';
  $month = ' Months';
  $day = ' Days';
  $hour = ' Hours';
  $minute = ' Mins';
  $second = ' Seconds';
  if ($_SERVER['REQUEST_TIME'])
      $now_time = $_SERVER['REQUEST_TIME'];
  else
      $now_time = time();
  $m = 60;  // 一分钟
  $h = 3600;  //一小时有3600秒
  $d = 86400;  // 一天有86400秒
  $mo = 2592000;  //一个月有2592000秒
  $y = 31536000;  //一年有31536000秒
  $endtime = (int)$endtime;  // 结束时间
  $post_time = get_post_time('U', true);
  $past_time = $now_time - $post_time;  // 文章发表至今经过多少秒
  if($past_time < $m){ //小于1分钟
      $past_date = $past_time . $second;
  }else if ($past_time < $h){ //小于1小时
      $past_date = $past_time / $m;
      $past_date = floor($past_date);
      $past_date .= $minute;
  }else if ($past_time < $d){ //小于1天
      $past_date = $past_time / $h;
      $past_date = floor($past_date);
      $past_date .= $hour;
  }else if ($past_time < $mo){
      $past_date = $past_time / $d;
      $past_date = floor($past_date);
      $past_date .= $day;
  }else if ($past_time < $y){
      $past_date = $past_time / $mo;
      $past_date = floor($past_date);
      $past_date .= $month;
  }else if ($past_time < $y*2){
      $past_date = $past_time / $y;
      $past_date = floor($past_date);
      $past_date .= $year;
  }else if ($past_time < $y*4){
      $past_date = $past_time / $y;
      $past_date = floor($past_date);
      $past_date .= $years;
  }else{
      echo 'Long Ago';
      return;
  }
  echo $past_date . $suffix;
}

 /* -----------------------------------------------
 <;<小牆>> Anti-Spam v1.9 by Willin Kan.
 */
 //建立
 class anti_spam {
   function anti_spam() {
     if ( !is_user_logged_in() ) {
       add_action('template_redirect', array($this, 'w_tb'), 1);
       add_action('pre_comment_on_post', array($this, 'gate'), 1);
       add_action('preprocess_comment', array($this, 'sink'), 1);
     }
   }
   //設欄位
   function w_tb() {
     if ( is_singular() ) {
       ob_start(create_function('$input', 'return preg_replace("#textarea(.*?)name=([\"\'])comment([\"\'])(.+)/textarea>#",
	   "textarea$1name=$2w$3$4/textarea><textarea name=\"comment\" cols=\"60\" rows=\"4\" style=\"display:none\"></textarea>", $input);') );
      }
   }
   //檢查
function gate() {
    if ( !empty($_POST['w']) && empty($_POST['comment']) ) {
      $_POST['comment'] = $_POST['w'];
    } else {
      $request = $_SERVER['REQUEST_URI'];
      $referer = isset($_SERVER['HTTP_REFERER'])         ? $_SERVER['HTTP_REFERER']         : '隐瞒';
      $IP      = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] . ' (透过代理)' : $_SERVER["REMOTE_ADDR"];
      $way     = isset($_POST['w'])                      ? '手动操作'                       : '未经评论表格';
      $spamcom = isset($_POST['comment'])                ? $_POST['comment']                : null;
      $_POST['spam_confirmed'] = "请求: ". $request. "\n来路: ". $referer. "\nIP: ". $IP. "\n方式: ". $way. "\n內容: ". $spamcom. "\n -- 记录成功 --";
    }
  }
   //處理
   function sink( $comment ) {
     if ( !empty($_POST['spam_confirmed']) ) {
       //方法一:直接擋掉, 將 die(); 前面兩斜線刪除即可.
       //die();
       //方法二:標記為spam, 留在資料庫檢查是否誤判.
       add_filter('pre_comment_approved', create_function('', 'return "spam";'));
       $comment['comment_content'] = "[ 小墙判断这是 Spam! ]\n". $_POST['spam_confirmed'];
     }
     return $comment;
   } 
}
$anti_spam = new anti_spam();

// -- END ----------------------------------------

// removes detailed login error information for security 移除wordpress登陆错误提示
add_filter('login_errors',create_function('$a', "return null;"));

/* Ajax 评论分页 */
add_action('wp_ajax_nopriv_ajax_comment_page_nav', 'ajax_comment_page_nav');
add_action('wp_ajax_ajax_comment_page_nav', 'ajax_comment_page_nav');
function ajax_comment_page_nav(){
    global $post,$wp_query, $wp_rewrite;
    $postid = $_POST["um_post"];
    $pageid = $_POST["um_page"];
    $comments = get_comments('post_id='.$postid);
    $post = get_post($postid);
    if( 'desc' != get_option('comment_order') ){
        $comments = array_reverse($comments);
    }
    $wp_query->is_singular = true;
    $baseLink = '';
    if ($wp_rewrite->using_permalinks()) {
        $baseLink = '&base=' . user_trailingslashit(get_permalink($postid) . 'comment-page-%#%', 'commentpaged');
    }
    echo '<ol class="commentlist" >';
    wp_list_comments('type=comment&callback=themecomment&max_depth=500&page=' . $pageid . '&per_page=' . get_option('comments_per_page'), $comments);//注意修改mycomment这个callback
    echo '</ol>';
    echo '<div id="commentnav" data-post-id='.$postid.'">';
    paginate_comments_links('current=' . $pageid . '&prev_text=« Prev&next_text=Next »');
    echo '</div>';
    die;
}
// 评论回复构架
require get_template_directory() . '/ajax-comment/do.php';

function themecomment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
    global $commentcount;
    if(!$commentcount) {
       $page = ( !empty($in_comment_loop) ) ? get_query_var('cpage')-1 : get_page_of_comment( $comment->comment_ID, $args )-1;
       $cpp = get_option('comments_per_page');
       $commentcount = $cpp * $page;
    }
    /* 区分普通评论和Pingback */
    switch ($pingtype=$comment->comment_type) {
    case 'pingback' : /* 标识Pingback */
    case 'trackback' : /* 标识Trackback */

?>

<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
  <div id="comment-<?php comment_ID(); ?>">
    <div class="comment-author vcard pingback">
      <span class="fn pingback"><?php comment_date('Y-m-d') ?> &raquo; <?php comment_author_link(); ?></span>
    </div>
  </div>

  <?php
    break;
    /* 标识完毕 */
    default : /* 普通评论部分 */ 
    if(!$comment->comment_parent){ ?>

<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

  <article id="comment-<?php comment_ID(); ?>" class="comment-body">

    <header class="comment-header">
      <span class="comment-author"><?php printf( __( '%s says:'), get_comment_author_link() ); ?></span>
    </header>

    <section class="comment-content">
      <?php comment_text(); ?>
    </section>

    <span class="floor flr"><?php printf('%1$s L', ++$commentcount); ?></span>

    <footer class="comment-footer">
      <span class="datetime"><?php comment_date('Y-m-d') ?> <?php comment_time() ?> </span>
      <span class="reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => 'Reply'))) ?></span>
    </footer>

  </article>

<?php }else{?>

<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

  <article id="comment-<?php comment_ID(); ?>" class="comment-body comment-children-body">
    <header class="comment-header">
      <span class="comment-author"><?php $parent_id = $comment->comment_parent; $comment_parent = get_comment($parent_id); printf('%s', get_comment_author_link()) ?> to <a href="<?php echo "#comment-".$parent_id;?>"><?php echo $comment_parent->comment_author;?></a>: </span>
    </header>

    <section class="comment-content">
      <?php comment_text(); ?>
    </section>

    <span class="floor flr"><?php if( $depth > 1){printf('B%1$s', $depth-1);} ?></span>

    <footer class="comment-footer">
      <span class="datetime"><?php comment_date('Y-m-d') ?> <?php comment_time() ?> </span>
      <span class="reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => 'Reply'))) ?></span>
    </footer>

  </article>


<?php }
break; /* 普通评论标识完毕 */
  }
}