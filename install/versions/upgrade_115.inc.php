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
// just so we know it is broken
class version extends installer {
	function load_prev_settings()
	{
	}
	function create_tables()
	{
		// this is the setup for the ADODB library
		include_once(dirname(__FILE__) . '/../../include/class/adodb/adodb.inc.php');
		$conn = &ADONewConnection($_SESSION['db_type']);
		$conn->PConnect($_SESSION['db_server'], $_SESSION['db_user'], $_SESSION['db_password'], $_SESSION['db_database']);
		$config['table_prefix'] = $_SESSION['table_prefix'] . $_SESSION['or_install_lang'] . '_';
		$config['table_prefix_no_lang'] = $_SESSION['table_prefix'];
		echo $this->lang['install_populate_db'] . '<br />';
		// removed prefixes because there is already going to be a prefix added.
		$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . "controlpanel (
			  controlpanel_version CHAR VARYING(15) NOT NULL ,
			  controlpanel_basepath CHAR VARYING(150) NOT NULL,
			  controlpanel_baseurl CHAR VARYING(150) NOT NULL,
			  controlpanel_admin_name CHAR VARYING(45) NOT NULL,
			  controlpanel_admin_email CHAR VARYING(45) NOT NULL,
			  controlpanel_company_name CHAR VARYING(45),
			  controlpanel_company_location CHAR VARYING(45),
			  controlpanel_company_logo CHAR VARYING(45),
			  controlpanel_url_style INT2 NOT NULL,
			  controlpanel_template CHAR VARYING(45) NOT NULL,
			  controlpanel_admin_template CHAR VARYING(45) NOT NULL,
			  controlpanel_listing_template CHAR VARYING(45) NOT NULL,
			  controlpanel_agent_template CHAR VARYING(45) NOT NULL,
			  controlpanel_search_result_template CHAR VARYING(45) NOT NULL,
			  controlpanel_lang CHAR(2) NOT NULL,
			  controlpanel_listings_per_page INT4 NOT NULL,
			  controlpanel_num_featured_listings INT4 NOT NULL,
			  controlpanel_add_linefeeds INT2 NOT NULL,
			  controlpanel_strip_html INT2 NOT NULL,
			  controlpanel_allowed_html_tags CHAR VARYING(45) NOT NULL,
			  controlpanel_money_sign CHAR VARYING(20) NOT NULL,
			  controlpanel_show_no_photo INT2 NOT NULL,
			  controlpanel_number_format_style INT4 NOT NULL,
			  controlpanel_money_format INT4 NOT NULL,
			  controlpanel_date_format INT4 NOT NULL,
			  controlpanel_max_listings_uploads INT4 NOT NULL,
			  controlpanel_max_listings_upload_size INT4 NOT NULL,
			  controlpanel_max_listings_upload_width INT4 NOT NULL,
			  controlpanel_max_listings_upload_height INT4 NOT NULL,
			  controlpanel_max_user_uploads INT4 NOT NULL,
			  controlpanel_max_user_upload_size INT4 NOT NULL,
			  controlpanel_max_user_upload_width INT4 NOT NULL,
			  controlpanel_max_user_upload_height INT4 NOT NULL,
			  controlpanel_max_vtour_uploads INT4 NOT NULL,
			  controlpanel_max_vtour_upload_size INT4 NOT NULL,
			  controlpanel_max_vtour_upload_width INT4 NOT NULL,
			  controlpanel_allowed_upload_extensions CHAR VARYING(45) NOT NULL,
			  controlpanel_make_thumbnail INT2 NOT NULL,
			  controlpanel_thumbnail_width INT4 NOT NULL,
			  controlpanel_gd_version INT2 NOT NULL,
			  controlpanel_thumbnail_prog CHAR VARYING(20) NOT NULL,
			  controlpanel_path_to_imagemagick CHAR VARYING(255) NOT NULL,
			  controlpanel_resize_img INT2 NOT NULL,
			  controlpanel_jpeg_quality INT2 NOT NULL,
			  controlpanel_use_expiration INT2 NOT NULL,
			  controlpanel_days_until_listings_expire INT4 NOT NULL,
			  controlpanel_allow_member_signup INT2 NOT NULL,
			  controlpanel_allow_agent_signup INT2 NOT NULL,
			  controlpanel_agent_default_active INT2 NOT NULL,
			  controlpanel_agent_default_admin INT2 NOT NULL,
			  controlpanel_agent_default_feature INT2 NOT NULL,
			  controlpanel_agent_default_moderate INT2 NOT NULL,
			  controlpanel_agent_default_logview INT2 NOT NULL,
			  controlpanel_agent_default_edit_site_config INT2 NOT NULL,
			  controlpanel_agent_default_edit_member_template INT2 NOT NULL,
			  controlpanel_agent_default_edit_agent_template INT2 NOT NULL,
			  controlpanel_agent_default_edit_listing_template INT2 NOT NULL,
			  controlpanel_agent_default_canchangeexpirations INT2 NOT NULL,
			  controlpanel_agent_default_editpages INT2 NOT NULL,
			  controlpanel_agent_default_havevtours INT2 NOT NULL,
			  controlpanel_agent_default_num_listings INT2 NOT NULL,
			  controlpanel_agent_default_can_export_listings  INT2 NOT NULL,
			  controlpanel_agent_default_edit_all_users INT2 NOT NULL,
			  controlpanel_agent_default_edit_all_listings INT2 NOT NULL,
			  controlpanel_agent_default_edit_property_classes  INT2 NOT NULL,
			  controlpanel_moderate_agents INT2 NOT NULL,
			  controlpanel_moderate_members INT2 NOT NULL,
			  controlpanel_moderate_listings INT2 NOT NULL,
			  controlpanel_export_listings INT2 NOT NULL,
			  controlpanel_email_notification_of_new_users INT2 NOT NULL,
			  controlpanel_email_notification_of_new_listings INT2 NOT NULL,
			  controlpanel_allowed_upload_types CHAR VARYING(95) NOT NULL,
			  controlpanel_configured_langs CHAR VARYING(100) NOT NULL,
			  controlpanel_email_users_notification_of_new_listings INT2 NOT NULL,
			  controlpanel_configured_show_count INT2 NOT NULL,
			  controlpanel_map_type CHAR VARYING(45) NOT NULL,
			  controlpanel_map_address CHAR VARYING(45) NOT NULL,
			  controlpanel_map_address2 CHAR VARYING(45) NOT NULL,
			  controlpanel_map_address3 CHAR VARYING(45) NOT NULL,
			  controlpanel_map_address4 CHAR VARYING(45) NOT NULL,
			  controlpanel_map_city CHAR VARYING(45) NOT NULL,
			  controlpanel_map_state CHAR VARYING(45) NOT NULL,
			  controlpanel_map_zip CHAR VARYING(45) NOT NULL,
			  controlpanel_map_country CHAR VARYING(45) NOT NULL,
			  controlpanel_wysiwyg_editor CHAR VARYING(45) NOT NULL,
			  controlpanel_wysiwyg_execute_php INT4 NOT NULL,
			  controlpanel_number_decimals_number_fields INT2 NOT NULL,
			  controlpanel_number_decimals_price_fields INT2 NOT NULL,
			  controlpanel_automatic_update_check INT2 NOT NULL,
			  controlpanel_search_sortby CHAR VARYING(45) NOT NULL,
			  controlpanel_search_sorttype CHAR VARYING(45) NOT NULL,
			  controlpanel_show_listedby_admin INT2 NOT NULL,
			  controlpanel_show_next_prev_listing_page INT2 NOT NULL,
			  controlpanel_multiple_pclass_selection INT2 NOT NULL,
			  controlpanel_seo_default_description TEXT NOT NULL,
			  controlpanel_seo_default_keywords TEXT NOT NULL,
			  controlpanel_seo_listing_description TEXT NOT NULL,
			  controlpanel_seo_listing_keywords TEXT NOT NULL,
			  controlpanel_seo_default_title TEXT NOT NULL,
			  controlpanel_seo_listing_title TEXT NOT NULL,
			  controlpanel_template_listing_sections TEXT NOT NULL,
			  controlpanel_vtour_template CHAR VARYING(45) NOT NULL,
			  controlpanel_vtour_width CHAR VARYING(45) NOT NULL,
			  controlpanel_vtour_height CHAR VARYING(45) NOT NULL,
			  controlpanel_vt_popup_width CHAR VARYING(45) NOT NULL,
			  controlpanel_vt_popup_height CHAR VARYING(45) NOT NULL,
			  controlpanel_vtour_fov CHAR VARYING(45) NOT NULL,
			  controlpanel_zero_price INT2 NOT NULL,
			  controlpanel_vcard_phone CHAR VARYING(45) NOT NULL,
			  controlpanel_vcard_fax CHAR VARYING(45) NOT NULL,
			  controlpanel_vcard_mobile CHAR VARYING(45) NOT NULL,
			  controlpanel_vcard_address CHAR VARYING(45) NOT NULL,
			  controlpanel_vcard_city CHAR VARYING(45) NOT NULL,
			  controlpanel_vcard_state CHAR VARYING(45) NOT NULL,
			  controlpanel_vcard_zip CHAR VARYING(45) NOT NULL,
			  controlpanel_vcard_country CHAR VARYING(45) NOT NULL,
			  controlpanel_vcard_notes CHAR VARYING(45) NOT NULL,
			  controlpanel_vcard_url CHAR VARYING(45) NOT NULL,
			  controlpanel_email_information_to_new_users INT2 NOT NULL,
			  controlpanel_demo_mode INT2 NOT NULL,
			  controlpanel_max_search_results INT4 NOT NULL,
			  controlpanel_feature_list_separator CHAR VARYING(45) NOT NULL,
			  controlpanel_search_list_separator CHAR VARYING(45) NOT NULL,
			  controlpanel_use_email_image_verification INT2 NOT NULL,
			  controlpanel_rss_title_featured CHAR VARYING(45) NOT NULL,
			  controlpanel_rss_desc_featured CHAR VARYING(255) NOT NULL,
			  controlpanel_rss_listingdesc_featured TEXT NOT NULL,
			  controlpanel_rss_title_lastmodified CHAR VARYING(45) NOT NULL,
			  controlpanel_rss_desc_lastmodified CHAR VARYING(255) NOT NULL,
			  controlpanel_rss_listingdesc_lastmodified TEXT NOT NULL,
			  controlpanel_rss_limit_lastmodified INT4 NOT NULL,
			  controlpanel_thumbnail_height INT4 NOT NULL,
			  controlpanel_resize_thumb_by CHAR VARYING(20) NOT NULL,
			  controlpanel_resize_by CHAR VARYING(20) NOT NULL,
			  controlpanel_charset VARCHAR( 15 ) NOT NULL,
			  controlpanel_wysiwyg_show_edit INT2 NOT NULL,
			  controlpanel_textarea_short_chars INT4 NOT NULL,
			  controlpanel_main_image_display_by CHAR VARYING(20) NOT NULL,
			  controlpanel_main_image_width INT4 NOT NULL,
			  controlpanel_main_image_height INT4 NOT NULL,
			  controlpanel_number_columns INT4 NOT NULL,
			  controlpanel_rss_limit_featured INT4 NOT NULL,
			  controlpanel_force_decimals INT2 NOT NULL,
			  controlpanel_icon_image_width INT4 NOT NULL,
			  controlpanel_icon_image_height INT4 NOT NULL,
			  controlpanel_max_listings_file_uploads INT4 NOT NULL,
			  controlpanel_max_listings_file_upload_size INT4 NOT NULL,
			  controlpanel_allowed_file_upload_extensions CHAR VARYING(255) NOT NULL,
			  controlpanel_show_file_icon INT(2) NOT NULL,
			  controlpanel_show_file_size INT(2) NOT NULL,
			  controlpanel_file_display_option CHAR VARYING(20) NOT NULL,
			  controlpanel_include_senders_ip INT(2) NOT NULL,
			  controlpanel_agent_default_haveuserfiles INT(2) NOT NULL,
			  controlpanel_max_users_file_uploads INT4 NOT NULL,
			  controlpanel_max_users_file_upload_size INT4 NOT NULL,
			  controlpanel_show_notes_field INT2 NOT NULL,
			  controlpanel_disable_referrer_check INT2 NOT NULL,
			  controlpanel_seo_url_seperator CHAR VARYING(20) NOT NULL,
			  controlpanel_search_step_max INT4 NOT NULL,
			  controlpanel_special_search_sortby CHAR VARYING(45) NOT NULL,
			  controlpanel_special_search_sorttype CHAR VARYING(45) NOT NULL,
			  controlpanel_price_field CHAR VARYING(45) NOT NULL,
			  controlpanel_users_per_page INT4 NOT NULL,
			  controlpanel_use_help_link INT2 NOT NULL,
			  controlpanel_main_admin_help_link TEXT NOT NULL,
			  controlpanel_add_listing_help_link TEXT NOT NULL,
			  controlpanel_edit_listing_help_link TEXT NOT NULL,
			  controlpanel_edit_user_help_link TEXT NOT NULL,
			  controlpanel_user_manager_help_link TEXT NOT NULL,
			  controlpanel_page_editor_help_link TEXT NOT NULL,
			  controlpanel_modify_listing_help_link TEXT NOT NULL,
			  controlpanel_edit_listing_images_help_link TEXT NOT NULL,
			  controlpanel_edit_vtour_images_help_link TEXT NOT NULL,
			  controlpanel_edit_listing_files_help_link TEXT NOT NULL,
			  controlpanel_edit_agent_template_add_field_help_link TEXT NOT NULL,
			  controlpanel_edit_agent_template_field_order_help_link TEXT NOT NULL,
			  controlpanel_edit_member_template_add_field_help_link TEXT NOT NULL,
			  controlpanel_edit_listing_template_help_link TEXT NOT NULL,
			  controlpanel_edit_listing_template_add_field_help_link TEXT NOT NULL,
			  controlpanel_edit_listings_template_field_order_help_link TEXT NOT NULL,
			  controlpanel_edit_listing_template_search_help_link TEXT NOT NULL,
			  controlpanel_edit_listing_template_search_results_help_link TEXT NOT NULL,
			  controlpanel_show_property_classes_help_link TEXT NOT NULL,
			  controlpanel_configure_help_link TEXT NOT NULL,
			  controlpanel_view_log_help_link TEXT NOT NULL,
			  controlpanel_addon_transparentmaps_admin_help_link TEXT NOT NULL,
			  controlpanel_addon_transparentmaps_geocode_all_help_link TEXT NOT NULL,
			  controlpanel_addon_transparentRETS_config_server_help_link TEXT NOT NULL,
			  controlpanel_addon_transparentRETS_config_imports_help_link TEXT NOT NULL,
			  controlpanel_user_template_member_help_link TEXT NOT NULL,
			  controlpanel_user_template_agent_help_link TEXT NOT NULL,
			  controlpanel_modify_property_class_help_link TEXT NOT NULL,
			  controlpanel_insert_property_class_help_link TEXT NOT NULL,
			  controlpanel_addon_IDXManager_config_help_link TEXT NOT NULL,
			  controlpanel_addon_IDXManager_classmanager_help_link TEXT NOT NULL,
			  controlpanel_addon_csvloader_admin_help_link TEXT NOT NULL,
			  controlpanel_edit_member_template_field_order_help_link TEXT NOT NULL,
			  controlpanel_show_admin_on_agent_list INT2 NOT NULL,
			  controlpanel_agent_default_num_featuredlistings INT4 NOT NULL,
			  controlpanel_use_signup_image_verification INT2 NOT NULL,
			  controlpanel_site_email CHAR VARYING(45) NOT NULL,
			  controlpanel_mbstring_enabled INT2 NOT NULL,
			  controlpanel_require_email_verification INT2 NOT NULL,
			  controlpanel_maintenance_mode INT2 NOT NULL,
			  PRIMARY KEY(controlpanel_version)
			);";
		$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . "addons (
				  addons_version CHAR VARYING(15) NOT NULL,
				  addons_name CHAR VARYING(150) NOT NULL,
				  PRIMARY KEY(addons_name)
				);";

		$sql_insert[] = "CREATE TABLE  " . $config['table_prefix_no_lang'] . "zipdist (
			  zipdist_zipcode char(5) NULL,
			  zipdist_ziptype char(1) NULL,
			  zipdist_cityname CHAR VARYING(64) NULL,
			  zipdist_citytype char(1) NULL,
			  zipdist_statename CHAR VARYING(64) NULL,
			  zipdist_stateabbr char(2) NULL,
			  zipdist_areacode char(3) NULL,
			  zipdist_latitude decimal(9,6) NULL,
			  zipdist_longitude  decimal(9,6) NULL,
			  zipdist_id INT4 NOT NULL AUTO_INCREMENT,
			  PRIMARY KEY  (zipdist_id)
			);";

		$sql_insert[] = "CREATE TABLE IF NOT EXISTS " . $config['table_prefix'] . "vtourimages (
			  vtourimages_id INT4 NOT NULL AUTO_INCREMENT,
			  userdb_id INT4 NOT NULL,
			  vtourimages_caption CHAR VARYING(255) NOT NULL,
			  vtourimages_file_name CHAR VARYING(255) NOT NULL,
			  vtourimages_thumb_file_name CHAR VARYING(255) NOT NULL,
			  vtourimages_description TEXT NOT NULL,
			  listingsdb_id INT4 NOT NULL,
			  vtourimages_rank INT4 NOT NULL,
			  vtourimages_active CHAR(3) NOT NULL,
			  PRIMARY KEY(vtourimages_id)
			);";

		$sql_insert[] = "CREATE TABLE  " . $config['table_prefix'] . "listingsfiles (
			  listingsfiles_id INT4 NOT NULL AUTO_INCREMENT,
			  userdb_id INT4 NOT NULL,
			  listingsfiles_caption CHAR VARYING(255) NOT NULL,
			  listingsfiles_file_name CHAR VARYING(255) NOT NULL,
			  listingsfiles_description TEXT NOT NULL,
			  listingsdb_id INT4 NOT NULL,
			  listingsfiles_rank INT4 NOT NULL,
			  listingsfiles_active CHAR(3) NOT NULL,
			  PRIMARY KEY (listingsfiles_id)
		);";

		$sql_insert[] = "CREATE TABLE  " . $config['table_prefix'] . "usersfiles (
			  usersfiles_id INT4 NOT NULL AUTO_INCREMENT,
			  userdb_id INT4 NOT NULL,
			  usersfiles_caption CHAR VARYING(255) NOT NULL,
			  usersfiles_file_name CHAR VARYING(255) NOT NULL,
			  usersfiles_description TEXT NOT NULL,
			  usersfiles_rank INT4 NOT NULL,
			  usersfiles_active CHAR(3) NOT NULL,
			  PRIMARY KEY (usersfiles_id)
		);";

		$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "pagesmain (
			  pagesmain_id INT4 NOT NULL AUTO_INCREMENT,
			  pagesmain_title TEXT NOT NULL,
			  pagesmain_date CHAR VARYING(20) NOT NULL,
			  pagesmain_summary CHAR VARYING(255) NOT NULL,
			  pagesmain_full LONGTEXT NOT NULL,
			  pagesmain_no_visitors INT4 NOT NULL,
			  pagesmain_complete INT2 NOT NULL,
			  pagesmain_description TEXT NOT NULL,
			  pagesmain_keywords TEXT NOT NULL,
			  PRIMARY KEY(pagesmain_id)
			);";

		$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . "forgot (
			  forgot_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
			  forgot_rand INTEGER UNSIGNED NOT NULL,
			  forgot_email CHAR VARYING(45) NOT NULL,
			  forgot_time TIMESTAMP NOT NULL,
			  PRIMARY KEY(forgot_id)
			);";
		while (list($elementIndexValue, $elementContents) = each($sql_insert)) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
		}
		echo 'Tables Created<br />';
	}
	function update_tables()
	{
		// this is the setup for the ADODB library
		include_once(dirname(__FILE__) . '/../../include/class/adodb/adodb.inc.php');
		$conn = &ADONewConnection($_SESSION['db_type']);
		$conn->PConnect($_SESSION['db_server'], $_SESSION['db_user'], $_SESSION['db_password'], $_SESSION['db_database']);
		$config['table_prefix'] = $_SESSION['table_prefix'] . $_SESSION['or_install_lang'] . '_';
		$config['table_prefix_no_lang'] = $_SESSION['table_prefix'];
		$sql_insert2[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "vtourimages RENAME " . $config['table_prefix'] . "vtourimages";
		$sql_insert2[] = "ALTER TABLE `" . $config['table_prefix'] . "vtourimages` CHANGE `ID` `vtourimages_id` INT( 4 ) NOT NULL AUTO_INCREMENT ";
		$sql_insert2[] = "ALTER TABLE `" . $config['table_prefix'] . "vtourimages` CHANGE `user_id` `userdb_id` INT( 4 ) NOT NULL ";
		$sql_insert2[] = "ALTER TABLE `" . $config['table_prefix'] . "vtourimages` CHANGE `caption` `vtourimages_caption` CHAR VARYING(255) NOT NULL ";
		$sql_insert2[] = "ALTER TABLE `" . $config['table_prefix'] . "vtourimages` CHANGE `file_name` `vtourimages_file_name` CHAR VARYING(255) NOT NULL ";
		$sql_insert2[] = "ALTER TABLE `" . $config['table_prefix'] . "vtourimages` CHANGE `thumb_file_name` `vtourimages_thumb_file_name` CHAR VARYING(255) NOT NULL ";
		$sql_insert2[] = "ALTER TABLE `" . $config['table_prefix'] . "vtourimages` CHANGE `description` `vtourimages_description` TEXT NOT NULL ";
		$sql_insert2[] = "ALTER TABLE `" . $config['table_prefix'] . "vtourimages` CHANGE `listing_id` `listingsdb_id` INT( 4 ) NOT NULL ";
		$sql_insert2[] = "ALTER TABLE `" . $config['table_prefix'] . "vtourimages` CHANGE `rank` `vtourimages_rank` INT( 4 ) NOT NULL ";
		$sql_insert2[] = "ALTER TABLE `" . $config['table_prefix'] . "vtourimages` CHANGE `active` `vtourimages_active` char( 3 ) NOT NULL ";
		$sql_insert2[] = "ALTER TABLE `" . $config['table_prefix'] . "vtourimages` DROP PRIMARY KEY , ADD PRIMARY KEY ( `vtourimages_id` ) ";
		while (list($elementIndexValue, $elementContents) = each($sql_insert2)) {
			$recordSet = $conn->Execute($elementContents);
		}
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "activityLog RENAME " . $config['table_prefix'] . "activitylog";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "activitylog` CHANGE `id` `activitylog_id` INT( 4 ) NOT NULL AUTO_INCREMENT";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "activitylog` CHANGE `log_date` `activitylog_log_date` DATETIME NOT NULL";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "activitylog CHANGE `user` `userdb_id` INT( 4 ) NOT NULL";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "activitylog CHANGE `action` `activitylog_action` CHAR VARYING( 150 ) NOT NULL";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "activitylog CHANGE `ip_address` `activitylog_ip_address` CHAR VARYING( 15 ) NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "activitylog` ADD PRIMARY KEY (`activitylog_id`)";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "activitylog` DROP INDEX `id`";
		// alteration complete
		// Change the listingDB Table to Suit.
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "listingsDB RENAME " . $config['table_prefix'] . "listingsdb";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` CHANGE `ID` `listingsdb_id` INT( 4 ) NOT NULL AUTO_INCREMENT ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` CHANGE `user_ID` `userdb_id` INT( 4 ) NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` CHANGE `Title` `listingsdb_title` TEXT NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` CHANGE `expiration` `listingsdb_expiration` DATE NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` CHANGE `notes` `listingsdb_notes` TEXT NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` CHANGE `creation_date` `listingsdb_creation_date` DATE NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` CHANGE `last_modified` `listingsdb_last_modified` DATETIME NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` CHANGE `hitcount` `listingsdb_hit_count` INT( 4 ) NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` CHANGE `mlsimport` `listingsdb_mlsexport` CHAR( 3 ) NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` CHANGE `active` `listingsdb_active` CHAR( 3 ) NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` CHANGE `featured` `listingsdb_featured` CHAR( 3 ) NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` DROP INDEX `ID_2`";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` DROP INDEX `ID` ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdb` DROP INDEX `idx_mlsimport`";
		// alteration complete
		// Change the listingsDBElements Table to Suit
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "listingsDBElements RENAME " . $config['table_prefix'] . "listingsdbelements";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdbelements` CHANGE `ID` `listingsdbelements_id` INT( 4 ) NOT NULL AUTO_INCREMENT";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdbelements` CHANGE `field_name` `listingsdbelements_field_name` CHAR VARYING( 20 ) NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdbelements` CHANGE `field_value` `listingsdbelements_field_value` TEXT NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdbelements` CHANGE `listing_id` `listingsdb_id` INT( 4 ) NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdbelements` CHANGE `user_id` `userdb_id` INT( 4 ) NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdbelements` DROP PRIMARY KEY ,ADD PRIMARY KEY ( `listingsdbelements_id` )";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdbelements` DROP INDEX `ID`";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsdbelements` DROP INDEX `idx_listingid3`";
		// alteration complete
		// Change the listingsFormElements Table to Suit
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "listingsFormElements RENAME " . $config['table_prefix'] . "listingsformelements";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `ID` `listingsformelements_id` INT( 4 ) NOT NULL AUTO_INCREMENT ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `field_type` `listingsformelements_field_type` CHAR VARYING( 20 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `field_name` `listingsformelements_field_name` CHAR VARYING( 20 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `field_caption` `listingsformelements_field_caption` CHAR VARYING( 80 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `default_text` `listingsformelements_default_text` TEXT NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `field_elements` `listingsformelements_field_elements` TEXT NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `rank` `listingsformelements_rank` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` ADD `listingsformelements_search_rank` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` ADD `listingsformelements_search_result_rank` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `required` `listingsformelements_required` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `location` `listingsformelements_location` CHAR VARYING( 50 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `display_on_browse` `listingsformelements_display_on_browse` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `searchable` `listingsformelements_searchable` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `search_type` `listingsformelements_search_type` CHAR VARYING( 50 ) DEFAULT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `search_label` `listingsformelements_search_label` CHAR VARYING( 50 ) DEFAULT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `search_step` `listingsformelements_search_step` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` CHANGE `display_priv` `listingsformelements_display_priv` INT(4) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` DROP PRIMARY KEY ,ADD PRIMARY KEY (`listingsformelements_id` ) ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsformelements` DROP INDEX `idx_searchable` ";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsformelements ADD COLUMN listingsformelements_field_length INT4 NOT NULL";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsformelements ADD COLUMN listingsformelements_tool_tip TEXT NOT NULL";
		// alteration complete
		// Change the listingsImages Table to Suit
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "listingsImages RENAME " . $config['table_prefix'] . "listingsimages";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsimages` CHANGE `ID` `listingsimages_id` INT( 4 ) NOT NULL AUTO_INCREMENT ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsimages` CHANGE `user_id` `userdb_id` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsimages` CHANGE `caption` `listingsimages_caption` CHAR VARYING( 255 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsimages` CHANGE `file_name` `listingsimages_file_name` CHAR VARYING(255) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsimages` CHANGE `thumb_file_name` `listingsimages_thumb_file_name` CHAR VARYING(255) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsimages` CHANGE `description` `listingsimages_description` TEXT NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsimages` CHANGE `listing_id` `listingsdb_id` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsimages` CHANGE `rank` `listingsimages_rank` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsimages` CHANGE `active` `listingsimages_active` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "listingsimages` DROP PRIMARY KEY ,ADD PRIMARY KEY ( `listingsimages_id` ) ";
		// alteration complete
		// Change the UserDB Table to Suit
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "UserDB RENAME " . $config['table_prefix'] . "userdb";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `ID` `userdb_id` INT( 4 ) NOT NULL AUTO_INCREMENT ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `user_name` `userdb_user_name` CHAR VARYING( 80 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `emailAddress` `userdb_emailaddress` CHAR VARYING( 80 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `Comments` `userdb_comments` TEXT NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `user_password` `userdb_user_password` CHAR VARYING( 50 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `isAdmin` `userdb_is_admin` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `canEditForms` `userdb_can_edit_forms` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `creation_date` `userdb_creation_date` DATE NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `canFeatureListings` `userdb_can_feature_listings` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `canViewLogs` `userdb_can_view_logs` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `last_modified` `userdb_last_modified` TIMESTAMP";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `hitcount` `userdb_hit_count` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `canModerate` `userdb_can_moderate` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `isAgent` `userdb_is_agent` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` CHANGE `active` `userdb_active` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` DROP PRIMARY KEY , ADD PRIMARY KEY (`userdb_id` ) ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` DROP INDEX `ID` ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` DROP INDEX `ID_2` ";
		// Addition
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` ADD `userdb_can_edit_pages` CHAR( 3 ) NOT NULL AFTER `userdb_can_moderate` ;";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` ADD `userdb_can_have_vtours` CHAR(3) NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` ADD `userdb_limit_listings` INT4 NOT NULL DEFAULT '-1'";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` ADD `userdb_rank` INT4 NOT NULL DEFAULT '0'";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdb` ADD `userdb_featuredlistinglimit` INT4 NOT NULL DEFAULT '-1'";
		// Admin setup
		$sql_insert[] = "UPDATE " . $config['table_prefix'] . "userdb SET userdb_can_edit_pages = 'yes' WHERE userdb_is_admin = 'yes';";
		// alteration complete
		// Change the UserDBElements Table to Suit
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "UserDBElements RENAME " . $config['table_prefix'] . "userdbelements";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdbelements` CHANGE `ID` `userdbelements_id` INT( 4 ) NOT NULL AUTO_INCREMENT ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdbelements` CHANGE `field_name` `userdbelements_field_name` CHAR VARYING( 80 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdbelements` CHANGE `field_value` `userdbelements_field_value` TEXT NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdbelements` CHANGE `user_id` `userdb_id` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdbelements` DROP PRIMARY KEY , ADD PRIMARY KEY ( `userdbelements_id` ) ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userdbelements` DROP INDEX `ID` ";
		// alteration complete
		// Remove the userFormElements Table
		$sql_insert[] = "DROP TABLE `" . $config['table_prefix_no_lang'] . "userFormElements`";
		// removal complete
		// Change the userImages Table to Suit
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "userImages RENAME " . $config['table_prefix'] . "userimages";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userimages` CHANGE `ID` `userimages_id` INT( 4 ) NOT NULL AUTO_INCREMENT ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userimages` CHANGE `user_id` `userdb_id` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userimages` CHANGE `caption` `userimages_caption` CHAR VARYING( 255 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userimages` CHANGE `file_name` `userimages_file_name` CHAR VARYING(255) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userimages` CHANGE `thumb_file_name` `userimages_thumb_file_name` CHAR VARYING(255) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userimages` CHANGE `description` `userimages_description` TEXT NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userimages` CHANGE `rank` `userimages_rank` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userimages` CHANGE `active` `userimages_active` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userimages` DROP PRIMARY KEY , ADD PRIMARY KEY (`userimages_id` ) ";
		// alteration complete
		// Change the userSavedSearches Table to Suit
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "userSavedSearches RENAME " . $config['table_prefix'] . "usersavedsearches";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "usersavedsearches` CHANGE `ID` `usersavedsearches_id` INT( 4 ) NOT NULL AUTO_INCREMENT ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "usersavedsearches` CHANGE `user_ID` `userdb_id` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "usersavedsearches` CHANGE `Title` `usersavedsearches_title` CHAR VARYING( 255 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "usersavedsearches` CHANGE `query_string` `usersavedsearches_query_string` TEXT NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "usersavedsearches` CHANGE `last_viewed` `usersavedsearches_last_viewed` TIMESTAMP";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "usersavedsearches` CHANGE `new_listings` `usersavedsearches_new_listings` TINYINT( 2 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "usersavedsearches` DROP PRIMARY KEY , ADD PRIMARY KEY ( `usersavedsearches_id` ) ";
		// alteration complete
		// Change the userFavoriteListings Table to Suit
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "userFavoriteListings RENAME " . $config['table_prefix'] . "userfavoritelistings";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userfavoritelistings` CHANGE `ID` `userfavoritelistings_id` INT( 4 ) NOT NULL AUTO_INCREMENT ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userfavoritelistings` CHANGE `user_ID` `userdb_id` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userfavoritelistings` CHANGE `listing_ID` `listingsdb_id` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "userfavoritelistings` DROP PRIMARY KEY , ADD PRIMARY KEY ( `userfavoritelistings_id` ) ";
		// alteration complete
		// Change the memberFormElements Table to Suit
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "memberFormElements RENAME " . $config['table_prefix'] . "memberformelements";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "memberformelements` CHANGE `ID` `memberformelements_id` INT( 4 ) NOT NULL AUTO_INCREMENT ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "memberformelements` CHANGE `field_type` `memberformelements_field_type` CHAR VARYING( 20 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "memberformelements` CHANGE `field_name` `memberformelements_field_name` CHAR VARYING( 20 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "memberformelements` CHANGE `field_caption` `memberformelements_field_caption` CHAR VARYING( 80 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "memberformelements` CHANGE `default_text` `memberformelements_default_text` TEXT NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "memberformelements` CHANGE `field_elements` `memberformelements_field_elements` TEXT NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "memberformelements` CHANGE `rank` `memberformelements_rank` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "memberformelements` CHANGE `required` `memberformelements_required` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "memberformelements` ADD `memberformelements_display_priv` INT4 NOT NULL DEFAULT 0";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "memberformelements` DROP PRIMARY KEY , ADD PRIMARY KEY ( `memberformelements_id` ) ";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "memberformelements ADD COLUMN memberformelements_tool_tip TEXT NOT NULL";
		// alteration complete
		// Change the agentFormElements Table to Suit
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "agentFormElements RENAME " . $config['table_prefix'] . "agentformelements";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "agentformelements` CHANGE `ID` `agentformelements_id` INT( 4 ) NOT NULL AUTO_INCREMENT ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "agentformelements` CHANGE `field_type` `agentformelements_field_type` CHAR VARYING( 20 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "agentformelements` CHANGE `field_name` `agentformelements_field_name` CHAR VARYING( 20 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "agentformelements` CHANGE `field_caption` `agentformelements_field_caption` CHAR VARYING( 80 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "agentformelements` CHANGE `default_text` `agentformelements_default_text` TEXT NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "agentformelements` CHANGE `field_elements` `agentformelements_field_elements` TEXT NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "agentformelements` CHANGE `rank` `agentformelements_rank` INT( 4 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "agentformelements` CHANGE `required` `agentformelements_required` CHAR( 3 ) NOT NULL ";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "agentformelements` ADD `agentformelements_display_priv` INT4 NOT NULL";
		$sql_insert[] = "ALTER TABLE `" . $config['table_prefix'] . "agentformelements` DROP PRIMARY KEY , ADD PRIMARY KEY ( `agentformelements_id` ) ";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "agenmtformelements ADD COLUMN agentformelements_tool_tip TEXT NOT NULL";
		// Update for 2.1
		// Add new user fields.
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_site_config CHAR(3) NOT NULL DEFAULT 'no'";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_member_template CHAR(3) NOT NULL DEFAULT 'no'";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_agent_template CHAR(3) NOT NULL DEFAULT 'no'";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_listing_template CHAR(3) NOT NULL DEFAULT 'no'";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_have_files CHAR(3) NOT NULL DEFAULT 'no'";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_have_user_files CHAR(3) NOT NULL DEFAULT 'no'";
		// Populate new fields
		$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET userdb_can_edit_member_template = 'yes',userdb_can_edit_agent_template = 'yes',userdb_can_edit_listing_template = 'yes' WHERE userdb_can_edit_forms = 'yes'";
		$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET userdb_can_have_files = 'no',userdb_can_have_user_files = 'no'";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb DROP COLUMN userdb_can_edit_forms";
		// Add new user fields
		$sql_insert[] = "ALTER TABLE  " . $config['table_prefix'] . "userdb ADD COLUMN userdb_user_first_name CHAR VARYING(100) NOT NULL default ''";
		$sql_insert[] = "ALTER TABLE  " . $config['table_prefix'] . "userdb ADD COLUMN userdb_user_last_name CHAR VARYING(100) NOT NULL default ''";
		// Update userdbd with new export option
		$sql_insert[] = "ALTER TABLE  " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_export_listings CHAR VARYING(100) NOT NULL default ''";
		$sql_insert[] = "ALTER TABLE  " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_expiration CHAR VARYING(100) NOT NULL default ''";
		// Add New permissions for edit_all_listings and edit_all_users.
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_all_users CHAR(3) NOT NULL DEFAULT 'no'";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_all_listings CHAR(3) NOT NULL DEFAULT 'no'";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "agentformelements MODIFY COLUMN agentformelements_field_name CHAR VARYING(80) NOT NULL";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsdbelements MODIFY COLUMN listingsdbelements_field_name CHAR VARYING(80) NOT NULL";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsformelements MODIFY COLUMN listingsformelements_field_name CHAR VARYING(80) NOT NULL";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "memberformelements MODIFY COLUMN memberformelements_field_name CHAR VARYING(80) NOT NULL";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_email_verified CHAR(3) NOT NULL DEFAULT 'no'";
		
		while (list($elementIndexValue, $elementContents) = each($sql_insert)) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) echo ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
		}
		$sql_insert = array();
		echo 'Creating Property Class Tables<br />';
		if (strpos($_SESSION['db_type'], 'postgres') !== false) {
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . "classformelements (
							  classformelements_id SERIAL NOT NULL,
							  class_id INT4 NOT NULL,
							  listingsformelements_id INT4 NOT NULL,
							  PRIMARY KEY(classformelements_id)
							);";
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . "classlistingsdb (
								  classlistingsdb_id SERIAL NOT NULL,
								  class_id INT4 NOT NULL,
								  listingsdb_id INT4 NOT NULL,
								  PRIMARY KEY(classlistingsdb_id)
								);";
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "class (
								  class_id SERIAL NOT NULL,
								  class_name CHAR VARYING(80) NOT NULL,
								  class_rank INT2 NOT NULL,
								  PRIMARY KEY(class_id)
								);";
		}else {
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . "classformelements (
							  classformelements_id INT4 NOT NULL AUTO_INCREMENT,
							  class_id INT4 NOT NULL,
							  listingsformelements_id INT4 NOT NULL,
							  PRIMARY KEY(classformelements_id)
							);";
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . "classlistingsdb (
								  classlistingsdb_id INT4 NOT NULL AUTO_INCREMENT,
								  class_id INT4 NOT NULL,
								  listingsdb_id INT4 NOT NULL,
								  PRIMARY KEY(classlistingsdb_id)
								);";
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "class (
								  class_id INT4 NOT NULL AUTO_INCREMENT,
								  class_name CHAR VARYING(80) NOT NULL,
								  class_rank INT2 NOT NULL,
								  PRIMARY KEY(class_id)
								);";
		}
		foreach($sql_insert as $elementContents) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet == false) die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
		}
		$sql_insert = array();
		echo 'Starting Property Class Conversion.<br />';
		// Get current Listing Types
		$sql = "SELECT listingsformelements_field_elements FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_field_name = 'type'";
		$recordSet = $conn->execute($sql);
		if ($recordSet == false) {
			die('FATAL ERROR IN CLASS CONVERSION');
		}
		$i = 1;
		$class_names = explode('||', $recordSet->fields['listingsformelements_field_elements']);
		foreach ($class_names as $class_name) {
			$classes[$i] = $class_name;
			$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "class VALUES ($i,'$class_name',$i)";
			$i++;
			$recordSet->MoveNext();
		}
		// Get a list of all the type fields with field vlaue and listingid
		$sql = "SELECT listingsdbelements_field_value, listingsdb_id FROM " . $config['table_prefix'] . "listingsdbelements WHERE listingsdbelements_field_name = 'type'";
		$recordSet = $conn->execute($sql);
		if ($recordSet == false) {
			die('FATAL ERROR IN CLASS CONVERSION');
		}
		$classes = array_flip($classes);
		while (!$recordSet->EOF) {
			$listing_id = $recordSet->fields['listingsdb_id'];
			$class_id = $classes[$recordSet->fields['listingsdbelements_field_value']];
			$sql_insert[] = "INSERT INTO " . $config['table_prefix_no_lang'] . "classlistingsdb (class_id,listingsdb_id)VALUES ($class_id,$listing_id)";
			$recordSet->MoveNext();
		}
		// Add each formelement to each property class.
		// Get a list of all the type fields with field vlaue and listingid
		$sql = "SELECT listingsformelements_id FROM " . $config['table_prefix'] . "listingsformelements";
		$recordSet = $conn->execute($sql);
		if ($recordSet == false) {
			die('FATAL ERROR IN CLASS CONVERSION');
		} while (!$recordSet->EOF) {
			$listingsformelements_id = $recordSet->fields['listingsformelements_id'];
			foreach ($classes as $class_id) {
				$sql_insert[] = "INSERT INTO " . $config['table_prefix_no_lang'] . "classformelements (class_id,listingsformelements_id)VALUES ($class_id,$listingsformelements_id)";
			}
			$recordSet->MoveNext();
		}
		// Delete Old tpe field and all type field elements
		$sql_insert[] = "DELETE FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_field_name = 'type'";
		$sql_insert[] = "DELETE FROM " . $config['table_prefix'] . "listingsdbelements WHERE listingsdbelements_field_name = 'type'";
		// Add new field for notify member option
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "usersavedsearches ADD COLUMN usersavedsearches_notify CHAR VARYING(3) NOT NULL DEFAULT 'no'";
		$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_property_classes CHAR(3) NOT NULL DEFAULT 'no'";
		foreach($sql_insert as $elementContents) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet == false) die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
		}
	}
	function create_index()
	{
		// this is the setup for the ADODB library
		include_once(dirname(__FILE__) . '/../../include/class/adodb/adodb.inc.php');
		$conn = &ADONewConnection($_SESSION['db_type']);
		$conn->PConnect($_SESSION['db_server'], $_SESSION['db_user'], $_SESSION['db_password'], $_SESSION['db_database']);
		$config['table_prefix'] = $_SESSION['table_prefix'] . $_SESSION['or_install_lang'] . '_';
		$config['table_prefix_no_lang'] = $_SESSION['table_prefix'];
		// Create Indexes -- CHANGED made into an array to increase speed.
		// Create Indexes -- CHANGED made into an array to increase speed.
		$sql_insert[] = "CREATE INDEX idx_user_name ON " . $config['table_prefix'] . "userdb (userdb_user_name);";
		$sql_insert[] = "CREATE INDEX idx_active ON " . $config['table_prefix'] . "listingsdb (listingsdb_active);";
		$sql_insert[] = "CREATE INDEX idx_user ON " . $config['table_prefix'] . "listingsdb (userdb_id);";
		$sql_insert[] = "CREATE INDEX idx_name ON " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_field_name);";
		// CHANGED: blob or text fields can only index a max of 255 (mysql) and you have to specify
		if ($_SESSION['db_type'] == 'mysql') {
			$sql_insert[] = "CREATE INDEX idx_value ON " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_field_value(255));";
		}else {
			$sql_insert[] = "CREATE INDEX idx_value ON " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_field_value);";
		}
		$sql_insert[] = "CREATE INDEX idx_images_listing_id ON " . $config['table_prefix'] . "listingsimages (listingsdb_id);";
		$sql_insert[] = "CREATE INDEX idx_searchable ON " . $config['table_prefix'] . "listingsformelements (listingsformelements_searchable);";
		$sql_insert[] = "CREATE INDEX idx_mlsexport ON " . $config['table_prefix'] . "listingsdb (listingsdb_mlsexport);";
		$sql_insert[] = "CREATE INDEX idx_listing_id ON " . $config['table_prefix'] . "listingsdbelements (listingsdb_id);";
		$sql_insert[] = "CREATE INDEX idx_field_type ON " . $config['table_prefix'] . "listingsformelements (listingsformelements_field_type);";
		$sql_insert[] = "CREATE INDEX idx_browse ON " . $config['table_prefix'] . "listingsformelements (listingsformelements_display_on_browse);";
		$sql_insert[] = "CREATE INDEX idx_field_name ON " . $config['table_prefix'] . "listingsformelements (listingsformelements_field_name);";
		$sql_insert[] = "CREATE INDEX idx_rank ON " . $config['table_prefix'] . "listingsformelements (listingsformelements_rank);";
		$sql_insert[] = "CREATE INDEX idx_search_rank ON " . $config['table_prefix'] . "listingsformelements (listingsformelements_search_rank);";
		$sql_insert[] = "CREATE INDEX idx_images_rank ON " . $config['table_prefix'] . "listingsimages (listingsimages_rank);";
		$sql_insert[] = "CREATE INDEX idx_forgot_email ON " . $config['table_prefix_no_lang'] . "forgot (forgot_email);";
		if ($_SESSION['db_type'] == 'mysql') {
			$sql_insert[] = "CREATE INDEX idx_user_field_value ON " . $config['table_prefix'] . "userdbelements (userdbelements_field_value(255));";
		}else {
			$sql_insert[] = "CREATE INDEX idx_user_field_value ON " . $config['table_prefix'] . "userdbelements (userdbelements_field_value);";
		}
		$sql_insert[] = "CREATE INDEX idx_user_field_name ON " . $config['table_prefix'] . "userdbelements (userdbelements_field_name);";
		$sql_insert[] = "CREATE INDEX idx_userdb_userid ON " . $config['table_prefix'] . "userdbelements (userdb_id);";
		while (list($elementIndexValue, $elementContents) = each($sql_insert)) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
		}
		echo 'Indexes Created<br />';
	}
	function insert_values()
	{
		// this is the setup for the ADODB library
		include_once(dirname(__FILE__) . '/../../include/class/adodb/adodb.inc.php');
		$conn = &ADONewConnection($_SESSION['db_type']);
		$conn->PConnect($_SESSION['db_server'], $_SESSION['db_user'], $_SESSION['db_password'], $_SESSION['db_database']);
		$config['table_prefix'] = $_SESSION['table_prefix'] . $_SESSION['or_install_lang'] . '_';
		$config['table_prefix_no_lang'] = $_SESSION['table_prefix'];
		// Insert Data
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "pagesmain (pagesmain_id, pagesmain_title, pagesmain_date, pagesmain_summary, pagesmain_full, pagesmain_no_visitors, pagesmain_complete,pagesmain_description,pagesmain_keywords) VALUES (1, 'Welcome', '17 August 2003', 'Welcome Page', '
		<table cellspacing=\"1\" cellpadding=\"1\" border=\"0\" style=\"width: 100%;\">
		<tbody>
		<tr>
		<td style=\"border-style: none; background-image: none; text-align: left; vertical-align: top;\"><br /></td>
		<td>
		{featured_listings_horizontal}
		<h3>Open-Realty&reg; v2.5</h3><br />
		<p>Open-Realty&reg; is a professional, open-source, web-based real estate listing management system written in PHP that is provided FREE by Transparent Technologies, Inc. Designed to be flexible, powerful, easy to install and maintain, Open-Realty&reg; has powered thousands of real estate web sites worldwide. Open-Realty&reg; is a mature application that is actively developed, and has been community supported since 2003.<br /><br /></p>
		<h3>Features:</h3>
		<ul>
		<li>All dynamically generated site-visitor content validates to XHTML 1.0 strict and CSS 2.0 standards.</li>
		<li>Graphical site administration interface.</li>
		<li>Built in WYSIWYG page editor for creating and modifying the content of key pages, including the one presently being viewed.</li>
		<li>Easy to use installer reduces installation time.</li>
		<li>Keeps property listings updated -- no special skills required to add, delete, or modify listings. </li>
		<li>Built-in photo management -- upload photos via a web browser. If photos are not uploaded for a property listing, a default &quot;photo not available&quot; image will be automatically displayed.</li>
		<li>Automatically creates thumbnails -- smaller versions of your photos are automatically created using the industry standard <a title=\"The GD Graphics Libray home page\" href=\"http://www.libgd.org\">GD Graphics Library</a>.</li>
		<li>Optional &quot;Search Engine Optimized&quot; (SEO) page links, and optimization features.</li>
		<li>Flexible database abstraction -- compatible with mySQL, postgres, MS SQL, and Oracle via. the <a title=\"ADODB lite home page\" href=\"http://adodblite.sourceforge.net/index.php\">ADODB lite</a> database library.</li>
		<li>Secure -- no one but the site Administrator or delegated Agents can modify or add listings.</li>
		<li>Showcase specific properties -- built-in featured listing management allows custom placement and arrangement of&nbsp; special properties. </li>
		<li>Flexible search -- Browse properties according to a definable criteria.</li>
		<li>Configurable search forms and fields -- All search forms and fields included in Open-Realty&reg; are completely definable to meet any needs.</li>
		<li>Template system -- Designers can produce a sophisticated functional site template without any knowledge of PHP.</li>
		<li>Integrated add-on system framework for application developers allows for extending or altering Open-Realty&reg;\'s capabilities without altering its source code, making upgrades to newer versions a snap.</li>
		<li>Powerful and professional <a href=\"http://www.open-realty.org/addons.html\" title=\"Commercial add-ons for Open-Realty&reg;\">commercial add-ons</a> available. </li>
		<li>Established <a href=\"http://www.open-realty.org\" title=\"Open-Realty&reg; support\">Community support forums</a>.</li>
		<li>Many, many, many more features.</li>
		</ul>
		<p>Open-Realty&reg; is written entirely in PHP, and utilizes a standards compliant XHTML template system, designing a new template from scratch or incorporating the look of an existing web site is greatly simplified.</p>
		</td>
		</tr>
		</tbody>
		</table>', 0, 1,'','');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "pagesmain (pagesmain_id, pagesmain_title, pagesmain_date, pagesmain_summary, pagesmain_full, pagesmain_no_visitors, pagesmain_complete,pagesmain_description,pagesmain_keywords) VALUES (2, 'Contact Us', '17 August 2003', 'contact us page', '<H3>Contact Open-Realty, Inc.</h3><br /><B>You can contact Us in a number of ways.</b> \r\n<P></p>\r\n<P>By Phone (07) 00000000<br />By Email <A href=\"mailto:admin@here.com\">admin@here.com</a><br />By Fax (00) 00000000<br />By Mail 100 Main Street, Anytown, QLD 0000</p>\r\n<P>We look forward to hearing from you!</p><br /><br />', 0, 1,'','');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "pagesmain (pagesmain_id, pagesmain_title, pagesmain_date, pagesmain_summary, pagesmain_full, pagesmain_no_visitors, pagesmain_complete,pagesmain_description,pagesmain_keywords) VALUES (3, 'About Us', '17 August 2003', 'About Us Page', '<FONT size=6>About Us</font><br /><br />This is the page where you tell clients about your company ', 0, 1,'','');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "pagesmain (pagesmain_id, pagesmain_title, pagesmain_date, pagesmain_summary, pagesmain_full, pagesmain_no_visitors, pagesmain_complete,pagesmain_description,pagesmain_keywords) VALUES (4, 'Legal Page', '17 August 2003', 'Legal Page', ' <p><font size=\"4\"><b>Legal Disclaimer</b></font></p><p align=\"center\"><strong>Use of this legal page is not suggested or reccomended., you should consult your lawyer to get proper legal disclaimers for your state/country.</strong> </p> <br />    <strong><i>Information Not Warranted or Guaranteed:</i></strong><br />     The official {company_name} website and all pages linked to it or from it, are PROVIDED ON AN \"AS IS, AS AVAILABLE\" BASIS. <span style=\"text-transform: uppercase;\">{company_name}</span> MAKES NO WARRANTIES, EXPRESSED OR IMPLIED, INCLUDING, WITHOUT LIMITATION, THOSE OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE, WITH RESPECT TO ANY INFORMATION OR USE OF INFORMATION CONTAINED IN THE WEBSITE, OR LINKED FROM IT OR TO IT.<br /><br />{company_name} does not warrant or guarantee the accuracy, adequacy, quality, currentness, completeness, or suitability of any information for any purpose; that any information will be free of infection from viruses, worms, Trojan horses or other destructive contamination; that the information presented will not be objectionable to some individuals or that this service will remain uninterrupted.<br /> <br /> <i><strong>No Liability:</strong></i><br /> {company_name}, its agents or employees shall not be held liable to anyone for any errors, omissions or inaccuracies under any circumstances. The entire risk for utilizing the information contained on this site or linked to this site rests solely with the users of this site.', 0, 1,'','');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix_no_lang'] . "controlpanel VALUES ('" . $this->version_number . "', '" . trim($_SESSION['basepath']) . "', '" . trim($_SESSION['baseurl']) . "', 'Administrator', 'changeme@default.com', NULL, NULL, 'title.jpg', 1, '" . $_SESSION['template'] . "', 'OR', 'listing_detail_default.html', 'view_user_default.html', 'search_result_default.html', '" . $_SESSION['or_install_lang'] . "', 10,4, 0, 0, '<a><b><i><u><br />', '$', 1, 0, 1, 1, 7, 100000, 700,700, 5, 100000, 700,700, 15, 900000, 3000, 'jpg,gif,png,egg', 1, 100, 1, 'gd','/usr/X11R6/bin/convert',1,100, 0, 365, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,-1,0, 0, 0,0,0,0, 0, 0, 1, 1,'image/pjpeg,image/jpeg,image/gif,image/x-png,application/octet-stream', '" . $_SESSION['or_install_lang'] . "',1,1,'google_us','address','','','','city','state','zip','','xinha',0,0,0,0,'listingsdb_id','ASC',0,0,0,'','','','','Open-Realty " . $this->version_number . "','Open-Realty " . $this->version_number . "','headline,top_left,top_right,center,feature1,feature2,bottom_left,bottom_right','vtour_default.html','400','250','800','480','70','0','','','','','','','','','','',0,0,0,'<br />','<br />',0,'Featured Listing Feed','RSS feed of our featured listings','<table><tr><td>{image_thumb_1}</td><td>{listing_field_full_desc}</td></tr></table>','Last Modified Listing Feed','RSS feed of our last modified listings','<table><tr><td>{image_thumb_1}</td><td>{listing_field_full_desc}</td></tr></table>','50','100','width','width','UTF-8','1','100','width','500','700','4','50','0','16','16','7','2097152','jpg,gif,png,egg,pdf,doc,swf,avi,mov,mpg,zip,sbd,stc,std,sti,stw,svw,sxc,sxd,sxg,sxi,sxm','1','1','both','0','0','7','2097152','1','0','-','100','none','DESC','price',5,1,'http://wiki.open-realty.org/Admin_guide','http://wiki.open-realty.org/Admin_create_new_listing','http://wiki.open-realty.org/Admin_edit_listings','http://wiki.open-realty.org/Admin_Edit_User','http://wiki.open-realty.org/Admin_user_manager','http://wiki.open-realty.org/Admin_Page_Editor','http://wiki.open-realty.org/Admin_modify_listing','http://wiki.open-realty.org/Admin_edit_images','http://wiki.open-realty.org/Admin_edit_vtour','http://wiki.open-realty.org/Admin_edit_files','http://wiki.open-realty.org/Agentmember_template_edit_add_field','http://wiki.open-realty.org/Agentmember_template_set_field_order','http://wiki.open-realty.org/Agentmember_template_edit_add_field','http://wiki.open-realty.org/Admin_edit_listing_template','http://wiki.open-realty.org/Listing_template_edit_add_field','http://wiki.open-realty.org/Listing_template_set_field_order','http://wiki.open-realty.org/Listing_template_search_setup','http://wiki.open-realty.org/Listing_template_search_results_setup','http://wiki.open-realty.org/Admin_property_classes','http://wiki.open-realty.org/Site_Configuration','http://wiki.open-realty.org/Admin_view_site_log','http://wiki.open-realty.org/TransparentMaps_configuration','http://wiki.open-realty.org/TransparentMaps_cron_jobs','http://wiki.open-realty.org/TransparentRETS_ug_config_server','http://wiki.open-realty.org/TransparentRETS_ug_class_settigns','http://wiki.open-realty.org/Admin_edit_agent_member_template','http://wiki.open-realty.org/Admin_edit_agent_member_template','http://wiki.open-realty.org/Property_class_insertmodify','http://wiki.open-realty.org/Property_class_insert','http://wiki.open-realty.org/Addon_IDXManager','http://wiki.open-realty.org/Addon_IDXManager','http://wiki.open-realty.org/Addon_csvloader_user_guide','http://wiki.open-realty.org/Agentmember_template_set_field_order','0','-1','0','','0','0','0');";
		while (list($elementIndexValue, $elementContents) = each($sql_insert)) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
		}
		$sql="SELECT controlpanel_date_format FROM " . $config['table_prefix_no_lang'] . "controlpanel";
				$recordSet=$conn->Execute($sql);
				$date_format=$recordSet->fields['controlpanel_date_format'];
				$sql='SELECT listingsformelements_field_name FROM ' . $config['table_prefix'] . 'listingsformelements WHERE listingsformelements_field_type = \'date\'';
				$recordSet=$conn->Execute($sql);
				while (!$recordSet->EOF)
				{
				$field_name=$recordSet->fields['listingsformelements_field_name'];
				$sql2='SELECT listingsdbelements_field_value, listingsdbelements_id FROM ' . $config['table_prefix'] . 'listingsdbelements WHERE listingsdbelements_field_name = \'' .$field_name.'\'';
				$recordSet2=$conn->Execute($sql2);
				while (!$recordSet2->EOF)
				{
				$field_value = $recordSet2->fields['listingsdbelements_field_value'];
				$id = $recordSet2->fields['listingsdbelements_id'];
				if ($date_format==1) {
					$format="%m/%d/%Y";
					}
				elseif ($date_format==2) {
					$format="%Y/%d/%m";
					}
				elseif ($date_format==3) {
					$format="%d/%m/%Y";
					}
				$returnValue=$this->parseDate($field_value,$format);
				$sql3= "UPDATE " . $config['table_prefix'] . "listingsdbelements SET listingsdbelements_field_value = '" .$returnValue. "' WHERE listingsdbelements_id = " .$id;
				$recordSet3=$conn->Execute($sql3);
				$recordSet2->MoveNext();
				}
				$recordSet->MoveNext();
				}
		echo 'Tables Populated<br />';
	}

	function load_version()
	{
		$this->load_lang($_SESSION['or_install_lang']);
		switch ($_GET['step']) {
			case 4:
				$this->get_new_settings();
				break;
			case 5:
				$_SESSION['table_prefix'] = trim($_POST['table_prefix']);
				$_SESSION['db_type'] = trim($_POST['db_type']);
				$_SESSION['db_user'] = trim($_POST['db_user']);
				$_SESSION['db_password'] = trim($_POST['db_password']);
				$_SESSION['db_database'] = trim($_POST['db_database']);
				$_SESSION['db_server'] = trim($_POST['db_server']);
				$_SESSION['basepath'] = trim($_POST['basepath']);
				$_SESSION['baseurl'] = trim($_POST['baseurl']);
				$_SESSION['devel_mode'] = $_POST['devel_mode'];
				$_SESSION['template'] = 'lazuli';
				$this->write_config();
				break;
			case 6:
				$this->update_tables();
				$this->create_tables();
				$this->create_index();
				$this->insert_values();
				echo '<br /><strong>' . $this->lang['install_installation_complete'] . ' <a href="../admin/index.php?action=configure">' . $this->lang['install_configure_installation'] . '</a></strong>';
				break;
		}
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
}

?>