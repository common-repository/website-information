<?php
/*
Plugin Name: Website Information
Description: It gives some the information about the website
Author: Jose Mortellaro
Author URI: https://josemortellaro.com/
Text Domain: eos-wi
Domain Path: /languages/
Version: 0.0.6
*/
/*  This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if( is_admin() ){
	define( 'EOS_WI_PLUGIN_DIR',untrailingslashit( dirname( __FILE__ ) ) );
	require EOS_WI_PLUGIN_DIR.'/class.admin.system.report.php';
	add_filter( 'plugin_action_links_'.untrailingslashit( plugin_basename( __FILE__ ) ), 'eos_wi_plugin_add_settings_link' );
	add_action( 'admin_menu', 'eos_wi_menu_pages',90 );
	add_action( 'admin_enqueue_scripts','eos_wi_enqueue_scripts' );
}
//Filter function to read plugin translation files
function eos_wi_load_translation_file( $mofile, $domain ) {
	if ( 'wi' === $domain ) {
		$loc = function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$mofile = EOS_WI_PLUGIN_DIR . '/languages/wi-' . $loc . '.mo';
	}
	return $mofile;
}
function eos_wi_menu_pages(){
	add_menu_page( esc_html__( 'Website Information','wi' ),esc_html__( 'Website Information','wi' ), 'manage_options', 'eos-wi', 'eos_wi_support_page_callback','dashicons-info-outline',60 );
}
//Generate the Status page
function eos_wi_support_page_callback(){
	Eos_wi_Admin_System_Report::output();
}
//Enqueue the needed admin script
function eos_wi_enqueue_scripts(){
	wp_enqueue_script( 'eos-wi',untrailingslashit( plugins_url( '', __FILE__ ) ).'/assets/js/wi.js',array( 'jquery' ) );
}
//It adds a link to the action links in the plugins page
function eos_wi_plugin_add_settings_link( $links ){
    $settings_link = '<a class="eos-wi-setts" href="'.admin_url( 'admin.php?page=eos-wi' ).'">'. esc_html__( 'Get Info for help','eos-wi' ). '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
