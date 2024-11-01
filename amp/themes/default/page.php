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
			<p>By <a href="#"><?php the_author_meta( 'display_name' ); ?></a> - <?php the_time( get_option( 'date_format' ) ) ?></p>
		</div>

		<div class="post">
			<?php if ( has_post_thumbnail() ) : $thumbnail_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' ); ?>
			<a href="<?php the_permalink(); ?>" class="thumbnail"> </a>
			<?php endif; ?>

			<?php the_content(); ?>
			<?php wp_link_pages( 'before=<p>&after=</p>&next_or_number=number&pagelink=Page %' ); ?>
		</div>

		<div class="remaining-ad-content">
			<!-- AD CODE -->
			<?php
			// $adCodeOneRem = get_option('ampAdOneRem');
			// $adCodeTwoRem = get_option('ampAdTwoRem');
			// $adCodeThreeRem = get_option('ampAdThreeRem');
			// $adCodeFourRem = get_option('ampAdFourRem');
			// $adCodeFiveRem = get_option('ampAdFiveRem');
			// $adCodeSixRem = get_option('ampAdSixRem');

			// if(get_option('ampAdOneRem')) {
			// 	echo $adCodeOneRem;
			// }
			// if(get_option('ampAdTwoRem')) {
			// 	echo $adCodeTwoRem;
			// }
			// if(get_option('ampAdThreeRem')) {
			// 	echo $adCodeThreeRem;
			// }
			// if(get_option('ampAdFourRem')) {
			// 	echo $adCodeFourRem;
			// }
			// if(get_option('ampAdFiveRem')) {
			// 	echo $adCodeFiveRem;
			// }
			// if(get_option('ampAdSixRem')) {
			// 	echo $adCodeSixRem;
			// }

			?>
		</div>

		<?php endwhile; ?>
		<?php endif;?>

	</div>

<?php get_footer(); ?>
