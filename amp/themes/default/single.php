<?php 

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
    exit; 
}

?>

<?php get_header(); ?>

    <div id="contentwrap">
        <?php if ( have_posts() ): while ( have_posts() ): the_post(); ?>

        <div id="title">
            <h2><?php the_title(); ?></h2>
        </div>

        <div class="postmeta">
            <p>By <a href="#"><?php the_author_meta( 'display_name' ); ?></a>  <?php the_time( get_option( 'date_format' ) ) ?></p>
        </div>

        <div class="post">
            <div class="post-content">
            <?php the_content(); ?>
            </div>
            <?php wp_link_pages( 'before=<p>&after=</p>&next_or_number=number&pagelink=Page %' ); ?>
        </div>

        <div id="posttags">
            <p><?php the_tags( 'Tags: ', ', ' ); ?></p>
        </div>

        <?php endwhile; ?>
        <?php endif;?>
    </div>

<?php get_footer(); ?>
