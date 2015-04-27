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

// LITHUANIAN LANGUAGE FILE

//error_reporting(E_ALL);
error_reporting(E_ALL& ~E_NOTICE);
global $lang;

$lang['delete_addon'] = 'Are you sure you want to UNINSTALL this Add-on? This is permanent and can not be undone.';
$lang['delete_blog_entry'] = 'Are you sure you want to DELETE this Blog Entry? This is permanent and can not be undone.';
$lang['addon_name_invalid'] = 'ADD-ON NAME IS INVALID - INJECTION ATTEMPT STOPPED';
$lang['maintenance_mode'] = 'Maintenance Mode';
$lang['maintenance_mode_desc'] = 'Run Open-Realty in Maintenance Mode? All users (agents, members and public) are going to see only a "maintenance mode" page ("maintenance_mode.html" template file). Only "admin" (username) LOGGED will be able to "see" the Website as usual.';
$lang['addon_doesnt_exist'] = 'The function you are trying to perform does not exist.';
$lang['notify_template'] = 'Listing Notification Template';
$lang['notify_template_desc'] = 'This is the template used to send members notification of new listings that match their saved searches.';
$lang['notify_unsubscribe_text'] = 'You can unsubscript and/or modify your listing notification subscriptions at the following URL ';
$lang['notify_listings'] = 'The following listings have been added or modified that match your saved listing search(s) on our website. Please contact us with any questions you have about these or other properties you are interested in.';
$lang['notify_saved_search_link'] = 'View/Modify Saved Searches';

?>