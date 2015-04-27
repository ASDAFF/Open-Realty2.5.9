<?php
	/***************************************************************************\
	* Open-Realty																*
	* http://www.open-realty.org												*
	* Written by Ryan C. Bonham <ryan@transparent-tech.com>						*
	* Copyright 2002, 2003 Transparent Technologies								*
	* --------------------------------------------								*
	* This file is part of Open-Realty.											*
	*																			*
	* Open-Realty is free software; you can redistribute it and/or modify		*
	* it under the terms of the Open-Realty License as published by				*
	* Transparent Technologies; either version 1 of the License, or				*
	* (at your option) any later version.										*
	*																			*
	* Open-Realty is distributed in the hope that it will be useful,			*
	* but WITHOUT ANY WARRANTY; without even the implied warranty of			*
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the				*
	* Open-Realty License for more details.										*
	*																			*
	* You should have received a copy of the Open-Realty License				*
	* along with Open-Realty; if not, write to Transparent Technologies			*
	* RR1 Box 162C, Kingsley, PA  18826  USA									*
	\***************************************************************************/

// ENGLISH LANGUAGE FILE

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Open-Realty Install";
$this->lang['install_version_warn'] = "Your version of php is not able to run the current version of Open-Realty Installation has been cancelled";
$this->lang['install_sqlversion_warn'] = "Your version of mysql is not able to run the current version of Open-Realty Installation has been cancelled";
$this->lang['install_php_version'] = "Your Current PHP version is ";
$this->lang['install_sql_version'] = "Your Current MySql version is ";
$this->lang['install_php_required'] = "The current version of Open-Realty requires a minimum PHP version of ";
$this->lang['install_sql_required'] = "The current version of Open-Realty requires a minimum MySql version of ";
$this->lang['install_welcome'] = "Welcome to the Open-Realty install tool.";
$this->lang['install_intro'] = "This tool will guide you through setting up your open-realty install. Before you begin you must have created a blank database on your system. You must also have file permissions set to either 777 or 755 depenging on your servers setup, on the following files or directories. (include/common.php, images/listing_photo, images/user_photos)  When you are done installing open-realty you should set the common.php back to 644, for security reasons. Please read the <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">install guide</a> for full directions.";
$this->lang['install_step_one_header'] = "Step 1: Check File Permissions:";
$this->lang['install_Permission_on'] = "Permission on";
$this->lang['install_are_correct'] = "are correct";
$this->lang['install_are_incorrect'] = "are incorrect";
$this->lang['install_all_correct'] = "All Permissions are correct.";
$this->lang['install_continue'] = "Click To Continue Installation";
$this->lang['install_please_fix'] = "Please fix your file permissions and then refresh this page.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Select Installation Type:';
$this->lang['upgrade_115'] = 'Upgrade from Open-Realty 1.1.5';
$this->lang['install_200'] = 'New Install Of Open-Realty 2.x.x';
$this->lang['move'] = 'Update Path and URL information only';
$this->lang['upgrade_200'] = 'Upgrade from Open-Realty 2.x.x (2.0.0 Beta 1 or higher)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Setup Database Connection:";
$this->lang['install_Database_Type'] = "Database Type:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Database Server:";
$this->lang['install_Database_Name'] = "Database Name:";
$this->lang['install_Database_User'] = "Database User:";
$this->lang['install_Database_Password'] = "Database Password:";
$this->lang['install_Table Prefix'] = "Table Prefix:";
$this->lang['install_Base_URL'] = "Base URL:";
$this->lang['install_Base_Path'] = "Base Path:";
$this->lang['install_Language'] = "Language:";
$this->lang['install_English'] = "English";
$this->lang['install_Spanish'] = "Spanish";
$this->lang['install_Italian'] = "Italian";
$this->lang['install_French'] = "French";
$this->lang['install_Portuguese'] = "Portuguese";
$this->lang['install_Russian'] = "Russian";
$this->lang['install_Turkish'] = "Turkish";
$this->lang['install_German'] = "German";
$this->lang['install_Dutch'] = "Dutch";
$this->lang['install_Lithuanian'] = "Lithuanian";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "We are unable to connect to your database. Please Check your settings and try again.";

//Step Three
$this->lang['install_get_old_version'] = 'Determining old Open-Realty version';
$this->lang['install_get_old_version_error'] = 'Error determining old Open-Realty version. Upgrade can not continue.';
$this->lang['install_cleared_cache'] = "Cleared Cache";
$this->lang['install_connection_ok'] = "We are able to connect to the database.";
$this->lang['install_save_settings'] = "We are now going to save your settings to your common.php file";
$this->lang['install_settings_saved'] = "Database Settings Saved.";
$this->lang['install_continue_db_setup'] = "Continue to setup the database.";
$this->lang['install_populate_db'] = "We are now going to populate the database.";

//finalize installation
$this->lang['install_installation_complete'] = "Installation is complete.";
$this->lang['install_configure_installation'] = "Click here to configure your installation";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Developer Mode Install - This will allow the install to continue even with errors. THIS IS NOT RECOMMENDED.";
$this->lang['yes'] = "Yes";
$this->lang['no'] = "No";

?>