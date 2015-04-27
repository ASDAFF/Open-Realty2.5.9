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

// DUTCH LANGUAGE FILE - Translated by Marco (marco@ya.com) - Revised by Isabel Heylen (info@inwebsname.com)

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Open-Realty Installatie";
$this->lang['install_version_warn'] = "Uw PHP versie is niet geschikt voor deze versie van Open-Realty. De installatie wordt afgebroken";
$this->lang['install_sqlversion_warn'] = "Uw MySql versie is niet geschikt voor deze versie van Open-Realty. De installatie wordt afgebroken";
$this->lang['install_php_version'] = "Uw huidige PHP versie is ";
$this->lang['install_sql_version'] = "Uw huidige MySql versie is ";
$this->lang['install_php_required'] = "De huidige versie van Open-Realty vereist minimaal PHP versie ";
$this->lang['install_sql_required'] = "De huidige versie van Open-Realty vereist minimaal MySql versie ";
$this->lang['install_welcome'] = "Welkom bij het Open-Realty installatieprogramma.";
$this->lang['install_intro'] = "Dit programma zal u door de installatie van Open Realty leiden. Voordat u begint, dient u een lege database te hebben aangemaakt. Tevens moet u de File Permissions voor de volgende files en directories (include/common.php, images/listing_photo, images/user_photos) op ofwel 777 of 755 zetten, afhankelijk van de setup van uw server. Wanneer u klaar bent met de installatie van Open Realty kunt u voor de veiligheid de file permissions voor de common.php file terugzetten naar 644. Lees de <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">installatie handleiding</a> voor volledige instructies.";
$this->lang['install_step_one_header'] = "Stap 1: Kijk uw File Permissions na:";
$this->lang['install_Permission_on'] = "Permissie aan";
$this->lang['install_are_correct'] = "zijn correct";
$this->lang['install_are_incorrect'] = "zijn incorrect";
$this->lang['install_all_correct'] = "Alle Permissies zijn correct.";
$this->lang['install_continue'] = "Klik om verder te gaan";
$this->lang['install_please_fix'] = "Pas uw file permissions aan en vernieuw deze pagina.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Selecteer Installatie Type:';
$this->lang['upgrade_115'] = 'Upgrade van Open-Realty 1.1.5';
$this->lang['install_200'] = 'Nieuwe Installatie Open-Realty 2.x.x';
$this->lang['move'] = 'Update alleen Pad en URL informatie';
$this->lang['upgrade_200'] = 'Upgrade van Open-Realty 2.x.x (2.0.0 Beta 1 or higher)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Setup Database Connectie:";
$this->lang['install_Database_Type'] = "Database Type:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Database Server:";
$this->lang['install_Database_Name'] = "Database Naam:";
$this->lang['install_Database_User'] = "Database Gebruiker:";
$this->lang['install_Database_Password'] = "Database Wachtwoord:";
$this->lang['install_Table Prefix'] = "Tabel Prefix:";
$this->lang['install_Base_URL'] = "Basis URL:";
$this->lang['install_Base_Path'] = "Basispad:";
$this->lang['install_Language'] = "Taal:";
$this->lang['install_English'] = "Engels";
$this->lang['install_Spanish'] = "Spaans";
$this->lang['install_Italian'] = "Italiaans";
$this->lang['install_French'] = "Frans";
$this->lang['install_Portuguese'] = "Portugees";
$this->lang['install_Russian'] = "Russisch";
$this->lang['install_Turkish'] = "Turks";
$this->lang['install_German'] = "Duits";
$this->lang['install_Dutch'] = "Nederlands";
$this->lang['install_Lithuanian'] = "Lithuaans";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "We kunnen geen verbinding maken met uw database. Controleer uw instellingen en probeer opnieuw.";

//Step Three
$this->lang['install_get_old_version'] = 'Bepaling oude Open-Realty versie';
$this->lang['install_get_old_version_error'] = 'Fout bij bepalen oude Open-Realty versie. Upgrade kan niet verder gaan.';
$this->lang['install_cleared_cache'] = "Cache gewist";
$this->lang['install_connection_ok'] = "Verbinding met de database geslaagd.";
$this->lang['install_save_settings'] = "Uw instellingen worden nu bewaard in de file common.php";
$this->lang['install_settings_saved'] = "Database instellingen bewaard.";
$this->lang['install_continue_db_setup'] = "Ga verder om de database aan te maken.";
$this->lang['install_populate_db'] = "De database wordt nu klaar gemaakt.";

//finalize installation
$this->lang['install_installation_complete'] = "Installatie is compleet.";
$this->lang['install_configure_installation'] = "Klik hier om uw installatie te configureren.";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Developer Mode Installatie - Hierdoor zal de installatie, zelfs met fouten, verder gaan. NIET AANBEVOLEN.";
$this->lang['yes'] = "Ja";
$this->lang['no'] = "Nee";

?>