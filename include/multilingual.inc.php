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
class multilingual {
	var $lang_names = array();
	function multilingual_select()
	{
		global $config, $lang;
		$guidestring = '';
		foreach ($_GET as $k => $v) {
			if ($k != 'PHPSESSID' && $v) {
				if (is_array($v)) {
					foreach ($v as $vitem) {
						$guidestring .= urlencode("$k") . '[]=' . urlencode("$vitem") . '&amp;';
					}
				}else {
					$guidestring .= urlencode("$k") . '=' . urlencode("$v") . '&amp;';
				}
			}
		}

		$display = '';
		$display .= '<form class="multilingual_form" id="lang_select" method="post" action="index.php?' . $guidestring . '"><div style="display:inline;">';
		foreach ($_POST as $k => $v) {
			if ($k != 'user_name' && $k != 'user_pass' && $k != 'ta' && $k != 'title') {
				if (is_array($v)) {
					foreach ($v as $vitem) {
						$display .= '<input type="hidden" name="' . $k . '[]" value="' . $vitem . '" />';
					}
				}else {
					$display .= '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
				}
			}
		}
		$display .= $lang['language'];
		$display .= '<select class="multilingual_select" name="select_users_lang" onchange="document.getElementById(\'lang_select\').submit()">';
		// Get List of active languages
		$configured_langs = explode(',', $config['configured_langs']);
		$selected_lang = '';
		if (!isset($_SESSION['users_lang'])) {
			$selected_lang = $config['lang'];
		}else {
			$selected_lang = $_SESSION['users_lang'];
		}
		foreach ($configured_langs as $langs) {
			if ($langs == $selected_lang) {
				$display .= '<option value="' . $langs . '" selected="selected">' . $lang['multilingual_' . $langs] . '</option>';
			}else {
				$display .= '<option value="' . $langs . '">' . $lang['multilingual_' . $langs] . '</option>';
			}
		}
		$display .= '</select>';
		$display .= '<input type="hidden" name="lang_change" value="yes" />';
		$display .= '</div></form>';
		return $display;
	}
	function setup_additional_language($language)
	{
		// echo 'Setup '.$language;
		global $config, $conn;
		$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . $language . "_listingsdb (
				listingsdb_id INT4 NOT NULL AUTO_INCREMENT,
				listingsdb_title CHAR VARYING(80) NOT NULL,
				listingsdb_notes TEXT NOT NULL,
				PRIMARY KEY(listingsdb_id)
				);";
		$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . $language . "_listingsdbelements (
				listingsdbelements_id INT4 NOT NULL AUTO_INCREMENT,
				listingsdbelements_field_value TEXT NOT NULL,
				PRIMARY KEY(listingsdbelements_id)
				);";

		$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . $language . "_listingsformelements (
				listingsformelements_id INT4 NOT NULL AUTO_INCREMENT,
				listingsformelements_field_caption CHAR VARYING(80) NOT NULL,
				listingsformelements_default_text TEXT NOT NULL,
				listingsformelements_field_elements TEXT NOT NULL,
				listingsformelements_search_label CHAR VARYING(50) NULL,
				PRIMARY KEY(listingsformelements_id)
				);";
		while (list($elementIndexValue, $elementContents) = each($sql_insert)) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong>");
		}
	}
	function remove_additional_language($language)
	{
		// echo 'Remove '.$language;
		global $config, $conn;
		$sql_insert[] = "DROP TABLE " . $config['table_prefix_no_lang'] . $language . "_listingsdb";
		$sql_insert[] = "DROP TABLE " . $config['table_prefix_no_lang'] . $language . "_listingsdbelements";
		$sql_insert[] = "DROP TABLE " . $config['table_prefix_no_lang'] . $language . "_listingsformelements";
		while (list($elementIndexValue, $elementContents) = each($sql_insert)) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong>");
		}
	}
}

?>