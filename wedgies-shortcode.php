<?php
/*
Plugin Name: Wedgies
Plugin URI: http://wedgies.com
Description: Support for a <a href="http://wedgies.com">Wedgies</a> Wordpress shortcode.  Use [wedgie id="5164b8cc9688ec020000000a"] to embed any wedgie into post content.
Version: 0.1
Author: Brendan Nee
Author URI: http://bn.ee
*/

/*
Wedgies (Wordpress Plugin)
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

add_shortcode("wedgie", "wedgie_handler");

function wedgie_handler($attrs) {
  $attrs = shortcode_atts(array(
    "id" => "5164b8cc9688ec020000000a"
  ), $attrs);
  $wedgie_output = '<script src="http://www.wedgies.com/js/public_embed/v1/embed.min.js"></script><div class="wedgies-embed" style="height: 400px; width: 400px;" data-wedgie-id="'  . $attrs['id'] . '"></div>';
  return $wedgie_output;
}
?>