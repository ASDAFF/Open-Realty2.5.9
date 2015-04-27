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

// FRENCH LANGUAGE FILE - Translated by 

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Installation de Open-Realty ";
$this->lang['install_version_warn'] = "Votre version de php ne peut pas fonctionner avec cette version de Open-Realty. Installation annulée";
$this->lang['install_sqlversion_warn'] = "Votre version de mysql ne peut pas fonctionner avec cette version de Open-Realty. Installation annulée";
$this->lang['install_php_version'] = "Votre version de PHP est ";
$this->lang['install_sql_version'] = "Votre version de MySql est ";
$this->lang['install_php_required'] = "Cette version de Open-Realty exige une version minimum PHP de $appPHP";
$this->lang['install_sql_required'] = "Cette version de Open-Realty exige une version minimum MySql";
$this->lang['install_welcome'] = "Bienvenue pour l'installation pas à  pas de Open-Realty.";
$this->lang['install_intro'] = "Cet outil va vous permettre l'installation de Open-Realty. D'abord il faut créez une base de données vide sur votre système.  Vous devez également avoir des permissions de fichiers réglés à  777 sur les fichiers ou les répertoires suivants (include/common.php, images/listing_photo, images/user_photos, cache). Quand l'installation est terminée, vous devrez protéger le common.php en 644, pour des raisons de sécurité. SVP Lire <a href=\"http://wiki.open-realty.org/index.php/Install_guide\"> Guide d'installation</a> pour toutes les procédures.";
$this->lang['install_step_one_header'] = "Etappe 1:  Vérifiez les droits des fichiers:";
$this->lang['install_Permission_on'] = "Vérification des permissions sur";
$this->lang['install_are_correct'] = "droits ok";
$this->lang['install_are_incorrect'] = "modifiez les droits";
$this->lang['install_all_correct'] = "Toutes les permissions sont ok.";
$this->lang['install_continue'] = "Cliquez pour continuer l'installation";
$this->lang['install_please_fix'] = "Veuillez fixer vos permissions de fichiers et renouveller cette page.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = "Choisissez votre type d'installation:";
$this->lang['upgrade_115'] = "Mise à  jours à  partir de la version Open-Realty 1.1.5";
$this->lang['install_200'] = "Nouvelle installation de Open-Realty 2.x.x";
$this->lang['move'] = "Mise à  jour seulement des informations Localisation de Open-Realty et  URL ";
$this->lang['upgrade_200'] = "Mise à  jours à  partir de la version Open-Realty 2.x.x (2.0.0 Beta 1 et Plus)";

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Configuration de connection à  la Base de données:";
$this->lang['install_Database_Type'] = "Type De Base de données:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Serveur De Base de données:";
$this->lang['install_Database_Name'] = "Nom De Base de données:";
$this->lang['install_Database_User'] = "Utilisateur De Base de données:";
$this->lang['install_Database_Password'] = "Mot de passe De Base de données:";
$this->lang['install_Table Prefix'] = "Préfixe des Tables:";
$this->lang['install_Base_URL'] = "URL De Base:";
$this->lang['install_Base_Path'] = "Localisation de Open-Realty:";
$this->lang['install_Language'] = "Language:";
$this->lang['install_English'] = "Anglais";
$this->lang['install_Spanish'] = "Espagnol";
$this->lang['install_Italian'] = "Italien";
$this->lang['install_French'] = "Français";
$this->lang['install_Portuguese'] = "Portugais";
$this->lang['install_Russian'] = "Russe";
$this->lang['install_Turkish'] = "Turc";
$this->lang['install_German'] = "Allemand";
$this->lang['install_Dutch'] = "Néerlandais";
$this->lang['install_Lithuanian'] = "Lituanien";
$this->lang['install_Arabic'] = "Arabe";
$this->lang['install_Polish'] = "Polonais";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "Impossible de se connecter à votre base de données. S'il vous plaît verifier vos paramètres et réessayez.";

//Step Three
$this->lang['install_get_old_version'] = "Détermination de l'ancienne version de Open-Realty";
$this->lang['install_get_old_version_error'] = "Erreur de détermination de l'ancienne version de Open-Realty. La mise à jour ne peut pas continuer.";
$this->lang['install_cleared_cache'] = "Vider le Cache";
$this->lang['install_connection_ok'] = "Connexion à la base de données OK.";
$this->lang['install_save_settings'] = "Nous allons maintenant sauvegarder vos paramètres dans le fichier common.php";
$this->lang['install_settings_saved'] = "Les paramètres de la Base de données sont sauvegardés.";
$this->lang['install_continue_db_setup'] = "Continuez l'installation de la base de données.";
$this->lang['install_populate_db'] = "Nous allons maintenant remplir la base de données.";

//finalize installation
$this->lang['install_installation_complete'] = "L'installation est terminée.";
$this->lang['install_configure_installation'] = "Cliquez ici pour configurer votre installation";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Installation en mode développateur - Ceci permettra de continuer l'installation même s'il existe des erreurs. Ceci n'est donc pas recommandé.";
$this->lang['yes'] = "Oui";
$this->lang['no'] = "Non";

?>