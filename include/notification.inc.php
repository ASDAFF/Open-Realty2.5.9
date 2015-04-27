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
class notification {
	function NotifyUsersOfAllNewListings(){
		global $conn, $lang, $config;
		$display = '';
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/search.inc.php');
		//Get Last Notification Timestamp
		$sql = 'SELECT controlpanel_notification_last_timestamp FROM '. $config['table_prefix_no_lang'] . 'controlpanel';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$last_timestamp = $conn->UnixTimeStamp($recordSet->fields['controlpanel_notification_last_timestamp']);
		//echo 'Timestamp'.$last_timestamp;
		$display .= 'Sending New Listing Notifications since '.date(DATE_RFC822,$last_timestamp)."<br />\r\n";
		$current_timestamp = time();
		$notify_count = 0;
		$sql = "SELECT " . $config['table_prefix'] . "usersavedsearches.userdb_id, usersavedsearches_title, usersavedsearches_query_string, usersavedsearches_notify, userdb_user_name, userdb_emailaddress
				FROM " . $config['table_prefix'] . "userdb , " . $config['table_prefix'] . "usersavedsearches
				WHERE " . $config['table_prefix'] . "userdb.userdb_id = " . $config['table_prefix'] . "usersavedsearches.userdb_id AND usersavedsearches_notify = 'yes'";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}

		while (!$recordSet->EOF) {
			$query_string = $misc->make_db_unsafe($recordSet->fields['usersavedsearches_query_string']);
			$user_id = $recordSet->fields['userdb_id'];
			$search_title = $misc->make_db_unsafe($recordSet->fields['usersavedsearches_title']);
			$email = $misc->make_db_unsafe($recordSet->fields['userdb_emailaddress']);
			$user_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_name']);
			$display .= 'Checking Notifications for Saved Search "'.$search_title.'" for '.$user_name."<br />\r\n";
			// Break Quesry String up into $_GET variables.
			unset($_GET);
			$query_string = urldecode($query_string);
			$criteria = explode('&', $query_string);
			foreach ($criteria as $crit) {
				if ($crit != '') {
					$pieces = explode('=', $crit);
					$pos = strpos($pieces[0], '[]');
					if ($pos !== false) {
						$name = substr($pieces[0], 0, -2);
						$_GET[$name][] = $pieces[1];
					}else {
						$_GET[$pieces[0]] = $pieces[1];
					}
				}
			}
			if (!isset($_GET)) {
				$_GET[] = '';
			}
			$_GET['listing_last_modified_greater']=$last_timestamp;
			$matched_listing_ids = search_page::search_results(true);
			if (count($matched_listing_ids) >= 1) {
				//print_r($matched_listing_ids);
				//Get User Details
				//Now that we have a list of the listings, render the template
				$template = $this->renderNotifyListings($matched_listing_ids,$search_title,$user_name,$email);
				$display .= '<span class=redtext">Sent Listing Notification to '.$user_name .'&lt;'.$email.'&gt; for listings '.implode(',',$matched_listing_ids)."</span><br />\r\n";
				// Send Mail
				if (isset($config['site_email']) && $config['site_email'] != '') {
					$sender_email = $config['site_email'];
				} else {
					$sender_email = $config['admin_email'];
				}
				$subject = $lang['new_listing_notify'].$search_title;
				$sent = $misc->send_email($config['admin_name'], $sender_email, $email, $template, $subject,TRUE,TRUE);
			}

			$recordSet->MoveNext();
		} // while
		//Swt Last Notification Timestamp
		$db_timestamp = $conn->DBTimeStamp($current_timestamp);
		$sql = 'UPDATE '. $config['table_prefix_no_lang'] . 'controlpanel SET controlpanel_notification_last_timestamp = '.$db_timestamp;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$display .= "Finish Sending Notifications<br />\r\n";
		return $display;
	}
	function renderNotifyListings($listingIDArray,$search_title,$user_name,$email)
	{
		global $conn, $lang, $config, $db_type, $current_ID;
		//Load the Core Template class and the Misc Class
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$page = new page_user();
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/listing.inc.php');
		$listingclass = new listing_pages();
		//Declare an empty display variable to hold all output from function.
		$display = '';
		//If We have a $current_ID save it
		$old_current_ID='';
		if ($current_ID != ''){
			$old_current_ID = $current_ID;
		}


		//Load the Notify Listing Template specified in the Site Config
			$page->load_page($config['template_path'] . '/' . $config['notify_listings_template']);

// Determine if the template uses rows.
// First item in array is the row conent second item is the number of block per block row
			$notify_template_row = $page->get_template_section_row('notify_listing_block_row');
			if (is_array($notify_template_row)) {
				$row = $notify_template_row[0];
				$col_count = $notify_template_row[1];
				$user_rows = true;
				$x = 1;
//Create an empty array to hold the row conents
				$new_row_data = array();
			}else {
				$user_rows = false;
			}
			$notify_template_section = '';
			foreach($listingIDArray as $current_ID){
				if ($user_rows == true && $x > $col_count) {
//We are at then end of a row. Save the template section as a new row.
					$new_row_data[] = $page->replace_template_section('notify_listing_block', $notify_template_section,$row);
//$new_row_data[] = $notify_template_section;
					$notify_template_section = $page->get_template_section('notify_listing_block');
					$x=1;
				}else {
					$notify_template_section .= $page->get_template_section('notify_listing_block');
				}
				$listing_title = $listingclass->get_title($current_ID);
				if ($config['url_style'] == '1') {
					$notify_url = $config['baseurl'] . '/index.php?action=listingview&amp;listingID=' . $current_ID; // #####
				}else {
					$url_title = str_replace("/", "", $listing_title);
					$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
					$notify_url = $config['baseurl'] . '/listing-' . misc::urlencode_to_sef($url_title) . '-' . $current_ID . '.html'; // #####
				}
				$notify_template_section = $page->replace_listing_field_tags($current_ID, $notify_template_section);
				$notify_template_section = $page->replace_listing_field_tags($current_ID, $notify_template_section);
				$notify_template_section = $page->parse_template_section($notify_template_section, 'notify_url', $notify_url);
				$notify_template_section = $page->parse_template_section($notify_template_section, 'listingid', $current_ID);

// Setup Image Tags
				$sql2 = "SELECT listingsimages_thumb_file_name,listingsimages_file_name
					FROM " . $config['table_prefix'] . "listingsimages
					WHERE (listingsdb_id = $current_ID)
					ORDER BY listingsimages_rank";
				$recordSet2 = $conn->SelectLimit($sql2, 1, 0);
				if ($recordSet2 === false) {
					$misc->log_error($sql2);
				}
				if ($recordSet2->RecordCount() > 0) {
					$thumb_file_name = $misc->make_db_unsafe ($recordSet2->fields['listingsimages_thumb_file_name']);
					$file_name = $misc->make_db_unsafe($recordSet2->fields['listingsimages_file_name']);
					if ($thumb_file_name != "" && file_exists("$config[listings_upload_path]/$thumb_file_name")) {
	// gotta grab the thumbnail image size
						$imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
						$imagewidth = $imagedata[0];
						$imageheight = $imagedata[1];
						$shrinkage = $config['thumbnail_width'] / $imagewidth;
						$notify_thumb_width = $imagewidth * $shrinkage;
						$notify_thumb_height = $imageheight * $shrinkage;
						$notify_thumb_src = $config['listings_view_images_path'] . '/' . $thumb_file_name;
	// gotta grab the thumbnail image size
						$imagedata = GetImageSize("$config[listings_upload_path]/$file_name");
						$imagewidth = $imagedata[0];
						$imageheight = $imagedata[1];
						$notify_width = $imagewidth;
						$notify_height = $imageheight;
						$notify_src = $config['listings_view_images_path'] . '/' . $file_name;
					}
				} else {
						if ($config['show_no_photo'] == 1) {
							$imagedata = GetImageSize($config['basepath']."/images/nophoto.gif");
							$imagewidth = $imagedata[0];
							$imageheight = $imagedata[1];
							$shrinkage = $config['thumbnail_width'] / $imagewidth;
							$notify_thumb_width = $imagewidth * $shrinkage;
							$notify_thumb_height = $imageheight * $shrinkage;
							$notify_thumb_src = $config['baseurl'] . '/images/nophoto.gif';
							$notify_width = $notify_thumb_width;
							$notify_height = $notify_thumb_height;
							$notify_src = $config['baseurl'] . '/images/nophoto.gif';
						} else {
							$notify_thumb_width = '';
							$notify_thumb_height = '';
							$notify_thumb_src = '';
							$notify_width = '';
							$notify_height = '';
							$notify_src = '';
						}
					}
				if (!empty($notify_thumb_src)) {
					$notify_template_section = $page->parse_template_section($notify_template_section, 'notify_thumb_src', $notify_thumb_src);
					$notify_template_section = $page->parse_template_section($notify_template_section, 'notify_thumb_height', $notify_thumb_height);
					$notify_template_section = $page->parse_template_section($notify_template_section, 'notify_thumb_width', $notify_thumb_width);
					$notify_template_section = $page->cleanup_template_block('notify_img', $notify_template_section);
				} else {
					$notify_template_section = $page->remove_template_block('notify_img', $notify_template_section);
				}
				if (!empty($notify_src)) {
								$notify_template_section = $page->parse_template_section($notify_template_section, 'notify_large_src', $notify_src);
								$notify_template_section = $page->parse_template_section($notify_template_section, 'notify_large_height', $notify_height);
								$notify_template_section = $page->parse_template_section($notify_template_section, 'notify_large_width', $notify_width);
								$notify_template_section = $page->cleanup_template_block('notify_img_large', $notify_template_section);
				}else {
								$notify_template_section = $page->remove_template_block('notify_img_large', $notify_template_section);
				}
				if ($user_rows == true) {
					$x++;
				}
			}
			if ($user_rows == true) {
				$notify_template_section = $page->cleanup_template_block('notify_listing', $notify_template_section);
				$new_row_data[] = $page->replace_template_section('notify_listing_block', $notify_template_section,$row);
				$replace_row = '';
				foreach ($new_row_data as $rows){
					$replace_row .= $rows;
				}
				$page->replace_template_section_row('notify_listing_block_row', $replace_row);
			}else {
				$page->replace_template_section('notify_listing_block', $notify_template_section);
			}
			$page->replace_permission_tags();
			$page->replace_urls();
			$page->auto_replace_tags();
			$page->replace_lang_template_tags();
			$display .= $page->return_page();

		$current_ID='';
		if ($old_current_ID != ''){
			 $current_ID= $old_current_ID;
		}
		return $display;
	}
}
?>