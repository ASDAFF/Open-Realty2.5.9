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
 * user
 *
 * @author Ryan Bonham
 * @copyright Copyright (c) 2005
 */
class user {
	public static function get_agent_link($agentID){
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// get the main data for a given listing
		$agentID=intval($agentID);
		if ($config['url_style'] == '1') {
			$display .= "index.php?action=view_user&amp;user=$agentID";
		}else {
			$Title = user::get_user_first_name($agentID).' '.user::get_user_last_name($agentID);
			$url_title = str_replace("/", "", $Title);
			$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
			$display .= 'agent-' . urlencode($url_title) . '-' . $agentID . '.html';
		}
		return $display;
	}
	/**
	 * user::view_user()
	 *
	 * @param  $type
	 * @return
	 */
	public static function view_users()
	{
		global $conn, $config, $lang,$agent_id;
		require_once($config['basepath'] . '/include/misc.inc.php');
		require_once($config['basepath'] . '/include/images.inc.php');
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$misc = new misc();
		$display = '';
		$user_section = '';
		$page = new page_user();
		$page->load_page($config['template_path'] . '/view_users_default.html');
		//Get User Count
		$sql = "SELECT count(userdb_id) as user_count FROM " . $config['table_prefix'] . "userdb where userdb_is_agent = 'yes' and userdb_active = 'yes' order by userdb_rank,userdb_user_name";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_rows= $recordSet->fields['user_count'];
		if ($config["show_admin_on_agent_list"] == 0 ) {
			$options = "userdb_is_agent = 'yes'";
		} else {
			$options = "(userdb_is_agent = 'yes' or userdb_is_admin = 'yes')";
		}
		$sql = "SELECT userdb_user_name, userdb_user_first_name, userdb_user_last_name, userdb_id FROM " . $config['table_prefix'] . "userdb where " . $options . " and userdb_active = 'yes' order by userdb_rank,userdb_user_name";
		//Handle Pagnation
		if(!isset($_GET['cur_page'])){
			$_GET['cur_page']=0;
		}
		$limit_str = intval($_GET['cur_page']) * $config['users_per_page'];
		$some_num = intval($_GET['cur_page']) + 1;
		$next_prev = $misc->next_prev($num_rows, intval($_GET['cur_page']), $guidestring); // put in the next/previous stuff
		$recordSet = $conn->SelectLimit($sql, $config['users_per_page'], $limit_str);
		//$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$first_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_first_name']);
			$last_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_last_name']);
			$agent_id = $misc->make_db_unsafe($recordSet->fields['userdb_id']);
			$agent_link = user::get_agent_link($recordSet->fields['userdb_id']);
			$agent_contact_link = user::contact_agent_link($agent_id);
			$agent_fields = user::renderUserInfo($agent_id);
			$user_section .= $page->get_template_section('user_block');
			$user_section = $page->parse_template_section($user_section, 'agent_first_name', $first_name);
			$user_section = $page->parse_template_section($user_section, 'agent_last_name', $last_name);
			$user_section = $page->parse_template_section($user_section, 'agent_id', $agent_id);
			$user_section = $page->parse_template_section($user_section, 'agent_contact_link', $agent_contact_link);
			$user_section = $page->parse_template_section($user_section, 'agent_fields', $agent_fields);
			$user_section = $page->parse_template_section($user_section, 'agent_link', $agent_link);
			// Insert Agent Image
			$sql2 = "SELECT userimages_thumb_file_name FROM " . $config['table_prefix'] . "userimages WHERE userdb_id = $agent_id ORDER BY userimages_rank";
			$recordSet2 = $conn->Execute($sql2);
			if ($recordSet2 === false) {
				$misc->log_error($sql2);
			}
			$num_images = $recordSet2->RecordCount();
			if ($num_images == 0) {
				if ($config['show_no_photo'] == 1) {
					$agent_image = '<img src="images/nophoto.gif" alt="' . $lang['no_photo'] . '" />';
					$raw_agent_image = 'images/nophoto.gif';
					$user_section = $page->cleanup_template_block('agent_image_thumb_1', $user_section);
				}else {
					$agent_image = '';
					$raw_agent_image = '';
				}
				$user_section = $page->parse_template_section($user_section, 'agent_image_thumb_1', $agent_image);
				$user_section = $page->parse_template_section($user_section, 'raw_agent_image_thumb_1', $raw_agent_image);
				$user_section = $page->remove_template_block('agent_image_thumb_[1-9]',$user_section);

			}
			$x = 1;
			while (!$recordSet2->EOF) {
				$thumb_file_name = $misc->make_db_unsafe ($recordSet2->fields['userimages_thumb_file_name']);
				if ($thumb_file_name != "") {
					// gotta grab the image size
					$imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
					$imagewidth = $imagedata[0];
					$imageheight = $imagedata[1];
					$shrinkage = $config['thumbnail_width'] / $imagewidth;
					$displaywidth = $imagewidth * $shrinkage;
					$displayheight = $imageheight * $shrinkage;
					$agent_image = '<img src="' . $config['user_view_images_path'] . '/' . $thumb_file_name . '" height="' . $displayheight . '" width="' . $displaywidth . '" alt="' . $thumb_file_name . '" />';
					$raw_agent_image = $config['user_view_images_path'] . '/' . $thumb_file_name;
				} // end if ($thumb_file_name != "")
				// We have the image so insert it into the section.
				$user_section = $page->parse_template_section($user_section, 'agent_image_thumb_' . $x, $agent_image);
				$user_section = $page->parse_template_section($user_section, 'raw_agent_image_thumb_' . $x, $raw_agent_image);
				$user_section = $page->cleanup_template_block('agent_image_thumb_' . $x, $user_section);
				$x++;
				$recordSet2->MoveNext();
			} // end while
			$user_section=preg_replace('{agent_image_thumb_(.*?)}', '',  $user_section);
			$user_section =preg_replace('{raw_agent_image_thumb_(.*?)}', '',  $user_section);
			$user_section = $page->remove_template_block('agent_image_thumb_[1-9]',$user_section);
			$recordSet->MoveNext();
		}

