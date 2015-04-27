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
class listing_pages {
	public static function renderSingleListingItem($listingID, $name, $display_type = 'both')
	{
		// Display_type - Sets what should be returned.
		// both - Displays both the caption and the formated value
		// value - Displays just the formated value
		// rawvalue - Displays just the raw value
		// caption - Displays only the captions
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$listingID = $misc->make_db_extra_safe($listingID);
		$name = $misc->make_db_extra_safe($name);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_id, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((listingsdb_id = $listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = $name))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$field_value = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
			$field_type = $misc->make_db_unsafe ($recordSet->fields['listingsformelements_field_type']);
			$form_elements_id = $misc->make_db_unsafe ($recordSet->fields['listingsformelements_id']);
			if (!isset($_SESSION["users_lang"])) {
				// Hold empty string for translation fields, as we are workgin with teh default lang
				$field_caption = $misc->make_db_unsafe ($recordSet->fields['listingsformelements_field_caption']);
			}else {
				$lang_sql = "SELECT listingsformelements_field_caption FROM " . $config['lang_table_prefix'] . "listingsformelements WHERE listingsformelements_id = $form_elements_id";
				$lang_recordSet = $conn->Execute($lang_sql);
				if ($lang_recordSet === false) {
					$misc->log_error($lang_sql);
				}
				$field_caption = $misc->make_db_unsafe ($lang_recordSet->fields['listingsformelements_field_caption']);
			}
			if ($field_type == 'divider') {
				$display .= "<br /><strong>$field_caption</strong>";
			} elseif ($field_value != "") {
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
						$list_count = count($feature_index_list);
						$l = 1;
						foreach($feature_index_list as $feature_list_item) {
							if ($l < $list_count) {
							$display .= $feature_list_item;
							$display .= $config['feature_list_separator'];
							$l++;
							} else {
							$display .= $feature_list_item;
							}
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
						$display .= "<br />$field_value";
					} else {
						$display .= $field_value;
					} // end else
				}
				if ($display_type === 'rawvalue') {
					$display .= $field_value;
				}
			} else {
				if ($field_type == "price" && $display_type !== 'rawvalue' && $config["zero_price"] == "1") {
						$display .= $lang['call_for_price'] . '<br />';
					} // end if
			} // end else
			$recordSet->MoveNext();
		} // end while
		return $display;
	} // end renderSingleListingItem
	public static function renderTemplateArea($templateArea, $listingID)
	{
		// renders all the elements in a given template area on the listing pages
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/login.inc.php');
		$listingID = $misc->make_db_extra_safe($listingID);
		$templateArea = $misc->make_db_extra_safe($templateArea);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_id, listingsformelements_field_type, listingsformelements_field_caption,listingsformelements_display_priv FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsformelements_location = $templateArea)) ORDER BY listingsformelements_rank ASC";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$display = '';
		while (!$recordSet->EOF) {
			$form_elements_id = $recordSet->fields['listingsformelements_id'];
			$field_type = $misc->make_db_unsafe ($recordSet->fields['listingsformelements_field_type']);
			$display_priv = $misc->make_db_unsafe($recordSet->fields['listingsformelements_display_priv']);
			$field_value = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
			if (!isset($_SESSION["users_lang"])) {
				// Hold empty string for translation fields, as we are workgin with teh default lang
				$field_caption = $misc->make_db_unsafe ($recordSet->fields['listingsformelements_field_caption']);
			}else {
				$lang_sql = "SELECT listingsformelements_field_caption FROM " . $config['lang_table_prefix'] . "listingsformelements WHERE listingsformelements_id = $form_elements_id";
				$lang_recordSet = $conn->Execute($lang_sql);
				if ($lang_recordSet === false) {
					$misc->log_error($lang_sql);
				}
				$field_caption = $misc->make_db_unsafe ($lang_recordSet->fields['listingsformelements_field_caption']);
			}
			$display_status = false;
			if ($display_priv == 1) {
				$display_status = login::loginCheck('Member', true);
			}elseif ($display_priv == 2) {
				$display_status = login::loginCheck('Agent', true);
			}elseif ($display_priv == 3) {
				$display_status = login::loginCheck('Admin', true);
			}else {
				$display_status = true;
			}
			if ($display_status === true) {
				if ($field_type == 'divider') {
				$display .= "<br /><strong>$field_caption</strong>";
				}elseif ($field_value != "") {
					if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox") {
						// handle field types with multiple options
						//$display .= "<br /><strong>$field_caption</strong><br />";
						$display .= '<div class="multiple_options_caption">' . $field_caption . '</div>';
						$feature_index_list = explode("||", $field_value);
						sort($feature_index_list);
						$list_count = count($feature_index_list);
						$l = 1;
						$display .= '<div class="multiple_options">';
						$display .= '<ul>';
							foreach($feature_index_list as $feature_list_item) {
								if ($l < $list_count) {
									$display .= '<li>';
									$display .= $feature_list_item;
									$display .= $config['feature_list_separator'];
									$display .= '</li>';
									$l++;
								} else {
									$display .= '<li>';
									$display .= $feature_list_item;
									$display .= '</li>';
								}
							} // end while
						$display .= '</ul>';
						$display .= '</div>';
					} // end if field type is a multiple type
					elseif ($field_type == "price") {
						$money_amount = $misc->international_num_format($field_value, $config['number_decimals_price_fields']);
						$display .= "<strong>$field_caption</strong>: " . $misc->money_formats($money_amount);
					} // end elseif
					elseif ($field_type == "number") {
						$display .= "<strong>$field_caption</strong>: " . $misc->international_num_format($field_value, $config['number_decimals_number_fields']);
					} // end elseif
					elseif ($field_type == "url") {
						$display .= "<strong>$field_caption</strong>: <a href=\"$field_value\" onclick=\"window.open(this.href,'_blank','location=1,resizable=1,status=1,scrollbars=1,toolbar=1,menubar=1');return false\">$field_value</a>";
					}elseif ($field_type == "email") {
						$display .= "<strong>$field_caption</strong>: <a href=\"mailto:$field_value\">$field_value</a>";
					}elseif ($field_type == "text" OR $field_type == "textarea") {
						if ($config['add_linefeeds'] === "1") {
							$field_value = nl2br($field_value); //replace returns with <br />
						} // end if
						$display .= "<strong>$field_caption</strong>: $field_value";
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
						$display .= "<strong>$field_caption</strong>: $field_value";
					} else {
						$display .= "<strong>$field_caption</strong>: $field_value";
					} // end else
					$display .= '<br />';
				} else {
				if ($field_type == "price" && $config["zero_price"] == "1") {
						$display .= "<strong>$field_caption</strong>: " . $lang['call_for_price'] . "<br />";
					} // end if
			} // end else
			}
			$recordSet->MoveNext();
		} // end while
		return $display;
	} // end renderTemplateArea
	public static function renderTemplateAreaNoCaption($templateArea, $listingID)
	{
		// renders all the elements in a given template area on the listing pages
		// this time without the corresponding captions
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/login.inc.php');
		$listingID = $misc->make_db_extra_safe($listingID);
		$templateArea = $misc->make_db_extra_safe($templateArea);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption, listingsformelements_display_priv FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsformelements_location = $templateArea)) ORDER BY listingsformelements_rank ASC";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$display = '';
		while (!$recordSet->EOF) {
			$field_value = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
			$field_type = $misc->make_db_unsafe ($recordSet->fields['listingsformelements_field_type']);
			$field_caption = $misc->make_db_unsafe ($recordSet->fields['listingsformelements_field_caption']);
			$display_priv = $misc->make_db_unsafe($recordSet->fields['listingsformelements_display_priv']);
			$display_status = false;
			if ($display_priv == 1) {
				$display_status = login::loginCheck('Member', true);
			}elseif ($display_priv == 2) {
				$display_status = login::loginCheck('Agent', true);
			}elseif ($display_priv == 3) {
				$display_status = login::loginCheck('Admin', true);
			}else {
				$display_status = true;
			}
			if ($display_status === true) {
				if ($field_value != "") {
					if ($field_type == "select-multiple" OR $field_type == "option" OR $field_type == "checkbox") {
						// handle field types with multiple options
						$feature_index_list = explode("||", $field_value);
						sort($feature_index_list);
						$list_count = count($feature_index_list);
						$l = 1;
						foreach($feature_index_list as $feature_list_item) {
							if ($l < $list_count) {
							$display .= $feature_list_item;
							$display .= $config['feature_list_separator'];
							$l++;
							} else {
							$display .= $feature_list_item;
							}
						} // end while
					} // end if field type is a multiple type
					elseif ($field_type == "price") {
						$money_amount = $misc->international_num_format($field_value, $config['number_decimals_price_fields']);
						$display .= "<strong>$field_caption</strong>: " . $misc->money_formats($money_amount);
					} // end elseif
					elseif ($field_type == "number") {
						$display .= "<strong>$field_caption</strong>: " . $misc->international_num_format($field_value, $config['number_decimals_number_fields']);
					} // end elseif
					elseif ($field_type == "url") {
						$display .= "<a href=\"$field_value\" onclick=\"window.open(this.href,'_blank','location=1,resizable=1,status=1,scrollbars=1,toolbar=1,menubar=1');return false\">$field_value</a>";
					}elseif ($field_type == "email") {
						$display .= "<a href=\"mailto:$field_value\">$field_value</a>";
					}elseif ($field_type == "text" OR $field_type == "textarea") {
						if ($config['add_linefeeds'] === "1") {
							$field_value = nl2br($field_value); //replace returns with <br />
						} // end if
						$display .= "$field_value";
					}elseif ($field_type == "Date") {
						if ($config['date_format']==1) {
							$format="m/d/Y";
						}
						elseif ($config['date_format']==2) {
							$format="Y/d/m";
						}
						elseif ($config['date_format']==3) {
							$format="d/m/Y";
						}
						$field_value=date($format,$field_value);
						$display .= "$field_value";
					}else {
						$display .= "$field_value";
					} // end else
					$display .= '<br />';
				} else {
				if ($field_type == "price" && $config["zero_price"] == "1") {
						$display .= $lang['call_for_price']. '<br />';
					} // end if
			} // end else
			}
			$recordSet->MoveNext();
		} // end while
		return $display;
	} // end renderTemplateAreaNoCaption
	public static function get_pclass($listing_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$listing_id = $misc->make_db_extra_safe($listing_id);
		$sql = "SELECT class_id FROM " . $config['table_prefix_no_lang'] . "classlistingsdb WHERE listingsdb_id = $listing_id";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$pclass_list = '';
		while (!$recordSet->EOF()) {
			if ($pclass_list == '') {
				$pclass_list .= $recordSet->fields['class_id'];
			}else {
				$pclass_list .= ',' . $recordSet->fields['class_id'];
			}
			$recordSet->MoveNext();
		}
		if ($pclass_list == '') {
			$pclass_list .= 0;
		}
		$sql = "SELECT class_name FROM " . $config['table_prefix'] . "class WHERE class_id IN ($pclass_list)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$pclass_name_list = '';
		while (!$recordSet->EOF()) {
			if ($pclass_name_list == '') {
				$pclass_name_list .= $recordSet->fields['class_name'];
			}else {
				$pclass_name_list .= ',' . $recordSet->fields['class_name'];
			}
			$recordSet->MoveNext();
		}
		return $pclass_name_list;
	}
	public static function get_creation_date($listing_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$listing_id = $misc->make_db_extra_safe($listing_id);
		$sql = "SELECT listingsdb_creation_date FROM " . $config['table_prefix'] . "listingsdb WHERE listingsdb_id = $listing_id";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$date = $recordSet->UserTimeStamp($recordSet->fields['listingsdb_creation_date'], $config["date_format_timestamp"]);
		return $date;
	}
	public static function get_modified_date($listing_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$listing_id = $misc->make_db_extra_safe($listing_id);
		$sql = "SELECT listingsdb_last_modified FROM " . $config['table_prefix'] . "listingsdb WHERE listingsdb_id = $listing_id";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$date = $recordSet->UserTimeStamp($recordSet->fields['listingsdb_last_modified'], $config["date_format_timestamp"]);
		return $date;
	}
	public static function get_title($listing_id)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$listing_id = $misc->make_db_extra_safe($listing_id);
		$sql = "SELECT listingsdb_title FROM " . $config['table_prefix'] . "listingsdb WHERE listingsdb_id = $listing_id";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$title = $misc->make_db_unsafe($recordSet->fields['listingsdb_title']);
		return $title;
	}
	public static function getListingAgentThumbnail($listing_id)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// get the main data for a given listing
		$listingID = $misc->make_db_extra_safe($listing_id);
		$sql = "SELECT userimages_thumb_file_name, userimages_caption FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "userimages WHERE ((listingsdb_id = $listingID) AND (" . $config['table_prefix'] . "userimages.userdb_id = " . $config['table_prefix'] . "listingsdb.userdb_id)) ORDER BY userimages_rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		$listing_user_thumnail = array();
		while (!$recordSet->EOF) {
			$image_name = $misc->make_db_unsafe ($recordSet->fields['userimages_thumb_file_name']);
			$caption = $misc->make_db_unsafe ($recordSet->fields['userimages_caption']);
			$listing_user_thumnail[] = '<img src="' . $config['user_view_images_path'] . '/' . $image_name . '" alt="' . $caption . '" />';
			$recordSet->MoveNext();
		} // end while
		return $listing_user_thumnail;
	}
	public static function getListingAgentLink($listingID)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// get the main data for a given listing
		$rawID=$listingID;
		$listingID = $misc->make_db_extra_safe($listingID);
		$sql = "SELECT " . $config['table_prefix'] . "listingsdb.userdb_id FROM $config[table_prefix]listingsdb  WHERE listingsdb_id = $listingID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		$display = '';
		while (!$recordSet->EOF) {
			$listing_user_ID = $misc->make_db_unsafe ($recordSet->fields['userdb_id']);
			$recordSet->MoveNext();
		} // end while
		if ($config['url_style'] == '1') {
			$display .= $config['baseurl'].'/index.php?action=view_user&amp;user='.$listing_user_ID;
		}else {
			$Title = listing_pages::getListingAgentFirstName($rawID).' '.listing_pages::getListingAgentLastName($rawID);
			$url_title = str_replace("/", "", $Title);
			$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
			$display .= $config['baseurl'].'/agent-' . urlencode($url_title) . '-' . $listing_user_ID . '.html';
		}
		return $display;
	}
	public static function getListingAgent($listingID)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// get the main data for a given listing
		$listingID = $misc->make_db_extra_safe($listingID);
		$sql = "SELECT userdb_user_name FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "userdb WHERE ((listingsdb_id = $listingID) AND (" . $config['table_prefix'] . "userdb.userdb_id = " . $config['table_prefix'] . "listingsdb.userdb_id))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		$display = '';
		while (!$recordSet->EOF) {
			$listing_user_name = $misc->make_db_unsafe ($recordSet->fields['userdb_user_name']);
			$recordSet->MoveNext();
		} // end while
		$display .= $listing_user_name;
		return $display;
	}
	public static function getListingAgentAdminStatus($listingID)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// get the main data for a given listing
		$listingID = $misc->make_db_extra_safe($listingID);
		$sql = "SELECT userdb_is_admin, userdb_is_agent FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "userdb WHERE ((listingsdb_id = $listingID) AND (" . $config['table_prefix'] . "userdb.userdb_id = " . $config['table_prefix'] . "listingsdb.userdb_id))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		while (!$recordSet->EOF) {
			$is_admin = $misc->make_db_unsafe ($recordSet->fields['userdb_is_admin']);
			$is_agent = $misc->make_db_unsafe ($recordSet->fields['userdb_is_agent']);
			$recordSet->MoveNext();
		} // end while
		if ($is_admin == 'yes' && $is_agent == 'no') {
			return true;
		}else {
			return false;
		}
	}
	public static function getListingAgentFirstName($listingID)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// get the main data for a given listing
		$listingID = $misc->make_db_extra_safe($listingID);
		$sql = "SELECT userdb_user_first_name FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "userdb WHERE ((listingsdb_id = $listingID) AND (" . $config['table_prefix'] . "userdb.userdb_id = " . $config['table_prefix'] . "listingsdb.userdb_id))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		$display = '';
		while (!$recordSet->EOF) {
			$listing_user_name = $misc->make_db_unsafe ($recordSet->fields['userdb_user_first_name']);
			$recordSet->MoveNext();
		} // end while
		$display .= $listing_user_name;
		return $display;
	}
	public static function getListingAgentLastName($listingID)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// get the main data for a given listing
		$listingID = $misc->make_db_extra_safe($listingID);
		$sql = "SELECT userdb_user_last_name FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "userdb WHERE ((listingsdb_id = $listingID) AND (" . $config['table_prefix'] . "userdb.userdb_id = " . $config['table_prefix'] . "listingsdb.userdb_id))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		$display = '';
		while (!$recordSet->EOF) {
			$listing_user_name = $misc->make_db_unsafe ($recordSet->fields['userdb_user_last_name']);
			$recordSet->MoveNext();
		} // end while
		$display .= $listing_user_name;
		return $display;
	}
	public static function getAgentListingsLink($listing_id)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// get the main data for a given listing
		$listingID = $misc->make_db_extra_safe($listing_id);
		$sql = "SELECT " . $config['table_prefix'] . "listingsdb.userdb_id FROM $config[table_prefix]listingsdb  WHERE listingsdb_id = $listingID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		$display = '';
		while (!$recordSet->EOF) {
			$listing_user_ID = $misc->make_db_unsafe ($recordSet->fields['userdb_id']);
			$recordSet->MoveNext();
		} // end while
		$display .= '<a href="'.$config['baseurl'].'/index.php?action=searchresults&amp;user_ID='.$listing_user_ID.'">'.$lang['user_listings_link_text'].'</a>';
		return $display;
	}
	public static function getListingAgentID($listing_id)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// get the main data for a given listing
		$listingID = $misc->make_db_extra_safe($listing_id);
		$sql = "SELECT " . $config['table_prefix'] . "listingsdb.userdb_id FROM $config[table_prefix]listingsdb  WHERE listingsdb_id = $listingID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// get main listings data
		$display = '';
		while (!$recordSet->EOF) {
			$listing_user_ID = $misc->make_db_unsafe ($recordSet->fields['userdb_id']);
			$recordSet->MoveNext();
		} // end while
		$display .= $listing_user_ID;
		return $display;
	}
	function renderFeaturedListingsVertical($num_of_listings = 0,$random=FALSE,$pclass='', $latest=FALSE)
	{
		return $this->renderFeaturedListings($num_of_listings, 'vertical', $random, $pclass, $latest);
	}
	function renderFeaturedListingsHorizontal($num_of_listings = 0, $random=FALSE, $pclass='', $latest=FALSE)
	{
		return $this->renderFeaturedListings($num_of_listings, 'horizontal', $random, $pclass, $latest);
	}
	function renderFeaturedListings($num_of_listings = 0, $template_name = '',$random=FALSE, $pclass='', $latest=FALSE)
	{
		global $conn, $lang, $config, $db_type, $current_ID;
		//Load the Core Template class and the Misc Class
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$page = new page_user();
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		//Declare an empty display variable to hold all output from function.
		$display = '';
		//If We have a $current_ID save it
		$old_current_ID='';
		if ($current_ID != ''){
			$old_current_ID = $current_ID;
		}
		//Get the number of listing to display by default, unless user specified an override in the template file.
		if ($num_of_listings == 0) {
			$num_of_listings = $config['num_featured_listings'];
		}
		//Load a Random set of featured listings
		if ($db_type == 'mysql') {
			$rand = 'RAND()';
		}else {
			$rand = 'RANDOM()';
		}
		if ($latest == TRUE) {
			$rand = 'listingsdb_id DESC';
		}
		if(($random == TRUE) || ($latest == TRUE)){
			$sql_rand = '';
		}else{
			$sql_rand = "(listingsdb_featured = 'yes') AND";
		}
		if ($config['use_expiration'] === "1") {
			if ($pclass != '') {
			$sql = "SELECT " . $config['table_prefix'] . "listingsdb.listingsdb_id, listingsdb_title FROM " . $config['table_prefix'] . "listingsdb," . $config['table_prefix_no_lang'] . "classlistingsdb WHERe $sql_rand (listingsdb_active = 'yes') AND (listingsdb_expiration > " . $conn->DBDate(time()) . ") AND (" . $config['table_prefix'] . "listingsdb.listingsdb_id = " . $config['table_prefix_no_lang'] . "classlistingsdb.listingsdb_id) AND class_id = " . $pclass . " ORDER BY $rand";
			} else {
			$sql = "SELECT " . $config['table_prefix'] . "listingsdb.listingsdb_id, listingsdb_title FROM " . $config['table_prefix'] . "listingsdb WHERE $sql_rand (listingsdb_active = 'yes') AND (listingsdb_expiration > " . $conn->DBDate(time()) . ") ORDER BY $rand";
			}
		}else {
			if ($pclass != '') {
			$sql = "SELECT " . $config['table_prefix'] . "listingsdb.listingsdb_id, listingsdb_title FROM " . $config['table_prefix'] . "listingsdb," . $config['table_prefix_no_lang'] . "classlistingsdb WHERE $sql_rand (listingsdb_active = 'yes') AND (" . $config['table_prefix'] . "listingsdb.listingsdb_id = " . $config['table_prefix_no_lang'] . "classlistingsdb.listingsdb_id) AND class_id = " . $pclass . " ORDER BY $rand";
			} else {
			$sql = "SELECT " . $config['table_prefix'] . "listingsdb.listingsdb_id, listingsdb_title FROM " . $config['table_prefix'] . "listingsdb WHERE $sql_rand (listingsdb_active = 'yes') ORDER BY $rand";
			}
		}
		$recordSet = $conn->SelectLimit($sql, $num_of_listings, 0);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		//Find out how many listing were returned
		$returned_num_listings = $recordSet->RecordCount();
		if ($returned_num_listings >= 1) {
//Load the Featured Listing Template specified in the Site Config unless a template was specified in the calling template tag.
			if ($template_name == '') {
				$page->load_page($config['template_path'] . '/' . $config['featured_listing_template']);
			}else {
				if ($random == TRUE) {
					$page->load_page($config['template_path'] . '/random_listing_' . $template_name . '.html');
				} elseif ($latest == TRUE) {
					$page->load_page($config['template_path'] . '/latest_listing_' . $template_name . '.html');
				}else {
					$page->load_page($config['template_path'] . '/featured_listing_' . $template_name . '.html');
				}
			}
// Determine if the template uses rows.
// First item in array is the row conent second item is the number of block per block row
			$featured_template_row = $page->get_template_section_row('featured_listing_block_row');
			if (is_array($featured_template_row)) {
				$row = $featured_template_row[0];
				$col_count = $featured_template_row[1];
				$user_rows = true;
				$x = 1;
//Create an empty array to hold the row conents
				$new_row_data = array();
			}else {
				$user_rows = false;
			}
			$featured_template_section = '';
			while (!$recordSet->EOF) {
				if ($user_rows == true && $x > $col_count) {
//We are at then end of a row. Save the template section as a new row.
					$new_row_data[] = $page->replace_template_section('featured_listing_block', $featured_template_section,$row);
//$new_row_data[] = $featured_template_section;
					$featured_template_section = $page->get_template_section('featured_listing_block');
					$x=1;
				}else {
					$featured_template_section .= $page->get_template_section('featured_listing_block');
				}
				$listing_title = $misc->make_db_unsafe ($recordSet->fields['listingsdb_title']);
				$current_ID = $misc->make_db_unsafe ($recordSet->fields['listingsdb_id']);
				if ($config['url_style'] == '1') {
					$featured_url = 'index.php?action=listingview&amp;listingID=' . $current_ID;
				}else {
					$url_title = str_replace("/", "", $listing_title);
					$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
					$featured_url = 'listing-' . misc::urlencode_to_sef($url_title) . '-' . $current_ID . '.html';
				}
				$featured_template_section = $page->replace_listing_field_tags($current_ID, $featured_template_section);
				$featured_template_section = $page->replace_listing_field_tags($current_ID, $featured_template_section);
				$featured_template_section = $page->parse_template_section($featured_template_section, 'featured_url', $featured_url);
				$featured_template_section = $page->parse_template_section($featured_template_section, 'listingid', $current_ID);
// Start {isfavorite} featured template section tag
                if (isset($_SESSION['userID'])) {
                    $userID = $misc->make_db_safe($_SESSION['userID']);
                    $sql1 = "SELECT listingsdb_id FROM " . $config['table_prefix'] . "userfavoritelistings WHERE ((listingsdb_id = $current_ID) AND (userdb_id=$userID))";
                    $recordSet1 = $conn->Execute($sql1);
                    if ($recordSet1 === false) {
                        $misc->log_error($sql1);
                    }
                    $favorite_listingsdb_id = $misc->make_db_unsafe ($recordSet1->fields['listingsdb_id']);
                        if ($favorite_listingsdb_id !== $current_ID) {
                            $isfavorite = "no";
                            $featured_template_section = $page->parse_template_section($featured_template_section, 'isfavorite', $isfavorite);
                        } else {
                            $isfavorite = "yes";
                            $featured_template_section = $page->parse_template_section($featured_template_section, 'isfavorite', $isfavorite);
                        }
                }
// End {isfavorite} featured template section tag  
// Setup Image Tags
				$sql2 = "SELECT listingsimages_thumb_file_name,listingsimages_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsdb_id = $current_ID) ORDER BY listingsimages_rank";
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
						$featured_thumb_width = $imagewidth * $shrinkage;
						$featured_thumb_height = $imageheight * $shrinkage;
						$featured_thumb_src = $config['listings_view_images_path'] . '/' . $thumb_file_name;
	// gotta grab the thumbnail image size
						$imagedata = GetImageSize("$config[listings_upload_path]/$file_name");
						$imagewidth = $imagedata[0];
						$imageheight = $imagedata[1];
						$featured_width = $imagewidth;
						$featured_height = $imageheight;
						$featured_src = $config['listings_view_images_path'] . '/' . $file_name;
					}
				} else {
						if ($config['show_no_photo'] == 1) {
							$imagedata = GetImageSize("images/nophoto.gif");
							$imagewidth = $imagedata[0];
							$imageheight = $imagedata[1];
							$shrinkage = $config['thumbnail_width'] / $imagewidth;
							$featured_thumb_width = $imagewidth * $shrinkage;
							$featured_thumb_height = $imageheight * $shrinkage;
							$featured_thumb_src = "images/nophoto.gif";
							$featured_width = $featured_thumb_width;
							$featured_height = $featured_thumb_height;
							$featured_src = "images/nophoto.gif";
						} else {
							$featured_thumb_width = '';
							$featured_thumb_height = '';
							$featured_thumb_src = '';
							$featured_width = '';
							$featured_height = '';
							$featured_src = '';
						}
					}
				if (!empty($featured_thumb_src)) {
					$featured_template_section = $page->parse_template_section($featured_template_section, 'featured_thumb_src', $featured_thumb_src);
					$featured_template_section = $page->parse_template_section($featured_template_section, 'featured_thumb_height', $featured_thumb_height);
					$featured_template_section = $page->parse_template_section($featured_template_section, 'featured_thumb_width', $featured_thumb_width);
					$featured_template_section = $page->cleanup_template_block('featured_img', $featured_template_section);
				} else {
					$featured_template_section = $page->remove_template_block('featured_img', $featured_template_section);
				}
				if (!empty($featured_src)) {
								$featured_template_section = $page->parse_template_section($featured_template_section, 'featured_large_src', $featured_src);
								$featured_template_section = $page->parse_template_section($featured_template_section, 'featured_large_height', $featured_height);
								$featured_template_section = $page->parse_template_section($featured_template_section, 'featured_large_width', $featured_width);
								$featured_template_section = $page->cleanup_template_block('featured_img_large', $featured_template_section);
				}else {
								$featured_template_section = $page->remove_template_block('featured_img_large', $featured_template_section);
				}
				$recordSet->MoveNext();
				if ($user_rows == true) {
					$x++;
				}
			}
			if ($user_rows == true) {
				$featured_template_section = $page->cleanup_template_block('featured_listing', $featured_template_section);
				$new_row_data[] = $page->replace_template_section('featured_listing_block', $featured_template_section,$row);
				$replace_row = '';
				foreach ($new_row_data as $rows){
					$replace_row .= $rows;
				}
				$page->replace_template_section_row('featured_listing_block_row', $replace_row);
			}else {
				$page->replace_template_section('featured_listing_block', $featured_template_section);
			}
			$page->replace_permission_tags();
			$page->auto_replace_tags();
			$display .= $page->return_page();
		}
		$current_ID='';
		if ($old_current_ID != ''){
			 $current_ID= $old_current_ID;
		}
		return $display;
	}

	public static function listing_view()
	{
		global $conn, $lang, $config;
		$display = '';
		if (isset($_GET['listingID']) && $_GET['listingID'] != "" && is_numeric($_GET['listingID'])) {
				$sql='SELECT listingsdb_id FROM ' . $config['table_prefix'] . 'listingsdb WHERE listingsdb_id='.$_GET['listingID'];
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$num = $recordSet->RecordCount();
				if ($num != 0) {
					// first, check to see whether the listing is currently active
					$show_listing = listing_pages::checkActive($_GET['listingID']);
					if ($show_listing == "yes") {
						require_once($config['basepath'] . '/include/class/template/core.inc.php');
						$page = new page_user();
						//Lookup Class
						$sql2 = "SELECT class_id FROM " . $config['table_prefix_no_lang'] . "classlistingsdb WHERE listingsdb_id = $_GET[listingID]";
						$recordSet2 = $conn->SelectLimit($sql2, 1, 0);
						$num = $recordSet2->RecordCount();
						if ($recordSet2 === false) {
							$misc->log_error($sql2);
						}
						$class = $recordSet2->fields['class_id'];
						if(file_exists($config['template_path'] . '/listing_detail_pclass'.$class.'.html')){
							$page->load_page($config['template_path'] . '/listing_detail_pclass'.$class.'.html');
						}else{
							$page->load_page($config['template_path'] . '/' . $config['listing_template']);
						}
						$sections = explode(',', $config['template_listing_sections']);
						foreach($sections as $section) {
							$replace = listing_pages::renderTemplateArea($section, $_GET['listingID']);
							$page->replace_tag($section, $replace);
						}
						$page->replace_listing_field_tags($_GET['listingID']);
						// Check to see if listing owner is an admin only.
						$is_admin = listing_pages::getListingAgentAdminStatus($_GET['listingID']);
						if ($is_admin == true && $config["show_listedby_admin"] == 0) {
							$page->page = $page->remove_template_block('show_listed_by_admin', $page->page);
							$page->page = $page->cleanup_template_block('!show_listed_by_admin', $page->page);
						}else {
							$page->page = $page->cleanup_template_block('show_listed_by_admin', $page->page);
							$page->page = $page->remove_template_block('!show_listed_by_admin', $page->page);
						}
						if ($config['show_next_prev_listing_page'] == 1) {
							$next_prev = listing_pages::listing_next_prev();
							$page->page = str_replace('{next_prev}', $next_prev, $page->page);
						}else {
							$page->page = str_replace('{next_prev}', '', $page->page);
						}
						require_once($config['basepath'] . '/include/vtour.inc.php');
						$goodvtour = vtours::goodvtour($_GET['listingID']);
						if ($goodvtour == true) {
							$page->page = $page->cleanup_template_block('vtour_tab', $page->page);
						}else {
							$page->page = $page->remove_template_block('vtour_tab', $page->page);
						}
						$display .= $page->return_page();
					} else {
						$display .= $lang['this_listing_is_not_active'];
					}
				} else {
					$display .= "<a href=\"index.php\">$lang[perhaps_you_were_looking_something_else]</a>";
				}
			} else {
				$display .= "<a href=\"index.php\">$lang[perhaps_you_were_looking_something_else]</a>";
			}
		return $display;
	}
	public static function listing_next_prev()
	{
		global $config, $lang;
		$display = '';
		$listingID = intval($_GET['listingID']);
		if (isset($_SERVER['HTTP_REFERER'])) {
			$pos = strpos($_SERVER['HTTP_REFERER'], 'search');
			$pos1 = strpos($_SERVER['HTTP_REFERER'], 'listingview');
			$pos2 = strpos($_SERVER['HTTP_REFERER'], 'view_listing_image');
			$pos3 = strpos($_SERVER['HTTP_REFERER'], 'listing-');
			$pos4 = strpos($_SERVER['HTTP_REFERER'], 'listing_image_');
			if (($pos == "") && ($pos1 == "") && ($pos2 == "") && ($pos3 == "") && ($pos4 == "")) {
				unset($_SESSION['results']);
			}
			if (isset($_SESSION['results'])) {
				$url = $_SESSION['searchstring'];
				$cur_page = $_SESSION['cur_page'];
				$url_with_page = $url . '&amp;cur_page=' . $cur_page;
				$np_listings = $_SESSION['results'];
				$np_titles = $_SESSION['titles'];
				$i = 1;
				$array = '';
				foreach($np_listings as $np_listing){
					$myarray[$i]= $np_listing;
					$i++;
				}
				$i = 1;
				$array = '';
				foreach($np_titles as $np_title){
					$myarray2[$i]= misc::urlencode_to_sef($np_title);
					$i++;
				}
				if($_SESSION['count'] > 1){
					$display .= '<div class="next_prev_listing"><div class="count">'.$lang['there_are_currently'].' ' . $_SESSION['count'] . ' '.$lang['that_match_search'].'<br />'.$lang['navigate'].'</div><ul>';
					foreach($myarray as $i => $item){
						if($item == $listingID){
							//Show First and Previous Buttons if we are not on the first listing.
							if($i != 1){
								if ($config['url_style'] == '1') {
								$display.='<li><a href="index.php?action=listingview&amp;listingID='.$myarray[1].'" ><img src="' . $config["template_url"] . '/images/nextprev/first.png" alt="' . $lang['first'] . '" title="' . $lang['first'] . '" /></a></li><li><a href="index.php?action=listingview&amp;listingID='.$myarray[$i-1].'" ><img src="' . $config["template_url"] . '/images/nextprev/previous.png" alt="' . $lang['previous'] . '" title="' . $lang['previous'] . '" /></a></li>';
								}
								else{
								$display.='<li><a href="listing-'.$myarray2[1].'-'.$myarray[1].'.html" ><img src="' . $config["template_url"] . '/images/nextprev/first.png" alt="' . $lang['first'] . '" title="' . $lang['first'] . '" /></a></li><li><a href="listing-'.$myarray2[$i-1].'-'.$myarray[$i-1].'.html" ><img src="' . $config["template_url"] . '/images/nextprev/previous.png" alt="' . $lang['previous'] . '" title="' . $lang['previous'] . '" /></a></li>';
								}
							}
							//Show Disabled Previous Button if we are on the first listing
							if($i == 1){
								$display.='<li><img src="' . $config["template_url"] . '/images/nextprev/first_n.png" alt="' . $lang['first'] . '" title="' . $lang['first'] . '" /></li><li><img src="' . $config["template_url"] . '/images/nextprev/previous_n.png" alt="' . $lang['previous'] . '" title="' . $lang['previous'] . '" /></li>';
							}
							//Show Refine Search and Save Search Buttons if the search string was not empty
							if(!empty($url)){
								$display.='<li><a href="index.php?action=searchresults'.$url_with_page.'" > <img src="' . $config["template_url"] . '/images/nextprev/search.png" alt="'.$lang['return_to_search'].'" title="'.$lang['return_to_search'].'" /></a></li><li><a href="index.php?action=save_search'.$url.'" ><img src="' . $config["template_url"] . '/images/nextprev/save.png" alt="' . $lang['save_this_search'] . '" title="' . $lang['save_this_search'] . '" /></a></li>';
							}
							if(empty($url)){
								$display.='<li><a href="index.php?action=searchpage" ><img src="' . $config["template_url"] . '/images/nextprev/search.png" alt="' . $lang['refine_search'] . '" title="' . $lang['refine_search'] . '" /></a></li><li><a href="index.php?action=save_search'.$url.'" ><img src="' . $config["template_url"] . '/images/nextprev/save.png" alt="' . $lang['save_this_search'] . '" title="' . $lang['save_this_search'] . '"  /></a></li>';
							}
							//Show Next Last Buttons if we are not on the last Listing
							if($i != $_SESSION['count']){
								if ($config['url_style'] == '1') {
								$display.='<li><a href="index.php?action=listingview&amp;listingID='.$myarray[$i+1].'" ><img src="' . $config["template_url"] . '/images/nextprev/next.png" alt="' . $lang['next'] . '" title="' . $lang['next'] . '" /></a></li><li><a href="index.php?action=listingview&amp;listingID='.$myarray[$_SESSION['count']].'" ><img src="' . $config["template_url"] . '/images/nextprev/last.png" alt="' . $lang['last'] . '" title="' . $lang['last'] . '" /></a></li></ul><div class="listing_xy">'.$lang['next_prev_listing'].' '.$i.' '.$lang['next_prev_of'].' '.$_SESSION['count'].'</div>';
								}
								else {
								$display.='<li><a href="listing-'.$myarray2[$i+1].'-'.$myarray[$i+1].'.html" ><img src="' . $config["template_url"] . '/images/nextprev/next.png" alt="' . $lang['next'] . '" title="' . $lang['next'] . '" /></a></li><li><a href="listing-'.$myarray2[$_SESSION['count']].'-'.$myarray[$_SESSION['count']].'.html" ><img src="' . $config["template_url"] . '/images/nextprev/last.png" alt="' . $lang['last'] . '" title="' . $lang['last'] . '" /></a></li></ul><div class="listing_xy">'.$lang['next_prev_listing'].' '.$i.' '.$lang['next_prev_of'].' ' . $_SESSION['count'] . '</div>';
								}
							}
							if($i == $_SESSION['count']){
								$display.='<li><img src="' . $config["template_url"] . '/images/nextprev/next_n.png" alt="' . $lang['next'] . '" title="' . $lang['next'] . '" /></li><li><img src="' . $config["template_url"] . '/images/nextprev/last_n.png" alt="' . $lang['last'] . '" title="' . $lang['last'] . '" /></li></ul><div class="listing_xy">'.$lang['next_prev_listing'].' '.$i.' '.$lang['next_prev_of'].' ' . $_SESSION['count'] . '</div>';
							}
						}
					}
					$display.='</div>';
				}
			}
		}
		return $display;
	}
	public static function contact_agent_link($url_only = 'no')
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$sql_listingID = intval($_GET['listingID']);
		$agentfname=$this->getListingAgentFirstName($sql_listingID);
		$agentlname=$this->getListingAgentLastName($sql_listingID);
		if ($url_only == 'no') {
			if ($config['url_style'] == '1') {
				$display = '<a href="'.$config['baseurl'].'/index.php?action=contact_agent&amp;popup=yes&amp;listing_id=' . $sql_listingID . '" onclick="window.open(this.href,\'_blank\',\'location=0,status=0,scrollbars=1,toolbar=0,menubar=0,width=500,height=520\');return false">' . $lang['contact_agent'] . '</a>';
			}else {
				$display = '<a href="'.$config['baseurl'].'/contact-agent-'.$agentfname.'_'.$agentlname.'-'.$sql_listingID . '.html" onclick="window.open(this.href,\'_blank\',\'location=0,status=0,scrollbars=1,toolbar=0,menubar=0,width=500,height=520\');return false">' . $lang['contact_agent'] . '</a>';
			}
		} else {
			if ($config['url_style'] == '1') {
				$display = $config['baseurl'].'/index.php?action=contact_agent&amp;popup=yes&amp;listing_id=' . $sql_listingID;
			}else{
				$display = $config['baseurl'].'/contact-agent-'.$agentfname.'_'.$agentlname.'-'.$sql_listingID.'.html';
			}
		}
		return $display;
	}
	public static function create_yahoo_school_link($url_only = 'no')
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$sql_listingID = $misc->make_db_safe($_GET['listingID']);
		$city_field = $config['map_city'];
		$sql_city_field = addslashes($city_field);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = '$sql_city_field'))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$city = '';
		while (!$recordSet->EOF) {
			$city = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
			$recordSet->MoveNext();
		} // end while
		//Get State
		$state_field = $config['map_state'];
		$sql_state_field = addslashes($state_field);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = '$sql_state_field'))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$state = '';
		while (!$recordSet->EOF) {
			$state = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
			$recordSet->MoveNext();
		} // end while
		//Get Zip
		$zip_field = $config['map_zip'];
		$sql_zip_field = addslashes($zip_field);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = '$sql_zip_field'))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$zip = '';
		while (!$recordSet->EOF) {
			$zip = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
			$recordSet->MoveNext();
		} // end while
		//Build URL
		if ($city != '' && ($state!=''||$zip!='')) {
			if ($url_only == 'no') {
				//http://www.greatschools.net/search/search.page?state=&amp;q=&amp;type=school
			$display = '<a href="http://www.greatschools.net/search/search.page?state='.$state.'&amp;q='.$city.'&amp;type=school" onclick="window.open(this.href,\'_school\',\'location=0,status=0,scrollbars=1,toolbar=0,menubar=0,resizable=1\');return false">' . $lang['school_profile'] . '</a>';
			} else {
				$display = 'http://www.greatschools.net/search/search.page?state='.$state.'&amp;q='.$city.'&amp;type=school';
			}
		}
		return $display;
	}
	public static function create_yahoo_neighborhood_link($url_only = 'no')
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$sql_listingID = intval($_GET['listingID']);
		$city_field = $config['map_city'];
		$sql_city_field = addslashes($city_field);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = '$sql_city_field'))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$city = '';
		while (!$recordSet->EOF) {
			$city = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
			$recordSet->MoveNext();
		} // end while
		//Get Zip
		$zip_field = $config['map_zip'];
		$sql_zip_field = addslashes($zip_field);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = '$sql_zip_field'))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$zip = '';
		while (!$recordSet->EOF) {
			$zip = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
			$recordSet->MoveNext();
		} // end while
		if ($zip != '') {
			if ($url_only == 'no') {
				$display = '<a href="http://www.bestplaces.net/search/?q='.$zip.'" onclick="window.open(this.href,\'_neighborhood\',\'location=0,status=0,scrollbars=1,toolbar=0,menubar=0,resizable=1\');return false">' . $lang['neighborhood_profile'] . '</a>';
			} else {
				$display = 'http://www.bestplaces.net/search/q='.$zip;
			}
		}elseif($city != ''){
			if ($url_only == 'no') {
				$display = '<a href="http://www.bestplaces.net/search/?q='.$city.'" onclick="window.open(this.href,\'_neighborhood\',\'location=0,status=0,scrollbars=1,toolbar=0,menubar=0,resizable=1\');return false">' . $lang['neighborhood_profile'] . '</a>';
			} else {
				$display = 'http://www.bestplaces.net/search/q='.$city;
			}
		}
		return $display;
	}
	public static function create_email_friend_link($url_only = 'no')
	{
		global $lang,$config;
		if ($url_only == 'no') {
			$display = '<a href="'.$config['baseurl'].'/index.php?action=contact_friend&amp;popup=yes&amp;listing_id=' . intval($_GET['listingID']) . '" onclick="window.open(this.href,\'_blank\',\'location=0,status=0,scrollbars=1,toolbar=0,menubar=0,width=500,height=520\');return false">' . $lang['email_listing_link'] . '</a>';
			} else {
				$display = $config['baseurl'].'/index.php?action=contact_friend&amp;popup=yes&amp;listing_id=' . intval($_GET['listingID']) . '';
			}
		return $display;
	}
	public static function create_printer_friendly_link($url_only = 'no')
	{
		global $lang,$config;
		if (isset($_GET['listingID'])) {
			if ($url_only == 'no') {
				$display = '<a href="'.$config['baseurl'].'/index.php?action=listingview&amp;listingID=' . intval($_GET['listingID']) . '&amp;printer_friendly=yes">' . $lang['printer_version_link'] . '</a>';
			} else {
				$display = $config['baseurl'].'/index.php?action=listingview&amp;listingID=' . intval($_GET['listingID']) . '&amp;printer_friendly=yes';
			}
		}else {
			// Save GET
			$guidestring = '';
			foreach ($_GET as $k => $v) {
				if ($v && $k != 'PHPSESSID' && $k != 'printer_friendly') {
					if (is_array($v)) {
						foreach ($v as $vitem) {
							$guidestring .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
						}
					}else {
						$guidestring .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
					}
				}
			}
			if ($url_only == 'no') {
				$display = '<a href="'.$config['baseurl'].'/index.php?' . $guidestring . '&amp;printer_friendly=yes">' . $lang['printer_version_link'] . '</a>';
			} else {
				$display = $config['baseurl'].'/index.php?' . $guidestring . '&amp;printer_friendly=yes';
			}
		}
		return $display;
	}
	public static function create_calc_link($url_only = 'no')
	{
		global $lang,$config;
		if ($url_only == 'no') {
		$display = '<a href="'.$config['baseurl'].'/index.php?action=calculator&amp;popup=yes&amp;price=' . listing_pages::renderSingleListingItem(intval($_GET['listingID']), $config['price_field'], 'rawvalue') . '" onclick="window.open(this.href,\'_blank\',\'location=0,status=0,scrollbars=1,toolbar=0,menubar=0,width=500,height=520\');return false">' . $lang['mortgage_calculator_link'] . '</a>';
		} else {
		$display = $config['baseurl'].'/index.php?action=calculator&amp;popup=yes&amp;price=' . listing_pages::renderSingleListingItem(intval($_GET['listingID']), $config['price_field'], 'rawvalue') . '';
		}
		return $display;
	}
	public static function create_add_favorite_link($url_only = 'no')
	{
		global $lang,$current_ID,$config;
		$list_id=0;
		if ($current_ID != '')
		{
		$_GET['listingID'] = $current_ID;
		}
		if ($url_only == 'no') {
		$display = '<a href="'.$config['baseurl'].'/index.php?action=addtofavorites&amp;listingID=' . intval($_GET['listingID']) . '">' . $lang['add_favorites_link'] . '</a>';
		} else {
		$display = $config['baseurl'].'/index.php?action=addtofavorites&amp;listingID=' . intval($_GET['listingID']) . '';
		}
		return $display;
	}
	public static function checkActive($listingID)
	{
		// checks whether a given listing is active
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$show_listing = "yes";
		$sql = "SELECT listingsdb_active, userdb_id FROM " . $config['table_prefix'] . "listingsdb WHERE listingsdb_id = " . $listingID;
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num = $recordSet->RecordCount();
		if ($num != 0) {
		if (!$recordSet->EOF) {
			$is_active = $recordSet->fields['listingsdb_active'];
			$user_ID = $recordSet->fields['userdb_id'];
			if ($is_active != "yes") {
				// if the listing isn't active
				$show_listing = "no";
				if (isset($_SESSION['userID'])) {
					if ($_SESSION['userID'] == $user_ID || $_SESSION['admin_privs'] == "yes") {
						// if this isn't a specific user's listing or the user
						// isn't an admin
						$show_listing = "yes";
					} // end if ($userID != $user_ID || $admin_privs != "yes")
				}
			} // end if ($is_active != "yes")
			else {
				if ($config['use_expiration'] === "1") {
					$sql = "SELECT listingsdb_expiration FROM " . $config['table_prefix'] . "listingsdb WHERE ((listingsdb_id = $listingID) AND (listingsdb_expiration > " . $conn->DBDate(time()) . "))";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
						}
					$num = $recordSet->RecordCount();
					if ($num == 0) {
						$show_listing = "no";
						if (isset($_SESSION['userID'])) {
							if ($_SESSION['userID'] == $user_ID || $_SESSION['admin_privs'] == "yes") {
								// if this isn't a specific user's listing or the user
								// isn't an admin
								$show_listing = "yes";
							} // end if ($userID != $user_ID || $admin_privs != "yes")
						}
					} // end if($num == 0)
				} // end if ($config[use_expiration] === "1")
			}
		} //end if ($num != 0)
		else
		{
		$show_listing = "no";
		}
		}

		return $show_listing;
	} // end function checkActive
	public static function getListingEmail($listingID,$value_only=false)
	{
		// get the email address for the person who posted a listing
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$listingID = $misc->make_db_extra_safe($listingID);
		$sql = "SELECT userdb_emailaddress FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "userdb WHERE ((listingsdb_id = $listingID) AND (" . $config['table_prefix'] . "userdb.userdb_id = " . $config['table_prefix'] . "listingsdb.userdb_id))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		// return the email address
		while (!$recordSet->EOF) {
			$listing_emailAddress = $misc->make_db_unsafe ($recordSet->fields['userdb_emailaddress']);
			$recordSet->MoveNext();
		} // end while
		if ($value_only === true){
		$display = "$listing_emailAddress";
		}else{
		$display = "<b>$lang[user_email]:</b> <a href=\"mailto:$listing_emailAddress\">$listing_emailAddress</a><br />";
		}
		return $display;
	} // public static function getMainListingData
	public static function hitcount($listingID)
	{
		// counts hits to a given listing
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$listingID = $misc->make_db_extra_safe($listingID);
		$sql = "UPDATE " . $config['table_prefix'] . "listingsdb SET listingsdb_hit_count=listingsdb_hit_count+1 WHERE listingsdb_id=$listingID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$sql = "SELECT listingsdb_hit_count FROM " . $config['table_prefix'] . "listingsdb WHERE listingsdb_id=$listingID";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$display = '';
		while (!$recordSet->EOF) {
			$hitcount = $recordSet->fields['listingsdb_hit_count'];
			$recordSet->MoveNext();
		} // end while
		return $hitcount;
	} // end function hitcount

	public static function edit_listing_link($url_only = 'no')
	{
		global $lang,$config,$current_ID;
		$display = '';
		//Get the listing ID
			if ($current_ID != '')
				{
				$_GET['listingID'] = $current_ID;
				}
		if (isset($_GET['listingID'])) {
			$listingID = intval($_GET['listingID']);
			$listingagentid = listing_pages::getListingAgentID($listingID);
			if (isset($_SESSION['userID'])) {
				$userid = $_SESSION['userID'];
				if ($_SESSION['edit_all_listings'] == 'yes' || $_SESSION['admin_privs'] == 'yes') {
					$edit_link = $config['baseurl'].'/admin/index.php?action=edit_listings&amp;edit='.$listingID;
				} elseif (($_SESSION['isAgent'] == 'yes') && ($listingagentid == $userid)) {
					$edit_link = $config['baseurl'].'/admin/index.php?action=edit_my_listings&amp;edit='.$listingID;
				}else{
					return;
				}
				if ($url_only == 'yes') {
					$display = $edit_link;
				} else {
					$display = '<a href="'.$edit_link.'">'.$lang['edit_listing'].'</a>';
				}
			}
		}
		return $display;
	} // end function edit_listing_link

	public static function get_featured($listing_id, $raw)
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$listing_id = $misc->make_db_extra_safe($listing_id);
		$sql = "SELECT listingsdb_featured FROM " . $config['table_prefix'] . "listingsdb WHERE listingsdb_id = $listing_id";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$featured = $recordSet->fields['listingsdb_featured'];
		if ($raw == 'no') {
			if ($featured == 'yes') {
				$featured = 'featured';
			} else {
				$featured = '';
			}
		}
		return $featured;
	}

}
?>