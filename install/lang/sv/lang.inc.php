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

// SWEDISH LANGUAGE FILE - Translated by Alexander (alex@acpu.se)

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Installera Open-Realty";
$this->lang['install_version_warn'] = "Din PHP version är för gammal, Installationen avbröts";
$this->lang['install_sqlversion_warn'] = "Fel MySQL Version, Installationen har avbröts";
$this->lang['install_php_version'] = "Din PHP version är ";
$this->lang['install_sql_version'] = "Din MySQL version är ";
$this->lang['install_php_required'] = "Denna versionen av Open Realty kräver minst PHP ";
$this->lang['install_sql_required'] = "Denna versionen av Open Realty kräver minst MySQL ";
$this->lang['install_welcome'] = "Välkommen till installationsverktyget för Open Realty.";
$this->lang['install_intro'] = "Guiden kommer att installera Open Realty på Din server, Innan du börjar se till att du skapat en blang MySQL databas. Du måste även byta tillstånden till 777 eller 775 på följande mappar och filer (include/common.php, images/listing_photo, images/user_photos)  Efter att du har slutfört installationen bör du återställa tillstånden på common.php tillbaka till 644, av säkerhetsskäl. Vänligen läs <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">install guide</a> för mer information om installationsprocessen.";
$this->lang['install_step_one_header'] = "Steg 1: Kolla tillstånd på filer och mappar:";
$this->lang['install_Permission_on'] = "Tillstånd på";
$this->lang['install_are_correct'] = "är korrekta";
$this->lang['install_are_incorrect'] = "är inkorrekt";
$this->lang['install_all_correct'] = "Alla tillstånd är korrekta.";
$this->lang['install_continue'] = "Klicka här för att fortsätta";
$this->lang['install_please_fix'] = "Vänligen ange korrekta tillstånd, uppdatera sedan dena sidan.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Välj typ av installation:';
$this->lang['upgrade_115'] = 'Uppgradering från Open-Realty 1.1.5';
$this->lang['install_200'] = 'Ny installation av Open-Realty 2.x.x';
$this->lang['move'] = 'Uppdatera endast Path (adress) och URL information';
$this->lang['upgrade_200'] = 'Uppgradera från Open-Realty 2.x.x (2.0.0 Beta 1 eller högre)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Inställningar för Databas:";
$this->lang['install_Database_Type'] = "typ av Databas:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Database Server:";
$this->lang['install_Database_Name'] = "Database Namne:";
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
$this->lang['install_connection_fail'] = "Vi kan inte komma åt din Databas. Vänligen ändra dina inställningar och försök igen.";

//Step Three
$this->lang['install_get_old_version'] = 'Bestämmer tidigare Open-Realty version';
$this->lang['install_get_old_version_error'] = 'Kan ej bestämma tidigare Open-Realty version. Upgradering kan inte fortsätta.';
$this->lang['install_cleared_cache'] = "Cache rensat";
$this->lang['install_connection_ok'] = "Vi kan komma åt din Databas.";
$this->lang['install_save_settings'] = "Vi kommer nu att spara dina inställningar i din common.php fil";
$this->lang['install_settings_saved'] = "Databas inställningar sparade.";
$this->lang['install_continue_db_setup'] = "Fortsätt konfigurera databasen.";
$this->lang['install_populate_db'] = "Vi kommer nu att spara data på databasen.";

//finalize installation
$this->lang['install_installation_complete'] = "Installationen är slutförd.";
$this->lang['install_configure_installation'] = "Klicka gär för att redigera dina inställningar";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Developer Mode Install - Detta låter installationen fortsätta även vid fel. DETTA REKOMMENDERAS INTE.";
$this->lang['yes'] = "Ja";
$this->lang['no'] = "Nej";

?>