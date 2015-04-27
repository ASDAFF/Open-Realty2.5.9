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
class listing_editor {
	var $debug = false;
	function notify_new_listing($listingID)
	{
		global $conn, $lang, $config;
		$display = '';
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/search.inc.php');
		$notify_count = 0;
		$sql = "SELECT userdb_id, usersavedsearches_title, usersavedsearches_query_string, usersavedsearches_notify FROM " . $config['table_prefix'] . "usersavedsearches WHERE usersavedsearches_notify = 'yes'";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		while (!$recordSet->EOF) {
			$query_string = $misc->make_db_unsafe($recordSet->fields['usersavedsearches_query_string']);
			$user_id = $recordSet->fields['userdb_id'];
			$search_title = $misc->make_db_unsafe($recordSet->fields['usersavedsearches_title']);
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
			$matched_listing_ids = search_page::search_results(true);
			if (in_array($listingID, $matched_listing_ids)) {
				// Listing Matches Search
				$sql = "SELECT userdb_user_name, userdb_emailaddress FROM " . $config['table_prefix'] . "userdb WHERE userdb_id = " . $user_id;
				$recordSet2 = $conn->Execute($sql);
				if ($recordSet2 === false) {
					$misc->log_error($sql);
				}
				$email = $misc->make_db_unsafe($recordSet2->fields['userdb_emailaddress']);
				$user_name = $misc->make_db_unsafe($recordSet2->fields['userdb_user_name']);
				$message = $lang['automated_email'] . "\r\n\r\n\r\n" . date("F j, Y, g:i:s a") . "\r\n\r\n" . $lang['new_listing_notify_long'] . "'" . $search_title . "'.\r\n\r\n" . $lang['click_on_link_to_view_listing'] . "\r\n\r\n$config[baseurl]/index.php?action=listingview&listingID=" . $listingID . "\r\n\r\n\r\n" . $lang['click_to_view_saved_searches'] . "\r\n\r\n$config[baseurl]/index.php?action=view_saved_searches\r\n\r\n\r\n" . $lang['automated_email'] . "\r\n";
				// Send Mail
				if (isset($config['site_email']) && $config['site_email'] != '') {
					$sender_email = $config['site_email'];
				} else {
					$sender_email = $config['admin_email'];
				}
				$subject = $lang['new_listing_notify'].$search_title;
				$sent = $misc->send_email($config['admin_name'], $sender_email, $email, $message, $subject);
				$notify_count++;
			}
			$recordSet->MoveNext();
			if ($notify_count > 0) {
				$display .= $lang['new_listing_email_sent'] . $notify_count . $lang['new_listing_email_users'] . '<br />';
			}
		} // while
		return $display;
	}
	function add_listing_logic()
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// First find out how many property classes exist.
		$sql = 'SELECT count(class_id) as num_classes FROM ' . $config['table_prefix'] . 'class';
		$recordSet = $conn->Execute($sql);
		if (!$recordSet) {
			$misc->log_error($sql);
		}
		$class_count = $recordSet->fields['num_classes'];
		if ($class_count > 1) {
			// Multiple Classes Exist show new search page.
			return listing_editor::add_listing_property_class();
		}else {
			// Load the only class id and then load the add_listing page for the user.
			$sql = 'SELECT class_id FROM ' . $config['table_prefix'] . 'class';
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			$_POST['property_class'][] = $recordSet->fields['class_id'];
			return listing_editor::add_listing();
		}
	}
	function add_listing_property_class()
	{
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		if (isset($_POST['property_class']) && count($_POST['property_class']) > 0) {
			return listing_editor::add_listing();
		}
		$display = '';
		$display .= '<span class="section_header">' . $lang['admin_menu_add_a_listing'] . '</span><br /><br />';
		$display .= '<form action="index.php?action=add_listing_property_class" method="post">';
		$display .= '<fieldset><legend>' . $lang['admin_add_listing_select_property_class'] . '</legend>';

		$display .= '<select name="property_class[]"';
		if ($config["multiple_pclass_selection"] == '1') {
			$display .= ' multiple="multiple" size="5"';
		}
		$display .= '>';
		// get list of all property clases
		$sql = 'SELECT class_name, class_id FROM ' . $config['table_prefix'] . 'class ORDER BY class_rank';
		$recordSet = $conn->Execute($sql);
		if (!$recordSet) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF()) {
			$class_id = $recordSet->fields['class_id'];
			$class_name = $recordSet->fields['class_name'];
			$display .= '<option value="' . $class_id . '" >' . $class_name . '</option>';
			$recordSet->MoveNext();
		}
		$display .= '</select> <input type="submit" value="' . $lang['submit'] . '" /></fieldset></form>';
		return $display;
	}
	function add_listing()
	{
		@set_time_limit(1500);
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/forms.inc.php');
		$forms = new forms();
		require_once($config['basepath'] . '/include/listing.inc.php');
		$listing = new listing_pages();
		$display = '';
		$display .= '<span class="section_header">' . $lang['admin_menu_add_a_listing'] . '</span>';
		if (isset($_POST['action']) && $_POST['action'] == "create_new_listing") {
			// Check Number of Listings User has
			if (isset($_POST['or_owner'])) {
				$or_owner = $misc->make_db_safe($_POST['or_owner']);

				$sql = 'SELECT count(listingsdb_id) as listing_count FROM ' . $config['table_prefix'] . 'listingsdb WHERE userdb_id = ' . $or_owner;
			}else {
				$sql = 'SELECT count(listingsdb_id) as listing_count FROM ' . $config['table_prefix'] . 'listingsdb WHERE userdb_id = ' . $_SESSION['userID'];
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$listing_count = $recordSet->fields['listing_count'];
			// Get User Listing Limit
			if (isset($_POST['or_owner'])) {
				$or_owner = $misc->make_db_safe($_POST['or_owner']);
				$sql = 'SELECT userdb_limit_listings FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $or_owner;
			}else {
				$sql = 'SELECT userdb_limit_listings FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $_SESSION['userID'];
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$listing_limit = $recordSet->fields['userdb_limit_listings'];

			//Ok Decide if user can have more listings
			if (($listing_count >= $listing_limit) && ($listing_limit != '-1')) {
				$display .= '<br />';
				$display .= '<!-- ' . $listing_count . ' >= ' . $listing_limit . ' -->';
				$display .= $lang['admin_listing_limit_reached'];
			}else {
				// creates a new listing
				if ($_POST['title'] == "") {
					$display .= "<p>$lang[admin_new_listing_enter_a_title]</p>";
					$display .= "<form><input type=\"button\" value=\"$lang[back_button_text]\" onclick=\"history.back()\" /></form>";
				} // end if
				else {
					$pass_the_form = $forms->validateForm('listingsformelements',$_POST['property_class']);
					if ($pass_the_form != "Yes") {
						// if we're not going to pass it, tell that they forgot to fill in one of the fields
						foreach ($pass_the_form as $k => $v) {
							if ($v == 'REQUIRED') {
								$display .= "<p class=\"redtext\">$k: $lang[required_fields_not_filled]</p>";
							}
							if ($v == 'TYPE') {
								$display .= "<p class=\"redtext\">$k: $lang[field_type_does_not_match]</p>";
							}
						}
						$display .= "<form><input type=\"button\" value=\"$lang[back_button_text]\" onclick=\"history.back()\" /></form>";
					}else {
						$title = $misc->make_db_safe($_POST['title']);
						$notes = $misc->make_db_safe($_POST['notes']);
						$mlsexport = $misc->make_db_safe($_POST['mlsexport']);
						if (isset($_POST['or_owner'])) {
							$new_listing_owner = $_POST['or_owner'];
							$sql_new_listing_owner = $misc->make_db_safe($_POST['or_owner']);
						} else {
							$new_listing_owner = $_SESSION['userID'];
							$sql_new_listing_owner = $misc->make_db_safe($_SESSION['userID']);
						}
						// check to see if moderation is turned on...
						if ($config['moderate_listings'] == false) {
							$set_active = "yes";
						}else {
							$set_active = "no";
						}
						if (isset($_POST['active'])) {
						$set_active = $_POST['active'];
						}
						// create the account with the random number as the password
						$expiration_date = mktime (0, 0, 0, date("m") , date("d") + $config['days_until_listings_expire'], date("Y"));
						$sql = "INSERT INTO " . $config['table_prefix'] . "listingsdb (listingsdb_title, listingsdb_notes, userdb_id, listingsdb_active, listingsdb_mlsexport, listingsdb_creation_date, listingsdb_last_modified, listingsdb_expiration, listingsdb_hit_count, listingsdb_featured) VALUES ($title, $notes,  $sql_new_listing_owner, '$set_active', $mlsexport, " . $conn->DBDate(time()) . "," . $conn->DBTimeStamp(time()) . "," . $conn->DBDate($expiration_date) . ",0,'no')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						} else {
							$new_listing_id = $conn->Insert_ID();
						} // end while
						// Add Listing to the property class system.
						foreach($_POST['property_class'] as $class_id) {
							$sql = 'INSERT INTO ' . $config['table_prefix_no_lang'] . 'classlistingsdb (listingsdb_id, class_id) VALUES(' . $new_listing_id . ',' . $class_id . ')';
							$recordSet = $conn->Execute($sql);
							if ($recordSet === false) {
								$misc->log_error($sql);
							}
						}
						// now that that's taken care of, it's time to insert all the rest
						// of the variables into the database
							$message = listing_editor::updateListingsData($new_listing_id, $new_listing_owner);
						if ($message == "success") {
							$display .= "<p>$lang[admin_new_listing_created], $_SESSION[username]</p>";
							if ($config['moderate_listings'] === "1") {
								// if moderation is turned on...
								$display .= "<p>$lang[admin_new_listing_moderated]</p>";
							}
							if (isset($_POST['or_owner'])) {
								$display .= "<p><a href=\"index.php?action=edit_listings&amp;edit=$new_listing_id\">$lang[you_may_now_edit_the_listing]</a></p>";
							}else {
								$display .= "<p><a href=\"index.php?action=edit_my_listings&amp;edit=$new_listing_id\">$lang[you_may_now_edit_your_listing]</a></p>";
							}
							$display .= "<br /><p>$lang[admin_additional_steps]</p>";
						$display .= '<form action="index.php?action=edit_listing_images" method="post" name="edit_listing_images"><input type="hidden" name="edit" value="' . $new_listing_id . '" /><a href="javascript:document.edit_listing_images.submit()">' . $lang['upload_images'] . '</a></form>';
						$display .= '<br />';
						if ($_SESSION['admin_privs'] == "yes" || $_SESSION['havevtours'] == "yes") {
							$display .= '<form action="index.php?action=edit_vtour_images" method="post" name="edit_vtour_images"><input type="hidden" name="edit" value="' . $new_listing_id . '" /><a href="javascript:document.edit_vtour_images.submit()">' . $lang['upload_vtours'] . '</a></form>';
							$display .= '<br />';
						}
						if ($_SESSION['admin_privs'] == "yes" || $_SESSION['havefiles'] == "yes") {
							$display .= '<form action="index.php?action=edit_listing_files" method="post" name="edit_listing_files"><input type="hidden" name="edit" value="' . $new_listing_id . '" /><a href="javascript:document.edit_listing_files.submit()">' . $lang['upload_files'] . '</a></form>';
							$display .= '<br />';
						}
							$misc->log_action ("$lang[log_created_listing] $new_listing_id");
							if ($config['email_notification_of_new_listings'] === "1") {
								// if the site admin should be notified when a new listing is added
								global $config, $lang;
								$agent_email=$listing->getListingEmail($new_listing_id,true);
								$agent_first_name=$listing->getListingAgentFirstName($new_listing_id);
								$agent_last_name=$listing->getListingAgentLastName($new_listing_id);
								$message = $_SERVER['REMOTE_ADDR'] . " -- " . date("F j, Y, g:i:s a") . "\r\n\r\n$lang[admin_new_listing]:\r\n$config[baseurl]/admin/index.php?action=edit_listings&edit=$new_listing_id\r\n";
								$header = "From: " . $agent_first_name ." ". $agent_last_name . " <" . $agent_email . ">\r\n";
								$header .= "X-Sender: $config[admin_email]\r\n";
								$header .= "Return-Path: $config[admin_email]\r\n";
								$sent = $misc->send_email($agent_first_name ." ". $agent_last_name, $agent_email, $config['admin_email'], $message, $lang['admin_new_listing']);
							} // end if
						} // end if
						else {
							$display .= "<p>$lang[alert_site_admin]</p>";
						} // end else
					} // end $pass_the_form == "Yes"
				} // end else
			} //End if (($listing_count >= $listing_limit) && ($listing_limit !== -1))
		} // end if $action == "create_new_listing"
		else {
			// Check Number of Listings User has
			$sql = 'SELECT count(listingsdb_id) FROM ' . $config['table_prefix'] . 'listingsdb WHERE userdb_id = ' . $_SESSION['userID'];
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$listing_count = $recordSet->fields[0];
			// Get User Listing Limit
			$sql = 'SELECT userdb_limit_listings FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $_SESSION['userID'];
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$listing_limit = $recordSet->fields[0];
			$display .= '<!-- ' . $listing_count . ' >= ' . $listing_limit . ' -->';
			if (($listing_count >= $listing_limit) && ($listing_limit !== '-1')) {
				$display .= '<br />';
				$display .= $lang['admin_listing_limit_reached'];
			}else {
				//START FORM VALIDATION
				if (isset($_POST['property_class'])) {
					$class_sql = '';
					foreach($_POST['property_class'] as $class_id) {
						if (empty($class_sql)) {
							$class_sql .= ' class_id = ' . $class_id;
						}else {
							$class_sql .= ' OR class_id = ' . $class_id;
						}
						$display .= '<input type="hidden" name="property_class[]" value="' . $class_id . '" />';
					}
					$pclass_list = '';
					$sql = "SELECT DISTINCT(listingsformelements_id) FROM  " . $config['table_prefix_no_lang'] . "classformelements WHERE " . $class_sql;
					$recordSet = $conn->execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					} while (!$recordSet->EOF) {
						if (empty($pclass_list)) {
							$pclass_list .= $recordSet->fields['listingsformelements_id'];
						}else {
							$pclass_list .= ',' . $recordSet->fields['listingsformelements_id'];
						}
						$recordSet->Movenext();
					}
					if ($pclass_list == '') {
						$pclass_list = 0;
					}
					$sql = "SELECT listingsformelements_field_type, listingsformelements_field_name, listingsformelements_field_caption, listingsformelements_default_text, listingsformelements_field_elements, listingsformelements_required, listingsformelements_field_length, listingsformelements_tool_tip from " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_id IN (" . $pclass_list . ") ORDER BY listingsformelements_rank, listingsformelements_field_name";
				}else {
					$sql = "SELECT listingsformelements_field_type, listingsformelements_field_name, listingsformelements_field_caption, listingsformelements_default_text, listingsformelements_field_elements, listingsformelements_required, listingsformelements_field_length, listingsformelements_tool_tip from " . $config['table_prefix'] . "listingsformelements ORDER BY listingsformelements_rank, listingsformelements_field_name";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$display.="\r\n<script type=\"text/javascript\" >\r\n";
				$display.="<!--\r\n";
				$display.="function validate_form()\r\n";
				$display.="{\r\n";
				$display.="var msg=\"\"\r\n";
				$display.="valid = true;\r\n";
				$display.="if ( document.addlisting.title.value == \"\" )\r\n";
    			$display.="{\r\n";
				$display.="msg += '$lang[forgot_field] $lang[admin_listings_editor_title] $lang[admin_template_editor_field].\\r\\n';\r\n";
				$display.="valid = false;\r\n";
				$display.="}\r\n";
				while (!$recordSet->EOF) {
				$field_name = $recordSet->fields['listingsformelements_field_name'];
				$field_caption = $recordSet->fields['listingsformelements_field_caption'];
				$required = $recordSet->fields['listingsformelements_required'];
				if ($required == 'Yes')
				{
				$display.="if ( document.addlisting.$field_name.value == \"\" )\r\n";
    			$display.="{\r\n";
				$display.="msg += '$lang[forgot_field] $field_caption $lang[admin_template_editor_field].\\r\\n';\r\n";
				$display.="valid = false;\r\n";
				$display.="}\r\n";
				}
				$recordSet->MoveNext();
				}
				$display.="if (msg != \"\")\r\n";
				$display.="{\r\n";
				$display.="alert (msg);";
				$display.="}\r\n";
				$display.="return valid;\r\n";
				$display.="}\r\n";
				$display.="//-->\r\n";
				$display.="</script>\r\n";
				//END FORM VALIDATION
				$display .= '<form name="addlisting" action="index.php?action=add_listing" method="post" onsubmit="return validate_form ( );">';
				$display .= '<input type="hidden" name="action" value="create_new_listing" />';
				$display .= '<table class="form_main">';
				$display .= '<tr>';
				$display .= '<td align="right" class="row_main"><b>' . $lang['admin_listings_editor_title'] . '<span class="required">*</span></b></td>';
				$display .= '<td align="left" class="row_main"> <input type="text" name="title" /></td>';
				$display .= '</tr>';
					// Display Agent selection Option to assign listing
					if ($_SESSION['admin_privs'] == "yes" || $_SESSION['edit_all_listings'] == "yes") {
						$display .= '<tr><td align="right"><b>' . $lang['listing_editor_listing_agent'] . ':</b></td>';
						$display .= '<td align="left" class="row_main"><select name="or_owner" size="1">';
						// find the name of the agent listed as ID in $edit_or_owner
						$sql = "SELECT userdb_user_first_name, userdb_user_last_name FROM " . $config['table_prefix'] . "userdb WHERE (userdb_id = $_SESSION[userID])";
						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						// strip slashes so input appears correctly
						$agent_first_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_first_name']);
						$agent_last_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_last_name']);
						if ($_SESSION['admin_privs'] != "yes")
						{
						$display .= "<option value=\"$_SESSION[userID]\">$agent_last_name,$agent_first_name</option>";
						}
						// fill list with names of all agents
						$sql = "SELECT userdb_id, userdb_user_first_name, userdb_user_last_name FROM " . $config['table_prefix'] . "userdb where userdb_is_agent = 'yes' or userdb_is_admin = 'yes' ORDER BY userdb_user_last_name,userdb_user_first_name";
						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						while (!$recordSet->EOF) {
							// strip slashes so input appears correctly
							$agent_ID = $recordSet->fields['userdb_id'];
							$agent_first_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_first_name']);
							$agent_last_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_last_name']);
							if ($agent_ID == $_SESSION['userID']) {
							$display .= "<option value=\"$agent_ID\" selected=\"selected\">$agent_last_name,$agent_first_name</option>";
							}else {
							$display .= "<option value=\"$agent_ID\">$agent_last_name,$agent_first_name</option>";
							}
							$recordSet->MoveNext();
						}
						$display .= "</select></td>";
						$display .= '</tr>';
					}
					if ($config["show_notes_field"] == 1) {
						$display .= '<tr>';
						$display .= '<td align="right" class="row_main"><b>' . $lang['admin_listings_editor_notes'] . '</b><br /><div class="small">(' . $lang['admin_listings_editor_notes_note'] . ')</div></td>';
						$display .= '<td align="left" class="row_main"><textarea name="notes" cols="40" rows="6"></textarea></td>';
						$display .= '</tr>';
					} else {
						$display .= '<input type="hidden" name="notes" value="" />';
					}
				if ($config["export_listings"] == 1 && $_SESSION['export_listings'] == "yes") {
					$display .= '<tr>';
					$display .= '<td align="right" class="row_main"><b>' . $lang['admin_listings_editor_mlsexport'] . '</b><br /><div class="small">(' . $lang['admin_listings_editor_mlsexport'] . ')</div></td>';
					$display .= '<td align="left" class="row_main">';
					$display .= '<select size="1" name="mlsexport">';
					$display .= '<option value="no" selected="selected">' . $lang['no'] . '</option>';
					$display .= '<option value="yes">' . $lang['yes'] . '</option>';
					$display .= '</select>';
					$display .= '</td>';
					$display .= '</tr>';
				}else {
					$display .= '<input type="hidden" name="mlsexport" value="no" />';
				}
				// Determine which fields to show based on property class
				if (isset($_POST['property_class'])) {
					$class_sql = '';
					foreach($_POST['property_class'] as $class_id) {
						if (empty($class_sql)) {
							$class_sql .= ' class_id = ' . $class_id;
						}else {
							$class_sql .= ' OR class_id = ' . $class_id;
						}
						$display .= '<input type="hidden" name="property_class[]" value="' . $class_id . '" />';
					}
					$pclass_list = '';
					$sql = "SELECT DISTINCT(listingsformelements_id) FROM  " . $config['table_prefix_no_lang'] . "classformelements WHERE " . $class_sql;
					$recordSet = $conn->execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					} while (!$recordSet->EOF) {
						if (empty($pclass_list)) {
							$pclass_list .= $recordSet->fields['listingsformelements_id'];
						}else {
							$pclass_list .= ',' . $recordSet->fields['listingsformelements_id'];
						}
						$recordSet->Movenext();
					}
					if ($pclass_list == '') {
						$pclass_list = 0;
					}
					$sql = "SELECT listingsformelements_field_type, listingsformelements_field_name, listingsformelements_field_caption, listingsformelements_default_text, listingsformelements_field_elements, listingsformelements_required, listingsformelements_field_length, listingsformelements_tool_tip from " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_id IN (" . $pclass_list . ") ORDER BY listingsformelements_rank, listingsformelements_field_name";
				}else {
					$sql = "SELECT listingsformelements_field_type, listingsformelements_field_name, listingsformelements_field_caption, listingsformelements_default_text, listingsformelements_field_elements, listingsformelements_required, listingsformelements_field_length, listingsformelements_tool_tip from " . $config['table_prefix'] . "listingsformelements ORDER BY listingsformelements_rank, listingsformelements_field_name";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}while (!$recordSet->EOF) {
					$field_type = $recordSet->fields['listingsformelements_field_type'];
					$field_name = $recordSet->fields['listingsformelements_field_name'];
					$field_caption = $recordSet->fields['listingsformelements_field_caption'];
					$default_text = $recordSet->fields['listingsformelements_default_text'];
					$field_elements = $recordSet->fields['listingsformelements_field_elements'];
					$required = $recordSet->fields['listingsformelements_required'];
					$field_length = $recordSet->fields['listingsformelements_field_length'];
					$tool_tip = $recordSet->fields['listingsformelements_tool_tip'];
					$field_type = $misc->make_db_unsafe($field_type);
					$field_name = $misc->make_db_unsafe($field_name);
					$field_caption = $misc->make_db_unsafe($field_caption);
					$default_text = $misc->make_db_unsafe($default_text);
					$field_elements = $misc->make_db_unsafe($field_elements);
					$required = $misc->make_db_unsafe($required);
					$field_length = $misc->make_db_unsafe($field_length);
					$tool_tip = $misc->make_db_unsafe($tool_tip);
					$display .= $forms->renderFormElement($field_type, $field_name, $field_caption, $default_text, $field_elements, $required, $field_length, $tool_tip);
					$recordSet->MoveNext();
				} // end while
				$display .= $forms->renderFormElement("submit", "", "$lang[submit]", "", "", "");
				$display .= '<tr><td colspan="2" align="center" class="row_main">' . $lang['required_form_text'] . '</td></tr>';
				$display .= '</table>';
				$display .= '</form>';
			} //End
		} // end if
		return $display;
	}
	function updateListingsData ($listing_id, $or_owner)
	{
		// UPDATES THE LISTINGS INFORMATION
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$sql_listing_id = $misc->make_db_safe($listing_id);
		$sql = "DELETE FROM " . $config['table_prefix'] . "listingsdbelements WHERE listingsdb_id = $sql_listing_id";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		foreach ($_POST as $ElementIndexValue => $ElementContents) {
			// first, ignore all the stuff that's been taken care of above
			$sql2="SELECT listingsformelements_field_type FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_field_name='".$ElementIndexValue."'";
			$recordSet2=$conn->Execute($sql2);
			if ($recordSet2 === false) {
			$misc->log_error($sql2);
			}
			$field_type=$recordSet2->fields['listingsformelements_field_type'];
			if ($ElementIndexValue == "title" || $ElementIndexValue == "notes" || $ElementIndexValue == "action" || $ElementIndexValue == "PHPSESSID" || $ElementIndexValue == "edit" || $ElementIndexValue == "edit_active" || $ElementIndexValue == "edit_expiration" || $ElementIndexValue == "featured" || $ElementIndexValue == "pclass" || $ElementIndexValue == "send_notices") {
				// do nothing
			}
			// this is currently set up to handle two feature lists
			// it could easily handle more...
			// just write handlers for 'em
			elseif (is_array($ElementContents)) {
				// deal with checkboxes & multiple selects elements
				$feature_insert = "";
				foreach ($ElementContents as $feature_item) {
					$feature_insert = "$feature_insert||$feature_item";
				} // end while
				// now remove the first two characters
				$feature_insert_length = strlen($feature_insert);
				$feature_insert_length = $feature_insert_length - 2;
				$feature_insert = substr($feature_insert, 2, $feature_insert_length);
				$sql_ElementIndexValue = $misc->make_db_safe($ElementIndexValue);
				$sql_feature_insert = $misc->make_db_safe(html_entity_decode($feature_insert, ENT_COMPAT, $config['charset']));
				$sql_or_owner = $misc->make_db_safe($or_owner);
				$sql = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES ($sql_ElementIndexValue, $sql_feature_insert, $sql_listing_id, $sql_or_owner)";
				$recordSet = $conn->Execute($sql);
				if ($recordSet == false) {
					$misc->log_error($sql);
				}
			} // end elseif
			else {
				// process the form
				$returnValue = '';
				if ($field_type == 'price' && $ElementContents != '') {
					for($i = 0; $i < strlen($ElementContents); $i++) {
						if (ereg('[0-9]', substr($ElementContents, $i, 1))) {
							$returnValue .= substr($ElementContents, $i, 1);
						}
						if (ereg('[.]', substr($ElementContents, $i, 1))) {
							$i = strlen($ElementContents) + 1;
						}
					}
				}
				if ($field_type == 'date' && $ElementContents != '') {
					if ($config['date_format']==1) {
					$format="%m/%d/%Y";
					}
					elseif ($config['date_format']==2) {
					$format="%Y/%d/%m";
					}
					elseif ($config['date_format']==3) {
					$format="%d/%m/%Y";
					}
					$returnValue=$misc->parseDate($ElementContents,$format);
				}
				else
				{
				$returnValue=$ElementContents;
				}
				$sql_ElementIndexValue = $misc->make_db_safe($ElementIndexValue);
				if ($returnValue == '') {
					$sql_ElementContents = $misc->make_db_safe($ElementContents);
				}else {
					$sql_ElementContents = $misc->make_db_safe($returnValue);
				}
				$sql_listing_id = $misc->make_db_safe($listing_id);
				$sql_or_owner = $misc->make_db_safe($or_owner);

				$sql = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES ($sql_ElementIndexValue, $sql_ElementContents, $sql_listing_id, $sql_or_owner)";
				$recordSet = $conn->Execute($sql);
				if ($recordSet == false) {
					$misc->log_error($sql);
				}
			} // end else
		} // end while
		return "success";
	} // end function updateListingsData

	function edit_listings($only_my_listings = true)
	{
		global $conn, $lang, $config, $listingID;
		if ($only_my_listings == false) {
			$security = login::loginCheck('edit_all_listings', true);
		}else {
			$security = login::loginCheck('Agent', true);
		}
		$display = '';
		if ($security === true) {
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			require_once($config['basepath'] . '/include/forms.inc.php');
			$forms = new forms();
			require_once($config['basepath'] . '/include/class/template/core.inc.php');
			$page = new page_user();
			// $display .= '<span class="section_header">'.$lang['listings_editor'].'<span><br /><br />';
			if (!isset($_GET['delete'])) {
				$_GET['delete'] = '';
			}
			if ($_GET['delete'] != '') {
				if ($_SESSION['admin_privs'] == 'yes' || $_SESSION['edit_all_listings'] == 'yes') {
					listing_editor::delete_listing($_GET['delete'], false);
				} else {
					listing_editor::delete_listing($_GET['delete'], true);
				}
			}
			if (!isset($_POST['action'])) {
				$_POST['action'] = '';
			}
			if ($_POST['action'] == "update_listing") {
				if ($_SESSION['admin_privs'] == 'yes' || $_SESSION['edit_all_listings'] == 'yes') {
					$display .= listing_editor::update_listing(false);
				} else {
					$display .= listing_editor::update_listing(true);
				}
			} // end if $action == "update listing"
			if (!isset($_GET['edit'])) {
				$_GET['edit'] = '';
			}
			if(isset($_POST['lookup_field']) && isset($_POST['lookup_value'])){
				$_SESSION['edit_listing_qeb_lookup_field'] =$_POST['lookup_field'];
				$_SESSION['edit_listing_qeb_lookup_value'] =$_POST['lookup_value'];
			}
			if(isset($_SESSION['edit_listing_qeb_lookup_field']) && isset($_SESSION['edit_listing_qeb_lookup_value'])){
				if ($_SESSION['edit_listing_qeb_lookup_field'] != 'listingsdb_id') {
					$_POST['lookup_field'] =$_SESSION['edit_listing_qeb_lookup_field'];
					$_POST['lookup_value'] =$_SESSION['edit_listing_qeb_lookup_value'];
				}
			}
			if(isset($_POST['filter'])){
				$_SESSION['edit_listing_qeb_filter'] =$_POST['filter'];
			}
			if(isset($_SESSION['edit_listing_qeb_filter'])){
				$_POST['filter'] =$_SESSION['edit_listing_qeb_filter'];
			}
			if(isset($_POST['agent_filter'])){
				$_SESSION['edit_listing_qeb_agent_filter'] =$_POST['agent_filter'];
			}
			if(isset($_SESSION['edit_listing_qeb_agent_filter'])){
				$_POST['agent_filter'] =$_SESSION['edit_listing_qeb_agent_filter'];
			}
			if(isset($_POST['pclass_filter'])){
				$_SESSION['edit_listing_qeb_pclass_filter'] =$_POST['pclass_filter'];
			}
			if(isset($_SESSION['edit_listing_qeb_pclass_filter'])){
				$_POST['pclass_filter'] =$_SESSION['edit_listing_qeb_pclass_filter'];
			}
			if (isset($_POST['lookup_field']) && isset($_POST['lookup_value']) && $_POST['lookup_field'] == 'listingsdb_id' && $_POST['lookup_value'] != '') {
				$_GET['edit'] = intval($_POST['lookup_value']);
			}
			if($only_my_listings==TRUE){
				unset($_POST['agent_filter']);
			}
			if ($_GET['edit'] != "") {
				$edit = intval($_GET['edit']);
				// first, grab the listings's main info
				if ($only_my_listings == true) {
					$sql = "SELECT listingsdb_id, listingsdb_title, listingsdb_notes, userdb_id, listingsdb_last_modified, listingsdb_featured, listingsdb_active, listingsdb_mlsexport, listingsdb_expiration FROM " . $config['table_prefix'] . "listingsdb WHERE (listingsdb_id = $edit) AND (userdb_id = '$_SESSION[userID]')";
				}else {
					$sql = "SELECT listingsdb_id, listingsdb_title, listingsdb_notes, userdb_id, listingsdb_last_modified, listingsdb_featured, listingsdb_active, listingsdb_mlsexport, listingsdb_expiration FROM " . $config['table_prefix'] . "listingsdb WHERE (listingsdb_id = $edit)";
				}
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				if ($recordSet->RecordCount() > 0) {
					// collect up the main DB's various fields
					$listing_ID = $misc->make_db_unsafe ($recordSet->fields['listingsdb_id']);
					$edit_title = $misc->make_db_unsafe ($recordSet->fields['listingsdb_title']);
					$edit_notes = $misc->make_db_unsafe ($recordSet->fields['listingsdb_notes']);
					$edit_mlsexport = $misc->make_db_unsafe ($recordSet->fields['listingsdb_mlsexport']);
					$edit_or_owner = $recordSet->fields['userdb_id'];
					$last_modified = $recordSet->UserTimeStamp($recordSet->fields['listingsdb_last_modified'], 'D M j G:i:s T Y');
					$edit_featured = $recordSet->fields['listingsdb_featured'];
					$edit_active = $recordSet->fields['listingsdb_active'];
					$expiration = $recordSet->UserTimeStamp($recordSet->fields['listingsdb_expiration'], $config["date_format_timestamp"]);
					// now, display all that stuff
					$display .= '<table class="form_main">';
					$display .= '<tr>';
					$display .= '<td colspan="3" class="row_main">';
					if ($only_my_listings == true) {
					$display .= '<span class="section_header"><a href="index.php?action=edit_my_listings">' . $lang['listings_editor'] . '</a></span><br />';
					} else {
					$display .= '<span class="section_header"><a href="index.php?action=edit_listings">' . $lang['listings_editor'] . '</a></span><br />';
					}
					$display .= '<h3>' . $lang['admin_listings_editor_modify_listing'] . ' (<a href="' . $config['baseurl'] . '/index.php?action=listingview&amp;listingID=' . $listing_ID . '" target="_preview">' . $lang['preview'] . '</a>)</h3>';
					$display .= '</td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td valign="top" align="center" class="row_main">';
					$display .= '<b>' . $lang['images'] . '</b>';
					$display .= '<br />';
					$display .= '<hr width="75%" />';
					$display .= '<form action="index.php?action=edit_listing_images" method="post" name="edit_listing_images"><input type="hidden" name="edit" value="' . $_GET['edit'] . '" /><a href="javascript:document.edit_listing_images.submit()">' . $lang['edit_images'] . '</a></form>';
					$display .= '<br />';
					$sql = "SELECT listingsimages_caption, listingsimages_file_name, listingsimages_thumb_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsdb_id = $edit) ORDER BY listingsimages_rank";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					} while (!$recordSet->EOF) {
						$caption = $misc->make_db_unsafe ($recordSet->fields['listingsimages_caption']);
						$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_thumb_file_name']);
						$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
					// gotta grab the image size
					$thumb_imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
					$thumb_imagewidth = $thumb_imagedata[0];
					$thumb_imageheight = $thumb_imagedata[1];
					$thumb_max_width = $config['thumbnail_width'];
					$thumb_max_height = $config['thumbnail_height'];
					$resize_by = $config['resize_thumb_by'];
					$shrinkage = 1;
					if (($thumb_max_width == $thumb_imagewidth) || ($thumb_max_height == $thumb_imageheight)) {
					$thumb_displaywidth = $thumb_imagewidth;
					$thumb_displayheight = $thumb_imageheight;
					} else {
						if ($resize_by == 'width') {
							$shrinkage = $thumb_imagewidth / $thumb_max_width;
							$thumb_displaywidth = $thumb_max_width;
							$thumb_displayheight = round($thumb_imageheight / $shrinkage);
						} elseif ($resize_by == 'height') {
							$shrinkage = $thumb_imageheight / $thumb_max_height;
							$thumb_displayheight = $thumb_max_height;
							$thumb_displaywidth = round($thumb_imagewidth / $shrinkage);
						} elseif ($resize_by == 'both') {
							$thumb_displayheight = $thumb_max_height;
							$thumb_displaywidth = $thumb_max_width;
						}
					}
						$display .= "<a href=\"$config[listings_view_images_path]/$file_name\" target=\"_thumb\"> ";
						$display .= "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$thumb_displayheight\" width=\"$thumb_displaywidth\" alt=\"$thumb_file_name\" /></a><br /> ";
						$display .= "<b>$caption</b><br /><br />";
						$recordSet->MoveNext();
					} // end while
					$display .= '</td>';
					if ($_SESSION['admin_privs'] == "yes" || $_SESSION['havevtours'] == "yes") {
						$display .= '<td valign="top" align="center" class="row_main">';
						$display .= '<b>' . $lang['vtours'] . '</b>';
						$display .= '<br />';
						$display .= '<hr width="75%" />';
						$display .= '<form action="index.php?action=edit_vtour_images" method="post" name="edit_vtour_images"><input type="hidden" name="edit" value="' . $edit . '" /><a href="javascript:document.edit_vtour_images.submit()">' . $lang['edit_vtours'] . '</a></form>';
						$display .= '<br />';
						$sql = "SELECT vtourimages_caption, vtourimages_file_name, vtourimages_thumb_file_name FROM " . $config['table_prefix'] . "vtourimages WHERE (listingsdb_id = '$edit') ORDER BY  vtourimages_rank";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						} while (!$recordSet->EOF) {
							$caption = $misc->make_db_unsafe ($recordSet->fields['vtourimages_caption']);
							$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_thumb_file_name']);
							$file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_file_name']);
							$ext = substr(strrchr($file_name, '.'), 1);
							if ($ext == 'jpg') {
								// gotta grab the image size
								$imagedata = GetImageSize("$config[vtour_upload_path]/$thumb_file_name");
								$imagewidth = $imagedata[0];
								$imageheight = $imagedata[1];
								$shrinkage = $config['thumbnail_width'] / $imagewidth;
								$displaywidth = $imagewidth * $shrinkage;
								$displayheight = $imageheight * $shrinkage;
								$display .= "<a href=\"$config[vtour_view_images_path]/$file_name\" target=\"_thumb\">";
								$display .= "<img src=\"$config[vtour_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\" alt=\"$thumb_file_name\" /></a><br /> ";
								$display .= "<strong>$caption</strong><br /><br />";
								$recordSet->MoveNext();
							} // end if ext = jpg
							elseif ($ext == 'egg') {
								$display .= "<img src=\"$config[baseurl]/images/eggimage.gif\" alt=\"eggimage.gif\" /><br /> ";
								$recordSet->MoveNext();
							}else {
								$display .= $file_name . '<br />' . $lang['unsupported_vtour'] . '<br /><br />';
								$recordSet->MoveNext();
							}
						} // end while
						if ($_SESSION['admin_privs'] == "yes" || $_SESSION['havefiles'] == "yes") {
							$display .= '<br />';
						} else {
							$display .= '</td>';
						}
					}
// Place the Files list and edit files link on the edit listing page if we are allowed to have files.
					if ($_SESSION['admin_privs'] == "yes" || $_SESSION['havefiles'] == "yes") {
						if ($_SESSION['admin_privs'] == "yes" || $_SESSION['havevtours'] == "yes") {
							$display .= '<br />';
						} else {
							$display .= '<td valign="top" align="center" class="row_main">';
						}
						$display .= '<b>'.$lang['files'].'</b>';
						$display .= '<br />';
						$display .= '<hr width="75%" />';
						$display .= '<form action="index.php?action=edit_listing_files" method="post" name="edit_listing_files"><input type="hidden" name="edit" value="' . $_GET['edit'] . '" /><a href="javascript:document.edit_listing_files.submit()">' . $lang['edit_files'] . '</a></form>';
						$display .= '<br />';
						$sql = "SELECT listingsfiles_id, listingsfiles_caption, listingsfiles_file_name FROM " . $config['table_prefix'] . "listingsfiles WHERE (listingsdb_id = '$_GET[edit]')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						} while (!$recordSet->EOF) {
							$caption = $misc->make_db_unsafe ($recordSet->fields['listingsfiles_caption']);
							$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsfiles_file_name']);
							$file_id =  $misc->make_db_unsafe ($recordSet->fields['listingsfiles_id']);
							$iconext = substr(strrchr($file_name, '.'), 1);
							$iconpath = $config["file_icons_path"] . '/' . $iconext . '.png';
							if (file_exists($iconpath)) {
								$icon = $config["listings_view_file_icons_path"] . '/' . $iconext . '.png';
							} else {
								$icon = $config["listings_view_file_icons_path"] . '/default.png';
							}
								$file_download_url = 'index.php?action=create_download&amp;ID='.$edit.'&amp;file_id='.$file_id.'&amp;type=listing';
								$display .= '<a href="'.$config['baseurl'].'/'.$file_download_url.'" target="_thumb">';
								$display .= '<img src="'.$icon.'" height="'.$config["file_icon_height"].'" width="'.$config["file_icon_width"].'" alt="'.$file_name.'" /><br />';
								$display .= '<strong>'.$file_name.'</strong></a><br />';
								$display .= '<strong>'.$caption.'</strong><br /><br />';
								$recordSet->MoveNext();
						} // end while
						$display .= '</td>';
					}
					$display .= '<td class="row_main">';
				//START FORM VALIDATION
				if (isset($_POST['property_class'])) {
					$class_sql = '';
					foreach($_POST['property_class'] as $class_id) {
						if (empty($class_sql)) {
							$class_sql .= ' class_id = ' . $class_id;
						}else {
							$class_sql .= ' OR class_id = ' . $class_id;
						}
						$display .= '<input type="hidden" name="property_class[]" value="' . $class_id . '" />';
					}
					$pclass_list = '';
					$sql = "SELECT DISTINCT(listingsformelements_id) FROM  " . $config['table_prefix_no_lang'] . "classformelements WHERE " . $class_sql;
					$recordSet = $conn->execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					} while (!$recordSet->EOF) {
						if (empty($pclass_list)) {
							$pclass_list .= $recordSet->fields['listingsformelements_id'];
						}else {
							$pclass_list .= ',' . $recordSet->fields['listingsformelements_id'];
						}
						$recordSet->Movenext();
					}
					if ($pclass_list == '') {
						$pclass_list = 0;
					}
					$sql = "SELECT listingsformelements_field_type, listingsformelements_field_name, listingsformelements_field_caption, listingsformelements_default_text, listingsformelements_field_elements, listingsformelements_required from " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_id IN (" . $pclass_list . ") ORDER BY listingsformelements_rank, listingsformelements_field_name";
				}else {
					$sql = "SELECT listingsformelements_field_type, listingsformelements_field_name, listingsformelements_field_caption, listingsformelements_default_text, listingsformelements_field_elements, listingsformelements_required from " . $config['table_prefix'] . "listingsformelements ORDER BY listingsformelements_rank, listingsformelements_field_name";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$display.="\r\n<script type=\"text/javascript\" >\r\n";
				$display.="<!--\r\n";
				$display.="function validate_form()\r\n";
				$display.="{\r\n";
				$display.="var msg=\"\"\r\n";
				$display.="valid = true;\r\n";
				$display.="if ( document.update_listing.title.value == \"\" )\r\n";
    			$display.="{\r\n";
				$display.="msg += '$lang[forgot_field] $lang[admin_listings_editor_title] $lang[admin_template_editor_field].\\r\\n';\r\n";
				$display.="valid = false;\r\n";
				$display.="}\r\n";
				while (!$recordSet->EOF) {
				$field_name = $recordSet->fields['listingsformelements_field_name'];
				$field_caption = $recordSet->fields['listingsformelements_field_caption'];
				$required = $recordSet->fields['listingsformelements_required'];
				if ($required == 'Yes')
				{
				$display.="if ( document.update_listing.$field_name.value == \"\" )\r\n";
    			$display.="{\r\n";
				$display.="msg += '".html_entity_decode($lang[forgot_field])." $field_caption ".html_entity_decode($lang[admin_template_editor_field]).".\\r\\n';\r\n";
				$display.="valid = false;\r\n";
				$display.="}\r\n";
				}
				$recordSet->MoveNext();
				}
				$display.="if (msg != \"\")\r\n";
				$display.="{\r\n";
				$display.="alert (msg);";
				$display.="}\r\n";
				$display.="return valid;\r\n";
				$display.="}\r\n";
				$display.="//-->\r\n";
				$display.="</script>\r\n";
				//END FORM VALIDATION
					$display .= '<table>';
					if ($only_my_listings == true) {
						$display .= '<form name="update_listing" action="index.php?action=edit_my_listings&amp;edit=' . $_GET['edit'] . '" method="post" onsubmit="return validate_form ( );">';
					}else {
						$display .= '<form name="update_listing" action="index.php?action=edit_listings&amp;edit=' . $_GET['edit'] . '" method="post" onsubmit="return validate_form ( );">';
					}
					$display .= '<input type="hidden" name="action" value="update_listing">';
					$display .= '<input type="hidden" name="edit" value="' . $_GET['edit'] . '">';
					$display .= '<tr>';
					$display .= '<td align="right"><b>' . $lang['admin_listings_editor_title'] . ': <font color="red">*</font></b></td>';
					$display .= '<td align="left"> <input type="text" name="title" value="' . $edit_title . '"></td></tr>';
					// Display Property Classes
					$sql2 = 'SELECT class_id FROM ' . $config['table_prefix_no_lang'] . 'classlistingsdb WHERE listingsdb_id =' . $listing_ID;
					$recordSet2 = $conn->execute($sql2);
					if ($recordSet2 === false) {
						$misc->log_error($sql2);
					}
					$selected_class_id = array();
					while (!$recordSet2->EOF) {
						$selected_class_id[] = $recordSet2->fields['class_id'];
						$recordSet2->MoveNext();
					}
					$sql2 = 'SELECT class_id,class_name FROM ' . $config['table_prefix'] . 'class';
					$recordSet2 = $conn->execute($sql2);
					if ($recordSet2 === false) {
						$misc->log_error($sql2);
					}
					$display .= '<tr><td align="right"><b>' . $lang['admin_listings_editor_property_class'] . '</b></td><td align="left">';
					$display .= '<select name="pclass[]"';
					if ($config["multiple_pclass_selection"] == '1') {
						$display .= ' multiple="multiple" size="5"';
					}
					$display .= '>';
					while (!$recordSet2->EOF) {
						$class_id = $recordSet2->fields['class_id'];
						$class_name = $misc->make_db_unsafe($recordSet2->fields['class_name']);
						if (in_array($class_id, $selected_class_id, true)) {
							$display .= '<option value="' . $class_id . '" selected="selected">' . $class_name . '</option>';
						}else {
							$display .= '<option value="' . $class_id . '">' . $class_name . '</option>';
						}
						$recordSet2->MoveNext();
					}
					$display .= '</select></td></tr>';
					// End property Class Display
					if ($_SESSION['featureListings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
						$display .= '<tr><td align="right"><b>' . $lang['admin_listings_editor_featured'] . ':</b></td><td align="left">';
						$display .= '<select name="featured" size="1">';
						$display .= '<option value="' . $edit_featured . '">' . $lang[''.$edit_featured.''] . '</option>';
						$display .= '<option value="">-----</option>';
						$display .= '<option value="yes">'.$lang['yes'].'</option>';
						$display .= '<option value="no">'.$lang['no'].'</option>';
						$display .= '</select></td></tr>';
					} // end if ($featureListings == "yes")
					if ($_SESSION['admin_privs'] == "yes" || $_SESSION['moderator'] == 'yes') {
						$display .= '<tr><td align="right"><b>' . $lang['admin_listings_active'] . ':</b></td><td align="left">';
						$display .= '<select name="edit_active" size="1">';
						$display .= '<option value="' . $edit_active . '">' . $lang[''.$edit_active.''] . '</option>';
						$display .= '<option value="">-----</option>';
						$display .= '<option value="yes">'.$lang['yes'].'</option>';
						$display .= '<option value="no">'.$lang['no'].'</option>';
						$display .= '</select></td></tr>';
						if ($config['moderate_listings'] == 1 && $edit_active == 'no'){
							$display .= '<tr><td align="right"><b>' . $lang['admin_send_notices'] . ':</b></td><td align="left">';
							$display .= '<select name="send_notices" size="1">';
							$display .= '<option value="no">'.$lang['no'].'</option>';
							$display .= '<option value="yes">'.$lang['yes'].'</option>';
							$display .= '</select>';
							$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$lang['send_notices_tool_tip'].'</span></a>';
							$display .= '</td></tr>';
						}
					} // end if ($featureListings == "yes")
					if (($_SESSION['admin_privs'] == "yes" || $_SESSION['edit_expiration'] == "yes") && $config['use_expiration'] == "1") {
						$display .= '<tr><td align="right" class="row_main"><b>' . $lang['expiration'] . ':</b></td><td align="left"><input type="text" name="edit_expiration" value="' . $expiration . '" onFocus="javascript:vDateType=\'' . $config['date_format'] . '\'" onKeyUp="DateFormat(this,this.value,event,false,\'' . $config['date_format'] . '\')" onBlur="DateFormat(this,this.value,event,true,\'' . $config['date_format'] . '\')" />(' . $config['date_format_long'] . ')</td></tr>';
					} // end if ($admin_privs == "yes" and $config[use_expiration] = "yes")
					if ($config["export_listings"] == 1 && $_SESSION['export_listings'] == "yes") {
						$display .= '<tr><td align="right"><strong>' . $lang['admin_listings_editor_mlsexport'] . ':</strong></td><td align="left">';
						$display .= '<select name="mlsexport" size="1">';
						$display .= '<option value="' . $edit_mlsexport . '">' . $lang[''.$edit_mlsexport.''] . '</option>';
						$display .= '<option value="">-----</option>';
						$display .= '<option value="yes">' . $lang['yes'] . '</option>';
						$display .= '<option value="no">' . $lang['no'] . '</option>';
						$display .= '</select>';
						$display .= '</td></tr>';
					}else {
						$display .= '<input type="hidden" name="mlsexport" value="no" />';
					}
					// Display Agent selection Option to reassign listing
					if ($_SESSION['admin_privs'] == "yes" || $_SESSION['edit_all_listings'] == "yes") {
						$display .= '<tr><td align="right"><b>' . $lang['listing_editor_listing_agent'] . ':</b></td>';
						$display .= '<td align="left" class="row_main"><select name="or_owner" size="1">';
						// find the name of the agent listed as ID in $edit_or_owner
						$sql = "SELECT userdb_user_first_name, userdb_user_last_name FROM " . $config['table_prefix'] . "userdb WHERE (userdb_id = $edit_or_owner)";
						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						// strip slashes so input appears correctly
						$agent_first_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_first_name']);
						$agent_last_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_last_name']);
						$display .= "<option value=\"$edit_or_owner\">$agent_last_name,$agent_first_name</option>";
						// fill list with names of all agents
						$sql = "SELECT userdb_id, userdb_user_first_name, userdb_user_last_name FROM " . $config['table_prefix'] . "userdb where userdb_is_agent = 'yes' or userdb_is_admin = 'yes' ORDER BY userdb_user_last_name,userdb_user_first_name";
						$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						while (!$recordSet->EOF) {
							// strip slashes so input appears correctly
							$agent_ID = $recordSet->fields['userdb_id'];
							$agent_first_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_first_name']);
							$agent_last_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_last_name']);
							$display .= "<option value=\"$agent_ID\">$agent_last_name,$agent_first_name</option>";
							$recordSet->MoveNext();
						}
						$display .= "</select></td>";
						$display .= '</tr>';
					}
					else {
						$display .= '<input type="hidden" name="or_owner" value="' . $edit_or_owner . '" />';
					}
					// Show Notes Field
					if ($config["show_notes_field"] == 1) {
					$display .= '<tr><td align="right"><b>' . $lang['admin_listings_editor_notes'] . ':</b><br /><div class="small">(' . $lang['admin_listings_editor_notes_note'] . ')</div></td><td align="left"> <textarea name="notes" rows="6" cols="40">' . $edit_notes . '</textarea></td></tr>';
					} else {
						$display .= '<input type="hidden" name="notes" value="' . $edit_notes . '" />';
					}
					// Show Listing Fields for this property class
					$sql = 'SELECT class_id from ' . $config['table_prefix_no_lang'] . 'classlistingsdb WHERE listingsdb_id =' . $edit;
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
					$class_sql = '';
					while (!$recordSet->EOF()) {
						$class_id = $recordSet->fields['class_id'];
						if (empty($class_sql)) {
							$class_sql .= ' class_id = ' . $class_id;
						}else {
							$class_sql .= ' OR class_id = ' . $class_id;
						}
						$recordSet->MoveNext();
					}
					$class_list = '';
					$sql = "SELECT DISTINCT(listingsformelements_id) FROM  " . $config['table_prefix_no_lang'] . "classformelements WHERE " . $class_sql;
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					} while (!$recordSet->EOF) {
						if (empty($class_list)) {
							$class_list .= $recordSet->fields['listingsformelements_id'];
						}else {
							$class_list .= ',' . $recordSet->fields['listingsformelements_id'];
						}
						$recordSet->MoveNext();
					}
					if ($class_list == '') {
						$class_list = 0;
					}
					$sql = "SELECT listingsformelements_field_name, listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption, listingsformelements_default_text, listingsformelements_field_elements, listingsformelements_required, listingsformelements_field_length, listingsformelements_tool_tip FROM " . $config['table_prefix'] . "listingsformelements left join " . $config['table_prefix'] . "listingsdbelements on listingsdbelements_field_name = listingsformelements_field_name AND listingsdb_id = $edit WHERE listingsformelements_id IN (" . $class_list . ") ORDER BY listingsformelements_rank";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					} while (!$recordSet->EOF) {
						$field_name = $misc->make_db_unsafe ($recordSet->fields['listingsformelements_field_name']);
						if (isset($_POST[$field_name])) {
							if (is_array($_POST[$field_name])) {
								$field_value = "";
								foreach ($_POST[$field_name] as $feature_item) {
									$feature_item = $misc->make_db_unsafe($feature_item);
									$field_value .= "||$feature_item";
								} // end while
								// now remove the first two characters
								$feature_insert_length = strlen($field_value);
								$feature_insert_length = $feature_insert_length - 2;
								$field_value = substr($field_value, 2, $feature_insert_length);
							}else {
									$field_value = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
							}
						}else {
							$field_value = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
						}
						$field_type = $misc->make_db_unsafe ($recordSet->fields['listingsformelements_field_type']);
						$field_caption = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_caption']);
						$default_text = $misc->make_db_unsafe($recordSet->fields['listingsformelements_default_text']);
						$field_elements = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_elements']);
						$required = $misc->make_db_unsafe($recordSet->fields['listingsformelements_required']);
						$field_length = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_length']);
						$tool_tip = $misc->make_db_unsafe($recordSet->fields['listingsformelements_tool_tip']);
						// pass the data to the function
						$display .= $forms->renderExistingFormElement($field_type, $field_name, $field_value, $field_caption, $default_text, $required, $field_elements, $field_length, $tool_tip);

						$recordSet->MoveNext();
					}
					//$editid = substr($edit, 1, -1) * 1;
					if ($only_my_listings == true) {
						$edit_link = $config['baseurl'].'/admin/index.php?action=edit_my_listings&amp;edit='.$edit;
						$delete_link = $config['baseurl'].'/admin/index.php?action=edit_my_listings&amp;delete='.$edit;
					}else {
						$edit_link = $config['baseurl'].'/admin/index.php?action=edit_listings&amp;edit='.$edit;
						$delete_link = $config['baseurl'].'/admin/index.php?action=edit_listings&amp;delete='.$edit;
					}
					$display .= '<tr><td colspan="2" align="center">' . $lang[required_form_text]. '</td></tr>';
					$display .= '<tr><td colspan="2" align="center"><input type="submit" value="' . $lang[update_button] . '">  <a href="' . $delete_link . '" onclick="return confirmDelete()">' . $lang[admin_listings_editor_delete_listing] . '</a></td></tr></table></form>';
					$display .= '</td></tr></table>';
				}else {
					$display .= '<center><span class="redtext">' . $lang['admin_listings_editor_invalid_listing'] . '</span></center>';
					$next_prev = '<center>' . $misc->next_prev($num_rows, $_GET['cur_page'], "",'',TRUE) . '</center>'; // put in the next/previous stuff
					$display .= listing_editor::show_quick_edit_bar($next_prev,$only_my_listings);
				}
			} else {
				// show all the listings
				$sql_filter = '';
				if (isset($_POST['filter'])) {
					if ($_POST['filter'] == 'active') {
						$sql_filter = " AND listingsdb_active = 'yes' ";
					}
					if ($_POST['filter'] == 'inactive') {
						$sql_filter = " AND listingsdb_active = 'no' ";
					}
					if ($_POST['filter'] == 'expired') {
						$sql_filter = " AND listingsdb_expiration < ".$conn->DBDate(time());
					}
					if ($_POST['filter'] == 'featured') {
						$sql_filter = " AND listingsdb_featured = 'yes' ";
					}
					if ($_POST['filter'] == 'created_1week') {
						$sql_filter = " AND listingsdb_creation_date >= ".$conn->DBDate(date('Y-m-d', strtotime('-1 week')));
					}
					if ($_POST['filter'] == 'created_1month') {
						$sql_filter = " AND listingsdb_creation_date >= ".$conn->DBDate(date('Y-m-d', strtotime('-1 month')));
					}
					if ($_POST['filter'] == 'created_3month') {
						$sql_filter = " AND listingsdb_creation_date >= ".$conn->DBDate(date('Y-m-d', strtotime('-3 month')));
					}
				}
				$lookup_sql = '';
				if (isset($_POST['lookup_field']) && isset($_POST['lookup_value']) && $_POST['lookup_field'] != 'listingsdb_id' && $_POST['lookup_field'] != 'listingsdb_title' && $_POST['lookup_value'] != '') {
					$lookup_field = $misc->make_db_safe($_POST['lookup_field']);
					$lookup_value = $misc->make_db_safe('%'.$_POST['lookup_value'].'%');
					$sql = 'SELECT listingsdb_id FROM '.$config['table_prefix'].'listingsdbelements WHERE listingsdbelements_field_name = '.$lookup_field.' AND listingsdbelements_field_value LIKE '.$lookup_value;
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
					$listing_ids=array();
					while(!$recordSet->EOF){
						$listing_ids[] = $recordSet->fields['listingsdb_id'];
						$recordSet->MoveNext();
					}
					if(count($listing_ids) > 0) {
						$listing_ids = implode(',',$listing_ids);
					} else {
						$listing_ids = '0';
					}
					$lookup_sql = ' AND listingsdb_id IN ('.$listing_ids.') ';
				}
				if (isset($_POST['lookup_field']) && isset($_POST['lookup_value']) && $_POST['lookup_field'] == 'listingsdb_title' && $_POST['lookup_value'] != '') {
					$lookup_value = $misc->make_db_safe('%'.$_POST['lookup_value'].'%');
					$sql = 'SELECT listingsdb_id FROM '.$config['table_prefix'].'listingsdb WHERE listingsdb_title  LIKE '.$lookup_value;
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
					$listing_ids=array();
					while(!$recordSet->EOF){
						$listing_ids[] = $recordSet->fields['listingsdb_id'];
						$recordSet->MoveNext();
					}
					if(count($listing_ids) > 0) {
						$listing_ids = implode(',',$listing_ids);
					} else {
						$listing_ids = '0';
					}
					$lookup_sql = ' AND listingsdb_id IN ('.$listing_ids.') ';
				}
				if (isset($_POST['pclass_filter']) &&  $_POST['pclass_filter'] != '')
				{
				$pclass_filter = $misc->make_db_safe($_POST['pclass_filter']);
				$sql = 'SELECT listingsdb_id FROM '.$config['table_prefix_no_lang'].'classlistingsdb WHERE class_id = '.$pclass_filter;
				$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
				$listing_ids=array();
					while(!$recordSet->EOF){
						$listing_ids[] = $recordSet->fields['listingsdb_id'];
						$recordSet->MoveNext();
					}
					if(count($listing_ids) > 0) {
						$listing_ids = implode(',',$listing_ids);
					} else {
						$listing_ids = '0';
					}
					$pclass_sql = ' AND listingsdb_id IN ('.$listing_ids.') ';

				}
				if (isset($_POST['agent_filter']) &&  $_POST['agent_filter'] != '')
				{
				$agent_filter = $misc->make_db_safe($_POST['agent_filter']);
				$sql = 'SELECT listingsdb_id FROM '.$config['table_prefix'].'listingsdb WHERE userdb_id = '.$agent_filter;
				$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
				$listing_ids=array();
					while(!$recordSet->EOF){
						$listing_ids[] = $recordSet->fields['listingsdb_id'];
						$recordSet->MoveNext();
					}
					if(count($listing_ids) > 0) {
						$listing_ids = implode(',',$listing_ids);
					} else {
						$listing_ids = '0';
					}
					$agent_sql = ' AND listingsdb_id IN ('.$listing_ids.') ';
				}
				// grab the number of listings from the db
				if ($only_my_listings == true) {
					$sql = "SELECT listingsdb_id, listingsdb_title, listingsdb_mlsexport, listingsdb_notes,	listingsdb_expiration, listingsdb_active, listingsdb_featured, listingsdb_hit_count, userdb_emailaddress FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "userdb WHERE " . $config['table_prefix'] . "listingsdb.userdb_id = " . $config['table_prefix'] . "userdb.userdb_id AND (" . $config['table_prefix'] . "userdb.userdb_id = '$_SESSION[userID]') $sql_filter $lookup_sql $pclass_sql $agent_sql ORDER BY listingsdb_id ASC";
				}else {
					$sql = "SELECT listingsdb_id, listingsdb_title, listingsdb_mlsexport, listingsdb_notes,	listingsdb_expiration, listingsdb_active, listingsdb_featured, listingsdb_hit_count, userdb_emailaddress FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "userdb WHERE " . $config['table_prefix'] . "listingsdb.userdb_id = " . $config['table_prefix'] . "userdb.userdb_id $sql_filter $lookup_sql $pclass_sql $agent_sql ORDER BY listingsdb_id ASC";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$num_rows = $recordSet->RecordCount();
				if (!isset($_GET['cur_page'])) {
					$_GET['cur_page'] = 0;
				}
				$next_prev = '<center>' . $misc->next_prev($num_rows, $_GET['cur_page'], "",'',TRUE) . '</center>'; // put in the next/previous stuff
				$display .= listing_editor::show_quick_edit_bar($next_prev,$only_my_listings);
				// build the string to select a certain number of listings per page
				$limit_str = $_GET['cur_page'] * $config['listings_per_page'];
				$recordSet = $conn->SelectLimit($sql, $config['listings_per_page'], $limit_str);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$count = 0;
				$display .= "<br /><br />";
				$page->load_page($config['admin_template_path'].'/edit_listings.html');
				$page->replace_lang_template_tags();
				$page->replace_tags();
				$addons = $page->load_addons();
				$listing_section = $page->get_template_section('listing_dataset');
				while (!$recordSet->EOF) {
					// alternate the colors
					if ($count == 0) {
						$count = $count + 1;
					}else {
						$count = 0;
					}
					$listing .= $listing_section;
					// strip slashes so input appears correctly
					$title = $misc->make_db_unsafe ($recordSet->fields['listingsdb_title']);
					$notes = $misc->make_db_unsafe ($recordSet->fields['listingsdb_notes']);
					$active = $misc->make_db_unsafe ($recordSet->fields['listingsdb_active']);
					$featured = $misc->make_db_unsafe ($recordSet->fields['listingsdb_featured']);
					$mlsexport = $misc->make_db_unsafe ($recordSet->fields['listingsdb_mlsexport']);
					$email = $misc->make_db_unsafe ($recordSet->fields['userdb_emailaddress']);
					$formatted_expiration = $recordSet->UserTimeStamp($recordSet->fields['listingsdb_expiration'], $config["date_format_timestamp"]);
					$listingID = $recordSet->fields['listingsdb_id'];
					$hit_count = $misc->make_db_unsafe ($recordSet->fields['listingsdb_hit_count']);
					if ($active == 'yes') {
						$active = '<span class="edit_listings_'.$active.'">'.$lang['yes'].'</span>';
					} elseif ($active == 'no') {
						$active = '<span class="edit_listings_'.$active.'">'.$lang['no'].'</span>';
					}
					if ($featured == 'yes') {
						$featured = '<span class="edit_listings_'.$featured.'">'.$lang['yes'].'</span>';
					} elseif ($featured == 'no') {
						$featured = '<span class="edit_listings_'.$featured.'">'.$lang['no'].'</span>';
					}
					//Add filters to link
					if(isset($_POST['lookup_field']) && isset($_POST['lookup_value'])){
					$_GET['lookup_field'] =$_POST['lookup_field'];
					$_GET['lookup_value'] =$_POST['lookup_value'];
					}
					if(isset($_GET['lookup_field']) && isset($_GET['lookup_value'])){
						$_POST['lookup_field'] =$_GET['lookup_field'];
						$_POST['lookup_value'] =$_GET['lookup_value'];
					}

					if ($only_my_listings == true) {
						$edit_link = $config['baseurl'].'/admin/index.php?action=edit_my_listings&amp;edit='.$listingID;
						$delete_link = $config['baseurl'].'/admin/index.php?action=edit_my_listings&amp;delete='.$listingID;

					}else {
						$edit_link = $config['baseurl'].'/admin/index.php?action=edit_listings&amp;edit='.$listingID;
						$delete_link = $config['baseurl'].'/admin/index.php?action=edit_listings&amp;delete='.$listingID;
					}
					$email_link = 'mailto:'.$email;
					$listing = $page->replace_listing_field_tags($listingID,$listing);
					$listing = $page->parse_template_section($listing, 'listingid', $listingID);
					$listing = $page->parse_template_section($listing, 'edit_listing_link', $edit_link);
					$listing = $page->parse_template_section($listing, 'delete_listing_link', $delete_link);
					$listing = $page->parse_template_section($listing, 'email_agent_link', $email_link);
					$listing = $page->parse_template_section($listing, 'listing_active_status', $active);
					$listing = $page->parse_template_section($listing, 'listing_featured_status', $featured);
					$listing = $page->parse_template_section($listing, 'listing_expiration', $formatted_expiration);
					$listing = $page->parse_template_section($listing, 'listing_notes', $notes);
					$listing = $page->parse_template_section($listing, 'row_num_even_odd', $count);
					$listing = $page->parse_template_section($listing, 'listing_hit_count', $hit_count);
					$addon_fields = $page->get_addon_template_field_list($addons);
					$listing = $page->parse_addon_tags($listing, $addon_fields);
					if ($config["use_expiration"]  == 0) {
						$listing = $page->remove_template_block('show_expiration', $listing);
					}else {
						$listing = $page->cleanup_template_block('show_expiration', $listing);
					}
					$recordSet->MoveNext();
				} // end while
				$page->replace_template_section('listing_dataset', $listing);
				$page->replace_permission_tags();
				$display .= $page->return_page();
			} // end if $edit == ""
		}else {
			$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
		return $display;
	}
	function show_quick_edit_bar($next_prev = '',$only_my_listings = true)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$display .= '<fieldset><legend>' . $lang['listings_editor'] . '</legend>';
		$display .= '<table width="715"  border="0" align="center" cellpadding="0" cellspacing="0">';
		$display .= '<tr>';
		$display .= '<td colspan="3">';
		$display .= '<form name="form1" method="post" action="">';
		$display .= '<strong>' . $lang['listing_editor_lookup'] . ':</strong>';
		$display .= '<select name="lookup_field">';
		$display .= '<option value="listingsdb_id" ';
		if (isset($_POST['lookup_field']) && $_POST['lookup_field'] == 'listingsdb_id') {
			$display .= ' selected="selected" ';
		}
		$display .= '>' . $lang['admin_listings_editor_listing_number'] . '</option>';
		$display .= '<option value="listingsdb_title" ';
		if (isset($_POST['lookup_field']) && $_POST['lookup_field'] == 'listingsdb_title') {
			$display .= ' selected="selected" ';
		}
		$display .= '>' . $lang['admin_listings_editor_listing_title'] . '</option>';
		$sql = 'SELECT listingsformelements_field_name, listingsformelements_field_caption, listingsformelements_field_type FROM '.$config['table_prefix'].'listingsformelements WHERE listingsformelements_field_type != \'divider\'';
		$recordSet = $conn->execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		while (!$recordSet->EOF) {;
			$field_name = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_name']);
			$field_caption = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_caption']);
			$display .= '<option value="'.$field_name.'" ';
			if (isset($_POST['lookup_field']) && $_POST['lookup_field'] == $field_name) {
				$display .= ' selected="selected" ';
			}
			$display .= '>'.$field_caption.'</option>';
			$recordSet->MoveNext();
		}
		$display .= '</select>';
		$display .= '<input name="lookup_value" type="text" value="';
		if (isset($_POST['lookup_value'])) {
			$display .= $_POST['lookup_value'];
		}
		$display .= '">';
		$display .= '<input name="Lookup" type="submit" value="' . $lang['listing_editor_lookup'] . '">';
		$display .= '</form>';
		$display .= '</td>';
		$display .= '<td><div style="text-align:center; width:100%;"><a href="'. $config['baseurl'] .'/admin/index.php?action=add_listing_property_class"><img src="images/no_lang/add_listing_transp.gif" style="border:none;" alt=""/><br />'. $lang['admin_add_listing'] .'</a></div></td>';
		$display .= '</tr>';
		$display .= '<tr>';
		$display .= '<td>';
		$display .= '<form name="form1" method="post" action="">';
		$display .= '<strong>' . $lang['listing_editor_show'] . ':</strong>';
		$display .= '<select name="filter">';
		$display .= '<option selected="selected">' . $lang['listing_editor_show_all'] . '</option>';
		$display .= '<option value="active" ';
		if (isset($_POST['filter']) && $_POST['filter'] == 'active') {
			$display .= ' selected="selected" ';
		}
		$display .= '>' . $lang['listing_editor_active'] . '</option>';
		$display .= '<option value="inactive" ';
		if (isset($_POST['filter']) && $_POST['filter'] == 'inactive') {
			$display .= ' selected="selected" ';
		}
		$display .= '>' . $lang['listing_editor_inactive'] . '</option>';
		$display .= '<option value="expired" ';
		if (isset($_POST['filter']) && $_POST['filter'] == 'expired') {
			$display .= ' selected="selected" ';
		}
		$display .= '>' . $lang['listing_editor_expired'] . '</option>';
		$display .= '<option value="featured" ';
		if (isset($_POST['filter']) && $_POST['filter'] == 'featured') {
			$display .= ' selected="selected" ';
		}
		$display .= '>' . $lang['listing_editor_featured'] . '</option>';
		//This Weeks Listings
		$display .= '<option value="created_1week" ';
		if (isset($_POST['filter']) && $_POST['filter'] == 'created_1week') {
			$display .= ' selected="selected" ';
		}
		$display .= '>' . $lang['listing_editor_created_1week'] . '</option>';
		//This Month's Listings
		$display .= '<option value="created_1month" ';
		if (isset($_POST['filter']) && $_POST['filter'] == 'created_1month') {
			$display .= ' selected="selected" ';
		}
		$display .= '>' . $lang['listing_editor_created_1month'] . '</option>';
		//Last 3 Month's Listings
		$display .= '<option value="created_3month" ';
		if (isset($_POST['filter']) && $_POST['filter'] == 'created_3month') {
			$display .= ' selected="selected" ';
		}
		$display .= '>' . $lang['listing_editor_created_3month'] . '</option>';

		$display .= '</select>';
		$display .= '<input name="Filter" type="submit" value="' . $lang['listing_editor_filter'] . '">';
		$display .= '</form>';
		$display .= '</td>';
		if ($only_my_listings === false)
		{
		$display .= '<td>';
		$display .= '<form name="form1" method="post" action="">';
		$display .= '<strong>' . $lang['listing_editor_show_agent'] . ':</strong>';
		$display .= '<select name="agent_filter">';
		$display .= '<option value="" selected="selected">' . $lang['listing_editor_show_all'] . '</option>';
		$sql = "SELECT userdb_id, userdb_user_first_name, userdb_user_last_name FROM " . $config['table_prefix'] . "userdb where userdb_is_agent = 'yes' ORDER BY userdb_user_last_name,userdb_user_first_name";
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		while (!$recordSet->EOF) {
			$agent_ID = $recordSet->fields['userdb_id'];
			$agent_first_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_first_name']);
			$agent_last_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_last_name']);
			if (isset($_POST['agent_filter']) && $_POST['agent_filter'] == $agent_ID) {
			$display .= '<option value="'.$agent_ID.'" selected="selected">'.$agent_last_name.','.$agent_first_name.'</option>';
			}else {
			$display .= '<option value="'.$agent_ID.'">'.$agent_last_name.','.$agent_first_name.'</option>';
			}
		$recordSet->MoveNext();
		}
		$display .= '</select>';
		$display .= '<input name="Filter" type="submit" value="' . $lang['listing_editor_filter'] . '">';
		$display .= '</form>';
		$display .= '</td>';
		}
		$display .= '<td>';
		$display .= '<form name="form1" method="post" action="">';
		$display .= '<strong>' . $lang['listing_editor_show_pclass'] . ':</strong>';
		$sql2 = 'SELECT class_id,class_name FROM ' . $config['table_prefix'] . 'class';
		$recordSet2 = $conn->execute($sql2);
		if ($recordSet2 === false) {
			$misc->log_error($sql2);
		}
		$display .= '<select name="pclass_filter">';
		$display .= '<option value="" selected="selected">' . $lang['listing_editor_show_all'] . '</option>';
		while (!$recordSet2->EOF) {
			$class_id = $recordSet2->fields['class_id'];
			$class_name = $misc->make_db_unsafe($recordSet2->fields['class_name']);
			if (isset($_POST['pclass_filter']) && $_POST['pclass_filter'] == $class_id) {
				$display .= '<option value="' . $class_id . '" selected="selected">' . $class_name . '</option>';
			}else {
				$display .= '<option value="' . $class_id . '">' . $class_name . '</option>';
			}
			$recordSet2->MoveNext();
		}
		$display .= '</select>';
		$display .= '<input name="Filter" type="submit" value="' . $lang['listing_editor_filter'] . '">';
		$display .= '</form>';
		$display .= '</td>';
		$display .= '</tr>';
		$display .= '</table>';
		if ($next_prev != '') {
			$display .= '<hr />' . $next_prev;
		}
		$display .= '</fieldset>';
		return $display;
	}
	function update_listing($verify_user = true)
	{
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/forms.inc.php');
		$forms = new forms();
		require_once($config['basepath'] . '/include/listing.inc.php');
		$listing_pages = new listing_pages();
		$display = '';
		// update the listing
		if ($verify_user) {
			$sql_edit = intval($_POST['edit']);
			$listing_ownerID = $listing_pages->getListingAgentID($sql_edit);
			if(intval($_SESSION['userID']) != $listing_ownerID){
				$display = $lang['listing_editor_permission_denied'].'<br />';
				return $display;
			}

		}
		if ($_POST['title'] == "") {
			// if the title is blank
			$display .= "$lang[admin_new_listing_enter_a_title]<br />";
		} // end if
		else {
			$pass_the_form = $forms->validateForm('listingsformelements',$_POST['pclass']);
			if ($pass_the_form !== "Yes") {
				// if we're not going to pass it, tell that they forgot to fill in one of the fields
				foreach ($pass_the_form as $k => $v) {
					if ($v == 'REQUIRED') {
						$display .= "<p class=\"redtext\">$k: $lang[required_fields_not_filled]</p>";
					}
					if ($v == 'TYPE') {
						$display .= "<p class=\"redtext\">$k: $lang[field_type_does_not_match]</p>";
					}
				}
				// $display .= "<p>$lang[required_fields_not_filled]</p>";
			}
			if ($pass_the_form == "Yes") {
				$sql_title = $misc->make_db_safe($_POST['title']);
				$sql_notes = $misc->make_db_safe($_POST['notes']);
				$sql_edit = $misc->make_db_safe($_POST['edit']);
				if (!isset($_POST['mlsexport'])) {
					$_POST['mlsexport'] = "no";
				}
				$sql_mlsexport = $misc->make_db_safe($_POST['mlsexport']);
				$sql = "UPDATE " . $config['table_prefix'] . "listingsdb SET ";
				if (!$verify_user) {
					$sql_or_owner = $misc->make_db_safe($_POST['or_owner']);
					// update the listing data
					$sql .= "userdb_ID = $sql_or_owner, ";
				}
				$sql .= "listingsdb_title = $sql_title, ";
				if ($_SESSION['admin_privs'] == "yes" || $_SESSION['featureListings'] == "yes") {

					// Check Number of Featured Listings User has
					if (isset($_POST['or_owner'])) {
						$or_owner = $misc->make_db_safe($_POST['or_owner']);
						$featuredsql = 'SELECT count(listingsdb_id) as listing_count FROM ' . $config['table_prefix'] . 'listingsdb WHERE listingsdb_featured = \'yes\' AND userdb_id = ' . $or_owner;
					}else {
						$featuredsql = 'SELECT count(listingsdb_id) as listing_count FROM ' . $config['table_prefix'] . 'listingsdb WHERE listingsdb_featured = \'yes\' AND userdb_id = ' . $_SESSION['userID'];
					}
					$recordSet = $conn->Execute($featuredsql);
					if ($recordSet === false) {
						$misc->log_error($featuredsql);
					}
					$featuredlisting_count = $recordSet->fields['listing_count'];
					// Get User Featured Listing Limit
					if (isset($_POST['or_owner'])) {
						$or_owner = $misc->make_db_safe($_POST['or_owner']);
						$featuredsql = 'SELECT userdb_featuredlistinglimit FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $or_owner;
					}else {
						$featuredsql = 'SELECT userdb_featuredlistinglimit FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $_SESSION['userID'];
					}
					$recordSet = $conn->Execute($featuredsql);
					if ($recordSet === false) {
						$misc->log_error($featuredsql);
					}
					$featuredlisting_limit = $recordSet->fields['userdb_featuredlistinglimit'];
					$featuredLimitError=FALSE;
					if($_POST['featured'] == 'yes'){
						if(($featuredlisting_limit > $featuredlisting_count) || ($featuredlisting_limit == '-1')){
							// if the user can feature properties
							$sql_featured = $misc->make_db_safe($_POST['featured']);
							$sql .= "listingsdb_featured = $sql_featured, ";
						} else {
							//See if we are already featured..
							$featuredcheckSql = 'SELECT listingsdb_featured FROM ' . $config['table_prefix'] . 'listingsdb WHERE listingsdb_id = '.$sql_edit;
							$recordSetFeatured = $conn->Execute($featuredcheckSql);
							if ($recordSetFeatured === false) {
								$misc->log_error($featuredcheckSql);
							}
							$current_status = $recordSetFeatured->fields['listingsdb_featured'];
							if($current_status=='yes'){
								$sql_featured = $misc->make_db_safe($_POST['featured']);
								$sql .= "listingsdb_featured = $sql_featured, ";
							}else{
								$featuredLimitError=TRUE;
							}
						}
					} else {
						//Not Feautred Save no matter what
						$sql_featured = $misc->make_db_safe($_POST['featured']);
						$sql .= "listingsdb_featured = $sql_featured, ";
					}
				} // end if ($featureListings == "yes")
				if ($_SESSION['admin_privs'] == "yes" || $_SESSION['moderator'] =="yes") {
					// if the user is an administrtor
					$sql_active = $misc->make_db_safe($_POST['edit_active']);
					$sql .= "listingsdb_active = $sql_active, ";
				} // end if ($admin_privs == "yes")
				if (($_SESSION['admin_privs'] == "yes" || $_SESSION['edit_expiration'] == "yes") && $config['use_expiration'] == "1") {
					$expiration_date = $misc->or_date_format($_POST['edit_expiration']);
					$sql .= "listingsdb_expiration = " . $expiration_date . ",";
				}
				if ($verify_user) {
					$sql .= "listingsdb_notes = $sql_notes, listingsdb_mlsexport = $sql_mlsexport, listingsdb_last_modified = " . $conn->DBTimeStamp(time()) . " WHERE ((listingsdb_id = $sql_edit) AND (userdb_id = $_SESSION[userID]))";
				}else {
					$sql .= "listingsdb_notes = $sql_notes, listingsdb_mlsexport = $sql_mlsexport, listingsdb_last_modified = " . $conn->DBTimeStamp(time()) . " WHERE listingsdb_id = $sql_edit";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				if ($verify_user) {
					$message = listing_editor::updateListingsData($_POST['edit'], $_SESSION['userID']);
				}else {
					// update the image data (in case the or_owner has changed)
					$sql = "UPDATE " . $config['table_prefix'] . "listingsimages SET userdb_id = $sql_or_owner WHERE listingsdb_id = $sql_edit";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
					$message = listing_editor::updateListingsData($_POST['edit'], $_POST['or_owner']);
				}
				// Ok Now Handle Any property class changes that all the data is saved.
				// First Get a list of all the currently assing property classes.
				$sql2 = 'SELECT class_id FROM ' . $config['table_prefix_no_lang'] . 'classlistingsdb WHERE listingsdb_id =' . $sql_edit;
				$recordSet2 = $conn->execute($sql2);
				if ($recordSet2 === false) {
					$misc->log_error($sql2);
				}
				$current_class_id = array();
				while (!$recordSet2->EOF) {
					$current_class_id[] = $recordSet2->fields['class_id'];
					$recordSet2->MoveNext();
				}
				// Get List of edited pclasses
				$new_class_assigned_sql = implode(',', $_POST['pclass']);
				// Now if teh property class is no longer assigned remove this listin from the class and remove any listing fields tha belogn only to this class
				foreach($current_class_id as $c_class_id) {
					if (!in_array($c_class_id, $_POST['pclass'])) {
						// Delete listing from class
						$sql = 'DELETE FROM ' . $config['table_prefix_no_lang'] . 'classlistingsdb WHERE class_id = ' . $c_class_id . ' AND listingsdb_id = ' . $sql_edit;
						$recordSet = $conn->execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						// Get a list of form element ids for the new selected property classes
						$sql = 'SELECT listingsformelements_id FROM ' . $config['table_prefix_no_lang'] . 'classformelements WHERE class_id IN (' . $new_class_assigned_sql . ')';
						$recordSet = $conn->execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$formelement_ids = array();
						while (!$recordSet->EOF) {
							$formelement_ids[] = $recordSet->fields['listingsformelements_id'];
							$recordSet->Movenext();
						}
						$new_listingsformelements_id_sql = implode(',', $formelement_ids);
						$sql = 'SELECT DISTINCT(listingsformelements_field_name) FROM ' . $config['table_prefix_no_lang'] . 'classformelements as c,' . $config['table_prefix'] . 'listingsformelements as f WHERE class_id = ' . $c_class_id . ' AND c.listingsformelements_id NOT IN (' . $new_listingsformelements_id_sql . ') AND c.listingsformelements_id = f.listingsformelements_id';
						if ($recordSet === false) {
							$misc->log_error($sql);
						} while (!$recordSet->EOF) {
							$sql2 = 'DELETE FROM ' . $config['table_prefix'] . 'listingsdbelements WHERE listingsdbelements_field_name = ' . $recordSet->fields['listingsformelements_field_name'] . ' AND listingsdb_id = ' . $sql_edit;
							$recordSet2 = $conn->execute($sql2);
							if ($recordSet2 === false) {
								$misc->log_error($sql2);
							}
						}
					}
				}
				// If this is a new class add the listing to the class
				foreach ($_POST['pclass'] as $class_id) {
					if (!in_array($class_id, $current_class_id)) {
						$sql2 = 'INSERT INTO ' . $config['table_prefix_no_lang'] . 'classlistingsdb (class_id,listingsdb_id) VALUES (' . $class_id . ',' . $sql_edit . ')';
						$recordSet2 = $conn->execute($sql2);
						if ($recordSet2 === false) {
							$misc->log_error($sql2);
						}
					}
				}
				if ($message == "success") {
					$display .= "<p>$lang[admin_listings_editor_listing_number] $_POST[edit] $lang[has_been_updated] </p>";
					if($featuredLimitError==TRUE){
						$display .= "<p style=\"error\">$lang[admin_listings_editor_featuredlistingerror] </p>";
					}
					$misc->log_action ("$lang[log_updated_listing] $_POST[edit]");
				} // end if
				else {
					$display .= "<p>$lang[alert_site_admin]</p>";
				} // end else
			} // end if $pass_the_form == "Yes"
		} // end else
		return $display;
	}
	/**
	 * delete_listing()
	 *
	 * @param  $id
	 * @param boolean $verify_user
	 * @return
	 */
	function delete_listing($id, $verify_user = true)
	{
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		if (!is_numeric($id)) {
			die($lang['data type mismatch']);
		}
		$sql_delete = $misc->make_db_safe($id);
		// delete a listing
		$configured_langs = explode(',', $config['configured_langs']);
		foreach($configured_langs as $configured_lang) {
			if ($verify_user === true) {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsdb WHERE ((listingsdb_id = $sql_delete) AND (userdb_id = $_SESSION[userID]))";
			}else {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsdb WHERE listingsdb_id = $sql_delete";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			// delete all the elements associated with a listing
			if ($verify_user === true) {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsdbelements WHERE ((listingsdb_id = $sql_delete) AND (userdb_id = $_SESSION[userID]))";
			}else {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsdbelements WHERE listingsdb_id = $sql_delete";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
		}
		// now get all the images associated with an listing
		if ($verify_user === true) {
			$sql = "SELECT listingsimages_file_name, listingsimages_thumb_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE ((listingsdb_id = $sql_delete) AND (userdb_id = $_SESSION[userID]))";
		}else {
			$sql = "SELECT listingsimages_file_name, listingsimages_thumb_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE listingsdb_id = $sql_delete";
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// so, you've got 'em... it's time to unlink those bad boys...
		while (!$recordSet->EOF) {
			$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_thumb_file_name']);
			$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
			// get rid of those darned things...
			@unlink("$config[listings_upload_path]/$file_name");
			if ($file_name != $thumb_file_name) {
				@unlink("$config[listings_upload_path]/$thumb_file_name");
			}
			$recordSet->MoveNext();
		}
		// now get all the vtours associated with an listing
		if ($verify_user === true) {
			$sql = "SELECT vtourimages_file_name, vtourimages_thumb_file_name FROM " . $config['table_prefix'] . "vtourimages WHERE ((listingsdb_id = $sql_delete) AND (userdb_id = $_SESSION[userID]))";
		} else {
			$sql = "SELECT vtourimages_file_name, vtourimages_thumb_file_name FROM " . $config['table_prefix'] . "vtourimages WHERE listingsdb_id = $sql_delete";
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// so, you've got 'em... it's time to unlink those bad boys...
		while (!$recordSet->EOF) {
			$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_thumb_file_name']);
			$file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_file_name']);
			// get rid of those darned things...
			@unlink("$config[vtour_upload_path]/$file_name");
			if ($file_name != $thumb_file_name) {
				@unlink("$config[vtour_upload_path]/$thumb_file_name");
			}
			$recordSet->MoveNext();
		}
		// for the grand finale, we're going to remove the db records of 'em as well...
		foreach($configured_langs as $configured_lang) {
			if ($verify_user === true) {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsimages WHERE listingsdb_id = $sql_delete AND userdb_id = $_SESSION[userID]";
			}else {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsimages WHERE listingsdb_id = $sql_delete";
			}

			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			if ($verify_user === true) {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_vtourimages WHERE listingsdb_id = $sql_delete AND userdb_id = $_SESSION[userID]";
			}else {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_vtourimages WHERE listingsdb_id = $sql_delete";
			}

			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
		}
// now get all the files associated with an listing
		$uploadpath = $config['listings_file_upload_path'].'/'.$id;
		if ($verify_user === true) {
			$sql = "SELECT listingsfiles_file_name FROM " . $config['table_prefix'] . "listingsfiles WHERE ((listingsdb_id = $sql_delete) AND (userdb_id = $_SESSION[userID]))";
		}else {
			$sql = "SELECT listingsfiles_file_name FROM " . $config['table_prefix'] . "listingsfiles WHERE listingsdb_id = $sql_delete";
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// so, you've got 'em... it's time to unlink those bad boys...
		while (!$recordSet->EOF) {
			$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsfiles_file_name']);
// delete the files themselves
				@unlink("$uploadpath/$file_name");
				$empty = (count(glob("$uploadpath/*")) === 0) ? 'true' : 'false';
				if ($empty == 'true') {
					rmdir($uploadpath);
				}
			$recordSet->MoveNext();
		}
		// for the grand finale, we're going to remove the db records of 'em as well...
		foreach($configured_langs as $configured_lang) {
			if ($verify_user === true) {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsimages WHERE listingsdb_id = $sql_delete AND userdb_id = $_SESSION[userID]";
			}else {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsimages WHERE listingsdb_id = $sql_delete";
			}

			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			if ($verify_user === true) {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_vtourimages WHERE listingsdb_id = $sql_delete AND userdb_id = $_SESSION[userID]";
			}else {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_vtourimages WHERE listingsdb_id = $sql_delete";
			}

			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			if ($verify_user === true) {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsfiles WHERE listingsdb_id = $sql_delete AND userdb_id = $_SESSION[userID]";
			}else {
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsfiles WHERE listingsdb_id = $sql_delete";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
		}
		//Remove the listing from the listingsclass table.
		$sql = " DELETE FROM ".$config['table_prefix_no_lang']."classlistingsdb WHERE listingsdb_id = $sql_delete";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// Delete from favorites
        $sql = "DELETE FROM " . $config['table_prefix'] . "userfavoritelistings WHERE listingsdb_id = $sql_delete";
        $recordSet = $conn->Execute($sql);
        if ($recordSet === false) {
            $misc->log_error($sql);
        }
		// ta da! we're done...
		$display .= '<p>' . $lang['admin_listings_editor_listing_number'] . ' ' . $id . ' ' . $lang['has_been_deleted'] . '</p>';
		$misc->log_action ($lang['log_deleted_listing'] . ' ' . $id);
		return $display;
	}
	function update_active_status($user_id, $status)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$sql_status = $misc->make_db_safe($status);
		$sql = 'UPDATE '. $config['table_prefix'] .'listingsdb SET listingsdb_active = ' . $sql_status . ' WHERE userdb_id = '. $user_id;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		return '<p>' . $lang['agent_listings_updated'] . $status . '</p>';
	}
}
?>