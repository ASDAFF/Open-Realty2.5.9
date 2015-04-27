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

$lang['admin_blog_manager'] = 'Blog Manager';
$lang['blog_edit_post'] = "Edit Blog Post";
$lang['create_new_blog'] = "New Blog Entry";
$lang['user_manager_limitFeaturedListings'] = "Limit # of Featured Listings";
$lang['user_manager_displayorder'] = "Display order";
$lang['agent_default_num_featuredlistings'] = 'Number of featured listings';
$lang['agent_default_num_featuredlistings_desc'] = 'This is the number of featured listings agents can create by default. (-1 is unlimited).';
$lang['admin_listings_editor_featuredlistingerror'] = 'Featured Listing Limit Hit. This listing was not marked as featured.';
$lang['blog_meta_description'] = 'Meta Description';
$lang['blog_meta_keywords'] = 'Meta Keywords';
$lang['delete_blog'] = 'Delete Blog Entry';
$lang['blog_read_story'] = 'Read the rest of this article';
$lang['blog_comments'] = 'Comments';
$lang['blog_post_by'] = 'Article By';
$lang['blog_comment_by'] = 'Comment By';
$lang['blog_comment_on'] = 'on';
$lang['blog_must_login'] = 'You must login to leave a comment.';
$lang['blog_comment_subject'] = 'Subject';
$lang['blog_comment_submit'] = 'Submit Comment';
$lang['blog_add_comment'] = 'Add Comment';
$lang['header_pclass'] = 'Property Class';
$lang['delete_all_images'] = 'Delete all listing images';
$lang['confirm_delete_all_images'] = 'Are you sure you want to delete all of the listings images?';
$lang['admin_addon_manager'] = 'Addon Manager';
$lang['warning_addon_folder_not_writeable'] = 'Your addon directory is not writeable, the addon manager will not work correctly.';
$lang['addon_name'] = 'Addon Name';
$lang['addon_version'] = 'Addon Version';
$lang['addon_status'] = 'Addon Status';
$lang['addon_actions'] = 'Actions';
$lang['addon_dir_removed'] = 'Addon Directory as been removed, without uninstalling the addon';
$lang['addon_files_removed'] = 'Addon Files have been removed, without uninstalling the addon';
$lang['addon_ok'] = 'Addon Ok';
$lang['addon_check_for_updates'] = 'Check for updates to this addon';
$lang['addon_view_docs'] = 'View Addon Help';
$lang['addon_uninstall'] = 'Uninstall Addon';
$lang['removed_addon'] = 'Removed Add-on';
$lang['addon_does_not_support_updates'] = 'Add-on does not support update system';
$lang['addon_update_server_not_avaliable'] = 'Update server for this addon is not currently avaliable';
$lang['addon_update_file_not_avaliable'] = 'Update file for this addon is not currently avaliable';
$lang['addon_already_latest_version'] = 'You already have the latest version of ';
$lang['addon_update_successful'] = 'Update successful';
$lang['addon_update_failed'] = 'Update failed';
$lang['addon_manager_ext_help_link'] = 'View External Help Documentation';
$lang['addon_manager_template_tags'] = 'Template Tags';
$lang['addon_manager_action_urls'] = 'Action URLS';
$lang['user_editor_can_manage_addons'] = 'Can manage add-ons?';
$lang['user_editor_blog_privileges'] = 'Blogging Privileges?';
$lang['blog_perm_subscriber'] = 'Subscriber';
$lang['blog_perm_contributor'] = 'Contributor';
$lang['blog_perm_author'] = 'Author';
$lang['blog_perm_editor'] = 'Editor';
$lang['site_config_heading_signup_settings'] = 'Signup Settings';
$lang['signup_image_verification'] = 'Use image verification on signup page:';
$lang['signup_image_verification_desc'] = 'Adds an image code to the signup forms. Users are required to enter the code in order to submit the form. Requires server to have the GD image libraries installed.';
$lang['signup_verification_code_not_valid'] = 'You did not enter the correct verification code.';
$lang['site_email'] = 'Site Email Address';
$lang['site_email_desc'] = 'Optional email address to use as the sender for site emails. If left blank, the admin email address will be used.';
$lang['admin_send_notices'] = 'Send New Listing Emails';
$lang['send_notices_tool_tip'] = 'Selecting yes will send this listing to all users where the listing matches one of their saved searches.';
$lang['controlpanel_mbstring_enabled'] = 'MBString is enabled at the server?';
$lang['mbstring_enabled_desc'] = 'MBString (MultiByte) is enabled at the server? <strong>By default it is set to "No"</strong>. Check with your hosting support if it can be set to "Yes" - it is only needed if you are going to store "special characters" (Multilingual Feature) at the Database (using the Page/Blog Editor with or without a WYSIWYG Editor).';
$lang['signup_email_verification'] = 'Require email address confirmation.';
$lang['signup_email_verification_desc'] = 'Setting this to yes will require that the user confirm their email address by clicking a link in the signup email.';
$lang['admin_new_user_email_verification'] = 'You are registered now, but you must verify your email address before your account is active. An email has been sent to your email address. Please verify your email address by clicking the link in the email.';
$lang['admin_new_user_email_verification_message'] = 'You are registered now, but you must verify your email address before your account is active. Please click the below link to verify your email address and activate your account.';
$lang['verify_email_invalid_link'] = 'You have clicked on or entered and invalid link.';
$lang['verify_email_thanks'] = 'Thank you for verifying your email address.';
$lang['admin_favorites'] = 'View Favorites';
$lang['admin_saved_search'] = 'Saved Searches';
$lang['blog_deleted'] = 'Blog Entry Deleted.';
$lang['delete_index_blog_error'] = 'You can\'t delete the main blog entry, you can only edit it.';
$lang['template_tag_for_blog'] = 'Template tag for blog';
$lang['link_to_blog'] = 'Link to this blog';
$lang['blog_post'] = 'Post';
$lang['blog_author'] = 'Author';
$lang['blog_keywords'] = 'Keywords';
$lang['blog_date'] = 'Date';
$lang['blog_published'] = 'Published';
$lang['blog_draft'] = 'Draft';
$lang['blog_review'] = 'Review';
$lang['blog_all'] = 'All';
$lang['blog_title'] = 'Title';
$lang['blog_seo'] = 'Search Engine Optimization';
$lang['blog_publication_status'] = 'Publication Status';
$lang['listing_editor_permission_denied'] = 'Permission Denied';
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