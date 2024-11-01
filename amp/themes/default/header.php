<?php 

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
	exit; 
}

?>

<!doctype html>
<html amp>
<head>

	<title>
		<?php 
			global $query_string;

			if ( is_home() )
				bloginfo( 'name' );

			if ( get_search_query() )
				echo 'Results for: "' . get_search_query() .'"';

			if ( is_month() )
				the_time('F Y');

			if ( is_category() )
				single_cat_title();

			if ( is_single() )
				the_title();

			if ( is_page() )
				the_title();

			if ( is_tag() )
				single_tag_title();

			if ( is_404() )
				echo 'Page Not Found!';
		?>
	</title>

	<?php

        // // get permalink of post
        // $permalink = get_permalink($post->ID);

        // // set question marks are 
        // $q_mark = '?';

        // // get current query params from permalink
        // $query_params = parse_url($permalink, PHP_URL_QUERY);

        // // check if query params exist
        // if ($query_params) {

        //     // change question mark to ampersand
        //     $q_mark = '&';

        // }

        // // set canonical url
        // $canonical_url = $permalink . $q_mark . 'amp=0';

	?>

	<link rel="canonical" href="<?php echo the_permalink(); ?>">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1">

	<?php

	/* 
	* Added the custom style through the custom Hook called "sovrn_amp_custom_style" and not used wp_enqueue, because of the strict rules of AMP.
	* 
	*/
	do_action('sovrn_amp_custom_elements');
	do_action('sovrn_amp_custom_style');

	// hide admin bar
	show_admin_bar(false);

	?>

</head>

<body id="<?php if ( !is_single() && !is_page() ) { ?>home<?php } ?>">

<nav class="title-bar">
	<div>
		<a href="<?php bloginfo('url'); ?>">

			<?php
				$sovrn_options = get_option( 'sovrn_option_name' );
				$client_logo = $sovrn_options['client_logo'];

				if ($client_logo) {
					// set default logo url (production)
					$logo_url = $client_logo;

					// check if in localhost
					if (strpos(site_url(), 'localhost') !== false) {
						$logo_url = get_site_url() . $client_logo;
					}

					// set amp utils instance to this class
					$amp_utils = new Sovrn_Workbench_AMP_Utils();

					// get amp cache url
					$logo_url = $amp_utils->get_amp_cache_url($logo_url, 'image');

					list($widthO, $heightO, $type, $attr) = getimagesize(esc_url($logo_url));

					$target = 95;
				}

				function sovrn_image_resize($width, $height, $target) {

					//takes the larger size of the width and height and applies the
					//formula accordingly...this is so this script will work
					//dynamically with any size image

					if ($width > $height) {
						if($width == 0) {
							$percentage = ($target / 1);
						} else {
							$percentage = ($target / $width);
						}

					} else {
						if($height == 0) {
							$percentage = ($target / 1);
						} else {
							$percentage = ($target / $height);
						}
					}

					//gets the new value and applies the percentage, then rounds the value
					$widthR = round($width * $percentage);
					$heightR = round($height * $percentage);

					//returns the new sizes in html image tag format...this is so you
					//can plug this function inside an image tag and just get the

					return array($widthR, $heightR);

				};
			?>

			<?php if ($client_logo && false) : ?>
				<?php list($widthN, $heightN) = sovrn_image_resize($widthO, $heightO, $target); ?>
				<amp-img src="<?php echo esc_url( $client_logo ); ?>" width="<?php echo $widthN; ?>" height="<?php echo $heightN; ?>" class="site-icon"></amp-img>
			<?php else: ?>
				<svg x="0px" y="0px" width="0" height="0" viewBox="0 0 0 0"></svg>
			<?php endif; ?>
			<div class="site-name"><?php echo esc_html( bloginfo('name') ); ?></div>
		</a>
	</div>
</nav>

<?php if ( is_single() ) {
	if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it. ?>
	<?php }
} ?>
<main role="main">
