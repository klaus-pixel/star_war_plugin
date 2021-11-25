<?php
/**
 * Plugin Name: Star Wars Plugin
 */

 /** Security */
 if(!defined('ABSPATH')){
     exit;
 }

 // Loads Class
 require_once(plugin_dir_path(__FILE__).'./includes/star-wars-class.php');

 // Register Widget

 function register_starwars(){
     register_widget('Star_Wars_Widget');
 }


 // Hook in function
 add_action('widgets_init', 'register_starwars');



