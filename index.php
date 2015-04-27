<?php
/**
 * Open-Realty
 *
 * Open-Realty is free software; you can redistribute it and/or modify
 * it under the terms of the Open-Realty License as published by
 * Transparent Technologies; either version 1 of the License, or
 * (at your option) any later version.
 *
 * Open-Realty is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * Open-Realty License for more details.
 * http://www.open-realty.org/license_info.html
 *
 * You should have received a copy of the Open-Realty License
 * along with Open-Realty; if not, write to Transparent Technologies
 * RR1 Box 162C, Kingsley, PA  18826  USA
 *
 * @author Ryan C. Bonham <ryan@transparent-tech.com>
 * @copyright Transparent Technologies 2004-2008
 * @link http://www.open-realty.org Open-Realty Project
 * @link http://www.transparent-tech.com Transparent Technologies
 * @link http://www.open-realty.org/license_info.html Open-Realty License
 */
// Set Error Handling to E_ALL
// error_reporting(E_ALL);
// This Fixes XHTML Validation issues, with PHP
@ini_set('arg_separator.output', '&amp;');
@ini_set('pcre.backtrack_limit', '10000000');
@ini_set('url_rewriter.tags', 'a=href,area=href,frame=src,input=src');
@ini_set('precision',14);
// Use Compression
// @ini_set('zlib.output_compression', 'On');
if (session_id()=='') session_start();
header("Cache-control: private"); //IE6 Form Refresh Fix
// Make sure install file has been removed
$filename = dirname(__FILE__) . '/install/index.php';
if (file_exists($filename)) {
	//die ('<html><div style="color:red;text-align:center">You must delete the file ' . $filename . ' before you can access your open-realty install.</div></html>');
}
// Check for User Selected Language
if (isset($_POST['select_users_lang'])) {
	session_register('users_lang');
	$_SESSION['users_lang'] = $_POST['select_users_lang'];
}
// Check for User Selected Template
if (isset($_POST['select_users_template'])) {
	session_register('template');
	$_SESSION['template'] = $_POST['select_users_template'];
}
// Register $config as a global variable
global $config, $conn, $css_file;
$css_file = '';
require_once(dirname(__FILE__) . '/include/common.php');
// Check that the defualt email address has been changed to something other then an open-realty.org address.
$pos = strpos($config['admin_email'], 'open-realty.org');
$pos2 = strpos($config['admin_email'], 'changeme@default.com');
if ($pos !== false || $pos2 !== false) {
	die ('<html><div style="color:red;text-align:center">You must set an administrative email address in the site configuration before you can use your site. </div></html>');
}
// Add GetMicroTime Function
require_once($config['basepath'] . '/include/misc.inc.php');
$misc = new misc();
$start_time = $misc->getmicrotime();
// Start OutPut Buffer
ob_start();
if (!isset($_GET['printer_friendly'])) {
	$_GET['printer_friendly'] = false;
}
// Determine which Language File to Use
if (isset($_SESSION["users_lang"]) && $_SESSION["users_lang"] != $config['lang']) {
	include($config['basepath'] . '/include/language/' . $_SESSION['users_lang'] . '/lang.inc.php');
}else {
	// Use Sites Defualt Language
	unset($_SESSION["users_lang"]);
	include($config['basepath'] . '/include/language/' . $config['lang'] . '/lang.inc.php');
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
	require_once($config['basepath'] . '/include/login.inc.php');
	$login = new login();
	$login->log_out('user');
}elseif (!isset($_GET['action'])) {
	$_GET['action'] = 'index';
}
if(strpos($_GET['action'],'://')!==false){
	$_GET['action'] = 'index';
}
require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$page = new page_user;
if(strpos($_GET['action'],'rss_') !== 0){
	if (isset($_GET['popup']) && $_GET['popup'] != 'blank') {
		$page->load_page($config['template_path'] . '/popup.html');
		} elseif (isset($_GET['popup']) && $_GET['popup'] == 'blank') {
		$page->load_page($config['template_path'] . '/blank.html');
		} elseif (isset($_GET['printer_friendly']) && $_GET['printer_friendly'] == 'yes') {
		$page->load_page($config['template_path'] . '/printer_friendly.html');
		} else {
		if (isset($_GET['PageID']) && file_exists($config['template_path'] . '/page' . $_GET['PageID'] . '_main.html')) {
			$page->load_page($config['template_path'] . '/page' . $_GET['PageID'] . '_main.html');
		} elseif ($_GET['action'] == 'index' && file_exists($config['template_path'] . '/page1_main.html')) {
			$page->load_page($config['template_path'] . '/page1_main.html');
		} elseif ($_GET['action'] == 'searchresults' && file_exists($config['template_path'] . '/searchresults_main.html')) {
			$page->load_page($config['template_path'] . '/searchresults_main.html');
		} else {
			$page->load_page($config['template_path'] . '/main.html');
		}
	}
}else{
	//echo 'RSS';
	$page->page='{content}';
}
// Are we in maintenance mode?
if($config["maintenance_mode"] == 1 && $_SESSION['username'] !== 'admin') {
	$page->load_page($config['template_path'] . '/maintenance_mode.html');
}
// Allow Addons/Functions to pass back custom jscript.
global $jscript,$jscript_last;
$jscript = '';
$jscript_last = '';
//Load Content
$page->replace_tags(array('content'));
//Replace Permission tags first
$page->replace_permission_tags();
$page->replace_urls();
$page->replace_meta_template_tags();
$page->auto_replace_tags();
// Load js last to make sure all custom js was added
$page->replace_tags(array('load_js','load_js_last'));
//Replace Languages
$page->replace_lang_template_tags();
$page->replace_css_template_tags();
$page->output_page();
$conn->Close();
// Close Buffer
$buffer = ob_get_contents();
ob_end_clean();
echo $buffer;
// Display TIme
$end_time = $misc->getmicrotime();
$render_time = sprintf('%.3f', $end_time - $start_time);
if (isset($_GET['popup']) && $_GET['popup'] == 'blank') {
} else {
echo '<!-- This page was generated in ' . $render_time . ' seconds -->';
}
?>