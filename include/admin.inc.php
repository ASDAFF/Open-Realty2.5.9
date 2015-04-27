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
 * @copyright Transparent Technologies 2004, 2005
 * @link http://www.open-realty.org Open-Realty Project
 * @link http://www.transparent-tech.com Transparent Technologies
 * @link http://www.open-realty.org/license_info.html Open-Realty License
 */

/**
 * general_admin
 * This class contains the functions related to the administrative index page section.
 *
 * @author Ryan Bonham
 * @copyright Copyright (c) 2005
 */
class general_admin {
	/*
	 * Depreciated for 2.6.0
	 */
	function myAddSlashes($string)
	{
		if (get_magic_quotes_gpc() == 1) {
			return ($string);
		}else {
			$o = "";
			$l = strlen($s);
			for($i = 0;$i < $l;$i++) {
				$c = $s[$i];
				switch ($c) {
					case '<': $o .= '\\x3C';
						break;
					case '>': $o .= '\\x3E';
						break;
					case '\'': $o .= '\\\'';
						break;
					case '\\': $o .= '\\\\';
						break;
					case '"': $o .= '\\"';
						break;
					case "\n": $o .= '\\n';
						break;
					case "\r": $o .= '\\r';
						break;
					default:
						$o .= $c;
				}
			}
			return $o;
		}
	}
	/**
	 * general_admin::index_page()
	 * This functions renders the admin index page.
	 *
	 * @return
	 */
	function load_js_body()
	{
		global $config, $lang,$loadjs;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$display .= '<script type="text/javascript">' . "\r\n";
		$display .= '<!-- Nannette Thacker http://www.shiningstar.net //-->' . "\r\n";
		$display .= '<!--' . "\r\n";
		$display .= 'function confirmDelete(msg)' . "\r\n";
		$display .= '{' . "\r\n";
		$display .= 'if (!msg) {msg="'.$lang['are_you_sure_you_want_to_delete'].'"}' . "\r\n";
		$display .= 'var agree=confirm(msg);' . "\r\n";
		$display .= 'if (agree)' . "\r\n";
		$display .= 'return true ;' . "\r\n";
		$display .= 'else' . "\r\n";
		$display .= 'return false ;' . "\r\n";
		$display .= '}' . "\r\n";
		$display .= 'function confirmUserDelete()' . "\r\n";
		$display .= '{' . "\r\n";
		$display .= 'var agree=confirm("' . $lang['delete_user'] . '");' . "\r\n";
		$display .= 'if (agree)' . "\r\n";
		$display .= 'return true ;' . "\r\n";
		$display .= 'else' . "\r\n";
		$display .= 'return false ;' . "\r\n";
		$display .= '}' . "\r\n";
		$display .= 'function IsNumeric(val){return(parseFloat(val,10)==(val*1));}' . "\r\n";
		$display .= '//-->' . "\r\n";
		$display .= '</script>' . "\r\n";

		$display .= '<script type="text/javascript">' . "\r\n";
		$display .= '<!--' . "\r\n";
		$display .= 'function ChooseState()' . "\r\n";
		$display .= '{' . "\r\n";
		$display .= '	if (document.getElementById("edit_isAgent").value == "no")' . "\r\n";
		$display .= '	{' . "\r\n";
		$display .= '		makeDisable();' . "\r\n";
		$display .= '	}' . "\r\n";
		$display .= '	else' . "\r\n";
		$display .= '	{' . "\r\n";
		$display .= '		makeEnable();' . "\r\n";
		$display .= '	}' . "\r\n";
		$display .= '}' . "\r\n";
		$display .= 'function makeDisable()' . "\r\n";
		$display .= '{	' . "\r\n";
			$display .= '	document.getElementById("edit_canViewLogs").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canViewLogs").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canModerate").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canModerate").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canFeatureListings").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canFeatureListings").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canPages").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canPages").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canVtour").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canVtour").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canFiles").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canFiles").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canUserFiles").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canUserFiles").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("limitListings").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("limitListings").value =-1;' . "\r\n";
			$display .= '	document.getElementById("edit_limitFeaturedListings").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_limitFeaturedListings").value =-1;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditListingExpiration").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditListingExpiration").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canEditAllListings").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditAllListings").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canEditAllUsers").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditAllUsers").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canEditSiteConfig").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditSiteConfig").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canEditMemberTemplate").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditMemberTemplate").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canEditAgentTemplate").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditAgentTemplate").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canEditListingTemplate").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditListingTemplate").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canEditPropertyClasses").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditPropertyClasses").selectedIndex = "no";' . "\r\n";
			$display .= '	document.getElementById("edit_canManageAddons").disabled=true;' . "\r\n";
			$display .= '	document.getElementById("edit_canManageAddons").selectedIndex = "no";' . "\r\n";
			if ($config["export_listings"] == 1) {
				$display .= '	document.getElementById("edit_canExportListings").disabled=true;' . "\r\n";
				$display .= '	document.getElementById("edit_canExportListings").selectedIndex = "no";' . "\r\n";
			}
		$display .= '}' . "\r\n";
		$display .= 'function makeEnable()' . "\r\n";
		$display .= '{' . "\r\n";
			$display .= '	document.getElementById("edit_canViewLogs").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canModerate").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canFeatureListings").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canPages").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canVtour").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canFiles").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canUserFiles").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("limitListings").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("limitListings").value =-1;' . "\r\n";
			$display .= '	document.getElementById("edit_limitFeaturedListings").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_limitFeaturedListings").value =-1;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditListingExpiration").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditAllListings").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditAllUsers").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditSiteConfig").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditMemberTemplate").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditAgentTemplate").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditListingTemplate").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canEditPropertyClasses").disabled=false;' . "\r\n";
			$display .= '	document.getElementById("edit_canManageAddons").disabled=false;' . "\r\n";
			if ($config["export_listings"] == 1) {
				$display .= '	document.getElementById("edit_canExportListings").disabled=false;' . "\r\n";
			}
		$display .= '}' . "\r\n";
		$display .= '//-->' . "\r\n";
		$display .= '</script>' . "\r\n";
		$display .= '<script type="text/javascript" src="' . $config['baseurl'] . '/dateformat.js"></script>' . "\r\n";
		$display .= '<script type="text/javascript" src="' . $config['baseurl'] . '/tabpane.js"></script>' . "\r\n";
		$display .= '<script type="text/javascript">' . "\r\n";
		$display .= '<!--' . "\r\n";
		$display .= 'function listing_change_confirm(s)' . "\r\n";
		$display .= '{' . "\r\n";
		$display .= 'var check=confirm("' . $lang['confirm_listing_change']  . ' ")' . "\r\n";
		$display .= 'if (check==true)' . "\r\n";
		$display .= '{' . "\r\n";
		$display .= 'var selvalue = s.options[s.selectedIndex].value;' . "\r\n";
		$display .= 'document.updateUser.edit_listing_active.value = selvalue;' . "\r\n";
		$display .= '}' . "\r\n";
		$display .= 'else' . "\r\n";
		$display .= '{' . "\r\n";
		$display .= 'document.updateUser.edit_listing_active.value = "";' . "\r\n";
  		$display .= '}' . "\r\n";
		$display .= '}' . "\r\n";
		$display .= '//-->' . "\r\n";
		$display .= '</script>' . "\r\n";
		// Load Xinha, fckeditor or TinyMCE Code
		if ($config["wysiwyg_editor"] == 'fckeditor' && file_exists($config['basepath'] . '/include/class/fckeditor')) {
			if (isset($_GET['action'])) {
				if ($_GET['action'] == 'edit_page' || $_GET['action'] == 'add_page' || $_GET['action'] == 'add_blog' || $_GET['action'] == 'edit_blog_post') {
					$display .= '<script type="text/javascript" src="' . $config['baseurl'] . '/include/class/fckeditor/fckeditor.js"></script>' . "\r\n";
					$display .= '<script type="text/javascript">' . "\r\n";
					$display .= '<!--' . "\r\n";
					$display .= 'window.onload = function()' . "\r\n";
					$display .= '{' . "\r\n";
					$display .= 'var oFCKeditor = new FCKeditor( \'ta\' ) ;' . "\r\n";

					$display .= 'oFCKeditor.BasePath = \'' . $config['baseurl'] . '/include/class/fckeditor/\';' . "\r\n";
					$display .= 'oFCKeditor.Config[\'UseBROnCarriageReturn\'] = \'TRUE\'  ;' . "\r\n";
					if($_GET['action'] == 'edit_blog_post'){
						$display .= 'oFCKeditor.ToolbarSet = \'Blog\' ;';
					}
					// Get Relative Path
					$me = $_SERVER['PHP_SELF'];
					$Apathweb = explode("/", $me);
					$pathweb = implode("/", $Apathweb);
					$pathweb = str_replace('/admin', '', $pathweb);
					$pathweb = str_replace('index.php', '', $pathweb);
					// $bodytag = str_replace("%body%", "black", "<body text='%body%'>");
					// echo $pathweb;
					if (ini_get('safe_mode')) {
						$display .= 'oFCKeditor.Config[\'ImageBrowserURL\'] = \'' . $config['baseurl'] . '/include/class/fckeditor/editor/filemanager/browser/open-realty/browser.html?Type=Image&amp;Connector=connectors/php/connector.php&amp;ServerPath=' . $pathweb . 'images/page_upload/\' ;' . "\r\n";
					}else {
						if (isset($_SESSION['PageID']) && $_SESSION['PageID'] != '') {
							@mkdir($config['basepath'] . '/images/page_upload/' . $_SESSION['PageID']);
							$display .= 'oFCKeditor.Config[\'ImageBrowserURL\'] = \'' . $config['baseurl'] . '/include/class/fckeditor/editor/filemanager/browser/open-realty/browser.html?Type=Image&amp;Connector=connectors/php/connector.php&amp;ServerPath=' . $pathweb . 'images/page_upload/' . $_SESSION['PageID'] . '/\' ;' . "\r\n";
						}else {
							$display .= 'oFCKeditor.Config[\'ImageBrowserURL\'] = \'' . $config['baseurl'] . '/include/class/fckeditor/editor/filemanager/browser/open-realty/browser.html?Type=Image&amp;Connector=connectors/php/connector.php&amp;ServerPath=' . $pathweb . 'images/page_upload/null/\' ;' . "\r\n";
						}
					}
					if (ini_get('safe_mode')) {
						$display .= 'oFCKeditor.Config[\'FlashBrowserURL\'] = \'' . $config['baseurl'] . '/include/class/fckeditor/editor/filemanager/browser/open-realty/browser.html?Type=Flash&amp;Connector=connectors/php/connector.php&amp;ServerPath=' . $pathweb . 'images/page_upload/\' ;' . "\r\n";
					}else {
						if (isset($_SESSION['PageID']) && $_SESSION['PageID'] != '') {
							@mkdir($config['basepath'] . '/images/page_upload/' . $_SESSION['PageID']);
							$display .= 'oFCKeditor.Config[\'FlashBrowserURL\'] = \'' . $config['baseurl'] . '/include/class/fckeditor/editor/filemanager/browser/open-realty/browser.html?Type=Flash&amp;Connector=connectors/php/connector.php&amp;ServerPath=' . $pathweb . 'images/page_upload/' . $_SESSION['PageID'] . '/\' ;' . "\r\n";
						}else {
							$display .= 'oFCKeditor.Config[\'FlashBrowserURL\'] = \'' . $config['baseurl'] . '/include/class/fckeditor/editor/filemanager/browser/open-realty/browser.html?Type=Flash&amp;Connector=connectors/php/connector.php&amp;ServerPath=' . $pathweb . 'images/page_upload/null/\' ;' . "\r\n";
						}
					}
					$display .= 'oFCKeditor.Config[\'LinkBrowser\'] = \'FALSE\'  ;' . "\r\n";
					if (!isset($_SESSION["users_lang"])) {
						$display .= 'oFCKeditor.Config[\'DefaultLanguage\'] = \'' . $config['lang'] . '\'  ;' . "\r\n";
					}else {
						$display .= 'oFCKeditor.Config[\'DefaultLanguage\'] = \'' . $_SESSION['users_lang'] . '\'  ;' . "\r\n";
					}
					$display .= 'oFCKeditor.Height = \'350px\';' . "\r\n";

					$display .= 'oFCKeditor.ReplaceTextarea() ;' . "\r\n";
					$display .= '}' . "\r\n";
					$display .= '//-->' . "\r\n";
					$display .= '</script>' . "\r\n";
				}
			}
		} elseif ($config["wysiwyg_editor"] == 'xinha' && file_exists($config['basepath'] . '/include/class/xinha')) {
			if (isset($_GET['action'])) {
				if ($_GET['action'] == 'edit_page' || $_GET['action'] == 'add_page' || $_GET['action'] == 'add_blog' || $_GET['action'] == 'edit_blog_post') {
					$display .= '
						<script language="JavaScript" type="text/javascript" >
						<!--
							_editor_url = "' . $config['baseurl'] . '/include/class/xinha";
							_editor_lang = "en";
						//-->
						</script>

						<script type="text/javascript" src="' . $config['baseurl'] . '/include/class/xinha/htmlarea.js"></script>


						<script type="text/javascript">
						<!--' . "
						xinha_editors = null;
						xinha_init    = null;
						xinha_config  = null;
						xinha_plugins = null;
						var ta = null;

						xinha_init = xinha_init ? xinha_init :
						function()
						{
					";
					if($_GET['action'] == 'edit_blog_post'){
						//$display .= "xinha_plugins = xinha_plugins ?	xinha_plugins :	['FullScreen','ListType','SpellChecker'];";
						$display .= "xinha_plugins = xinha_plugins ?	xinha_plugins :	['FullScreen','ImageManager','ListType','SpellChecker','SuperClean'];";
					}else{
						$display .= "xinha_plugins = xinha_plugins ?	xinha_plugins :	['ContextMenu','FullScreen','ImageManager','ListType','SpellChecker','Stylist','SuperClean','TableOperations'];";
					}

					$display .= "
						if(!HTMLArea.loadPlugins(xinha_plugins,	xinha_init)) return;

						xinha_editors = xinha_editors ?	xinha_editors :	['ta'];


						xinha_config = xinha_config ? xinha_config() : new HTMLArea.Config();";
					if (file_exists($config["template_path"] . "/editor.css")) {
						$display .= "xinha_config.stylistLoadStylesheet('" . $config["template_url"] . "/editor.css');";
					}
					if (session_id()=='') session_start();
					$IMConfig = array();
					if (ini_get('safe_mode')) {
						$IMConfig['images_dir'] = $config['basepath'] . '/images/page_upload/';
					}else {
						if (isset($_SESSION['PageID']) && $_SESSION['PageID'] != '') {
							$IMConfig['images_dir'] = $config['basepath'] . '/images/page_upload/' . $_SESSION['PageID'] . '/';
						}else {
							$IMConfig['images_dir'] = $config['basepath'] . '/images/page_upload/null/';
						}
					}
					@mkdir($IMConfig['images_dir'], 0777);
					if (ini_get('safe_mode')) {
						$IMConfig['images_url'] = $config['baseurl'] . '/images/page_upload/';
					}else {
						if (isset($_SESSION['PageID']) && $_SESSION['PageID'] != '') {
							$IMConfig['images_url'] = $config['baseurl'] . '/images/page_upload/' . $_SESSION['PageID'] . '/';
						}else {
							$IMConfig['images_url'] = $config['baseurl'] . '/images/page_upload/null/';
						}
					}
					// $IMConfig['safe_mode'] = ini_get('safe_mode');
					if ($config['thumbnail_prog'] == 'gd') {
						// $IMConfig['IMAGE_CLASS'] = 'GD';
					}else {
						// $IMConfig['IMAGE_CLASS'] = 'IM';
					}
					// $IMConfig['IMAGE_TRANSFORM_LIB_PATH'] = $config['path_to_imagemagick'];
					$IMConfig = serialize($IMConfig);

					if (!isset($_SESSION['Xinha:ImageManager'])) {
						$_SESSION['Xinha:ImageManager'] = uniqid('secret_');
					}

					$display .= "
						xinha_config.ImageManager.backend_config = '" . $IMConfig . "';

						xinha_config.ImageManager.backend_config_hash = '" . sha1($IMConfig . $_SESSION['Xinha:ImageManager']) . "';
						xinha_config.ImageManager.backend_config_secret_key_location = 'Xinha:ImageManager';
						xinha_editors = HTMLArea.makeEditors(xinha_editors, xinha_config, xinha_plugins);

						HTMLArea.startEditors(xinha_editors);
						}

						window.onload = xinha_init;

						//-->
						</script> ";

				}
			}
		} elseif ($config["wysiwyg_editor"] == 'tinymce' && file_exists($config['basepath'] . '/include/class/tinymce')) {
			if (isset($_GET['action'])) {
				if ($_GET['action'] == 'edit_page' || $_GET['action'] == 'add_page' || $_GET['action'] == 'add_blog' || $_GET['action'] == 'edit_blog_post') {
					// load commercial plugins for TinyMCE if they exist
					if (file_exists($config['basepath'] . '/include/class/tinymce/filemanager/jscripts/mcfilemanager.js')) {
						$display .= '<script language="javascript" type="text/javascript" src="/filemanager/jscripts/mcfilemanager.js"></script>';
					}
					if (file_exists($config['basepath'] . '/include/class/tinymce/filemanager/jscripts/mcimagemanager.js')) {
						$display .= '<script language="javascript" type="text/javascript" src="/imagemanager/jscripts/mcimagemanager.js"></script>';
					}
					// Display TinyMCE Editor
					$display .= '
						<script language="javascript" type="text/javascript" src="' . $config['baseurl'] . '/include/class/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
						<script language="javascript" type="text/javascript">
						tinyMCE.init({
						mode : "textareas",
						theme : "advanced",
						plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable",
						theme_advanced_buttons1_add_before : "save,newdocument,separator",
						theme_advanced_buttons1_add : "fontselect,fontsizeselect",
						theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
						theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
						theme_advanced_buttons3_add_before : "tablecontrols,separator",
						theme_advanced_buttons3_add : "emotions,iespell,flash,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_path_location : "bottom",
						content_css : "' . $config['basepath'] . '/include/class/tinymce/example_data/example_full.css",
						plugin_insertdate_dateFormat : "%Y-%m-%d",
						plugin_insertdate_timeFormat : "%H:%M:%S",
						extended_valid_elements : "hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
						external_link_list_url : "example_data/example_link_list.js",
						external_image_list_url : "example_data/example_image_list.js",
						flash_external_list_url : "example_data/example_flash_list.js",
						file_browser_callback : "mcFileManager.filebrowserCallBack",
						theme_advanced_resize_horizontal : false,
						theme_advanced_resizing : true
						});
						</script>';
				}
			}
		}
		$display .= $loadjs;
		return $display;
	}
	function index_page()
	{
		global $config, $lang;
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$page = new page_admin();
		$page->load_page($config['admin_template_path'] . '/or_index.html');
		//echo "Start Index Page :\r\n".print_r($page->page,TRUE)."\r\n--- End Index Page\r\n\r\n";
		$page->replace_tags(array('general_info', 'openrealty_links', 'baseurl', 'lang', 'user_id', 'addon_links'));
		//echo "Start Index Page Replace :\r\n".print_r($page->page,TRUE)."\r\n--- End Index Page Replace\r\n\r\n";
		return $page->return_page();
	}
	/**
	 * general_admin::display_addons()
	 * This functions first calls the add-on install function to make sure it is intalled/updated. Then calls the addons show_admin_icons function.
	 *
	 * @param string $addon Should be the name of the addon to install and then load.
	 * @return
	 */
	function display_addons($addon = '')
	{
		global $config;
		$addon_file = $config['basepath'] . '/addons/' . $addon . '/addon.inc.php';
		$links = '';
		if (file_exists($addon_file)) {
			include_once ($addon_file);
			$function_name = $addon . '_install_addon';
			$function_name();
			// $install = install_addon();
			$function_name = $addon . '_show_admin_icons';
			$links = $function_name();
		}
		return $links;
	}

