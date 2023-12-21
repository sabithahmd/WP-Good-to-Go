<?php
/*
Plugin Name: WP Good to Go
Plugin URI: https://github.com/MuhammadUsman0304/wp-security.git
Description: Plugin to enhance WP security 
Version: 1.0.0
Author: Muhammad Usman
Tags: wp security, security, enable/disable xmlrpc, xmlrpc, comments, turn off comments, 
Author URI: https://www.linkedin.com/in/muhammad-usman-b3439218b/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


namespace WP_Good_To_Go;

use WP_Good_To_Go\Tools\WPConfigTransformer; 

use WP_Good_To_Go\Scanner\WP_GTG_Scanner;

defined('ABSPATH') || die('you can\'t call me directly');
define('WPGTG_DIR_URL', plugin_dir_url(__FILE__).'/');
define('WPGTG_DIR_PATH', __DIR__.'/');
define('WPGTG_VER','1.0.0');

// add menu in dashboard

class WP_Good_To_Go {

   function __construct() {
      $this->add_actions();
   }

   private function add_actions() {
      add_action('admin_menu',[$this, 'wpgtg_admin_menu']);
      add_action("wp_ajax_scan_action" ,[$this, 'scan_action']);
      add_action("wp_ajax_fix_issue" ,[$this, 'fix_issue']);
      add_action("admin_enqueue_scripts" ,[$this, 'wpgtc_enqueue_scripts']);
   }

   function wpgtc_enqueue_scripts() {
      wp_enqueue_style('style',WPGTG_DIR_URL.'assets/style.css');
      wp_enqueue_style('wpgtg_script',WPGTG_DIR_URL.'assets/scan.js');
      wp_enqueue_script( 'wpgtg_script', WPGTG_DIR_URL.'assets/scan.js', array('jquery'), null, true );
   }

   public function wpgtg_admin_menu() {
      add_menu_page('WP Good To Go','WP Good To Go', 'manage_options', 'wpgtg', [$this, 'wpgtg_dashboard']);
   }

   function scan_action() {
	   $scanner  = new WP_GTG_Scanner();
      $scanner->start_scan();
      wp_die();
   }

   function fix_issue() {
      $scanner  = new WP_GTG_Scanner();
      $scanner->fix_scan();
      wp_die();
   }

   public function wpgtg_dashboard() {

      echo '
         <div class="wrap">
            <form action="" method="post">
               <input type="submit" name="wpgtg_scan" class="button button-primary" id="scan_btn" value="SCAN">
            </form>
            <div class="js-scan_result_wrapper">

            </div>
         </div> 

      ';
 
   }

}

function wpgtg_autoloader($class) {
   if (strpos($class, __NAMESPACE__) === 0) {
       $class = str_replace(__NAMESPACE__ . '\\', '', $class);
       $class_file = str_replace('\\', DIRECTORY_SEPARATOR, $class);
       $class_file = strtolower($class_file);
       $class_file = str_replace('_', '-', $class_file);
       require_once WPGTG_DIR_PATH . 'includes/' . $class_file . '.php';
   }
}


spl_autoload_register(__NAMESPACE__ . '\wpgtg_autoloader');

$wp_good_to_go = new WP_Good_To_Go();