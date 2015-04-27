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
class membersfavorites {
	function delete_favorites()
	{
		global $config, $lang, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		login::loginCheck('Member');
		if (!isset($_GET['listingID'])) {
			$display .= '<a href="' . $config['baseurl'] . '/index.php">' . $lang['perhaps_you_were_looking_something_else'] . '</a>';
		}elseif ($_GET['listingID'] == '') {
			$display .= '<a href="' . $config['baseurl'] . '/index.php">' . $lang['perhaps_you_were_looking_something_else'] . '</a>';
		}else {
			$userID = $misc->make_db_safe($_SESSION['userID']);
			$listingID = $misc->make_db_safe($_GET['listingID']);
			$sql = "DELETE FROM " . $config['table_prefix'] . "userfavoritelistings WHERE userdb_id = $userID AND listingsdb_id = $listingID";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$display .= '<br />' . $lang['listing_deleted_from_favorites'];
			$display .= membersfavorites::view_favorites();
		}
		return $display;
	} // End function delete_favorites
	function addtofavorites()
	{
		global $config, $lang, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$security = login::loginCheck('Member');
		if ($security === true) {
			ob_start();
			$display = '';
			if ($_GET['listingID'] == "") {
				$display .= '<a href="' . $config['baseurl'] . '/index.php">' . $lang['perhaps_you_were_looking_something_else'] . '</a>';
			}else {
				$userID = $misc->make_db_safe($_SESSION['userID']);
				$listingID = $misc->make_db_safe($_GET['listingID']);
				$sql = "SELECT * FROM " . $config['table_prefix'] . "userfavoritelistings WHERE userdb_id = $userID AND listingsdb_id = $listingID";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$num_columns = $recordSet->RecordCount();
				if ($num_columns == 0) {
					$sql = "INSERT INTO " . $config['table_prefix'] . "userfavoritelistings (userdb_id, listingsdb_id) VALUES ($userID, $listingID)";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
					echo '<br />' . $lang['listing_added_to_favorites'];
				}else {
					echo '<br />' . $lang['listing_already_in_favorites'];
				}
			}
			include_once(dirname(__FILE__) . '/listing.inc.php');
			echo listing_pages::listing_view();
			$display = ob_get_contents();
			ob_end_clean();
			return $display;
		}else {
			return $security;
		}
	} // End function add_to_favorites()
	function view_favorites()
	{
		global $config, $lang, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$security = login::loginCheck('Member');
		if ($security === true) {
			$display = '';
			$display .= '<h3>' . $lang['favorite_listings'] . '</h3>';
			$userID = $misc->make_db_safe($_SESSION['userID']);
			$sql = "SELECT listingsdb_id FROM " . $config['table_prefix'] . "userfavoritelistings WHERE userdb_id = $userID";
			$recordSet = $conn->Execute($sql);
			if ($recordSet == false) {
				$misc->log_error($sql);
			}
			$num_columns = $recordSet->RecordCount();
			if ($num_columns == 0) {
				$display .= $lang['no_listing_in_favorites'] . '<br /><br />';
			}else {
				$recordNum = 0;
				$listings = '';
				while (!$recordSet->EOF) {
					if ($recordNum == 0) {
						$listings .= $recordSet->fields['listingsdb_id'];
					}else {
						$listings .= "," . $recordSet->fields['listingsdb_id'];
					}
					$recordNum++;
					$recordSet->MoveNext();
				}
				$_GET['listing_id'] = $listings;
				require_once($config['basepath'] . '/include/search.inc.php');
				$search = new search_page();
				$display .= $search->search_results();
			} // End else
			return $display;
		}else {
			return $security;
		}
	} // End Function view_favorites()
} // End cladd membersfavorites extends members

?>