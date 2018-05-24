<?php
/*
Plugin Name: {{Plugin Name}}
Plugin URI:
Description:
Version:     0.0.2.20180330
Author:
Author URI:  https://developer.wordpress.org/
Text Domain: {{plugin-slug}}-plugin
Domain Path: /languages
License:     GPL2

{{Plugin Name}} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

{{Plugin Name}} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with {{Plugin Name}}. If not, see {License URI}.
*/



/**
 * Find and Replace
 *
 *  {{Plugin Name}} => 'Name Of Plugin'
 *  {{plugin-slug}} => 'name-of-plugin'
 *  {{PLUGIN_ABBR}} => 'NOP'
 *  {{plugin-abbr}} => 'nop'
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Access denied.' );
}

define( '{{PLUGIN_ABBR}}_NAME',                 '{{Plugin Name}}' );
define( '{{PLUGIN_ABBR}}_REQUIRED_PHP_VERSION', '5.3' );               // because of get_called_class()
define( '{{PLUGIN_ABBR}}_REQUIRED_WP_VERSION',  '3.1' );

require_once('functions.php');

spl_autoload_register('{{plugin-abbr}}_autoloaders');

register_activation_hook( __FILE__, '{{plugin-abbr}}_install' );
register_deactivation_hook( __FILE__, '{{plugin-abbr}}_deactivation' );


global ${{plugin-abbr}}_nearby_places;
${{plugin-abbr}}_nearby_places = new {{PLUGIN_ABBR}}_Core();

function {{plugin-abbr}}_debug( $atts ){
	ob_start();
	?>
<h4>Shortcode output</h4>
	<?php
	do_action('{{plugin-abbr}}_debug');

	$output = ob_get_clean();
	return $output;
}
add_shortcode( '{{plugin-abbr}}_debug', '{{plugin-abbr}}_debug' );
