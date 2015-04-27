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
		// include_once('../include/common.php');
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
			  controlpanel_agent_default_havefiles INT(2) NOT NULL,
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
			  controlpanel_blog_requires_moderation INT2 NOT NULL,
			  controlpanel_maintenance_mode INT2 NOT NULL,
	  		  controlpanel_notification_last_timestamp TIMESTAMP NOT NULL DEFAULT now(),
	  		  controlpanel_notify_listings_template CHAR VARYING(45) NOT NULL,
			  PRIMARY KEY(controlpanel_version)
			);";
		$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . "addons (
			  addons_version CHAR VARYING(15) NOT NULL,
			  addons_name CHAR VARYING(150) NOT NULL,
			  PRIMARY KEY(addons_name)
			);";
		if (strpos($_SESSION['db_type'], 'postgres') !== false) {

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
			  zipdist_id SERIAL NOT NULL,
			  PRIMARY KEY  (zipdist_id)
			);";
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "activitylog (
				activitylog_id SERIAL NOT NULL,
				activitylog_log_date TIMESTAMP NOT NULL,
				userdb_id INT4 NOT NULL,
				activitylog_action CHAR VARYING(150) NOT NULL,
				activitylog_ip_address CHAR VARYING(15) NOT NULL,
				PRIMARY KEY(activitylog_id)
				);";
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "agentformelements (
				  agentformelements_id SERIAL NOT NULL,
				  agentformelements_field_type CHAR VARYING(20) NOT NULL,
				  agentformelements_field_name CHAR VARYING(80) NOT NULL,
				  agentformelements_field_caption CHAR VARYING(80) NOT NULL,
				  agentformelements_default_text TEXT NOT NULL,
				  agentformelements_field_elements TEXT NOT NULL,
				  agentformelements_rank INT4 NOT NULL,
				  agentformelements_required CHAR(3) NOT NULL,
				  agentformelements_display_priv INT4 NOT NULL,
				  agentformelements_tool_tip TEXT NOT NULL,
				  PRIMARY KEY(agentformelements_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "listingsdb (
				  listingsdb_id SERIAL NOT NULL,
				  userdb_id INT4 NOT NULL,
				  listingsdb_title TEXT NOT NULL,
				  listingsdb_expiration DATE NOT NULL,
				  listingsdb_notes TEXT NOT NULL,
				  listingsdb_creation_date DATE NOT NULL,
				  listingsdb_last_modified DATETIME NOT NULL,
				  listingsdb_hit_count INT4 NOT NULL,
				  listingsdb_featured CHAR(3) NOT NULL,
				  listingsdb_active CHAR(3) NOT NULL,
				  listingsdb_mlsexport CHAR(3) NOT NULL,
				  PRIMARY KEY(listingsdb_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "listingsdbelements (
				  listingsdbelements_id SERIAL NOT NULL,
				  listingsdbelements_field_name CHAR VARYING(80) NOT NULL,
				  listingsdbelements_field_value TEXT NOT NULL,
				  listingsdb_id INT4 NOT NULL,
				  userdb_id INT4 NOT NULL,
				  PRIMARY KEY(listingsdbelements_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "listingsformelements (
				  listingsformelements_id SERIAL NOT NULL,
				  listingsformelements_field_type CHAR VARYING(20) NOT NULL,
				  listingsformelements_field_name CHAR VARYING(80) NOT NULL,
				  listingsformelements_field_caption CHAR VARYING(80) NOT NULL,
				  listingsformelements_default_text TEXT NOT NULL,
				  listingsformelements_field_elements TEXT NOT NULL,
				  listingsformelements_rank INT4 NOT NULL,
				  listingsformelements_search_rank INT4 NOT NULL,
				  listingsformelements_search_result_rank INT4 NOT NULL,
				  listingsformelements_required CHAR(3) NOT NULL,
				  listingsformelements_location CHAR VARYING(50) NOT NULL,
				  listingsformelements_display_on_browse CHAR(3) NOT NULL,
				  listingsformelements_searchable INT4 NOT NULL,
				  listingsformelements_search_type CHAR VARYING(50) NULL,
				  listingsformelements_search_label CHAR VARYING(50) NULL,
				  listingsformelements_search_step CHAR VARYING(25) NULL,
				  listingsformelements_display_priv INT4 NOT NULL,
				  listingsformelements_field_length INT4 NULL,
				  listingsformelements_tool_tip TEXT NULL,
				  PRIMARY KEY(listingsformelements_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "listingsimages (
				  listingsimages_id SERIAL NOT NULL,
				  userdb_id INT4 NOT NULL,
				  listingsimages_caption CHAR VARYING(255) NOT NULL,
				  listingsimages_file_name CHAR VARYING(255) NOT NULL,
				  listingsimages_thumb_file_name CHAR VARYING(255) NOT NULL,
				  listingsimages_description TEXT NOT NULL,
				  listingsdb_id INT4 NOT NULL,
				  listingsimages_rank INT4 NOT NULL,
				  listingsimages_active CHAR(3) NOT NULL,
				  PRIMARY KEY(listingsimages_id)
				);";

			$sql_insert[] = "CREATE TABLE  " . $config['table_prefix'] . "listingsfiles (
				  listingsfiles_id SERIAL NOT NULL,
				  userdb_id INT4 NOT NULL,
				  listingsfiles_caption varchar(255) NOT NULL,
				  listingsfiles_file_name varchar(255) NOT NULL,
				  listingsfiles_description TEXT NOT NULL,
				  listingsdb_id INT4 NOT NULL,
				  listingsfiles_rank INT4 NOT NULL,
				  listingsfiles_active char(3) NOT NULL,
				  PRIMARY KEY (listingsfiles_id)
				);";

			$sql_insert[] = "CREATE TABLE  " . $config['table_prefix'] . "usersfiles (
				  usersfiles_id SERIAL NOT NULL,
				  userdb_id INT4 NOT NULL,
				  usersfiles_caption varchar(255) NOT NULL,
				  usersfiles_file_name varchar(255) NOT NULL,
				  usersfiles_description TEXT NOT NULL,
				  listingsdb_id INT4 NOT NULL,
				  usersfiles_rank INT4 NOT NULL,
				  usersfiles_active char(3) NOT NULL,
				  PRIMARY KEY (usersfiles_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "vtourimages (
				  vtourimages_id SERIAL NOT NULL,
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

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "memberformelements (
				  memberformelements_id SERIAL NOT NULL,
				  memberformelements_field_type CHAR VARYING(20) NOT NULL,
				  memberformelements_field_name CHAR VARYING(80) NOT NULL,
				  memberformelements_field_caption CHAR VARYING(80) NOT NULL,
				  memberformelements_default_text TEXT NOT NULL,
				  memberformelements_field_elements TEXT NOT NULL,
				  memberformelements_rank INT4 NOT NULL,
				  memberformelements_required CHAR(3) NOT NULL,
				  memberformelements_display_priv INT4 NOT NULL DEFAULT 0,
				  memberformelements_tool_tip TEXT NOT NULL,
				  PRIMARY KEY(memberformelements_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "pagesmain (
				  pagesmain_id SERIAL NOT NULL,
				  pagesmain_title TEXT NOT NULL,
				  pagesmain_date CHAR VARYING(20) NOT NULL,
				  pagesmain_summary CHAR VARYING(255) NOT NULL,
				  pagesmain_full TEXT NOT NULL,
				  pagesmain_no_visitors INT4 NOT NULL,
				  pagesmain_complete INT2 NOT NULL,
				  pagesmain_description TEXT NOT NULL,
				  pagesmain_keywords TEXT NOT NULL,
				  PRIMARY KEY(pagesmain_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "userdb (
				 userdb_id SERIAL NOT NULL,
				  userdb_user_name CHAR VARYING(80) NOT NULL,
				  userdb_emailaddress CHAR VARYING(80) NOT NULL,
				  userdb_user_first_name CHAR VARYING(100) NOT NULL,
				  userdb_user_last_name CHAR VARYING(100) NOT NULL,
				  userdb_comments TEXT NOT NULL,
				  userdb_user_password CHAR VARYING(50) NOT NULL,
				  userdb_is_admin CHAR(3) NOT NULL,
				  userdb_can_edit_site_config CHAR(3) NOT NULL,
				  userdb_can_edit_member_template CHAR(3) NOT NULL,
				  userdb_can_edit_agent_template CHAR(3) NOT NULL,
				  userdb_can_edit_listing_template CHAR(3) NOT NULL,
				  userdb_creation_date DATE NOT NULL,
				  userdb_can_feature_listings CHAR(3) NOT NULL,
				  userdb_can_view_logs CHAR(3) NOT NULL,
				  userdb_last_modified TIMESTAMP NOT NULL,
				  userdb_hit_count INT4 NOT NULL,
				  userdb_can_moderate CHAR(3) NOT NULL,
				  userdb_can_edit_pages CHAR(3) NOT NULL,
				  userdb_can_have_vtours CHAR(3) NOT NULL,
				  userdb_is_agent CHAR(3) NOT NULL,
				  userdb_active CHAR(3) NOT NULL,
				  userdb_limit_listings INT4 NOT NULL,
				  userdb_can_edit_expiration CHAR VARYING(100) NOT NULL,
				  userdb_can_export_listings CHAR VARYING(100) NOT NULL,
				  userdb_can_edit_all_users CHAR(3) NOT NULL,
				  userdb_can_edit_all_listings CHAR(3) NOT NULL,
				  userdb_can_edit_property_classes CHAR(3) NOT NULL,
				  userdb_can_have_files CHAR(3) NOT NULL,
				  userdb_can_have_user_files CHAR(3) NOT NULL,
				  userdb_blog_user_type INT4 NOT NULL DEFAULT 1,
				  userdb_can_manage_addons CHAR(3) NOT NULL,
				  userdb_rank INT4 NOT NULL,
				  userdb_featuredlistinglimit INT4 NOT NULL,
				  userdb_email_verified CHAR(3) NOT NULL,
				  PRIMARY KEY(userdb_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "userdbelements (

				  userdbelements_id SERIAL NOT NULL,
				  userdbelements_field_name CHAR VARYING(80) NOT NULL,
				  userdbelements_field_value TEXT NOT NULL,
				  userdb_id INT4 NOT NULL,
				  PRIMARY KEY(userdbelements_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "userfavoritelistings (
				  userfavoritelistings_id SERIAL NOT NULL,
				  userdb_id INT4 NOT NULL,
				  listingsdb_id INT4 NOT NULL,
				  PRIMARY KEY(userfavoritelistings_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "userimages (
				  userimages_id SERIAL NOT NULL,
				  userdb_id INT4 NOT NULL,
				  userimages_caption CHAR VARYING(255) NOT NULL,
				  userimages_file_name CHAR VARYING(255) NOT NULL,
				  userimages_thumb_file_name CHAR VARYING(255) NOT NULL,
				  userimages_description TEXT NOT NULL,
				  userimages_rank INT4 NOT NULL,
				  userimages_active CHAR(3) NOT NULL,
				  PRIMARY KEY(userimages_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "usersavedsearches (
				  usersavedsearches_id SERIAL NOT NULL,
				  userdb_id INT4 NOT NULL,
				  usersavedsearches_title CHAR VARYING(255) NOT NULL,
				  usersavedsearches_query_string TEXT NOT NULL,
				  usersavedsearches_last_viewed TIMESTAMP NOT NULL,
				  usersavedsearches_new_listings INT2 NOT NULL,
				  usersavedsearches_notify CHAR VARYING(3) NOT NULL,
				  PRIMARY KEY(usersavedsearches_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . "forgot (
				  forgot_id SERIAL NOT NULL,
				  forgot_rand INT4 NOT NULL,
				  forgot_email CHAR VARYING(45) NOT NULL,
				  forgot_time TIMESTAMP NOT NULL,
				  PRIMARY KEY(forgot_id)
				);";
			// Create Tables for property classing
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
		} else {
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
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "activitylog (
				  activitylog_id INT4 NOT NULL AUTO_INCREMENT,
				  activitylog_log_date TIMESTAMP NOT NULL,
				  userdb_id INT4 NOT NULL,
				  activitylog_action CHAR VARYING(150) NOT NULL,
				  activitylog_ip_address CHAR VARYING(15) NOT NULL,
				  PRIMARY KEY(activitylog_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "agentformelements (
				  agentformelements_id INT4 NOT NULL AUTO_INCREMENT,
				  agentformelements_field_type CHAR VARYING(20) NOT NULL,
				  agentformelements_field_name CHAR VARYING(80) NOT NULL,
				  agentformelements_field_caption CHAR VARYING(80) NOT NULL,
				  agentformelements_default_text TEXT NOT NULL,
				  agentformelements_field_elements TEXT NOT NULL,
				  agentformelements_rank INT4 NOT NULL,
				  agentformelements_required CHAR(3) NOT NULL,
				  agentformelements_display_priv INT4 NOT NULL,
				  agentformelements_tool_tip TEXT NULL,
				  PRIMARY KEY(agentformelements_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "listingsdb (
				  listingsdb_id INT4 NOT NULL AUTO_INCREMENT,
				  userdb_id INT4 NOT NULL,
				  listingsdb_title TEXT NOT NULL,
				  listingsdb_expiration DATE NOT NULL,
				  listingsdb_notes TEXT NOT NULL,
				  listingsdb_creation_date DATE NOT NULL,
				  listingsdb_last_modified DATETIME NOT NULL,
				  listingsdb_hit_count INT4 NOT NULL,
				  listingsdb_featured CHAR(3) NOT NULL,
				  listingsdb_active CHAR(3) NOT NULL,
				  listingsdb_mlsexport CHAR(3) NOT NULL,
				  PRIMARY KEY(listingsdb_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "listingsdbelements (
				  listingsdbelements_id INT4 NOT NULL AUTO_INCREMENT,
				  listingsdbelements_field_name CHAR VARYING(80) NOT NULL,
				  listingsdbelements_field_value TEXT NOT NULL,
				  listingsdb_id INT4 NOT NULL,
				  userdb_id INT4 NOT NULL,
				  PRIMARY KEY(listingsdbelements_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "listingsformelements (
				  listingsformelements_id INT4 NOT NULL AUTO_INCREMENT,
				  listingsformelements_field_type CHAR VARYING(20) NOT NULL,
				  listingsformelements_field_name CHAR VARYING(80) NOT NULL,
				  listingsformelements_field_caption CHAR VARYING(80) NOT NULL,
				  listingsformelements_default_text TEXT NOT NULL,
				  listingsformelements_field_elements TEXT NOT NULL,
				  listingsformelements_rank INT4 NOT NULL,
				  listingsformelements_search_rank INT4 NOT NULL,
				  listingsformelements_search_result_rank INT4 NOT NULL,
				  listingsformelements_required CHAR(3) NOT NULL,
				  listingsformelements_location CHAR VARYING(50) NOT NULL,
				  listingsformelements_display_on_browse CHAR(3) NOT NULL,
				  listingsformelements_searchable INT4 NOT NULL,
				  listingsformelements_search_type CHAR VARYING(50) NULL,
				  listingsformelements_search_label CHAR VARYING(50) NULL,
				  listingsformelements_search_step CHAR VARYING(25) NULL,
				  listingsformelements_display_priv INT4 NOT NULL,
				  listingsformelements_field_length INT4 NULL,
				  listingsformelements_tool_tip TEXT NULL,
				  PRIMARY KEY(listingsformelements_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "listingsimages (
				  listingsimages_id INT4 NOT NULL AUTO_INCREMENT,
				  userdb_id INT4 NOT NULL,
				  listingsimages_caption CHAR VARYING(255) NOT NULL,
				  listingsimages_file_name CHAR VARYING(255) NOT NULL,
				  listingsimages_thumb_file_name CHAR VARYING(255) NOT NULL,
				  listingsimages_description TEXT NOT NULL,
				  listingsdb_id INT4 NOT NULL,
				  listingsimages_rank INT4 NOT NULL,
				  listingsimages_active CHAR(3) NOT NULL,
				  PRIMARY KEY(listingsimages_id)
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
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "vtourimages (
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

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "memberformelements (
				  memberformelements_id INT4 NOT NULL AUTO_INCREMENT,
				  memberformelements_field_type CHAR VARYING(20) NOT NULL,
				  memberformelements_field_name CHAR VARYING(80) NOT NULL,
				  memberformelements_field_caption CHAR VARYING(80) NOT NULL,
				  memberformelements_default_text TEXT NOT NULL,
				  memberformelements_field_elements TEXT NOT NULL,
				  memberformelements_rank INT4 NOT NULL,
				  memberformelements_required CHAR(3) NOT NULL,
				  memberformelements_tool_tip TEXT NULL,
				  PRIMARY KEY(memberformelements_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "pagesmain (
				  pagesmain_id INT4 NOT NULL AUTO_INCREMENT,
				  pagesmain_title TEXT NOT NULL,
				  pagesmain_date CHAR VARYING(20) NOT NULL,
				  pagesmain_summary CHAR VARYING(255) NOT NULL,
				  pagesmain_full LONGTEXT NOT NULL,
				  pagesmain_no_visitors INT4 NOT NULL,
				  pagesmain_complete INT2 NOT NULL,
				  pagesmain_description LONGTEXT NOT NULL,
				  pagesmain_keywords LONGTEXT NOT NULL,
				  PRIMARY KEY(pagesmain_id)
				);";
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "userdb (
				  userdb_id INT4 NOT NULL AUTO_INCREMENT,
				  userdb_user_name CHAR VARYING(80) NOT NULL,
				  userdb_emailaddress CHAR VARYING(80) NOT NULL,
				  userdb_user_first_name CHAR VARYING(100) NOT NULL,
				  userdb_user_last_name CHAR VARYING(100) NOT NULL,
				  userdb_comments TEXT NOT NULL,
				  userdb_user_password CHAR VARYING(50) NOT NULL,
				  userdb_is_admin CHAR(3) NOT NULL,
				  userdb_can_edit_site_config CHAR(3) NOT NULL,
				  userdb_can_edit_member_template CHAR(3) NOT NULL,
				  userdb_can_edit_agent_template CHAR(3) NOT NULL,
				  userdb_can_edit_listing_template CHAR(3) NOT NULL,
				  userdb_creation_date DATE NOT NULL,
				  userdb_can_feature_listings CHAR(3) NOT NULL,
				  userdb_can_view_logs CHAR(3) NOT NULL,
				  userdb_last_modified TIMESTAMP NOT NULL,
				  userdb_hit_count INT4 NOT NULL,
				  userdb_can_moderate CHAR(3) NOT NULL,
				  userdb_can_edit_pages CHAR(3) NOT NULL,
				  userdb_can_have_vtours CHAR(3) NOT NULL,
				  userdb_is_agent CHAR(3) NOT NULL,
				  userdb_active CHAR(3) NOT NULL,
				  userdb_limit_listings INT4 NOT NULL,
				  userdb_can_edit_expiration CHAR VARYING(100) NOT NULL,
				  userdb_can_export_listings CHAR VARYING(100) NOT NULL,
				  userdb_can_edit_all_users CHAR(3) NOT NULL,
				  userdb_can_edit_all_listings CHAR(3) NOT NULL,
				  userdb_can_edit_property_classes CHAR(3) NOT NULL,
				  userdb_can_have_files CHAR(3) NOT NULL,
				  userdb_can_have_user_files CHAR(3) NOT NULL,
				  userdb_blog_user_type INT4 NOT NULL DEFAULT 1,
				  userdb_can_manage_addons CHAR(3) NOT NULL,
				  userdb_rank INT4 NOT NULL,
				  userdb_featuredlistinglimit INT4 NOT NULL,
				  userdb_email_verified CHAR(3) NOT NULL,
				  PRIMARY KEY(userdb_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "userdbelements (
				  userdbelements_id INT4 NOT NULL AUTO_INCREMENT,
				  userdbelements_field_name CHAR VARYING(80) NOT NULL,
				  userdbelements_field_value TEXT NOT NULL,
				  userdb_id INT4 NOT NULL,
				  PRIMARY KEY(userdbelements_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "userfavoritelistings (
				  userfavoritelistings_id INT4 NOT NULL AUTO_INCREMENT,
				  userdb_id INT4 NOT NULL,
				  listingsdb_id INT4 NOT NULL,
				  PRIMARY KEY(userfavoritelistings_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "userimages (
				  userimages_id INT4 NOT NULL AUTO_INCREMENT,
				  userdb_id INT4 NOT NULL,
				  userimages_caption CHAR VARYING(255) NOT NULL,
				  userimages_file_name CHAR VARYING(255) NOT NULL,
				  userimages_thumb_file_name CHAR VARYING(255) NOT NULL,
				  userimages_description TEXT NOT NULL,
				  userimages_rank INT4 NOT NULL,
				  userimages_active CHAR(3) NOT NULL,
				  PRIMARY KEY(userimages_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "usersavedsearches (
				  usersavedsearches_id INT4 NOT NULL AUTO_INCREMENT,
				  userdb_id INT4 NOT NULL,
				  usersavedsearches_title CHAR VARYING(255) NOT NULL,
				  usersavedsearches_query_string TEXT NOT NULL,
				  usersavedsearches_last_viewed TIMESTAMP NOT NULL,
				  usersavedsearches_new_listings INT2 NOT NULL,
				  usersavedsearches_notify CHAR VARYING(3) NOT NULL,
				  PRIMARY KEY(usersavedsearches_id)
				);";

			$sql_insert[] = "CREATE TABLE " . $config['table_prefix_no_lang'] . "forgot (
				  forgot_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
				  forgot_rand INTEGER UNSIGNED NOT NULL,
				  forgot_email CHAR VARYING(45) NOT NULL,
				  forgot_time TIMESTAMP NOT NULL,
				  PRIMARY KEY(forgot_id)
				);";
			// Create Tables for property classing
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
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "blogmain (
				  blogmain_id INT4 NOT NULL AUTO_INCREMENT,
				  userdb_id INT4 NOT NULL,
				  blogmain_title TEXT NOT NULL,
				  blogmain_date CHAR VARYING(20) NOT NULL,
				  blogmain_full LONGTEXT NOT NULL,
				  blogmain_description LONGTEXT NOT NULL,
				  blogmain_keywords LONGTEXT NOT NULL,
				  blogmain_published INT4 NOT NULL,
				  PRIMARY KEY(blogmain_id)
				);";
			$sql_insert[] = "CREATE TABLE " . $config['table_prefix'] . "blogcomments (
				  blogcomments_id INT4 NOT NULL AUTO_INCREMENT,
				  blogmain_id INT4 NOT NULL,
				  userdb_id INT4 NULL,
				  blogcomments_timestamp INT4 NOT NULL,
				  blogcomments_text LONGTEXT NOT NULL,
				  blogcomments_moderated BOOLEAN,
				  PRIMARY KEY(blogcomments_id)
				);";

		}
		 while (list($elementIndexValue, $elementContents) = each($sql_insert)) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) {
				if ($_SESSION['devel_mode'] == 'no') {
					die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
				}else {
					echo "<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />";
				}
			}
		}
		echo 'Tables Created<br />';
	}
	function update_tables()
	{
	}
	function create_index()
	{
		// this is the setup for the ADODB library
		include_once(dirname(__FILE__) . '/../../include/class/adodb/adodb.inc.php');
		$conn = &ADONewConnection($_SESSION['db_type']);
		$conn->PConnect($_SESSION['db_server'], $_SESSION['db_user'], $_SESSION['db_password'], $_SESSION['db_database']);
		$config['table_prefix'] = $_SESSION['table_prefix'] . $_SESSION['or_install_lang'] . '_';
		$config['table_prefix_no_lang'] = $_SESSION['table_prefix'];

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

		$sql_insert[] = "CREATE INDEX idx_classformelements_class_id ON " . $config['table_prefix_no_lang'] . "classformelements (class_id);";
		$sql_insert[] = "CREATE INDEX idx_classformelements_listingsformelements_id ON " . $config['table_prefix_no_lang'] . "classformelements (listingsformelements_id);";

		$sql_insert[] = "CREATE INDEX idx_classlistingsdb_class_id ON " . $config['table_prefix_no_lang'] . "classlistingsdb (class_id);";
		$sql_insert[] = "CREATE INDEX idx_classlistingsdb_listingsdb_id ON " . $config['table_prefix_no_lang'] . "classlistingsdb (listingsdb_id);";

		$sql_insert[] = "CREATE INDEX idx_class_rank ON " . $config['table_prefix'] . "class (class_rank);";
		//Add indexes for userdbelements tables
		// CHANGED: blob or text fields can only index a max of 255 (mysql) and you have to specify
		if ($_SESSION['db_type'] == 'mysql') {
			$sql_insert[] = "CREATE INDEX idx_user_field_value ON " . $config['table_prefix'] . "userdbelements (userdbelements_field_value(255));";
		}else {
			$sql_insert[] = "CREATE INDEX idx_user_field_value ON " . $config['table_prefix'] . "userdbelements (userdbelements_field_value);";
		}
		$sql_insert[] = "CREATE INDEX idx_user_field_name ON " . $config['table_prefix'] . "userdbelements (userdbelements_field_name);";
		$sql_insert[] = "CREATE INDEX idx_userdb_userid ON " . $config['table_prefix'] . "userdbelements (userdb_id);";
		$sql_insert[] = "CREATE INDEX idx_listfieldmashup  ON " . $config['table_prefix'] . "listingsdb (listingsdb_id ,listingsdb_active,userdb_id);";
		$sql_insert[] = "CREATE INDEX idx_fieldmashup  ON " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_field_name,listingsdb_id);";
		$sql_insert[] = "CREATE INDEX idx_classfieldmashup  ON " . $config['table_prefix_no_lang'] . "classlistingsdb (listingsdb_id ,class_id);";
		// ADDED foreach to run through array with errorchecking to see if something went wrong
		while (list($elementIndexValue, $elementContents) = each($sql_insert)) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) {
				if ($_SESSION['devel_mode'] == 'no') {
					die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
				}else {
					echo "<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />";
				}
			}
		}
		echo 'Indexes Created<br />';
	}
	function insert_values()
	{
		// this is the setup for the ADODB library
		$this->set_version();
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
		$sql_insert[] = "INSERT INTO " . $config['table_prefix_no_lang'] . "controlpanel VALUES ('" . $this->version_number . "', '" . trim($_SESSION['basepath']) . "', '" . trim($_SESSION['baseurl']) . "', 'Administrator', 'changeme@default.com', NULL, NULL, 'title.jpg', 1, '" . $_SESSION['template'] . "', 'OR', 'listing_detail_default.html', 'view_user_default.html', 'search_result_default.html', '" . $_SESSION['or_install_lang'] . "', 10, 4, 0, 0, '<a><b><i><u><br />', '$', 1, 0, 1, 1, 7, 100000, 700,700, 5, 100000, 700,700, 15, 900000, 3000, 'jpg,gif,png,egg', 1, 100, 1, 'gd','/usr/X11R6/bin/convert',1,100, 0, 365, 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,-1,0, 0, 0,0,0,0, 0, 0, 1, 1,'image/pjpeg,image/jpeg,image/gif,image/x-png,application/octet-stream', '" . $_SESSION['or_install_lang'] . "',1,1,'google_us','address','','','','city','state','zip','','xinha',0,0,0,0,'listingsdb_id','ASC',0,0,0,'','','','','Open-Realty " . $this->version_number . "','Open-Realty " . $this->version_number . "','headline,top_left,top_right,center,feature1,feature2,bottom_left,bottom_right','vtour_default.html','400','250','800','480','70','0','','','','','','','','','','',0,0,0,'<br />','<br />',0,'Featured Listing Feed','RSS feed of our featured listings','<table><tr><td>{image_thumb_1}</td><td>{listing_field_full_desc_rawvalue}</td></tr></table>','Last Modified Listing Feed','RSS feed of our last modified listings','<table><tr><td>{image_thumb_1}</td><td>{listing_field_full_desc_rawvalue}</td></tr></table>','50','100','width','width','UTF-8','1','100','width','500','700','4','50','0','16','16','7','2097152','jpg,gif,png,egg,pdf,doc,swf,avi,mov,mpg,zip,sbd,stc,std,sti,stw,svw,sxc,sxd,sxg,sxi,sxm','1','1','both','0','0','0','7','2097152','1','0','-','100','none','DESC','price',5,1,'http://wiki.open-realty.org/Admin_guide','http://wiki.open-realty.org/Admin_create_new_listing','http://wiki.open-realty.org/Admin_edit_listings','http://wiki.open-realty.org/Admin_Edit_User','http://wiki.open-realty.org/Admin_user_manager','http://wiki.open-realty.org/Admin_Page_Editor','http://wiki.open-realty.org/Admin_modify_listing','http://wiki.open-realty.org/Admin_edit_images','http://wiki.open-realty.org/Admin_edit_vtour','http://wiki.open-realty.org/Admin_edit_files','http://wiki.open-realty.org/Agentmember_template_edit_add_field','http://wiki.open-realty.org/Agentmember_template_set_field_order','http://wiki.open-realty.org/Agentmember_template_edit_add_field','http://wiki.open-realty.org/Admin_edit_listing_template','http://wiki.open-realty.org/Listing_template_edit_add_field','http://wiki.open-realty.org/Listing_template_set_field_order','http://wiki.open-realty.org/Listing_template_search_setup','http://wiki.open-realty.org/Listing_template_search_results_setup','http://wiki.open-realty.org/Admin_property_classes','http://wiki.open-realty.org/Site_Configuration','http://wiki.open-realty.org/Admin_view_site_log','http://wiki.open-realty.org/TransparentMaps_configuration','http://wiki.open-realty.org/TransparentMaps_cron_jobs','http://wiki.open-realty.org/TransparentRETS_ug_config_server','http://wiki.open-realty.org/TransparentRETS_ug_class_settigns','http://wiki.open-realty.org/Admin_edit_agent_member_template','http://wiki.open-realty.org/Admin_edit_agent_member_template','http://wiki.open-realty.org/Property_class_insertmodify','http://wiki.open-realty.org/Property_class_insert','http://wiki.open-realty.org/Addon_IDXManager','http://wiki.open-realty.org/Addon_IDXManager','http://wiki.open-realty.org/Addon_csvloader_user_guide','http://wiki.open-realty.org/Agentmember_template_set_field_order','0','-1','0','','0','0','0','0',CURRENT_TIMESTAMP,'notify_listings_default.html')";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "userdb VALUES (1,'admin','changeme@default.com','Default','Admin','','5f4dcc3b5aa765d61d8327deb882cf99','yes','yes','yes','yes','yes','2002-07-01','yes','yes','2002-07-01 22:38:50',1,'yes','yes','yes','no','yes',-1,'yes','yes','yes','yes','yes','yes','yes',4,'yes',1,-1,'no')";

		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "userdbelements (userdbelements_id, userdbelements_field_name, userdbelements_field_value, userdb_id) VALUES (1, 'edit_user_name', 'admin', 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "userdbelements (userdbelements_id, userdbelements_field_name, userdbelements_field_value, userdb_id) VALUES (2, 'phone', '215.850.0710', 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "userdbelements (userdbelements_id, userdbelements_field_name, userdbelements_field_value, userdb_id) VALUES (3, 'mobile', '215.850.0710', 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "userdbelements (userdbelements_id, userdbelements_field_name, userdbelements_field_value, userdb_id) VALUES (4, 'fax', '702.995.6591', 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "userdbelements (userdbelements_id, userdbelements_field_name, userdbelements_field_value, userdb_id) VALUES (5, 'homepage', 'http://www.open-realty.org', 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "userdbelements (userdbelements_id, userdbelements_field_name, userdbelements_field_value, userdb_id) VALUES (6, 'info', 'I am the system administrator!', 1);";

		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdb (listingsdb_id, userdb_id, listingsdb_title, listingsdb_expiration, listingsdb_notes, listingsdb_creation_date, listingsdb_last_modified, listingsdb_hit_count, listingsdb_featured, listingsdb_active,listingsdb_mlsexport) VALUES (1, 1, 'Example Listing', '2003-07-01', 'This is an example listing!', '2002-07-01', '2002-07-01 22:39:58', 17, 'yes', 'yes','no');";

		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (962, 'home_features', 'Balcony||Patio/Deck||Waterfront||Dishwasher||Disposal||Gas Range||Microwave||Washer/Dryer||Carpeted Floors||Hardwood Floors||Air Conditioning||Alarm||Cable/Satellite TV||Fireplace||Wheelchair Access', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (963, 'community_features', 'Fitness Center||Golf Course||Pool||Spa/Jacuzzi||Sports Complex||Tennis Courts||Bike Paths||Boating||Courtyard||Playground/Park||Association Fee||Clubhouse||Controlled Access||Public Transportation', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (961, 'mls', '13013', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (960, 'status', 'Active', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (958, 'prop_tax', '0', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (959, 'garage_size', '40 car', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (957, 'lot_size', '20 Acres', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id)VALUES (956, 'sq_feet', '35000', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (954, 'floors', '6', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (955, 'year_built', '1800', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (953, 'baths', '35', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (952, 'beds', '10', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (950, 'full_desc', 'Exclusive to this site! For two hundred years, the White House has stood as a symbol of the Presidency, the United States government, and the American people.', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (949, 'price', '2500000', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (948, 'neighborhood', 'Capitol', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (947, 'zip', '20500', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (946, 'state', 'DC', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (945, 'city', 'Washington', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (944, 'address', '1600 Pennsylvania Avenue', 1, 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_id, listingsdbelements_field_name, listingsdbelements_field_value, listingsdb_id, userdb_id) VALUES (943, 'owner', '1', 1, 1);";

		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (1,'text','city','City','','',2,1,1,'Yes','top_left','Yes',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (2,'text','address','Address','','',0,2,2,'Yes','top_left','Yes',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (3,'text','mls','mls','','',33,0,16,'No','top_right','No',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (4,'number','prop_tax','Annual Property Tax','','',29,0,15,'No','top_right','No',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (5,'select','status','Status','','Active||Pending||Sold',31,0,19,'No','top_right','No',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (6,'text','lot_size','Lot Size','','',27,0,12,'No','top_right','No',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (7,'text','garage_size','Garage Size','','',0,29,9,'No','top_right','No',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (8,'text','year_built','Year Built','','',23,0,11,'No','top_left','No',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (9,'number','sq_feet','Square Feet','','',25,0,10,'No','top_right','No',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (10,'text','baths','Baths','','',19,0,7,'No','top_left','Yes',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (11,'number','floors','Floors','','',21,0,8,'No','top_left','No',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (12,'text','beds','Beds','','',17,0,6,'No','top_left','Yes',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (13,'textarea','full_desc','Full Description','','',13,0,1,'No','center','Yes',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (14,'text','neighborhood','Neighborhood','','',7,0,14,'No','top_left','No',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (15,'price','price','Price','','',9,0,5,'No','top_left','Yes',1,'minmax','Price',5000,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (16,'text','zip','Zip','','',5,3,3,'Yes','top_left','Yes',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (17,'select','state','State','','AK||AL||AR||AZ||CA||CO||CT||DC||DE||FL||GA||HI||IA||ID||IL||IN||KS||KY||LA||MA||MD||ME||MH||MI||MN||MO||MS||MT||NC||ND||NE||NH||NJ||NM||NV||NY||OH||OK||OR||PA||RI||SC||SD||TN||TX||UT||VA||VT||WA||WI||WV||WY',4,4,4,'Yes','top_left','Yes',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (18,'checkbox','home_features','Home Features','','Balcony||Patio/Deck||Waterfront||Dishwasher||Disposal||Gas Range||Microwave||Washer/Dryer||Carpeted Floors||Hardwood Floors||Air Conditioning||Alarm||Cable/Satellite TV||Fireplace||Wheelchair Access',80,0,17,'No','feature1','No',0,NULL,NULL,0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (19,'checkbox','community_features','Community Features','','Fitness Center||Golf Course||Pool||Spa/Jacuzzi||Sports Complex||Tennis Courts||Bike Paths||Boating||Courtyard||Playground/Park||Association Fee||Clubhouse||Controlled Access||Public Transportation',85,0,18,'No','feature2','No',1,'optionlist','Community Features',0,0,NULL,'');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsformelements VALUES (20,'text','country','Country','','',6,0,18,'No','top_left','No',0,NULL,NULL,0,0,NULL,'');";

		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsimages (listingsimages_id, userdb_id, listingsimages_caption, listingsimages_file_name, listingsimages_thumb_file_name, listingsimages_description, listingsdb_id, listingsimages_rank, listingsimages_active) VALUES (1, 1, 'View From the Lawn', '1_white-house.jpg', 'thumb_1_white-house.jpg', 'This property has six floors, 132 rooms, 35 bathrooms, 147 windows, 412 doors, 12 chimneys, 8 staircases, and 3 elevators.', 1, 1, 'yes');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsimages (listingsimages_id, userdb_id, listingsimages_caption, listingsimages_file_name, listingsimages_thumb_file_name, listingsimages_description, listingsdb_id, listingsimages_rank, listingsimages_active) VALUES (2, 1, 'Vermeil Room', '1_vermeil_room.jpg', 'thumb_1_vermeil_room.jpg', 'The Vermeil Room, sometimes called the Gold Room, was last refurbished in 1991; it serves as a display room and, for formal occasions, as a ladies sitting room. The soft yellow of the paneled walls complements the collection of vermeil, or gilded silver, bequeathed to the White House in 1956 by Mrs. Margaret Thompson Biddle.', 1, 5, 'yes');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsimages (listingsimages_id, userdb_id, listingsimages_caption, listingsimages_file_name, listingsimages_thumb_file_name, listingsimages_description, listingsdb_id, listingsimages_rank, listingsimages_active) VALUES (3, 1, 'The China Room', '1_china_room.jpg', 'thumb_1_china_room.jpg', 'The Presidential Collection Room, now the China Room, was designated by Mrs. Woodrow Wilson in 1917 to display the growing collection of White House china. The room was redecorated in 1970, retaining the traditional red color scheme determined by the portrait of Mrs. Calvin Coolidge--painted by Howard Chandler Christy in 1924. President Coolidge, who was scheduled to sit for Christy, was too occupied that day with events concerning the Teapot Dome oil scandal. So the President postponed his appointment, and Mrs. Coolidge posed instead.', 1, 5, 'yes');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsimages (listingsimages_id, userdb_id, listingsimages_caption, listingsimages_file_name, listingsimages_thumb_file_name, listingsimages_description, listingsdb_id, listingsimages_rank, listingsimages_active) VALUES (4, 1, 'State Dining Room', '1_dining_room.jpg', 'thumb_1_dining_room.jpg', 'The State Dining Room, which now seats as many as 140 guests, was originally much smaller and served at various times as a drawing room, office, and Cabinet Room. Not until the Andrew Jackson administration was it called the State Dining Room, although it had been used for formal dinners by previous Presidents.', 1, 5, 'yes');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "listingsimages (listingsimages_id, userdb_id, listingsimages_caption, listingsimages_file_name, listingsimages_thumb_file_name, listingsimages_description, listingsdb_id, listingsimages_rank, listingsimages_active) VALUES (5, 1, 'The Green Room', '1_green_room.jpg', 'thumb_1_green_room.jpg', 'Although intended by architect James Hoban to be the Common Dining Room, the Green Room has served many purposes since the White House was first occupied in 1800. The inventory of February 1801 indicates that it was first used as a Lodging Room. Thomas Jefferson, the second occupant of the White House, used it as a dining room with a canvas floor cloth, painted green, foreshadowing the present color scheme. James Madison made it a sitting room since his Cabinet met in the East Room next door, and the Monroes used it as the Card Room with two tables for the whist players among their guests. ', 1, 5, 'yes');";

		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "memberformelements (memberformelements_id, memberformelements_field_type, memberformelements_field_name, memberformelements_field_caption, memberformelements_default_text, memberformelements_field_elements, memberformelements_rank, memberformelements_required) VALUES (3, 'textarea', 'info', 'Info', '', '', 10, 'No');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "memberformelements (memberformelements_id, memberformelements_field_type, memberformelements_field_name, memberformelements_field_caption, memberformelements_default_text, memberformelements_field_elements, memberformelements_rank, memberformelements_required) VALUES (4, 'text', 'phone', 'Phone', '', '', 1, 'No');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "memberformelements (memberformelements_id, memberformelements_field_type, memberformelements_field_name, memberformelements_field_caption, memberformelements_default_text, memberformelements_field_elements, memberformelements_rank, memberformelements_required) VALUES (5, 'text', 'mobile', 'Mobile', '', '', 3, 'No');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "memberformelements (memberformelements_id, memberformelements_field_type, memberformelements_field_name, memberformelements_field_caption, memberformelements_default_text, memberformelements_field_elements, memberformelements_rank, memberformelements_required) VALUES (6, 'text', 'fax', 'Fax', '', '', 5, 'No');";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "memberformelements (memberformelements_id, memberformelements_field_type, memberformelements_field_name, memberformelements_field_caption, memberformelements_default_text, memberformelements_field_elements, memberformelements_rank, memberformelements_required) VALUES (7, 'url', 'homepage', 'Homepage', '', '', 7, 'No');";

		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "agentformelements (agentformelements_id, agentformelements_field_type, agentformelements_field_name, agentformelements_field_caption, agentformelements_default_text, agentformelements_field_elements, agentformelements_rank, agentformelements_required,agentformelements_display_priv) VALUES (3, 'textarea', 'info', 'Info', '', '', 10, 'No',0);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "agentformelements (agentformelements_id, agentformelements_field_type, agentformelements_field_name, agentformelements_field_caption, agentformelements_default_text, agentformelements_field_elements, agentformelements_rank, agentformelements_required,agentformelements_display_priv) VALUES (4, 'text', 'phone', 'Phone', '', '', 1, 'No',0);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "agentformelements (agentformelements_id, agentformelements_field_type, agentformelements_field_name, agentformelements_field_caption, agentformelements_default_text, agentformelements_field_elements, agentformelements_rank, agentformelements_required,agentformelements_display_priv) VALUES (5, 'text', 'mobile', 'Mobile', '', '', 3, 'No',0);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "agentformelements (agentformelements_id, agentformelements_field_type, agentformelements_field_name, agentformelements_field_caption, agentformelements_default_text, agentformelements_field_elements, agentformelements_rank, agentformelements_required,agentformelements_display_priv) VALUES (6, 'text', 'fax', 'Fax', '', '', 5, 'No',0);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "agentformelements (agentformelements_id, agentformelements_field_type, agentformelements_field_name, agentformelements_field_caption, agentformelements_default_text, agentformelements_field_elements, agentformelements_rank, agentformelements_required,agentformelements_display_priv) VALUES (7, 'url', 'homepage', 'Homepage', '', '', 7, 'No',0);";

		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "class (class_id, class_name, class_rank) VALUES (1, 'Home', 1);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "class (class_id, class_name, class_rank) VALUES (2, 'Land', 2);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "class (class_id, class_name, class_rank) VALUES (3, 'Farms', 3);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "class (class_id, class_name, class_rank) VALUES (4, 'Commercial', 4);";
		$sql_insert[] = "INSERT INTO " . $config['table_prefix'] . "class (class_id, class_name, class_rank) VALUES (5, 'Rental', 5);";

		$sql_insert[] = "INSERT INTO " . $config['table_prefix_no_lang'] . "classlistingsdb (classlistingsdb_id, class_id, listingsdb_id) VALUES (1, 1, 1);";
		$z = 1;
		for($x = 1;$x < 6;$x++) {
			for($y = 1;$y < 21;$y++) {
				$sql_insert[] = "INSERT INTO " . $config['table_prefix_no_lang'] . "classformelements (classformelements_id, class_id, listingsformelements_id) VALUES ($z, $x, $y);";
				$z++;
			}
		} while (list($elementIndexValue, $elementContents) = each($sql_insert)) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) {
				if ($_SESSION['devel_mode'] == 'no') {
					die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
				}else {
					echo "<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />";
				}
			}
		}
		echo 'Tables Populated<br />';
	}

	function load_version()
	{
		$this->load_lang($_SESSION['or_install_lang']);
		switch ($_GET['step']) {
			case 'autoinstall':
				$_SESSION['table_prefix'] = trim($_GET['table_prefix']);
				$_SESSION['db_type'] = trim($_GET['db_type']);
				$_SESSION['db_user'] = trim($_GET['db_user']);
				$_SESSION['db_password'] = trim($_GET['db_password']);
				$_SESSION['db_database'] = trim($_GET['db_database']);
				$_SESSION['db_server'] = trim($_GET['db_server']);
				$_SESSION['basepath'] = trim($_GET['basepath']);
				$_SESSION['baseurl'] = trim($_GET['baseurl']);
				if (isset($_GET['devel_mode'])) {
					$_SESSION['devel_mode'] = $_GET['devel_mode'];
				}else {
					$_SESSION['devel_mode'] = 'no';
				}
				$_SESSION['template'] = 'lazuli';
				$this->write_config();
				$this->create_tables();
				$this->create_index();
				$this->insert_values();
				echo '<br /><strong>' . $this->lang['install_installation_complete'] . ' <a href="../admin/index.php?action=configure">' . $this->lang['install_configure_installation'] . '</a></strong>';
				break;
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
				$this->create_tables();
				$this->create_index();
				$this->insert_values();
				$this->database_maintenance();
				echo '<br /><strong>' . $this->lang['install_installation_complete'] . ' <a href="../admin/index.php?action=configure">' . $this->lang['install_configure_installation'] . '</a></strong>';
				break;
		}
	}
}

?>