	/**
	 * general_admin::openrealty_links()
	 * This function displays the Open-Realty Support, Wiki, Defects, and Upgrade Links.
	 *
	 * @return
	 */
	function openrealty_links()
	{
		global $lang;
		$display = '';
		$display .= '<div id="openrealty_links">';
		$display .= '<fieldset>';
		$display .= '<legend>Open <span class="realty">Realty</span> ' . $lang['link_links'] . '</legend>';
		$display .= '<ul>';
		$display .= '<li><a href="http://support.open-realty.org/">' . $lang['link_support_forums'] . '</a></li>';
		$display .= '<li><a href="http://docs.google.com/View?id=dhk2ckgx_4dw62x5fh">' . $lang['link_online_documentation'] . '</a></li>';
		$display .= '<li><a href="http://www.open-realty.org/bugs/">' . $lang['link_report_bug'] . '</a></li>';
		if (general_admin::update_check() === true) {
			$display .= '<li class="upgrade_true"><a href="http://www.open-realty.org/download.html">' . $lang['link_upgrade_available'] . '</a></li>';
		}else if (general_admin::update_check() == 'php_error') {
			$display .= '<li class="upgrade_false"><a href="http://www.open-realty.org/download.html">' . $lang['link_upgrade_manual'] . '</a></li>';
		}
		$display .= '</ul>';
		$display .= '</fieldset></div>';
		return $display;
	}
	/**
	 * general_admin::update_check()
	 * This function Check to see if it is necessary to update Open-Realty. It looks at the Version number in the config table and compares it to the number located in http://www.open-realty.org/release/version.txt. If allow_url_fopen is off, it show a manual update link, which the user cna click on to open the open-realty site.
	 *
	 * @return
	 */
	function update_check()
	{
		global $config;
		if ($config["automatic_update_check"] == 1) {
			$current_version = $config['version'];
			if (ini_get("allow_url_fopen") == 1) {
				$lastest_version = @file_get_contents("http://www.open-realty.org/release/version.txt");
				$check = version_compare($current_version, $lastest_version, ">=");
				if ($check == 1) {
					return false;
				} else {
					return true;
				}
			} else {
				return 'php_error';
			}
		} else {
			return 'php_error';
		}
	}
	/**
	 * general_admin::general_info()
	 * This displays the general information section on the index page. It is showing the following information.
	 *
	 * @see general_admin::listing_count()
	 * @see general_admin::listing_count()
	 * @see general_admin::agent_count()
	 * @return
	 */
	function general_info()
	{
		global $lang,$config;
		$display = '<div id="general_info">';
		$display .= '<fieldset>';
		$display .= '<legend>' . $lang['general_information'] . '</legend>';
		$display .= '<ul class="left">';
		$display .= '<li class="total_listings"><a href="'.$config['baseurl'].'/admin/index.php?action=edit_listings">' . $lang['total_listings'] . '</a></li>';
		$display .= '<li class="active_listings"><a href="javascript:document.getElementById(\'edit_active\').submit()">' . $lang['active_listings'] . '</a></li>';
		$display .= '<li class="inactive_listings"><a href="javascript:document.getElementById(\'edit_inactive\').submit()">' . $lang['inactive_listings'] . '</a></li>';
		$display .= '<li class="featured_listings"><a href="javascript:document.getElementById(\'edit_featured\').submit()">' . $lang['featured_listings'] . '</a></li>';
		if ($config['use_expiration'] == 1)
		{
		$display .= '<li class="expired_listings"><a href="javascript:document.getElementById(\'edit_expired\').submit()">' . $lang['expired_listings'] . '</a></li>';
		}
		$display .= '<li class="number_of_agents"><a href="javascript:document.getElementById(\'edit_agents\').submit()">' . $lang['number_of_agents'] . '</a></li>';
		$display .= '<li class="number_of_members"><a href="javascript:document.getElementById(\'edit_members\').submit()">' . $lang['number_of_members'] . '</a></li>';
		$display .= '</ul>';
		$display .= '<ul class="right">';
		$display .= '<li class="total_listings">'.general_admin::listing_count() . '</li>';
		$display .= '<li class="active_listings">'.general_admin::listing_count('yes') . '</li>';
		$display .= '<li class="inactive_listings">'.general_admin::listing_count('no') . '</li>';
		$display .= '<li class="featured_listings">'.general_admin::listing_count('featured') . '</li>';
		if ($config['use_expiration'] == 1)
		{
		$display .= '<li class="expired_listings">'.general_admin::listing_count('expired') . '</li>';
		}
		$display .= '<li class="number_of_agents">'.general_admin::agent_count() . '</li>';
		$display .= '<li class="number_of_members">'.general_admin::member_count() . '</li>';
		$display .= '</ul>';
		$display .= '</fieldset></div>';
		
		$display .= '<div id="HiddenFilterForm" style="display:none">';
		$display .= '<form id="edit_active" action="'.$config['baseurl'].'/admin/index.php?action=edit_listings" method="post"><fieldset><input type="hidden" name="filter" value="active" /></fieldset></form>';
		$display .= '<form id="edit_inactive" action="'.$config['baseurl'].'/admin/index.php?action=edit_listings" method="post"><fieldset><input type="hidden" name="filter" value="inactive" /></fieldset></form>';
		$display .= '<form id="edit_featured" action="'.$config['baseurl'].'/admin/index.php?action=edit_listings" method="post"><fieldset><input type="hidden" name="filter" value="featured" /></fieldset></form>';
		if ($config['use_expiration'] == 1)
		{
		$display .= '<form id="edit_expired" action="'.$config['baseurl'].'/admin/index.php?action=edit_listings" method="post"><fieldset><input type="hidden" name="filter" value="expired" /></fieldset></form>';
		}
		$display .= '<form id="edit_agents" action="'.$config['baseurl'].'/admin/index.php?action=user_manager" method="post"><fieldset><input type="hidden" name="filter" value="agents" /></fieldset></form>';
		$display .= '<form id="edit_members" action="'.$config['baseurl'].'/admin/index.php?action=user_manager" method="post"><fieldset><input type="hidden" name="filter" value="members" /></fieldset></form>';
		$display .= '</div>';
		return $display;
	}
	/**
	 * general_admin::agent_count()
	 * Returns the number of agents currently in the database.
	 *
	 * @return string Number of agents
	 */
	function agent_count()
	{
		global $conn, $config;
		$agent_count_sql = 'SELECT count(userdb_id) as agent_count FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_is_agent = \'yes\'';
		$agent_count = $conn->Execute($agent_count_sql);
		$agent_count = $agent_count->fields['agent_count'];
		return $agent_count;
	}
	/**
	 * general_admin::listing_count()
	 * Returns the number of listings currently in the database.
	 *
	 * @param string $active if set to yes it returns only active listings.
	 * @return Number of listings found
	 */
	function listing_count($view='all')
	{
		global $conn, $config;
		if ($view == 'all') {
			$listing_count_sql = 'SELECT count(listingsdb_id) as listing_count FROM ' . $config['table_prefix'] . 'listingsdb';
		} elseif ($view == 'yes') {
			$listing_count_sql = 'SELECT count(listingsdb_id) as listing_count FROM ' . $config['table_prefix'] . 'listingsdb WHERE listingsdb_active = \'yes\'';
		} elseif ($view == 'no') {
			$listing_count_sql = 'SELECT count(listingsdb_id) as listing_count FROM ' . $config['table_prefix'] . 'listingsdb WHERE listingsdb_active = \'no\'';
		} elseif ($view == 'featured') {
			$listing_count_sql = 'SELECT count(listingsdb_id) as listing_count FROM ' . $config['table_prefix'] . 'listingsdb WHERE listingsdb_featured = \'yes\'';
		} elseif ($view == 'expired') {
			$listing_count_sql = 'SELECT count(listingsdb_id) as listing_count FROM ' . $config['table_prefix'] . 'listingsdb WHERE listingsdb_expiration < ' . $conn->DBDate(time());
		}

		$listing_count = $conn->Execute($listing_count_sql);
		$listing_count = $listing_count->fields['listing_count'];
		return $listing_count;
	}
	/**
	 * general_admin::member_count()
	 * Returns the number of members currently in the database.
	 *
	 * @return string Number of members
	 */
	function member_count()
	{
		global $conn, $config;
		$member_count_sql = 'SELECT count(userdb_id) as member_count FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_is_agent = \'no\' AND userdb_is_admin = \'no\'';
		$member_count = $conn->Execute($member_count_sql);
		$member_count = $member_count->fields['member_count'];
		return $member_count;
	}
}

?>