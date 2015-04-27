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

// LITHUANIAN LANGUAGE FILE - Translated by Simonas Buitkus (simonasbuitkus AT gmail DOT com)

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Nekilnojamojo turto programos Open realty įdiegimas";
$this->lang['install_version_warn'] = "Jūsų PHP versija nepalaiko programos. Įdiegimas atšauktas";
$this->lang['install_sqlversion_warn'] = "Jūsų Mysql versija nepalaiko programos. Įdiegimas atšauktas";
$this->lang['install_php_version'] = "Jūsų PHP versija yra ";
$this->lang['install_sql_version'] = "Jūsų Mysql versija yra ";
$this->lang['install_php_required'] = "Kad programa veiktų, jums reikalinga ne senesnė PHP versija nei ";
$this->lang['install_sql_required'] = "Kad programa veiktų, jums reikalinga ne senesnė Mysql versija nei ";
$this->lang['install_welcome'] = "Sveiki, čia nekilnojamojo turto programos įdiegimas.";
$this->lang['install_intro'] = "Šis vedlys jums padės įdiegti programą. Prieš jums pradedant, jūs privalote sukurti naują duomenų bazę. Jūs taip pat turite nustatyti leidimus 777 (Visi gali rašyti, skaityti, vykdyti) arba 755  (priklausomai nuo jūsų serverio nustatymų) sekantiems failams ar katalogams. (include/common.php, images/listing_photo, images/user_photos)  Kai jūs baigsite įdiegimą, programa automatiškai nustatys failui common.php leidimus atgal į 644, dėl saugumo sumetimų. Pasiskaitykite daugiau apie tai: <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">Įdiegimo pagalba </a>.";
$this->lang['install_step_one_header'] = "Pirmas žingsnis: leidimų patikrinimas: ";
$this->lang['install_Permission_on'] = "Leidimas duotas";
$this->lang['install_are_correct'] = "yra teisingas";
$this->lang['install_are_incorrect'] = "yra neteisingas";
$this->lang['install_all_correct'] = "Visi leidimai nustatyti teisingai.";
$this->lang['install_continue'] = "Spauskite čia, kad pratęstumėte įdiegimą. ";
$this->lang['install_please_fix'] = "Prašome sutvarkyti leidimus, tada perkraukite puslapį. ";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Pasirinkite įdiegimo būdą: ';
$this->lang['upgrade_115'] = 'Atnaujimas iš 1.1.5';
$this->lang['install_200'] = 'Naujas įdiegimas Open-Realty 2.x.x';
$this->lang['move'] = 'Atnaujinti įdiegtos programos nustatymus';
$this->lang['upgrade_200'] = 'Atnaujinti iš Open-Realty 2.x.x (2.0.0 Beta 1 ar didesnės)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Duomenų bazės nustatymai:";
$this->lang['install_Database_Type'] = "Duomenų bazės tipas:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Duomenų bazės serveris:";
$this->lang['install_Database_Name'] = "Duomenų bazės pavadinimas:";
$this->lang['install_Database_User'] = "Duomenų bazės vartotojo vardas:";
$this->lang['install_Database_Password'] = "Duomenų bazės vartotojo slaptažodis:";
$this->lang['install_Table Prefix'] = "Lentelių priešdėlis:";
$this->lang['install_Base_URL'] = "Duomenų bazės URL: ";
$this->lang['install_Base_Path'] = "Duomenų bazės katalogas: ";
$this->lang['install_Language'] = "Kalba:";
$this->lang['install_English'] = "Anglų";
$this->lang['install_Spanish'] = "Ispanų";
$this->lang['install_Italian'] = "Italų";
$this->lang['install_French'] = "Prancūzų";
$this->lang['install_Portuguese'] = "Portuguese";
$this->lang['install_Russian'] = "Russian";
$this->lang['install_Turkish'] = "Turkish";
$this->lang['install_German'] = "German";
$this->lang['install_Dutch'] = "Dutch";
$this->lang['install_Lithuanian'] = "Lietuvių";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "Negalėjome prisijungti prie duomenų bazės. Patikrinkite nustatymus, ar duomenų bazė sukurta ir bandykite dar kartą.";

//Step Three
$this->lang['install_get_old_version'] = 'Nustatoma senesnė versija.';
$this->lang['install_get_old_version_error'] = 'Klaida bandant nustatyti senesnę versiją. Atnaujinimas neįmanomas';
$this->lang['install_cleared_cache'] = "Atmintis išvayta";
$this->lang['install_connection_ok'] = "Prisijungimas prie duomenų bazės pavyko.";
$this->lang['install_save_settings'] = "Vyksta jūsų nustatymų išsaugojimas common.php faile";
$this->lang['install_settings_saved'] = "Nustatymai išsaugoti.";
$this->lang['install_continue_db_setup'] = "Pereikite prie duomenų bazės nustatymų.";
$this->lang['install_populate_db'] = "Vyksta duomenų bazės konfigūracija.";

//finalize installation
$this->lang['install_installation_complete'] = "Įdiegimas baigtas.";
$this->lang['install_configure_installation'] = "Spauskite čia, jei norite papildomai konfigūruoti";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Developer Mode įdiegimas - Tai leis jums tęsti netgi su klaidomis (nerekomenduojama).";
$this->lang['yes'] = "Taip";
$this->lang['no'] = "Ne";

?>