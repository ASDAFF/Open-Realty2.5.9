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
class log {
	function view()
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		// find the number of log items
		$sql = "SELECT * FROM " . $config['table_prefix'] . "activitylog ORDER BY activitylog_id DESC";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_rows = $recordSet->RecordCount();
		if (!isset($_GET['cur_page'])) {
			$_GET['cur_page'] = 0;
		}
		$display .= $misc->next_prev($num_rows, intval($_GET['cur_page']), "",'',TRUE); // put in the next/previous stuff
		// build the string to select a certain number of users per page
		$limit_str = intval($_GET['cur_page']) * 25;
		$recordSet = $conn->SelectLimit($sql, 25, $limit_str);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$display .= '<table class="log_viewer" cellpadding="0" cellspacing="0" summary="' . $lang['log_viewer'] . '">';
		$display .= '<caption>' . $lang['log_viewer'] . '</caption>';
		$display .= '<tr>';
		$display .= '<th>' . $lang['id'] . '</th>';
		$display .= '<th>' . $lang['date'] . '</th>';
		$display .= '<th>' . $lang['user_ip'] . '</th>';
		$display .= '<th>' . $lang['user_manager_user_name'] . '</th>';
		$display .= '<th>' . $lang['action'] . '</th>';
		$display .= '</tr>';
		$count = 0;
		while (!$recordSet->EOF) {
			// alternate the colors
			if ($count == 0) {
				$count = 1;
			}else {
				$count = 0;
			}
			$log_id = $misc->make_db_unsafe($recordSet->fields['activitylog_id']);
			$log_date = $recordSet->UserTimeStamp($recordSet->fields['activitylog_log_date'], 'D M j G:i:s T Y');
			$log_action = $misc->make_db_unsafe($recordSet->fields['activitylog_action']);
			$log_ip = $misc->make_db_unsafe($recordSet->fields['activitylog_ip_address']);
			$sqlUser = 'SELECT userdb_user_first_name, userdb_user_last_name FROM '.$config['table_prefix'].'userdb WHERE userdb_id ='.$recordSet->fields['userdb_id'];
			$recordSet2 = $conn->execute($sqlUser);
			if ($recordSet2 === false) {
				$misc->log_error($sqlUser);
			}
			$first_name = $misc->make_db_unsafe($recordSet2->fields['userdb_user_first_name']);
			$last_name = $misc->make_db_unsafe($recordSet2->fields['userdb_user_last_name']);
			$display .= '<tr>';
			$display .= '<td class="shade_' . $count . '">' . $log_id . '</td>';
			$display .= '<td class="shade_' . $count . '">' . $log_date . '</td>';
			$display .= '<td class="shade_' . $count . '">' . $log_ip .'</td>';
			$display .= '<td class="shade_' . $count . '">' . $last_name.', '.$first_name.'</td>';
			$display .= '<td class="shade_' . $count . '">' . $log_action . '</td>';
			$display .= '</tr>';
			$recordSet->MoveNext();
		} // end while
		$display .= '</table>';
		return $display;
	}


	function clear_log()
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$display .= "<h3>$lang[log_delete]</h3>";
// Check for Admin privs before doing anything
		if ($_SESSION['admin_privs'] == "yes") {
			// find the number of log items
			$sql = "TRUNCATE TABLE " . $config['table_prefix'] . "activitylog";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
				$display .= "$lang[log_clear_error]";
			} else {
			$display .= "$lang[log_cleared]";
			$misc->log_action($lang['log_reset']);
			}
		} else {
		$display .= "$lang[clear_log_need_privs]";
		}
		$display .= '<br /><a href="' . $config['baseurl'] . '/admin/index.php?action=view_log">' . $lang['admin_view_log'] . '</a>';
		return $display;
	}
// END CLEAR LOG FUNCTION





}
?>