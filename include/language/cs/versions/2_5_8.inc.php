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

// CZECH LANGUAGE FILE

//error_reporting(E_ALL);
error_reporting(E_ALL& ~E_NOTICE);
global $lang;

$lang['delete_addon'] = 'Jste si jisti, že chcete odinstalovat tento doplněk? Toto je trvalé a nelze vrátit zpět.';
$lang['delete_blog_entry'] = 'Jste si jisti, že chcete smazat tento Blog? Toto je trvalé a nelze vrátit zpět.';
$lang['addon_name_invalid'] = 'NEPLATNÝ NÁZEV DOPLŇKU - INJECTION ATTEMPT STOPPED';
$lang['maintenance_mode'] = 'Režim údržby';
$lang['maintenance_mode_desc'] = 'Spustit Open-Realty v režimu údržby? Všichni uživatelé (zaměstnanci, členy a veřejnost) uvidí jen strana "udržovací režim" ("maintenance_mode.html" template file). Pouze "admin" (username) LOGGED bude schopen "vidět" webové stránky jako obvykle.';
$lang['addon_doesnt_exist'] = 'The function you are trying to perform does not exist.';
$lang['notify_template'] = 'Listing Notification Template';
$lang['notify_template_desc'] = 'This is the template used to send members notification of new listings that match their saved searches.';
$lang['notify_unsubscribe_text'] = 'You can unsubscript and/or modify your listing notification subscriptions at the following URL ';
$lang['notify_listings'] = 'The following listings have been added or modified that match your saved listing search(s) on our website. Please contact us with any questions you have about these or other properties you are interested in.';
$lang['notify_saved_search_link'] = 'View/Modify Saved Searches';

?>