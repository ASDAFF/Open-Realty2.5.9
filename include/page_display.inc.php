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
class page_display {
	public static function get_page_title($page_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$page_id = intval($page_id);
		$page_id = $misc->make_db_safe($page_id);
		$sql = "SELECT pagesmain_title FROM " . $config['table_prefix'] . "pagesmain WHERE pagesmain_id=" . $page_id;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$title = $misc->make_db_unsafe($recordSet->fields['pagesmain_title']);
		return $title;
	}
	public static function get_page_description($page_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		if (isset($_GET['PageID'])) {
			$page_id = $misc->make_db_safe($page_id);
			$sql = "SELECT pagesmain_description FROM " . $config['table_prefix'] . "pagesmain WHERE pagesmain_id=" . $page_id;
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$description = $misc->make_db_unsafe($recordSet->fields['pagesmain_description']);
			return $description;
		}else {
			return '';
		}
	}
	public static function get_page_keywords($page_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		if (isset($_GET['PageID'])) {
			$page_id = $misc->make_db_safe($page_id);
			$sql = "SELECT pagesmain_keywords FROM " . $config['table_prefix'] . "pagesmain WHERE pagesmain_id=" . $page_id;
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$keywords = $misc->make_db_unsafe($recordSet->fields['pagesmain_keywords']);
			return $keywords;
		}else {
			return '';
		}
	}
	function display()
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// Make Sure we passed the PageID
		$display = '';
		if (!isset($_GET['PageID'])) {
			$display .= "ERROR. PageID not sent";
		}
		$page_id = $misc->make_db_safe($_GET['PageID']);
		$display .= '<div class="page_display">';
		$sql = "SELECT pagesmain_full,pagesmain_id FROM " . $config['table_prefix'] . "pagesmain WHERE pagesmain_id=" . $page_id;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$full = html_entity_decode($misc->make_db_unsafe($recordSet->fields['pagesmain_full']), ENT_NOQUOTES, $config['charset']); //$full = $misc->make_db_unsafe($recordSet->fields['pagesmain_full']);
		$id = $recordSet->fields['pagesmain_id'];
		if ($config["wysiwyg_execute_php"] == 1) {
			ob_start();
			$full = str_replace("<!--<?php", "<?php", $full);
			$full = str_replace("?>-->", "?>", $full);
			eval('?>' . "$full" . '<?php ');
			$display .= ob_get_contents();
			ob_end_clean();
		}else {
			$display .= $full;
		}
		// Allow Admin To Edit #
		if ((isset($_SESSION['editpages']) && $_SESSION['admin_privs'] == 'yes') && $config["wysiwyg_show_edit"] == 1) {
			$display .= "<p>&nbsp;</p>";
			$display .= "<a href=\"$config[baseurl]/admin/index.php?action=edit_page&amp;id=$id\">$lang[edit_html_from_site]</a>";
		}

		$display .= '</div>' ;
		// parse page for template varibales
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$template = new page_user();
		$template->page = $display;
		$template->replace_tags(array('templated_search_form', 'featured_listings_horizontal', 'featured_listings_vertical', 'company_name', 'link_printer_friendly'));
		$display = $template->return_page();
		return $display;
	} // End page_display()
} //End page_display Class

?>