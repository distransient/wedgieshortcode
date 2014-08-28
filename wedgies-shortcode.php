<?php
/**
 * @package Wedgies_Shortcode
 * @version 1.2
 */
/*
Plugin Name: Social Polls by Wedgies.com
Plugin URI: http://wedgies.com
Description: Wedgies are polls you can embed on your WordPress page. Engage your audience by asking them a question via Wedgies.
Version: 1.2
Author: Brendan Nee, James Barcellano
Author URI: http://bn.ee
License: GPL3
*/
/*
Wedgies (WordPress Plugin)
Copyright (C) 2013 Wedgies
Contact me at http://wedgies.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

function enqueue_wedgie() {
	wp_enqueue_script('wedgie_embed', 'https://www.wedgies.com/js/widgets.js', null, '1.00');
}

add_shortcode("wedgie", "wedgie_handler");

function wedgie_handler($attrs) {

	$attrs = shortcode_atts(array(
		"id" => "52dc9862da36f6020000000c"
	), $attrs);
	
	$wedgie_output = '<noscript><a href="https://www.wedgies.com/question/' . $attrs['id'] . '">Vote on our poll!</a></noscript><div class="wedgie-widget" wd-pending wd-type="embed" wd-version="v1" id="' . $attrs['id'] . '" style="max-width: 720px;"></div>';
	
	return $wedgie_output;
}

wp_embed_register_handler( 'wedgie', '#http(s?)://(www\.)?wedgies\.com/question/(.*)#i', 'wp_embed_handler_wedgie', 1);

function wp_embed_handler_wedgie( $matches, $attr, $url, $rawattr ) {
	
	$embed = sprintf(
				'<noscript><a href="https://www.wedgies.com/question/%1$s">Vote on our poll!</a></noscript><div class="wedgie-widget" wd-pending wd-type="embed" wd-version="v1" id="%1$s" style="max-width: 720px;"></div>', $matches[3]);

	return apply_filters( 'embed_wedgie', $embed, $matches, $attr, $url, $rawattr );
}

function has_wedgie($posts) {
	if (empty($posts)) {
		return $posts;
	}
	
	$shortcode_found = false;
	
	foreach ($posts as $post) {
		if ( !(stripos($post->post_content, '[wedgie') === false) || preg_match('#http(s?)://(www\.)?wedgies\.com/question/(.*)#i', $post->post_content)) {
			$shortcode_found = true;
			break;
		}
	}
	
	if ($shortcode_found) {
		add_action('wp_enqueue_scripts', 'enqueue_wedgie');
	}
	
	return $posts;
}

add_action('the_posts', 'has_wedgie');