		$page->replace_template_section('user_block', $user_section);
		$page->page = str_replace('{next_prev}', $next_prev, $page->page);
		return $page->page;
	}
	public static function view_user()
	{
		global $conn, $lang, $config, $user;
		require_once($config['basepath'] . '/include/misc.inc.php');
		require_once($config['basepath'] . '/include/images.inc.php');
		$display = '';
		$user = intval($_GET['user']);
		if ($user != "") {
		$misc = new misc();
		$sql = "SELECT userdb_is_agent, userdb_is_admin FROM " . $config['table_prefix'] . "userdb WHERE userdb_id = " . $user . "";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		while (!$recordSet->EOF) {
			$is_agent = $misc->make_db_unsafe ($recordSet->fields['userdb_is_agent']);
			$is_admin = $misc->make_db_unsafe ($recordSet->fields['userdb_is_admin']);
			$recordSet->MoveNext();
		} // end while

		if (($is_agent == 'yes') || ($is_admin == true && $config["show_listedby_admin"] == 1)) {
			require_once(dirname(__FILE__).'/class/template/core.inc.php');
			$page = new page_user();
			require_once(dirname(__FILE__).'/images.inc.php');
			$image_handler = new image_handler();
			require_once(dirname(__FILE__).'/files.inc.php');
			$file_handler = new file_handler();
			$page->load_page($config['template_path'] . '/' . $config['agent_template']);
			//Replace Tags
			$page->page = str_replace('{user_last_name}', $this->get_user_last_name($user), $page->page);
			$page->page = str_replace('{user_first_name}', $this->get_user_first_name($user), $page->page);
			$page->page = str_replace('{user_images_thumbnails}', $image_handler->renderUserImages($user), $page->page);
			$page->page = str_replace('{user_display_info}', $this->renderUserInfo($user), $page->page);
			$page->page = str_replace('{user_contact_link}', $this->contact_agent_link($user), $page->page);
			$page->page = str_replace('{user_vcard_link}', $this->vcard_agent_link($user), $page->page);
			$page->page = str_replace('{user_listings_list}', $this->userListings($user), $page->page);
			$page->page = str_replace('{user_hit_count}', $this->userHitcount($user), $page->page);
			$page->page = str_replace('{user_id}', $user, $page->page);
			$page->page = str_replace('{user_listings_link}', $this->userListingsLink($user), $page->page);
			$page->page = str_replace('{files_user_horizontal}', $file_handler->render_templated_files($user, 'user', 'horizontal'), $page->page);
			$page->page = str_replace('{files_user_vertical}', $file_handler->render_templated_files($user, 'user', 'vertical'), $page->page);
			$page->page = str_replace('{user_files_select}', $file_handler->render_files_select($user, 'user'), $page->page);
			// Handle Caption Only
			$page->page = preg_replace_callback('/{user_field_([^{}]*?)_caption}/', create_function('$matches', 'global $config,$user,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return user::renderSingleListingItem($user, $matches[1],\'caption\');'), $page->page);
			// Hanle VlaueOnly
			$page->page = preg_replace_callback('/{user_field_([^{}]*?)_value}/', create_function('$matches', 'global $config,$user,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return user::renderSingleListingItem($user, $matches[1],\'value\');'), $page->page);
			// Handle Raw Value
			$page->page = preg_replace_callback('/{user_field_([^{}]*?)_rawvalue}/', create_function('$matches', 'global $config,$user,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return user::renderSingleListingItem($user, $matches[1],\'rawvalue\');'), $page->page);
			// Handle Both Caption and Value
			$page->page = preg_replace_callback('/{user_field_([^{}]*?)}/', create_function('$matches', 'global $config,$user,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return user::renderSingleListingItem($user, $matches[1]);'), $page->page);
			// Insert Agent Image
			$sql2 = "SELECT userimages_thumb_file_name FROM " . $config['table_prefix'] . "userimages WHERE userdb_id = $user ORDER BY userimages_rank";
			$recordSet2 = $conn->Execute($sql2);
			if ($recordSet2 === false) {
				$misc->log_error($sql2);
			}
			$num_images = $recordSet2->RecordCount();
			if ($num_images == 0) {
				if ($config['show_no_photo'] == 1) {
					$agent_image = '<img src="images/nophoto.gif" alt="' . $lang['no_photo'] . '" />';
					$raw_agent_image = 'images/nophoto.gif';
				}else {
					$agent_image = '';
					$raw_agent_image = '';
				}
				$page->page = $page->parse_template_section($page->page, 'agent_image_thumb_1', $agent_image);
				$page->page = $page->parse_template_section($page->page, 'raw_agent_image_thumb_1', $raw_agent_image);
			}
			$x = 1;
			while (!$recordSet2->EOF) {
				$thumb_file_name = $misc->make_db_unsafe ($recordSet2->fields['userimages_thumb_file_name']);
				if ($thumb_file_name != "") {
					// gotta grab the image size
					$imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
					$imagewidth = $imagedata[0];
					$imageheight = $imagedata[1];
					$shrinkage = $config['thumbnail_width'] / $imagewidth;
					$displaywidth = $imagewidth * $shrinkage;
					$displayheight = $imageheight * $shrinkage;
					$agent_image = '<img src="' . $config['user_view_images_path'] . '/' . $thumb_file_name . '" height="' . $displayheight . '" width="' . $displaywidth . '" alt="' . $thumb_file_name . '" />';
					$raw_agent_image = $config['user_view_images_path'] . '/' . $thumb_file_name;
				} // end if ($thumb_file_name != "")
				// We have the image so insert it into the section.
				$page->page = $page->parse_template_section($page->page, 'agent_image_thumb_' . $x, $agent_image);
				$page->page = $page->parse_template_section($page->page, 'raw_agent_image_thumb_' . $x, $raw_agent_image);
				$x++;
				$recordSet2->MoveNext();
			} // end while
			$page->page =preg_replace('{agent_image_thumb_(.*?)}', '',  $page->page);
			$page->page =preg_replace('{raw_agent_image_thumb_(.*?)}', '',  $page->page);
			$display = $page->page;
		} else {
			$display = $lang['user_manager_invalid_user_id'];
		}
		}

		return $display;
	}
	public static function userListings($user)
	{
		// produces the rest of the listings for users
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$user = $misc->make_db_extra_safe($user);
		$display .= '<strong>' . $lang['users_other_listings'] . '</strong>';
		$sql = "SELECT listingsdb_id, listingsdb_title FROM " . $config['table_prefix'] . "listingsdb WHERE userdb_id = $user AND listingsdb_active = 'yes'";
		$recordSet = $conn->SelectLimit($sql, 50, 0);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		if ($recordSet->RecordCount() > 0) {
			$display .= '<ul>';
			while (!$recordSet->EOF) {
				$ID = $misc->make_db_unsafe ($recordSet->fields['listingsdb_id']);
				$Title = $misc->make_db_unsafe ($recordSet->fields['listingsdb_title']);
				if ($config['url_style'] == '1') {
					$url = '<a href="index.php?action=listingview&amp;listingID=' . $ID . '">';
				}else {
					$url_title = str_replace("/", "", $Title);
					$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
					$url = '<a href="listing-' . misc::urlencode_to_sef($url_title) . '-' . $ID . '.html">';
				}
				$display .= '<li> '.$url.$Title.'</a></li>';
				$recordSet->MoveNext();
			}
			$display .= '</ul>';
		}

		return $display;
	} // end public static function userListings
	public static function userHitcount($user)
	{
		// hit counter for user listings
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$user = $misc->make_db_safe($user);
		$sql = "UPDATE " . $config['table_prefix'] . "userdb SET userdb_hit_count=userdb_hit_count+1 WHERE userdb_id=$user";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$sql = "SELECT userdb_hit_count FROM " . $config['table_prefix'] . "userdb WHERE userdb_id=$user";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$hitcount = $misc->make_db_unsafe($recordSet->fields['userdb_hit_count']);
			$display .= "$lang[this_user_has_been_viewed] <strong>$hitcount</strong> $lang[times].";
			$recordSet->MoveNext();
		} // end while
		return $display;
	} // end public static function userHitcount
	public static function get_user_name($user)
	{
		// grabs the main info for a given user
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';

		$user = $misc->make_db_extra_safe($user);
		$sql = "SELECT userdb_user_name FROM " . $config['table_prefix'] . "userdb WHERE (userdb_id = $user)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		while (!$recordSet->EOF) {
			$name = $misc->make_db_unsafe ($recordSet->fields['userdb_user_name']);
			$recordSet->MoveNext();
		} // end while
		$display .= $name;
		return $display;
	} // public static function getMainListingData
	//Get User Type
	public static function get_user_type($user)
	{
		// grabs the main info for a given user
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';

		$user = $misc->make_db_extra_safe($user);
		$sql = "SELECT userdb_is_agent,userdb_is_admin FROM " . $config['table_prefix'] . "userdb WHERE (userdb_id = $user)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		while (!$recordSet->EOF) {
			$agent = $misc->make_db_unsafe ($recordSet->fields['userdb_is_agent']);
			$admin = $misc->make_db_unsafe ($recordSet->fields['userdb_is_admin']);
			$recordSet->MoveNext();
		} // end while
		if($admin=='yes'){
			return 'admin';
		}elseif($agent=='yes'){
			return 'agent';
		}
		return 'member';

	}

	public static function get_user_first_name($user)
	{
		// grabs the main info for a given user
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';

		$user = $misc->make_db_extra_safe($user);
		$sql = "SELECT userdb_user_first_name FROM " . $config['table_prefix'] . "userdb WHERE (userdb_id = $user)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		while (!$recordSet->EOF) {
			$name = $misc->make_db_unsafe ($recordSet->fields['userdb_user_first_name']);
			$recordSet->MoveNext();
		} // end while
		$display .= $name;
		return $display;
	} // public static function getMainListingData
	public static function get_user_last_name($user)
	{
		// grabs the main info for a given user
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';

		$user = $misc->make_db_extra_safe($user);
		$sql = "SELECT userdb_user_last_name FROM " . $config['table_prefix'] . "userdb WHERE (userdb_id = $user)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		while (!$recordSet->EOF) {
			$name = $misc->make_db_unsafe ($recordSet->fields['userdb_user_last_name']);
			$recordSet->MoveNext();
		} // end while
		$display .= $name;
		return $display;
	} // public static function getMainListingData
	public static function contact_agent_link($userID)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$userID = $misc->make_db_unsafe($userID);
		$display = '<a href="index.php?action=contact_agent&amp;popup=yes&amp;agent_id=' . $userID . '" onclick="window.open(this.href,\'_blank\',\'location=0,status=0,scrollbars=1,toolbar=0,menubar=0,width=500,height=520\');return false">' . $lang['contact_agent'] . '</a>';
		return $display;
	}
	public static function renderUserInfo($user)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$formDB = user::determine_user_formtype($user);
		$user = $misc->make_db_safe($user);
		///agentformelements_id, agentformelements_field_type, agentformelements_field_name, agentformelements_field_caption, agentformelements_default_text, agentformelements_field_elements, agentformelements_rank, agentformelements_required, agentformelements_display_priv
		$priv_sql = '';
		if($formDB == 'agentformelements'){
			//Check Users Permissions.
			$display_agent = login::loginCheck('Agent', true);
			$display_member = login::loginCheck('Member', true);
			if($display_agent==TRUE){
				$priv_sql = 'AND '.$formDB.'_display_priv <= 2 ';
			}elseif($display_member==TRUE){
				$priv_sql = 'AND '.$formDB.'_display_priv <= 1 ';
			}else{
				$priv_sql = 'AND '.$formDB.'_display_priv = 0 ';
			}
		}
		$sql = 'SELECT userdbelements_field_value, ' . $formDB . '_field_type, ' . $formDB . '_field_caption FROM ' . $config['table_prefix'] . 'userdbelements, ' . $config['table_prefix'] . $formDB . ' WHERE ((userdb_id = ' . $user . ') AND (userdbelements_field_name = ' . $formDB . '_field_name)) '.$priv_sql.' ORDER BY ' . $formDB . '_rank ASC';
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$field_value = $misc->make_db_unsafe ($recordSet->fields['userdbelements_field_value']);
			$field_type = $misc->make_db_unsafe ($recordSet->fields[$formDB . '_field_type']);
			$field_caption = $misc->make_db_unsafe ($recordSet->fields[$formDB . '_field_caption']);
			if ($field_value != "") {
				if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox") {
					// handle field types with multiple options
					$display .= "<strong>$field_caption</strong><br />";
					$feature_index_list = explode("||", $field_value);
					foreach ($feature_index_list as $feature_list_item) {
							$display .= $feature_list_item;
							$display .= $config['feature_list_separator'];
					} // end while
				} // end if field type is a multiple typ
				elseif ($field_type == "price") {
					$money_amount = $misc->international_num_format($field_value);
					$display .= "<br /><strong>$field_caption</strong>: " . money_formats($money_amount);
				} // end elseif
				elseif ($field_type == "number") {
					$display .= "<br /><strong>$field_caption</strong>: " . $misc->international_num_format($field_value);
				} // end elseif
				elseif ($field_type == "url") {
					$display .= "<br /><strong>$field_caption</strong>: <a href=\"$field_value\" onclick=\"window.open(this.href,'_blank','location=1,resizable=1,status=1,scrollbars=1,toolbar=1,menubar=1');return false\">$field_value</a>";
				}elseif ($field_type == "email") {
					$display .= "<br /><strong>$field_caption</strong>: <a href=\"mailto:$field_value\">$field_value</a>";
				}elseif ($field_type == "date") {
						if ($config['date_format']==1) {
							$format="m/d/Y";
						}
						elseif ($config['date_format']==2) {
							$format="Y/d/m";
						}
						elseif ($config['date_format']==3) {
							$format="d/m/Y";
						}
						$field_value=date($format,"$field_value");
						$display .= "<br /><strong>$field_caption</strong>: $field_value";
						}else {
					if ($config['add_linefeeds'] === "1") {
						$field_value = nl2br($field_value); //replace returns with <br />
					} // end if
					$display .= "<br /><strong>$field_caption</strong>: $field_value";
				} // end else
			} // end if ($field_value != "")
			$recordSet->MoveNext();
		} // end while
		return $display;
	} // end renderUserInfo
	public static function determine_user_formtype($userID)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$userID = $misc->make_db_extra_safe($userID);
		$sql = "SELECT userdb_is_agent, userdb_is_admin FROM " . $config['table_prefix'] . "userdb where userdb_id = $userID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$edit_isAgent = $misc->make_db_unsafe ($recordSet->fields['userdb_is_agent']);
		$edit_isAdmin = $misc->make_db_unsafe ($recordSet->fields['userdb_is_admin']);
		if ($edit_isAgent == "yes" || $edit_isAdmin == "yes") {
			$formDB = 'agentformelements';
		}else {
			$formDB = 'memberformelements';
		}
		return $formDB;
	}
	public static function renderSingleListingItem($userID, $name, $display_type = 'both')
	{
		// Display_type - Sets what should be returned.
		// both - Displays both the caption and the formated value
		// value - Displays just the formated value
		// rawvalue - Displays just the raw value
		// caption - Displays only the captions
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$formDB = user::determine_user_formtype($userID);
		$userID = $misc->make_db_safe($userID);
		$name = $misc->make_db_safe($name);

		$sql = "SELECT userdbelements_field_value, " . $formDB . "_id, " . $formDB . "_field_type,
			" . $formDB . "_field_caption FROM " . $config['table_prefix'] . "userdbelements, " . $config['table_prefix'] . $formDB . " WHERE ((userdb_id = $userID) AND
			(" . $formDB . "_field_name = userdbelements_field_name) AND (userdbelements_field_name = $name))";

		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$field_value = $misc->make_db_unsafe ($recordSet->fields['userdbelements_field_value']);
			$field_type = $misc->make_db_unsafe ($recordSet->fields[$formDB . '_field_type']);
			$form_elements_id = $misc->make_db_unsafe ($recordSet->fields[$formDB . '_id']);
			if (!isset($_SESSION["users_lang"])) {
				// Hold empty string for translation fields, as we are workgin with teh default lang
				$field_caption = $misc->make_db_unsafe ($recordSet->fields[$formDB . '_field_caption']);
			}else {
				$lang_sql = "SELECT " . $formDB . "_field_caption FROM " . $config['lang_table_prefix'] . $formDB . " WHERE " . $formDB . "_id = $form_elements_id";
				$lang_recordSet = $conn->Execute($lang_sql);
				if ($lang_recordSet === false) {
					$misc->log_error($lang_sql);
				}
				$field_caption = $misc->make_db_unsafe ($lang_recordSet->fields[$formDB . '_field_caption']);
			}

			if ($field_value != "") {
				if ($display_type === 'both' || $display_type === 'caption') {
					$display .= '<span class="field_caption">' . $field_caption . '</span>';
				}
				if ($display_type == 'both') {
					$display .= ':&nbsp;';
				}
				if ($display_type === 'both' || $display_type === 'value') {
					if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox") {
						// handle field types with multiple options
						// $display .= "<br /><b>$field_caption</b>";
						$feature_index_list = explode("||", $field_value);
						sort($feature_index_list);
						foreach($feature_index_list as $feature_list_item) {
							$display .= "<br />$feature_list_item";
						} // end while
					} // end if field type is a multiple type
					elseif ($field_type == "price") {
						$money_amount = $misc->international_num_format($field_value, $config['number_decimals_price_fields']);
						$display .= $misc->money_formats($money_amount);
					} // end elseif
					elseif ($field_type == "number") {
						$display .= $misc->international_num_format($field_value, $config['number_decimals_number_fields']);
					} // end elseif
					elseif ($field_type == "url") {
						$display .= "<a href=\"$field_value\" onclick=\"window.open(this.href,'_blank','location=1,resizable=1,status=1,scrollbars=1,toolbar=1,menubar=1');return false\">$field_value</a>";
					}elseif ($field_type == "email") {
						$display .= "<a href=\"mailto:$field_value\">$field_value</a>";
					}elseif ($field_type == "text" OR $field_type == "textarea") {
						if ($config['add_linefeeds'] === "1") {
							$field_value = nl2br($field_value); //replace returns with <br />
						} // end if
						$display .= $field_value;
					}elseif ($field_type == "date") {
						if ($config['date_format']==1) {
							$format="m/d/Y";
						}
						elseif ($config['date_format']==2) {
							$format="Y/d/m";
						}
						elseif ($config['date_format']==3) {
							$format="d/m/Y";
						}
						$field_value=date($format,"$field_value");
						$display .= $field_value;
						} else {
						$display .= $field_value;
					} // end else
				}
				if ($display_type === 'rawvalue') {
					$display .= $field_value;
				}
			} // end if ($field_value != "")
			$recordSet->MoveNext();
		} // end while
		return $display;
	} // end renderSingleListingItem
	public static function vcard_agent_link($user)
	{
		global $lang;
		$display = '';
		$display.='<a href="index.php?action=create_vcard&amp;user='.$user.'">'.$lang['vcard_link_text'].'</a>';
		return $display;
	}// end vcard_agent_link
	public static function create_vcard($user)
	{
		global $config, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/class/vcard/vcard.inc.php');
		$v = new vCard();
		$first=$this->get_user_first_name($user);
		$last=$this->get_user_last_name($user);
		$v->setName($last, $first);
		$sql='SELECT userdb_emailaddress FROM ' . $config['lang_table_prefix'] . 'userdb WHERE userdb_id=' . $user;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$email=$recordSet->fields['userdb_emailaddress'];
		$v->setEmail($email);
		$sql=$sql = "SELECT userdbelements_field_name,userdbelements_field_value FROM " . $config['lang_table_prefix'] . "userdbelements WHERE userdb_id=" . $user;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
		if ($recordSet->fields['userdbelements_field_name'] == $config['vcard_phone'])
		{
		$phone = $misc->make_db_unsafe($recordSet->fields['userdbelements_field_value']);
		$v->setPhoneNumber($phone, "HOME;VOICE");
		}
		elseif ($recordSet->fields['userdbelements_field_name'] == $config['vcard_fax'])
		{
		$fax = $misc->make_db_unsafe($recordSet->fields['userdbelements_field_value']);
		$v->setPhoneNumber($fax, "HOME;FAX");
		}
		elseif ($recordSet->fields['userdbelements_field_name'] == $config['vcard_mobile'])
		{
		$mobile = $misc->make_db_unsafe($recordSet->fields['userdbelements_field_value']);
		$v->setPhoneNumber($mobile, "HOME;CELL");
		}
		elseif ($recordSet->fields['userdbelements_field_name'] == $config['vcard_notes'])
		{
		$notes = $misc->make_db_unsafe($recordSet->fields['userdbelements_field_value']);
		$v->setNote($notes);
		}
		elseif ($recordSet->fields['userdbelements_field_name'] == $config['vcard_url'])
		{
		$url = $misc->make_db_unsafe($recordSet->fields['userdbelements_field_value']);
		$v->setURL($url,"HOME");
		}
		elseif ($recordSet->fields['userdbelements_field_name'] == $config['vcard_address'])
		{
		$address = $misc->make_db_unsafe($recordSet->fields['userdbelements_field_value']);
		}
		elseif ($recordSet->fields['userdbelements_field_name'] == $config['vcard_city'])
		{
		$city = $misc->make_db_unsafe($recordSet->fields['userdbelements_field_value']);
		}
		elseif ($recordSet->fields['userdbelements_field_name'] == $config['vcard_state'])
		{
		$state = $misc->make_db_unsafe($recordSet->fields['userdbelements_field_value']);
		}
		elseif ($recordSet->fields['userdbelements_field_name'] == $config['vcard_zip'])
		{
		$zip = $misc->make_db_unsafe($recordSet->fields['userdbelements_field_value']);
		}
		elseif ($recordSet->fields['userdbelements_field_name'] == $config['vcard_country'])
		{
		$country = $misc->make_db_unsafe($recordSet->fields['userdbelements_field_value']);
		}
		$v->setAddress("", "", $address, $city, $state, $zip, $country, "HOME;POSTAL");
		$recordSet->MoveNext();
		}
		$output = $v->getVCard();
		echo $output;
		$filename = $v->getFileName();
		Header("Content-Disposition: attachment; filename=$filename");
		Header("Content-Length: ".strlen($output));
		Header("Connection: close");
		Header("Content-Type: text/x-vCard; name=$filename");
	}// end create_vcard
	public static function userListingsLink($user)
	{
		global $lang;
		$display = '';
		$display.='<a href="index.php?action=searchresults&amp;user_ID='.$user.'">'.$lang['user_listings_link_text'].'</a>';
		return $display;
	}// end vcard_agent_link
}

?>