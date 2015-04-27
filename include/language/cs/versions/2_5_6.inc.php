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

// CZECH LANGUAGE FILE - Translated by AdriaticOnline.eu (info@adriaticonline.eu)

//error_reporting(E_ALL);
error_reporting(E_ALL& ~E_NOTICE);
global $lang;

$lang['admin_blog_manager'] = 'Správce blogu';
$lang['blog_edit_post'] = "Úpravy blogu";
$lang['create_new_blog'] = "Založit nový blog";
$lang['user_manager_limitFeaturedListings'] = "Limit # Top nabídky";
$lang['user_manager_displayorder'] = "Pořadí zobrazení";
$lang['agent_default_num_featuredlistings'] = 'Počet Top nabídek';
$lang['agent_default_num_featuredlistings_desc'] = 'Toto je počet Top nabídek které agenti mohou přidat. (-1 je neomezeně).';
$lang['admin_listings_editor_featuredlistingerror'] = 'Chyba ! Tento inzerát není označen jako Top nabídka.';
$lang['blog_meta_description'] = 'Meta popis';
$lang['blog_meta_keywords'] = 'Keywords';
$lang['delete_blog'] = 'Smazat blog';
$lang['blog_read_story'] = 'Přečtěte si celý článek';
$lang['blog_comments'] = 'Komentáře';
$lang['blog_post_by'] = 'Autor';
$lang['blog_comment_by'] = 'Autor komentáře';
$lang['blog_comment_on'] = 'dne';
$lang['blog_must_login'] = 'Musíte se přihlásit k napsání komentáře.';
$lang['blog_comment_subject'] = 'Předmět';
$lang['blog_comment_submit'] = 'Poslat komentář';
$lang['blog_add_comment'] = 'Přidat komentář';
$lang['header_pclass'] = 'Kategorie';
$lang['delete_all_images'] = 'Smazat všechny fotografie v inzerátu';
$lang['confirm_delete_all_images'] = 'Jste si jisti, že si přejete smazat všechny fotografie v inzerátu?';
$lang['admin_addon_manager'] = 'Správce doplňků';
$lang['warning_addon_folder_not_writeable'] = 'Vaš adresář doplňků není zapisovatelný, správce doplňků nebude fungovat správně.';
$lang['addon_name'] = 'Název doplňku';
$lang['addon_version'] = 'Verze doplňku';
$lang['addon_status'] = 'Status doplňku';
$lang['addon_actions'] = 'Akce';
$lang['addon_dir_removed'] = 'Adresář doplňků byla odstraněn, aniž by byl odinstalován doplněk';
$lang['addon_files_removed'] = 'Složky doplňků byly odstraněny, aniž by byl odinstalován doplněk';
$lang['addon_ok'] = 'Doplňek je v pořádku';
$lang['addon_check_for_updates'] = 'Zkontrolovat aktualizace tohoto doplňku';
$lang['addon_view_docs'] = 'Zobrazit pomoc pro doplněk';
$lang['addon_uninstall'] = 'Odinstalovat doplněk';
$lang['removed_addon'] = 'Doplněk byl odstraněn';
$lang['addon_does_not_support_updates'] = 'Doplňek nepodporuje aktualizaci systému';
$lang['addon_update_server_not_avaliable'] = 'Update server pro tento doplněk je dočasně nedostupný';
$lang['addon_update_file_not_avaliable'] = 'Update složka pro tento doplněk je dočasně nedostupná';
$lang['addon_already_latest_version'] = 'Již máte naistalovanou nejnovější verzi doplňku';
$lang['addon_update_successful'] = 'Aktualizace úspěšně provedena';
$lang['addon_update_failed'] = 'Aktualizace se nezdařila';
$lang['addon_manager_ext_help_link'] = 'Přehled externí pomocné dokumentace';
$lang['addon_manager_template_tags'] = 'Tagy šablony';
$lang['addon_manager_action_urls'] = 'Akční URL';
$lang['user_editor_can_manage_addons'] = 'Může spravovat doplňky?';
$lang['user_editor_blog_privileges'] = 'Oprávnění k Blogu?';
$lang['blog_perm_subscriber'] = 'Účastník';
$lang['blog_perm_contributor'] = 'Spolupracovník';
$lang['blog_perm_author'] = 'Autor';
$lang['blog_perm_editor'] = 'Editor';
$lang['site_config_heading_signup_settings'] = 'Registrace Nastavení';
$lang['signup_image_verification'] = 'Použít obrázek ověření registrace na stránce:';
$lang['signup_image_verification_desc'] = 'Přidává kód z obrázku do formuláře registrace. Uživatelé jsou povinni zadat kód, pro odeslání formuláře. Vyžaduje, aby server měl nainstalované knihovny GD image.';
$lang['signup_verification_code_not_valid'] = 'Nezadali jste správný ověřovací kód.';
$lang['site_email'] = 'E-mail adresa stránky';
$lang['site_email_desc'] = 'Volitelná E-mailová adresu bude použita pro odesílání zpráv ze stránek. Nebude li vyplněna, bude použita administrační e-mailová adresa.';
$lang['admin_send_notices'] = 'Zasílání e-mailu o nových inzerátech';
$lang['send_notices_tool_tip'] = 'Výběr ano pošle tento seznam všem uživatelům, kde výpis odpovídá jednomu z jejich uložených vyhledávání';
$lang['controlpanel_mbstring_enabled'] = 'MBString je povolen na serveru?';
$lang['mbstring_enabled_desc'] = 'MBString (MultiByte) je povolen na serveru? <strong>Ve výchozím nastavení je nastaveno na "Ne"</strong>. Kontaktujte podporu u svého hostingu, jestli může být nastavena na "Ano" - to je jen zapotřebí, pokud budete ukládat "speciální znaky" (Multilingual funkce) v databázi (pomocí stránka / Blog Editor s nebo bez WYSIWYG editoru)';
$lang['signup_email_verification'] = 'Vyžadovat potvrzení E-mailové adresy.';
$lang['signup_email_verification_desc'] = 'Pokud označíte ano, systém bude vyžadovat, aby uživatel potvrdil svou E-mailovou adresu kliknutím na odkaz v registračním E-mailu.';
$lang['admin_new_user_email_verification'] = 'Nyní proběhla Vaše registrace, ale musíte ověřit svou E-mailovou adresu, než Váš účet bude plně aktivní. E-mail byl odeslán na vaši E-mailovou adresu. Potvrďte prosím svou E-mailovou adresu kliknutím na odkaz v E-mailu';
$lang['admin_new_user_email_verification_message'] = 'Nyní proběhla Vaše registrace ale musíte ověřit svou E-mailovou adresu, než Váš účet bude plně aktivní. Prosím, klikněte na níže uvedený odkaz a ověřte tak svou E-mailovou adresu a aktivaci účtu.';
$lang['verify_email_invalid_link'] = 'Kliknuli jste na špatný link, nebo jste zadali neplatný odkaz.';
$lang['verify_email_thanks'] = 'Děkujeme vám za ověření Vaší emailové adresy.';
$lang['admin_favorites'] = 'Zobrazit Oblíbené';
$lang['admin_saved_search'] = 'Uložená vyhledávání';
$lang['blog_deleted'] = 'Blog smazán.';
$lang['delete_index_blog_error'] = 'Nemůžete smazat hlavní blog, můžete ho pouze editovat.';
$lang['template_tag_for_blog'] = 'Šablona tagů pro blog';
$lang['link_to_blog'] = 'Link na tento blog';
$lang['blog_post'] = 'Post';
$lang['blog_author'] = 'Autor';
$lang['blog_keywords'] = 'Keywords';
$lang['blog_date'] = 'Datum';
$lang['blog_published'] = 'Publikováno';
$lang['blog_draft'] = 'Koncept';
$lang['blog_review'] = 'Recenze';
$lang['blog_all'] = 'Vše';
$lang['blog_title'] = 'Nadpis';
$lang['blog_seo'] = 'SEO optimalizace';
$lang['blog_publication_status'] = 'Status zveřejnění';
$lang['listing_editor_permission_denied'] = 'Povolení zamítnuto';
$lang['not_authorized'] = 'Není povoleno';
$lang['blog_comment_delete'] = 'Smazat';
$lang['blog_comment_approve'] = 'Schválit';
$lang['site_config_blog'] = 'Blog';
$lang['blog_config'] = 'Konfigurace blogu';
$lang['blog_requires_moderation'] = 'Moderovat komentáře';
$lang['blog_requires_moderation_desc'] = 'Poznámky vyžadují schválení moderátorem, než budou zveřejněny na webu?';
$lang['blog_permission_denied'] = 'Povolení zamítnuto';
$lang['email_address_already_used'] = 'E-mailovou adresu je již používá jiný uživatel.';

?>