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
 * @copyright Transparent Technologies 2004
 * @link http://www.open-realty.org Open-Realty Project
 * @link http://www.transparent-tech.com Transparent Technologies
 * @link http://www.open-realty.org/license_info.html Open-Realty License
 */
// Set Error Handling to E_ALL
// error_reporting(E_ALL);
// This Fixes XHTML Validation issues, with PHP
@ini_set('arg_separator.output', '&amp;');
@ini_set('url_rewriter.tags', 'a=href,area=href,frame=src,input=src');
@ini_set('precision',14);
if (session_id()=='') session_start();
header("Cache-control: private"); //IE6 Form Refresh Fix
// Start OutPut Buffer
if (!isset($_GET['printer_friendly'])) {
	$_GET['printer_friendly'] = false;
}
// Check for User Selected Language
if (isset($_POST['select_users_lang'])) {
	session_register('users_lang');
	$_SESSION['users_lang'] = $_POST['select_users_lang'];
}
// Register $config as a global variable
global $config, $conn;
ob_start();
require_once(dirname(__FILE__) . '/../include/common.php');
// Determine which Language File to Use
if (isset($_SESSION["users_lang"]) && $_SESSION["users_lang"] != $config['lang']) {
	include($config['basepath'] . '/include/language/' . $_SESSION['users_lang'] . '/lang.inc.php');
}else {
	// Use Sites Defualt Language
	unset($_SESSION["users_lang"]);
	include($config['basepath'] . '/include/language/' . $config['lang'] . '/lang.inc.php');
}
require_once($config['basepath'] . '/include/login.inc.php');
$login = new login();

if (isset($_GET['action']) && $_GET['action'] == 'log_out') {
	$login->log_out();
}
if (!isset($_GET['action'])) {
	$_GET['action'] = 'index';
}
if(strpos($_GET['action'],'://')!==false){
	$_GET['action'] = 'index';
}

// Add GetMicroTime Function
require_once($config['basepath'] . '/include/misc.inc.php');
$misc = new misc();
$start_time = $misc->getmicrotime();
require_once($config['basepath'] . '/include/class/template/core.inc.php');
// NEW TEMPLATE SYSTEM
$page = new page_admin();
if (isset($_GET['popup']) && $_GET['popup'] != 'blank') {
	$page->load_page($config['admin_template_path'] . '/popup.html');
}else{
	$page->load_page($config['admin_template_path'] . '/main.html');
}
// Allow Addons/Functions to pass back custom jscript.
global $jscript,$jscript_last;
$jscript = '';
$jscript_last = '';
//Load Content
$page->auto_replace_tags('', true);
if($_GET['action']=='edit_page'){
	$page->replace_permission_tags();
	$page->replace_urls();
	$page->replace_css_template_tags(true);
	$page->replace_meta_template_tags();
	$page->replace_tags(array('load_js','load_js_body','load_js_last'));
	$page->replace_lang_template_tags(true);
	$page->replace_tags(array('content'));

}else{
	$page->replace_tags(array('content'));
	$page->replace_permission_tags();
	$page->replace_urls();
	$page->replace_css_template_tags(true);
	$page->replace_meta_template_tags();
	$page->replace_tags(array('load_js','load_js_body','load_js_last'));
	$page->replace_lang_template_tags(true);
}
$page->output_page();
$conn->Close();
// Close Buffer
$buffer = ob_get_contents();
ob_end_clean();
echo $buffer;
// NEW TEMPLATE SYSTEM END
$end_time = $misc->getmicrotime();
$render_time = sprintf('%.16f', $end_time - $start_time);
echo "<!-- This page was generated in $render_time seconds -->";
?>