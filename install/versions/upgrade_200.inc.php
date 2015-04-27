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
	function load_prev_settings($return = false)
	{
		// Open File
		$old_file = file_get_contents(dirname(__FILE__) . '/../../include/common.php');
		// Get values
		preg_match('/\$db_type = "(.*?)"/', $old_file, $old_db_type);
		preg_match('/\$db_server = "(.*?)"/', $old_file, $old_db_server);
		preg_match('/\$db_database = "(.*?)"/', $old_file, $old_db_name);
		preg_match('/\$db_user = "(.*?)"/', $old_file, $old_db_user);
		preg_match('/\$db_password = "(.*?)"/', $old_file, $old_db_password);
		preg_match('/\$config\["table_prefix_no_lang"\] = "(.*?)"/', $old_file, $old_table_prefix);
		if ($return == false) {
			$this->get_new_settings($old_db_type[1], $old_db_server[1], $old_db_name[1], $old_db_user[1], $old_db_password[1], $old_table_prefix[1]);
		}else {
			return array('db_type' => $old_db_type[1], 'db_server' => $old_db_server[1], 'db_database' => $old_db_name[1], 'db_user' => $old_db_user[1], 'db_password' => $old_db_password[1], 'table_prefix' => $old_table_prefix[1]);
		}
	}
	function create_tables($old_version)
	{
		$sql_insert = array();
		switch ($old_version) {
			case '2.0 Beta 1':
			case '2.0 Beta 2':
			case '2.0.0':
			case '2.0.1':
			case '2.0.2':
			case '2.0.3':
			case '2.0.4':
			case '2.0.5':
			case '2.0.6':

				break;
				foreach($sql_insert as $elementContents) {
					$recordSet = $conn->Execute($elementContents);
					if ($recordSet === false) {
						if ($_SESSION['devel_mode'] == 'no') {
							die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
						}else {
							echo "<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />";
						}
					}
				}
		}
	}
	function update_tables($old_version)
	{
		$sql_insert = array();
		$this->set_version();
		// this is the setup for the ADODB library
		include_once(dirname(__FILE__) . '/../../include/class/adodb/adodb.inc.php');
		$conn = &ADONewConnection($_SESSION['db_type']);
		$conn->PConnect($_SESSION['db_server'], $_SESSION['db_user'], $_SESSION['db_password'], $_SESSION['db_database']);
		$config['table_prefix'] = $_SESSION['table_prefix'] . $_SESSION['or_install_lang'] . '_';
		$config['table_prefix_no_lang'] = $_SESSION['table_prefix'];
		switch ($old_version) {
			case '2.0 Beta 1':
			case '2.0 Beta 2':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD controlpanel_wysiwyg_execute_php INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD controlpanel_agent_default_num_listings INT4 NOT NULL";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_agent_default_num_listings = -1";
				$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET  userdb_is_agent = 'no'";
			case '2.0.0':
			case '2.0.1':
			case '2.0.2':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_number_decimals_number_fields INT2 NOT NULL DEFAULT 0 ";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_number_decimals_price_fields INT2 NOT NULL DEFAULT 0 ";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_agent_default_num_listings = 0";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_number_decimals_price_fields = 0";
			case '2.0.3':
			case '2.0.4':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "memberformelements ADD COLUMN memberformelements_display_priv INT4 NOT NULL DEFAULT 0";
				// Remove bad fields that might were incorrectly saved.
				$bad_value[] = 'user_user_name';
				$bad_value[] = 'edit_user_pass';
				$bad_value[] = 'edit_user_pass2';
				$bad_value[] = 'user_email';
				$bad_value[] = 'PHPSESSID';
				$bad_value[] = 'edit';
				$bad_value[] = 'edit_isAdmin';
				$bad_value[] = 'edit_active';
				$bad_value[] = 'edit_isAgent';
				$bad_value[] = 'edit_limitListings';
				$bad_value[] = 'edit_canEditForms';
				$bad_value[] = 'edit_canViewLogs';
				$bad_value[] = 'edit_canModerate';
				$bad_value[] = 'edit_canFeatureListings';
				$bad_value[] = 'edit_canPages';
				$bad_value[] = 'edit_canVtour';
				$sql_bad_values = '';
				foreach ($bad_value as $value) {
					if ($sql_bad_values != '') {
						$sql_bad_values .= " OR ";
					}
					$sql_bad_values .= "userdbelements_field_name = '$value'";
				}
				$sql_insert[] = "DELETE FROM " . $config['table_prefix'] . "userdbelements WHERE $sql_bad_values";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_automatic_update_check INT2 NOT NULL DEFAULT 0 ";
			case '2.0.5':
				if ($_SESSION['db_type'] == 'mysql') {
					$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "pagesmain MODIFY COLUMN pagesmain_full LONGTEXT";
				}
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_search_sortby varchar(45) NOT NULL DEFAULT 0 ";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_search_sorttype varchar(45) NOT NULL DEFAULT 0 ";
			case '2.0.6':
				// Putt settings back to correct values after messed up 2.0.5 upgrade
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_agent_default_num_listings = 0";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_number_decimals_price_fields = 0";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_search_sortby = 'listingsdb_id'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_search_sorttype = 'ASC'";
			case '2.0.7':
			case '2.0.8':
				echo 'Creating New User Permissions<br />';
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_agent_default_edit_site_config INT2 NOT NULL DEFAULT 0" ;
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_agent_default_edit_member_template  INT2 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_agent_default_edit_agent_template INT2 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_agent_default_edit_listing_template  INT2 NOT NULL DEFAULT 0";
				// Remove old permission default
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel DROP COLUMN controlpanel_agent_default_editforms";
				// Add new user fields.
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_site_config CHAR(3) NOT NULL DEFAULT 'no'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_member_template CHAR(3) NOT NULL DEFAULT 'no'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_agent_template CHAR(3) NOT NULL DEFAULT 'no'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_listing_template CHAR(3) NOT NULL DEFAULT 'no'";
				// Populate new fields
				$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET  userdb_can_edit_member_template = 'yes',userdb_can_edit_agent_template = 'yes',userdb_can_edit_listing_template = 'yes' WHERE userdb_can_edit_forms = 'yes'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb DROP COLUMN userdb_can_edit_forms";
				// Add new user fields
				$sql_insert[] = "ALTER TABLE  " . $config['table_prefix'] . "userdb ADD COLUMN userdb_user_first_name CHAR VARYING(100) NOT NULL default ''";
				$sql_insert[] = "ALTER TABLE  " . $config['table_prefix'] . "userdb ADD COLUMN userdb_user_last_name CHAR VARYING(100) NOT NULL default ''";
				// Update Controlpanel with new export option
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_export_listings INT2 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE  " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_export_listings CHAR VARYING(100) NOT NULL default ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_agent_default_can_export_listings  INT2 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE  " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_expiration CHAR VARYING(100) NOT NULL default ''";
				// Add New permissions for edit_all_listings and edit_all_users.
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_agent_default_edit_all_users INT2 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_agent_default_edit_all_listings  INT2 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_all_users CHAR(3) NOT NULL DEFAULT 'no'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_all_listings CHAR(3) NOT NULL DEFAULT 'no'";

				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "agentformelements MODIFY COLUMN agentformelements_field_name CHAR VARYING(80) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsdbelements MODIFY COLUMN listingsdbelements_field_name CHAR VARYING(80) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsformelements MODIFY COLUMN listingsformelements_field_name CHAR VARYING(80) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "memberformelements MODIFY COLUMN memberformelements_field_name CHAR VARYING(80) NOT NULL";
				// Create Tables for property classing
				foreach($sql_insert as $elementContents) {
					$recordSet = $conn->Execute($elementContents);
					if ($recordSet === false) {
						if ($_SESSION['devel_mode'] == 'no') {
							die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
						}else {
							echo "<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />";
						}
					}
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
				} else {
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
					if ($recordSet === false) {
						if ($_SESSION['devel_mode'] == 'no') {
							die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
						}else {
							echo "<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />";
						}
					}
				}
				$sql_insert = array();
				echo 'Starting Property Class Conversion.<br />';
				// Get current Listing Types
				$sql = "SELECT listingsformelements_field_elements FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_field_name = 'type'";
				$recordSet = $conn->execute($sql);
				if ($recordSet === false) {
					if ($_SESSION['devel_mode'] == 'no') {
						die('FATAL ERROR IN CLASS CONVERSION');
					}else {
						echo "FATAL ERROR IN CLASS CONVERSION";
					}
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
				if ($recordSet === false) {
					if ($_SESSION['devel_mode'] == 'no') {
						die('FATAL ERROR IN CLASS CONVERSION');
					}else {
						echo "FATAL ERROR IN CLASS CONVERSION";
					}
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
				if ($recordSet === false) {
					if ($_SESSION['devel_mode'] == 'no') {
						die('FATAL ERROR IN CLASS CONVERSION');
					}else {
						echo "FATAL ERROR IN CLASS CONVERSION";
					}
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
				// Remove fields from control panel that are no longer used.
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel DROP COLUMN controlpanel_rental_step";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel DROP COLUMN controlpanel_max_rental_price";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel DROP COLUMN controlpanel_min_rental_price";
				foreach($sql_insert as $elementContents) {
					$recordSet = $conn->Execute($elementContents);
					if ($recordSet === false) {
						if ($_SESSION['devel_mode'] == 'no') {
							die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
						}else {
							echo "<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />";
						}
					}
				}
				$sql_insert = array();
				echo 'Adding new user property class permissions and member notify options.<br />';
				// Add User Permissions for Property Class
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_agent_default_edit_property_classes  INT2 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_property_classes CHAR(3) NOT NULL DEFAULT 'no'";
				// Add new field for notify member option
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "usersavedsearches ADD COLUMN usersavedsearches_notify CHAR VARYING(3) NOT NULL DEFAULT 'no'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_map_country CHAR VARYING(45) NOT NULL DEFAULT ''";
				// Remove fields no longer needed.
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel DROP COLUMN controlpanel_phpmyadmin";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel DROP COLUMN controlpanel_manage_index_permissions";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_show_listedby_admin INT2 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_show_next_prev_listing_page INT2 NOT NULL DEFAULT 0";
			case '2.1.0':
			case '2.1.1':
			case '2.1.2':
			case '2.1.3':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_multiple_pclass_selection INT2 NOT NULL DEFAULT 0";
			case '2.1.5':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_map_address2 CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_map_address3 CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_seo_default_keywords TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_seo_default_description TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel CHANGE COLUMN controlpanel_site_title controlpanel_seo_default_title TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_seo_listing_title TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_template_listing_sections TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_seo_listing_keywords TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_seo_listing_description TEXT NOT NULL";
			case '2.1.6dev':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_agent_template CHAR VARYING(45) NOT NULL DEFAULT ''";
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
			$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsformelements MODIFY COLUMN listingsformelements_search_type CHAR VARYING(50) NOT NULL";

			case '2.2.0':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vtour_template CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vtour_width CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vtour_height CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vt_popup_width CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vt_popup_height CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vtour_fov CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_map_address4 CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_zero_price INT2 NOT NULL DEFAULT 0";

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
				$sql_insert[] = "UPDATE " . $config['table_prefix'] . "listingsdbelements SET listingsdbelements_field_value = '" .$returnValue. "' WHERE listingsdbelements_id = " .$id;
				$recordSet2->MoveNext();
				}
				$recordSet->MoveNext();
				}
			case '2.3.0':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vcard_phone CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vcard_fax CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vcard_mobile CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vcard_address CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vcard_city CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vcard_state CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vcard_zip CHAR VARYING(4) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vcard_country CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vcard_notes CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_vcard_url CHAR VARYING(45) NOT NULL DEFAULT ''";
			case '2.3.1':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "pagesmain ADD COLUMN pagesmain_description TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "pagesmain ADD COLUMN pagesmain_keywords TEXT NOT NULL";
			case '2.3.2':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsdb CHANGE COLUMN listingsdb_last_modified listingsdb_last_modified DATETIME NOT NULL";
			case '2.3.3':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_email_information_to_new_users INT(2) NOT NULL DEFAULT 0";
			case '2.3.4':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_demo_mode INT(2) NOT NULL DEFAULT 0";
			case '2.3.5':
			case '2.3.6':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_max_search_results INT4 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_feature_list_separator CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_search_list_separator CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_use_email_image_verification INT2 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_rss_title_featured CHAR VARYING(45) NOT NULL DEFAULT 'Featured Listings Feed'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_rss_desc_featured CHAR VARYING(255) NOT NULL DEFAULT 'RSS feed of our featured listings'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_rss_listingdesc_featured CHAR VARYING(255) NOT NULL DEFAULT '<table><tr><td>{image_thumb_1}</td><td>{listing_field_full_desc_rawvalue}</td></tr></table>';";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_rss_title_lastmodified CHAR VARYING(45) NOT NULL DEFAULT 'Last Modified Listings Feed'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_rss_desc_lastmodified CHAR VARYING(255) NOT NULL DEFAULT 'RSS feed of our last modified listings'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_rss_listingdesc_lastmodified CHAR VARYING(255) NOT NULL DEFAULT '<table><tr><td>{image_thumb_1}</td><td>{listing_field_full_desc_rawvalue}</td></tr></table>';";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_max_listings_upload_height INT4 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_max_user_upload_height INT4 NOT NULL DEFAULT 0";
			case '2.4.0':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel CHANGE COLUMN controlpanel_rss_listingdesc_lastmodified controlpanel_rss_listingdesc_lastmodified TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel CHANGE COLUMN controlpanel_rss_listingdesc_featured controlpanel_rss_listingdesc_featured TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_rss_limit_lastmodified INT4 NOT NULL DEFAULT 50";
			case '2.4.1':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_thumbnail_height INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_resize_thumb_by CHAR VARYING(20) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_resize_by CHAR VARYING(20) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_charset CHAR VARYING(15) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_wysiwyg_show_edit INT(2) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_textarea_short_chars INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_main_image_display_by CHAR VARYING(20) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_main_image_width INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_main_image_height INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_number_columns INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_rss_limit_featured INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_force_decimals INT(2) NOT NULL";
			case '2.4.2':
			case '2.4.3':
			case '2.4.4':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsformelements ADD COLUMN listingsformelements_field_length INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_icon_image_width INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_icon_image_height INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_max_listings_file_uploads INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_max_listings_file_upload_size INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_max_users_file_uploads INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_max_users_file_upload_size INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_allowed_file_upload_extensions CHAR VARYING(255) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_show_file_icon INT(2) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_show_file_size INT(2) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_file_display_option CHAR VARYING(20) NOT NULL";
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
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_include_senders_ip INT(2) NOT NULL ;";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_agent_default_havefiles INT(2) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_agent_default_haveuserfiles INT(2) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_have_files CHAR(3) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_have_user_files CHAR(3) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsformelements ADD COLUMN listingsformelements_tool_tip TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "agentformelements ADD COLUMN agentformelements_tool_tip TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "memberformelements ADD COLUMN memberformelements_tool_tip TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_show_notes_field INT(2) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_disable_referrer_check INT(2) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_seo_url_seperator CHAR VARYING(20) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_search_step_max INT(4) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_special_search_sortby CHAR VARYING(45) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_special_search_sorttype CHAR VARYING(45) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_price_field CHAR VARYING(45) NOT NULL DEFAULT 'price'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_users_per_page INT4 NOT NULL DEFAULT 5";
			case '2.5.0':
			case '2.5.1':
			case '2.5.2':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_use_help_link INT2 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_main_admin_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_add_listing_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_listing_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_user_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_user_manager_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_page_editor_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_modify_listing_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_listing_images_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_vtour_images_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_listing_files_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_agent_template_add_field_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_agent_template_field_order_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_member_template_add_field_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_listing_template_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_listing_template_add_field_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_listings_template_field_order_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_listing_template_search_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_listing_template_search_results_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_show_property_classes_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_configure_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_view_log_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_addon_transparentmaps_admin_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_addon_transparentmaps_geocode_all_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_addon_transparentRETS_config_server_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_addon_transparentRETS_config_imports_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_user_template_member_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_user_template_agent_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_modify_property_class_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_insert_property_class_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_addon_IDXManager_config_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_addon_IDXManager_classmanager_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_addon_csvloader_admin_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_edit_member_template_field_order_help_link TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_show_admin_on_agent_list INT(2) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsformelements CHANGE COLUMN listingsformelements_location listingsformelements_location CHAR VARYING(50) NOT NULL";
			case '2.5.3':
			case '2.5.4':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "pagesmain CHANGE COLUMN pagesmain_title pagesmain_title TEXT NOT NULL";
			case '2.5.5':
			case '2.5.6':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsdb CHANGE COLUMN listingsdb_title listingsdb_title TEXT NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsimages CHANGE COLUMN listingsimages_file_name listingsimages_file_name CHAR VARYING(255) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsimages CHANGE COLUMN listingsimages_thumb_file_name listingsimages_thumb_file_name CHAR VARYING(255) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userimages CHANGE COLUMN userimages_file_name userimages_file_name CHAR VARYING(255) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userimages CHANGE COLUMN userimages_thumb_file_name userimages_thumb_file_name CHAR VARYING(255) NOT NULL";
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
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_rank INT4";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_featuredlistinglimit INT4";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD controlpanel_agent_default_num_featuredlistings INT4 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_edit_blog CHAR(3) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_blog_user_type INT4 NOT NULL DEFAULT 1";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_manage_addons INT4 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD controlpanel_use_signup_image_verification INT2 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_site_email CHAR VARYING(45) NOT NULL DEFAULT ''";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_mbstring_enabled INT(2) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD controlpanel_require_email_verification INT2 NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_email_verified CHAR(3) NOT NULL";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD controlpanel_blog_requires_moderation INT2 NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "listingsformelements MODIFY COLUMN `listingsformelements_search_step` VARCHAR(25) DEFAULT NULL";
			case '2.5.7':
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_maintenance_mode INT(2) NOT NULL DEFAULT 0";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_notification_last_timestamp TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix_no_lang'] . "controlpanel ADD COLUMN controlpanel_notify_listings_template CHAR VARYING(45) NOT NULL DEFAULT 'notify_listings_default.html'";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb DROP COLUMN userdb_can_manage_addons";
				$sql_insert[] = "ALTER TABLE " . $config['table_prefix'] . "userdb ADD COLUMN userdb_can_manage_addons CHAR(3) NOT NULL";
			default:
				// Update version
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET  controlpanel_basepath ='" . trim($_SESSION['basepath']) . "', controlpanel_baseurl = '" . trim($_SESSION['baseurl']) . "'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET  controlpanel_version ='" . $this->version_number . "'";
				break;
		}
		foreach($sql_insert as $elementContents) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) {
				if ($_SESSION['devel_mode'] == 'no') {
					die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
				}else {
					echo "<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />";
				}
			}
		}
	}
	function create_index($old_version)
	{
		// this is the setup for the ADODB library
		include_once(dirname(__FILE__) . '/../../include/class/adodb/adodb.inc.php');
		$conn = &ADONewConnection($_SESSION['db_type']);
		$conn->PConnect($_SESSION['db_server'], $_SESSION['db_user'], $_SESSION['db_password'], $_SESSION['db_database']);
		$config['table_prefix'] = $_SESSION['table_prefix'] . $_SESSION['or_install_lang'] . '_';
		$config['table_prefix_no_lang'] = $_SESSION['table_prefix'];

		$sql_insert = array();
		switch ($old_version) {
			case '2.0 Beta 1':
			case '2.0 Beta 2':
			case '2.0.0':
			case '2.0.1':
			case '2.0.2':
			case '2.0.3':
			case '2.0.4':
			case '2.0.5':
			case '2.0.6':
			case '2.0.7':
			case '2.0.8':
			case '2.1.0':
			case '2.1.1':
			case '2.1.2':
			case '2.1.3':
			case '2.1.4':
			case '2.1.5':
				$sql_insert[] = "CREATE INDEX idx_classformelements_class_id ON " . $config['table_prefix_no_lang'] . "classformelements (class_id);";
				$sql_insert[] = "CREATE INDEX idx_classformelements_listingsformelements_id ON " . $config['table_prefix_no_lang'] . "classformelements (listingsformelements_id);";

				$sql_insert[] = "CREATE INDEX idx_classlistingsdb_class_id ON " . $config['table_prefix_no_lang'] . "classlistingsdb (class_id);";
				$sql_insert[] = "CREATE INDEX idx_classlistingsdb_listingsdb_id ON " . $config['table_prefix_no_lang'] . "classlistingsdb (listingsdb_id);";

				$sql_insert[] = "CREATE INDEX idx_class_rank ON " . $config['table_prefix'] . "class (class_rank);";
			case '2.1.6dev':
			case '2.2.0':
			case '2.3.0':
			case '2.3.1':
			case '2.3.2':
			case '2.3.3':
			case '2.3.4':
			case '2.3.5':
			case '2.3.6':
				if ($_SESSION['db_type'] == 'mysql') {
					$sql_insert[] = "CREATE INDEX idx_user_field_value ON " . $config['table_prefix'] . "userdbelements (userdbelements_field_value(255));";
				}else {
					$sql_insert[] = "CREATE INDEX idx_user_field_value ON " . $config['table_prefix'] . "userdbelements (userdbelements_field_value);";
				}
				$sql_insert[] = "CREATE INDEX idx_user_field_name ON " . $config['table_prefix'] . "userdbelements (userdbelements_field_name);";
				$sql_insert[] = "CREATE INDEX idx_userdb_userid ON " . $config['table_prefix'] . "userdbelements (userdb_id);";

			case '2.4.0':
			case '2.4.1':
			case '2.4.2':
			case '2.4.3':
			case '2.4.4':
			case '2.5.0':
			case '2.5.1':
			case '2.5.2':
				$sql_insert[] = "CREATE INDEX idx_agentformelements_field_name ON " . $config['table_prefix'] . "agentformelements (agentformelements_field_name);";
				$sql_insert[] = "CREATE INDEX idx_userimages_userdb_id ON " . $config['table_prefix'] . "userimages (userdb_id);";
				$sql_insert[] = "CREATE INDEX idx_userimages_userimages_rank ON " . $config['table_prefix'] . "userimages (userimages_rank);";
				$sql_insert[] = "CREATE INDEX idx_vtourimages_listingsdb_id ON " . $config['table_prefix'] . "vtourimages (listingsdb_id);";
				$sql_insert[] = "CREATE INDEX idx_vtourimages_vtourimages_rank ON " . $config['table_prefix'] . "vtourimages (vtourimages_rank);";
			case '2.5.5':
				$sql_insert[] = "CREATE INDEX idx_listfieldmashup  ON " . $config['table_prefix'] . "listingsdb (listingsdb_id ,listingsdb_active,userdb_id);";
				$sql_insert[] = "CREATE INDEX idx_fieldmashup  ON " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_field_name,listingsdb_id);";
				$sql_insert[] = "CREATE INDEX idx_classfieldmashup  ON " . $config['table_prefix_no_lang'] . "classlistingsdb (listingsdb_id ,class_id);";

			default:
				break;
		}

		foreach($sql_insert as $elementContents) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) {
				if ($_SESSION['devel_mode'] == 'no') {
					die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
				}else {
					echo "<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />";
				}
			}
		}
	}
	function insert_values($old_version)
	{
		$sql_insert = array();
		// this is the setup for the ADODB library
		include_once(dirname(__FILE__) . '/../../include/class/adodb/adodb.inc.php');
		$conn = &ADONewConnection($_SESSION['db_type']);
		$conn->PConnect($_SESSION['db_server'], $_SESSION['db_user'], $_SESSION['db_password'], $_SESSION['db_database']);
		$config['table_prefix'] = $_SESSION['table_prefix'] . $_SESSION['or_install_lang'] . '_';
		$config['table_prefix_no_lang'] = $_SESSION['table_prefix'];
		switch ($old_version) {
			case '2.0 Beta 1':
			case '2.0 Beta 2':
			case '2.0.0':
			case '2.0.1':
			case '2.0.2':
			case '2.0.3':
			case '2.0.4':
			case '2.0.5':
			case '2.0.6':
			case '2.0.7':
			case '2.0.8':
			case '2.1.0':
			case '2.1.1':
			case '2.1.2':
			case '2.1.3':
			case '2.1.4':
			case '2.1.5':
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_template_listing_sections = 'headline,top_left,top_right,center,feature1,feature2,bottom_left,bottom_right'";
			case '2.1.6dev':
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_agent_template = 'view_user_default.html'";
			case '2.2.0':
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_vtour_template = 'vtour_default.html'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_vtour_width = '400'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_vtour_height = '250'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_vt_popup_width = '800'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_vt_popup_height = '480'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_vtour_fov = '70'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_zero_price = '0'";
			case '2.3.6':
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_max_search_results = 0";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_feature_list_separator = '<br />'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_search_list_separator = '<br />'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_use_email_image_verification = 0";
			case '2.4.1':
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_thumbnail_height = '100'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_resize_thumb_by = 'width'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_resize_by = 'width'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_max_listings_upload_height = '700' WHERE controlpanel_max_listings_upload_height = 0";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_max_user_upload_height = '700' WHERE controlpanel_max_user_upload_height = 0";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_charset = 'ISO-8859-1'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_wysiwyg_show_edit = '1'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_textarea_short_chars = '100'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_main_image_display_by = 'width'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_main_image_width = '500'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_main_image_height = '700'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_number_columns = '4'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_rss_limit_featured = '50'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_force_decimals = '0'";
			case '2.4.4':
			case '2.4.3':
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_icon_image_width = '16'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_icon_image_height = '16'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_max_listings_file_uploads = '7'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_max_listings_file_upload_size = '2097152'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_allowed_file_upload_extensions = 'jpg,gif,png,egg,pdf,doc,swf,avi,mov,mpg,zip,sbd,stc,std,sti,stw,svw,sxc,sxd,sxg,sxi,sxm'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_show_file_icon = '1'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_show_file_size = '1'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_file_display_option = 'both'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_agent_default_havefiles = '0'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET userdb_can_have_files = 'yes' WHERE userdb_can_have_vtours = 'yes'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET userdb_can_have_files = 'no' WHERE userdb_can_have_vtours = 'no'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_max_users_file_uploads = '7'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_max_users_file_upload_size = '2097152'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_agent_default_havefiles = '0'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET userdb_can_have_user_files = 'yes' WHERE userdb_can_have_vtours = 'yes'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET userdb_can_have_user_files = 'no' WHERE userdb_can_have_vtours = 'no'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_show_notes_field = '1'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_disable_referrer_check = '0'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_seo_url_seperator = ' '";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_search_step_max = '100'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_special_search_sortby = 'none'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_special_search_sorttype = 'DESC'";
			case '2.5.0':
			case '2.5.1':
			case '2.5.2':
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_use_help_link = '1'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_main_admin_help_link = 'http://wiki.open-realty.org/Admin_guide'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_add_listing_help_link = 'http://wiki.open-realty.org/Admin_create_new_listing'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_listing_help_link = 'http://wiki.open-realty.org/Admin_edit_listings'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_page_editor_help_link = 'http://wiki.open-realty.org/Admin_Page_Editor'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_user_help_link = 'http://wiki.open-realty.org/Admin_Edit_User'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_user_manager_help_link = 'http://wiki.open-realty.org/Admin_user_manager'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_modify_listing_help_link = 'http://wiki.open-realty.org/Admin_modify_listing'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_listing_images_help_link = 'http://wiki.open-realty.org/Admin_edit_images'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_vtour_images_help_link = 'http://wiki.open-realty.org/Admin_edit_vtour'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_listing_files_help_link = 'http://wiki.open-realty.org/Admin_edit_files'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_agent_template_add_field_help_link = 'http://wiki.open-realty.org/Agentmember_template_edit_add_field'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_agent_template_field_order_help_link = 'http://wiki.open-realty.org/Agentmember_template_set_field_order'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_member_template_add_field_help_link = 'http://wiki.open-realty.org/Agentmember_template_edit_add_field'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_listing_template_help_link = 'http://wiki.open-realty.org/Admin_edit_listing_template'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_listing_template_add_field_help_link = 'http://wiki.open-realty.org/Listing_template_edit_add_field'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_listings_template_field_order_help_link = 'http://wiki.open-realty.org/Listing_template_set_field_order'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_listing_template_search_help_link = 'http://wiki.open-realty.org/Listing_template_search_setup'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_listing_template_search_results_help_link = 'http://wiki.open-realty.org/Listing_template_search_results_setup'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_show_property_classes_help_link = 'http://wiki.open-realty.org/Admin_property_classes'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_configure_help_link = 'http://wiki.open-realty.org/Site_Configuration'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_view_log_help_link = 'http://wiki.open-realty.org/Admin_view_site_log'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_addon_transparentmaps_admin_help_link = 'http://wiki.open-realty.org/TransparentMaps_configuration'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_addon_transparentmaps_geocode_all_help_link = 'http://wiki.open-realty.org/TransparentMaps_cron_jobs'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_addon_transparentRETS_config_server_help_link = 'http://wiki.open-realty.org/TransparentRETS_ug_config_server'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_addon_transparentRETS_config_imports_help_link = 'http://wiki.open-realty.org/TransparentRETS_ug_class_settigns'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_user_template_member_help_link = 'http://wiki.open-realty.org/Admin_edit_agent_member_template'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_user_template_agent_help_link = 'http://wiki.open-realty.org/Admin_edit_agent_member_template'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_modify_property_class_help_link = 'http://wiki.open-realty.org/Property_class_insertmodify'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_insert_property_class_help_link = 'http://wiki.open-realty.org/Property_class_insert'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_addon_IDXManager_config_help_link = 'http://wiki.open-realty.org/Addon_IDXManager'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_addon_IDXManager_classmanager_help_link = 'http://wiki.open-realty.org/Addon_IDXManager'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_addon_csvloader_admin_help_link = 'http://wiki.open-realty.org/Addon_csvloader_user_guide'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_edit_member_template_field_order_help_link = 'http://wiki.open-realty.org/Agentmember_template_set_field_order'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_show_admin_on_agent_list = 0";
			case '2.5.6':
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_agent_default_num_featuredlistings = -1";
				$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET userdb_rank = 0";
				$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET userdb_featuredlistinglimit = -1";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_use_signup_image_verification = 0";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_mbstring_enabled = '0'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_require_email_verification = 0";
			case '2.5.7':
				$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET userdb_blog_user_type = 4 WHERE userdb_is_admin = 'yes'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_notification_last_timestamp = CURRENT_TIMESTAMP";
				$sql_insert[] = "UPDATE  " . $config['table_prefix_no_lang'] . "controlpanel SET controlpanel_charset = 'UTF-8'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET userdb_can_manage_addons = 'yes' WHERE userdb_is_admin = 'yes'";
				$sql_insert[] = "UPDATE  " . $config['table_prefix'] . "userdb SET userdb_can_manage_addons = 'no' WHERE userdb_is_admin = 'no'";
			break;
		}
		foreach($sql_insert as $elementContents) {
			$recordSet = $conn->Execute($elementContents);
			if ($recordSet === false) {
				if ($_SESSION['devel_mode'] == 'no') {
					die ("<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />");
				}else {
					echo "<strong><span style=\"red\">ERROR - $elementContents</span></strong><br />";
				}
			}
		}
	}
	function load_version()
	{
		$this->load_lang($_SESSION['or_install_lang']);
		switch ($_GET['step']) {
			case 'autoupdate':
				$settings = $this->load_prev_settings(true);
				$_SESSION['table_prefix'] = $settings['table_prefix'];
				$_SESSION['db_type'] = $settings['db_type'];
				$_SESSION['db_user'] = $settings['db_user'];
				if ($settings['db_password'] != false) {
					$_SESSION['db_password'] = $settings['db_password'];
				}else {
					$_SESSION['db_password'] = '';
				}

				$_SESSION['db_database'] = $settings['db_database'];
				$_SESSION['db_server'] = $settings['db_server'];
				$www = $this->get_base_url();
				$physical = $this->get_base_path();
				$_SESSION['basepath'] = $physical;
				$_SESSION['baseurl'] = $www;
				if (isset($_GET['devel_mode'])) {
					$_SESSION['devel_mode'] = $_GET['devel_mode'];
				}else {
					$_SESSION['devel_mode'] = 'no';
				}

				$this->write_config();
				$old_version = $this->get_previous_version();
				if (empty($old_version)) {
					echo $this->lang['install_get_old_version_error'];
					break;
				}
				$this->update_tables($old_version);
				$this->create_tables($old_version);
				$this->create_index($old_version);
				$this->insert_values($old_version);
				$this->database_maintenance();
				echo '<br /><strong>' . $this->lang['install_installation_complete'] . '</strong>';
				break;
			case 4:
				$this->load_prev_settings();
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
				$this->write_config();
				break;
			case 6:
				$old_version = $this->get_previous_version();
				if (empty($old_version)) {
					echo $this->lang['install_get_old_version_error'];
					break;
				}
				$this->update_tables($old_version);
				$this->create_tables($old_version);
				$this->create_index($old_version);
				$this->insert_values($old_version);
				$this->database_maintenance();
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