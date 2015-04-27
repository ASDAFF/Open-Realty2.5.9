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
class misc {
	function or_date_format($date)
	{
		global $config, $conn;
		// $date_format[1] = "mm/dd/yyyy";
		// $date_format[2] = "yyyy/dd/mm";
		// $date_format[3] = "dd/mm/yyyy";
		$new_date = $date;
		switch ($config["date_format"]) {
			case 2:
				preg_match('/(.*?)\/(.*?)\/(.*)/', $date, $matches);
				$new_date = $matches[3] . '/' . $matches[1] . '/' . $matches[1];
				break;
			case 3:
				preg_match('/(.*?)\/(.*?)\/(.*)/', $date, $matches);
				$new_date = $matches[2] . '/' . $matches[1] . '/' . $matches[3];
				break;
		}
		$new_date = strtotime($new_date);
		$new_date = $conn->DBDate($new_date);
		return $new_date;
	}
	function money_formats ($number)
	{
		global $config;
		switch ($config['money_format']) {
			case '2':
				$output = $number . $config['money_sign']; // germany, spain -- 123.456,78
				break;
			case '3':
				$output = $config['money_sign'] . ' ' . $number; // honduras -- 123,456.78
				break;
			default:
				$output = $config['money_sign'] . $number; // usa, uk - $123,345
				break;
		}
		return $output;
	}
	function international_num_format($input, $decimals = 2)
	{
		// internationalizes numbers on the site
		global $config;

		switch ($config['number_format_style']) {
			case '2': // spain, germany
				if ($config['force_decimals'] == "1") {
				$output = number_format($input, $decimals, ',', '.');
				} else {
				$output = misc::formatNumber($input, $decimals, ',', '.');
				}
				break;
			case '3': // estonia
				if ($config['force_decimals'] == "1") {
				$output = number_format($input, $decimals, '.', ' ');
				} else {
				$output = misc::formatNumber($input, $decimals, '.', ' ');
				}
				break;
			case '4': // france, norway
				if ($config['force_decimals'] == "1") {
				$output = number_format($input, $decimals, ',', ' ');
				} else {
				$output = misc::formatNumber($input, $decimals, ',', ' ');
				}
				break;
			case '5': // switzerland
				if ($config['force_decimals'] == "1") {
				$output = number_format($input, $decimals, ",", "'");
				} else {
				$output = misc::formatNumber($input, $decimals, ",", "'");
				}
				break;
			case '6': // kazahistan
				if ($config['force_decimals'] == "1") {
				$output = number_format($input, $decimals, ',', '.');
				} else {
				$output = misc::formatNumber($input, $decimals, ',', '.');
				}
				break;
			default:
				if ($config['force_decimals'] == "1") {
				$output = number_format($input, $decimals, '.', ',');
				} else {
				$output = misc::formatNumber($input, $decimals, '.', ',');
				}
				break;
		} // end switch
		return $output;
	} // end international_num_format($input)
	function formatNumber($number, $decimals, $dec_point, $thousands_sep) {
		$nocomma = abs($number - floor($number));
		$strnocomma = number_format($nocomma , $decimals, ".", "");
		for ($i = 1; $i <= $decimals; $i++) {
			if (substr($strnocomma, ($i * -1), 1) != "0") {
				break;
			}
		}
		return number_format($number, ($decimals - $i +1), $dec_point, $thousands_sep);
	}
	function getmicrotime()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	function make_db_safe ($input, $skipHtmlStrip = FALSE) // handles data going into the db
	{
		global $config, $conn;
		if ($config['strip_html'] === "1" && $skipHtmlStrip == FALSE) {
			$input = strip_tags($input, $config['allowed_html_tags']); // strips out disallowed tags
		}
		$output = $conn->qstr($input, get_magic_quotes_gpc());
		return $output;
	} // end make_db_safe
	function make_db_extra_safe ($input) // handles data going into the db
	{
		global $conn;
		$output = strip_tags($input); // strips out all tags
		$output = str_replace(";", "", $output);
		$output = $conn->qstr($output, get_magic_quotes_gpc());
		$output = trim($output);
		return $output;
	} // end make_db_extra_safe
	function make_db_unsafe ($input) // handles data coming out of the db
	{
		$output = stripslashes($input); // strips out slashes
		$output = str_replace("''", "'", $output); // strips out double quotes from m$ db's
		return $output;
	} // end make_db_unsafe
	function log_error($sql)
	{
		// logs SQL errrors for later inspection
		global $config, $lang, $conn;
		$message = '';
		$message .= "Crashed by User at IP --> " . $_SERVER['REMOTE_ADDR'] . " ON " . date("F j, Y, g:i:s a") . "\r\n\r\n";
		$message .= 'SQL Error Message: '.$conn->ErrorMsg()."\r\n";
		$message .= "SQL statement that failed below: " . "\r\n";
		$message .= "---------------------------------------------------------" . "\r\n";
		$message .= $sql . "\r\n";
		$message .= "\r\n"."---------------------------------------------------------" . "\r\n";
		$message .= "\r\n"."ERROR REPORT ".$_SERVER['SERVER_NAME'].": ".date("F j, Y, g:i:s a"). "\r\n";
		$message .= "\r\n"."---------------------------------------------------------" . "\r\n";
		if(isset($_SERVER['SERVER_SOFTWARE'])){
			$message .= "Server Type: ".$_SERVER['SERVER_SOFTWARE']. "\r\n";
		}
		if(isset($_SERVER['REQUEST_METHOD'])){
			$message .= "Request Method: ".$_SERVER['REQUEST_METHOD']. "\r\n";
		}
		if(isset($_SERVER['QUERY_STRING'])){
			$message .= "Query String: ".$_SERVER['QUERY_STRING']. "\r\n";
		}
		if(isset($_SERVER['HTTP_REFERER'])){
			$message .= "Refereer: ".$_SERVER['HTTP_REFERER']. "\r\n";
		}
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			$message .= "User Agent: ".$_SERVER['HTTP_USER_AGENT']. "\r\n";
		}
		if(isset($_SERVER['REQUEST_URI'])){
			$message .= "Request URI: ".$_SERVER['REQUEST_URI']. "\r\n";
		}

