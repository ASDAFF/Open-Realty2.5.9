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

// GERMAN LANGUAGE FILE - Translated by CanariasData (Aug/2008)

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Open-Realty Installation";
$this->lang['install_version_warn'] = "Die auf Ihrem Server installierte PHP Version ist nicht in der Lage diese Version von Open-Realty auszuführen. Die Installation wurde abgebrochen.";
$this->lang['install_sqlversion_warn'] = "Die auf Ihrem Server installierte MySql Version ist nicht in der Lage diese Version von Open-Realty auszuführen. Die Installation wurde abgebrochen";
$this->lang['install_php_version'] = "Die auf Ihrem Server installierte PHP Version ist ";
$this->lang['install_sql_version'] = "Die auf Ihrem Server installierte MySql Version ist ";
$this->lang['install_php_required'] = "Die aktuelle Version von Open-Realty benötigt mindestens PHP Version ";
$this->lang['install_sql_required'] = "Die aktuelle Version von Open-Realty benötigt mindestens MYSql Version ";
$this->lang['install_welcome'] = "Willkommen beim Open-Realty Installations Assistenten.";
$this->lang['install_intro'] = "Dieser Assistent wird Sie Schritt für Schritt durch die Installation des Open-Realty Systems leiten. Bevor Sie anfangen, müssen sie eine leere Datenbank auf ihren System eingerichtet und die Zugangsdaten zu dieser Datenbank bereit haben. Desweitern müssen Sie einigen Ordnern und Dateien auf ihrem Server bestimmte Berechtigungen zugewiesen haben. Je Nach Server brauchen die folgenden Dateien die Berechtigung 755 oder 777: (include/common.php, images/listing_photo, images/user_photos, images/page_uploads, images/vtour_photos, files/listings, files/users).   Aus Sicherheitsgründen sollten Sie nach erfolgreicher Installation die Rechte für die common.php Datei wieder auf 644 setzen. Bitte lesen Sie <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">Installationsanweisung (nur Englisch)</a> für weitere Hilfestellung.";
$this->lang['install_step_one_header'] = "Schritt 1: Dateiberechtigung prüfen:";
$this->lang['install_Permission_on'] = "Rechte von";
$this->lang['install_are_correct'] = "sind korrekt";
$this->lang['install_are_incorrect'] = "sind falsch";
$this->lang['install_all_correct'] = "Alle Rechte sind korrekt gesetzt.";
$this->lang['install_continue'] = "Hier klicken um die Installation fortzusetzen";
$this->lang['install_please_fix'] = "Bitte überprüfen sie die Dateiberechtigungen und aktuallisieren sie diese Seite in ihrem Browser.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Art der Installation wählen:';
$this->lang['upgrade_115'] = 'Upgrade von Open-Realty 1.1.5';
$this->lang['install_200'] = 'Neuinstallation von Open-Realty 2.x.x';
$this->lang['move'] = 'Nur Pfad und URL Information ändern';
$this->lang['upgrade_200'] = 'Upgrade von Open-Realty 2.x.x (2.0.0 Beta 1 oder später)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Datenbankverbindung einrichten:";
$this->lang['install_Database_Type'] = "Databank Typ:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Datenbank Server:";
$this->lang['install_Database_Name'] = "Datenbank Name:";
$this->lang['install_Database_User'] = "Datenbank User:";
$this->lang['install_Database_Password'] = "Datenbank Password:";
$this->lang['install_Table Prefix'] = "Tabellen Prefix:";
$this->lang['install_Base_URL'] = "Base URL:";
$this->lang['install_Base_Path'] = "Base Pfad:";
$this->lang['install_Language'] = "Sprache:";
$this->lang['install_English'] = "English";
$this->lang['install_Spanish'] = "Spanish";
$this->lang['install_Italian'] = "Italian";
$this->lang['install_French'] = "French";
$this->lang['install_Portuguese'] = "Portuguese";
$this->lang['install_Russian'] = "Russian";
$this->lang['install_Turkish'] = "Turkish";
$this->lang['install_German'] = "Deutsch";
$this->lang['install_Dutch'] = "Dutch";
$this->lang['install_Lithuanian'] = "Lithuanian";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "Wir konnten uns nicht mit Ihrer Datenbank verbinden. Bitte überprüfen Sie die Einstellungen und versuchen Sie es erneut.";

//Step Three
$this->lang['install_get_old_version'] = 'Suche nach einer alten Open-Realty Version';
$this->lang['install_get_old_version_error'] = 'Es ist ein Fehler bei der Suche nach einer früheren Version von Open-realty aufgetreten. Upgrade kann nicht fortgesetzt werden.';
$this->lang['install_cleared_cache'] = "gelöschter Cache";
$this->lang['install_connection_ok'] = "Wir konnten uns erfolgreich mit der Datenbank verbinden.";
$this->lang['install_save_settings'] = "Nun werden Ihre Daten in der common.php Datei speichern";
$this->lang['install_settings_saved'] = "Datenbank Einstellungen gespeichet.";
$this->lang['install_continue_db_setup'] = "Datenbank einrichten.";
$this->lang['install_populate_db'] = "Wir werden nun die benötigten Tabellen einrichten.";

//finalize installation
$this->lang['install_installation_complete'] = "Installation abgeschlossen.";
$this->lang['install_configure_installation'] = "Klicken Sie hier um Ihr System zu konfigurieren";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Installation im Developer Modus  - Unterbricht die Installation nicht, sebst wenn fehler auftreten. NICHT ZU EMPFEHLEN.";
$this->lang['yes'] = "Ja";
$this->lang['no'] = "Nein";

?>