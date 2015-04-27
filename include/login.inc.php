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

class login {
	var $debug;
	public static function check_login()
	{
		/* Check if user has been remembered */
		if (isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])) {
			$_SESSION['username'] = $_COOKIE['cookname'];
			$_SESSION['userpassword'] = $_COOKIE['cookpass'];
		}
		/* Username and password have been set */
		if (isset($_SESSION['username']) && isset($_SESSION['userpassword'])) {
			/* Confirm that username and password are valid */
			if (login::confirm_user($_SESSION['username'], $_SESSION['userpassword']) != 0) {
				/* Variables are incorrect, user not logged in */
				unset($_SESSION['username']);
				unset($_SESSION['userpassword']);
				return false;
			}
			return true;
		}
		/* User not logged in */
		else {
			return false;
		}
	}
	public static function loginCheck($priv_level_needed, $internal = false)
	{
		global $conn, $config, $lang;
		// Load misc Class
		$display = '';
		$checked = login::check_login();
		if (!$checked AND !isset($_POST['user_name'])) {
			if ($internal !== true) {
				return login::display_login($priv_level_needed);
			}else {
				return false;
			}
		}elseif (isset($_POST['user_name'])) {
			if (!$_POST['user_name'] || !$_POST['user_pass']) {
				if ($internal !== true) {
					$display .= $lang['required_field_not_filled'];
					$display .= login::display_login($priv_level_needed);
					return $display;
				}else {
					return false;
				}
			}
			/* Spruce up username, check length */
			$_POST['user_name'] = trim($_POST['user_name']);
			if (strlen($_POST['user_name']) > 30) {
				if ($internal !== true) {
					$display .= $lang['username_excessive_length'];
					$display .= login::display_login($priv_level_needed);
					return $display;
				}else {
					return false;
				}
			}
			/* Checks that username is in database and password is correct */
			$md5pass = md5($_POST['user_pass']);
			$result = login::confirm_user($_POST['user_name'], $md5pass);
			/* Check error codes */
			if ($result == 1) {
				if ($internal !== true) {
					$display .= $lang['nonexistent_username'];
					$display .= login::display_login($priv_level_needed);
					return $display;
				}else {
					return false;
				}
			}else if ($result == 2) {
				if ($internal !== true) {
					$display .= $lang['incorrect_password'];
					$display .= login::display_login($priv_level_needed);
					return $display;
				}else {
					return false;
				}
			}else if ($result == 3) {
				if ($internal !== true) {
					$display .= $lang['inactive_user'];
					$display .= login::display_login($priv_level_needed);
					return $display;
				}else {
					return false;
				}
			}
		}
		if (isset($_POST['user_name']) || $checked) {
			/* Username and password correct, register session variables */
			if (isset($_POST['user_name'])) {
				$_POST['user_name'] = stripslashes($_POST['user_name']);
				$_SESSION['username'] = $_POST['user_name'];
				$_SESSION['userpassword'] = $md5pass;
			}
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$username = $misc->make_db_safe($_SESSION['username']);
			$sql = 'SELECT * FROM ' . $config['table_prefix'] . 'userdb WHERE  userdb_user_name= ' . $username ;
			$recordSet = $conn->Execute($sql);
			$_SESSION['userID'] = $recordSet->fields['userdb_id'];
			$_SESSION['admin_privs'] = $recordSet->fields['userdb_is_admin'];
			$_SESSION['active'] = $recordSet->fields['userdb_active'];
			$_SESSION['isAgent'] = $recordSet->fields['userdb_is_agent'];
			$_SESSION['featureListings'] = $recordSet->fields['userdb_can_feature_listings'];
			$_SESSION['viewLogs'] = $recordSet->fields['userdb_can_view_logs'];
			$_SESSION['moderator'] = $recordSet->fields['userdb_can_moderate'];
			$_SESSION['editpages'] = $recordSet->fields['userdb_can_edit_pages'];
			$_SESSION['havevtours'] = $recordSet->fields['userdb_can_have_vtours'];
			$_SESSION['havefiles'] = $recordSet->fields['userdb_can_have_files'];
			$_SESSION['is_member'] = 'yes';
			// Removed in 2.1
			// $_SESSION['editForms'] = $recordSet->fields['userdb_can_edit_forms'];
			// New Permissions with OR 2.1
			$_SESSION['edit_site_config'] = $recordSet->fields['userdb_can_edit_site_config'];
			$_SESSION['edit_member_template'] = $recordSet->fields['userdb_can_edit_member_template'];
			$_SESSION['edit_agent_template'] = $recordSet->fields['userdb_can_edit_agent_template'];
			$_SESSION['edit_listing_template'] = $recordSet->fields['userdb_can_edit_listing_template'];
			$_SESSION['export_listings'] = $recordSet->fields['userdb_can_export_listings'];
			$_SESSION['edit_all_listings'] = $recordSet->fields['userdb_can_edit_all_listings'];
			$_SESSION['edit_all_users'] = $recordSet->fields['userdb_can_edit_all_users'];
			$_SESSION['edit_property_classes'] = $recordSet->fields['userdb_can_edit_property_classes'];
			$_SESSION['edit_expiration'] =  $recordSet->fields['userdb_can_edit_expiration'];
			$_SESSION['blog_user_type']=$recordSet->fields['userdb_blog_user_type'];
			$_SESSION['can_manage_addons']=$recordSet->fields['userdb_can_manage_addons'];
			/**
			 * This is the cool part: the user has requested that we remember that
			 * he's logged in, so we set two cookies. One to hold his username,
			 * and one to hold his md5 encrypted password. We set them both to
			 * expire in 100 days. Now, next time he comes to our site, we will
			 * log him in automatically.
			 */
			if (isset($_POST['remember'])) {
				setcookie('cookname', $_SESSION['username'], time() + 60 * 60 * 24 * 100, '/');
				setcookie('cookpass', $_SESSION['userpassword'], time() + 60 * 60 * 24 * 100, '/');
			}
			if (!login::verify_priv($priv_level_needed)) {
				if ($internal !== true) {
					$display .= $lang['access_denied'];
					$display .= login::display_login($priv_level_needed);
					return $display;
				}else {
					return false;
				}
			}else {
				return true;
			}
		}
	}
	public static function display_login($priv_level_needed)
	{
		// See if we just logged in and redirect.
		global $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$login_status = login::loginCheck('Member', true);
		if ($login_status == true) {
			// Find referer UEL
			if (isset($_POST['referer']) && $_POST['referer'] != '') {
				$referer_url = $_POST['referer'];
			} elseif (isset($_SERVER['HTTP_REFERER'])) {
//				echo $_SERVER['HTTP_REFERER'];
				$pos = strpos($_SERVER['HTTP_REFERER'], 'login');
				$pos2 = strpos($_SERVER['HTTP_REFERER'], 'admin');
				if ($pos !== false || $pos2 !== false) {
					$referer_url = $config['baseurl'] . '/index.php';
				} else {
					$referer_url = $_SERVER['HTTP_REFERER'];
				}
			} else {
					$referer_url = $config['baseurl'] . '/index.php';
			}
			header('Location: ' . $referer_url);
			} else {
			@session_destroy();
			$guidestring = '';
			$display = '';
			foreach ($_GET as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $vitem) {
						$guidestring .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
					}
				}else {
					$guidestring .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
				}
			}
			global $lang, $config, $conn;
			if ($priv_level_needed == 'Member') {
				if ($config["allow_member_signup"] == 1) {
					$display .= '<p><strong>' . $lang['visitor_alert_phrase'] . '</strong></p>';
				}
			}else {
				if ($config["allow_agent_signup"] == 1) {
					$display .= '<p>' . $lang['admin_challenge_phrase'] . '</p>';
				}
			}
			if ($_GET['action'] == 'member_login') {
				if (isset($_POST['user_name'])) {
					if (!$_POST['user_name'] || !$_POST['user_pass']) {
							$display .= $lang['required_field_not_filled'];
					} else {
					/* Spruce up username, check length */
					$_POST['user_name'] = trim($_POST['user_name']);
					if (strlen($_POST['user_name']) > 30) {
							$display .= $lang['username_excessive_length'];
						}
					/* Checks that username is in database and password is correct */
					$md5pass = md5($_POST['user_pass']);
					$result = login::confirm_user($_POST['user_name'], $md5pass);
					/* Check error codes */
					if ($result == 1) {
						$display .= $lang['nonexistent_username'];
					} elseif ($result == 2) {
						$display .= $lang['incorrect_password'];
					} elseif ($result == 3) {
						$display .= $lang['inactive_user'];
					}
					}
					}
			}
			$display .= '<form action="" method="post">';
			$display .= '<input type="hidden" name="referer" value="'.$_SERVER['HTTP_REFERER'].'" />';
			$display .= '<table border="0" cellspacing="0" cellpadding="3">';
			$display .= '<tr><td>' . $lang['admin_login_name'] . ':</td><td><input type="text" name="user_name" maxlength="30" /></td></tr>';
			$display .= '<tr><td>' . $lang['admin_password'] . ':</td><td><input type="password" name="user_pass" maxlength="30" /></td></tr>';
			$display .= '<tr><td colspan="2" align="left"><input type="checkbox" name="remember" />';
			$display .= '<span style="font-size:10px">' . $lang['remember_me'] . '</span></td></tr>';
			$display .= '<tr><td colspan="2" align="right"><input type="submit" value="' . $lang['login'] . '" /></td></tr>';
			$display .= '</table>';
			$display .= '</form><br />';
			$display .= '<form action="' . $config['baseurl'] . '/admin/index.php?action=send_forgot" method="post">' . $lang['enter_your_email_address_for_pass'] . '<br /><input type="text" name="email" /><br /><input type="submit" value="' . $lang['lookup'] . '" /></form>';
			// Run the cleanup for the forgot password table
			global $db_type;
			if ($db_type == 'mysql') {
				$sql = 'DELETE FROM ' . $config['table_prefix_no_lang'] . 'forgot WHERE forgot_time < NOW() - INTERVAL 1 DAY';
			}else {
				$sql = 'DELETE FROM ' . $config['table_prefix_no_lang'] . 'forgot WHERE forgot_time < NOW() - INTERVAL \'1 DAY\'';
			}
			$recordSet = $conn->execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			return $display;
		}
	}
	public static function verify_priv($priv_level_needed)
	{
		if (!isset($_SESSION['is_member'])) {
			return false;
		}
		switch ($priv_level_needed) {
			case 'Agent':
				if ($_SESSION['isAgent'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			// case 'canEditForms':
			// if($_SESSION['editForms'] == 'yes' || $_SESSION['admin_privs'] == 'yes')
			// {
			// return TRUE;
			// }
			// break;
			case 'Admin':
				if ($_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'canViewLogs':
				if ($_SESSION['viewLogs'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'editpages':
				if ($_SESSION['editpages'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'havevtours':
				if ($_SESSION['havevtours'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'havefiles':
				if ($_SESSION['havefiles'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'Member':
				if ($_SESSION['is_member'] == 'yes') {
					return true;
				}
				break;
			case 'edit_site_config':
				if ($_SESSION['edit_site_config'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'edit_member_template':
				if ($_SESSION['edit_member_template'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'edit_agent_template':
				if ($_SESSION['edit_agent_template'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'edit_listing_template':
				if ($_SESSION['edit_listing_template'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'export_listings':
				if ($_SESSION['export_listings'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'edit_all_listings':
				if ($_SESSION['edit_all_listings'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'edit_all_users':
				if ($_SESSION['edit_all_users'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'edit_property_classes':
				if ($_SESSION['edit_property_classes'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'can_access_blog_manager':
				if ($_SESSION['blog_user_type'] >1 || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			case 'can_manage_addons':
				if ($_SESSION['can_manage_addons'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					return true;
				}
				break;
			default:
				return false;
				break;
		} // End switch($priv_level_needed)
		return false;
	} // End public static Function verify_priv()
	public static function confirm_user($username, $password)
	{
		global $conn, $config, $lang;
		// Load misc Class
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$username = $misc->make_db_safe($username);

		/* Verify that user is in database */
		$sql = "SELECT * FROM " . $config['table_prefix'] . "userdb WHERE  userdb_user_name= " . $username;
		$recordSet = $conn->Execute($sql);
		if (!$recordSet || ($recordSet->RecordCount() < 1)) {
			return 1; //Indicates username failure
		}
		if ($recordSet->fields['userdb_active'] != "yes") {
			return 3; //Indicates user is inactive
		}
		/* Retrieve password from result, strip slashes */
		$dbarray['password'] = $misc->make_db_unsafe($recordSet->fields['userdb_user_password']);
		$password = $misc->make_db_unsafe($password);

		/* Validate that password is correct */
		if ($password == $dbarray['password']) {
			return 0; //Success! Username and password confirmed
		}else {
			return 2; //Indicates password failure
		}
	}
	public static function log_out($type = 'admin')
	{
		unset($_SESSION['username']);
		unset($_SESSION['userpassword']);
		unset($_SESSION['userID']);
		unset($_SESSION['featureListings']);
		unset($_SESSION['viewLogs']);
		unset($_SESSION['admin_privs']);
		unset($_SESSION['active']);
		unset($_SESSION['isAgent']);
		unset($_SESSION['moderator']);
		unset($_SESSION['editpages']);
		unset($_SESSION['havevtours']);
		unset($_SESSION['is_member']);
		// Removed in 2.1
		// unset( $_SESSION['editForms']);
		// New Permissions with OR 2.1
		unset($_SESSION['edit_site_config']);
		unset($_SESSION['edit_member_template']);
		unset($_SESSION['edit_agent_template']);
		unset($_SESSION['edit_listing_template']);
		unset($_SESSION['export_listings']);
		unset($_SESSION['edit_all_listings']);
		unset($_SESSION['edit_all_users']);
		unset($_SESSION['edit_property_classes']);
		unset($_SESSION['edit_expiration']);
		unset($_SESSION['blog_user_type']);
		// Destroy Cookie
		setcookie("cookname", "", time() - 3600, "/");
		setcookie("cookpass", "", time() - 3600, "/");
		global $config, $lang;
		// Refresh the screen
		if ($type == 'admin') {
			header('Location:' . $config['baseurl'] . '/admin/');
		}else {
			header('Location:' . $config['baseurl'] . '/index.php');
		}
		die();
	}
	public static function forgot_password()
	{
		global $config, $lang, $conn;
		$email = $_POST['email'];
		if (is_string($email)) {
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$valid = $misc->validate_email($email);

			if ($valid) {
				$email = $misc->make_db_safe($email);
				// Verify the user has not tried to reset more then 3 times in 24 hours.
				$sql = "SELECT forgot_id FROM " . $config['table_prefix_no_lang'] . "forgot WHERE forgot_email = $email AND forgot_time > NOW() - INTERVAL 1 DAY";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}

				if ($recordSet->Recordcount() > 3) {
					return $lang['to_many_password_reset_attempts'];
				}
				if ($config["demo_mode"] == 1) {
					return $lang['password_reset_denied_demo_mode'];
				}
				$sql = "SELECT userdb_user_name, userdb_emailaddress FROM " . $config['table_prefix'] . "userdb WHERE userdb_emailaddress=" . $email;
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$num = $recordSet->RecordCount();
				if ($num == 1) {
					$forgot_rand = mt_rand(100000, 999999);
					$user_email = $misc->make_db_unsafe($recordSet->fields['userdb_emailaddress']);
					$user_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_name']);
					$sql = "INSERT INTO " . $config['table_prefix_no_lang'] . "forgot (forgot_rand, forgot_email) VALUES ($forgot_rand,'$user_email')";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
					$forgot_link = $config['baseurl'] . '/admin/index.php?action=forgot&id=' . $forgot_rand . '&email=' . $user_email;
					$message = $lang['your_username'] . "\r\n\r\n";
					$message .= $user_name . "\r\n\r\n";
					$message .= $lang['click_to_reset_password'] . "\r\n\r\n";
					$message .= $forgot_link . "\r\n\r\n";
					$message .= $lang['link_expires'] . "\r\n\r\n";
					$header = "From: " . $config['admin_name'] . " <" . $config['admin_email'] . ">\r\n";
					$header .= "X-Sender: $config[admin_email]\r\n";
					$header .= "Return-Path: $config[admin_email]\r\n";
					mail($user_email, $lang['forgotten_password'], $message, $header);
					return $lang['check_your_email'];
				} else {
					return '<font color="red">' . $lang['email_invalid_email_address'] . '</font>';
				}
			}else {
				return $lang['email_invalid_email_address'];
			}
		}
	}
	public static function forgot_password_reset()
	{
		global $config, $lang, $conn;
		$data = '';
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		if (!isset($_POST['user_pass'])) {
			if (isset($_GET['id']) || isset($_GET['email'])) {
				$id = $misc->make_db_safe($_GET['id']);
				$email = $misc->make_db_safe($_GET['email']);
				$sql = "SELECT forgot_id FROM " . $config['table_prefix_no_lang'] . "forgot WHERE forgot_email = $email AND forgot_rand = $id AND forgot_time > NOW() - INTERVAL 1 DAY";
				// echo $sql.'<br />';
				$recordSet = $conn->execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$num = $recordSet->RecordCount();
				if ($num == 1) {
					$data .= '<form action="' . $config['baseurl'] . '/admin/index.php?action=forgot" method="post">';
					$data .= '<input type="hidden" name="rand_id" value="' . htmlentities($_GET['id']) . '"><input type="hidden" name="email" value="' . htmlentities($_GET['email']) . '"><p>' . $lang['reset_password'] . ': <input type="password" name="user_pass" /></p><p><input type="submit" value="' . $lang['enter_new_password'] . '" /></p></form>';
					$data .= '</form>';
				}else {
					$data .= $lang['invalid_expired_link'];
				}
			}else {
				$data .= $lang['invalid_expired_link'];
			}
		}else {
			$id = $misc->make_db_safe($_POST['rand_id']);
			$email = $misc->make_db_safe($_POST['email']);
			$sql = "SELECT forgot_id FROM " . $config['table_prefix_no_lang'] . "forgot WHERE forgot_email = $email AND forgot_rand = $id AND forgot_time > NOW() - INTERVAL 1 DAY";
			$recordSet = $conn->execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$num = $recordSet->RecordCount();
			if ($num == 1) {
				// Delete ID from Forgot list
				$delete_id = $recordSet->fields['forgot_id'];
				$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . "forgot WHERE forgot_id = $delete_id";
				$recordSet = $conn->execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				// Set Password
				$md5_pass = md5($_POST['user_pass']);
				$md5_pass = $misc->make_db_safe($md5_pass);
				$sql = "UPDATE " . $config['table_prefix'] . "userdb SET userdb_user_password = $md5_pass WHERE userdb_emailaddress = $email";
				$recordSet = $conn->execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}else {
					$data .= '<center>' . $lang['password_changed'] . '<br /><a href="' . $config['baseurl'] . '/admin/index.php">' . $config['baseurl'] . '/admin/index.php</a></center>';
				}
			}else {
				$data .= $lang['invalid_expired_link'];
			}
		}
		return $data;
	}
} //End Class users

?>