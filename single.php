<?php get_header(); ?>

<section id="content">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php get_template_part( 'content', get_post_format() ); ?>
<!--Views: <?php the_view(); ?>-->
<?php endwhile;?>
<?php endif; ?>


<?php comments_template( '', true ); ?>

</section>

<?php get_footer(); ?>

