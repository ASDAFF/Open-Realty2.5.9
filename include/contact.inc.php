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

/**
 * contact
 * This class contains all functions related to contacting people agents and friends about listings.
 *
 * @author Ryan Bonham
 * @copyright Copyright (c) 2005
 */
class contact {
	/**
	 * Contact::ContactAgentForm()
	 *
	 * @param integer $listing_id This should hold the listing ID. Listing_id is used only if agent_id is not set
	 * @param integer $agent_id This should hold the agent id
	 * @return
	 */
	function ContactAgentForm($listing_id = 0, $agent_id = 0)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$error = array();
		$listing_id=intval($listing_id);
		$agent_id=intval($agent_id);
		if ($agent_id == 0) {
			if ($listing_id != 0) {
				$sql_listing_id = $misc->make_db_safe($listing_id);
				$sql = 'SELECT userdb_id FROM ' . $config['table_prefix'] . 'listingsdb WHERE listingsdb_id = ' . $sql_listing_id;
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$agent_id = $misc->make_db_unsafe($recordSet->fields['userdb_id']);
			}
		}
		if (isset($_POST['message'])) {
			// Make sure there is a message
			if( ($_SESSION['security_code'] != md5($_POST['security_code'])) && $config["use_email_image_verification"] == 1 ) {
				$error[] = 'email_verification_code_not_valid';
			}
			if (trim($_POST['name']) == '') {
				$error[] = 'email_no_name';
			}
			if (trim($_POST['email']) == '') {
				$error[] = 'email_no_email_address';
			}elseif ($misc->validate_email($_POST['email']) !== true) {
				$error[] = 'email_invalid_email_address';
			}
			if (trim($_POST['subject']) == '') {
				$error[] = 'email_no_subject';
			}
			if (trim($_POST['message']) == '') {
				$error[] = 'email_no_message';
			}
		}
		if (count($error) == 0 && isset($_POST['message'])) {
			// Grab Agents Email
			$sql_agent_id = $misc->make_db_safe($agent_id);
			$sql = 'SELECT userdb_emailaddress FROM ' . $config['table_prefix'] . 'userdb WHERE userdb_id = ' . $sql_agent_id;
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			if ($config["include_senders_ip"] == 1) {
				$_POST['message'] .= "\r\n" . $lang['senders_ip_address'] . $_SERVER["REMOTE_ADDR"];
			}
			if ($recordSet->RecordCount() != 0) {
				$emailaddress = $misc->make_db_unsafe($recordSet->fields['userdb_emailaddress']);
				// Send Mail
				$sent = $misc->send_email($_POST['name'], $_POST['email'], $emailaddress, $_POST['message'], $_POST['subject']);
				if ($sent === true) {
					$display .= $lang['email_listing_agent_sent'];
				}else {
					$display .= $sent;
				}
			}
		}else {
			if (count($error) != 0) {
				foreach ($error as $err) {
					$display .= '<div class="error_text">' . $lang[$err] . '</div>';
				}
			}
			$name = '';
			$email = '';
			$subject = '';
			if ($listing_id !== 0) {
				$subject = $lang['email_in_reference_to_listing'] . $listing_id;
			}
			$message = '';
			if (isset($_POST['message'])) {
				$email = stripslashes($_POST['email']);
				$name = stripslashes($_POST['name']);
				$message = stripslashes($_POST['message']);
				$subject = stripslashes($_POST['subject']);
			}
			$display .= '<form name="contact_agent" method="post" action="index.php?action=contact_agent&amp;popup=yes&amp;listing_id=' . $listing_id . '&amp;agent_id=' . $agent_id . '">
				<table border="0" cellspacing="2" cellpadding="4">
					<tr>
						<td colspan="2" style="vertical-align: top" class="TitleColor">
							<label for="name">' . $lang['email_your_name'] . '&nbsp;&nbsp;</label>
							<input id="name" name="name" value="' . htmlentities($name) . '" type="text" size="50" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align: top" class="TitleColor">
							<label for="email">' . $lang['email_your_email'] . '&nbsp;&nbsp;&nbsp;</label>
							<input id="email" name="email" value="' . htmlentities($email) . '" type="text" size="50" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align: top" class="TitleColor">
							<label for="subject">' . $lang['email_your_subject'] . '</label>
							<input id="subject" name="subject" value="' . htmlentities($subject, ENT_NOQUOTES, $config['charset']) . '" type="text" size="50" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align: top" class="TitleColor">
							<label for="message">' . $lang['email_your_message'] . '</label><br /><br />
							<textarea id="message" name="message" rows="5" cols="50">' . htmlentities($message) . '</textarea>
						</td>
					</tr>';
					if($config["use_email_image_verification"] == 1 ) {
						$display .= '<tr>
							<td colspan="2"><img src="'.$config['baseurl'].'/include/class/captcha/captcha_image.php" alt="" /></td>
						</tr>
						<tr>
							<td colspan="2" style="vertical-align: top" class="TitleColor">
								<label for="security_code">' . $lang['email_verification_code'] . '</label>
								<input id="security_code" name="security_code" type="text" />
							</td>
						</tr>';
					}
					$display .= '<tr>
						<td colspan="2"><input type="submit" name="Submit" value="' . $lang['email_send'] . '" /></td>
					</tr>
				</table>
				</form>';
		}
		return $display;
	}
	/**
	 * Contact::ContactFriendForm()
	 *
	 * @param integer $listing_id This should hold the listing ID that you aer emailing your friend about.
	 * @return
	 */
	function ContactFriendForm($listing_id)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$error = array();
		if (isset($_POST['message'])) {
			// Make sure there is a message
			if( ($_SESSION['security_code'] != md5($_POST['security_code'])) && $config["use_email_image_verification"] == 1 ) {
				$error[] = 'email_verification_code_not_valid';
			}
			if (trim($_POST['name']) == '') {
				$error[] = 'email_no_name';
			}
			if (trim($_POST['email']) == '') {
				$error[] = 'email_no_email_address';
			}
			elseif ($misc->validate_email($_POST['email']) !== true) {
				$error[] = 'email_invalid_email_address';
			}
			if (trim($_POST['friend_email']) == '') {
				$error[] = 'email_no_email_address';
			}elseif ($misc->validate_email($_POST['friend_email']) !== true) {
				$error[] = 'email_invalid_email_address';
			}
			if (trim($_POST['subject']) == '') {
				$error[] = 'email_no_subject';
			}
			if (trim($_POST['message']) == '') {
				$error[] = 'email_no_message';
			}
		}
		if (count($error) == 0 && isset($_POST['message'])) {
			// Send Mail
			$sent = $misc->send_email($_POST['name'], $_POST['email'], $_POST['friend_email'], $_POST['message'], $_POST['subject']);
			if ($sent === true) {
				$display .= $lang['email_listing_sent'] . ' ' . $_POST['friend_email'];
			}else {
				$display .= $sent;
			}
		}else {
			if (count($error) != 0) {
				foreach ($error as $err) {
					$display .= '<div class="error_text">' . $lang[$err] . '</div>';
				}
			}
			$name = '';
			$email = '';
			$subject = '';
			// $friend_name = '';
			$friend_email = '';
			$message = '';
			if (isset($_POST['message'])) {
				$email = stripslashes($_POST['email']);
				$name = stripslashes($_POST['name']);
				$message = stripslashes($_POST['message']);
				$subject = stripslashes($_POST['subject']);
				// $friend_name = $_POST['friend_name'];
				$friend_email = stripslashes($_POST['friend_email']);
			}else {
				$subject = $lang['email_in_reference_to_listing'] . $listing_id;
				$message = $lang['email_listing_default_message'] . "\r\n\r\n" . $config['baseurl'] . '/index.php?action=listingview&listingID=' . $listing_id;
			}
			$display .= '<form name="contact_friend" method="post" action="index.php?action=contact_friend&amp;popup=yes&amp;listing_id=' . $listing_id . '">
				<table border="0" cellspacing="2" cellpadding="4">
					<tr>
						<td colspan="2" style="vertical-align: top" class="TitleColor">
							<label for="name">' . $lang['email_your_name'] . '&nbsp;&nbsp;</label>
							<input id="name" name="name" value="' . htmlentities($name) . '" type="text" size="50" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align: top" class="TitleColor">
							<label for="email">' . $lang['email_your_email'] . '&nbsp;&nbsp;&nbsp;</label>
							<input id="email" name="email" value="' . htmlentities($email) . '" type="text" size="50" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align: top" class="TitleColor">
							<label for="email">' . $lang['email_friend_email'] . '&nbsp;&nbsp;&nbsp;</label>
							<input id="email" name="friend_email" value="' . htmlentities($friend_email) . '" type="text" size="50" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align: top" class="TitleColor">
							<label for="subject">' . $lang['email_your_subject'] . '</label>
							<input id="subject" name="subject" value="' . htmlentities($subject, ENT_NOQUOTES, $config['charset']) . '" type="text" size="50" />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align: top" class="TitleColor">
							<label for="message">' . $lang['email_your_message'] . '</label><br /><br />
							<textarea id="message" name="message" rows="5" cols="50">' . htmlentities($message, ENT_NOQUOTES, $config['charset']) . '</textarea>
						</td>
					</tr>';
			if($config["use_email_image_verification"] == 1 ) {
				$display .= '<tr>
							<td colspan="2"><img src="'.$config['baseurl'].'/include/class/captcha/captcha_image.php" alt="" /></td>
						</tr>
						<tr>
							<td colspan="2" style="vertical-align: top" class="TitleColor">
								<label for="security_code">' . $lang['email_verification_code'] . '</label>
								<input id="security_code" name="security_code" type="text" />
							</td>
						</tr>';
				}
			$display .= '<tr>
						<td colspan="2"><input type="submit" name="Submit" value="' . $lang['email_send'] . '" /></td>
					</tr>
				</table>
				</form>';
		}
		return $display;
	}
}

?>