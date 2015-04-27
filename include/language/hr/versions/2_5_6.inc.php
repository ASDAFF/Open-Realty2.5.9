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

// CROATIAN LANGUAGE FILE - Translated by Bojan Kukuljan (neon@hgu.hr)

//error_reporting(E_ALL);
error_reporting(E_ALL& ~E_NOTICE);
global $lang;

$lang['admin_blog_manager'] = 'Upravljanje blogom';
$lang['blog_edit_post'] = "Editiranje blog upisa";
$lang['create_new_blog'] = "Novi blog upis";
$lang['user_manager_limitFeaturedListings'] = "Limitiranje # izdvojenih oglasa";
$lang['user_manager_displayorder'] = "Poredak prikazivanja";
$lang['agent_default_num_featuredlistings'] = 'Broj izdvojenih oglasa';
$lang['agent_default_num_featuredlistings_desc'] = 'Ovo je broj izdvojenih oglasa koje agenti mogu dodati po defaultu. (-1 je neograničeno).';
$lang['admin_listings_editor_featuredlistingerror'] = 'Pogreška. Ovaj oglas nije označen kao izdvojeni oglas.';
$lang['blog_meta_description'] = 'Meta opis';
$lang['blog_meta_keywords'] = 'Meta ključne riječi';
$lang['delete_blog'] = 'Brisanje blog unosa';
$lang['blog_read_story'] = 'Pročitajte cijeli članak';
$lang['blog_comments'] = 'Komentari';
$lang['blog_post_by'] = 'Autor';
$lang['blog_comment_by'] = 'Autor komentara';
$lang['blog_comment_on'] = 'dana';
$lang['blog_must_login'] = 'Morate se logirati kako biste upisali komentar.';
$lang['blog_comment_subject'] = 'Naslov';
$lang['blog_comment_submit'] = 'Pošalji komentar';
$lang['blog_add_comment'] = 'Dodaj komentar';
$lang['header_pclass'] = 'Kategorija';
$lang['delete_all_images'] = 'Brisanje svih slika u oglasu';
$lang['confirm_delete_all_images'] = 'Jeste li sigurni da želite obrisati sve slike oglasa?';
$lang['admin_addon_manager'] = 'Upravljanje addonima';
$lang['warning_addon_folder_not_writeable'] = 'Vaš addon direktorij nije označen kao writeable, addon manager neće raditi ispravno.';
$lang['addon_name'] = 'Addon ime';
$lang['addon_version'] = 'Addon verzija';
$lang['addon_status'] = 'Addon status';
$lang['addon_actions'] = 'Akcije';
$lang['addon_dir_removed'] = 'Addon direktorij je uklonjen, bez deinstalacije addona';
$lang['addon_files_removed'] = 'Addon datoteke su uklonjene, bez deinstalacije addona';
$lang['addon_ok'] = 'Addon je u redu';
$lang['addon_check_for_updates'] = 'Provjerite update ovog addona';
$lang['addon_view_docs'] = 'Pregled addon pomoći';
$lang['addon_uninstall'] = 'Deinstaliraj addon';
$lang['removed_addon'] = 'Uklonjen addon';
$lang['addon_does_not_support_updates'] = 'Addon ne podržava update sistem';
$lang['addon_update_server_not_avaliable'] = 'Update server za ovaj addon trenutno nije dostupan';
$lang['addon_update_file_not_avaliable'] = 'Update datoteka za ovaj addon trenutno nije dostupna';
$lang['addon_already_latest_version'] = 'Već imate posljednju verziju addona ';
$lang['addon_update_successful'] = 'Update uspješan';
$lang['addon_update_failed'] = 'Update nije uspješan';
$lang['addon_manager_ext_help_link'] = 'Pregled vanjske dokumentacije za pomoć';
$lang['addon_manager_template_tags'] = 'Tagovi predloška';
$lang['addon_manager_action_urls'] = 'Akcijski URL-ovi';
$lang['user_editor_can_manage_addons'] = 'Može upravljati addonima?';
$lang['user_editor_blog_privileges'] = 'Blog ovlasti?';
$lang['blog_perm_subscriber'] = 'Pretplatnik';
$lang['blog_perm_contributor'] = 'Suradnik';
$lang['blog_perm_author'] = 'Autor';
$lang['blog_perm_editor'] = 'Urednik';
$lang['site_config_heading_signup_settings'] = 'Postavke registracije';
$lang['signup_image_verification'] = 'Korištenje slike za verifikaciju na stranici za registraciju:';
$lang['signup_image_verification_desc'] = 'Dodaje sliku sa kodom kod obrazaca za registraciju. Korisnici moraju upisati kod sa slike u polje obrasca. Server mora imati instaliran GD image libraries.';
$lang['signup_verification_code_not_valid'] = 'Niste unijeli ispravan kod verifikacije.';
$lang['site_email'] = 'E-mail adresa stranice';
$lang['site_email_desc'] = 'Opcionalna e-mail adresa koja će se koristiti kao pošiljatelj e-mailova za poruke sa stranice. Ako ostavite prazno, koristiti će se administratorska e-mail adresa.';
$lang['admin_send_notices'] = 'Slanje e-maila o novim oglasima';
$lang['send_notices_tool_tip'] = 'Ako odaberete da, ovaj oglas će biti poslan svim korisnicima ako oglas odgovara snimljenim pretragama korisnika.';
$lang['controlpanel_mbstring_enabled'] = 'MBString je omogućen na serveru?';
$lang['mbstring_enabled_desc'] = 'MBString (MultiByte) je omogućen na serveru? <strong>Defaultno je odabrano "Ne"</strong>. Provjerite kod vašeg hostera ako može biti odabrano "Da" - potrebno je jedino ako namjeravate koristiti "posebne znakove" (Višejezična opcija) u bazi (korištenje editora stranice ili bloga sa ili bez WYSIWYG editora).';
$lang['signup_email_verification'] = 'Zahtijeva potvrdu e-mail adrese.';
$lang['signup_email_verification_desc'] = 'Ako označite da, zahtjevati će da korisnik potvrdi e-mail adresu klikom na link u registracijskom e-mailu.';
$lang['admin_new_user_email_verification'] = 'Registrirali ste se, ali trebate potvrditi svoju e-mail adresu da bi vaš korisnički račun postao aktivan. Molimo vas da potvrdite svoju e-mail adresu klikom na link u e-mailu koji smo vam poslali.';
$lang['admin_new_user_email_verification_message'] = 'Registrirali ste se uspješno, ali trebate potvrditi vašu e-mail adresu da bi korisnički račun postao aktivan. Molimo kliknite ispod da biste potvrdili e-mail adresu i aktivirali vaš korisnički račun.';
$lang['verify_email_invalid_link'] = 'Kliknuli ste na, ili ste unijeli pogrešan link.';
$lang['verify_email_thanks'] = 'Zahvaljujemo na potvrdi adrese vaše e-pošte.';
$lang['admin_favorites'] = 'Pregled favorita';
$lang['admin_saved_search'] = 'Snimljene pretrage';
$lang['blog_deleted'] = 'Blog unos obrisan.';
$lang['delete_index_blog_error'] = 'Ne možete obrisati glavni unos bloga, možete ga samo editirati.';
$lang['template_tag_for_blog'] = 'Tag predloška za blog';
$lang['link_to_blog'] = 'Link na ovaj blog';
$lang['blog_post'] = 'Post';
$lang['blog_author'] = 'Autor';
$lang['blog_keywords'] = 'Ključne riječi';
$lang['blog_date'] = 'Datum';
$lang['blog_published'] = 'Objavljeno';
$lang['blog_draft'] = 'Skice';
$lang['blog_review'] = 'Recenzije';
$lang['blog_all'] = 'Sve';
$lang['blog_title'] = 'Naslov';
$lang['blog_seo'] = 'SEO - prilagodba za tražilice';
$lang['blog_publication_status'] = 'Status objavljivanja';
$lang['listing_editor_permission_denied'] = 'Dozvola odbijena';
$lang['not_authorized'] = 'Niste ovlašteni';
$lang['blog_comment_delete'] = 'Obriši';
$lang['blog_comment_approve'] = 'Odobri';
$lang['site_config_blog'] = 'Blog';
$lang['blog_config'] = 'Blog konfiguracija';
$lang['blog_requires_moderation'] = 'Moderiranje komentara';
$lang['blog_requires_moderation_desc'] = 'Da li komentari zahtjevaju odobrenje moderatora da bi se prikazali na stranici?';
$lang['blog_permission_denied'] = 'Dozvola odbijena';
$lang['email_address_already_used'] = 'Drugi korisnik već koristi e-mail adresu..';

?>