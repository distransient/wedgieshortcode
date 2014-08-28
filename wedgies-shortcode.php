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

/**
 * Enqueue wedgie script
 */
function enqueue_wedgie() {
	wp_enqueue_script( 'wedgie_embed', 'https://www.wedgies.com/js/widgets.js', null, '1.2' );
}


/**
 * Construct a wedgie embed from an ID
 *
 * @param $id
 * @return string
 */
function wedgie_construct_embed( $id ) {
	$embed = sprintf(
		'<noscript><a href="%s">%s</a></noscript><div class="wedgie-widget" wd-pending wd-type="embed" wd-version="v1" id="%s" style="max-width: 720px;"></div>',
		esc_url( 'https://www.wedgies.com/question/' . $id ),
		esc_html__( 'Vote on our poll!', 'wedgies-shortcode' ),
		esc_attr( $id )
	);
	return $embed;
}

/**
 * Shortcode handler for [wedgie]
 *
 * @param array $attrs
 *
 * @return string
 */
function wedgie_handler( $attrs ) {
	$attrs = shortcode_atts( array(
		'id' => '52dc9862da36f6020000000c',
	), $attrs, 'wedgie' );
	$wedgie_output = wedgie_construct_embed( $attrs['id'] );
	return $wedgie_output;
}
add_shortcode( 'wedgie', 'wedgie_handler' );



/**
 * Embed handler for Wedgie
 *
 * @param array $matches
 * @param array $attr
 * @param string $url
 * @param string $rawattr
 *
 * @return mixed|void
 */
function wp_embed_handler_wedgie( $matches, $attr, $url, $rawattr ) {
	$embed = wedgie_construct_embed( $matches[3] );
	return apply_filters( 'embed_wedgie', $embed, $matches, $attr, $url, $rawattr );
}
wp_embed_register_handler( 'wedgie', '#http(s?)://(www\.)?wedgies\.com/question/(.*)#i', 'wp_embed_handler_wedgie', 1 );


/**
 * Hook handler for the_posts to enqueue the JS if a wedgie is found.
 *
 * @param array $posts
 *
 * @return mixed
 */
function has_wedgie( $posts ) {
	if ( empty( $posts ) ) {
		return $posts;
	}

	$shortcode_found = false;

	foreach ( $posts as $post ) {
		if ( ! ( false === stripos( $post->post_content, '[wedgie' ) ) || preg_match( '#http(s?)://(www\.)?wedgies\.com/question/(.*)#i', $post->post_content ) ) {
			$shortcode_found = true;
			break;
		}
	}

	if ( $shortcode_found ) {
		add_action( 'wp_enqueue_scripts', 'enqueue_wedgie' );
	}

	return $posts;
}

add_action( 'the_posts', 'has_wedgie' );
