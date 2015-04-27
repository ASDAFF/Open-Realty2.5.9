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

// CROATIAN LANGUAGE FILE - Translated by Bojan Kukuljan (neon@hgu.hr)

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Open-Realty instalacija";
$this->lang['install_version_warn'] = "Vaša verzija php-a nije u mogućnosti da pokrene trenutnu Open-Realty verziju. Instalacija je otkazana.";
$this->lang['install_sqlversion_warn'] = "Vaša verzija mysql-a nije u mogućnosti da pokrene trenutnu Open-Realty verziju. Instalacija je otkazana.";
$this->lang['install_php_version'] = "Vaša trenutna verzija PHP-a je ";
$this->lang['install_sql_version'] = "Vaša trenutna verzija MySql-a je ";
$this->lang['install_php_required'] = "Trenutna verzija Open-Realty-a zahtjeva najmanje PHP verziju ";
$this->lang['install_sql_required'] = "Trenutna verzija Open-Realty-a zahtjeva najmanje MySql verziju ";
$this->lang['install_welcome'] = "Dobro došli u alat za instalaciju Open-Realty-a.";
$this->lang['install_intro'] = "Ovaj alat će Vas provesti kroz postavke Vaše open-realty instalacije. Prije nego počnemo trebate napraviti praznu bazu na serveru. Također trebate postaviti dozvole na 777 ili 755 ovisno o postavkama servera, na sljedećim datotekama i direktorijima. (include/common.php, images/listing_photo, images/user_photos)  Kada završite s instalacijom open-realty-a, trebate podesiti dozvolu datoteke common.php nazad na 644, zbog sigurnosnih razloga. Molimo pročitajte <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">vodič za instalaciju</a> za potpune upute.";
$this->lang['install_step_one_header'] = "Korak 1: Provjera dopuštenja datoteka:";
$this->lang['install_Permission_on'] = "Dozvole uključene";
$this->lang['install_are_correct'] = "su ispravne";
$this->lang['install_are_incorrect'] = "su ispravne";
$this->lang['install_all_correct'] = "Sve dozvole su ispravne.";
$this->lang['install_continue'] = "Kliknite za nastavak instalacije";
$this->lang['install_please_fix'] = "Please fix your file permissions and then refresh this page.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Odaberite vrstu instalacije:';
$this->lang['upgrade_115'] = 'Upgrade sa Open-Realty 1.1.5';
$this->lang['install_200'] = 'Nova instalacija Open-Realty 2.x.x';
$this->lang['move'] = 'Update samo Path u URL informacija';
$this->lang['upgrade_200'] = 'Upgrade sa Open-Realty 2.x.x (2.0.0 Beta 1 ili više)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Postavke povezivanja baze:";
$this->lang['install_Database_Type'] = "Tip baze:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Server baze:";
$this->lang['install_Database_Name'] = "Ime baze:";
$this->lang['install_Database_User'] = "Korisnik baze:";
$this->lang['install_Database_Password'] = "Lozinka baze:";
$this->lang['install_Table Prefix'] = "Prefix tablice:";
$this->lang['install_Base_URL'] = "URL baze:";
$this->lang['install_Base_Path'] = "Osnovni path:";
$this->lang['install_Language'] = "Jezik:";
$this->lang['install_English'] = "Engleski";
$this->lang['install_Spanish'] = "Španjolski";
$this->lang['install_Italian'] = "Talijanski";
$this->lang['install_French'] = "Francuski";
$this->lang['install_Portuguese'] = "Portugalski";
$this->lang['install_Russian'] = "Ruski";
$this->lang['install_Turkish'] = "Turski";
$this->lang['install_German'] = "Njemački";
$this->lang['install_Dutch'] = "Nizozemski";
$this->lang['install_Lithuanian'] = "Litvanski";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "Nismo u mogućnost da se spojimo na bazu. Molimo provjerite Vaše postavke i pokušajte ponovno.";

//Step Three
$this->lang['install_get_old_version'] = 'Određivanje stare Open-Realty verzije';
$this->lang['install_get_old_version_error'] = 'Greška pri određivanju stare Open-Realty brzije. Upgrade se ne može nastaviti.';
$this->lang['install_cleared_cache'] = "Cache obrisan";
$this->lang['install_connection_ok'] = "Povezivanje na bazu je uspješno.";
$this->lang['install_save_settings'] = "Sada će se Vaše postavke snimiti u common.php datoteku";
$this->lang['install_settings_saved'] = "Postavke baze snimljene.";
$this->lang['install_continue_db_setup'] = "Nastavak podešavanja baze.";
$this->lang['install_populate_db'] = "Sada će se baza popuniti.";

//finalize installation
$this->lang['install_installation_complete'] = "Instalacija je dovršena.";
$this->lang['install_configure_installation'] = "Kliknite ovdje za konfiguraciju instalacije";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Developer mod instalacija - Ovo će dopustiti instalaciju čak i ako se pojave greške. OVO NIJE PREPORUČENO.";
$this->lang['yes'] = "Da";
$this->lang['no'] = "Ne";

?>