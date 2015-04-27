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
global $config;
class editor {
	/**
	 * **************************************************************************\
	 * function page_list() - This Creates a list of pages that you can edit 	*
	 * \**************************************************************************
	 */
	function page_list()
	{
		$security = login::loginCheck('editpages', true);
		$display = '';
		if ($security === true) {
			// include global variables
			global $conn, $lang, $config;
			// Include the Form Generation Class
			include($config['basepath'] . '/include/class/form_generation.inc.php');
			$formGen = new formGeneration();
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			// Grab a list of pages in the Database to Edit
			$default_value = '';
			if (isset($_POST['PageID'])) {
				$default_value = '';
				if ($_POST['PageID'] != '') {
					$sql = "SELECT pagesmain_title FROM " . $config['table_prefix'] . "pagesmain WHERE pagesmain_id = $_POST[PageID]";
					$recordSet = $conn->Execute($sql);
					if (!$recordSet) {
						$misc->log_error($sql);
					}
					$default_value = $misc->make_db_unsafe($recordSet->fields['pagesmain_title']);
				}
			}
			$sql = "SELECT pagesmain_title, pagesmain_id FROM " . $config['table_prefix'] . "pagesmain";
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			// Start the Form
			$display .= '<span class="section_header">' . $lang['admin_page_editor'] . '</span><br /><br />';
			$display .= $formGen->startform('index.php?action=edit_page', 'POST', 'multipart/form-data', 'PickPage');
			while (!$recordSet->EOF) {
				$options[$recordSet->fields['pagesmain_id']] = $recordSet->fields['pagesmain_title'];
				$recordSet->Movenext();
			}
			$display .= $formGen->create_select('PageID', '', false, 5, 0, false, 0, 0, $options, $default_value);
			// $display .= $recordSet->GetMenu('PageID',"$default_value",true);}
			$display .= $formGen->createformitem('submit', '', $lang['edit_page']);
			$display .= '<span style="margin-left:10px;"><a href="index.php?action=add_page">' . $lang['create_new_page'] . '</a></span>';
			$display .= $formGen->endform() . '<br />';
		}
		return $display;
	}
	/**
	 * **************************************************************************\
	 * function page_edit() - Display's the page editor							*
	 * \**************************************************************************
	 */
	function page_edit()
	{
		global $conn, $lang, $config;
		$security = login::loginCheck('editpages', true);
		$display = '';
		if ($security === true) {
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			// Do we need to save?
			if (isset($_POST['edit'])) {
				// Save page now
				$pageID = $misc->make_db_safe($_POST['PageID']);
				$save_full = $_POST['ta'];
				$save_title = $misc->make_db_safe($_POST['title']);
				$save_full_xhtml =  $misc->make_db_safe(editor::htmlEncodeText($save_full),TRUE);
				$save_description = $misc->make_db_safe($_POST['description']);
				$save_keywords = $misc->make_db_safe($_POST['keywords']);
				$sql = "UPDATE " . $config['table_prefix'] . "pagesmain SET pagesmain_full = " . $save_full_xhtml . ", pagesmain_title = " . $save_title . ", pagesmain_description = " . $save_description . ", pagesmain_keywords = " . $save_keywords . " WHERE pagesmain_id = " . $pageID . "";
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$display .= "<center><b>$lang[page_saved]</b></center><br />";
			}
			if (isset($_POST['delete'])) {
				if (isset($_POST['PageID'])) {
					if ($_POST['PageID'] != 1) {
						// Delete Page
						$pageID = $misc->make_db_safe($_POST['PageID']);
						$sql = "DELETE FROM " . $config['table_prefix'] . "pagesmain WHERE pagesmain_id = " . $pageID;
						$recordSet = $conn->Execute($sql);
						if (!$recordSet) {
							$misc->log_error($sql);
						}
						$display .= '<center><b>' . $lang['page_deleted'] . '</b></center><br />';
						$_POST['PageID'] = '';
					}else {
						$display .= '<center><font color="red">' . $lang['delete_index_page_error'] . '</font></center><br />';
					}
				}else {
					$display .= '<center><font color="red">' . $lang['invalid_page'] . '</font></center><br />';
					$_POST['PageID'] = '';
				}
			}
			$display .= $this->page_list();
			$display .= '<form action="index.php?action=edit_page" method="post" id="edit" name="edit">';
			$html = '';
			if (isset($_GET['id'])) {
				$_POST['PageID'] = $_GET['id'];
			}
			if (isset($_POST['PageID'])) {
				if ($_POST['PageID'] != '') {
					// Save PageID to Session for Image Upload Plugin
					$_SESSION['PageID'] = $_POST['PageID'];
					$pageID = $misc->make_db_safe($_POST['PageID']);
					// Pull the page from the database
					$display .= "<input type=\"hidden\" name=\"edit\" value=\"yes\" />";
					$display .= "<input type=\"hidden\" name=\"PageID\" value=\"" . $_POST['PageID'] . "\" />";
					$sql = "SELECT pagesmain_full, pagesmain_title, pagesmain_complete, pagesmain_description, pagesmain_keywords  FROM " . $config['table_prefix'] . "pagesmain WHERE pagesmain_id = " . $pageID;
					$recordSet = $conn->Execute($sql);
					if (!$recordSet) {
						$misc->log_error($sql);
					}
					if ($config["controlpanel_mbstring_enabled"] == 0) {
						// MBSTRING NOT ENABLED
						//$html = htmlentities($misc->make_db_unsafe($recordSet->fields['pagesmain_full']));
						$html = strtr($misc->make_db_unsafe($recordSet->fields['pagesmain_full']), array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
					} else {
						// MBSTRING ENABLED
						$html = mb_convert_encoding($misc->make_db_unsafe($recordSet->fields['pagesmain_full']), 'HTML-ENTITIES', $config['charset']);
					}
					$title = $misc->make_db_unsafe($recordSet->fields['pagesmain_title']);
					$description = $misc->make_db_unsafe($recordSet->fields['pagesmain_description']);
					$keywords = $misc->make_db_unsafe($recordSet->fields['pagesmain_keywords']);
					$display .= $lang['title'] . ' <input type="text" name="title" value="' . $title . '" /><br /><br />';
					$display .= $lang['page_meta_description'] . ' <input type="text" size="50" name="description" value="' . $description . '" /><br /><br />';
					$display .= $lang['page_meta_keywords'] . ' <input type="text" size="50" name="keywords" value="' . $keywords . '" /><br /><br />';
					$display .= $lang['template_tag_for_page'] . ' - {page_link_' . $_POST['PageID'] . '} <br />';
					$display .= $lang['link_to_page'] . ' - ';
					if ($config['url_style'] == '1') {
						$article_url = 'index.php?action=page_display&amp;PageID=' . $_POST['PageID'];
					}else {
						$url_title = str_replace("/", "", $title);
						$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
						$article_url = 'page-' . urlencode($url_title) . '-' . $_POST['PageID'] . '.html';
					}
					$display .= '<a href="' . $config['baseurl'] . '/' .$article_url . '">' . $config['baseurl'] . '/' .$article_url . '</a><br />';
				}
			}

			$display .= '<textarea name="ta" id="ta" style="height: 350px; width: 100%;">' . $html . '</textarea>';
			if (($config["demo_mode"] != 1) || ($_SESSION['admin_privs'] == 'yes')) {
			$display .= '<input type="submit" name="ok" value="' . $lang['submit'] . '" style="margin-top:3px;" />';
			}
			$display .= '</form>';
			if (($config["demo_mode"] != 1) || ($_SESSION['admin_privs'] == 'yes')) {
			if (isset($_POST['PageID'])) {
				if ($_POST['PageID'] != '') {
					$display .= '<form action="index.php?action=edit_page" method="post" id="delete" style="margin-top:3px;">';
					$display .= '<input type="hidden" name="delete" value="yes" />';
					$display .= '<input type="hidden" name="PageID" value="' . $_POST['PageID'] . '" />';
					$display .= '<input type="submit" name="ok" value="' . $lang['delete_page'] . '" />';
					$display .= '</form>';
				}
			}
			}
			if (($config["demo_mode"] == 1) && ($_SESSION['admin_privs'] != 'yes')) {
			$display .= $lang['demo_mode_editor_denied'];
			}
		}else {
			$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
		return $display;
	}
	function add_page()
	{
		global $conn, $lang, $config;
		$security = login::loginCheck('editpages', true);
		$display = '';
		if ($security === true) {
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			// Do we need to save?
			if (isset($_POST['edit'])) {
				// Save page now
				$save_full = $_POST['ta'];
				$save_title = $misc->make_db_safe($_POST['title']);
				$save_description = $misc->make_db_safe($_POST['description']);
				$save_keywords = $misc->make_db_safe($_POST['keywords']);
				// $save_full_xhtml = urldecode($save_full);
				// $save_full_xhtml = $this->html2xhtml($save_full_xhtml);
				$save_full_xhtml =  $misc->make_db_safe(editor::htmlEncodeText($save_full),TRUE);
				$sql = "INSERT INTO " . $config['table_prefix'] . "pagesmain (pagesmain_full,pagesmain_title,pagesmain_date,pagesmain_summary,pagesmain_no_visitors,pagesmain_complete,pagesmain_description,pagesmain_keywords) VALUES ($save_full_xhtml,$save_title," . $conn->DBDate(time()) . ",'',0,1,$save_description,$save_keywords)";
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$display .= "<center><b>$lang[page_saved]</b></center><br />";
				$display .= $this->page_list();
				$display .= '<form action="index.php?action=edit_page" method="post" id="edit" name="edit">';
				$html = '';
				$sql = "SELECT pagesmain_full, pagesmain_title, pagesmain_complete, pagesmain_id, pagesmain_description, pagesmain_keywords  FROM " . $config['table_prefix'] . "pagesmain WHERE pagesmain_title = " . $save_title;
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				// Save PageID to Session for Image Upload Plugin
				$_SESSION['PageID'] = $recordSet->fields['pagesmain_id'];
				// Pull the page from the database
				$display .= "<input type=\"hidden\" name=\"edit\" value=\"yes\" />";
				$display .= "<input type=\"hidden\" name=\"PageID\" value=\"" . $_SESSION['PageID'] . "\" />";
				$html = $misc->make_db_unsafe($recordSet->fields['pagesmain_full']);
				$title = $misc->make_db_unsafe($recordSet->fields['pagesmain_title']);
				$description = $misc->make_db_unsafe($recordSet->fields['pagesmain_description']);
				$keywords = $misc->make_db_unsafe($recordSet->fields['pagesmain_keywords']);
				// $complete = $misc->make_db_unsafe($recordSet->fields['pagesmain_complete']);
				$display .= $lang['title'] . ' <input type="text" name="title" value="' . $title . '" /><br /><br />';
				$display .= $lang['page_meta_description'] . ' <input type="text" size="50" name="description" value="' . $description . '" /><br /><br />';
				$display .= $lang['page_meta_keywords'] . ' <input type="text" size="50" name="keywords" value="' . $keywords . '" /><br /><br />';
				$display .= '<textarea name="ta" id="ta" style="height: 350px; width: 100%;">' . $html . '</textarea>';
				$display .= '<input type="submit" name="ok" value="' . $lang['submit'] . '"  style="margin-top:3px;"/>';
				$display .= '</form>';
				if ($_SESSION['PageID'] != '') {
					$display .= '<form action="index.php?action=edit_page" method="post" id="delete" style="margin-top:3px;">';
					$display .= '<input type="hidden" name="delete" value="yes" />';
					$display .= '<input type="hidden" name="PageID" value="' . $_SESSION['PageID'] . '" />';
					$display .= '<input type="submit" name="ok" value="' . $lang['delete_page'] . '" />';
					$display .= '</form>';
				}
			}else {
				$display .= $this->page_list();
				$display .= '<form action="index.php?action=add_page" method="post" id="edit" name="edit">';
				$display .= "<input type=\"hidden\" name=\"edit\" value=\"yes\" />";
				$display .= $lang['title'] . ' <input type="text" name="title" value="" /><br /><br />';
				$display .= $lang['page_meta_description'] . ' <input type="text" size="50" name="description" value="" /><br /><br />';
				$display .= $lang['page_meta_keywords'] . ' <input type="text" size="50" name="keywords" value="" /><br /><br />';
				$display .= '<textarea name="ta" id="ta" style="height: 30em; width: 100%;"></textarea>';
				$display .= '<input type="submit" name="ok" value="' . $lang['submit'] . '" style="margin-top:3px;" />';
				$display .= '</form>';
			}
		}else {
			$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
		return $display;
	}
	function htmlEncodeText($string)
	{
		global $config;
		$pattern = '<([a-zA-Z0-9\.\, "\'_\/\-\+~=;:\(\)?&#%![\]@]+)>';
		preg_match_all('/' . $pattern . '/', $string, $tagMatches, PREG_SET_ORDER);
		$textMatches = preg_split('/' . $pattern . '/', $string);
		foreach($textMatches as $key => $value) {
			$textMatches [$key] = htmlentities($value, ENT_NOQUOTES, $config['charset']);
			}
		for($i = 0; $i < count ($textMatches); $i ++) {
			$textMatches [$i] = $textMatches [$i] . $tagMatches [$i] [0];
			}
		return implode($textMatches);
	}
}

?>