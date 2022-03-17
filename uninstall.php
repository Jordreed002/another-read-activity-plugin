<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

//clear options from database
$optionsArray = array('another_read_settings', 'another_read_settings_timestamp');

foreach($optionsArray as $option){
	delete_option($option);
}

//clear posts from database
$activities = get_posts(array('post_type' => 'activity', 'numberposts' => -1));

foreach($activities as $activity){
	wp_delete_post($activity->ID, true);
}

//unregister CPT

unregister_post_type('activity')


?>
