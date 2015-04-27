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
class user_managment {
	function user_signup($type)
	{
		global $lang, $config, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/forms.inc.php');
		$forms = new forms();
		$display = '';
		$referers = $config['baseurl'];
		$referers = str_replace('http://', '', $referers);
		$referers = str_replace('https://', '', $referers);
		$referers = str_replace('www.', '', $referers);
		$referers = explode("/", $referers);
		$found = false;
		$temp = explode("/", $_SERVER['HTTP_REFERER']);
		$referer = $temp[2];
		if (eregi ($referers[0], $referer)) {
			$found = true;
		}
		if (!$found) {
			$temp = $lang['not_authorized'];
			return $temp;
		} else {
			if (!isset($_SERVER['HTTP_USER_AGENT'])) {
				$temp = $lang['not_authorized'];
				return $temp;
			}
		}
		if ($config['allow_' . $type . '_signup'] == 1) {
			if (isset($_POST['edit_user_name'])) {
				if ($_POST['edit_user_pass'] != $_POST['edit_user_pass2']) {
					$display .= '<p>' . $lang['user_creation_password_identical'] . '</p>';
					$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
				} elseif ($_POST['edit_user_pass'] == '') {
					$display .= '<p>' . $lang['user_creation_password_blank'] . '</p>';
					$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
				} elseif ($_POST['edit_user_name'] == '') {
					$display .= '<p>' . $lang['user_editor_need_username'] . '</p>';
					$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
				} elseif ($_POST['user_email'] == '') {
					$display .= '<p>' . $lang['user_editor_need_email_address'] . '</p>';
					$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
				} elseif ($_POST['user_first_name'] == "") {
					$display .= '<p>' . $lang['user_editor_need_first_name'] . '</p>';
					$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
				} elseif ($_POST['user_last_name'] == "") {
					$display .= '<p>' . $lang['user_editor_need_last_name'] . '</p>';
					$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
				} elseif (($_SESSION['security_code'] != md5($_POST['security_code'])) && $config["use_signup_image_verification"] == 1) {
					$display .= '<p>' . $lang['signup_verification_code_not_valid'] . '</p>';
					$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
				} else {
					$sql_user_name = $misc->make_db_safe($_POST['edit_user_name']);
					$sql_user_email = $misc->make_db_safe($_POST['user_email']);
					$sql_user_first_name = $misc->make_db_safe($_POST['user_first_name']);
					$sql_user_last_name = $misc->make_db_safe($_POST['user_last_name']);
					$pass_the_form = "No";
					// first, make sure the user name isn't in use
					$sql = 'SELECT userdb_user_name from ' . $config['table_prefix'] . 'userdb WHERE userdb_user_name = ' . $sql_user_name;
					$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
					$num = $recordSet->RecordCount();
					// second, make sure the user eamail isn't in use
					$sql2 = 'SELECT userdb_emailaddress from ' . $config['table_prefix'] . 'userdb WHERE userdb_emailaddress = ' . $sql_user_email;
					$recordSet2 = $conn->Execute($sql2);
					if ($recordSet2 === false) {
						$misc->log_error($sql2);
					}
					$num2 = $recordSet2->RecordCount();
					if ($num >= 1) {
						$pass_the_form = 'No';
						$display .= $lang['user_creation_username_taken'];
						$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
					} // end if
					elseif ($num2 >= 1) {
						$pass_the_form = 'No';
						$display .= $lang['email_address_already_registered'];
						$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
					} // end if
					else {
						// validate the user form
						$pass_the_form = $forms->validateForm($type . 'formelements');
						if ($pass_the_form == 'No') {
							// if we're not going to pass it, tell that they forgot to fill in one of the fields
							$display .= '<p>' . $lang['required_fields_not_filled'] . '</p>';
							$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
						}
						if ($pass_the_form != 'Yes') {
							// if we're not going to pass it, tell that they forgot to fill in one of the fields
							$display .= '<p>' . $lang['required_fields_not_filled'] . '</p>';
							$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
						}
					}

					if ($pass_the_form == 'Yes') {
						// what the program should do if the form is valid
						// generate a random number to enter in as the password (initially)
						// we'll need to know the actual account id to help with retrieving the user
						// We will be putting in a random number that we know the value of, we can easily
						// retrieve the account id in a few moment
						// check to see if moderation is turned on...
						if ($config['moderate_' . $type . 's'] == 1) {
							$set_active = "no";
						} else {
							if ($type == 'agent') {
								if ($config["agent_default_active"] == 0) {
									$set_active = "no";
								} else {
									$set_active = "yes";
								}
							} else {
								$set_active = "yes";
							}
						}

						if ($config["require_email_verification"] == 1){
							$set_active = "no";
						}

						$sql_user_name = $misc->make_db_safe($_POST['edit_user_name']);
						$md5_user_pass = md5($_POST['edit_user_pass']);
						$md5_user_pass = $misc->make_db_safe($md5_user_pass);
						$sql_user_email = $misc->make_db_safe($_POST['user_email']);
						$sql_set_active = $misc->make_db_safe($set_active);
						// create the account with the random number as the password
						$sql = 'INSERT INTO ' . $config['table_prefix'] . 'userdb (userdb_user_name, userdb_user_password, userdb_user_first_name,userdb_user_last_name, userdb_emailaddress, userdb_creation_date,userdb_last_modified, userdb_active,	userdb_comments,userdb_is_admin,userdb_can_edit_site_config,userdb_can_edit_member_template,userdb_can_edit_agent_template,userdb_can_edit_listing_template,userdb_can_feature_listings,userdb_can_view_logs, userdb_hit_count,userdb_can_moderate,userdb_can_edit_pages,userdb_can_have_vtours,userdb_is_agent,userdb_limit_listings,userdb_can_edit_expiration,userdb_can_export_listings,userdb_can_edit_all_users,userdb_can_edit_all_listings,userdb_can_edit_property_classes,userdb_can_have_files,userdb_can_have_user_files) VALUES (' . $sql_user_name . ', ' . $md5_user_pass . ', ' . $sql_user_first_name . ', ' . $sql_user_last_name . ', ' . $sql_user_email . ', ' . $conn->DBDate(time()) . ',' . $conn->DBTimeStamp(time()) . ','. $sql_set_active . ',\'\',\'no\',\'no\',\'no\',\'no\',\'no\',\'no\',\'no\',0,\'no\',\'no\',\'no\',\'no\',0,\'no\',\'no\',\'no\',\'no\',\'no\',\'no\',\'no\')';
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$new_user_id = $conn->Insert_ID(); // this is the new user's ID number
						// Update Agent Settings
						if ($type == 'agent') {
							$is_agent = $misc->make_db_safe("yes");
							if ($config["agent_default_admin"] == 0) {
								$agent_default_admin = $misc->make_db_safe('no');
							}else {
								$agent_default_admin = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_feature"] == 0) {
								$agent_default_feature = $misc->make_db_safe('no');
							}else {
								$agent_default_feature = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_moderate"] == 0) {
								$agent_default_moderate = $misc->make_db_safe('no');
							}else {
								$agent_default_moderate = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_logview"] == 0) {
								$agent_default_logview = $misc->make_db_safe('no');
							}else {
								$agent_default_logview = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_edit_site_config"] == 0) {
								$agent_default_edit_site_config = $misc->make_db_safe('no');
							}else {
								$agent_default_edit_site_config = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_edit_member_template"] == 0) {
								$agent_default_edit_member_template = $misc->make_db_safe('no');
							}else {
								$agent_default_edit_member_template = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_edit_agent_template"] == 0) {
								$agent_default_edit_agent_template = $misc->make_db_safe('no');
							}else {
								$agent_default_edit_agent_template = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_edit_listing_template"] == 0) {
								$agent_default_edit_listing_template = $misc->make_db_safe('no');
							}else {
								$agent_default_edit_listing_template = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_canChangeExpirations"] == 0) {
								$agent_default_canChangeExpirations = $misc->make_db_safe('no');
							} else {
								$agent_default_canChangeExpirations = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_editpages"] == 0) {
								$agent_default_editpages = $misc->make_db_safe('no');
							}else {
								$agent_default_editpages = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_havevtours"] == 0) {
								$agent_default_havevtours = $misc->make_db_safe('no');
							}else {
								$agent_default_havevtours = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_havefiles"] == 0) {
								$agent_default_havefiles = $misc->make_db_safe('no');
							}else {
								$agent_default_havefiles = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_have_user_files"] == 0) {
								$agent_default_have_user_files = $misc->make_db_safe('no');
							}else {
								$agent_default_have_user_files = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_can_export_listings"] == 0) {
								$agent_default_can_export_listings = $misc->make_db_safe('no');
							}else {
								$agent_default_can_export_listings = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_edit_all_users"] == 0) {
								$agent_default_edit_all_users = $misc->make_db_safe('no');
							}else {
								$agent_default_edit_all_users = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_edit_all_listings"] == 0) {
								$agent_default_edit_all_listings = $misc->make_db_safe('no');
							}else {
								$agent_default_edit_all_listings = $misc->make_db_safe('yes');
							}
							if ($config["agent_default_edit_property_classes"] == 0) {
								$agent_default_edit_property_classes = $misc->make_db_safe('no');
							}else {
								$agent_default_edit_property_classes = $misc->make_db_safe('yes');
							}
							$sql = 'UPDATE ' . $config['table_prefix'] . 'userdb SET userdb_is_agent = ' . $is_agent . ', userdb_is_admin = ' . $agent_default_admin . ',
							userdb_can_feature_listings = ' . $agent_default_feature . ', userdb_can_moderate = ' . $agent_default_moderate . ', userdb_can_view_logs =
							' . $agent_default_logview . ', userdb_can_edit_site_config = ' . $agent_default_edit_site_config . ', userdb_can_edit_member_template = ' . $agent_default_edit_member_template . '
							, userdb_can_edit_agent_template = ' . $agent_default_edit_agent_template . ', userdb_can_edit_listing_template = ' . $agent_default_edit_listing_template . ', userdb_can_edit_pages = ' . $agent_default_editpages . ',
							userdb_can_have_vtours = ' . $agent_default_havevtours . ',
							userdb_can_have_files = ' . $agent_default_havefiles . ',
							userdb_can_have_user_files = ' . $agent_default_have_user_files . ', userdb_limit_listings = ' . $config["agent_default_num_listings"] . ', userdb_can_edit_expiration = '.$agent_default_canChangeExpirations.', userdb_can_export_listings = '.$agent_default_can_export_listings.', userdb_can_edit_all_users = '.$agent_default_edit_all_users.', userdb_can_edit_all_listings = '.$agent_default_edit_all_listings.', userdb_can_edit_property_classes = '.$agent_default_edit_property_classes.' WHERE userdb_id = ' . $new_user_id;

							$recordSet = $conn->Execute($sql);
							if ($recordSet === false) {
								$misc->log_error($sql);
							}
						}else {
							$is_agent = $misc->make_db_safe("no");
							$agent_default_admin = $misc->make_db_safe('no');
							$sql = 'UPDATE ' . $config['table_prefix'] . 'userdb SET userdb_is_agent = ' . $is_agent . ', userdb_is_admin = ' . $agent_default_admin . ' WHERE userdb_id = ' . $new_user_id;
							$recordSet = $conn->Execute($sql);
							if ($recordSet === false) {
								$misc->log_error($sql);
							}
						}
						// Update Remaining Variables
						$message = user_managment::updateUserData($new_user_id);
						if ($message == 'success') {
							// $user_name = $misc->make_db_unsafe($_POST['edit_user_name']);
							$display .= '<p>' . $lang['user_creation_username_success'] . ', ' . $_POST['edit_user_name'] . '</p>';
							if ($config['moderate_' . $type . 's'] == 1) {
								// if moderation is turned on...
								$display .= '<p>' . $lang['admin_new_user_moderated'] . '</p>';
							} elseif ($config["require_email_verification"] == 1) {
								$display .= '<p>' . $lang['admin_new_user_email_verification'] . '</p>';
							} else {
								//log the user in
								$_POST['user_name'] = $_POST['edit_user_name'];
								$_POST['user_pass'] = $_POST['edit_user_pass'];
								login::loginCheck('Member');
								$display .= '<p>' . $lang['you_may_now_view_priv'] . '</p>';
							}
							$misc->log_action ($lang['log_created_user'] . ': ' . $_POST['edit_user_name']);
							if ($config['email_notification_of_new_users'] == 1 && $config["require_email_verification"] == 0) {
								// if the site admin should be notified when a new user is added
								$message = $_SERVER['REMOTE_ADDR'] . ' -- ' . date('F j, Y, g:i:s a') . "\r\n\r\n" . $lang['admin_new_user'] . ":\r\n" . $config['baseurl'] . '/admin/index.php?action=user_manager&edit=' . $new_user_id . "\r\n";

								$header = 'From: ' . $config['admin_name'] . ' <' . $config['admin_email'] . ">\r\n";
								$header .= "X-Sender: $config[admin_email]\r\n";
								$header .= "Return-Path: $config[admin_email]\r\n";

								mail("$config[admin_email]", "$lang[admin_new_user]", $message, $header);
							} // end if
							if (($config['email_information_to_new_users'] == 1) || ($config["require_email_verification"] == 1)) {
								$message = $lang['user_email_message'] . ":\r\n\r\n";
								if ($config['moderate_' . $type . 's'] == 1) {
									$message .= $lang['admin_new_user_moderated']."\r\n\r\n";
								}
								if ($config["require_email_verification"] == 1) {
									$message .= $lang['admin_new_user_email_verification_message']."\r\n\r\n";
									$message .= $config['baseurl'].'/index.php?action=verify_email&id='.$new_user_id.'&key='.md5($new_user_id.':'.$_POST['user_email'])."\r\n\r\n";
								}
								$message .= $lang['user_email_login_information']."\r\n\r\n".$lang['user_email_username']."\r\n\r\n".$lang['user_email_password']."\r\n\r\n".$lang['user_email_login_link'];
								if ($type == 'member')
								{
									$message .= $config['baseurl'].'/index.php?action=member_login';
								}
								if ($type == 'agent')
								{
									$message .= $config['baseurl'].'/admin/index.php';
								}
								$message .= "\r\n\r\n".$lang['user_email_privacy_info'];
								if (isset($config['site_email']) && $config['site_email'] != '') {
									$sender_email = $config['site_email'];
								} else {
									$sender_email = $config['admin_email'];
								}
								$header = 'From: ' . $config['admin_name'] . ' <' . $sender_email . ">\r\n";
								$header .= "X-Sender: $sender_email\r\n";
								$header .= "Return-Path: $sender_email\r\n";
								$header .= 'Content-Type: text/plain; charset="' . $config["charset"] . '"'. "\r\n";
								mail("$_POST[user_email]", "$lang[email_user_subject]", $message, $header);
							} //end if
						} // end if
						else {
							$display .= '<p>' . $lang['alert_site_admin'] . '</p>';
						} // end else
					} // end if
				} // end else
			}else {

				$display .= '<form action="?action=signup&amp;type=' . $type . '" method="post">';
				$display .= '<table class="form_main">';
				if ($type == 'agent') {
					$display .= '<tr><td colspan="2" class="row_main"><h3>' . $lang['user_signup_agent'] . '</h3></td></tr>';
				} else {
					$display .= '<tr><td colspan="2" class="row_main"><h3>' . $lang['user_signup'] . '</h3></td></tr>';
				}
				$display .= '<tr>';
				$display .= '	<td align="right" class="row_main"><strong>' . $lang['user_name'] . ' <span class="required">*</span></strong></td>';
				$display .= '	<td align="left" class="row_main"> <input type="text" name="edit_user_name" /></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="row_main"><strong>' . $lang['user_password'] . ' <span class="required">*</span></strong></td>';
				$display .= '<td align="left" class="row_main"> <input type="password" name="edit_user_pass" /></td>';
				$display .= '</tr>';

				$display .= '<tr>';
				$display .= '	<td align="right" class="row_main"><strong>' . $lang['user_password'] . '</strong> (' . $lang['again'] . ')<strong><span class="required">*</span></strong> </td>';
				$display .= '	<td align="left" class="row_main"> <input type="password" name="edit_user_pass2" /></td>';
				$display .= '</tr>';
				// First Name
				$display .= '<tr>';
				$display .= '<td align="right" class="row_main"><b>' . $lang['user_manager_first_name'] . '</b> <b><span class="required">*</span></b></td>';
				$display .= '<td align="left" class="row_main"> <input type="text" name="user_first_name" /></td>';
				$display .= '</tr>';
				// Last name
				$display .= '<tr>';
				$display .= '<td align="right" class="row_main"><b>' . $lang['user_manager_last_name'] . '</b> <b><span class="required">*</span></b></td>';
				$display .= '<td align="left" class="row_main"> <input type="text" name="user_last_name" /></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="row_main"><strong>' . $lang['user_email'] . '</strong> <strong><span class="required">*</span></strong><br />'.$lang['email_not_displayed'].'</td>';
				$display .= '	<td align="left" class="row_main"> <input type="text" name="user_email" /></td>';
				$display .= '</tr>';

				global $conn;
				$sql = 'SELECT ' . $type . 'formelements_field_type, ' . $type . 'formelements_field_name, ' . $type . 'formelements_field_caption, ' . $type . 'formelements_default_text, ' . $type . 'formelements_field_elements, ' . $type . 'formelements_required, ' . $type . 'formelements_tool_tip FROM ' . $config['table_prefix'] . $type . 'formelements ORDER BY ' . $type . 'formelements_rank, ' . $type . 'formelements_field_caption';
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				} while (!$recordSet->EOF) {
					$field_type = $recordSet->fields[$type . 'formelements_field_type'];
					$field_name = $recordSet->fields[$type . 'formelements_field_name'];
					$field_caption = $recordSet->fields[$type . 'formelements_field_caption'];
					$default_text = $recordSet->fields[$type . 'formelements_default_text'];
					$field_elements = $recordSet->fields[$type . 'formelements_field_elements'];
					$required = $recordSet->fields[$type . 'formelements_required'];
					$tool_tip = $recordSet->fields[$type . 'formelements_tool_tip'];

					$field_type = $misc->make_db_unsafe($field_type);
					$field_name = $misc->make_db_unsafe($field_name);
					$field_caption = $misc->make_db_unsafe($field_caption);
					$default_text = $misc->make_db_unsafe($default_text);
					$field_elements = $misc->make_db_unsafe($field_elements);
					$required = $misc->make_db_unsafe($required);
					$tool_tip = $misc->make_db_unsafe($tool_tip);
					$display .= $forms->renderFormElement($field_type, $field_name, $field_caption, $default_text, $field_elements, $required,'',$tool_tip);

					$recordSet->MoveNext();
				} // end while
				if($config["use_signup_image_verification"] == 1 ) {
					$display .= '<tr>';
					$display .= '<td align="right" class="row_main"></td>';
					$display .= '<td align="left" class="row_main"><img src="'.$config['baseurl'].'/include/class/captcha/captcha_image.php" /></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td align="right" class="row_main"><b>' . $lang['email_verification_code'] . '</b> <b><span class="required">*</span></b></td>';
					$display .= '<td align="left" class="row_main"> <input id="security_code" name="security_code" type="text" /></td>';
					$display .= '</tr>';
				}
				$display .= $forms->renderFormElement("submit", "", "$lang[submit]", "", "", "", "");
				$display .= '<tr><td colspan="2" align="center" class="row_main">' . $lang['required_form_text'] . '</td></tr>';
				$display .= '</table>';
				$display .= '</form>';
			}


		} // end if ($config[allow_user_signup] === "1")
		else {
			// if users can't sign up...
			$display .= '<h3>' . $lang['no_user_signup'] . '</h3>';
		}
		return $display;
	} //End function memeber_signup()
	function show_quick_edit_bar()
	{
		global $conn, $config, $lang;
		$display = '';
		$display .= '<span class="section_header"><a href="index.php?action=user_manager">' . $lang['user_manager'] . '</a></span><br />';
		$display .= '<br /><table width="600" border="1" align="center" cellpadding="0" cellspacing="0">';
		$display .= '<tr>';
		$display .= '<td width="88%">';
		$display .= '<form name="form2" method="POST" action="index.php?action=user_manager">';
		$display .= '<strong>' . $lang['user_manager_quick_edit'] . ':</strong>';
		$display .= '<select name="lookup_field">';
		$display .= '<option value="userdb_id" selected="selected">' . $lang['user_manager_user_id'] . '</option>';
		$display .= '<option value="userdb_emailaddress">' . $lang['user_manager_email_address'] . '</option>';
		$display .= '<option value="userdb_user_name">' . $lang['user_manager_user_name'] . '</option>';
		$display .= '</select>';
		$display .= '<input type="text" name="lookup_value" />';
		$display .= '<input type="submit" name="Lookup" value="' . $lang['user_manager_lookup'] . '" />';
		$display .= '</form>';
		$display .= '</td>';
		$display .= '<td width="12%" rowspan="2">';
		$display .= '<a href="index.php?action=user_manager&amp;add_user=new"><img src="images/' . $config['lang'] . '/user_manager_add.jpg" width="48" height="48" alt="' . $lang['user_manager_add_user'] . '"></a>';
		$display .= '</td>';
		$display .= '</tr>';
		$display .= '<tr>';
		$display .= '<td>';
		$display .= '<form name="form1" method="post" action="">';
		$display .= '<strong>' . $lang['user_manager_show'] . ':</strong>';
		$display .= '<select name="filter">';
		$display .= '<option selected="selected">' . $lang['user_manager_show_all'] . '</option>';
		$display .= '<option value="agents" ';
		if (isset($_POST['filter']) && $_POST['filter'] == 'agents') {
			$display .= ' selected="selected" ';
		}
		$display .= '>' . $lang['user_manager_agents'] . '</option>';
		// Check if user logged is admin
		$security = login::loginCheck('Admin', true);
		if ($security === true) {
			$display .= '<option value="admins" ';
			if (isset($_POST['filter']) && $_POST['filter'] == 'admins') {
				$display .= ' selected="selected" ';
			}
			$display .= '>' . $lang['user_manager_admins'] . '</option>';
		}
		$display .= '<option value="members" ';
		if (isset($_POST['filter']) && $_POST['filter'] == 'members') {
			$display .= ' selected="selected" ';
		}
		$display .= '>' . $lang['user_manager_members'] . '</option>';
		$display .= '</select>';
		$display .= '<input name="Filter" type="submit" value="' . $lang['user_manager_filter'] . '" />';
		$display .= '</form>';
		$display .= '</td>';
		$display .= '</tr>';
		$display .= '</table>';
		$display .= '<br />';
		return $display;
	}
	function show_user_manager()
	{
		$display = '';
		$security = login::loginCheck('edit_all_users', true);
		if ($security === true) {
			if (isset($_GET['delete'])) {
				$sucess = user_managment::delete_user(intval($_GET['delete']));
				if ($sucess !== true) {
					$display .= $sucess;
				}
			}
			if (isset($_GET['add_user'])) {
				$display .= user_managment::show_quick_edit_bar();
				$display .= user_managment::add_user();
			}elseif (isset($_GET['edit'])) {
				$display .= user_managment::show_quick_edit_bar();
				$display .= user_managment::edit_user(intval($_GET['edit']));
			}elseif (isset($_POST['action']) && $_POST['action'] == 'createNewUser') {
				$display .= user_managment::show_quick_edit_bar();
				$display .= user_managment::create_user();
			}elseif (isset($_POST['lookup_field']) && $_POST['lookup_value'] != '') {
				$display .= user_managment::show_quick_edit_bar();
				$display .= user_managment::lookup();
			}else {
				$display .= user_managment::show_quick_edit_bar();
				if (isset($_POST['filter'])) {
					$display .= user_managment::show_users($_POST['filter']);
				}else {
					$display .= user_managment::show_users();
				}
			}
		}else {
			if (isset($_GET['edit'])) {
				$display .= user_managment::edit_user(intval($_GET['edit']));
			}
		}
		return $display;
	}
	function lookup()
	{
		if (isset($_POST['lookup_field']) && $_POST['lookup_value'] != '') {
			global $conn, $config, $lang;
			require_once($config['basepath'] . '/include/user.inc.php');
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$display = '';
			$lookup_value = $misc->make_db_safe($_POST['lookup_value']);
			$sql = 'SELECT userdb_id FROM ' . $config['table_prefix'] . 'userdb WHERE ' . $_POST['lookup_field'] . ' = ' . $lookup_value;
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$id = $recordSet->fields[0];
			if ($id != '') {
				$security = login::loginCheck('Admin', true);
				if ($security === true) {
					$display .= user_managment::edit_user($id);
				} else {
					$user_type = user::get_user_type($id);
					if ($user_type === admin) {
						$display .= $lang['user_manager_permission_denied'];
					} else {
						$display .= user_managment::edit_user($id);
					}
				}
			}else {
				$display .= '<div align="center" class="redtext">' . $lang['user_manager_user_not_found'] . '</div>';
				$display .= user_managment::show_users();
			}
			return $display;
		}
	}
	function add_user()
	{
		global $conn, $config, $lang;
		$security = false;
		if ((($config["demo_mode"] != 1) &&($_SESSION['edit_all_users'] == 'yes')) || ($_SESSION['admin_privs'] == 'yes')) {
			$security = true;
		}
		if ($security === true) {
			$display = '';
			$display .= '<table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td>';
			$display .= '<form action="index.php?action=user_manager" method="post">';
			$display .= '<input type="hidden" name="action" value="createNewUser" />';
			$display .= '<table class="add_user_table">';

			$display .= '<tr><td colspan="2" class="row_main"><h3>' . $lang['create_new_user'] . '</h3></td></tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="row_main"><b>' . $lang['user_name'] . '<span class="required">*</span></b></td>';
			$display .= '<td align="left" class="row_main"> <input type="text" name="edit_user_name" /></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="row_main"><b>' . $lang['user_password'] . ' <span class="required">*</span></b></b></td>';
			$display .= '<td align="left" class="row_main"> <input type="password" name="edit_user_pass" /></td>';
			$display .= '</tr>';

			$display .= '<tr>';
			$display .= '<td align="right" class="row_main"><b>' . $lang['user_password'] . '</b> (' . $lang['again'] . ')<b><span class="required">*</span></b> </td>';
			$display .= '<td align="left" class="row_main"> <input type="password" name="edit_user_pass2" /></td>';
			$display .= '</tr>';
			// First Name
			$display .= '<tr>';
			$display .= '<td align="right" class="row_main"><b>' . $lang['user_manager_first_name'] . '</b> <b><span class="required">*</span></b></td>';
			$display .= '<td align="left" class="row_main"> <input type="text" name="user_first_name" /></td>';
			$display .= '</tr>';
			// Last name
			$display .= '<tr>';
			$display .= '<td align="right" class="row_main"><b>' . $lang['user_manager_last_name'] . '</b> <b><span class="required">*</span></b></td>';
			$display .= '<td align="left" class="row_main"> <input type="text" name="user_last_name" /></td>';
			$display .= '</tr>';
			// Email
			$display .= '<tr>';
			$display .= '<td align="right" class="row_main"><b>' . $lang['user_email'] . '</b> <b><span class="required">*</span></b></td>';
			$display .= '<td align="left" class="row_main"> <input type="text" name="user_email" /></td>';
			$display .= '</tr>';
			// is the user active?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_active'] . ': </b></td>';
			$display .= '<td align=left><select name="edit_active" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// is the user an administrator?
			if ($_SESSION['admin_privs'] == 'yes') {
				$display .= '<tr><td align=right><b>' . $lang['user_editor_isAdmin'] . ': </b></td>';
				$display .= '<td align=left><select id="edit_isAdmin" name="edit_isAdmin" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			} else {
				$display .= '<input type="hidden" name="edit_isAdmin" value="no">';
			}
			// is the user an agent?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_isAgent'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_isAgent" name="edit_isAgent" size="1" onchange="ChooseState()"><option value="no">no<option value="yes">yes</select></td></tr>';
			// limit # of listings?
			$display .= '<tr><td align=right><b>' . $lang['user_manager_limitListings'] . ': </b></td>';
			$display .= '<td align=left><input id="limitListings" name="limitListings" size="6" value="' . $config["agent_default_num_listings"] . '" /><i>(-1 = Unlimited)</i></td></tr>';
			// limit # of featured listings?
			$display .= '<tr><td align=right><b>' . $lang['user_manager_limitFeaturedListings'] . ': </b></td>';
			$display .= '<td align=left><input id="edit_limitFeaturedListings" name="edit_limitFeaturedListings" size="6" value="' . $config["agent_default_num_featuredlistings"] . '" /><i>(-1 = Unlimited)</i></td></tr>';
			// user display order?
			$display .= '<tr><td align=right><b>' . $lang['user_manager_displayorder'] . ': </b></td>';
			$display .= '<td align=left><input id="edit_userRank" name="edit_userRank" size="6" value="0" /></td></tr>';

			// can they edit other agents lisings?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_all_listings'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canEditAllListings" name="edit_canEditAllListings" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they edit other users accounts?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_all_users'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canEditAllUsers" name="edit_canEditAllUsers" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they edit site config?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_site_config'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canEditSiteConfig" name="edit_canEditSiteConfig" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they edit member templates?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_member_template'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canEditMemberTemplate" name="edit_canEditMemberTemplate" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they edit agent templates?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_agent_template'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canEditAgentTemplate" name="edit_canEditAgentTemplate" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they edit property classes?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_property_classes'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canEditPropertyClasses" name="edit_canEditPropertyClasses" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they edit listing templages?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_listing_template'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canEditListingTemplate" name="edit_canEditListingTemplate" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they view logs?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_view_logs'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canViewLogs" name="edit_canViewLogs" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they moderate incoming listings?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_moderator'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canModerate" name="edit_canModerate" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they feature listings?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_feature_listings'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canFeatureListings" name="edit_canFeatureListings" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they edit listing expirations?
			$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_listing_expiration'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canEditListingExpiration" name="edit_canEditListingExpiration" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they export listings?
			if ($config["export_listings"] == 1) {
				$display .= '<tr><td align=right><b>' . $lang['user_editor_can_export_listings'] . ': </b></td>';
				$display .= '<td align=left><select id="edit_canExportListings" name="edit_canExportListings" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			}else {
				$display .= '<input type="hidden" id="edit_canExportListings" name="edit_canExportListings" value="no" />';
			}
			// can they edit pages?
			$display .= '<tr><td align=right><b>' . $lang['user_manager_can_edit_pages'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canPages" name="edit_canPages" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they have vtours
			$display .= '<tr><td align=right><b>' . $lang['user_manager_can_have_vtours'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canVtour" name="edit_canVtour" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they have listings files
			$display .= '<tr><td align=right><b>' . $lang['user_manager_can_have_files'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canFiles" name="edit_canFiles" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they have user files
			$display .= '<tr><td align=right><b>' . $lang['user_manager_can_have_user_files'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canUserFiles" name="edit_canUserFiles" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// can they access addon manager
			$display .= '<tr><td align=right><b>' . $lang['user_editor_can_manage_addons'] . ': </b></td>';
			$display .= '<td align=left><select id="edit_canManageAddons" name="edit_canManageAddons" size="1"><option value="no">no<option value="yes">yes</select></td></tr>';
			// required text message
			$display .= '<tr><td colspan="2" align="center">' . $lang['required_form_text'] . '</td></tr>';
			// add user button
			$display .= '<tr><td colspan="2" align="right"><input type="submit" value="' . $lang['user_manager_add_user'] . '" /></td></tr>';
			$display .= '</form>';
			$display .= '</table>';
			$display .= '</td></tr></table>';
			$display .= '<script type="text/javascript"> window.onload = makeDisable();</script>';
			return $display;
		}
	}
	function edit_member_profile($user_id)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		// Set Variable to hold errors
		// Verify ID is Numeric
		if (!is_numeric($user_id)) {
			return $lang['user_manager_invalid_user_id'];
		}
		if ($_SESSION['userID'] == $user_id && $_SESSION['is_member'] == 'yes') {
			$sql_edit = intval($_SESSION['userID']);
			$raw_id = intval($_SESSION['userID']);
		}else {
			return $lang['user_manager_permission_denied'];
		}
		// $raw_id = $misc->make_db_unsafe($sql_edit);
		// Save any Changes that were posted
		if (isset($_POST['edit'])) {
			$display .= user_managment::update_member_profile($raw_id);
		}
		// Show Account Edit Form
		require_once($config['basepath'] . '/include/forms.inc.php');
		$forms = new forms();
		$display .= '<table border="0" cellpadding="0" cellspacing="0"><tr><td>';
		$display .= '<table class="edit_users">';
		$display .= '<tr><td colspan="2"><h3>' . $lang['user_manager_edit_user'] . '</h3></td></tr>';
		$display .= '<tr>';
		$display .= '<td valign="top" class="row_main">';
		// first, grab the user's main info
		$sql = 'SELECT * FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $sql_edit;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			// collect up the main DB's various fields
			$_POST['edit_user_name'] = $misc->make_db_unsafe ($recordSet->fields['userdb_user_name']);
			$edit_emailAddress = $misc->make_db_unsafe ($recordSet->fields['userdb_emailaddress']);
			// $edit_comments = $misc->make_db_unsafe ($recordSet->fields['userdb_comments']);
			$edit_firstname = $misc->make_db_unsafe ($recordSet->fields['userdb_user_first_name']);
			$edit_lastname = $misc->make_db_unsafe ($recordSet->fields['userdb_user_last_name']);
			$edit_active = $recordSet->fields['userdb_active'];
			$edit_isAgent = $recordSet->fields['userdb_is_agent'];
			$edit_isAdmin = $recordSet->fields['userdb_is_admin'];
			$edit_limitListings = $recordSet->fields['userdb_limit_listings'];
			$edit_canEditAllListings = $recordSet->fields['userdb_can_edit_all_listings'];
			$edit_canEditAllUsers = $recordSet->fields['userdb_can_edit_all_users'];
			$edit_canEditSiteConfig = $recordSet->fields['userdb_can_edit_site_config'];
			$edit_canEditMemberTemplate = $recordSet->fields['userdb_can_edit_member_template'];
			$edit_canEditAgentTemplate = $recordSet->fields['userdb_can_edit_agent_template'];
			$edit_canEditListingTemplate = $recordSet->fields['userdb_can_edit_listing_template'];
			$edit_canExportListings = $recordSet->fields['userdb_can_export_listings'];
			$edit_canEditListingExpiration = $recordSet->fields['userdb_can_edit_expiration'];
			$edit_canModerate = $recordSet->fields['userdb_can_moderate'];
			$edit_canViewLogs = $recordSet->fields['userdb_can_view_logs'];
			$edit_canVtour = $recordSet->fields['userdb_can_have_vtours'];
			$edit_canFiles = $recordSet->fields['userdb_can_have_files'];
			$edit_canUserFiles = $recordSet->fields['userdb_can_have_user_files'];
			$edit_canFeatureListings = $recordSet->fields['userdb_can_feature_listings'];
			$edit_canPages = $recordSet->fields['userdb_can_edit_pages'];
			$last_modified = $recordSet->UserTimeStamp($recordSet->fields['userdb_last_modified'], $config["date_format_timestamp"]);
			$edit_canManageAddons = $recordSet->fields['userdb_can_manage_addons'];
			$recordSet->MoveNext();
		} // end while
		// now, display all that stuff
		$display .= '<form name="updateUser" action="index.php?action=edit_profile&amp;user_id=' . $raw_id . '" method="post">';
		$display .= '<input type="hidden" name="edit" value="' . $raw_id . '" />';
		$display .= '<table class="edit_users"><tr><td>';

		$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_name'] . ':</strong></td><td align="left" class="row_main">' . $_POST['edit_user_name'] . '</td></tr>';
		$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_manager_first_name'] . ': <span class="required">*</span></strong></td><td align="left" class="row_main"> <input type="text" name="user_first_name" value="' . $edit_firstname . '" /> ';
		$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_manager_last_name'] . ': <span class="required">*</span></strong></td><td align="left" class="row_main"> <input type="text" name="user_last_name" value="' . $edit_lastname . '" /> ';
		$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['last_modified'] . ':</strong></td><td align="left">' . $last_modified . '</td></tr>';
		if (($config["demo_mode"] != 1) || ($_SESSION['admin_privs'] == 'yes')) {
			$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_password'] . ': <span class="required">*</span></strong></td><td align="left" class="row_main"> <input type="password" name="edit_user_pass" /></td></tr>';
			$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_password'] . ' (' . $lang['again'] . ') <span class="required">*</span></strong> </td><td align="left" class="row_main"> <input type="password" name="edit_user_pass2" /></td></tr>';
		} else {
			$display .= '<input type="hidden" name="edit_user_pass" value="">';
			$display .= '<input type="hidden" name="edit_user_pass2" value="">';
		}
		$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_email'] . ': <span class="required">*</span></strong><br />'.$lang['email_not_displayed'].'</td><td align="left" class="row_main"> <input type="text" name="user_email" value="' . $edit_emailAddress . '" /> ';

		$db_to_use = 'memberformelements';

		$sql = 'SELECT ' . $db_to_use . '_field_name, userdbelements_field_value, ' . $db_to_use . '_field_type, ' . $db_to_use . '_rank, ' . $db_to_use . '_field_caption, ' . $db_to_use . '_default_text, ' . $db_to_use . '_required, ' . $db_to_use . '_field_elements, ' . $db_to_use . '_tool_tip FROM ' . $config['table_prefix'] . $db_to_use . ' left join ' . $config['table_prefix'] . 'userdbelements on userdbelements_field_name = ' . $db_to_use . '_field_name and userdb_id = ' . $sql_edit . ' ORDER BY ' . $db_to_use . '_rank';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$field_name = $misc->make_db_unsafe ($recordSet->fields[$db_to_use . '_field_name']);
			$field_value = $misc->make_db_unsafe ($recordSet->fields['userdbelements_field_value']);
			$field_type = $misc->make_db_unsafe ($recordSet->fields[$db_to_use . '_field_type']);
			$field_caption = $misc->make_db_unsafe($recordSet->fields[$db_to_use . '_field_caption']);
			$default_text = $misc->make_db_unsafe($recordSet->fields[$db_to_use . '_default_text']);
			$field_elements = $misc->make_db_unsafe($recordSet->fields[$db_to_use . '_field_elements']);
			$required = $misc->make_db_unsafe($recordSet->fields[$db_to_use . '_required']);
			$tool_tip = $misc->make_db_unsafe($recordSet->fields[$db_to_use . '_tool_tip']);
			// pass the data to the function
			$display .= $forms->renderExistingFormElement($field_type, $field_name, $field_value, $field_caption, $default_text, $required, $field_elements,'',$tool_tip);
			$recordSet->MoveNext();
		} // end while
		$display .= '<tr><td colspan="2" align="center" class="row_main">' . $lang['required_form_text'] . '</td></tr>';
		$display .= '<tr><td colspan="2" align="center" class="row_main"><input type="submit" value="' . $lang['update_button'] . '" /></td></tr></table></form>';
		$display .= '</td></tr></table>';
		$display .= '</td></tr></table>';
		return $display;
	}
	function edit_user($user_id)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/user.inc.php');
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/listing_editor.inc.php');
		$listing = new listing_editor();
		$display = '';
		// Set Variable to hold errors
		// Verify ID is Numeric
		if (!is_numeric($user_id)) {
			return $lang['user_manager_invalid_user_id'];
		}
		// Admins can edit any user. Anyone can edit there own information.
		if (($_SESSION['admin_privs'] == 'yes' || $_SESSION['edit_all_users']=='yes') && $user_id != '') {
			$security = login::loginCheck('Admin', true);
			if ($security === true) {
				$sql_edit = intval($user_id);
				$raw_id = $user_id;
			} else {
				$user_type = user::get_user_type($user_id);
				if ($user_type === admin) {
					// Agents cannot edit Admin account
					return $lang['user_manager_permission_denied'];
				} else {
					$sql_edit = intval($user_id);
					$raw_id = $user_id;
				}
			}
		}elseif (($_SESSION['admin_privs'] == 'yes' && $user_id == '') || ($_SESSION['userID'] == $user_id)) {
			$sql_edit =intval($_SESSION['userID']);
			$raw_id = intval($_SESSION['userID']);
		}else {
			return $lang['user_manager_permission_denied'];
		}
		// $raw_id = $misc->make_db_unsafe($sql_edit);
		// Save any Changes that were posted
		if (isset($_POST['edit'])) {
			$display .= user_managment::update_user($raw_id);
			if (isset($_POST['edit_listing_active']) && $_POST['edit_listing_active'] != "") {
				$display .= $listing->update_active_status($raw_id, $_POST['edit_listing_active']);
			}
		}
		//Blog Permissions
		$blog_perm[1]=$lang['blog_perm_subscriber'];
		$blog_perm[2]=$lang['blog_perm_contributor'];
		$blog_perm[3]=$lang['blog_perm_author'];
		$blog_perm[4]=$lang['blog_perm_editor'];

		// Show Account Edit Form
		require_once($config['basepath'] . '/include/forms.inc.php');
		$forms = new forms();
		$display .= '<table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td>';
		$display .= '<table class="edit_users">';
		$display .= '<tr><td colspan="2"><h3>' . $lang['user_manager_edit_user'] . '</h3></td></tr>';
		$display .= '<tr>';
		$display .= '<td valign="top" align="center">';
		$display .= '<strong>' . $lang['images'] . '</strong>';
		$display .= '<br />';
		$display .= '<hr width="75%" />';
		$display .= '<form action="' . $config['baseurl'] . '/admin/index.php?action=edit_user_images" method="post" name="edit_user_images"><input type="hidden" name="edit" value="' . $raw_id . '" /><a href="javascript:document.edit_user_images.submit()">' . $lang['edit_images'] . '</a></form>';
		// Show User Images
		$sql = 'SELECT userimages_caption, userimages_file_name, userimages_thumb_file_name FROM ' . $config['table_prefix'] . 'userimages WHERE userdb_id = ' . $sql_edit;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$caption = $misc->make_db_unsafe ($recordSet->fields['userimages_caption']);
			$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['userimages_thumb_file_name']);
			$file_name = $misc->make_db_unsafe ($recordSet->fields['userimages_file_name']);
			// gotta grab the image size
			$imagedata = GetImageSize($config['user_upload_path'] . '/' . $thumb_file_name);
			$imagewidth = $imagedata[0];
			$imageheight = $imagedata[1];
			$shrinkage = $config['thumbnail_width'] / $imagewidth;
			$displaywidth = $imagewidth * $shrinkage;
			$displayheight = $imageheight * $shrinkage;

			$display .= '<a href="' . $config['user_view_images_path'] . '/' . $file_name . '" target="_thumb"> ';

			$display .= '<img src="' . $config['user_view_images_path'] . '/' . $thumb_file_name . '" height="' . $displayheight . '" width="' . $displaywidth . '" /></a><br /> ';
			$display .= '<strong>' . $caption . '</strong><br /><br />';
			$recordSet->MoveNext();
		} // end while
		$display .= '</td>';
		// Place the Files list and edit files link on the edit user profile page if they are allowed to have files.
		if ($_SESSION['admin_privs'] == "yes" || $_SESSION['havefiles'] == "yes") {
			$display .= '<td valign="top" align="center" class="row_main">';
			$display .= '<b>'.$lang['files'].'</b>';
			$display .= '<br />';
			$display .= '<hr width="75%" />';
			$display .= '<form action="index.php?action=edit_user_files" method="post" name="edit_user_files"><input type="hidden" name="edit" value="' . intval($_GET['edit']) . '" /><a href="javascript:document.edit_user_files.submit()">' . $lang['edit_files'] . '</a></form>';
			$display .= '<br />';
			$sql = "SELECT usersfiles_id, usersfiles_caption, usersfiles_file_name FROM " . $config['table_prefix'] . "usersfiles WHERE (userdb_id = $sql_edit)";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			} while (!$recordSet->EOF) {
				$caption = $misc->make_db_unsafe ($recordSet->fields['usersfiles_caption']);
				$file_name = $misc->make_db_unsafe ($recordSet->fields['usersfiles_file_name']);
				$file_id = $misc->make_db_unsafe ($recordSet->fields['usersfiles_id']);
				$iconext = substr(strrchr($file_name, '.'), 1);
				$iconpath = $config["file_icons_path"] . '/' . $iconext . '.png';
				if (file_exists($iconpath)) {
					$icon = $config["listings_view_file_icons_path"] . '/' . $iconext . '.png';
				} else {
					$icon = $config["listings_view_file_icons_path"] . '/default.png';
				}
				//
				$file_download_url = 'index.php?action=create_download&amp;ID='.$sql_edit.'&amp;file_id='.$file_id.'&amp;type=user';
				$display .= '<a href="'.$config['baseurl'].'/'.$file_download_url.'" target="_thumb">';
				$display .= '<img src="'.$icon.'" height="'.$config["file_icon_height"].'" width="'.$config["file_icon_width"].'" alt="'.$file_name.'" /><br />';
				$display .= '<strong>'.$file_name.'</strong></a><br />';
				$display .= '<strong>'.$caption.'</strong><br /><br />';
				$recordSet->MoveNext();
			} // end while
			$display .= '</td>';
		}
		$display .= '<td valign="top" class="row_main">';
		// first, grab the user's main info
		$sql = 'SELECT * FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $sql_edit;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			// collect up the main DB's various fields
			$_POST['edit_user_name'] = $misc->make_db_unsafe ($recordSet->fields['userdb_user_name']);
			$edit_emailAddress = $misc->make_db_unsafe ($recordSet->fields['userdb_emailaddress']);
			// $edit_comments = $misc->make_db_unsafe ($recordSet->fields['userdb_comments']);
			$edit_firstname = $misc->make_db_unsafe ($recordSet->fields['userdb_user_first_name']);
			$edit_lastname = $misc->make_db_unsafe ($recordSet->fields['userdb_user_last_name']);
			$edit_active = $recordSet->fields['userdb_active'];
			$edit_isAgent = $recordSet->fields['userdb_is_agent'];
			$edit_isAdmin = $recordSet->fields['userdb_is_admin'];
			$edit_limitListings = $recordSet->fields['userdb_limit_listings'];
			$edit_limitFeaturedListings = $recordSet->fields['userdb_featuredlistinglimit'];
			$edit_userRank = $recordSet->fields['userdb_rank'];
			$edit_canEditAllListings = $recordSet->fields['userdb_can_edit_all_listings'];
			$edit_canEditAllUsers = $recordSet->fields['userdb_can_edit_all_users'];
			$edit_canEditSiteConfig = $recordSet->fields['userdb_can_edit_site_config'];
			$edit_canEditMemberTemplate = $recordSet->fields['userdb_can_edit_member_template'];
			$edit_canEditAgentTemplate = $recordSet->fields['userdb_can_edit_agent_template'];
			$edit_canEditListingTemplate = $recordSet->fields['userdb_can_edit_listing_template'];
			$edit_canExportListings = $recordSet->fields['userdb_can_export_listings'];
			$edit_canEditListingExpiration = $recordSet->fields['userdb_can_edit_expiration'];
			$edit_canEditPropertyClasses = $recordSet->fields['userdb_can_edit_property_classes'];
			$edit_canModerate = $recordSet->fields['userdb_can_moderate'];
			$edit_canViewLogs = $recordSet->fields['userdb_can_view_logs'];
			$edit_canVtour = $recordSet->fields['userdb_can_have_vtours'];
			$edit_canFiles = $recordSet->fields['userdb_can_have_files'];
			$edit_canUserFiles = $recordSet->fields['userdb_can_have_user_files'];
			$edit_canFeatureListings = $recordSet->fields['userdb_can_feature_listings'];
			$edit_canPages = $recordSet->fields['userdb_can_edit_pages'];
			$edit_BlogPrivileges = $recordSet->fields['userdb_blog_user_type'];
			$last_modified = $recordSet->UserTimeStamp($recordSet->fields['userdb_last_modified'], $config["date_format_timestamp"]);
			$edit_canManageAddons = $recordSet->fields['userdb_can_manage_addons'];
			$recordSet->MoveNext();
		} // end while

		// now, display all that stuff
		$display .= '<form name="updateUser" action="index.php?action=user_manager&amp;edit=' . $raw_id . '" method="post">';
		$display .= '<input type="hidden" name="edit" value="' . $raw_id . '" />';
		$display .= '<table class="edit_users"><tr><td>';

		$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_name'] . ':</strong></td><td align="left" class="row_main">' . $_POST['edit_user_name'] . '</td></tr>';
		$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_manager_first_name'] . ': <span class="required">*</span></strong></td><td align="left" class="row_main"> <input type="text" name="user_first_name" value="' . $edit_firstname . '" /> ';
		$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_manager_last_name'] . ': <span class="required">*</span></strong></td><td align="left" class="row_main"> <input type="text" name="user_last_name" value="' . $edit_lastname . '" /> ';
		$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['last_modified'] . ':</strong></td><td align="left">' . $last_modified . '</td></tr>';


		if (($config["demo_mode"] != 1) || ($_SESSION['admin_privs'] == 'yes')) {
			$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_password'] . ': <span class="required">*</span></strong></td><td align="left" class="row_main"> <input type="password" name="edit_user_pass" /></td></tr>';
			$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_password'] . ' (' . $lang['again'] . ') <span class="required">*</span></strong> </td><td align="left" class="row_main"> <input type="password" name="edit_user_pass2" /></td></tr>';
		} else {
			$display .= '<input type="hidden" name="edit_user_pass" value="">';
			$display .= '<input type="hidden" name="edit_user_pass2" value="">';
		}
		$display .= '<tr><td align="right" class="row_main"><strong>' . $lang['user_email'] . ': <span class="required">*</span></strong><br />'.$lang['email_not_displayed'].'</td><td align="left" class="row_main"> <input type="text" name="user_email" value="' . $edit_emailAddress . '" /> ';

		if ($_SESSION['admin_privs'] == 'yes') {
			// if the user is an admin, they can set additional properties about a given user
			// is the user active?
			$display .= "<tr><td align=right><b>$lang[user_manager_is_user_active]: </b></td>";
			$display .= "<td align=left><select name=\"edit_active\" size=\"1\" ";
			if ($edit_isAgent == 'yes') {
				$display .= "onchange=\"listing_change_confirm(this.form.edit_active)\"";
			}
			$display .= "><option value=\"$edit_active\">$edit_active<option value=\"\">-----<option value=\"yes\">yes<option value=\"no\">no</select><input type=\"hidden\" name=\"edit_listing_active\" value=\"\" /></td></tr>";
			// is the user an administrator?
			$display .= "<tr><td align=right><b>$lang[user_manager_is_an_admin]: </b></td>";
			$display .= "<td align=left>$edit_isAdmin</td></tr>";
			$display .= "<input type=\"hidden\" name=\"edit_isAdmin\" value=\"" . $edit_isAdmin . "\" />";
			// is the user an agent?
			$display .= "<tr><td align=right><b>$lang[user_manager_is_an_agent]: </b></td>";
			$display .= "<td align=left>$edit_isAgent</td></tr>";
			$display .= "<input type=\"hidden\" name=\"edit_isAgent\" value=\"" . $edit_isAgent . "\" />";

			if ($edit_isAgent == 'yes' || $edit_isAdmin == 'yes') {
				// limit # of listings?
				$display .= '<tr><td align=right><b>' . $lang['user_manager_limitListings'] . ': </b></td>';
				$display .= '<td align=left><input id="edit_limitListings" name="edit_limitListings" size="6" value="' . $edit_limitListings . '" /><i>(-1 = Unlimited)</i></td></tr>';
				// limit # of featured listings?
				$display .= '<tr><td align=right><b>' . $lang['user_manager_limitFeaturedListings'] . ': </b></td>';
				$display .= '<td align=left><input id="edit_limitFeaturedListings" name="edit_limitFeaturedListings" size="6" value="' . $edit_limitFeaturedListings . '" /><i>(-1 = Unlimited)</i></td></tr>';
				// user display order?
				$display .= '<tr><td align=right><b>' . $lang['user_manager_displayorder'] . ': </b></td>';
				$display .= '<td align=left><input id="edit_userRank" name="edit_userRank" size="6" value="' . $edit_userRank . '" /></td></tr>';
			}
			if ($edit_isAgent == 'yes') {
				// can they edit all listings?
				$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_all_listings'] . ': </b></td>';
				$display .= '<td align=left><select id="edit_canEditAllListings" name="edit_canEditAllListings" size="1"><option value="' . $edit_canEditAllListings . '">' . $edit_canEditAllListings . '</option><option value="">-----</option><option value="no">no</option><option value="yes">yes</option></select></td></tr>';
				// can they edit all users?
				$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_all_users'] . ': </b></td>';
				$display .= '<td align=left><select id="edit_canEditAllUsers" name="edit_canEditAllUsers" size="1"><option value="' . $edit_canEditAllUsers . '">' . $edit_canEditAllUsers . '</option><option value="">-----</option><option value="no">no</option><option value="yes">yes</option></select></td></tr>';
				// can they edit site config?
				$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_site_config'] . ': </b></td>';
				$display .= '<td align=left><select id="edit_canEditSiteConfig" name="edit_canEditSiteConfig" size="1"><option value="' . $edit_canEditSiteConfig . '">' . $edit_canEditSiteConfig . '</option><option value="">-----</option><option value="no">no</option><option value="yes">yes</option></select></td></tr>';
				// can they edit member templates?
				$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_member_template'] . ': </b></td>';
				$display .= '<td align=left><select id="edit_canEditMemberTemplate" name="edit_canEditMemberTemplate" size="1"><option value="' . $edit_canEditMemberTemplate . '">' . $edit_canEditMemberTemplate . '</option><option value="">-----</option><option value="no">no</option><option value="yes">yes</option></select></td></tr>';
				// can they edit agent templates?
				$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_agent_template'] . ': </b></td>';
				$display .= '<td align=left><select id="edit_canEditAgentTemplate" name="edit_canEditAgentTemplate" size="1"><option value="' . $edit_canEditAgentTemplate . '">' . $edit_canEditAgentTemplate . '</option><option value="">-----</option><option value="no">no</option><option value="yes">yes</option></select></td></tr>';
				// can they edit listing templages?
				$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_listing_template'] . ': </b></td>';
				$display .= '<td align=left><select id="edit_canEditListingTemplate" name="edit_canEditListingTemplate" size="1"><option value="' . $edit_canEditListingTemplate . '">' . $edit_canEditListingTemplate . '</option><option value="">-----</option><option value="no">no</option><option value="yes">yes</option></select></td></tr>';
				// can they edit property classes?
				$display .= '<tr><td align=right><b>' . $lang['user_editor_can_edit_property_classes'] . ': </b></td>';
				$display .= '<td align=left><select id="edit_canEditPropertyClasses" name="edit_canEditPropertyClasses" size="1"><option value="' . $edit_canEditPropertyClasses . '">' . $edit_canEditPropertyClasses . '</option><option value="">-----</option><option value="no">no</option><option value="yes">yes</option></select></td></tr>';
				// can they view logs?
				$display .= "<tr><td align=right><b>$lang[user_manager_can_view_logs]: </b></td>";
				$display .= "<td align=left><select name=\"edit_canViewLogs\" size=\"1\"><option value=\"$edit_canViewLogs\">$edit_canViewLogs</option><option value=\"\">-----</option><option value=\"yes\">yes</option><option value=\"no\">no</option></select></td></tr>";
				// can they moderate incoming listings?
				$display .= "<tr><td align=right><b>$lang[user_manager_is_a_moderator]: </b></td>";
				$display .= "<td align=left><select name=\"edit_canModerate\" size=\"1\"><option value=\"$edit_canModerate\">$edit_canModerate</option><option value=\"\">-----</option><option value=\"yes\">yes</option><option value=\"no\">no</option></select></td></tr>";
				// can they feature listings?
				$display .= "<tr><td align=right><b>$lang[user_manager_feature_listings]: </b></td>";
				$display .= "<td align=left><select name=\"edit_canFeatureListings\" size=\"1\"><option value=\"$edit_canFeatureListings\">$edit_canFeatureListings</option><option value=\"\">-----</option><option value=\"yes\">yes</option><option value=\"no\">no</option></select></td></tr>";
				// can they edit pages?
				$display .= "<tr><td align=right><b>$lang[user_manager_can_edit_pages]: </b></td>";
				$display .= "<td align=left><select name=\"edit_canPages\" size=\"1\"><option value=\"$edit_canPages\">$edit_canPages</option><option value=\"\">-----</option><option value=\"yes\">yes</option><option value=\"no\">no</option></select></td></tr>";
				// can they have vtours?
				$display .= "<tr><td align=right><b>$lang[user_manager_can_have_vtours]: </b></td>";
				$display .= "<td align=left><select name=\"edit_canVtour\" size=\"1\"><option value=\"$edit_canVtour\">$edit_canVtour</option><option value=\"\">-----</option><option value=\"yes\">yes</option><option value=\"no\">no</option></select></td></tr>";
				// can they have listings files
				$display .= "<tr><td align=right><b>$lang[user_manager_can_have_files]: </b></td>";
				$display .= "<td align=left><select name=\"edit_canFiles\" size=\"1\"><option value=\"$edit_canFiles\">$edit_canFiles</option><option value=\"\">-----</option><option value=\"yes\">yes</option><option value=\"no\">no</option></select></td></tr>";
				// can they have user files
				$display .= "<tr><td align=right><b>$lang[user_manager_can_have_user_files]: </b></td>";
				$display .= "<td align=left><select name=\"edit_canUserFiles\" size=\"1\"><option value=\"$edit_canUserFiles\">$edit_canUserFiles</option><option value=\"\">-----</option><option value=\"yes\">yes</option><option value=\"no\">no</option></select></td></tr>";
				// can modify expiration?
				$display .= "<tr><td align=right><b>$lang[user_editor_can_edit_listing_expiration]: </b></td>";
				$display .= "<td align=left><select name=\"edit_canEditListingExpiration\" size=\"1\"><option value=\"$edit_canEditListingExpiration\">$edit_canEditListingExpiration</option><option value=\"\">-----</option><option value=\"yes\">yes</option><option value=\"no\">no</option></select></td></tr>";
				// Blog Permisisons
				$display .= "<tr><td align=right><b>$lang[user_editor_blog_privileges]: </b></td>";
				$display .= "<td align=left><select name=\"edit_BlogPrivileges\" size=\"1\"><option value=\"$edit_BlogPrivileges\">$blog_perm[$edit_BlogPrivileges]</option><option value=\"\">-----</option>";
				foreach($blog_perm as $perm_key => $perm_value){
					$display .='<option value="'.$perm_key.'">'.$perm_value.'</option>';
				}
				$display .= "</select></td></tr>";
				// can access addon manager
				$display .= "<tr><td align=right><b>$lang[user_editor_can_manage_addons]: </b></td>";
				$display .= "<td align=left><select name=\"edit_canManageAddons\" size=\"1\"><option value=\"$edit_canManageAddons\">$edit_canManageAddons</option><option value=\"\">-----</option><option value=\"yes\">yes</option><option value=\"no\">no</option></select></td></tr>";
				if ($config["export_listings"] == 1) {
					// can export listings?
					$display .= "<tr><td align=right><b>$lang[user_editor_can_export_listings]: </b></td>";
					$display .= "<td align=left><select name=\"edit_canExportListings\" size=\"1\"><option value=\"$edit_canExportListings\">$edit_canExportListings</option><option value=\"\">-----</option><option value=\"yes\">yes</option><option value=\"no\">no</option></select></td></tr>";
				}else {
					$display .= '<input type="hidden" name="edit_canExportListings" value="no" />';
				}
			}
		}
		// now grab miscellenous debris
		if ($edit_isAgent == "yes" || $edit_isAdmin == 'yes') {
			$db_to_use = 'agentformelements';
		}else {
			$db_to_use = 'memberformelements';
		}

		$sql = 'SELECT ' . $db_to_use . '_field_name, userdbelements_field_value, ' . $db_to_use . '_field_type, ' . $db_to_use . '_rank, ' . $db_to_use . '_field_caption, ' . $db_to_use . '_default_text, ' . $db_to_use . '_required, ' . $db_to_use . '_field_elements, ' . $db_to_use . '_tool_tip FROM ' . $config['table_prefix'] . $db_to_use . ' left join ' . $config['table_prefix'] . 'userdbelements on userdbelements_field_name = ' . $db_to_use . '_field_name and userdb_id = ' . $sql_edit . ' ORDER BY ' . $db_to_use . '_rank';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$field_name = $misc->make_db_unsafe ($recordSet->fields[$db_to_use . '_field_name']);
			$field_value = $misc->make_db_unsafe ($recordSet->fields['userdbelements_field_value']);
			$field_type = $misc->make_db_unsafe ($recordSet->fields[$db_to_use . '_field_type']);
			$field_caption = $misc->make_db_unsafe($recordSet->fields[$db_to_use . '_field_caption']);
			$default_text = $misc->make_db_unsafe($recordSet->fields[$db_to_use . '_default_text']);
			$field_elements = $misc->make_db_unsafe($recordSet->fields[$db_to_use . '_field_elements']);
			$required = $misc->make_db_unsafe($recordSet->fields[$db_to_use . '_required']);
			$tool_tip = $misc->make_db_unsafe($recordSet->fields[$db_to_use . '_tool_tip']);
			// pass the data to the function
			$display .= $forms->renderExistingFormElement($field_type, $field_name, $field_value, $field_caption, $default_text, $required, $field_elements,'',$tool_tip);
			$recordSet->MoveNext();
		} // end while
		$display .= '<tr><td colspan="2" align="center" class="row_main">' . $lang['required_form_text'] . '</td></tr>';
		$display .= '<tr><td colspan="2" align="center" class="row_main"><input type="submit" value="' . $lang['update_button'] . '" />';
		$security = login::loginCheck('edit_all_users', true);
		if ($security === true) {
			$display .= '&nbsp;&nbsp;&nbsp;<a href="index.php?action=user_manager&amp;delete=' . $user_id . '" onclick="return confirmDelete(\'' . $lang['delete_user'] . '\')">' . $lang['delete'] . '</a>';
		}
		$display .= '</td></tr></table></form>';
		$display .= '</td></tr></table>';
		$display .= '</td></tr></table>';
		return $display;
	}
	function create_user()
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$security = false;
		if ((($config["demo_mode"] != 1) &&($_SESSION['edit_all_users'] == 'yes')) || ($_SESSION['admin_privs'] == 'yes')) {
			$security = true;
		}
		$display = '';
		if ($security === true) {
			// create the user
			if ($_POST['edit_user_pass'] != $_POST['edit_user_pass2']) {
				$display .= '<p>' . $lang['user_creation_password_identical'] . '</p>';
				$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
			} elseif ($_POST['edit_user_pass'] == "") {
				$display .= '<p>' . $lang['user_creation_password_blank'] . '</p>';
				$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
			} elseif ($_POST['edit_user_name'] == "") {
				$display .= '<p>' . $lang['user_editor_need_username'] . '</p>';
				$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
			} elseif ($_POST['user_email'] == "") {
				$display .= '<p>' . $lang['user_editor_need_email_address'] . '</p>';
				$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
			} elseif ($_POST['user_first_name'] == "") {
				$display .= '<p>' . $lang['user_editor_need_first_name'] . '</p>';
				$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
			} elseif ($_POST['user_last_name'] == "") {
				$display .= '<p>' . $lang['user_editor_need_last_name'] . '</p>';
				$display .= '<form><input type="button" value="' . $lang['back_button_text'] . '" onclick="history.back()" /></form>';
			} else {
				$sql_user_name = $misc->make_db_safe($_POST['edit_user_name']);
				$sql_user_email = $misc->make_db_safe($_POST['user_email']);
				$pass_the_form = "Yes";
				// first, make sure the user name isn't in use
				$sql = 'SELECT userdb_user_name from ' . $config['table_prefix'] . 'userdb WHERE userdb_user_name = ' . $sql_user_name;
				$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$num = $recordSet->RecordCount();
				// second, make sure the user eamail isn't in use
				$sql2 = 'SELECT userdb_emailaddress from ' . $config['table_prefix'] . 'userdb WHERE userdb_emailaddress = ' . $sql_user_email;
				$recordSet2 = $conn->Execute($sql2);
				if ($recordSet2 === false) {
					$misc->log_error($sql2);
				}
				$num2 = $recordSet2->RecordCount();
				if ($num >= 1) {
					$pass_the_form = 'No';
					$display .= $lang['user_creation_username_taken'];
				} // end if
				elseif ($num2 >= 1) {
					$pass_the_form = 'No';
					$display .= $lang['email_address_already_registered'];
				} // end if
				if ($pass_the_form == "Yes") {
					// what the program should do if the form is valid
					// generate a random number to enter in as the password (initially)
					// we'll need to know the actual account id to help with retrieving the user
					// We will be putting in a random number that we know the value of, we can easily
					// retrieve the account id in a few moments
					$random_number = $misc->make_db_safe(rand(1, 10000));
					$sql_user_name = $misc->make_db_safe($_POST['edit_user_name']);
					$md5_user_pass = md5($_POST['edit_user_pass']);
					$md5_user_pass = $misc->make_db_safe($md5_user_pass);
					$sql_user_email = $misc->make_db_safe($_POST['user_email']);
					$sql_user_first_name = $misc->make_db_safe($_POST['user_first_name']);
					$sql_user_last_name = $misc->make_db_safe($_POST['user_last_name']);
					$sql_edit_active = $misc->make_db_safe($_POST['edit_active']);
					$sql_edit_isAgent = $misc->make_db_safe($_POST['edit_isAgent']);
					$sql_edit_isAdmin = $misc->make_db_safe($_POST['edit_isAdmin']);
					if ($_POST['edit_isAgent'] == 'yes') {
						$sql_edit_canEditSiteConfig = $misc->make_db_safe($_POST['edit_canEditSiteConfig']);
						$sql_edit_canEditMemberTemplate = $misc->make_db_safe($_POST['edit_canEditMemberTemplate']);
						$sql_edit_canEditAgentTemplate = $misc->make_db_safe($_POST['edit_canEditAgentTemplate']);
						$sql_edit_canEditListingTemplate = $misc->make_db_safe($_POST['edit_canEditListingTemplate']);
						$sql_edit_canFeatureListings = $misc->make_db_safe($_POST['edit_canFeatureListings']);
						$sql_edit_canViewLogs = $misc->make_db_safe($_POST['edit_canViewLogs']);
						$sql_edit_canModerate = $misc->make_db_safe($_POST['edit_canModerate']);
						$sql_edit_canPages = $misc->make_db_safe($_POST['edit_canPages']);
						$sql_edit_canVtour = $misc->make_db_safe($_POST['edit_canVtour']);
						$sql_edit_canFiles = $misc->make_db_safe($_POST['edit_canFiles']);
						$sql_edit_canUserFiles = $misc->make_db_safe($_POST['edit_canUserFiles']);
						$sql_limitListings = $misc->make_db_safe($_POST['limitListings']);
						$sql_edit_canExportListings = $misc->make_db_safe($_POST['edit_canExportListings']);
						$sql_edit_canEditListingExpiration = $misc->make_db_safe($_POST['edit_canEditListingExpiration']);
						$sql_edit_canEditAllListings = $misc->make_db_safe($_POST['edit_canEditAllListings']);
						$sql_edit_canEditAllUsers = $misc->make_db_safe($_POST['edit_canEditAllUsers']);
						$sql_edit_canEditPropertyClasses = $misc->make_db_safe($_POST['edit_canEditPropertyClasses']);
						$sql_edit_limitFeaturedListings = $misc->make_db_safe($_POST['edit_limitFeaturedListings']);
						$sql_edit_userRank = $misc->make_db_safe($_POST['edit_userRank']);
						$sql_edit_canManageAddons = $misc->make_db_safe($_POST['edit_canManageAddons']);
					}else if($_POST['edit_isAdmin'] == 'yes'){
						$sql_edit_limitFeaturedListings = $misc->make_db_safe('-1');
						$sql_edit_userRank = $misc->make_db_safe($_POST['edit_userRank']);
						$sql_limitListings = $misc->make_db_safe('-1');
						$sql_edit_canEditSiteConfig = $misc->make_db_safe("no");
						$sql_edit_canEditMemberTemplate = $misc->make_db_safe("no");
						$sql_edit_canEditAgentTemplate = $misc->make_db_safe("no");
						$sql_edit_canEditListingTemplate = $misc->make_db_safe("no");
						$sql_edit_canFeatureListings = $misc->make_db_safe("no");
						$sql_edit_canViewLogs = $misc->make_db_safe("no");
						$sql_edit_canModerate = $misc->make_db_safe("no");
						$sql_edit_canPages = $misc->make_db_safe("no");
						$sql_edit_canVtour = $misc->make_db_safe("no");
						$sql_edit_canFiles = $misc->make_db_safe("no");
						$sql_edit_canUserFiles = $misc->make_db_safe("no");
						$sql_edit_canExportListings = $misc->make_db_safe("no");
						$sql_edit_canEditListingExpiration = $misc->make_db_safe("no");
						$sql_edit_canEditAllListings = $misc->make_db_safe("no");
						$sql_edit_canEditAllUsers = $misc->make_db_safe("no");
						$sql_edit_canEditPropertyClasses = $misc->make_db_safe("no");
						$sql_edit_canManageAddons = $misc->make_db_safe("no");
					}else {
						$sql_edit_canEditSiteConfig = $misc->make_db_safe("no");
						$sql_edit_canEditMemberTemplate = $misc->make_db_safe("no");
						$sql_edit_canEditAgentTemplate = $misc->make_db_safe("no");
						$sql_edit_canEditListingTemplate = $misc->make_db_safe("no");
						$sql_edit_canFeatureListings = $misc->make_db_safe("no");
						$sql_edit_canViewLogs = $misc->make_db_safe("no");
						$sql_edit_canModerate = $misc->make_db_safe("no");
						$sql_edit_canPages = $misc->make_db_safe("no");
						$sql_edit_canVtour = $misc->make_db_safe("no");
						$sql_edit_canFiles = $misc->make_db_safe("no");
						$sql_edit_canUserFiles = $misc->make_db_safe("no");
						$sql_edit_canExportListings = $misc->make_db_safe("no");
						$sql_edit_canEditListingExpiration = $misc->make_db_safe("no");
						$sql_edit_canEditAllListings = $misc->make_db_safe("no");
						$sql_edit_canEditAllUsers = $misc->make_db_safe("no");
						$sql_limitListings = 0;
						$sql_edit_limitFeaturedListings = 0;
						$sql_edit_userRank =0;
						$sql_edit_canEditPropertyClasses = $misc->make_db_safe("no");
						$sql_edit_canManageAddons = $misc->make_db_safe("no");
					}
					// create the account with the random number as the password
					$sql = 'INSERT INTO ' . $config['table_prefix'] . 'userdb (userdb_user_name, userdb_user_password,userdb_user_first_name ,userdb_user_last_name, userdb_emailAddress,
						userdb_creation_date,userdb_last_modified,userdb_active,userdb_is_agent,userdb_is_admin,userdb_can_edit_member_template,
						userdb_can_edit_agent_template,userdb_can_edit_listing_template,userdb_can_feature_listings,userdb_can_view_logs,
						userdb_can_moderate,userdb_can_edit_pages,userdb_can_have_vtours,userdb_can_have_files,userdb_can_have_user_files,userdb_limit_listings,userdb_comments,userdb_hit_count,
						userdb_can_edit_expiration,userdb_can_export_listings,userdb_can_edit_all_users,userdb_can_edit_all_listings,userdb_can_edit_site_config,userdb_can_edit_property_classes,userdb_can_manage_addons,userdb_rank,userdb_featuredlistinglimit) VALUES
						(' . $sql_user_name . ',' . $random_number . ',' . $sql_user_first_name . ',' . $sql_user_last_name . ',' . $sql_user_email . ',' . $conn->DBDate(time()) . ',' . $conn->DBTimeStamp(time()) . ','
					 . $sql_edit_active . ',' . $sql_edit_isAgent . ',' . $sql_edit_isAdmin . ',' . $sql_edit_canEditMemberTemplate . ',' . $sql_edit_canEditAgentTemplate . ',' . $sql_edit_canEditListingTemplate . ',' . $sql_edit_canFeatureListings . ',' . $sql_edit_canViewLogs . ',' . $sql_edit_canModerate . ','
					 . $sql_edit_canPages . ',' . $sql_edit_canVtour . ',' . $sql_edit_canFiles . ',' . $sql_edit_canUserFiles . ',' . $sql_limitListings . ',\'\',0,' . $sql_edit_canEditListingExpiration . ',' . $sql_edit_canExportListings
					 . ',' . $sql_edit_canEditAllUsers . ',' . $sql_edit_canEditAllListings . ',' . $sql_edit_canEditSiteConfig . ',' . $sql_edit_canEditPropertyClasses . ',' . $sql_edit_canManageAddons . ',' . $sql_edit_userRank . ',' . $sql_edit_limitFeaturedListings . ')';
					 $recordSet = $conn->Execute($sql);
					 if ($recordSet === false) {
					 	$misc->log_error($sql);
					 }
					 // then we need to retrieve the new user id
					 $sql = 'SELECT userdb_id FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_user_password = ' . $random_number;
					 $recordSet = $conn->Execute($sql);
					 if ($recordSet === false) {
					 	$misc->log_error($sql);
					 } while (!$recordSet->EOF) {
					 	$new_user_id = $recordSet->fields['userdb_id']; // this is the new user's ID number
					 	$recordSet->MoveNext();
					 } // end while
					 // now it's time to replace the password
					 $sql = 'UPDATE ' . $config['table_prefix'] . 'userdb SET userdb_user_password = ' . $md5_user_pass . ' WHERE userdb_id = ' . $new_user_id;
					 $recordSet = $conn->Execute($sql);
					 if ($recordSet === false) {
					 	$misc->log_error($sql);
					 }
					 // now that that's taken care of, it's time to insert all the rest
					 // of the variables into the database;
					 $display .= '<p>' . $lang['user_editor_creation_success'] . ': ' . $_POST['edit_user_name'] . '</p>';
					 $display .= user_managment::edit_user($new_user_id);
					 return $display;
				}
			}
		}
		return $display;
	}
	function update_member_profile($user_id)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/forms.inc.php');
		$forms = new forms();
		$display = '';
		if ($_SESSION['edit_all_users'] == 'yes' || $_SESSION['admin_privs'] == 'yes' || $user_id = $_SESSION['userID']) {
			$do_update = true;
			if ($_POST['edit_user_pass'] != $_POST['edit_user_pass2']) {
				$display .= '<p>' . $lang['user_manager_password_identical'] . '</p>';
				$do_update = false;
			} elseif ($_POST['edit_user_pass'] == '') {
				$do_update = true;
			} // end elseif
			if ($_POST['user_email'] == '' || $_POST['user_first_name'] == '' || $_POST['user_last_name'] == '') {
				$display .= "<p class=\"redtext\">$lang[required_fields_not_filled]</p>";
				$do_update = false;
			}
			// Get Current User type
			$sql = 'SELECT userdb_is_agent, userdb_is_admin, userdb_active FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $user_id;
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}

			$is_agent = $misc->make_db_unsafe ($recordSet->fields['userdb_is_agent']);
			$is_admin = $misc->make_db_unsafe ($recordSet->fields['userdb_is_admin']);
			$is_active = $misc->make_db_unsafe ($recordSet->fields['userdb_active']);
			if ($do_update) {
				global $pass_the_form;
				if ($is_agent == 'yes' || $is_admin == 'yes') {
					$db_to_validate = 'agentformelements';
				}else {
					$db_to_validate = 'memberformelements';
				}
				$pass_the_form = $forms->validateForm($db_to_validate);
				$sql_user_email = $misc->make_db_safe($_POST['user_email']);
				$sql_user_first_name = $misc->make_db_safe($_POST['user_first_name']);
				$sql_user_last_name = $misc->make_db_safe($_POST['user_last_name']);
				//Make sure no other user has this email address.
				$sql = 'SELECT userdb_id FROM ' . $config['table_prefix'] . 'userdb WHERE  userdb_emailaddress = '.$sql_user_email;

				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				while(!$recordSet->EOF){
					if($recordSet->fields['userdb_id'] != $user_id){
						$display .= "<p class=\"redtext\">$lang[email_address_already_used]</p>";
						$do_update = false;
					}
					$recordSet->MoveNext();
				}

				if (is_array($pass_the_form)) {
					// if we're not going to pass it, tell that they forgot to fill in one of the fields
					foreach ($pass_the_form as $k => $v) {
						if ($v == 'REQUIRED') {
							$display .= "<p class=\"redtext\">$k: $lang[required_fields_not_filled]</p>";
						}
						if ($v == 'TYPE') {
							$display .= "<p class=\"redtext\">$k: $lang[field_type_does_not_match]</p>";
						}
					}
				} else {
					if ($_POST['edit_user_pass'] == '') {
						$sql = 'UPDATE ' . $config['table_prefix'] . 'userdb SET userdb_emailaddress = ' . $sql_user_email . ', userdb_user_first_name = ' . $sql_user_first_name . ', userdb_user_last_name = ' . $sql_user_last_name . ', userdb_last_modified = ' . $conn->DBTimeStamp(time()) . ' WHERE userdb_id = ' . $user_id;
					}else {
						$md5_user_pass = md5($_POST['edit_user_pass']);
						$md5_user_pass = $misc->make_db_safe($md5_user_pass);
						$sql = 'UPDATE ' . $config['table_prefix'] . 'userdb SET userdb_emailaddress = ' . $sql_user_email . ', userdb_user_first_name = ' . $sql_user_first_name . ', userdb_user_last_name = ' . $sql_user_last_name . ', userdb_user_password = ' . $md5_user_pass . ', userdb_last_modified = ' . $conn->DBTimeStamp(time()) . ' WHERE userdb_id = ' . $user_id;
					}
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}

					$message = user_managment::updateUserData($user_id);
					if ($message == 'success') {
						// one has to ensure that the cookie containing the pass is reset
						// otherwise, one would have to log out and in again everytime
						// an account was updated
						if ($_POST['edit_user_pass'] != "" && $_SESSION['userID'] == $user_id) {
							$_SESSION['userpassword'] = md5($_POST['edit_user_pass']);
						}
						$display .= '<p>' . $lang['user_editor_account_updated'] . ', ' . htmlentities($_SESSION['username']) . '</p>';
					} // end if
					else {
						$display .= '<p>' . $lang['alert_site_admin'] . '</p>';
					} // end else
				} // end if $pass_the_form == "Yes"
			} // end else
			return $display;
		} // end if $_POST['action'] == "update_user"
	}
	function update_user($user_id)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/forms.inc.php');
		$forms = new forms();
		$display = '';
		$do_update = true;
		if ($_POST['edit_user_pass'] != $_POST['edit_user_pass2']) {
			$display .= '<p>' . $lang['user_manager_password_identical'] . '</p>';
			$do_update = false;
		} elseif ($_POST['edit_user_pass'] == '') {
			$do_update = true;
		} // end elseif
		if ($_POST['user_email'] == '' || $_POST['user_first_name'] == '' || $_POST['user_last_name'] == '') {
			$display .= "<p class=\"redtext\">$lang[required_fields_not_filled]</p>";
			$do_update = false;
		}
		// Get Current User type
		$sql = 'SELECT userdb_is_agent, userdb_is_admin, userdb_active FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $user_id;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$is_agent = $misc->make_db_unsafe ($recordSet->fields['userdb_is_agent']);
		$is_admin = $misc->make_db_unsafe ($recordSet->fields['userdb_is_admin']);
		$is_active = $misc->make_db_unsafe ($recordSet->fields['userdb_active']);
		$sql_user_email = $misc->make_db_safe($_POST['user_email']);
		$sql_user_first_name = $misc->make_db_safe($_POST['user_first_name']);
		$sql_user_last_name = $misc->make_db_safe($_POST['user_last_name']);
		//Make sure no other user has this email address.
		$sql = 'SELECT userdb_id FROM ' . $config['table_prefix'] . 'userdb WHERE  userdb_emailaddress = '.$sql_user_email;

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		while(!$recordSet->EOF){
			if($recordSet->fields['userdb_id'] != $user_id){
				$display .= "<p class=\"redtext\">$lang[email_address_already_used]</p>";
				$do_update = false;
			}
			$recordSet->MoveNext();
		}
		if ($do_update) {
			global $pass_the_form;
			if ($is_agent == 'yes' || $is_admin == 'yes') {
				$db_to_validate = 'agentformelements';
			}else {
				$db_to_validate = 'memberformelements';
			}
			$pass_the_form = $forms->validateForm($db_to_validate);

			if (is_array($pass_the_form)) {
				// if we're not going to pass it, tell that they forgot to fill in one of the fields
				foreach ($pass_the_form as $k => $v) {
					if ($v == 'REQUIRED') {
						$display .= "<p class=\"redtext\">$k: $lang[required_fields_not_filled]</p>";
					}
					if ($v == 'TYPE') {
						$display .= "<p class=\"redtext\">$k: $lang[field_type_does_not_match]</p>";
					}
				}
			} else {
				$_POST['user_email'] = $misc->make_db_safe($_POST['user_email']);
				if ($_POST['edit_user_pass'] == '') {
					$sql = 'UPDATE ' . $config['table_prefix'] . 'userdb SET userdb_emailaddress = ' . $_POST['user_email'] . ', userdb_last_modified = ' . $conn->DBTimeStamp(time()) . ' WHERE userdb_id = ' . $user_id;
				}else {
					$md5_user_pass = md5($_POST['edit_user_pass']);
					$md5_user_pass = $misc->make_db_safe($md5_user_pass);
					$sql = 'UPDATE ' . $config['table_prefix'] . 'userdb SET userdb_emailaddress = ' . $_POST['user_email'] . ', userdb_user_password = ' . $md5_user_pass . ', userdb_last_modified = ' . $conn->DBTimeStamp(time()) . ' WHERE userdb_id = ' . $user_id;
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				if ($_SESSION['admin_privs'] == 'yes' && $is_admin == 'yes') {
					$sql_edit_limitListings = $misc->make_db_safe($_POST['edit_limitListings']);
					$sql_edit_limitFeaturedListings = $misc->make_db_safe($_POST['edit_limitFeaturedListings']);
					$sql_edit_userRank = $misc->make_db_safe($_POST['edit_userRank']);
					$sql = 'UPDATE ' . $config['table_prefix'] . 'userdb SET userdb_rank = ' . $sql_edit_userRank . ', userdb_featuredlistinglimit = ' . $sql_edit_limitFeaturedListings . ', userdb_limit_listings = ' . $sql_edit_limitListings . ' WHERE userdb_id = ' . $user_id;

					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
				}
				// If Admin is upadting and agent set other fields
				if ($_SESSION['admin_privs'] == 'yes' && $is_agent == 'yes') {
					$edit_is_active = $misc->make_db_safe($_POST['edit_active']);
					$edit_first_name = $misc->make_db_safe($_POST['user_first_name']);
					$edit_last_name = $misc->make_db_safe($_POST['user_last_name']);
					$edit_canEditSiteConfig = $misc->make_db_safe($_POST['edit_canEditSiteConfig']);
					$edit_canEditMemberTemplate = $misc->make_db_safe($_POST['edit_canEditMemberTemplate']);
					$edit_canEditAgentTemplate = $misc->make_db_safe($_POST['edit_canEditAgentTemplate']);
					$edit_canEditListingTemplate = $misc->make_db_safe($_POST['edit_canEditListingTemplate']);
					$edit_canEditAllListings = $misc->make_db_safe($_POST['edit_canEditAllListings']);
					$edit_canEditAllUsers = $misc->make_db_safe($_POST['edit_canEditAllUsers']);
					$edit_can_view_logs = $misc->make_db_safe($_POST['edit_canViewLogs']);
					$edit_can_moderate = $misc->make_db_safe($_POST['edit_canModerate']);
					$edit_can_feature_listings = $misc->make_db_safe($_POST['edit_canFeatureListings']);
					$edit_can_edit_pages = $misc->make_db_safe($_POST['edit_canPages']);
					$edit_can_have_vtours = $misc->make_db_safe($_POST['edit_canVtour']);
					$edit_can_have_files = $misc->make_db_safe($_POST['edit_canFiles']);
					$edit_can_have_user_files = $misc->make_db_safe($_POST['edit_canUserFiles']);
					$edit_limitListings = $misc->make_db_safe($_POST['edit_limitListings']);
					$sql_edit_canExportListings = $misc->make_db_safe($_POST['edit_canExportListings']);
					$sql_edit_canEditListingExpiration = $misc->make_db_safe($_POST['edit_canEditListingExpiration']);
					$sql_edit_canEditPropertyClasses = $misc->make_db_safe($_POST['edit_canEditPropertyClasses']);
					$sql_userdb_blog_user_type = $misc->make_db_safe($_POST['edit_BlogPrivileges']);
					$sql_edit_limitFeaturedListings = $misc->make_db_safe($_POST['edit_limitFeaturedListings']);
					$sql_edit_userRank = $misc->make_db_safe($_POST['edit_userRank']);
					$sql_edit_canManageAddons = $misc->make_db_safe($_POST['edit_canManageAddons']);
					$sql = 'UPDATE ' . $config['table_prefix'] . 'userdb SET 
						userdb_active = ' . $edit_is_active . ', 
						userdb_user_first_name = ' . $edit_first_name . ', 
						userdb_user_last_name = ' . $edit_last_name . ',
						userdb_can_edit_site_config = ' . $edit_canEditSiteConfig . ', 
						userdb_can_edit_member_template = ' . $edit_canEditMemberTemplate . ', 
						userdb_can_edit_agent_template = ' . $edit_canEditAgentTemplate . ', 
						userdb_can_edit_listing_template = ' . $edit_canEditListingTemplate . ', 
						userdb_can_view_logs = ' . $edit_can_view_logs . ', 
						userdb_can_moderate = ' . $edit_can_moderate . ', 
						userdb_can_feature_listings = ' . $edit_can_feature_listings . ', 
						userdb_can_edit_pages = ' . $edit_can_edit_pages . ', 
						userdb_can_have_vtours = ' . $edit_can_have_vtours . ', 
						userdb_can_have_files = ' . $edit_can_have_files . ', 
						userdb_can_have_user_files = ' . $edit_can_have_user_files . ', 
						userdb_limit_listings = ' . $edit_limitListings . ', 
						userdb_can_edit_expiration = ' . $sql_edit_canEditListingExpiration . ', 
						userdb_can_export_listings = ' . $sql_edit_canExportListings . ', 
						userdb_can_edit_all_users = ' . $edit_canEditAllUsers . ', 
						userdb_can_edit_all_listings = ' . $edit_canEditAllListings . ', 
						userdb_can_edit_property_classes = ' . $sql_edit_canEditPropertyClasses . ', 
						userdb_can_manage_addons = ' . $sql_edit_canManageAddons . ', 
						userdb_rank = ' . $sql_edit_userRank . ', 
						userdb_featuredlistinglimit = ' . $sql_edit_limitFeaturedListings . ', 
						userdb_blog_user_type = '.$sql_userdb_blog_user_type.' 
						WHERE userdb_id = ' . $user_id;
						 $recordSet = $conn->Execute($sql);
						 if ($recordSet === false) {
						 	$misc->log_error($sql);
						 }
				}else {
					if (isset($_POST['edit_active'])) {
						$edit_is_active = $misc->make_db_safe($_POST['edit_active']);
					}else {
						$edit_is_active = $misc->make_db_safe('yes');
					}
					$edit_first_name = $misc->make_db_safe($_POST['user_first_name']);
					$edit_last_name = $misc->make_db_safe($_POST['user_last_name']);
					$sql = 'UPDATE ' . $config['table_prefix'] . 'userdb SET userdb_active = ' . $edit_is_active . ', userdb_user_first_name = ' . $edit_first_name . ', userdb_user_last_name ='
					. $edit_last_name . ' WHERE userdb_id = ' . $user_id;
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
				}
				if ($is_active=='no' && $_POST['edit_active']=='yes')
				{
					if (($config['moderate_agents'] == 1 && $is_agent == 'yes') || ($config['moderate_members'] == 1 && $is_agent == 'no'))
					{
						$message=$_POST['user_first_name'].' '.$_POST['user_last_name'].",\r\n".$lang['user_activated_message']."\r\n\r\n";
						if ($is_agent == 'yes')
						{
							$link=$config['baseurl'].'/admin/index.php';
						}else{
							$link=$config['baseurl'].'/index.php?action=member_login';
						}
						$message.=$link;
						$email=str_replace('\'','',$_POST['user_email']);
						$send=$misc->send_email($config['company_name'], $config['admin_email'], $email, $message, $lang['user_activated_subject']);
					}
				}
				$message = user_managment::updateUserData($user_id);
				if ($message == 'success') {
					// one has to ensure that the cookie containing the pass is reset
					// otherwise, one would have to log out and in again everytime
					// an account was updated
					if ($_POST['edit_user_pass'] != "" && $_SESSION['userID'] == $user_id) {
						$_SESSION['userpassword'] = md5($_POST['edit_user_pass']);
					}
					$display .= '<p>' . $lang['user_editor_account_updated'] . ', ' . $_SESSION['username'] . '</p>';
				} // end if
				else {
					$display .= '<p>' . $lang['alert_site_admin'] . '</p>';
				} // end else
			} // end if $pass_the_form == "Yes"
		} // end else
		$misc->log_action ($lang['log_updated_user'] . ': ' . $user_id);
		return $display;
	}
	function updateUserData ($user_id)
	{
		// UPDATES THE USER INFORMATION
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$sql_user_id = $misc->make_db_extra_safe($user_id);
		$sql = 'DELETE FROM ' . $config['table_prefix'] . 'userdbelements WHERE userdb_id = ' . $sql_user_id;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$sql3='SELECT userdb_is_agent FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $sql_user_id;
		$recordSet3=$conn->Execute($sql3);
		if ($recordSet3 === false) {
			$misc->log_error($sql3);
		}
		if ($recordSet3->fields['userdb_is_agent'] == 'yes')
		{
			$db_to_use='agent';
		} else {
			$db_to_use='member';
		}
		foreach ($_POST as $ElementIndexValue => $ElementContents) {

			$sql2="SELECT ".$db_to_use."formelements_field_type FROM " . $config['table_prefix'] . $db_to_use."formelements WHERE ".$db_to_use."formelements_field_name='".$ElementIndexValue."'";
			$recordSet2=$conn->Execute($sql2);
			if ($recordSet2 === false) {
				$misc->log_error($sql2);
			}
			$field_type=$recordSet2->fields[$db_to_use.'formelements_field_type'];
			// first, ignore all the stuff that's been taken care of above
			if ($ElementIndexValue == 'user_user_name' || $ElementIndexValue == 'edit_user_pass' || $ElementIndexValue == 'edit_user_pass2' || $ElementIndexValue == 'user_email' || $ElementIndexValue == 'PHPSESSID' || $ElementIndexValue == 'edit' || $ElementIndexValue == 'edit_isAdmin' || $ElementIndexValue == 'edit_active' || $ElementIndexValue == 'edit_isAgent' || $ElementIndexValue == 'edit_limitListings' || $ElementIndexValue == 'edit_canEditSiteConfig' || $ElementIndexValue == 'edit_canMemberTemplate' || $ElementIndexValue == 'edit_canAgentTemplate' || $ElementIndexValue == 'edit_canListingTemplate' || $ElementIndexValue == 'edit_canViewLogs' || $ElementIndexValue == 'edit_canModerate' || $ElementIndexValue == 'edit_canFeatureListings' || $ElementIndexValue == 'edit_canPages' || $ElementIndexValue == 'edit_canVtour' || $ElementIndexValue == 'edit_canFiles' || $ElementIndexValue == 'edit_canUserFiles') {
				// do nothing
			}
			// this is currently set up to handle two feature lists
			// it could easily handle more...
			// just write handlers for 'em
			elseif (is_array($ElementContents)) {
				// deal with checkboxes & multiple selects elements
				$feature_insert = '';
				foreach ($ElementContents as $feature_item) {
					$feature_insert = $feature_insert . '||' . $feature_item;
				} // end foreach
				// now remove the first two characters
				$feature_insert_length = strlen($feature_insert);
				$feature_insert_length = $feature_insert_length - 2;
				$feature_insert = substr($feature_insert, 2, $feature_insert_length);
				$sql_ElementIndexValue = $misc->make_db_safe($ElementIndexValue);
				$sql_feature_insert = $misc->make_db_safe($feature_insert);
				$sql = 'INSERT INTO ' . $config['table_prefix'] . 'userdbelements (userdbelements_field_name, userdbelements_field_value, userdb_id) VALUES (' . $sql_ElementIndexValue . ', ' . $sql_feature_insert . ', ' . $sql_user_id . ')';
				// }
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
			} // end elseif
			else {
				// it's time to actually insert the form data into the db
				$sql_ElementIndexValue = $misc->make_db_safe($ElementIndexValue);
				$sql_ElementContents = $misc->make_db_safe($ElementContents);
				// if ($_SESSION['admin_privs'] == 'yes' && $_GET['edit'] != "")
				// {
				// $sql_edit = $misc->make_db_safe($_GET['edit']);
				// $sql = 'INSERT INTO ' . $config['table_prefix'] . 'userdbelements (userdbelements_field_name, userdbelements_field_value, userdb_id) VALUES ('.$sql_ElementIndexValue.', '.$sql_ElementContents.', '.$sql_edit.')';
				// }
				// else
				// {
				// $sql_user_id = $misc->make_db_safe($_SESSION['userID']);
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
					$sql_ElementContents = $misc->make_db_safe($returnValue);
				}
				$sql = 'INSERT INTO ' . $config['table_prefix'] . 'userdbelements (userdbelements_field_name, userdbelements_field_value, userdb_id) VALUES (' . $sql_ElementIndexValue . ', ' . $sql_ElementContents . ', ' . $sql_user_id . ')';
				// }
				$recordSet = $conn->Execute($sql);
			} // end else
		} // end while
		return 'success';
	} // end function updateUserData
	function delete_user($user_id)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// Set Variable to hold errors
		$errors = '';
		// Verify ID is Numeric
		if (!is_numeric($user_id)) {
			return $lang['user_manager_invalid_user_id'];
		}
		if ($config['demo_mode'] == 1 && $_SESSION['admin_privs'] != 'yes') {
			return $lang['demo_mode'] . ' - ' . $lang['user_manager_permission_denied'] . '<br />';
		}
		// Admins can delte any user. Anyone can delte there own information as this is needed for updates.
		if ($_SESSION['admin_privs'] == 'yes' && $user_id != '') {
			$sql_delete = $misc->make_db_extra_safe($user_id);
		} elseif (($_SESSION['admin_privs'] == 'yes' && $user_id == '') || ($_SESSION['userID'] == $user_id)) {
			$sql_delete = $misc->make_db_extra_safe($_SESSION['userID']);
		} else {
			return $lang['user_manager_permission_denied'];
		}
		// delete the user
		$sql = 'DELETE FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $sql_delete;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// delete all the elements associated with the user
		$sql = 'DELETE FROM ' . $config['table_prefix'] . 'userdbelements WHERE userdb_id = ' . $sql_delete;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// delete all the listings associated with a user
		$sql = 'DELETE FROM ' . $config['table_prefix'] . 'listingsdb WHERE (userdb_ID = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// delete all the elements associated with a user
		$sql = 'DELETE FROM ' . $config['table_prefix'] . 'listingsdbelements WHERE (userdb_id = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// delete all the favorites associated with a user
		$sql = 'DELETE FROM ' . $config['table_prefix'] . 'userfavoritelistings WHERE (userdb_id = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// delete all the saved searches associated with a user
		$sql = 'DELETE FROM ' . $config['table_prefix'] . 'usersavedsearches WHERE (userdb_id = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// now get all the images associated with a user's listings
		$sql = 'SELECT listingsimages_file_name, listingsimages_thumb_file_name FROM ' . $config['table_prefix'] . 'listingsimages WHERE (userdb_id = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// so, you've got 'em... it's time to unlink those bad boys...
		while (!$recordSet->EOF) {
			$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_thumb_file_name']);
			$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
			// get rid of those darned things...
			if (!unlink($config['listings_upload_path'] . '/' . $file_name)) {
				$errors .= $lang['user_manager_failed_to_delete'] . ' ' . $config['listings_upload_path'] . '/' . $file_name . '<br />';
			}
			if ($file_name != $thumb_file_name) {
				if (!unlink($config['listings_upload_path'] . '/' . $thumb_file_name)) {
					$errors .= $lang['user_manager_failed_to_delete'] . ' ' . $config['listings_upload_path'] . '/' . $thumb_file_name . '<br />';
				}
			}
			$recordSet->MoveNext();
		}
		// delete all the saved images associated with a user from listingimages
		$sql = 'DELETE FROM ' . $config['table_prefix'] . 'listingsimages WHERE (userdb_id = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// it's time to do the same for all the images associated with the user himself
		$sql = 'SELECT userimages_file_name, userimages_thumb_file_name FROM ' . $config['table_prefix'] . 'userimages WHERE (userdb_id = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['userimages_thumb_file_name']);
			$file_name = $misc->make_db_unsafe ($recordSet->fields['userimages_file_name']);
			// get rid of those darned things...
			if (!unlink($config['user_upload_path'] . '/' . $file_name)) {
				$errors .= $lang['user_manager_failed_to_delete'] . ' ' . $config['user_upload_path'] . '/' . $file_name . '<br />';
			}
			if ($file_name != $thumb_file_name) {
				if (!unlink($config['user_upload_path'] . '/' . $thumb_file_name)) {
					$errors .= $lang['user_manager_failed_to_delete'] . ' ' . $config['user_upload_path'] . '/' . $thumb_file_name . '<br />';
				}
			}
			$recordSet->MoveNext();
		}
		// delete all the saved images associated with a user from userImages
		$sql = 'DELETE FROM ' . $config['table_prefix'] . 'userimages WHERE (userdb_id = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$sql = 'SELECT vtourimages_file_name FROM ' . $config['table_prefix'] . 'vtourimages WHERE (userdb_id = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$vtour_file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_file_name']);

			// get rid of those darned things...
			if (!unlink($config["vtour_upload_path"] . '/' . $vtour_file_name)) {
				$errors .= $lang['user_manager_failed_to_delete'] . ' ' . $config["vtour_upload_path"] . '/' . $vtour_file_name . '<br />';
			}

			$recordSet->MoveNext();
		}
		// delete all the saved images associated with a user from userImages
		$sql = 'DELETE FROM ' . $config['table_prefix'] . 'vtourimages WHERE (userdb_id = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		if ($errors != '') {
			return $errors;
		}
		// delete all the saved vtour images associated with a user from vtourimages
		$sql = 'SELECT vtourimages_file_name FROM ' . $config['table_prefix'] . 'vtourimages WHERE (userdb_id = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$vtour_file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_file_name']);

			// get rid of those darned things...
			if (!unlink($config["vtour_upload_path"] . '/' . $vtour_file_name)) {
				$errors .= $lang['user_manager_failed_to_delete'] . ' ' . $config["vtour_upload_path"] . '/' . $vtour_file_name . '<br />';
			}

			$recordSet->MoveNext();
		}
		// delete all the saved images associated with a user from vtourimages
		$sql = 'DELETE FROM ' . $config['table_prefix'] . 'vtourimages WHERE (userdb_id = ' . $sql_delete . ')';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		if ($errors != '') {
			return $errors;
		}
		$misc->log_action ($lang['log_deleted_user'] . ': ' . $user_id);
		return true;
	}
	function show_users($filter = '', $lookup_field = '', $lookup_value = '')
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// Verify User is an Admin
		$security = login::loginCheck('edit_all_users', true);
		$display = '';
		$filter_sql = '';
		if ($filter == 'agents') {
			$filter_sql = " WHERE userdb_is_agent = 'yes'";
		}elseif ($filter == 'members') {
			$filter_sql = " WHERE userdb_is_agent = 'no' AND userdb_is_admin = 'no'";
		}elseif ($filter == 'admins') {
			$filter_sql = " WHERE userdb_is_admin = 'yes'";
		}
		if ($security === true) {
			$security2 = login::loginCheck('Admin', true);
			if ($security2 === true) {
			} else {
				if ($filter === 'Show All' || $filter === '') {
					$filter_sql = " WHERE userdb_is_admin = 'no'";
				}
			}
			$sql = "SELECT * FROM " . $config['table_prefix'] . "userdb $filter_sql ORDER BY userdb_id ";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$num_rows = $recordSet->RecordCount();
			if (!isset($_GET['cur_page'])) {
				$_GET['cur_page'] = 0;
			}
			$display .= '<center>' . $misc->next_prev($num_rows, intval($_GET['cur_page']),'','',TRUE) . '</center>'; // put in the next/previous stuff

			// build the string to select a certain number of users per page
			$limit_str = intval($_GET['cur_page']) * $config['listings_per_page'];
			$recordSet = $conn->SelectLimit($sql, $config['listings_per_page'], $limit_str);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}

			$count = 0;
			// $display .= "<br /><br />";
			while (!$recordSet->EOF) {
				// alternate the colors
				if ($count == 0) {
					$count = $count + 1;
				}else {
					$count = 0;
				}
				// strip slashes so input appears correctly
				$edit_ID = $recordSet->fields['userdb_id'];
				$edit_user_name = $misc->make_db_unsafe ($recordSet->fields['userdb_user_name']);
				$edit_user_first_name = $misc->make_db_unsafe ($recordSet->fields['userdb_user_first_name']);
				$edit_user_last_name = $misc->make_db_unsafe ($recordSet->fields['userdb_user_last_name']);
				$edit_emailAddress = $misc->make_db_unsafe ($recordSet->fields['userdb_emailaddress']);
				$edit_active = $recordSet->fields['userdb_active'];
				$edit_isAgent = $recordSet->fields['userdb_is_agent'];
				$edit_isAdmin = $recordSet->fields['userdb_is_admin'];
				$edit_canEditSiteConfig = $recordSet->fields['userdb_can_edit_site_config'];
				$edit_canEditMemberTemplate = $recordSet->fields['userdb_can_edit_member_template'];
				$edit_canEditAgentTemplate = $recordSet->fields['userdb_can_edit_agent_template'];
				$edit_canEditListingTemplate = $recordSet->fields['userdb_can_edit_listing_template'];
				$edit_canFeatureListings = $recordSet->fields['userdb_can_feature_listings'];
				$edit_canViewLogs = $recordSet->fields['userdb_can_view_logs'];
				$edit_canModerate = $recordSet->fields['userdb_can_moderate'];
				$edit_can_have_vtours = $recordSet->fields['userdb_can_have_vtours'];
				$edit_can_edit_expiration = $recordSet->fields['userdb_can_edit_expiration'];
				$edit_can_export_listings = $recordSet->fields['userdb_can_export_listings'];
				$edit_canEditAllListings = $recordSet->fields['userdb_can_edit_all_listings'];
				$edit_canEditAllUsers = $recordSet->fields['userdb_can_edit_all_users'];
				$edit_canEditPropertyClasses = $recordSet->fields['userdb_can_edit_property_classes'];
				// Determine user type
				if ($edit_isAgent == 'yes') {
					$user_type = $lang['user_manager_agent'];
				}elseif ($edit_isAdmin == 'yes') {
					$user_type = $lang['user_manager_admin'];
				}else {
					$user_type = $lang['user_manager_member'];
				}
				// Layout Start
				$display .= '<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">';
				// $display .= '<tbody style="border-width:thin;border-style:solid;border-color:#FFFFFF;">';
				$display .= '<tr bgcolor="#330099">';
				$display .= '<td width="510" colspan="2" style="padding-left:2px">';
				$display .= '<span style="color:#FFFFFF;font-weight:bold;">' . $edit_user_first_name . ' ' . $edit_user_last_name . ' (' . $edit_ID . '): ' . $edit_emailAddress . '</span>';
				$display .= '</td>';
				$display .= '<td width="90" align="right">';
				$display .= '<a href="index.php?action=user_manager&amp;edit=' . $edit_ID . '"><img src="images/' . $config['lang'] . '/user_manager_edit.jpg" alt="' . $lang['user_manager_edit_user'] . '" width="16" height="16"></a>';
				$display .= '<img src="images/blank.gif" alt=" " width="16" height="16">';
				$display .= '<a href="index.php?action=user_manager&amp;delete=' . $edit_ID . '" onclick="return confirmDelete(\'' . $lang['delete_user'] . '\')"><img src="images/' . $config['lang'] . '/user_manager_delete.jpg" alt="' . $lang['user_manager_delete_user'] . '" width="16" height="16"></a>';
				$display .= '</td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td colspan="2"><strong>' . $lang['user_manager_user_name'] . ': ' . $edit_user_name . '</strong></td>';
				$display .= '<td></td>';
				$display .= '</tr>';

				$display .= '<tr>';
				$display .= '<td colspan="2"><strong>' . $lang['user_manager_account_type'] . ': ' . $user_type . '</strong></td>';
				$display .= '<td></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td colspan="2"><strong>' . $lang['user_manager_active'] . ': ' . $edit_active . '</strong></td>';
				$display .= '<td></td>';
				$display .= '</tr>';
				if ($edit_isAgent == 'yes') {
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_admin'] . ': ' . $edit_isAdmin . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_vtour'] . ': ' . $edit_can_have_vtours . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_featured_listings'] . ': ' . $edit_canFeatureListings . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_edit_expiration'] . ': ' . $edit_can_edit_expiration . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_export_listings'] . ': ' . $edit_can_export_listings . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_edit_all_listings'] . ': ' . $edit_canEditAllListings . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_edit_all_users'] . ': ' . $edit_canEditAllUsers . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_edit_property_classes'] . ': ' . $edit_canEditPropertyClasses . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_moderate'] . ': ' . $edit_canModerate . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_view_logs'] . ': ' . $edit_canViewLogs . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_member_template_access'] . ': ' . $edit_canEditMemberTemplate . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_agent_template_access'] . ': ' . $edit_canEditAgentTemplate . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_listing_template_access'] . ': ' . $edit_canEditListingTemplate . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
					$display .= '<tr>';
					$display .= '<td colspan="2"><strong>' . $lang['user_manager_site_config_access'] . ': ' . $edit_canEditSiteConfig . '</strong></td>';
					$display .= '<td></td>';
					$display .= '</tr>';
				}
				// $display .= '</tbody>';
				$display .= '</table>';
				$recordSet->MoveNext();
			} // end while
		} // End Verify User isAdmin
		return $display;
	}
	function verify_email() {
		global $conn, $config, $lang;
		$display='';
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		if (isset($_GET['id']) && isset($_GET['key'])) {
			$userID = $misc->make_db_unsafe($_GET['id']);
			$sql = 'SELECT userdb_id, userdb_user_name, userdb_user_password, userdb_emailaddress, userdb_is_agent FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $userID;
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$user_id = $misc->make_db_unsafe ($recordSet->fields['userdb_id']);
			$user_name = $misc->make_db_unsafe ($recordSet->fields['userdb_user_name']);
			$user_pass = $misc->make_db_unsafe ($recordSet->fields['userdb_user_password']);
			$emailAddress = $misc->make_db_unsafe ($recordSet->fields['userdb_emailaddress']);
			if (md5($user_id.':'.$emailAddress) == $_GET['key']) {
				$valid = true;
			}
			if ($recordSet->fields['userdb_is_agent'] == 'yes') {
				$type = 'agent';
			} else {
				$type = 'member';
			}
			if ($config['moderate_' . $type . 's'] == 0) {
				if ($type == 'agent') {
					if ($config["agent_default_active"] == 0) {
						$set_active = "no";
					}else {
						$set_active = "yes";
					}
				}else {
					$set_active = "yes";
				}
			} else {
				$set_active = "no";
			}
			$sql_set_active = $misc->make_db_safe($set_active);
			if ($valid == true) {
				if ($config['email_notification_of_new_users'] == 1) {
					// if the site admin should be notified when a new user is added
					$message = $_SERVER['REMOTE_ADDR'] . ' -- ' . date('F j, Y, g:i:s a') . "\r\n\r\n" . $lang['admin_new_user'] . ":\r\n" . $config['baseurl'] . '/admin/index.php?action=user_manager&edit=' . $userID . "\r\n";
					$header = 'From: ' . $config['admin_name'] . ' <' . $config['admin_email'] . ">\r\n";
					$header .= "X-Sender: $config[admin_email]\r\n";
					$header .= "Return-Path: $config[admin_email]\r\n";
					mail("$config[admin_email]", "$lang[admin_new_user]", $message, $header);
				} // end if
				$verified = $misc->make_db_safe('yes');
				$sql = 'UPDATE ' . $config['table_prefix'] . 'userdb SET userdb_active = ' . $sql_set_active . ', userdb_email_verified = ' . $verified . ' WHERE userdb_id = ' . $userID;
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$display .= '<p class="notice">' . $lang['verify_email_thanks'] . '</p>';
				if ($config['moderate_' . $type . 's'] == 1) {
					// if moderation is turned on...
					$display .= '<p>' . $lang['admin_new_user_moderated'] . '</p>';
				} else {
					//log the user in
					$_SESSION['username'] = $user_name;
					$_SESSION['userpassword'] = $user_pass;
					login::loginCheck('Member');
					$display .= '<p>' . $lang['you_may_now_view_priv'] . '</p>';
				}
			} else {
				$display .= '<p class="notice">'.$lang['verify_email_invalid_link'].'</div>';
			}

		} else {
			$display .= '<p class="notice">'.$lang['verify_email_invalid_link'].'</div>';
		}
		return $display;
	}
}

?>