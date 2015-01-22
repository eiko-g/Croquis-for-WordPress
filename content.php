<article id="post-<?php the_ID(); ?>" class="post normal clear<?php if ( is_sticky() ) :/*置顶文章判定*/ ?> sticky<?php endif; ?>">

<h1 class="post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>

<div class="entry">
  <?php the_content('');?>
</div>

<aside class="entry-meta">
  <div class="date">
    <a href="<?php the_permalink() ?>"><?php the_time('Y-m-d'); ?></a>
  </div>
  <ul class="meta-element">
    <li class="timeago"><?php the_timeago(); ?></li>
    <li class="notes"><?php comments_number('0 Note', '1 Note','% Notes'); ?></li>
    <li class="post-tag"><?php $index_cat = get_cat_name(the_category_ID(false)); ?>
  <a href="<?php echo esc_url ( get_category_link ( the_category_ID ( false ) ) ); ?>" rel="tag" title="View all post at <?php echo $index_cat; ?>" class="post-meta-category cat-<?php echo the_category_ID(); ?>"><?php echo '#'.$index_cat; ?></a></li>
  </ul>
  <ul class="other-meta">
    <li class="share">
      <span class="icon-share"></span>
      <div class="share-element" data-title="<?php the_title(); ?>" data-link="<?php the_permalink() ?>">
        <ul>
          <li class="share-weibo"><span class="icon-weibo"></span>Weibo</li>
          <li class="share-twitter"><span class="icon-twitter"></span>Twitter</li>
        </ul>
      </div>
    </li>
    <li class="like"><a data-action="ding" data-id="<?php the_ID(); ?>" class="favorite<?php if(isset($_COOKIE['bigfa_ding_'.$post->ID])){ echo ' done icon-favorite'; }else{ echo ' icon-favorite-outline'; };?>" data-like="+<?php if( get_post_meta($post->ID,'bigfa_ding',true) ){            
                    echo get_post_meta($post->ID,'bigfa_ding',true);
                } else {
                    echo '0';
                }?>"></a></li>
  </ul>

</aside>

</article>