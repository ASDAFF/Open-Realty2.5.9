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

$lang['admin_blog_manager'] = 'Administrateur Blog';
$lang['blog_edit_post'] = "Editer Blog Post";
$lang['create_new_blog'] = "Nouvelle Entrée Blog";
$lang['user_manager_limitFeaturedListings'] = "Limite # d'annonces spéciales";
$lang['user_manager_displayorder'] = "Ordre d'affichage";
$lang['agent_default_num_featuredlistings'] = "Nombre d'annonces spéciales";
$lang['agent_default_num_featuredlistings_desc'] = "Le nombre d'annonces spéciales qu'un agent peut créer par default. (-1 est illimité).";
$lang['admin_listings_editor_featuredlistingerror'] = "Limite nombre d'annonces spéciales atteint. Cette annonce n'etait pas marqué comme spéciale.";
$lang['blog_meta_description'] = 'Description Meta';
$lang['blog_meta_keywords'] = 'Meta Mots clef';
$lang['delete_blog'] = 'Supprimer Blog Entry';
$lang['blog_read_story'] = 'Lire le rest de cette article';
$lang['blog_comments'] = 'Commentaires';
$lang['blog_post_by'] = 'Article Par';
$lang['blog_comment_by'] = 'Commentaire Par';
$lang['blog_comment_on'] = 'on';
$lang['blog_must_login'] = 'Vous devez login pour ecrire un commentaire.';
$lang['blog_comment_subject'] = 'Sujet';
$lang['blog_comment_submit'] = 'Soumettre Commentaire';
$lang['blog_add_comment'] = 'Ajouter Commentaire';
$lang['header_pclass'] = 'Classe de bien';
$lang['delete_all_images'] = "Supprimer toutes les images d'annonce";
$lang['confirm_delete_all_images'] = "Vous êtes sur vous voulez supprimer les images d'annonce?";
$lang['admin_addon_manager'] = 'Administration Addon';
$lang['warning_addon_folder_not_writeable'] = "Votre dossier addon n'est pas accèsible, le manager addon ne functionnera pas correct.";
$lang['addon_name'] = 'Nom Addon';
$lang['addon_version'] = 'Version Addon';
$lang['addon_status'] = 'Statut Addon';
$lang['addon_actions'] = 'Actions';
$lang['addon_dir_removed'] = 'Addon Dossiers sont supprimés, sans désinstaller le addon';
$lang['addon_files_removed'] = 'Addon Fichiers sont supprimés, sans désinstaller le addon';
$lang['addon_ok'] = 'Addon Ok';
$lang['addon_check_for_updates'] = 'Controller les mis à jour pour ce addon';
$lang['addon_view_docs'] = 'Visitez Add-on Help';
$lang['addon_uninstall'] = 'Désinstaller Addon';
$lang['removed_addon'] = 'Add-on supprimé';
$lang['addon_does_not_support_updates'] = "Ce Add-on ne supporte pas le system mis à jour";
$lang['addon_update_server_not_avaliable'] = "Le Serveur mis à jour pour ce addon n'est pas disponible";
$lang['addon_update_file_not_avaliable'] = "Le mis à jour pour ce addon n'est pas disponible pour le moment";
$lang['addon_already_latest_version'] = 'Vous avez déja la dèrnière version de ';
$lang['addon_update_successful'] = 'Mis à jour réussit';
$lang['addon_update_failed'] = 'Mis à jour echoué';
$lang['addon_manager_ext_help_link'] = 'View External Help Documentation';
$lang['addon_manager_template_tags'] = 'Template Tags';
$lang['addon_manager_action_urls'] = 'Action URLS';
$lang['user_editor_can_manage_addons'] = 'Peut gérer add-ons?';
$lang['user_editor_blog_privileges'] = 'Privilèges Blogging?';
$lang['blog_perm_subscriber'] = 'souscripteur';
$lang['blog_perm_contributor'] = 'Contributeur';
$lang['blog_perm_author'] = 'Auteur';
$lang['blog_perm_editor'] = 'Editeur';
$lang['site_config_heading_signup_settings'] = "Paramètres d'enregistrement";
$lang['signup_image_verification'] = "Utiliser verification avec image sur le page d'inscription:";
$lang['signup_image_verification_desc'] = "Ajoute un code d'image au formulaire d'enregistrement. Utilisateurs seront obligées de entrer le code pour pouvoir envoyer le formulaire. Installation de GD image au serveur est requis.";
$lang['signup_verification_code_not_valid'] = "Vous n'avez pas entré le correct code de verification.";
$lang['site_email'] = 'Adresse Email du site web';
$lang['site_email_desc'] = "Adresse email Optionelle à utiliser comme expéditeur d'emails du site web. Si vierge, l'adresse email admin sera utiliser.";
$lang['admin_send_notices'] = 'Envoyer emails nouvelles annonces';
$lang['send_notices_tool_tip'] = "Selectionné comme "yes" va envoyer cette annonce à tous les membres au lesquelles l'annonce correspond à un de leur recherches sauvegarder.";
$lang['controlpanel_mbstring_enabled'] = 'MBString est-il activé au serveur?';
$lang['mbstring_enabled_desc'] = "MBString (MultiByte) est activé au serveur? <strong>Par default est "No"</strong>. Verifier auprès de votre hébergeur si ca peut être set comme "Yes" - on a que besoin si vous avez besoin de sauvegarder "charactères spéciales" (Multilingual Feature) au base de données (utilisant l'editeur Page/Blog avec ou sans un editeur WYSIWYG).";
$lang['signup_email_verification'] = 'verification Adresse Email obligatoire.';
$lang['signup_email_verification_desc'] = "Selectionné comme yes obligera l'utilisateur de confirmer l'adresse Email par cliquer sur un lien dans l'email de souscription.";
$lang['admin_new_user_email_verification'] = 'Vous avez vous enregistré, mais vous devez verifier votre adresse email avant que votre compte sera active. Un email a été envoyé à votre adresse email. Veuillez verifier votre adresse email en cliquant le lien dans le message email.';
$lang['admin_new_user_email_verification_message'] = 'Vous avez vous enregistré, mais vous devez verifier votre adresse email avant que votre compte sera activé. Merci the cliquez sur le lien en dessous pour verifier votre adresse email et activer votre compte.';
$lang['verify_email_invalid_link'] = 'Vous avez cliquez sur ou entrez un lien non valide.';
$lang['verify_email_thanks'] = 'Merci pour verifier votre adresse email.';
$lang['admin_favorites'] = 'View Favorites';
$lang['admin_saved_search'] = 'Recherches Sauvegarder';
$lang['blog_deleted'] = 'Entrée Blog Supprimé.';
$lang['delete_index_blog_error'] = "Vous pouvez pas supprimer le principal entrée blog, vous ne pouvez pas que l'editer.";
$lang['template_tag_for_blog'] = 'Template tag for blog';
$lang['link_to_blog'] = 'Lien vers le blog';
$lang['blog_post'] = 'Post';
$lang['blog_author'] = 'Auteur';
$lang['blog_keywords'] = 'Mots clefs';
$lang['blog_date'] = 'Date';
$lang['blog_published'] = 'Publié';
$lang['blog_draft'] = 'Brouillon';
$lang['blog_review'] = 'Revue';
$lang['blog_all'] = 'Toutes';
$lang['blog_title'] = 'Titre';
$lang['blog_seo'] = 'Optimalisation Moteurs de recherche';
$lang['blog_publication_status'] = 'Statut Publication';
$lang['listing_editor_permission_denied'] = 'Permission Réfusé';
$lang['not_authorized'] = 'Pas Autorisé';
$lang['blog_comment_delete'] = 'Supprimer';
$lang['blog_comment_approve'] = 'Approuver';
$lang['site_config_blog'] = 'Blog';
$lang['blog_config'] = 'Configuration Blog';
$lang['blog_requires_moderation'] = 'Moderer commentaires';
$lang['blog_requires_moderation_desc'] = "Les commentaires doivent-ils être approuvés avant qu'ils sont montrés sur le site?";
$lang['blog_permission_denied'] = 'Permission Réfusé';
$lang['email_address_already_used'] = "l'Addres Email a déja été pris par un autre utilisateur.";

?>