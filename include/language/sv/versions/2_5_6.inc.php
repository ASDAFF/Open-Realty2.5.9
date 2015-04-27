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

// SWEDISH LANGUAGE FILE - Translated by Alexander (alex@acpu.se)

//error_reporting(E_ALL);
error_reporting(E_ALL& ~E_NOTICE);
global $lang;

$lang['admin_blog_manager']='Blogg Manager';
$lang['blog_edit_post'] = "Redigera blogginlägg";
$lang['create_new_blog'] = "Skriv nytt blogginlägg";
$lang['user_manager_limitFeaturedListings'] = "Begränsa # rekommenderade objekt";
$lang['user_manager_displayorder'] = "Visningsordning";
$lang['agent_default_num_featuredlistings'] = 'Antal rekommenderade objekt';
$lang['agent_default_num_featuredlistings_desc'] = 'Antalat rekommenderade objekt som mäklare kan lista (-1 = obegränsat).';
$lang['admin_listings_editor_featuredlistingerror'] = 'Max antal träffar för rekommenderade objekt är redan uppnått. Detta objekt blev inte inlaggt som ett rekommenderat objekt.';
$lang['blog_meta_description'] = 'Meta Beskrivning';
$lang['blog_meta_keywords'] = 'Meta nyckelord';
$lang['delete_blog'] = 'Ta bort blogginlägg ';
$lang['blog_read_story'] = 'Läs hela artikeln';
$lang['blog_comments'] = 'Kommentarer';
$lang['blog_post_by'] = 'Artikel av';
$lang['blog_comment_by'] = 'Kommentar av';
$lang['blog_comment_on'] = 'på';
$lang['blog_must_login'] = 'Du måste logga in för att kommentera artikeln.';
$lang['blog_comment_subject'] = 'Ämne';
$lang['blog_comment_submit'] = 'Ämneskommentar';
$lang['blog_add_comment'] = 'Lägg till kommentar';
$lang['header_pclass'] = 'Typ av objekt';
$lang['delete_all_images'] = 'Ta bort alla objektsbilder';
$lang['confirm_delete_all_images'] = 'Är du säker på att du vill ta bort alla objektbilder ';
$lang['admin_addon_manager'] = 'Tilläggshanteraren';
$lang['warning_addon_folder_not_writeable'] = 'Din tilläggsmapp kan inte skrivas, tilläggshanteraren kommer inte att fungera korrekt.';
$lang['addon_name'] = 'Tilläggsnamn';
$lang['addon_version'] = 'Tilläggsversion';
$lang['addon_status'] = 'Tilläggsstatus';
$lang['addon_actions'] = 'Tilläggsåtgärder';
$lang['addon_dir_removed'] = 'Tilläggsmappen har raderats, utan att tillägget först avinstallerats';
$lang['addon_files_removed'] = 'Tilläggsfilen har raderats, utan att tillägget avinstallerats';
$lang['addon_ok'] = 'Tillägg Ok';
$lang['addon_check_for_updates'] = 'Leta efter uppdateringar för detta tillägg';
$lang['addon_view_docs'] = 'Se tilläggshjälp';
$lang['addon_uninstall'] = 'Avinstallera tillägg';
$lang['removed_addon'] = 'Ta bort tillägg';
$lang['addon_does_not_support_updates'] = 'Tillägget stödjer inte uppdateringstjänsten';
$lang['addon_update_server_not_avaliable'] = 'Uppdateringsservern för detta tillägget är nere eller inte tillgänglig';
$lang['addon_update_file_not_avaliable'] = 'Uppdateringsfilen för detta tillägget kan inte hittas';
$lang['addon_already_latest_version'] = 'Du har redan den senaste versionen av ';
$lang['addon_update_successful'] = 'Uppdateringen har slutförts';
$lang['addon_update_failed'] = 'Uppdateringen misslyckadets';
$lang['addon_manager_ext_help_link'] = 'Se hjälpfil';
$lang['addon_manager_template_tags'] = 'Malltaggar';
$lang['addon_manager_action_urls'] = 'Åtgärds URL';
$lang['user_editor_can_manage_addons'] = 'Kan hantera tillägg?';
$lang['user_editor_blog_privileges'] = 'Bloggprivilegier?';
$lang['blog_perm_subscriber'] = 'Prenumerant';
$lang['blog_perm_contributor'] = 'Bidragare';
$lang['blog_perm_author'] = 'Författare';
$lang['blog_perm_editor'] = 'Redigerare';
$lang['site_config_heading_signup_settings'] = 'Registreringsegenskaper';
$lang['signup_image_verification'] = 'Använd bildbekräftelse på registreringssidan:';
$lang['signup_image_verification_desc'] = 'Använd bildbekräftelse på registreringssidan. Användarna måste ange en kod för att skicka formulär, kräver att GD image toool biblotek är installerat.';
$lang['signup_verification_code_not_valid'] = 'Du skrev in fel kodord (se bilden).';
$lang['site_email'] = 'Hemsidans E-post';
$lang['site_email_desc'] = 'Alternativ e-post att användas för hemsidans e-post, om du lämnar detta blankt används admins e-post.';
$lang['admin_send_notices'] = 'Skicka e-post med nya objekt';
$lang['send_notices_tool_tip'] = 'Om du väljer ja här så skickas detta objekt till alla som har en sparad sökning som matchar med detta objektet.';
$lang['controlpanel_mbstring_enabled'] = 'Skall MBString vara aktiv på servern?';
$lang['mbstring_enabled_desc'] = 'Skall MBString (MultiByte) vara aktivt hos servern? <strong>standard är "Nej"</strong>. Kolla med din ditt hostingboilag om detta kan ändras till "Ja" - Detta behövs endast om du vill lagra "Specialtecken" (Multilingual Feature) i databasen (då du använder sid/bloggredigeraren med eller utan WYSIWAG redigerare).';
$lang['signup_email_verification'] = 'Kräv bekräftelse av e-post adress.';
$lang['signup_email_verification_desc'] = 'Om du ställer in detta till Ja måste använderen bekräfta sin e-postadress genom att klicka på en länk som skickas till den angivna e-postadressen.';
$lang['admin_new_user_email_verification'] = 'Du är nu registrerad men för att slutföra registreringen så måste du klicka på bekräftelselänken som skickats till din e-postadress.';
$lang['admin_new_user_email_verification_message'] = 'Du är nu registrerad, klicka på länken nedan för att aktivera ditt konto.';
$lang['verify_email_invalid_link'] = 'Du har klickat på eller skrivit in en felaktig länk.';
$lang['verify_email_thanks'] = 'Thank you for verifying your email address.';
$lang['admin_favorites'] = 'View Favorites';
$lang['admin_saved_search'] = 'Sparade sökningar';
$lang['blog_deleted'] = 'Blogginklägget har tagits bort.';
$lang['delete_index_blog_error'] = 'Du kan inte ta bort huvudblogginlägget, du kan endast redigera det.';
$lang['template_tag_for_blog'] = 'Malltagg gör blogg';
$lang['link_to_blog'] = 'Länk till denna blogg';
$lang['blog_post'] = 'Inlägg';
$lang['blog_author'] = 'Författare';
$lang['blog_keywords'] = 'Nyckelord';
$lang['blog_date'] = 'Datum';
$lang['blog_published'] = 'Publiserad';
$lang['blog_draft'] = 'Utkast';
$lang['blog_review'] = 'Recension';
$lang['blog_all'] = 'Alla';
$lang['blog_title'] = 'Titel';
$lang['blog_seo']='Sök Motor Optimering';
$lang['blog_publication_status']='Publiceringsstatus';
$lang['listing_editor_permission_denied'] = "Tillstånd nekat";
$lang['not_authorized'] = 'Not Authorized';
$lang['blog_comment_delete'] = 'Delete';
$lang['blog_comment_approve'] = 'Approve';
$lang['site_config_blog'] = 'Blog';
$lang['blog_config'] = 'Blog Configuration';
$lang['blog_requires_moderation'] = 'Moderate comments';
$lang['blog_requires_moderation_desc'] = 'Do comments require moderator approval before showing on the site?';
$lang['blog_permission_denied'] = 'Permission Denied';
$lang['email_address_already_used'] = 'Email Address is already used by another user.';

?>