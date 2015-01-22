<?php get_header(); ?>

<section id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<article id="post-<?php the_ID(); ?>" class="post page clear<?php if ( is_sticky() ) :/*置顶文章判定*/ ?> sticky<?php endif; ?>">

<h1 class="post-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>

<div class="entry">
  <?php the_content('');?>
</div>

</article>
<!--Views: <?php the_view(); ?>-->
<?php endwhile;?>
<?php endif; ?>

</section>

<?php get_footer(); ?>