		$message .= "POST Variables: ".var_export($_POST,TRUE). "\r\n";
		$message .= "GET Variables: ".var_export($_GET,TRUE). "\r\n";
		$header = "From: " . $config['admin_email'] . " <" . $config['admin_email'] . ">\n";
		$header .= "X-Sender: $config[admin_email]\n";
		$header .= "Return-Path: $config[admin_email]\n";
		mail("$config[admin_email]", "SQL Error http://$_SERVER[SERVER_NAME]", $message, $header,'-f '.$config[admin_email]);
		header('Location: 500.shtml');die;
		//die(nl2br($message));
	} // end function log_action
	function next_prev($num_rows, $cur_page, $guidestring = '', $template = '',$admin=FALSE) // handles multiple page listings
	{
		global $lang, $config;
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$page = new page_user();
		if (isset($template) && $template != '') {
			$template_file = 'next_prev_'.$template.'.html';
		} else {
			$template_file = 'next_prev.html';
		}
		if($admin==TRUE){
			$page->load_page($config['admin_template_path'] . '/'.$template_file);
		}else{
			$page->load_page($config['template_path'] . '/'.$template_file);
		}
		$guidestring = '';
		$guidestring_no_action = '';
		$guidestring_with_sort = '';
		// Save GET
		foreach ($_GET as $k => $v) {
			if ($v && $k != 'cur_page' && $k != 'PHPSESSID') {
				if (is_array($v)) {
					foreach ($v as $vitem) {
						$guidestring .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
					}
				}else {
					$guidestring .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
				}
			}
			if ($v && $k != 'cur_page' && $k != 'PHPSESSID' && $k != 'action') {
				if (is_array($v)) {
					foreach ($v as $vitem) {
						$guidestring_no_action .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
					}
				}else {
					$guidestring_no_action .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
				}
			}
		}
		$page->page = str_replace('{nextprev_guidestring}', $guidestring, $page->page);
		$page->page = str_replace('{nextprev_guidestring_no_action}', $guidestring_no_action, $page->page);
		if ($cur_page == "") {
			$cur_page = 0;
		}
		$page_num = $cur_page + 1;

		$page->page = str_replace('{nextprev_num_rows}', $num_rows, $page->page);
		if($_GET['action'] == 'view_log') {
			$items_per_page = 25;
			$page->page = str_replace('{nextprev_page_type}', $lang['log'], $page->page);
			$page->page = str_replace('{nextprev_meet_your_search}', $lang['logs_meet_your_search'], $page->page);

			if ($num_rows == 1) {
				$page->page = $page->remove_template_block('!nextprev_num_of_rows_is_1', $page->page);
				$page->page = $page->cleanup_template_block('nextprev_num_of_rows_is_1', $page->page);
			} else {
				$page->page = $page->remove_template_block('nextprev_num_of_rows_is_1', $page->page);
				$page->page = $page->cleanup_template_block('!nextprev_num_of_rows_is_1', $page->page);
			}
		} elseif($_GET['action'] == 'view_users'){
			$items_per_page = $config['users_per_page'];
			$page->page = str_replace('{nextprev_page_type}', $lang['agent'], $page->page);
			$page->page = str_replace('{nextprev_meet_your_search}', $lang['agents'], $page->page);
			if ($num_rows == 1) {
				$page->page = $page->remove_template_block('!nextprev_num_of_rows_is_1', $page->page);
				$page->page = $page->cleanup_template_block('nextprev_num_of_rows_is_1', $page->page);
			}else {
				$page->page = $page->remove_template_block('nextprev_num_of_rows_is_1', $page->page);
				$page->page = $page->cleanup_template_block('!nextprev_num_of_rows_is_1', $page->page);
			}
		}else {
			$items_per_page = $config['listings_per_page'];
			$page->page = str_replace('{nextprev_page_type}', $lang['listing'], $page->page);
			$page->page = str_replace('{nextprev_meet_your_search}', $lang['listings_meet_your_search'], $page->page);
			if ($num_rows == 1) {
				$page->page = $page->remove_template_block('!nextprev_num_of_rows_is_1', $page->page);
				$page->page = $page->cleanup_template_block('nextprev_num_of_rows_is_1', $page->page);
			}else {
				$page->page = $page->remove_template_block('nextprev_num_of_rows_is_1', $page->page);
				$page->page = $page->cleanup_template_block('!nextprev_num_of_rows_is_1', $page->page);
			}
		}
		$total_num_page = ceil($num_rows / $items_per_page);
		if($total_num_page==0){
			$listing_num_min=0;
			$listing_num_max=0;
		}else{
			$listing_num_min = (($cur_page * $items_per_page) + 1);
			if ($page_num == $total_num_page) {
				$listing_num_max = $num_rows;
			} else {
				$listing_num_max = $page_num * $items_per_page;
			}
		}


		$page->page = str_replace('{nextprev_listing_num_min}', $listing_num_min, $page->page);
		$page->page = str_replace('{nextprev_listing_num_max}', $listing_num_max, $page->page);
		$prevpage = $cur_page-1;
		$nextpage = $cur_page + 1;
		$next10page = $cur_page + 10;
		$prev10page = $cur_page-10;
		$next_minus10page = $cur_page-10;
		$page->page = str_replace('{nextprev_nextpage}', $nextpage, $page->page);
		$page->page = str_replace('{nextprev_prevpage}', $prevpage, $page->page);
		$page->page = str_replace('{nextprev_next10page}', $next10page, $page->page);
		$page->page = str_replace('{nextprev_prev10page}', $prev10page, $page->page);

		if ($_GET['action'] == 'searchresults') {
			$page->page = $page->cleanup_template_block('nextprev_show_save_search', $page->page);
		}else{
			$page->page = $page->remove_template_block('nextprev_show_save_search', $page->page);
		}
		if ($_GET['action'] == 'searchresults') {
			$page->page = $page->cleanup_template_block('nextprev_show_refine_search', $page->page);
		}else{
			$page->page = $page->remove_template_block('nextprev_show_refine_search', $page->page);
		}
		if ($page_num <= 1) {
			$page->page = $page->cleanup_template_block('nextprev_is_firstpage', $page->page);
			$page->page = $page->remove_template_block('!nextprev_is_firstpage', $page->page);
		}

		if ($page_num > 1) {
			$page->page = $page->cleanup_template_block('!nextprev_is_firstpage', $page->page);
			$page->page = $page->remove_template_block('nextprev_is_firstpage', $page->page);
		} //end if ($page_num > 10)
		// begin 10 page menu selection
		$count = $cur_page;

		//Determine Where to Start the Page Count At
		$count_start = $count-10;
		if($count_start <0){
			$count_start=0;
			$real_count_start=0;
		}else{
			while(!preg_match("/0$/", $count_start)) {
				$count_start++;
			}
		}
		//echo 'Count Start '.$count_start.'<br />';
		//$count = ($count - $lastnum);
		$page_section_part = $page->get_template_section('nextprev_page_section');
		$page_section='';

		$reverse_count = $count_start;
		while ($count > $count_start){
			//echo 'Count '.$count.'<br />';
			//echo 'Reverse Count '.$reverse_count.'<br />';
		// If the last number is a zero, it's divisible by 10 check it...
			if (preg_match("/0$/", $count)) {
				break;
			}
			$page_section .= $page_section_part;
			$disp_count = ($reverse_count+1);

			$page_section = str_replace('{nextprev_count}', $reverse_count, $page_section);
			$page_section = str_replace('{nextprev_disp_count}', $disp_count, $page_section);
			$page_section = $page->cleanup_template_block('nextprev_page_other', $page_section);
			$page_section = $page->remove_template_block('nextprev_page_current', $page_section);
			$count--;
			$reverse_count++;

		}
		$count = $cur_page;
		while ($count < $total_num_page) {
			$page_section .= $page_section_part;
			$disp_count = ($count + 1);
			$page_section = str_replace('{nextprev_count}', $count, $page_section);
			$page_section = str_replace('{nextprev_disp_count}', $disp_count, $page_section);
			if ($page_num == $disp_count) {
				// the currently selected page
				$page_section = $page->cleanup_template_block('nextprev_page_current', $page_section);
				$page_section = $page->remove_template_block('nextprev_page_other', $page_section);
			}else {
				$page_section = $page->cleanup_template_block('nextprev_page_other', $page_section);
				$page_section = $page->remove_template_block('nextprev_page_current', $page_section);
			}
			$count++;
			// If the last number is a zero, it's divisible by 10 check it...
			if (!($count%10)) {
				break;
			}
		} // end while ($count <= 10)
		$page->replace_template_section('nextprev_page_section', $page_section);
		if ($page_num >= $total_num_page) {
			$page->page = $page->cleanup_template_block('nextprev_lastpage', $page->page);
			$page->page = $page->remove_template_block('!nextprev_lastpage', $page->page);
		}
		if ($page_num < $total_num_page) {
			$diff = ($total_num_page - $cur_page);
			$page->page = $page->cleanup_template_block('!nextprev_lastpage', $page->page);
			$page->page = $page->remove_template_block('nextprev_lastpage', $page->page);
		} //end if
		// search buttons
		if ($page_num >= 11) { // previous 10 page
			$page->page = $page->cleanup_template_block('nextprev_prev_100_button', $page->page);
			$page->page = $page->remove_template_block('!nextprev_prev_100_button', $page->page);
		} else {
			$page->page = $page->cleanup_template_block('!nextprev_prev_100_button', $page->page);
			$page->page = $page->remove_template_block('nextprev_prev_100_button', $page->page);
		}
		// Next 100 button
		if (($cur_page < ($total_num_page - $config['listings_per_page'])) && ($total_num_page > 10)) {
			$page->page = $page->cleanup_template_block('nextprev_next_100_button', $page->page);
			$page->page = $page->remove_template_block('!nextprev_next_100_button', $page->page);
		} else {
			$page->page = $page->cleanup_template_block('!nextprev_next_100_button', $page->page);
			$page->page = $page->remove_template_block('nextprev_next_100_button', $page->page);
		}
		if ($_GET['action'] == 'view_log' && $_SESSION['admin_privs'] == "yes") {
			$page->page = $page->cleanup_template_block('nextprev_clearlog', $page->page);
		}else{
			$page->page = $page->remove_template_block('nextprev_clearlog', $page->page);
		}
		return $page->page;
	} // end function next_prev

	function send_email($sender, $sender_email, $recipient, $message, $subject,$isHTML=FALSE,$skipRefCheck=FALSE)
	{
		global $config, $lang;
		// Make sure data is comming from the site. (Easily faked, but will stop some of the spammers)
		$referers = $config['baseurl'];
		$referers = str_replace('http://', '', $referers);
		$referers = str_replace('https://', '', $referers);
		$referers = str_replace('www.', '', $referers);
		$referers = explode("/", $referers);
		$found = false;
		$temp = explode("/", $_SERVER['HTTP_REFERER']);
		$referer = $temp[2];
		if (eregi ($referers[0], $referer) || $skipRefCheck==TRUE) {
			$found = true;
		}
		if (!$found) {
			$temp = $lang['email_not_authorized'];
			return $temp;
		}else {
			// First, make sure the form was posted from a browser.
			// For basic web-forms, we don't care about anything
			// other than requests from a browser:
			if (!isset($_SERVER['HTTP_USER_AGENT'])) {
				$temp = $lang['email_not_authorized'];
				return $temp;
			}
			// Attempt to defend against header injections:
			$badStrings = array("Content-Type:",
				"MIME-Version:",
				"Content-Transfer-Encoding:",
				"bcc:",
				"cc:");
			foreach($badStrings as $v2) {
				if (strpos($sender, $v2) !== false) {
					$temp = $lang['email_not_authorized'];
					return $temp;
					exit;
				}
				if (strpos($sender_email, $v2) !== false) {
					$temp = $lang['email_not_authorized'];
					return $temp;
					exit;
				}
				if (strpos($recipient, $v2) !== false) {
					$temp = $lang['email_not_authorized'];
					return $temp;
					exit;
				}
				if (strpos($message, $v2) !== false) {
					$temp = $lang['email_not_authorized'];
					return $temp;;
					exit;
				}
				if (strpos($subject, $v2) !== false) {
					$temp = $lang['email_not_authorized'];
					return $temp;
					exit;
				}
			}
			// validate Sender_email as a Spam check
			$valid = $this->validate_email($sender_email);
			if ($valid) {
				$message = stripslashes($message);
				$subject = stripslashes($subject);
				if($isHTML){
					$header = "Content-Type: text/html; charset=" . $config['charset'] . "\n";
				}else{
					$header = "Content-Type: text/plain; charset=" . $config['charset'] . "\n";
				}

				$header .= "Content-Transfer-Encoding: 8bit\n";
				$header .= "From: " . $sender . " <" . $sender_email . ">\n";
				$header .= "Return-Path: $config[admin_email]\n";
				$header .= "X-Sender: <" . $config["admin_email"] . ">\n";
				$header .= "X-Mailer: Open-Realty " . $config["version"] . " - Installed at " . $config["baseurl"] . "\n";
				$temp = mail($recipient, $subject, $message, $header,'-f '.$sender_email);
			}else {
				$temp = false;
			}
			return $temp;
		}
	}
	function log_action($log_action)
	{
		$log_action = $this->make_db_safe($log_action);
		// logs user actions
		global $conn, $config;
		if (isset($_SESSION['userID'])) {
			$id = $this->make_db_safe($_SESSION['userID']);
		}else {
			$id = 0;
		}
		$sql = "INSERT INTO " . $config['table_prefix'] . "activitylog (activitylog_log_date, userdb_id, activitylog_action, activitylog_ip_address) VALUES (" . $conn->DBTimeStamp(time()) . ", " . $id . ", $log_action, '$_SERVER[REMOTE_ADDR]')";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$this->log_error($sql);
		}
	} // end function log_action
	function os_type()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$OS = "Windows";
		}else {
			$OS = "Linux";
		}
		return $OS;
	}
	function validate_email($email)
	{
		// Create the syntactical validation regular expression
		$regexp = "^([_a-z0-9-]+)(\.[_a-z0-9-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$";
		// Presume that the email is invalid
		$valid = false;
		// Validate the syntax
		if (eregi($regexp, $email)) {
			list($username, $domaintld) = split("@", $email);
			$OS = $this->os_type();
			if ($OS == 'Linux') {
				if (checkdnsrr($domaintld, "MX")) {
					return true;
				}
			}else {
				return true;
			}
		}
		return $valid;
	}
	// This function is no longer needed. Scripts should no longer call it.
	function clear_cache()
	{
		return true;
	}
	function parseDate( $date, $format ) {
		//Supported formats
		//%Y - year as a decimal number including the century
		//%m - month as a decimal number (range 01 to 12)
		//%d - day of the month as a decimal number (range 01 to 31)
		//%H - hour as a decimal number using a 24-hour clock (range 00 to 23)
		//%M - minute as a decimal number
	   // Builds up date pattern from the given $format, keeping delimiters in place.
	   if( !preg_match_all( "/%([YmdHMp])([^%])*/", $format, $formatTokens, PREG_SET_ORDER ) ) {
		   return false;
	   }
	   foreach( $formatTokens as $formatToken ) {
		   $delimiter = preg_quote( $formatToken[2], "/" );
		   $datePattern .= "(.*)".$delimiter;
	   }
	   // Splits up the given $date
	   if( !preg_match( "/".$datePattern."/", $date, $dateTokens) ) {
		   return false;
	   }
	   $dateSegments = array();
	   for($i = 0; $i < count($formatTokens); $i++) {
		   $dateSegments[$formatTokens[$i][1]] = $dateTokens[$i+1];
	   }
	   // Reformats the given $date into US English date format, suitable for strtotime()
	   if( $dateSegments["Y"] && $dateSegments["m"] && $dateSegments["d"] ) {
		   $dateReformated = $dateSegments["Y"]."-".$dateSegments["m"]."-".$dateSegments["d"];
	   }
	   else {
		   return false;
	   }
	   if( $dateSegments["H"] && $dateSegments["M"] ) {
		   $dateReformated .= " ".$dateSegments["H"].":".$dateSegments["M"];
	   }

	   return strtotime( $dateReformated );
	}

	function sanitize($value,$length='' )
	{
		if( get_magic_quotes_gpc() )
		{
			  $value = stripslashes( $value );
		}
		if ($length !='')
		{
		$value=substr($value,0,$length);
		$value = mysql_real_escape_string( $value );
		}
		else
		{
		$value = mysql_real_escape_string( $value );
		}
		return $value;
	}

	function clean_filename($filename)
	{
//function to clean a filename string so it is a valid filename
//replaces all characters that are not alphanumeric with the exception of the . for filename usage
		$realname = preg_replace("/[^a-zA-Z0-9\.]/", "", strtolower($filename));
		return $realname;
	}

	function urlencode_to_sef($url_title)
	{
	// function to allow special chars at listing title with SEF links
	return urlencode(utf8_encode($url_title));
	}

} //End Class misc
?>