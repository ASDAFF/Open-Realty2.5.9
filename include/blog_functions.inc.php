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
class blog_functions {

	function get_blog_title($blog_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$blog_id = intval($blog_id);
		$blog_id = $misc->make_db_safe($blog_id);
		$sql = "SELECT blogmain_title FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_id=" . $blog_id;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$title = $misc->make_db_unsafe($recordSet->fields['blogmain_title']);
		return $title;
	}
	function get_blog_date($blog_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$blog_id = intval($blog_id);
		$blog_id = $misc->make_db_safe($blog_id);
		$sql = "SELECT blogmain_date FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_id=" . $blog_id;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$date = $misc->make_db_unsafe($recordSet->fields['blogmain_date']);
		return $date;
	}
	function get_blog_comment_count($blog_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$blog_id = intval($blog_id);
		$blog_id = $misc->make_db_safe($blog_id);
		$sql = "SELECT count(blogcomments_id) as commentcount FROM " . $config['table_prefix'] . "blogcomments WHERE blogmain_id=" . $blog_id;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$count = $misc->make_db_unsafe($recordSet->fields['commentcount']);
		return $count;
	}
	function get_blog_author($blog_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$blog_id = intval($blog_id);
		$blog_id = $misc->make_db_safe($blog_id);
		$sql = "SELECT userdb_id FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_id=" . $blog_id;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$userid = $misc->make_db_unsafe($recordSet->fields['userdb_id']);
		require_once($config['basepath'] . '/include/user.inc.php');
		$user = new user();
		$name = $user->get_user_last_name($userid).', '.$user->get_user_first_name($userid);
		return $name;
	}
	function get_blog_description($blog_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		if (isset($_GET['PageID'])) {
			$blog_id = $misc->make_db_safe($blog_id);
			$sql = "SELECT blogmain_description FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_id=" . $blog_id;
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$description = $misc->make_db_unsafe($recordSet->fields['blogmain_description']);
			return $description;
		}else {
			return '';
		}
	}
	function get_blog_keywords($blog_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		if (isset($_GET['PageID'])) {
			$blog_id = $misc->make_db_safe($blog_id);
			$sql = "SELECT blogmain_keywords FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_id=" . $blog_id;
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$keywords = $misc->make_db_unsafe($recordSet->fields['blogmain_keywords']);
			return $keywords;
		}else {
			return '';
		}
	}
}
?>