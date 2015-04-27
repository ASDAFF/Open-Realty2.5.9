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

// CZECH LANGUAGE FILE - Translated by AdriaticOnline.eu (info@adriaticonline.eu)

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Open-Realty instalace";
$this->lang['install_version_warn'] = "Vaše verze PHP není schopna spustit aktuální verzi Open-Realty .Instalace byla zrušena";
$this->lang['install_sqlversion_warn'] = "Vaše verze MySql není schopna spustit aktuální verzi Open-Realty .Instalace byla zrušena";
$this->lang['install_php_version'] = "Vaše aktuální verze PHP, je ";
$this->lang['install_sql_version'] = "Vaše aktuální verze MySql, je ";
$this->lang['install_php_required'] = "Aktuální verze Open-Realty vyžaduje minimální verzi PHP ";
$this->lang['install_sql_required'] = "Aktuální verze Open-Realty vyžaduje minimální verzi MySql ";
$this->lang['install_welcome'] = "Vítejte v průvodci instalací Open-Realty";
$this->lang['install_intro'] = "Tento průvodce instalací Vás provede nastavením Vaší Open-Realty instalace. Než začnete, musíte si vytvořit prázdnou databázi ve vašem systému. Musíte změnit oprávnění k souborům a nastavit buď na 777 nebo 755 v následujících souborech či adresářích. (include/common.php, images/listing_photo, images/user_photos)  Po dokončení instalace open-realty je doporučeno common.php vrátit zpět na 644, z bezpečnostních důvodů. Přečtěte si prosím <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">průvodce instalací</a> pro kompletní návod.";
$this->lang['install_step_one_header'] = "Krok 1: Kontrola oprávnění k souborům:";
$this->lang['install_Permission_on'] = "Oprávnění zapnuta";
$this->lang['install_are_correct'] = "Jsou správné";
$this->lang['install_are_incorrect'] = "Nejsou správné";
$this->lang['install_all_correct'] = "Všechna oprávnění jsou správná.";
$this->lang['install_continue'] = "Klikněte pro pokračování instalace";
$this->lang['install_please_fix'] = "Opravte prosím svá oprávnění k souborům a potom obnovte tuto stránku.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Zvolte typ instalace:';
$this->lang['upgrade_115'] = 'Upgrade z Open-Realty 1.1.5';
$this->lang['install_200'] = 'Nová instalace Open-Realty 2.x.x';
$this->lang['move'] = 'Update pouze Path URL informace';
$this->lang['upgrade_200'] = 'Upgrade z Open-Realty 2.x.x (2.0.0 Beta 1 nebo višší)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Nastavení připojení k databázi:";
$this->lang['install_Database_Type'] = "Typ databáze:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Server databaze:";
$this->lang['install_Database_Name'] = "Jméno databáze:";
$this->lang['install_Database_User'] = "Uživatelské jméno:";
$this->lang['install_Database_Password'] = "Heslo:";
$this->lang['install_Table Prefix'] = "Prefix tabulek:";
$this->lang['install_Base_URL'] = "URL databáze:";
$this->lang['install_Base_Path'] = "Base Path:";
$this->lang['install_Language'] = "Jazyk:";
$this->lang['install_English'] = "Anglicky";
$this->lang['install_Spanish'] = "Španělsky";
$this->lang['install_Italian'] = "Italsky";
$this->lang['install_French'] = "Francouzky";
$this->lang['install_Portuguese'] = "Portugalsky";
$this->lang['install_Russian'] = "Rusky";
$this->lang['install_Turkish'] = "Turecky";
$this->lang['install_German'] = "Německy";
$this->lang['install_Dutch'] = "Nizozemsky";
$this->lang['install_Lithuanian'] = "Litevsky";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "Není možno se připojit k databázi. Prosím zkontrolujte, nastavení a zkuste to znovu.";

//Step Three
$this->lang['install_get_old_version'] = 'Určení staré verze Open-Realty';
$this->lang['install_get_old_version_error'] = 'Chyba při určování staré verze Open-Realty. Upgrade nemůže pokračovat.';
$this->lang['install_cleared_cache'] = "Cache vymazáno";
$this->lang['install_connection_ok'] = "Připojení k databázi proběhlo úspěšně.";
$this->lang['install_save_settings'] = "Nyní proběhne uložení nastavení do souboru common.php";
$this->lang['install_settings_saved'] = "Nastavení databáze uloženo.";
$this->lang['install_continue_db_setup'] = "Pokračovat v nastavení databáze.";
$this->lang['install_populate_db'] = "Nyní proběhne načtení databáze.";

//finalize installation
$this->lang['install_installation_complete'] = "Instalace je kompletní.";
$this->lang['install_configure_installation'] = "Klikněte zde pro konfiguraci instalace";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Developer Mode Instalace - To umožní pokračovat v instalaci i s chybami. TOTO NENÍ DOPORUČENO.";
$this->lang['yes'] = "Ano";
$this->lang['no'] = "Ne";

?>