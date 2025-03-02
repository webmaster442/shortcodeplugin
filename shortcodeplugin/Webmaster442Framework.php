<?php
/*
Plugin Name: Webmaster442 Framework
Description: Webmaster442's Wordpress extension Framework
Version: 1.2
Author: Ruzsinszki Gábor
Author URI: https://webmaster442.hu
License: GPL2
*/
require_once('ShortCodes.php');
require_once('ImageResize.php');

//globális cuccok
define('FRAMEWORKVERSION', '1.3');

function DLog($message) {
  global $LOGENABLED;
  
  if($LOGENABLED) {
      error_log(print_r($message, true));
  }
}

if(!get_option('w442fw_version') || (get_option('w442fw_version')  != FRAMEWORKVERSION)) {
    add_option('w442fw_version', FRAMEWORKVERSION, '','yes');
    add_option('w442fw_debug_yesno', 'no', '','yes');
    add_option('w442fw_usernameend_yesno', 'no', '', 'yes');
    add_option('w442fw_resizeupload_width', '1200', '', 'yes');
    add_option('w442fw_resizeupload_height', '1200', '', 'yes');
    add_option('w442fw_resizeupload_quality', '90', '', 'yes');
    add_option('w442fw_resizeupload_resize_yesno', 'yes', '','yes');
    add_option('w442fw_resizeupload_recompress_yesno', 'no', '','yes');
    add_option('w442fw_resizeupload_convertpng_yesno', 'no', '', 'yes');
    add_option('w442fw_resizeupload_convertgif_yesno', 'no', '', 'yes');
    add_option('w442fw_facebookshare_endpost_yesno', 'no', '', 'yes');
    add_option('w442fw_copyprotect_yesno', 'no', '', 'yes');
}

$LOGENABLED = (get_option('w442fw_debug_yesno')=='yes') ? true : false;

class Webmaster442Framework {
    
    public function __construct() {
        //admin menü
        add_action('admin_menu', array($this, 'RegisterAdminMenu'));
    }
    
    private function GetContent($filename) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    
    public function RegisterAdminMenu() {
        add_menu_page('Webmaster442 Framework', 'Webmaster442 Framework', 'manage_options', 'w442fw', array($this, 'PageWelcome'));
        add_submenu_page( 'w442fw', 'ShortCode lista', 'ShortCode lista', 'manage_options', 'w442shcodes', array($this, 'PageShortCodeList'));
        add_submenu_page( 'w442fw', 'Beállítások', 'Beállítások', 'manage_options', 'w442imgresize', array($this, 'PageSetings'));
    }
    
    public function PageWelcome() {
        ob_start();
        echo('<h1>Webmaster442 Framework '.FRAMEWORKVERSION. '</h1>');
        echo('<hr/>');
        $content = $this->GetContent('README.md');
        echo "<pre>";
        echo $content;
        echo "</pre>";
        echo('<hr/><h1>Válassz az almenükből a beállítások módosításához!</h1>');
        ob_end_flush();
    }
    
    public function PageShortCodeList() {
        echo "<pre>";
        $content = $this->GetContent('ShortCodes.txt');
        echo $content;
        echo "</pre>";
    }
    
    public function PageSetings() {
        include('Settings.php');
    }
}
$framework = new Webmaster442Framework();
$sh = new ShortCodes();
$resize = new ImageResize();
?>