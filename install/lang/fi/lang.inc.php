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

// FINNISH LANGUAGE FILE - Translated by Marko Yli-Paavola (info@sivuja.com)

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Open-Realtyn Asennus";
$this->lang['install_version_warn'] = "Tämä Open-Realtyn versio ei tue asennettua PHP versiota. Asennus on keskeytetty.";
$this->lang['install_sqlversion_warn'] = "Tämä Open-Realtyn versio ei tue asennettua MYSQL versiota. Asennus on keskeytetty.";
$this->lang['install_php_version'] = "PHP versio ";
$this->lang['install_sql_version'] = "MySql versio ";
$this->lang['install_php_required'] = "Open-Realtyn nykyinen versio vaatii vähintään PHP version ";
$this->lang['install_sql_required'] = "Open-Realtyn nykyinen versio vaatii vähintään MySql version ";
$this->lang['install_welcome'] = "Tervetuloa Open-Realtyn asennusohjelmaan.";
$this->lang['install_intro'] = "Tämä työkalu auttaa Open-Realtyn asennuksessa. Ennen asennusta sinun on luotava tyhjä tietokanta. Seuraavien tiedostojen käyttöoikeuksien on oltava 777 tai 755 riippuen palvelimen asetuksista. (include/common.php, images/listing_photo, images/user_photos)  Asennuksen jälkeen vaihda common.php -tiedoston oikeudet takaisin 644, tietoturvasyistä. Lue <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">asennusoppaasta</a> lisätietoja.";
$this->lang['install_step_one_header'] = "Vaihe 1: Tarkasta tiedostojen käyttöoikeudet:";
$this->lang['install_Permission_on'] = "Käyttöoikeus päällä";
$this->lang['install_are_correct'] = "on oikein";
$this->lang['install_are_incorrect'] = "on väärin";
$this->lang['install_all_correct'] = "Kaikki käyttöoikeudet ovat oikein.";
$this->lang['install_continue'] = "Jatka asennusta";
$this->lang['install_please_fix'] = "Muuta tiedostojen käyttöoikeudet ja päivitä sivu.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Valitse asennuksen tyyppi:';
$this->lang['upgrade_115'] = 'Päivitys Open-Realty versiosta 1.1.5';
$this->lang['install_200'] = 'Uusi asennus Open-Realty versiosta 2.x.x';
$this->lang['move'] = 'Vain päivityspolun ja URL:n tieto';
$this->lang['upgrade_200'] = 'Päivitys Open-Realty versiosta 2.x.x (2.0.0 Beta 1 tai uudempi)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Tietokantayhteyden asetukset:";
$this->lang['install_Database_Type'] = "Tietokannan tyyppi:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Tietokantapalvelin:";
$this->lang['install_Database_Name'] = "Tietokannan nimi:";
$this->lang['install_Database_User'] = "Tietokannan käyttäjä:";
$this->lang['install_Database_Password'] = "Tietokannan salasana:";
$this->lang['install_Table Prefix'] = "Taulun prefiksi / etuliite:";
$this->lang['install_Base_URL'] = "Base URL:";
$this->lang['install_Base_Path'] = "Base Path:";
$this->lang['install_Language'] = "Kieli:";
$this->lang['install_English'] = "Englanti";
$this->lang['install_Spanish'] = "Espanja";
$this->lang['install_Italian'] = "Italia";
$this->lang['install_French'] = "Ranska";
$this->lang['install_Portuguese'] = "Portugali";
$this->lang['install_Russian'] = "Venäjä";
$this->lang['install_Turkish'] = "Turkki";
$this->lang['install_German'] = "Saksa";
$this->lang['install_Dutch'] = "Hollanti";
$this->lang['install_Lithuanian'] = "Liettua";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "Tietokantaan ei saada yhteyttä. Tarkista asetukset ja kokeile uudelleen.";

//Step Three
$this->lang['install_get_old_version'] = 'Selvitetään Open-Realtyn versiota';
$this->lang['install_get_old_version_error'] = 'Virhe selvitettäessä vanhaa Open-Realty versiota. Päivitystä ei voida jatkaa.';
$this->lang['install_cleared_cache'] = "Välimuisti tyhjennetty";
$this->lang['install_connection_ok'] = "Tietokantaan saadaan yhteys.";
$this->lang['install_save_settings'] = "Asetukset tallennetaan common.php -tiedostoon";
$this->lang['install_settings_saved'] = "Tietokannan asetukset tallennettu.";
$this->lang['install_continue_db_setup'] = "Jatka tietokannan asennusta.";
$this->lang['install_populate_db'] = "We are now going to populate the database.";

//finalize installation
$this->lang['install_installation_complete'] = "Asennus on valmis.";
$this->lang['install_configure_installation'] = "Muokkaa asennusta";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Developer Mode Install - This will allow the install to continue even with errors. THIS IS NOT RECOMMENDED. EI SUOSITELLA.";
$this->lang['yes'] = "Kyllä";
$this->lang['no'] = "Ei";

?>