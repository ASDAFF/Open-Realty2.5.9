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

// ITALIAN LANGUAGE FILE - Translated by Sara Presenti (sara.presenti@gmail.com)

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Installazione Open Realty";
$this->lang['install_version_warn'] = "La vostra versione di php non può far funzionare la versione corrente dell'installazione. L'installazione di open-Realty è stata annullata";
$this->lang['install_sqlversion_warn'] = "La vostra versione di php non può far funzionare la versione corrente dell'installazione. L'installazione di open-Realty è stata annullata";
$this->lang['install_php_version'] = "La vostra versione corrente di PHP è ";
$this->lang['install_sql_version'] = "La vostra versione corrente di MySql è ";
$this->lang['install_php_required'] = "La versione corrente di Open-Realty richiede una versione di minimo PHP di ";
$this->lang['install_sql_required'] = "La versione corrente del Open-Realty richiede una versione di minimo MySql di ";
$this->lang['install_welcome'] = "Benvenuti all'installazione di Open Realty.";
$this->lang['install_intro'] = "Questa guida vi aiuterà nel processo di installazione di Open Realty. Prima che cominciate dovete generare un database vuoto sul vostro sistema. Dovete anche avere i permessi regolati a 777 sulle seguenti lime o gli indici (include/common.php, images/listing_photo, images/user_photos, nascondiglio) Dopo l'installazione di open-realty vi consigliamo di regolare il file common.php di nuovo a 644, per motivi di sicurezza.";
$this->lang['install_step_one_header'] = "Punto 1: Controlli i permessi";
$this->lang['install_Permission_on'] = "il permesso";
$this->lang['install_are_correct'] = "è corretto";
$this->lang['install_are_incorrect'] = "è errato";
$this->lang['install_all_correct '] = "Tutti i permessi sono corretti.";
$this->lang['install_continue'] = "Clicchi per continuare l'installazione";
$this->lang['install_please_fix'] = "Setti nuovamente i permessi e aggiorni la pagina.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Seleziona il tipo di installazione:';
$this->lang['upgrade_115'] = 'Aggiornamento di Open-Realty 1.1.5';
$this->lang['install_200'] = 'Nuova installazione di Open-Realty 2.x.x';
$this->lang['move'] = 'Installazione solo di Path e url di informazioni';
$this->lang['upgrade_200'] = 'Aggiornamento di Open-Realty 2.x.x (2.0.0 Beta 1 o )';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Setup Database Connection:";
$this->lang['install_Database_Type'] = "Tipo Della Base di dati:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Assistente Della Base di dati:";
$this->lang['install_Database_Name'] = "Nome Della Base di dati:";
$this->lang['install_Database_User'] = "Utente Della Base di dati:";
$this->lang['install_Database_Password'] = "Parola d'accesso Della Base di dati:";
$this->lang['install_Table Prefix'] = "Prefisso Della Tabella:";
$this->lang['install_Base_URL'] = "URL Della Base:";
$this->lang['install_Base_Path'] = "Percorso:";
$this->lang['install_Language'] = "Lingua:";
$this->lang['install_English'] = "Inglese";
$this->lang['install_Spanish'] = "Spagnolo";
$this->lang['install_Italian'] = "Italiano";
$this->lang['install_French'] = "Francese";
$this->lang['install_Portuguese'] = "Portoghese";
$this->lang['install_Russian'] = "Russo";
$this->lang['install_Turkish'] = "Turko";
$this->lang['install_German'] = "Tedesco";
$this->lang['install_Dutch'] = "Dutch";
$this->lang['install_Lithuanian'] = "Lituano";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "Non è stato possibile connettersi al database. Ti consigliamo di controllare le impostazioni e di provare nuovamente.";

//Step Three
$this->lang['install_get_old_version'] = 'Riscontrata una vecchia versione di Open-Realty';
$this->lang['install_get_old_version_error'] = 'Errore determinato da una vecchia versione di Open-Realty. L\'aggiornamento non può procedere.';
$this->lang['install_cleared_cache'] = "Cache cancellata";
$this->lang['install_connection_ok'] = "Possiamo collegare alla base di dati.";
$this->lang['install_save_settings'] = "Ora provvederemo a conservare le vostre impostazioni in common.php";
$this->lang['install_settings_saved'] = "Impostazioni di installazione salvate.";
$this->lang['install_continue_db_setup'] = "Continui ad installare la base di dati.";
$this->lang['install_populate_db'] = "Ora stiamo andando a popolare la base di dati.";

//finalize installation
$this->lang['install_installation_complete'] = "L'installazione è completa.";
$this->lang['install_configure_installation'] = "Clicchi qui per configurare la vostra installazione";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Developer Mode Install - In questo modo l'installazione procederà anche in caso di riscontro errori. NON E'CONSIGLIATO.";
$this->lang['yes'] = "Si";
$this->lang['no'] = "No";

?>