<?php

// if this file is called directly, exit
if (!defined('ABSPATH') || !defined('WPINC')) { 
    exit; 
}

// get home url of post
$home_url = home_url();

// set question marks are 
$q_mark = '?';

// get current query params from home url
$query_params = parse_url($home_url, PHP_URL_QUERY);

// check if query params exist
if ($query_params) {

    // change question mark to ampersand
    $q_mark = '&';

}

// set canonical url
$redirect_url = $home_url . $q_mark . 'r';

// redirect to url
wp_redirect($redirect_url); 

// end process
exit;

// // redirect to home url
// wp_redirect(home_url()); 

// // end process
// exit;

?>