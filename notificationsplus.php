<?php
/*
Plugin Name: Notifications+
Plugin URI: https://github.com/riyadhalnur/notificationsplus
Description: A realtime notification plugin for WordPress forked from the Wp Heartbeat Notify project.
Version: 1.0
Author: Riyadh Al Nur
Author URI: http://verticalaxisbd.com
License: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

require_once( 'core/class-nplus-plugin.php' );
require_once( 'core/class-nplus.php' );

global $nplus_plugin;

// Create the plugin and store it as a global
$nplus_plugin = new NPlus_Plugin( 
	__FILE__, 
	array(
		'required'	=>	array( 
			'wordpress'	=>	'3.8' // WordPress 3.8 is required
		)
	) 
);

// Start notifications
new notifications_plus( array(
	'context'	=>	array( 'admin', 'front' ),	// This plugin is supposed to work only on the front end
	'base_url'	=>	$nplus_plugin->uri		// Set js and css base url
) );

// Hook for posts
add_filter ( 'publish_post', 'notify_published_post' );
function notify_published_post( $post_id ) {
	
	global $nplus_plugin;
	
	notifications_plus::notify( array(
		'title'		=>		__( 'New Article', $nplus_plugin->textdomain ),
		'content'	=>	 	__( 'There\'s a new post, why don\'t you give a look at', $nplus_plugin->textdomain ) .
							' <a href="' . get_permalink( $post_id ) . '">' . get_the_title( $post_id ) . '</a>',
		'type'		=>		'update'
	) );
	
	return $post_id;	
}

// Hook for comments
add_filter ( 'comment_post', 'notify_published_comment' );
function notify_published_comment( $comment_id ) {
	
	$comment = get_comment( $comment_id );

	if( ! $comment->user_id > 0 ) {
		return;
	}

	$comment_link = get_comment_link( $comment_id );
	
	notifications_plus::notify( array(
		'title'		=>		'New Comment by ' . $comment->comment_author,
		'content'   =>      'There\'s a new comment, why don\'t you <a href="' . $comment_link . '">give it</a> a look?',
		'type'		=>		'info'
	) );	
}
