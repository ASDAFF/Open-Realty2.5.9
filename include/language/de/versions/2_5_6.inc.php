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

// GERMAN LANGUAGE FILE - Translated by CanariasData (Oct/2009)

//error_reporting(E_ALL);
error_reporting(E_ALL& ~E_NOTICE);
global $lang;

$lang['admin_blog_manager'] = 'Blog Manager';
$lang['blog_edit_post'] = "Blog Eintrag editieren";
$lang['create_new_blog'] = "Neuer Blog Eintrag";
$lang['user_manager_limitFeaturedListings'] = "Limit der Anzahl besonderer Objekte";
$lang['user_manager_displayorder'] = "Anzeigenreihenfolge";
$lang['agent_default_num_featuredlistings'] = 'Anzahl besonderer Objekte';
$lang['agent_default_num_featuredlistings_desc'] = 'Hier bestimmem Sie die Anzahl an hervorgehobenen Objekten die ein Makler maximal haben darf (-1 bedeutet unlimitiert).';
$lang['admin_listings_editor_featuredlistingerror'] = 'Das Limit an besonderen Objekten wurde erreicht. Dieses Objekt wurde deshalb nicht als besonders makiert';
$lang['blog_meta_description'] = 'Meta Beschreibung';
$lang['blog_meta_keywords'] = 'Meta Keywords';
$lang['delete_blog'] = 'Blog Eintrag löschen';
$lang['blog_read_story'] = 'Weiter lesen...';
$lang['blog_comments'] = 'Kommentare';
$lang['blog_post_by'] = 'Geschrieben von:';
$lang['blog_comment_by'] = 'Kommentiert von:';
$lang['blog_comment_on'] = 'am';
$lang['blog_must_login'] = 'Sie müssen angemeldet sein um einen Kommentar zu verfassen.';
$lang['blog_comment_subject'] = 'Betreff';
$lang['blog_comment_submit'] = 'Kommentar senden';
$lang['blog_add_comment'] = 'Kommentar hinzufügen';
$lang['header_pclass'] = 'Objekt Klassen';
$lang['delete_all_images'] = 'Alle Objektbilder löschen';
$lang['confirm_delete_all_images'] = 'Sind Sie sicher, dass Sie alle Fotos zu diesem Objekt löschen möchten?';
$lang['admin_addon_manager'] = 'Addon Manager';
$lang['warning_addon_folder_not_writeable'] = 'Die Rechte für dem Addon Ordner sind nicht richtig gesetzt (chmod 777 oder 755). Der Addon Manager kann so nicht funktionieren.';
$lang['addon_name'] = 'Addon Name';
$lang['addon_version'] = 'Addon Version';
$lang['addon_status'] = 'Addon Status';
$lang['addon_actions'] = 'Aktionen';
$lang['addon_dir_removed'] = 'Der Addon-Ordner wurde entfernt ohne das Addon zu deinstallieren';
$lang['addon_files_removed'] = 'Dateien des Addons wurden entfernt ohne das Addon zu deinstallieren';
$lang['addon_ok'] = 'Addon Ok';
$lang['addon_check_for_updates'] = 'Auf aktuelle Addon Version prüfen';
$lang['addon_view_docs'] = 'Addon Hilfe anzeigen';
$lang['addon_uninstall'] = 'Addon deinstallieren';
$lang['removed_addon'] = 'Addon wurde entfernt';
$lang['addon_does_not_support_updates'] = 'Dieses Addon unterstützt das Update-System nicht';
$lang['addon_update_server_not_avaliable'] = 'Der Update Server für dieses Addon ist nicht erreichbar';
$lang['addon_update_file_not_avaliable'] = 'Die aktuallisierte Datei ist leider nicht verfügbar';
$lang['addon_already_latest_version'] = 'Sie benutzen bereits die aktuelle Version von';
$lang['addon_update_successful'] = 'Aktuallisierung erfolgreich';
$lang['addon_update_failed'] = 'Aktuallisierung fehlgeschlagen';
$lang['addon_manager_ext_help_link'] = 'Externe Dokumentation anzeigen';
$lang['addon_manager_template_tags'] = 'Template Tags';
$lang['addon_manager_action_urls'] = 'Action URLS';
$lang['user_editor_can_manage_addons'] = 'Kann Addons verwalten?';
$lang['user_editor_blog_privileges'] = 'Kann Bloggen?';
$lang['blog_perm_subscriber'] = 'Subscriber';
$lang['blog_perm_contributor'] = 'Contributor';
$lang['blog_perm_author'] = 'Author';
$lang['blog_perm_editor'] = 'Editor';
$lang['site_config_heading_signup_settings'] = 'Anmeldung';
$lang['signup_image_verification'] = 'CAPTCHA verwenden:';
$lang['signup_image_verification_desc'] = 'Fügt dem Anmeldeformular eine Sicherheitsüberprüfung zu, bei der die Person einen im Bild angezeigten Code wiederholen muss. Der Server muss die GD image libraries installiert haben.';
$lang['signup_verification_code_not_valid'] = 'Sie haben den Sicherheitscode falsch wiederholt.';
$lang['site_email'] = 'Site Email Adresse';
$lang['site_email_desc'] = 'Optionale Email Adresse an als Absender der Webseiteninfos verwendet werden soll. Lassen Sie das Feld leer um die Admin Email zu verwenden.';
$lang['admin_send_notices'] = 'Über neue Objekte informieren?';
$lang['send_notices_tool_tip'] = 'Wenn Sie hier "Ja" wählen, wird den Benutzern Ihrer Webseite eine Email gesendet, falls ein neues Objekt seinen gespeicherten Suchergebnissen entspricht.';
$lang['controlpanel_mbstring_enabled'] = 'Ist MBString auf Ihrem Server aktiviert?';
$lang['mbstring_enabled_desc'] = 'Ist MBString (MultiByte) auf Ihrem Server aktiviert? <strong>Normalerweise ist diese Einstellung auf "Nein" gesetzt</strong>. Bitte fragen Sie Ihren Hosting Support, ob diese Einstellung verwendet werden kann. - MBString wird nur benötigt wenn Sie Sonderzeichen in der Datenbank speichern wollen (Multilinguale Webseiten) und dazu den Blog oder den internen Webseiteneditor benutzen möchten.';
$lang['signup_email_verification'] = 'Notwendige Email Verifizierung.';
$lang['signup_email_verification_desc'] = 'Wir diese Option auf "Ja" gesetzt muss der neu registrierte Benutzer zuerst einen Link in einer Bestätigungsmail anklicken bevor die Registrierung abgeschlossen ist.';
$lang['admin_new_user_email_verification'] = 'Danke, Ihre Registrierung ist nun fast abgeschlossen. Sie haben gerade eine Email mit einen Link zur Verifizierung ihrer Email Adresse erhalten. Bitte bestätigen Sie durch klick auf den Link, dass die bei der Registrierung verwendete Email Adresse Ihre eigene ist.';
$lang['admin_new_user_email_verification_message'] = 'Danke, Ihre Registrierung ist nun fast abgeschlossen. Bitte bestätigen Sie duch klick auf den Link, das die bei der Registrierung verwendete Email Adresse Ihre ist und aktivieren Sie damit gleichzeitig ihr Konto';
$lang['verify_email_invalid_link'] = 'Sie haben auf einen falschen oder ungültigen Link geklickt.';
$lang['verify_email_thanks'] = 'Danke, Ihre Email-Adresse ist nun bestätigt.';
$lang['admin_favorites'] = 'Favoriten ansehen';
$lang['admin_saved_search'] = 'Gespeicherte Suchergebnisse';
$lang['blog_deleted'] = 'Blog-Eintrag gelöscht';
$lang['delete_index_blog_error'] = 'Sie können den letzten Blog-Eintrag nicht löschen, nur editieren ist möglich.';
$lang['template_tag_for_blog'] = 'Template Tag für den blog';
$lang['link_to_blog'] = 'Link zu diesem Blog';
$lang['blog_post'] = 'Eintrag';
$lang['blog_author'] = 'Autor';
$lang['blog_keywords'] = 'Schlüsselworte';
$lang['blog_date'] = 'Datum';
$lang['blog_published'] = 'Veröffentlicht';
$lang['blog_draft'] = 'Rohfassung';
$lang['blog_review'] = 'Review';
$lang['blog_all'] = 'Alle';
$lang['blog_title'] = 'Titel';
$lang['blog_seo'] = 'Suchmaschinen Optimierung';
$lang['blog_publication_status'] = 'Publikations Status';
$lang['listing_editor_permission_denied'] = 'Zugriff verweigert';
$lang['not_authorized'] = 'Nicht Authoriziert';
$lang['blog_comment_delete'] = 'Löschen';
$lang['blog_comment_approve'] = 'Freigeben';
$lang['site_config_blog'] = 'Blog';
$lang['blog_config'] = 'Blog Konfiguration';
$lang['blog_requires_moderation'] = 'Kommentare moderieren';
$lang['blog_requires_moderation_desc'] = 'Müssen neue Kommentare von einem Moderator freigegeben werden, bevor sie auf der Seite erscheinen?';
$lang['blog_permission_denied'] = 'Zugriff verweigert';
$lang['email_address_already_used'] = 'Diese Email Adresse wird bereits von einem anderen Benutzer verwendet.';

?>