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

// HUNGARIAN LANGUAGE FILE - Translated by Halász Árpád (arpad.halasz@t-online.hu)

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Open-Realty Telepítő";
$this->lang['install_version_warn'] = "Az Ön PHP verziója nem elegendő az Open-Realty jelenlegi verziójának futtatásához. A telepítés leállt.";
$this->lang['install_sqlversion_warn'] = "Az Ön mysql verziója nem elegendő az Open-Realty jelenlegi verziójának futtatásához. A telepítés leállt.";
$this->lang['install_php_version'] = "Az Ön jelenlegi PHP verziója ";
$this->lang['install_sql_version'] = "Az Ön jelenlegi MySql verziója ";
$this->lang['install_php_required'] = "Az Open-Realty jelenlegi verziójához szükséges minimum PHP verzó ";
$this->lang['install_sql_required'] = "Az Open-Realty jelenlegi verziójához szükséges minimum MySql verzó ";
$this->lang['install_welcome'] = "Üdvözöljük az Open-Realty telepítőjében.";
$this->lang['install_intro'] = "A telepítő segíteni fog Önnek az open-realty feltelepítésében. Mielőtt elkezdi, készítenie kell egy üres adatbázist az Ön vagy szolgáltatója rendszerén. A fájl jogosultságokat a szerver beállításaitól függően be kell állítania 777-re vagy 755-re, a következő fájlokon vagy könyvtárakon. (include/common.php, images/listing_photo, images/user_photos) Amikor befejezte az open-realty telepítését, a common.php-n a jogosultságokat vissza kell állítania 644-re, a biztonság érdekében. Amennyiben további információkat szeretne kapni az Open-Realty telepítéséről, olvassa el a <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">Telepítési kézikönyvet</a>.";
$this->lang['install_step_one_header'] = "Első lépés: Fájl jogosultságok ellenőrzése:";
$this->lang['install_Permission_on'] = "Jogosultság eze(ke)n";
$this->lang['install_are_correct'] = "rendben";
$this->lang['install_are_incorrect'] = "rossz";
$this->lang['install_all_correct'] = "Minden jogosultság rendben.";
$this->lang['install_continue'] = "Kattintson ide a telepítés folytatásához";
$this->lang['install_please_fix'] = "Kérem javítsa a fájl jogosultságokat és frissítse ezt az oldalt a böngészőjében.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Válasszon telepítési típust:';
$this->lang['upgrade_115'] = 'Frissítés Open-Realty 1.1.5-ről';
$this->lang['install_200'] = 'Open-Realty 2.x.x új verziójának telepítése';
$this->lang['move'] = 'Kizárólag a Path és URL információk frissítése';
$this->lang['upgrade_200'] = 'Frissítés az Open-Realty 2.x.x verziójáról (2.0.0 Beta 1 vagy magasabb)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Az adatbázis-kapcsolat telepítője:";
$this->lang['install_Database_Type'] = "Adatbázis típus:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Adatbázis szerver:";
$this->lang['install_Database_Name'] = "Adatbázis neve:";
$this->lang['install_Database_User'] = "Adatbázis felhasználó:";
$this->lang['install_Database_Password'] = "Adatbázis jelszó:";
$this->lang['install_Table Prefix'] = "Tábla Prefix:";
$this->lang['install_Base_URL'] = "Alap URL:";
$this->lang['install_Base_Path'] = "Alap útvonal:";
$this->lang['install_Language'] = "Nyelv:";
$this->lang['install_English'] = "Angol";
$this->lang['install_Spanish'] = "Spanyol";
$this->lang['install_Italian'] = "Olasz";
$this->lang['install_French'] = "Francia";
$this->lang['install_Portuguese'] = "Portugál";
$this->lang['install_Russian'] = "Orosz";
$this->lang['install_Turkish'] = "Török";
$this->lang['install_German'] = "Német";
$this->lang['install_Dutch'] = "Dán";
$this->lang['install_Lithuanian'] = "Litván";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "Nem tudunk kapcsolódni az Ön adatbázisához. Kérem ellenőrizza beállításait és próbálja meg ismét.";

//Step Three
$this->lang['install_get_old_version'] = 'Régebbi Open-Realty verzió felderítése';
$this->lang['install_get_old_version_error'] = 'Hiba a régebbi Open-Realty verzió felderítése során. A frissítés nem folytatható.';
$this->lang['install_cleared_cache'] = "Cache törölve";
$this->lang['install_connection_ok'] = "Sikeresen csatlakoztunk az adatbázishoz.";
$this->lang['install_save_settings'] = "Most elmentjuk a beállításait a common.php fájlba";
$this->lang['install_settings_saved'] = "Az adatbázis beállítások mentve.";
$this->lang['install_continue_db_setup'] = "Tovább az adatbázis beállításhoz.";
$this->lang['install_populate_db'] = "Tovább lépünk az adatbázis feltöltéséhez.";

//finalize installation
$this->lang['install_installation_complete'] = "A telepítés sikeresen befejeződött.";
$this->lang['install_configure_installation'] = "Kattintson ide a telepítés testreszabásához";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Fejlesztői telepítés - Ez a telepítési mód lehetővé teszi, hogy a hibák ellenére is tovább lépjen a telepítés során. EZ NEM AJÁNLOTT!";
$this->lang['yes'] = "Igen";
$this->lang['no'] = "Nem";

?>