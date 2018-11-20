<?php
/*
Plugin Name: Current User Comments
Plugin URI: http://cms.ubc.ca
Description: Provides a shortcode [user_comments] which you can use to show recent comments by a particular user
Version: 1.1
Author: CTLT Dev
Author URI: http://cms.ubc.ca
License: GPL 2.0
Usage: (many)
*
*
* Adapted from the work of 'Ashfame'
* http://blog.ashfame.com/2011/01/show-recent-comments-particular-user-wordpress/
*
* You may need to use CSS for the output - as the HTML output area has a a <div> for
* better control of styling the comment
*
*
*/

add_shortcode ( 'user_comments', 'show_recent_comments_handler' );

function show_recent_comments_handler ( $atts, $content = null )
	{
		extract(
			shortcode_atts(
				array(
					"count" => 150,
				), $atts
			)
		);

		$output = ''; // this holds the output until it's referenced later

		if ( is_user_logged_in() )
		{
			global $current_user;
			get_currentuserinfo();

			$args = array(
				'user_id' => $current_user->ID,
				'number' => $count, // how many comments to retrieve
				'status' => 'approve'
			);

			$comments = get_comments( $args );

				foreach ( $comments as $c )
				{

					$output = $output . "<li>";
					$output = $output . "</br>";

					// comment author
					$output = $output . "<b>";
					$output = $output . $c->comment_author;
					$output = $output . "</b>";

					$output = $output . " on ";

					// comment date
					$output = $output . "<i>";
					$output = $output . get_comment_date ( 'F-Y', $c);
					$output = $output . "</i>";

					$output = $output . " wrote:";

					// comment content
					$output = $output . '<div class="comment_content">';
					$output = $output . '"';
					$output = $output . substr($c->comment_content ,0 ,350);
					$output = $output . ' ..." ';

					// link to comment
					$output = $output . '<a href="' . get_settings('siteurl') . '/?p=' . $c->comment_post_ID . '#comment-' . $c->comment_ID . '">';
					$output = $output . "[link to post]";
					$output = $output . "</a>";
					$output = $output . "</div>";

					// comment content style
					$output = $output . "<style>
					.comment_content { margin-top: 5px; }
					</style>";

					$output = $output . "</li>";
					$output = $output . "</p>";

				}

		}

		else
		{
			$output = $output . "Please login to see your comments.";
		}

	return $output;

}