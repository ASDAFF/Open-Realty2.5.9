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

// FRENCH LANGUAGE FILE

//error_reporting(E_ALL);
error_reporting(E_ALL& ~E_NOTICE);
global $lang;

$lang['delete_addon'] = 'Vous êtes sur que vous voulez desinstaller cette Add-on? Ca sera definitive et est irréversible.';
$lang['delete_blog_entry'] = 'Vous êtes sur que vous voulez supprimer ce entrée de blog? Ca sera definitive et est irréversible.';
$lang['addon_name_invalid'] = "LE NOM ADD-ON N'EST PAS VALIDE - TENTATIVE D'INJECTION ARRETE";
$lang['maintenance_mode'] = "Mode d'entretien";
$lang['maintenance_mode_desc'] = "Publier Open-Realty en Mode d'entretien? Toutes utilisateurs (agents, membres et visiteurs) vont voir un page "mode entretien" ("maintenance_mode.html" template file). Que les utilisateurs "admin" (nom d'utilisateur) LOGGED peuvent voir le site web comme d'habitude.";
$lang['addon_doesnt_exist'] = "Le fonction que vous essayez d'executer n'existe pas.";
$lang['notify_template'] = "Template de notification d'annonce";
$lang['notify_template_desc'] = 'Ceci est le template utilisé pour envoyer une notification au membres de nouvelles annonces qui correspondent a leurs recherches sauvegardés.';
$lang['notify_unsubscribe_text'] = "Vous pouvez vous désinscrire et/ou modifier votre souscriptions de notification d'annonces au URL suivant";
$lang['notify_listings'] = 'Les annonces suivants ont été ajoutés ou modifiés qui correspondent aux recherche(s) que vous avez sauvegardées sur notre site internet. Veuillez nous contacter pour toute question concernant ces ou toute autre annonce que vous serez interessé.';
$lang['notify_saved_search_link'] = 'Montrer/Modifier les recherches sauvegardées';

?>