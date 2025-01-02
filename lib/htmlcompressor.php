<?php
/*
 * Github Profile Widget for WordPress
 *
 *     Copyright (C) 2015 Henrique Dias <hacdias@gmail.com>
 *     Copyright (C) 2015 Luís Soares <lsoares@gmail.com>
 *     Copyright (C) 20204 DJABHipHop <djabhiphop-DJABHipHop@yahoo.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( "refactors_HTMLCompressor" ) ) {
	function refactors_HTMLCompressor( $buffer ) {
		$buffer = preg_replace( '/<!--([^\[|(<!)].*)/', '', $buffer );
		$buffer = preg_replace( '/(?<!\S)\/\/\s*[^\r\n]*/', '', $buffer );

		$search = array(
			'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
			'/[^\S ]+\</s',  // strip whitespaces before tags, except space
			'/(\s)+/s'       // shorten multiple whitespace sequences
		);

		$replace = array( '>', '<', '\\1' );

		$buffer = preg_replace( $search, $replace, $buffer );

		return $buffer;
	}
}
