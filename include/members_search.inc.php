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
class memberssearch {
	function delete_search()
	{
		global $config, $lang, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$status = login::loginCheck('Member');
		if ($status === true) {
			if (!isset($_GET['searchID'])) {
				$display .= '<a href="' . $config['baseurl'] . '/index.php">' . $lang['perhaps_you_were_looking_something_else'] . '</a>';
			}elseif ($_GET['searchID'] == '') {
				$display .= '<a href="' . $config['baseurl'] . '/index.php">' . $lang['perhaps_you_were_looking_something_else'] . '</a>';
			}elseif ($_GET['searchID'] != '') {
				$userID = $misc->make_db_safe($_SESSION['userID']);
				$searchID = $misc->make_db_safe($_GET['searchID']);
				$sql = "DELETE FROM " . $config['table_prefix'] . "usersavedsearches WHERE usersavedsearches_id = $searchID AND userdb_id = $userID";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$display .= "<br />$lang[search_deleted_from_favorites]";
				$display .= memberssearch::view_saved_searches();
				return $display;
			}
		}else {
			$display = $status;
		}
	} //End function delete_search()
	function save_search()
	{
		global $config, $lang, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$status = login::loginCheck('Member');
		if ($status === true) {
			$userID = $misc->make_db_safe($_SESSION['userID']);
			if (isset($_POST['title'])) {
				$title = $misc->make_db_safe($_POST['title']);
				$query = $misc->make_db_safe($_POST['query']);
				$notify = $misc->make_db_safe($_POST['notify']);
				$misc->make_db_safe($_POST['title']);
				$sql = "SELECT * FROM " . $config['table_prefix'] . "usersavedsearches WHERE userdb_id = $userID AND usersavedsearches_title = $title";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$num_columns = $recordSet->RecordCount();
				if ($num_columns == 0) {
					$sql = "INSERT INTO " . $config['table_prefix'] . "usersavedsearches (userdb_id, usersavedsearches_title, usersavedsearches_query_string,usersavedsearches_last_viewed,usersavedsearches_new_listings,usersavedsearches_notify) VALUES ($userID, $title, $query,now(),0, $notify)";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}else {
						$display .= '<br />' . $lang['search_added_to_saved_searches'];
					}
				}else {
					$display .= '<br />' . $lang['search_title_already_in_saved_searches'] . '<br />';
					$guidestring = '';
					foreach ($_GET as $k => $v) {
						if (is_array($v)) {
							foreach ($v as $vitem) {
								$guidestring .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
							}
						}else {
							$guidestring .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
						}
					}
					$display .= '<br />';
					$display .= '<form action="index.php?' . $guidestring . '" method="post">';
					$display .= $lang['enter_title_for_search'];
					$display .= '<input type="text" name="title" /><br /><br />';
					if ($config['email_users_notification_of_new_listings'] == "1") {
						$display .= $lang['notify_saved_search'];
						$display .= '<select name="notify" size="1"><option value="yes">' . $lang['yes'] . '<option value="no">' . $lang['no'] . '</select><br /><br />';
					} else {
						$display .= $lang['notify_saved_search_disabled'] . '<br />';
						$display .= $lang['notify_saved_search'];
						$display .= '<select name="notify" size="1"><option value="yes">' . $lang['yes'] . '<option value="no">' . $lang['no'] . '</select><br /><br />';
					}
					$display .= '<input type="submit" value=' . $lang['save_search'] . '" />';
					$display .= '<input type="hidden" name="query" value="' . $query . '" />';
					$display .= '</form>';
					$display .= '<br />';
				}
			}else {
				$query = '';
				foreach ($_GET as $k => $v) {
					if ($v && $k != 'action' && $k != 'PHPSESSID') {
						if (is_array($v)) {
							foreach ($v as $vitem) {
								$query .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
							}
						}else {
							$query .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
						}
					}
				}
				if (substr($query, 0, strcspn($query, "=")) == "cur_page") {
					$query = substr($query, strcspn($query, "&") + 1);
					// echo $QUERY_STRING;
				}
				$sql = "SELECT usersavedsearches_title, usersavedsearches_query_string FROM " . $config['table_prefix'] . "usersavedsearches WHERE userdb_id = $_SESSION[userID] AND usersavedsearches_query_string = '$query'";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$num_columns = $recordSet->RecordCount();
				if ($num_columns != 0) {
					$display .= '<br />' . $lang['search_already_in_saved_searches'] . '<a href="' . $config['baseurl'] . '/index.php?searchresults&amp;' . make_db_unsafe($recordSet->fields['usersavedsearches_query_string'] . '">' . make_db_unsafe($recordSet->fields['usersavedsearches_title']) . '</a><br />');
				}else {
					// Get full guidesting
					$guidestring = '';
					foreach ($_GET as $k => $v) {
						if (is_array($v)) {
							foreach ($v as $vitem) {
								$guidestring .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
							}
						}else {
							$guidestring .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
						}
					}
					$display .= '<br />';
					$display .= '<form action="index.php?' . $guidestring . '" method="post">';
					$display .= $lang['enter_title_for_search'] . '<input type="text" name="title" /><br /><br />';
					if ($config['email_users_notification_of_new_listings'] == "1") {
						$display .= $lang['notify_saved_search'];
						$display .= '<select name="notify" size="1"><option value="yes">' . $lang['yes'] . '<option value="no">' . $lang['no'] . '</select><br /><br />';
					} else {
						$display .= $lang['notify_saved_search_disabled'] . '<br />';
						$display .= $lang['notify_saved_search'];
						$display .= '<select name="notify" size="1"><option value="yes">' . $lang['yes'] . '<option value="no">' . $lang['no'] . '</select><br /><br />';
					}
					$display .= '<input type="submit" value="' . $lang['save_search'] . '" />';
					$display .= '<input type="hidden" name="query" value="' . $query . '" />';
					$display .= '</form>';
					$display .= '<br />';
				}
			}
		}else {
			$display = $status;
		}
		return $display;
	} // End function save_search()
	function view_saved_searches()
	{
		global $config, $lang, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$status = login::loginCheck('Member');
		if ($status === true) {
			$display .= '<h3>' . $lang['saved_searches'] . '</h3>';
			$userID = $misc->make_db_safe($_SESSION['userID']);
			$sql = "SELECT usersavedsearches_id, usersavedsearches_title, usersavedsearches_query_string FROM " . $config['table_prefix'] . "usersavedsearches WHERE userdb_id = $userID ORDER BY usersavedsearches_title";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$num_columns = $recordSet->RecordCount();
			if ($num_columns == 0) {
				$display .= $lang['no_saved_searches'] . '<br /><br />';
			}else {
				while (!$recordSet->EOF) {
					$title = $misc->make_db_unsafe($recordSet->fields['usersavedsearches_title']);
					if ($title == '') {
						$title = $lang['saved_search'];
					}
					$display .= '<a href="index.php?action=searchresults&amp;' . $misc->make_db_unsafe($recordSet->fields['usersavedsearches_query_string']) . '">' . $title . '</a>&nbsp;&nbsp;&nbsp;&nbsp;<div class="note"><a href="index.php?action=delete_search&amp;searchID=' . $misc->make_db_unsafe($recordSet->fields['usersavedsearches_id']) . '" onclick="return confirmDelete()">' . $lang['delete_search'] . '</a></div><br /><br />';
					$recordSet->MoveNext();
				}
			}
		}else {
			$display = $status;
		}
		return $display;
	} // End function view_saved_searches()
}

?>