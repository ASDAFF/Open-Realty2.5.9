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

// DUTCH LANGUAGE FILE - Translated Isabel Heylen (info@inwebsname.com)

//error_reporting(E_ALL);
error_reporting(E_ALL& ~E_NOTICE);
global $lang;

$lang['admin_blog_manager'] = 'Blog Manager';
$lang['blog_edit_post'] = "Bewerken Blog Post";
$lang['create_new_blog'] = "Nieuwe Blog Ingave";
$lang['user_manager_limitFeaturedListings'] = "Limiet # Aktiewoningen";
$lang['user_manager_displayorder'] = "Volgorde weergeven";
$lang['agent_default_num_featuredlistings'] = 'Aantal aktiewoningen';
$lang['agent_default_num_featuredlistings_desc'] = 'Dit is het aantal aktiewoningen dat agenten per definitie kunnen aanmaken. (-1 is onbeperkt).';
$lang['admin_listings_editor_featuredlistingerror'] = 'Featured Listing Limit Hit. This listing was not marked as featured.';
$lang['blog_meta_description'] = 'Meta Beschrijving';
$lang['blog_meta_keywords'] = 'Meta Sleutelwoorden';
$lang['delete_blog'] = 'Verwijder Blog Ingave';
$lang['blog_read_story'] = 'Lees de rest van dit artikel';
$lang['blog_comments'] = 'Reacties';
$lang['blog_post_by'] = 'Artikel Door';
$lang['blog_comment_by'] = 'Reactie Door';
$lang['blog_comment_on'] = 'aan';
$lang['blog_must_login'] = 'U moet aanmelden om een reactie te kunnen plaatsen.';
$lang['blog_comment_subject'] = 'Onderwerp';
$lang['blog_comment_submit'] = 'Reactie verzenden';
$lang['blog_add_comment'] = 'Reactie Toevoegen';
$lang['header_pclass'] = 'Property Class';
$lang['delete_all_images'] = 'Verwijder alle woning afbeeldingen';
$lang['confirm_delete_all_images'] = 'Bent u zeker dat u alle afbeeldingen van de woningen wilt verwijderen?';
$lang['admin_addon_manager'] = 'Addon Manager';
$lang['warning_addon_folder_not_writeable'] = 'Your addon directory is not writeable, the addon manager will not work correctly.';
$lang['addon_name'] = 'Addon Naam';
$lang['addon_version'] = 'Addon Versie';
$lang['addon_status'] = 'Addon Status';
$lang['addon_actions'] = 'Acties';
$lang['addon_dir_removed'] = 'Addon Map werd verwijderd, de addon is nog steeds ge•nstalleerd';
$lang['addon_files_removed'] = 'De Addon Bestanden werden verwijderd, de addon is nog steeds ge•nstalleerd';
$lang['addon_ok'] = 'Addon Ok';
$lang['addon_check_for_updates'] = 'Check voor updates voor deze addon';
$lang['addon_view_docs'] = 'Bekijk Addon Help';
$lang['addon_uninstall'] = 'Addon desinstalleren';
$lang['removed_addon'] = 'Add-on verwijderd';
$lang['addon_does_not_support_updates'] = 'Add-on does not support update system';
$lang['addon_update_server_not_avaliable'] = 'Update server voor deze addon is momenteel niet beschikbaar';
$lang['addon_update_file_not_avaliable'] = 'Update bestand voor deze addon is momenteel niet beschikbaar';
$lang['addon_already_latest_version'] = 'U hebt reeds de laatste versie van ';
$lang['addon_update_successful'] = 'Update successvol';
$lang['addon_update_failed'] = 'Update mislukt';
$lang['addon_manager_ext_help_link'] = 'Raadpleeg Externe Help Documentatie';
$lang['addon_manager_template_tags'] = 'Sjabloon Tags'; // tags of etiketten??
$lang['addon_manager_action_urls'] = 'Action URLS';
$lang['user_editor_can_manage_addons'] = 'Kan addons beheren?';
$lang['user_editor_blog_privileges'] = 'Blogging Privileges?';
$lang['blog_perm_subscriber'] = 'Inschrijver'; 
$lang['blog_perm_contributor'] = 'Medewerker';
$lang['blog_perm_author'] = 'Auteur';
$lang['blog_perm_editor'] = 'Editor';
$lang['site_config_heading_signup_settings'] = 'Instellingen Signup';
$lang['signup_image_verification'] = 'Gebruik afbeelding ter controle op de signup pagina:';
$lang['signup_image_verification_desc'] = 'Voegt een afbeeldingscode toe aan de inschrijfformulieren. Gebruikers dienen de code in te geven om het formulier te kunnen verzenden. Vereiste is dat de server GD image libraries ge•nstalleerd heeft.';
$lang['signup_verification_code_not_valid'] = 'U heeft de verkeerde controle code ingegeven.';
$lang['site_email'] = 'Site Email Adres';
$lang['site_email_desc'] = 'Extra email adres te gebruiken als afzender van de site emails. Indien leeg, zal het email adres van de admin worden gebruikt.';
$lang['admin_send_notices'] = 'Stuur Emails Nieuwe Woningen';
$lang['send_notices_tool_tip'] = 'Ja selecteren, zal deze woning versturen naar alle gebruikers voor wie deze woning overeenkomt met een van hun bewaarde zoekopdrachten.';
$lang['controlpanel_mbstring_enabled'] = 'MBString is enabled at the server?';
$lang['mbstring_enabled_desc'] = 'MBString (MultiByte) is enabled at the server? <strong>By default it is set to "No"</strong>. Check with your hosting support if it can be set to "Yes" - it is only needed if you are going to store "special characters" (Multilingual Feature) at the Database (using the Page/Blog Editor with or without a WYSIWYG Editor).';
$lang['signup_email_verification'] = 'Bevestiging email adres is nodig.';
$lang['signup_email_verification_desc'] = 'Indien ingesteld op ja, zal de gebruiker zijn/haar email adres moeten bevestigen via klikken op een link in de signup email.';
$lang['admin_new_user_email_verification'] = 'U bent nu geregistreerd, maar u dient eerst uw emailadres te bevestigen alvorens uw account geactiveerd wordt. Er werd een email naar uw email adres gestuurd. Gelieve uw email adres te bevestigen door te klikken op de link in de email.';
$lang['admin_new_user_email_verification_message'] = 'U bent nu geregistreerd, maar u dient eerst uw emailadres te bevestigen alvorens uw account geactiveerd wordt. Gelieve op de link hieronder te klikken om uw email adres te bevestigen en uw account te activeren.';
$lang['verify_email_invalid_link'] = 'U hebt op een ongeldige link geklikt of een ongeldige link ingegeven.';
$lang['verify_email_thanks'] = 'Bedankt voor het bevestigen van uw email adres.';
$lang['admin_favorites'] = 'Bekijk Favorieten';
$lang['admin_saved_search'] = 'Bewaarde Zoekopdrachten';
$lang['blog_deleted'] = 'Blog Ingave Verwijderd.';
$lang['delete_index_blog_error'] = 'U kan geen hoofd blog ingave verwijderen, u kan deze enkel bewerken.';
$lang['template_tag_for_blog'] = 'Sjabloon tag voor blog';
$lang['link_to_blog'] = 'Link naar deze blog';
$lang['blog_post'] = 'Post';
$lang['blog_author'] = 'Auteur';
$lang['blog_keywords'] = 'Zoekwoorden';
$lang['blog_date'] = 'Datum';
$lang['blog_published'] = 'Gepubliceerd';
$lang['blog_draft'] = 'Draft';
$lang['blog_review'] = 'Review';
$lang['blog_all'] = 'Alles';
$lang['blog_title'] = 'Titel';
$lang['blog_seo'] = 'Zoekmachine Optimalisatie';
$lang['blog_publication_status'] = 'Status Publicatie';
$lang['listing_editor_permission_denied'] = 'Toestemming Geweigerd';
$lang['not_authorized'] = 'Niet Toegelaten';
$lang['blog_comment_delete'] = 'Verwijder';
$lang['blog_comment_approve'] = 'Goedkeuren';
$lang['site_config_blog'] = 'Blog';
$lang['blog_config'] = 'Blog Configuratie';
$lang['blog_requires_moderation'] = 'Moderate comments';
$lang['blog_requires_moderation_desc'] = 'Moeten reacties toestemming krijgen van de moderator alvorens op de site te tonen?';
$lang['blog_permission_denied'] = 'Toestemming Geweigerd'; 
$lang['email_address_already_used'] = 'Email adres reeds in gebruik voor een andere gebruiker.';

?>