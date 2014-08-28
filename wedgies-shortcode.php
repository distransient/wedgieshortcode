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

class Wedgie_Shortcode_Plugin {

	const SHORTCODE_SLUG = 'wedgie';

	/**
	 * @var Wedgie_Shortcode_Plugin
	 */
	static protected $instance;

	/**
	 * @return Wedgie_Shortcode_Plugin
	 */
	static public function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Add hooks
	 */
	protected function __construct() {
		add_shortcode( self::SHORTCODE_SLUG, array( $this, 'shortcode_handler' ) );
		wp_embed_register_handler( 'wedgie', '#http(s?)://(www\.)?wedgies\.com/question/(.*)#i', array( $this, 'wp_embed_handler' ), 1 );
		add_action( 'the_posts', array( $this, 'conditionally_enqueue_script' ) );

	}

	/**
	 * wp_enqueue_scripts hook.
	 */
	public function enqueue_script() {
		wp_enqueue_script( 'wedgie_embed', 'https://www.wedgies.com/js/widgets.js', null, '1.2' );
	}

	/**
	 * Construct a wedgie embed from an ID
	 *
	 * @param $id
	 * @return string
	 */
	function construct_embed( $id ) {
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
	function shortcode_handler( $attrs ) {
		$attrs = shortcode_atts(
			array(
				'id' => '52dc9862da36f6020000000c',
			),
			$attrs,
			self::SHORTCODE_SLUG
		);
		$wedgie_output = $this->construct_embed( $attrs['id'] );
		return $wedgie_output;
	}

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
	function wp_embed_handler( $matches, $attr, $url, $rawattr ) {
		$embed = $this->construct_embed( $matches[3] );
		return apply_filters( 'embed_wedgie', $embed, $matches, $attr, $url, $rawattr );
	}


	/**
	 * Hook handler for the_posts to enqueue the JS if a wedgie is found.
	 *
	 * @param array $posts
	 *
	 * @return mixed
	 */
	function conditionally_enqueue_script( $posts ) {
		if ( empty( $posts ) ) {
			return $posts;
		}

		$shortcode_found = false;

		foreach ( $posts as $post ) {
			if ( ! ( false === stripos( $post->post_content, '[' . self::SHORTCODE_SLUG ) ) || preg_match( '#http(s?)://(www\.)?wedgies\.com/question/(.*)#i', $post->post_content ) ) {
				$shortcode_found = true;
				break;
			}
		}

		if ( $shortcode_found ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ) );
		}

		return $posts;
	}

}

Wedgie_Shortcode_Plugin::instance();
