<?php
/**
 * Open-Realty
 *
 * Open-Realty is free software; you can redistribute it and/or modify
 * it under the terms of the Open-Realty License as published by
 * Transparent Technologies; either version 1 of the License, or
 * (at your option) any later version.
 *
 * Open-Realty is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * Open-Realty License for more details.
 * http://www.open-realty.org/license_info.html
 *
 * You should have received a copy of the Open-Realty License
 * along with Open-Realty; if not, write to Transparent Technologies
 * RR1 Box 162C, Kingsley, PA  18826  USA
 *
 * @author Ryan C. Bonham <ryan@transparent-tech.com>
 * @copyright Transparent Technologies 2004
 * @link http://www.open-realty.org Open-Realty Project
 * @link http://www.transparent-tech.com Transparent Technologies
 * @link http://www.open-realty.org/license_info.html Open-Realty License
 */

class page {
	var $page;
	function load_js()
	{
		global $lang, $jscript, $config;
		$display = '';
		$display .= '<script type="text/javascript">' . "\r\n";
		$display .= '<!--' . "\r\n";
		$display .= 'function confirmDelete()' . "\r\n";
		$display .= '{' . "\r\n";
		$display .= 'var agree=confirm("' . $lang['are_you_sure_you_want_to_delete'] . '");' . "\r\n";
		$display .= 'if (agree)' . "\r\n";
		$display .= 'return true ;' . "\r\n";
		$display .= 'else' . "\r\n";
		$display .= 'return false ;' . "\r\n";
		$display .= '}' . "\r\n";
		$display .= '//-->' . "\r\n";
		$display .= '</script>' . "\r\n";
		$display .= '<script type="text/javascript">' . "\r\n";
		$display .= '<!--' . "\r\n";
		$display .= 'function open_window(url)' . "\r\n";
		$display .= '{' . "\r\n";
		$display .= 'cwin = window.open(url,"attach","width=350,height=400,toolbar=no,resizable=yes");' . "\r\n";
		$display .= '}' . "\r\n";
		$display .= 'function ptoutput(theText)' . "\r\n";
		$display .= '{' . "\r\n";
		$display .= 'document.write(theText);' . "\r\n";
		$display .= '}' . "\r\n";
		$display .= '//-->' . "\r\n";
		$display .= '</script>' . "\r\n";
		$display .= $jscript;
		return $display;
	}
	function auto_replace_tags($section = '', $admin = false)
	{
		if ($section == '') {
			$section = $this->page;
		}
		if ($admin == true) {
			//echo "Start Section 1:\r\n".print_r($section,TRUE)."\r\n--- End Section 1\r\n\r\n";
			$section = preg_replace('/<form.*?form>/si', '', $section);
			//echo "Start Section 2:\r\n".print_r($section,TRUE)."\r\n--- End Section 2\r\n\r\n";
			preg_match_all("/{(?!lang)(.\S*?)}/i", $section, $tags_found);
			$tags_found = $tags_found[1];
			$tags_special = array('content', 'site_title');
			$tags_found = array_diff($tags_found, $tags_special);
			foreach ($tags_found as $x => $y) {
				if (strpos($y, 'load_') === 0 || strpos($y, 'check_') === 0 || strpos($y, '/check_') === 0 || strpos($y, '!check_') === 0 || strpos($y, '/!check_') === 0) {
					unset($tags_found[$x]);
				}
			}

		} else {
			preg_match_all("/{(?!lang)(.\S*?)}/i", $section, $tags_found);
			$tags_found = $tags_found[1];
			$tags_special = array('content', 'site_title');
			$tags_found = array_diff($tags_found, $tags_special);
			foreach ($tags_found as $x => $y) {
				if (strpos($y, 'load_') === 0) {
					unset($tags_found[$x]);
				}
			}
		}
		//echo "Start Tags Found :\r\n".print_r($tags_found,TRUE)."\r\n--- End Tags Found\r\n\r\n";
		$this->replace_tags($tags_found);
		//echo "Start Page :\r\n".print_r($this->page,TRUE)."\r\n--- End Page\r\n\r\n";
	}
	function get_addon_template_field_list($addons)
	{
		global $config;
		$template_list = array();
		foreach ($addons as $addon) {
			$addon_file = $config['basepath'] . '/addons/' . $addon . '/addon.inc.php';
			if (file_exists($addon_file)) {
				include_once ($addon_file);
				$function_name = $addon . '_load_template';
				$addon_fields = $function_name();
				if (is_array($addon_fields)) {
					$template_list = array_merge($template_list, $addon_fields);
				}
			}
		}
		return $template_list;
	}
	function load_addons()
	{
		global $lang, $config;
		// Get Addon List
		$dir = 0;
		$options = array();
		if ($handle = opendir($config['basepath'] . '/addons')) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
					if (is_dir($config['basepath'] . '/addons/' . $file)) {
						$options[$file] = $file;
						$dir++;
					}
				}
			}
			closedir($handle);
		}
		return $options;
	}
	// This function should be called first, it checks that the page exsists and sets up the page variable for the other functions
	function load_page($template = '', $parse = true)
	{
		if (file_exists($template)) {
			if ($parse == false) {
				$this->page = file_get_contents($template);
			} else {
				$this->page = $this->parse($template);
			}
		} else {
			die('Template file ' . $template . ' not found.');
		}
	}
	// This function allows us to parse the file allowing us to have php directives
	function parse($file)
	{
		ob_start();
		include($file);
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	function get_template_section_row($section_name)
	{
		if (!empty($section_name)) {
			$section = '/{' . $section_name . ' repeat="([0-9]{1,3})"}(.*?){\/' . $section_name . '}/is';
			$section_results = array();
			preg_match($section, $this->page, $section_results);
			if (isset($section_results[1])) {
				return array($section_results[2], $section_results[1]);
			}
		}
	}
	function get_template_section($section_name)
	{
		if (!empty($section_name)) {
			$section = '/{' . $section_name . '}(.*?){\/' . $section_name . '}/is';
			$section_results = array();
			preg_match($section, $this->page, $section_results);
			if (isset($section_results[1])) {
				return $section_results[1];
			}
			return false;
		}
	}
	function cleanup_template_block($block, $section)
	{
		$section = str_replace('{' . $block . '_block}', '', $section);
		$section = str_replace('{/' . $block . '_block}', '', $section);
		return $section;
	}
	function remove_template_block($block, $section)
	{
		$find_block = '/{' . $block . '_block}(.*?){\/' . $block . '_block}/is';
		$section = preg_replace($find_block, '', $section);
		return $section;
	}
	function replace_tag($tag, $replacement)
	{
		$this->page = str_replace('{' . $tag . '}', $replacement, $this->page);
	}
	function parse_template_section($section_as_variable, $field, $value)
	{
		$section_as_variable = str_replace('{' . $field . '}', $value, $section_as_variable);
		$section_as_variable = $this->cleanup_template_block($field, $section_as_variable);
		return $section_as_variable;
	}
	function replace_template_section($section_name, $replacement, $page = '')
	{
		$section = '/{' . $section_name . '}(.*?){\/' . $section_name . '}/is';
		$replacement = str_replace('$', '\$', $replacement);
		if ($page == '') {
			$this->page = preg_replace("$section", "$replacement", $this->page);
		} else {
			$page = preg_replace("$section", "$replacement", $page);
			return $page;
		}
	}
	function replace_template_section_row($section_name, $replacement)
	{
		$section = '/{' . $section_name . ' (.*?)}(.*?){\/' . $section_name . '}/is';
		$replacement = str_replace('$', '\$', $replacement);
		$this->page = preg_replace("$section", "$replacement", $this->page);
	}

	 // This function is used to cleanup any {field_#} tags on the search result page that were not filled with data. It should be run after every data row.
	function cleanup_fields($section)
	{
		$section = preg_replace('/{field_(.*?)}/', '', $section);
		return $section;
	}
	 // This function is used to cleanup any indivdual image or thumbnail tags on the search result page that were not filled with data. It should be run after every data row.
	function cleanup_images($section)
	{
		$section = preg_replace('/{(.*?)image_(.*?)}/', '', $section);
		$section = preg_replace('/{listing_agent_thumbnail_(.*?)}/', '', $section);
		return $section;
	}
	function output_page()
	{
		print $this->page;
	}
	// This function returns the results for sub templates
	function return_page()
	{
		return $this->page;
	}
	function replace_permission_tags()
	{
		global $config;
		require_once($config['basepath'] . '/include/login.inc.php');
		$login = new login();
		// Check for tags: Admin, Agent, canEditForms, canViewLogs, editpages, havevtours
		$login_status = $login->verify_priv('Agent');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_agent}(.*?){\/check_agent}/is', '', $this->page);
			$this->page = str_replace('{!check_agent}', '', $this->page);
			$this->page = str_replace('{/!check_agent}', '', $this->page);
		} else {
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = preg_replace('/{!check_agent}(.*?){\/!check_agent}/is', '', $this->page);
			$this->page = str_replace('{check_agent}', '', $this->page);
			$this->page = str_replace('{/check_agent}', '', $this->page);
		}
		$login_status = $login->verify_priv('Member');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_member}(.*?){\/check_member}/is', '', $this->page);
			$this->page = str_replace('{!check_member}', '', $this->page);
			$this->page = str_replace('{/!check_member}', '', $this->page);
			$this->page = str_replace('{check_guest}', '', $this->page);
			$this->page = str_replace('{/check_guest}', '', $this->page);
		} else {
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = preg_replace('/{!check_member}(.*?){\/!check_member}/is', '', $this->page);
			$this->page = str_replace('{check_member}', '', $this->page);
			$this->page = str_replace('{/check_member}', '', $this->page);
			$this->page = preg_replace('/{check_guest}(.*?){\/check_guest}/is', '', $this->page);
		}
		$login_status = $login->verify_priv('Admin');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_moderate_listings}(.*?){\/check_moderate_listings}/is', '', $this->page);
			$this->page = str_replace('{!check_moderate_listings}', '', $this->page);
			$this->page = str_replace('{/!check_moderate_listings}', '', $this->page);
			$this->page = str_replace('{!check_admin}', '', $this->page);
			$this->page = str_replace('{/!check_admin}', '', $this->page);
			$this->page = preg_replace('/{check_admin}(.*?){\/check_admin}/is', '', $this->page);
		} else {
			if ($config['moderate_listings'] === "1") {
				$this->page = str_replace('{check_moderate_listings}', '', $this->page);
				$this->page = str_replace('{/check_moderate_listings}', '', $this->page);
				$this->page = preg_replace('/{!check_moderate_listings}(.*?){\/!check_moderate_listings}/is', '', $this->page);
			} else {
				$this->page = str_replace('{!check_moderate_listings}', '', $this->page);
				$this->page = str_replace('{/!check_moderate_listings}', '', $this->page);
				$this->page = preg_replace('/{check_moderate_listings}(.*?){\/check_moderate_listings}/is', '', $this->page);
			}
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = str_replace('{check_admin}', '', $this->page);
			$this->page = str_replace('{/check_admin}', '', $this->page);
			$this->page = preg_replace('/{!check_admin}(.*?){\/!check_admin}/is', '', $this->page);
		}
		$login_status = $login->verify_priv('edit_site_config');
		if ($login_status !== true) {
			$this->page = preg_replace('/{check_edit_site_config}(.*?){\/check_edit_site_config}/is', '', $this->page);
			$this->page = str_replace('{!check_edit_site_config}', '', $this->page);
			$this->page = str_replace('{/!check_edit_site_config}', '', $this->page);
		} else {
			$this->page = str_replace('{check_edit_site_config}', '', $this->page);
			$this->page = str_replace('{/check_edit_site_config}', '', $this->page);
			$this->page = preg_replace('/{!check_edit_site_config}(.*?){\/!check_edit_site_config}/is', '', $this->page);
		}
		$login_status = $login->verify_priv('edit_member_template');
		if ($login_status !== true) {
			$this->page = preg_replace('/{check_edit_member_template}(.*?){\/check_edit_member_template}/is', '', $this->page);
			$this->page = str_replace('{!check_edit_member_template}', '', $this->page);
			$this->page = str_replace('{/!check_edit_member_template}', '', $this->page);
		} else {
			$this->page = str_replace('{check_edit_member_template}', '', $this->page);
			$this->page = str_replace('{/check_edit_member_template}', '', $this->page);
			$this->page = preg_replace('/{!check_edit_member_template}(.*?){\/!check_edit_member_template}/is', '', $this->page);
		}
		$login_status = $login->verify_priv('edit_agent_template');
		if ($login_status !== true) {
			$this->page = preg_replace('/{check_edit_agent_template}(.*?){\/check_edit_agent_template}/is', '', $this->page);
			$this->page = str_replace('{!check_edit_agent_template}', '', $this->page);
			$this->page = str_replace('{/!check_edit_agent_template}', '', $this->page);
		} else {
			$this->page = str_replace('{check_edit_agent_template}', '', $this->page);
			$this->page = str_replace('{/check_edit_agent_template}', '', $this->page);
			$this->page = preg_replace('/{!check_edit_agent_template}(.*?){\/!check_edit_agent_template}/is', '', $this->page);
		}
		$login_status = $login->verify_priv('edit_listing_template');
		if ($login_status !== true) {
			$this->page = preg_replace('/{check_edit_listing_template}(.*?){\/check_edit_listing_template}/is', '', $this->page);
			$this->page = str_replace('{!check_edit_listing_template}', '', $this->page);
			$this->page = str_replace('{/!check_edit_listing_template}', '', $this->page);
		} else {
			$this->page = str_replace('{check_edit_listing_template}', '', $this->page);
			$this->page = str_replace('{/check_edit_listing_template}', '', $this->page);
			$this->page = preg_replace('/{!check_edit_listing_template}(.*?){\/!check_edit_listing_template}/is', '', $this->page);
		}
		$login_status = $login->verify_priv('canViewLogs');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_view_logs}(.*?){\/check_view_logs}/is', '', $this->page);
			$this->page = str_replace('{!check_view_logs}', '', $this->page);
			$this->page = str_replace('{/!check_view_logs}', '', $this->page);
		} else {
			$this->page = preg_replace('/{!check_view_logs}(.*?){\/!check_view_logs}/is', '', $this->page);
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = str_replace('{check_view_logs}', '', $this->page);
			$this->page = str_replace('{/check_view_logs}', '', $this->page);
		}
		$login_status = $login->verify_priv('editpages');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_edit_pages}(.*?){\/check_edit_pages}/is', '', $this->page);
			$this->page = str_replace('{!check_edit_pages}', '', $this->page);
			$this->page = str_replace('{/!check_edit_pages}', '', $this->page);
		} else {
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = str_replace('{check_edit_pages}', '', $this->page);
			$this->page = str_replace('{/check_edit_pages}', '', $this->page);
			$this->page = preg_replace('/{!check_edit_pages}(.*?){\/!check_edit_pages}/is', '', $this->page);
		}
		$login_status = $login->verify_priv('edit_all_listings');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_edit_all_listings}(.*?){\/check_edit_all_listings}/is', '', $this->page);
			$this->page = str_replace('{!check_edit_all_listings}', '', $this->page);
			$this->page = str_replace('{/!check_edit_all_listings}', '', $this->page);
		} else {
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = str_replace('{check_edit_all_listings}', '', $this->page);
			$this->page = str_replace('{/check_edit_all_listings}', '', $this->page);
			$this->page = preg_replace('/{!check_edit_all_listings}(.*?){\/!check_edit_all_listings}/is', '', $this->page);
		}
		$login_status = $login->verify_priv('edit_all_users');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_edit_all_users}(.*?){\/check_edit_all_users}/is', '', $this->page);
			$this->page = str_replace('{!check_edit_all_users}', '', $this->page);
			$this->page = str_replace('{/!check_edit_all_users}', '', $this->page);
		} else {
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = str_replace('{check_edit_all_users}', '', $this->page);
			$this->page = str_replace('{/check_edit_all_users}', '', $this->page);
			$this->page = preg_replace('/{!check_edit_all_users}(.*?){\/!check_edit_all_users}/is', '', $this->page);
		}
		$login_status = $login->verify_priv('edit_property_classes');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_edit_listing_classes}(.*?){\/check_edit_listing_classes}/is', '', $this->page);
			$this->page = str_replace('{!check_edit_listing_classes}', '', $this->page);
			$this->page = str_replace('{/!check_edit_listing_classes}', '', $this->page);
		} else {
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = str_replace('{check_edit_listing_classes}', '', $this->page);
			$this->page = str_replace('{/check_edit_listing_classes}', '', $this->page);
			$this->page = preg_replace('/{!check_edit_listing_classes}(.*?){\/!check_edit_listing_classes}/is', '', $this->page);
		}
		$login_status = $login->verify_priv('havevtours');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_have_vtours}(.*?){\/check_have_vtours}/is', '', $this->page);
			$this->page = str_replace('{!check_have_vtours}', '', $this->page);
			$this->page = str_replace('{/!check_have_vtours}', '', $this->page);
		} else {
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = preg_replace('/{!check_have_vtours}(.*?){\/!check_have_vtours}/is', '', $this->page);
			$this->page = str_replace('{check_have_vtours}', '', $this->page);
			$this->page = str_replace('{/check_have_vtours}', '', $this->page);
		}
		$login_status = $login->verify_priv('havefiles');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_have_files}(.*?){\/check_have_files}/is', '', $this->page);
			$this->page = str_replace('{!check_have_files}', '', $this->page);
			$this->page = str_replace('{/!check_have_files}', '', $this->page);
		} else {
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = preg_replace('/{!check_have_files}(.*?){\/!check_have_files}/is', '', $this->page);
			$this->page = str_replace('{check_have_files}', '', $this->page);
			$this->page = str_replace('{/check_have_files}', '', $this->page);
		}
		if (isset($_GET['printer_friendly']) && $_GET['printer_friendly'] == 'yes') {
			$this->page = preg_replace('/{hide_printer_friendly}(.*?){\/hide_printer_friendly}/is', '', $this->page);
			$this->page = str_replace('{show_printer_friendly}', '', $this->page);
			$this->page = str_replace('{/show_printer_friendly}', '', $this->page);
		} else {
			$this->page = preg_replace('/{show_printer_friendly}(.*?){\/show_printer_friendly}/is', '', $this->page);
			$this->page = str_replace('{hide_printer_friendly}', '', $this->page);
			$this->page = str_replace('{/hide_printer_friendly}', '', $this->page);
		}
		$login_status = $login->verify_priv('can_manage_addons');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_addon_manager}(.*?){\/check_addon_manager}/is', '', $this->page);
			$this->page = str_replace('{!check_addon_manager}', '', $this->page);
			$this->page = str_replace('{/!check_addon_manager}', '', $this->page);
		} else {
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = preg_replace('/{!check_addon_manager}(.*?){\/!check_addon_manager}/is', '', $this->page);
			$this->page = str_replace('{check_addon_manager}', '', $this->page);
			$this->page = str_replace('{/check_addon_manager}', '', $this->page);
		}
		//can_access_blog_manager
		$login_status = $login->verify_priv('can_access_blog_manager');
		if ($login_status !== true) {
			// Use pregreplace to removed {check_agent} tags and content between them
			$this->page = preg_replace('/{check_access_blog_manager}(.*?){\/check_access_blog_manager}/is', '', $this->page);
			$this->page = str_replace('{!check_access_blog_manager}', '', $this->page);
			$this->page = str_replace('{/!check_access_blog_manager}', '', $this->page);
		} else {
			// Use strreplace to remove {check_agent} tags and leave the content.
			$this->page = preg_replace('/{!check_access_blog_manager}(.*?){\/!check_access_blog_manager}/is', '', $this->page);
			$this->page = str_replace('{check_access_blog_manager}', '', $this->page);
			$this->page = str_replace('{/check_access_blog_manager}', '', $this->page);
		}
	}
	function replace_urls()
	{
		global $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		if ($config['url_style'] == '1') {
			$this->page = str_replace('{url_index}', $config['baseurl'] . '/index.php', $this->page);
			$this->page = str_replace('{url_search}', $config['baseurl'] . '/index.php?action=searchpage', $this->page);
			$this->page = str_replace('{url_search_rental}', $config['baseurl'] . '/index.php?action=search_step_2&amp;pclass[]=5', $this->page);
			$this->page = preg_replace('/{url_search_class_(.*?)}/is', $config['baseurl'] . '/index.php?action=search_step_2&amp;pclass[]=$1', $this->page);
			$this->page = preg_replace('/{url_searchresults_class_(.*?)}/is', $config['baseurl'] . '/index.php?action=searchresults&amp;pclass[]=$1', $this->page);
			$this->page = str_replace('{url_search_results}', $config['baseurl'] . '/index.php?action=searchresults', $this->page);
			$this->page = str_replace('{url_view_agents}', $config['baseurl'] . '/index.php?action=view_users', $this->page);
			$this->page = str_replace('{url_view_favorites}', $config['baseurl'] . '/index.php?action=view_favorites', $this->page);
			$this->page = str_replace('{url_view_calculator}', $config['baseurl'] . '/index.php?action=calculator&amp;popup=yes', $this->page);
			$this->page = str_replace('{url_view_saved_searches}', $config['baseurl'] . '/index.php?action=view_saved_searches', $this->page);
			$this->page = preg_replace('/{page_link_(.*?)}/is', $config['baseurl'] . '/index.php?action=page_display&amp;PageID=$1', $this->page);
			$this->page = preg_replace('/{blog_link_(.*?)}/is', $config['baseurl'] . '/index.php?action=blog_view_article&amp;ArticleID=$1', $this->page);
			$this->page = str_replace('{url_logout}', $config['baseurl'] . '/index.php?action=logout', $this->page);
			$this->page = str_replace('{url_member_signup}', $config['baseurl'] . '/index.php?action=signup&amp;type=member', $this->page);
			$this->page = str_replace('{url_agent_signup}', $config['baseurl'] . '/index.php?action=signup&amp;type=agent', $this->page);
			$this->page = str_replace('{url_member_login}', $config['baseurl'] . '/index.php?action=member_login', $this->page);
			$this->page = str_replace('{url_agent_login}', $config['baseurl'] . '/admin/index.php', $this->page);
			$this->page = str_replace('{url_blog}', $config['baseurl'] . '/index.php?action=blog_index', $this->page);
			$this->page = str_replace('{curley_open}', '{', $this->page);
			$this->page = str_replace('{curley_close}', '}', $this->page);
			if (isset($_SESSION['userID'])) {
				$this->page = str_replace('{url_edit_profile}', $config['baseurl'] . '/index.php?action=edit_profile&amp;user_id=' . $_SESSION['userID'], $this->page);
			}
		} else {
			$this->page = str_replace('{url_index}', $config['baseurl'] . '/index.html', $this->page);
			$this->page = str_replace('{url_search}', $config['baseurl'] . '/search.html', $this->page);
			$this->page = str_replace('{url_search_rental}', $config['baseurl'] . '/rental_search.html', $this->page);
			$this->page = str_replace('{url_search_results}', $config['baseurl'] . '/searchresults.html', $this->page);
			$this->page = str_replace('{url_view_agents}', $config['baseurl'] . '/agents.html', $this->page);
			$this->page = str_replace('{url_view_favorites}', $config['baseurl'] . '/view_favorites.html', $this->page);
			$this->page = str_replace('{url_view_calculator}', $config['baseurl'] . '/calculator.html', $this->page);
			$this->page = str_replace('{url_view_saved_searches}', $config['baseurl'] . '/saved_searches.html', $this->page);
			$this->page = preg_replace_callback('/{page_link_(.*?)}/is', create_function('$matches', 'global $config; require_once($config[\'basepath\'].\'/include/page_display.inc.php\'); $title = page_display::get_page_title($matches[1]); $title = strtolower(str_replace(" ", $config[\'seo_url_seperator\'], $title)); return $config[\'baseurl\'].\'/page-\'.urlencode($title).\'-\'.$matches[1].\'.html\';'), $this->page);
			$this->page = preg_replace_callback('/{blog_link_(.*?)}/is', create_function('$matches', 'global $config; require_once($config[\'basepath\'].\'/include/blog_display.inc.php\'); $title = blog_display::get_blog_title($matches[1]); $title = strtolower(str_replace(" ", $config[\'seo_url_seperator\'], $title)); return $config[\'baseurl\'].\'/article-\'.urlencode($title).\'-\'.$matches[1].\'.html\';'), $this->page);
			$this->page = preg_replace_callback('/{url_search_class_(.*?)}/is', create_function('$matches', 'global $config,$conn; $classid = $matches[1];
				$sql = "SELECT class_name FROM " . $config[\'table_prefix\'] . "class WHERE class_id = $classid";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$class_name = $recordSet->fields[\'class_name\'];
				return $config[\'baseurl\'].\'/\'.urlencode($class_name).\'-search-\'.$matches[1].\'.html\';'), $this->page);
			$this->page = preg_replace_callback('/{url_searchresults_class_(.*?)}/is', create_function('$matches', 'global $config,$conn; $classid = $matches[1];
				$sql = "SELECT class_name FROM " . $config[\'table_prefix\'] . "class WHERE class_id = $classid";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$class_name = $recordSet->fields[\'class_name\'];
				return $config[\'baseurl\'].\'/\'.urlencode($class_name).\'-searchresults-\'.$matches[1].\'.html\';'), $this->page);
			$this->page = str_replace('{url_logout}', $config['baseurl'] . '/logout.html', $this->page);
			$this->page = str_replace('{url_member_signup}', $config['baseurl'] . '/member_signup.html', $this->page);
			$this->page = str_replace('{url_agent_signup}', $config['baseurl'] . '/agent_signup.html', $this->page);
			$this->page = str_replace('{url_member_login}', $config['baseurl'] . '/member_login.html', $this->page);
			$this->page = str_replace('{url_agent_login}', $config['baseurl'] . '/admin/index.php', $this->page);
			$this->page = str_replace('{url_blog}', $config['baseurl'] . '/blog.html', $this->page);
			$this->page = str_replace('{curley_open}', '{', $this->page);
			$this->page = str_replace('{curley_close}', '}', $this->page);
			if (isset($_SESSION['userID'])) {
				$this->page = str_replace('{url_edit_profile}', $config['baseurl'] . '/edit_profile_' . $_SESSION['userID'] . '.html', $this->page);
			}
		}
	}
	function replace_meta_template_tags()
	{
		global $config, $lang;
		if ((isset($_GET['listingID'])) && ($_GET['action']!= 'searchresults')) {
			$listing_keywords = $this->replace_listing_field_tags($_GET['listingID'], $config['seo_listing_keywords']);
			$listing_keywords = strip_tags(str_replace(array("\r\n", "\r", "\n", '||'), array('', '', '', ','), $listing_keywords));
			$this->page = str_replace('{load_meta_keywords}', '<meta name="keywords" content="' . $listing_keywords . '" />', $this->page);
			$this->page = str_replace('{load_meta_keywords_raw}', $listing_keywords, $this->page);
			$listing_description = $this->replace_listing_field_tags($_GET['listingID'], $config['seo_listing_description']);
			$listing_description = strip_tags(str_replace(array("\r\n", "\r", "\n", '||'), array('', '', '', ','), $listing_description));
			$this->page = str_replace('{load_meta_description}', '<meta name="description" content="' . $listing_description . '" />', $this->page);
			$this->page = str_replace('{load_meta_description_raw}', $listing_description, $this->page);
			// Handle Site Title
			$listing_title = $this->replace_listing_field_tags($_GET['listingID'], $config['seo_listing_title']);
			$listing_title = strip_tags(str_replace(array("\r\n", "\r", "\n", '||'), array('', '', '', ','), $listing_title));
			$this->page = str_replace('{site_title}', $listing_title, $this->page);
		} else if ($_GET['action'] == 'view_users') {
			$this->page = str_replace('{load_meta_keywords}', '<meta name="keywords" content="' . $config['seo_default_keywords'] . '" />', $this->page);
			$this->page = str_replace('{load_meta_description}', '<meta name="description" content="' . $config['seo_default_description'] . '" />', $this->page);
			$this->page = str_replace('{load_meta_keywords_raw}', $config['seo_default_keywords'], $this->page);
			$this->page = str_replace('{load_meta_description_raw}', $config['seo_default_description'], $this->page);
			$this->page = str_replace('{site_title}', $config['seo_default_title'] . ' - ' . $lang['menu_view_agents'], $this->page);
		} else if ($_GET['action'] == 'view_listing_image') {
			if (isset($_GET['image_id'])) {
				require_once($config['basepath'] . '/include/images.inc.php');
				$title = image_handler::get_image_caption();
				$this->page = str_replace('{load_meta_keywords}', '<meta name="keywords" content="' . $config['seo_default_keywords'] . '" />', $this->page);
				$this->page = str_replace('{load_meta_description}', '<meta name="description" content="' . $config['seo_default_description'] . '" />', $this->page);
				$this->page = str_replace('{load_meta_keywords_raw}', $config['seo_default_keywords'], $this->page);
				$this->page = str_replace('{load_meta_description_raw}', $config['seo_default_description'], $this->page);
				$this->page = str_replace('{site_title}', $config['seo_default_title'] . ' - ' . $title, $this->page);
			} else {
				$this->page = str_replace('{load_meta_keywords}', '<meta name="keywords" content="' . $config['seo_default_keywords'] . '" />', $this->page);
				$this->page = str_replace('{load_meta_description}', '<meta name="description" content="' . $config['seo_default_description'] . '" />', $this->page);
				$this->page = str_replace('{load_meta_keywords_raw}', $config['seo_default_keywords'], $this->page);
				$this->page = str_replace('{load_meta_description_raw}', $config['seo_default_description'], $this->page);
				$this->page = str_replace('{site_title}', $config['seo_default_title'], $this->page);
			}
		} else if (isset($_GET['PageID'])) {
			require_once($config['basepath'] . '/include/page_display.inc.php');
			$title = page_display::get_page_title($_GET['PageID']);
			$description = page_display::get_page_description($_GET['PageID']);
			$keywords = page_display::get_page_keywords($_GET['PageID']);
			if ($title == '') {
				$title = $config['seo_default_title'];
			}
			if ($description == '') {
				$description = $config['seo_default_description'];
			}
			if ($keywords == '') {
				$keywords = $config['seo_default_keywords'];
			}
			$this->page = str_replace('{load_meta_keywords}', '<meta name="keywords" content="' . $keywords . '" />', $this->page);
			$this->page = str_replace('{load_meta_description}', '<meta name="description" content="' . $description . '" />', $this->page);
			$this->page = str_replace('{load_meta_keywords_raw}', $keywords, $this->page);
			$this->page = str_replace('{load_meta_description_raw}', $description, $this->page);
			$this->page = str_replace('{site_title}', $title, $this->page);
		} else {
			$this->page = str_replace('{load_meta_keywords}', '<meta name="keywords" content="' . $config['seo_default_keywords'] . '" />', $this->page);
			$this->page = str_replace('{load_meta_description}', '<meta name="description" content="' . $config['seo_default_description'] . '" />', $this->page);
			$this->page = str_replace('{load_meta_keywords_raw}', $config['seo_default_keywords'], $this->page);
			$this->page = str_replace('{load_meta_description_raw}', $config['seo_default_description'], $this->page);
			$this->page = str_replace('{site_title}', $config['seo_default_title'], $this->page);
		}
	}
	function replace_css_template_tags($admin = false)
	{
		if ($admin == true) {
			$this->page = preg_replace_callback('/{load_css_(.*?)}/', create_function('$matches', 'global $config,$listing_id,$lang; $css = new page_admin;$css->load_page($config["admin_template_path"]."/$matches[1].css"); $css->replace_tags(array("company_logo","baseurl","template_url")); $css_text = "<style type=\"text/css\"><!-- ".$css->return_page()." --></style>"; return $css_text;'), $this->page);
		} else {
			$this->page = preg_replace_callback('/{load_css_(.*?)}/', create_function('$matches', 'global $config,$listing_id,$lang; $css = new page_user;$css->load_page($config["template_path"]."/$matches[1].css"); $css->replace_tags(array("company_logo","baseurl","template_url")); $css_text = "<style type=\"text/css\"><!-- ".$css->return_page()." --></style>"; return $css_text;'), $this->page);
		}
	}
	function replace_lang_template_tags($admin = false)
	{
		$this->page = preg_replace_callback('/{lang_(.*?)}/is', create_function('$matches','global $lang; return $lang[$matches[1]];'), $this->page);
	}
	function parse_addon_tags($section_as_variable, $fields)
	{
		if ($section_as_variable == '') {
			$section_as_variable = $this->page;
		}
		//print_r($fields);
		//echo $section_as_variable;
		foreach($fields as $field) {
			global $config;
			if ($field == '') {
				continue;
			}
			// Make sure that the tag is in the section
			if (strpos($section_as_variable, '{'.$field.'}') !== false) {
				//echo 'Field Found: '.$field;
				$addon_name = array();
				if (preg_match("/^addon_(.\S*?)_.*/", $field, $addon_name)) {
					include_once($config['basepath'] . '/addons/' . $addon_name[1] . '/addon.inc.php');
					$function_name = $addon_name[1] . '_run_template_user_fields';
					$value = $function_name($field);
					$section_as_variable = str_replace('{' . $field . '}', $value, $section_as_variable);
				}
			}
		}
		//echo $section_as_variable;die;
		return $section_as_variable;
	}
	function cleanup_template_sections($next_prev = '', $next_prev_bottom='')
	{
		// Insert Next Prev where needed
		$section = '{next_prev}';
		$this->page = str_replace($section, $next_prev, $this->page);
		$section = '{next_prev_bottom}';
		$this->page = str_replace($section, $next_prev_bottom, $this->page);
		// Renmove any unused blocks
		$section = '/{(.*?)_block}.*?{\/\1_block}/is';
		$this->page = preg_replace($section, '', $this->page);
	}

	function replace_listing_field_tags($listing_id, $tempate_section = '', $utf8HTML = false)
	{
		global $lang;
		if (is_numeric($listing_id)) {
			global $config, $conn, $or_replace_listing_id, $or_replace_listing_owner;
			$or_replace_listing_id = $listing_id;
			require_once($config['basepath'] . '/include/listing.inc.php');
			require_once($config['basepath'] . '/include/vtour.inc.php');
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			if ($tempate_section != '') {
				$tsection = true;
			} else {
				$tempate_section = $this->page;
				$tsection = false;
			}
			if ($utf8HTML) {
			//Deal with listing field blocks
				$lf_blocks=array();
				preg_match_all('/{listing_field_([^{}]*?)_block}/',$tempate_section,$lf_blocks);
				require_once($config['basepath'].'/include/user.inc.php');
				global $or_replace_listing_owner;
				if(count($lf_blocks) > 1){
					foreach($lf_blocks[1] as $block){
						require_once($config['basepath'].'/include/listing.inc.php');
						$value =  listing_pages::renderSingleListingItem($or_replace_listing_id, $block,'rawvalue');
						if($value == ''){
							$tempate_section = preg_replace('/{listing_field_'.$block.'_block}(.*?){\/listing_field_'.$block.'_block}/is', '', $tempate_section);
						}else{
							$tempate_section = str_replace('{listing_field_'.$block.'_block}', '', $tempate_section);
							$tempate_section = str_replace('{/listing_field_'.$block.'_block}', '', $tempate_section);
						}
					}
				}
				// Handle Caption Only
				$tempate_section = preg_replace_callback('/{listing_field_([^{}]*?)_caption}/', create_function('$matches', 'global $config,$or_replace_listing_id,$lang;require_once($config[\'basepath\'].\'/include/listing.inc.php\'); return htmlentities(utf8_encode(listing_pages::renderSingleListingItem($or_replace_listing_id, $matches[1],\'caption\')), ENT_QUOTES, \'UTF-8\');'), $tempate_section);
				// Hanle Value Only
				$tempate_section = preg_replace_callback('/{listing_field_([^{}]*?)_value}/', create_function('$matches', 'global $config,$or_replace_listing_id,$lang;require_once($config[\'basepath\'].\'/include/listing.inc.php\'); return htmlentities(utf8_encode(listing_pages::renderSingleListingItem($or_replace_listing_id, $matches[1],\'value\')), ENT_QUOTES, \'UTF-8\');'), $tempate_section);
				// Handle Raw Value
				$tempate_section = preg_replace_callback('/{listing_field_([^{}]*?)_rawvalue}/', create_function('$matches', 'global $config,$or_replace_listing_id,$lang;require_once($config[\'basepath\'].\'/include/listing.inc.php\'); return htmlentities(utf8_encode(listing_pages::renderSingleListingItem($or_replace_listing_id, $matches[1],\'rawvalue\')), ENT_QUOTES, \'UTF-8\');'), $tempate_section);
				// Handle Both Caption and Value
				$tempate_section = preg_replace_callback('/{listing_field_([^{}]*?)}/', create_function('$matches', 'global $config,$or_replace_listing_id,$lang;require_once($config[\'basepath\'].\'/include/listing.inc.php\'); return htmlentities(utf8_encode(listing_pages::renderSingleListingItem($or_replace_listing_id, $matches[1])), ENT_QUOTES, \'UTF-8\');'), $tempate_section);
				$value = htmlentities(utf8_encode(listing_pages::get_title($listing_id)), ENT_QUOTES, 'UTF-8');
				$tempate_section = str_replace('{listing_title}', $value, $tempate_section);
				$value = listing_pages::get_title($listing_id);
				if ($config["controlpanel_mbstring_enabled"] == 1) {
					if(mb_detect_encoding($value)!='UTF-8'){
						$value = utf8_encode($value);
					}
				}

				$tempate_section = str_replace('{rss_listing_title}', $value, $tempate_section);
				$value = htmlentities(utf8_encode(listing_pages::getListingAgent($listing_id)), ENT_QUOTES, 'UTF-8');
				$tempate_section = str_replace('{listing_agent_name}', $value, $tempate_section);
				$value = htmlentities(utf8_encode(listing_pages::getListingAgentFirstName($listing_id)), ENT_QUOTES, 'UTF-8');
				$tempate_section = str_replace('{listing_agent_first_name}', $value, $tempate_section);
				$value = htmlentities(utf8_encode(listing_pages::getListingAgentLastName($listing_id)), ENT_QUOTES, 'UTF-8');
				$tempate_section = str_replace('{listing_agent_last_name}', $value, $tempate_section);
				$value = htmlentities(utf8_encode(listing_pages::getListingAgentLink($listing_id)), ENT_QUOTES, 'UTF-8');
				$tempate_section = str_replace('{listing_agent_link}', $value, $tempate_section);
				$value = htmlentities(utf8_encode(listing_pages::get_pclass($listing_id)), ENT_QUOTES, 'UTF-8');
				$tempate_section = str_replace('{listing_pclass}', $value, $tempate_section);
				$value = htmlentities(utf8_encode(listing_pages::getAgentListingsLink($listing_id)), ENT_QUOTES, 'UTF-8');
				$tempate_section = str_replace('{listing_agent_listings}', $value, $tempate_section);
				$value = htmlentities(utf8_encode(listing_pages::getListingAgentID($listing_id)), ENT_QUOTES, 'UTF-8');
				$tempate_section = str_replace('{listing_agent_id}', $value, $tempate_section);
				// Get listing owner
				$owner_sql = 'SELECT userdb_id FROM ' . $config['table_prefix'] . 'listingsdb WHERE (listingsdb_id = ' . $or_replace_listing_id . ')';
				$recordSet = $conn->execute($owner_sql);
				$or_replace_listing_owner = $recordSet->fields['userdb_id'];
				//New listing_agent_field_****_block tag handler for 2.4.1
				$laf_blocks=array();
				preg_match_all('/{listing_agent_field_([^{}]*?)_block}/',$tempate_section,$laf_blocks);
				require_once($config['basepath'].'/include/user.inc.php');
				global $or_replace_listing_owner;
				if(count($laf_blocks) > 1){
					foreach($laf_blocks[1] as $block){
						$value = user::renderSingleListingItem($or_replace_listing_owner, $block,'rawvalue');
						if($value == ''){
							$tempate_section = preg_replace('/{listing_agent_field_'.$block.'_block}(.*?){\/listing_agent_field_'.$block.'_block}/is', '', $tempate_section);
						}else{
							$tempate_section = str_replace('{listing_agent_field_'.$block.'_block}', '', $tempate_section);
							$tempate_section = str_replace('{/listing_agent_field_'.$block.'_block}', '', $tempate_section);
						}
					}
				}// Replace listing_agent tags
				// Handle Caption Only
				$tempate_section = preg_replace_callback('/{listing_agent_field_([^{}]*?)_caption}/', create_function('$matches', 'global $config,$or_replace_listing_owner,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return htmlentities(utf8_encode(user::renderSingleListingItem($or_replace_listing_owner, $matches[1],\'caption\')), ENT_QUOTES, \'UTF-8\');'), $tempate_section);
				// Hanle Value Only
				$tempate_section = preg_replace_callback('/{listing_agent_field_([^{}]*?)_value}/', create_function('$matches', 'global $config,$or_replace_listing_owner,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return htmlentities(utf8_encode(user::renderSingleListingItem($or_replace_listing_owner, $matches[1],\'value\')), ENT_QUOTES, \'UTF-8\');'), $tempate_section);
				// Handle Raw Value
				$tempate_section = preg_replace_callback('/{listing_agent_field_([^{}]*?)_rawvalue}/', create_function('$matches', 'global $config,$or_replace_listing_owner,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return htmlentities(utf8_encode(user::renderSingleListingItem($or_replace_listing_owner, $matches[1],\'rawvalue\')), ENT_QUOTES, \'UTF-8\');'), $tempate_section);
				// Handle Both Caption and Value
				$tempate_section = preg_replace_callback('/{listing_agent_field_([^{}]*?)}/', create_function('$matches', 'global $config,$or_replace_listing_owner,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return htmlentities(utf8_encode(user::renderSingleListingItem($or_replace_listing_owner, $matches[1])), ENT_QUOTES, \'UTF-8\');'), $tempate_section);
			} else {
				//Deal with listing field blocks
				$lf_blocks=array();
				preg_match_all('/{listing_field_([^{}]*?)_block}/',$tempate_section,$lf_blocks);
				require_once($config['basepath'].'/include/user.inc.php');
				global $or_replace_listing_owner;
				if(count($lf_blocks) > 1){
					foreach($lf_blocks[1] as $block){
						require_once($config['basepath'].'/include/listing.inc.php');
						$value =  listing_pages::renderSingleListingItem($or_replace_listing_id, $block,'rawvalue');
						if($value == ''){
							$tempate_section = preg_replace('/{listing_field_'.$block.'_block}(.*?){\/listing_field_'.$block.'_block}/is', '', $tempate_section);
						}else{
							$tempate_section = str_replace('{listing_field_'.$block.'_block}', '', $tempate_section);
							$tempate_section = str_replace('{/listing_field_'.$block.'_block}', '', $tempate_section);
						}
					}
				}
				// Handle Caption Only
				$tempate_section = preg_replace_callback('/{listing_field_([^{}]*?)_caption}/', create_function('$matches', 'global $config,$or_replace_listing_id,$lang;require_once($config[\'basepath\'].\'/include/listing.inc.php\'); return listing_pages::renderSingleListingItem($or_replace_listing_id, $matches[1],\'caption\');'), $tempate_section);
				// Hanle Value Only
				$tempate_section = preg_replace_callback('/{listing_field_([^{}]*?)_value}/', create_function('$matches', 'global $config,$or_replace_listing_id,$lang;require_once($config[\'basepath\'].\'/include/listing.inc.php\'); return listing_pages::renderSingleListingItem($or_replace_listing_id, $matches[1],\'value\');'), $tempate_section);
				// Handle Raw Value
				$tempate_section = preg_replace_callback('/{listing_field_([^{}]*?)_rawvalue}/', create_function('$matches', 'global $config,$or_replace_listing_id,$lang;require_once($config[\'basepath\'].\'/include/listing.inc.php\'); return listing_pages::renderSingleListingItem($or_replace_listing_id, $matches[1],\'rawvalue\');'), $tempate_section);
				// Handle Both Caption and Value
				$tempate_section = preg_replace_callback('/{listing_field_([^{}]*?)}/', create_function('$matches', 'global $config,$or_replace_listing_id,$lang;require_once($config[\'basepath\'].\'/include/listing.inc.php\'); return listing_pages::renderSingleListingItem($or_replace_listing_id, $matches[1]);'), $tempate_section);
				$value = listing_pages::get_title($listing_id);
				$tempate_section = str_replace('{listing_title}', $value, $tempate_section);
				$value = listing_pages::getListingAgent($listing_id);
				$tempate_section = str_replace('{listing_agent_name}', $value, $tempate_section);
				$value = listing_pages::getListingAgentFirstName($listing_id);
				$tempate_section = str_replace('{listing_agent_first_name}', $value, $tempate_section);
				$value = listing_pages::getListingAgentLastName($listing_id);
				$tempate_section = str_replace('{listing_agent_last_name}', $value, $tempate_section);
				$value = listing_pages::getListingAgentLink($listing_id);
				$tempate_section = str_replace('{listing_agent_link}', $value, $tempate_section);
				$value = listing_pages::get_pclass($listing_id);
				$tempate_section = str_replace('{listing_pclass}', $value, $tempate_section);
				$value = listing_pages::getAgentListingsLink($listing_id);
				$tempate_section = str_replace('{listing_agent_listings}', $value, $tempate_section);
				$value = listing_pages::getListingAgentID($listing_id);
				$tempate_section = str_replace('{listing_agent_id}', $value, $tempate_section);
				// Get listing owner
				$owner_sql = 'SELECT userdb_id FROM ' . $config['table_prefix'] . 'listingsdb WHERE (listingsdb_id = ' . $or_replace_listing_id . ')';
				$recordSet = $conn->execute($owner_sql);
				$or_replace_listing_owner = $recordSet->fields['userdb_id'];
				$laf_blocks=array();
				preg_match_all('/{listing_agent_field_([^{}]*?)_block}/',$tempate_section,$laf_blocks);
				require_once($config['basepath'].'/include/user.inc.php');
				global $or_replace_listing_owner;
				if(count($laf_blocks) > 1){
					foreach($laf_blocks[1] as $block){
						$value = user::renderSingleListingItem($or_replace_listing_owner, $block,'rawvalue');
						if($value == ''){
							$tempate_section = preg_replace('/{listing_agent_field_'.$block.'_block}(.*?){\/listing_agent_field_'.$block.'_block}/is', '', $tempate_section);
						}else{
							$tempate_section = str_replace('{listing_agent_field_'.$block.'_block}', '', $tempate_section);
							$tempate_section = str_replace('{/listing_agent_field_'.$block.'_block}', '', $tempate_section);
						}
					}
				}
				// Replace listing_agent tags
				// Handle Caption Only
				$tempate_section = preg_replace_callback('/{listing_agent_field_([^{}]*?)_caption}/', create_function('$matches', 'global $config,$or_replace_listing_owner,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return user::renderSingleListingItem($or_replace_listing_owner, $matches[1],\'caption\');'), $tempate_section);
				// Hanle Value Only
				$tempate_section = preg_replace_callback('/{listing_agent_field_([^{}]*?)_value}/', create_function('$matches', 'global $config,$or_replace_listing_owner,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return user::renderSingleListingItem($or_replace_listing_owner, $matches[1],\'value\');'), $tempate_section);
				// Handle Raw Value
				$tempate_section = preg_replace_callback('/{listing_agent_field_([^{}]*?)_rawvalue}/', create_function('$matches', 'global $config,$or_replace_listing_owner,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return user::renderSingleListingItem($or_replace_listing_owner, $matches[1],\'rawvalue\');'), $tempate_section);
				// Handle Both Caption and Value
				$tempate_section = preg_replace_callback('/{listing_agent_field_([^{}]*?)}/', create_function('$matches', 'global $config,$or_replace_listing_owner,$lang;require_once($config[\'basepath\'].\'/include/user.inc.php\'); return user::renderSingleListingItem($or_replace_listing_owner, $matches[1]);'), $tempate_section);
			}
			// Listing Images
			$sql2 = "SELECT listingsdb_title FROM " . $config['table_prefix'] . "listingsdb WHERE listingsdb_id = $listing_id";
			$recordSet2 = $conn->Execute($sql2);
			if (!$recordSet2) {
				$misc->log_error($sql2);
			}
			$Title = $misc->make_db_unsafe ($recordSet2->fields['listingsdb_title']);
			if ($config['url_style'] == '1') {
				$url = '<a href="index.php?action=listingview&amp;listingID=' . $listing_id . '">';
				$fullurl = '<a href="' . $config["baseurl"] . '/index.php?action=listingview&amp;listingID=' . $listing_id . '">';
				// Listing Link
				$tempate_section = str_replace('{link_to_listing}', 'index.php?action=listingview&amp;listingID=' . $listing_id, $tempate_section);
				$tempate_section = str_replace('{fulllink_to_listing}', $config['baseurl'] . '/index.php?action=listingview&amp;listingID=' . $listing_id, $tempate_section);
			} else {
				$url_title = str_replace("/", "", $Title);
				$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
				$url = '<a href="listing-' . misc::urlencode_to_sef($url_title) . '-' . $listing_id . '.html">';
				$fullurl = '<a href="' . $config["baseurl"] . '/listing-' . misc::urlencode_to_sef($url_title) . '-' . $listing_id . '.html">';
				// Listing Link
				$tempate_section = str_replace('{link_to_listing}', 'listing-' . misc::urlencode_to_sef($url_title) . '-' . $listing_id . '.html', $tempate_section);
				$tempate_section = str_replace('{fulllink_to_listing}', '' . $config["baseurl"] . '/listing-' . misc::urlencode_to_sef($url_title) . '-' . $listing_id . '.html', $tempate_section);
			}
			// grab the listing's image
			$sql2 = "SELECT listingsimages_id, listingsimages_caption, listingsimages_thumb_file_name, listingsimages_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE listingsdb_id = $listing_id ORDER BY listingsimages_rank";
			$recordSet2 = $conn->Execute($sql2);
			if (!$recordSet2) {
				$misc->log_error($sql2);
			}
			$num_images = $recordSet2->RecordCount();
			if ($num_images == 0) {
				if ($config['show_no_photo'] == 1) {
					$listing_image = $url . '<img src="' . $config["baseurl"] . '/images/nophoto.gif" alt="' . $lang['no_photo'] . '" /></a>';
					$listing_image_full = $fullurl . '<img src="' . $config["baseurl"] . '/images/nophoto.gif" alt="' . $lang['no_photo'] . '" /></a>';
					if ($_GET['action'] == 'listingview') {
						$listing_image = '<img src="' . $config["baseurl"] . '/images/nophoto.gif" alt="' . $lang['no_photo'] . '" />';
						$listing_image_full = '<img src="' . $config["baseurl"] . '/images/nophoto.gif" alt="' . $lang['no_photo'] . '" />';
					}
					$tempate_section = str_replace('{raw_image_thumb_1}', $config['baseurl'] . '/images/nophoto.gif', $tempate_section);
				} else {
					$listing_image = '';
					$tempate_section = str_replace('{raw_image_thumb_1}', '', $tempate_section);
				}
				$tempate_section = str_replace('{image_thumb_1}', $listing_image, $tempate_section);
				$tempate_section = str_replace('{image_thumb_fullurl_1}', $listing_image, $tempate_section);
			}
			$x = 1;
			while (!$recordSet2->EOF) {
//if we're already on the listing then make the urls goto the view image
				$listingsimages_id = $misc->make_db_unsafe($recordSet2->fields['listingsimages_id']);
				$image_caption = $misc->make_db_unsafe($recordSet2->fields['listingsimages_caption']);
				$thumb_file_name = $misc->make_db_unsafe ($recordSet2->fields['listingsimages_thumb_file_name']);
				$full_file_name = $misc->make_db_unsafe ($recordSet2->fields['listingsimages_file_name']);
				if ($_GET['action'] == 'listingview') {
					if ($config['url_style'] == '1') {
						$url = '<a href="index.php?action=view_listing_image&amp;image_id=' . $listingsimages_id . '">';
						$fullurl = '<a href="' . $config["baseurl"] . '/index.php?action=view_listing_image&amp;image_id=' . $listingsimages_id . '">';
					}else {
						$url = '<a href="listing_image_' . $listingsimages_id . '.html">';
						$fullurl = '<a href="' . $config["baseurl"] . '/listing_image_' . $listingsimages_id . '.html">';

					}
				}
				if ($thumb_file_name != "" && file_exists("$config[listings_upload_path]/$thumb_file_name")) {
// Full Image Sizes
					$imagedata = GetImageSize("$config[listings_upload_path]/$full_file_name");
					$imagewidth = $imagedata[0];
					$imageheight = $imagedata[1];
					$max_width = $config['main_image_width'];
					$max_height = $config['main_image_height'];
					$resize_by = $config['resize_by'];
					$shrinkage = 1;
					if (($max_width == $imagewidth) || ($max_height == $imageheight)) {
					$display_width = $imagewidth;
					$display_height = $imageheight;
					} else {
						if ($resize_by == 'width') {
						$shrinkage = $imagewidth / $max_width;
						$display_width = $max_width;
						$display_height = round($imageheight / $shrinkage);
						} elseif ($resize_by == 'height') {
						$shrinkage = $imageheight / $max_height;
						$display_height = $max_height;
						$display_width = round($imagewidth / $shrinkage);
						} elseif ($resize_by == 'both') {
						$display_width = $max_width;
						$display_height = $max_height;
						} elseif ($resize_by == 'bestfit') {
							$shrinkage_width = $imagewidth / $max_width;
							$shrinkage_height = $imageheight / $max_height;
							$shrinkage = max($shrinkage_width, $shrinkage_height);
							$display_height = round($imageheight / $shrinkage);
							$display_width = round($imagewidth / $shrinkage);
						}
					}
// Thumbnail Image Sizes
					$thumb_imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
					$thumb_imagewidth = $thumb_imagedata[0];
					$thumb_imageheight = $thumb_imagedata[1];
					$thumb_max_width = $config['thumbnail_width'];
					$thumb_max_height = $config['thumbnail_height'];
					$resize_thumb_by = $config['resize_thumb_by'];
					$shrinkage = 1;
					if (($thumb_max_width == $thumb_imagewidth) || ($thumb_max_height == $thumb_imageheight)) {
					$thumb_displaywidth = $thumb_imagewidth;
					$thumb_displayheight = $thumb_imageheight;
					} else {
						if ($resize_thumb_by == 'width') {
							$shrinkage = $thumb_imagewidth / $thumb_max_width;
							$thumb_displaywidth = $thumb_max_width;
							$thumb_displayheight = round($thumb_imageheight / $shrinkage);
						} elseif ($resize_thumb_by == 'height') {
							$shrinkage = $thumb_imageheight / $thumb_max_height;
							$thumb_displayheight = $thumb_max_height;
							$thumb_displaywidth = round($thumb_imagewidth / $shrinkage);
						} elseif ($resize_thumb_by == 'both') {
							$thumb_displayheight = $thumb_max_height;
							$thumb_displaywidth = $thumb_max_width;
						}
					}

					$listing_image = $url . '<img src="' . $config['listings_view_images_path'] . '/' . $thumb_file_name . '" height="' . $thumb_displayheight . '" width="' . $thumb_displaywidth . '" alt="' . $image_caption . '" /></a>';
					$listing_image_full = $url . '<img src="' . $config['listings_view_images_path'] . '/' . $full_file_name . '" height="' . $display_height . '" width="' . $display_width . '" alt="' . $image_caption . '" /></a>';
					$listing_image_fullurl = $fullurl . '<img src="' . $config['listings_view_images_path'] . '/' . $thumb_file_name . '" height="' . $thumb_displayheight . '" width="' . $thumb_displaywidth . '" alt="' . $image_caption . '" /></a>';
					$listing_image_full_fullurl = $fullurl . '<img src="' . $config['listings_view_images_path'] . '/' . $full_file_name . '" height="' . $display_height . '" width="' . $display_width . '" alt="' . $image_caption . '" /></a>';
					$tempate_section = str_replace('{image_thumb_' . $x . '}', $listing_image, $tempate_section);
					$tempate_section = str_replace('{raw_image_thumb_' . $x . '}', $config['listings_view_images_path'] . '/' . $thumb_file_name, $tempate_section);
					$tempate_section = str_replace('{image_thumb_fullurl_' . $x . '}', $listing_image_fullurl, $tempate_section);
					//Full Image tags
					$tempate_section = str_replace('{image_full_' . $x . '}', $listing_image_full, $tempate_section);
					$tempate_section = str_replace('{raw_image_full_' . $x . '}', $config['listings_view_images_path'] . '/' . $full_file_name, $tempate_section);
					$tempate_section = str_replace('{image_full_fullurl_' . $x . '}', $listing_image_full_fullurl, $tempate_section);
				} else {
					if ($config['show_no_photo'] == 1) {
						$listing_image = $url . '<img src="' . $config["baseurl"] . '/images/nophoto.gif" alt="' . $lang['no_photo'] . '" /></a>';
						$listing_image_fullurl = $fullurl . '<img src="' . $config["baseurl"] . '/images/nophoto.gif" alt="' . $lang['no_photo'] . '" /></a>';
						$tempate_section = str_replace('{raw_image_thumb_' . $x . '}', $config['baseurl'] . '/images/nophoto.gif', $tempate_section);
					} else {
						$listing_image = '';
						$tempate_section = str_replace('{raw_image_thumb_' . $x . '}', '', $tempate_section);
					}
					$tempate_section = str_replace('{image_thumb_' . $x . '}', $listing_image, $tempate_section);
					$tempate_section = str_replace('{image_thumb_fullurl_' . $x . '}', $listing_image_fullurl, $tempate_section);
					$tempate_section = str_replace('{image_full_' . $x . '}', '', $tempate_section);
					$tempate_section = str_replace('{raw_image_full_' . $x . '}', '', $tempate_section);
					$tempate_section = str_replace('{image_full_fullurl_' . $x . '}', '', $tempate_section);
				}
				// We have the image so insert it into the section.
				$x++;
				$recordSet2->MoveNext();
			} // end while
			// End Listing Images
			$value = array();
			$value = listing_pages::getListingAgentThumbnail($listing_id);
			$x = 0;
			foreach($value as $y) {
				$tempate_section = str_replace('{listing_agent_thumbnail_' . $x . '}', $y, $tempate_section);
				$x++;
			}
			$tempate_section = preg_replace('/{listing_agent_thumbnail_([^{}]*?)}/', '', $tempate_section);
			// End of Listing Tag Replacement
			if ($tsection === true) {
				return $tempate_section;
			} else {
				$this->page = $tempate_section;
			}
		}
	}
}
class page_user extends page {
	function replace_user_action()
	{
		global $lang, $config;
		require_once($config['basepath'] . '/include/login.inc.php');
		$login = new login();
		switch ($_GET['action']) {
			case 'index':
				$_GET['PageID'] = 1;
				require_once($config['basepath'] . '/include/page_display.inc.php');
				$search = new page_display();
				$data = $search->display();
				break;
			case 'member_login':
				$data = $login->display_login('Member');
				break;
			case 'search_step_2':
				require_once($config['basepath'] . '/include/search.inc.php');
				$search = new search_page();
				$data = $search->create_searchpage();
				break;
			case 'searchpage':
				require_once($config['basepath'] . '/include/search.inc.php');
				$search = new search_page();
				$data = $search->create_search_page_logic();
				break;
			case 'searchresults':
				require_once($config['basepath'] . '/include/search.inc.php');
				$search = new search_page();
				$data = $search->search_results();
				break;
			case 'listingview':
				require_once($config['basepath'] . '/include/listing.inc.php');
				$listing = new listing_pages();
				$data = $listing->listing_view();
				break;
			case 'addtofavorites':
				require_once($config['basepath'] . '/include/members_favorites.inc.php');
				$listing = new membersfavorites();
				$data = $listing->addtofavorites();
				break;
			case 'view_favorites':
				require_once($config['basepath'] . '/include/members_favorites.inc.php');
				$listing = new membersfavorites();
				$data = $listing->view_favorites();
				break;
			case 'view_saved_searches':
				require_once($config['basepath'] . '/include/members_search.inc.php');
				$listing = new memberssearch();
				$data = $listing->view_saved_searches();
				break;
			case 'save_search':
				require_once($config['basepath'] . '/include/members_search.inc.php');
				$listing = new memberssearch();
				$data = $listing->save_search();
				break;
			case 'delete_search':
				require_once($config['basepath'] . '/include/members_search.inc.php');
				$listing = new memberssearch();
				$data = $listing->delete_search();
				break;
			case 'delete_favorites':
				require_once($config['basepath'] . '/include/members_favorites.inc.php');
				$listing = new membersfavorites();
				$data = $listing->delete_favorites();
				break;
			case 'page_display':
				require_once($config['basepath'] . '/include/page_display.inc.php');
				$search = new page_display();
				$data = $search->display();
				break;
			case 'calculator':
				require_once($config['basepath'] . '/include/calculators.inc.php');
				$calc = new calculators();
				$data = $calc->start_calc();
				break;
			case 'view_listing_image':
				require_once($config['basepath'] . '/include/images.inc.php');
				$image = new image_handler();
				$data = $image->view_image('listing');
				break;
			case 'view_user_image':
				require_once($config['basepath'] . '/include/images.inc.php');
				$image = new image_handler();
				$data = $image->view_image('userimage');
				break;
			case 'rss_featured_listings':
				require_once($config['basepath'] . '/include/rss.inc.php');
				$rss = new rss();
				$data = $rss->rss_view('featured');
				break;
			case 'rss_lastmodified_listings':
				require_once($config['basepath'] . '/include/rss.inc.php');
				$rss = new rss();
				$data = $rss->rss_view('lastmodified');
				break;
			case 'view_user':
				require_once($config['basepath'] . '/include/user.inc.php');
				$user = new user();
				$data = $user->view_user();
				break;
			case 'view_users':
				require_once($config['basepath'] . '/include/user.inc.php');
				$user = new user();
				$data = $user->view_users();
				break;
			case 'edit_profile':
				require_once($config['basepath'] . '/include/user_manager.inc.php');
				if (!isset($_GET['user_id'])) {
					$_GET['user_id'] = 0;
				}
				$user_managment = new user_managment();
				$data = $user_managment->edit_member_profile($_GET['user_id']);
				break;
			case 'signup':
				if (isset($_GET['type'])) {
					require_once($config['basepath'] . '/include/user_manager.inc.php');
					$listing = new user_managment();
					$data = $listing->user_signup($_GET['type']);
				}
				break;
			case 'show_vtour':
				if (isset($_GET['listingID'])) {
					require_once($config['basepath'] . '/include/vtour.inc.php');
					$vtour = new vtours();
					$data = $vtour->show_vtour($_GET['listingID']);
				} else {
					$data = 'No Listing ID';
				}
				break;
			case 'contact_friend':
				require_once($config['basepath'] . '/include/contact.inc.php');
				$contact = new contact();
				if (isset($_GET['listing_id'])) {
					$data = $contact->ContactFriendForm($_GET['listing_id']);
				}
				break;
			case 'contact_agent':
				require_once($config['basepath'] . '/include/contact.inc.php');
				$contact = new contact();
				if (isset($_GET['listing_id']) && isset($_GET['agent_id'])) {
					$data = $contact->ContactAgentForm($_GET['listing_id'], $_GET['agent_id']);
				} elseif (isset($_GET['listing_id'])) {
					$data = $contact->ContactAgentForm($_GET['listing_id'], 0);
				} elseif (isset($_GET['agent_id'])) {
					$data = $contact->ContactAgentForm(0, $_GET['agent_id']);
				} else {
					$data = '';
				}
				break;
			case 'create_vcard':
				require_once($config['basepath'] . '/include/user.inc.php');
				$user = new user();
				if (isset($_GET['user'])) {
					$data = $user->create_vcard($_GET['user']);
				}
				break;
			case 'create_download':
				require_once($config['basepath'] . '/include/files.inc.php');
				$files = new file_handler();
				if (isset($_GET['ID']) && isset($_GET['file_id']) && isset($_GET['type'])) {
					$data = $files->create_download($_GET['ID'], $_GET['file_id'], $_GET['type']);
				} elseif (isset($_POST['ID']) && isset($_POST['file_id']) && isset($_POST['type'])) {
					$data = $files->create_download($_POST['ID'], $_POST['file_id'], $_POST['type']);
				}
				break;
			case 'blog_index':
				require_once($config['basepath'] . '/include/blog_display.inc.php');
				$blog = new blog_display();
				$data = $blog->disply_blog_index();
				break;
			case 'blog_view_article':
				require_once($config['basepath'] . '/include/blog_display.inc.php');
				$blog = new blog_display();
				$data = $blog->display();
				break;
			case 'verify_email':
				require_once($config['basepath'] . '/include/user_manager.inc.php');
				$user_manager = new user_managment();
				$data = $user_manager->verify_email();
				break;
			default:
				$addon_name = array();
				if (preg_match("/^addon_(.\S*?)_.*/", $_GET['action'], $addon_name)) {
					$file = $config['basepath'] . '/addons/' . $addon_name[1] . '/addon.inc.php';
					if (file_exists($file)) {
						include_once($file);
						$function_name = $addon_name[1] . '_run_action_user_template';
						$data = $function_name();
					} else {
						$data = $lang['addon_doesnt_exist'];
					}
				} else {
					$data = '';
				}
				break;
		} // End switch ($_GET['action'])
		return $data;
	}
	function replace_tags($tags = array())
	{
		global $config, $lang;
		require_once($config['basepath'] . '/include/login.inc.php');
		$login = new login();
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// Remove tags not found in teh template
		$new_tags = $tags;
		$tags = array();
		foreach($new_tags as $tag) {
			if (strpos($this->page, '{' . $tag . '}') !== false) {
				$tags[] = $tag;
			}
		}
		unset($new_tags);
		if (sizeof($tags) > 0) {
			foreach ($tags as $tag) {
				$data = '';
				switch ($tag) {
					case 'content':
						$data = $this->replace_user_action();
						break;
					case 'templated_search_form';
						require_once($config['basepath'] . '/include/search.inc.php');
						$search = new search_page();
						$data = $search->create_searchpage('no', true);
						break;
					case 'baseurl':
						$data = $config['baseurl'];
						break;
					case 'template_url':
						$data = $config['template_url'];
						break;
					case 'addthis_button':
						global $jscript_last;
						$jscript_last .= "\r\n".'<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>';
						$data = '<a href="http://www.addthis.com/bookmark.php?v=250" class="addthis_button"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" border="0" alt="Share" /></a>';
						break;
					case 'load_js':
						$data = $this->load_js();
						break;
					case 'load_js_last':
						global $jscript_last;
						$data = $jscript_last;
						break;
					case 'tabbed_js':
						global $jscript;
						$jscript .= '<script type="text/javascript" src="' . $config['baseurl'] . '/tabpane.js"></script>' . "\r\n";
						$data = '';
						break;
					case 'license_tag':
						$data = "<!--Open-Realty is distributed by Transparent Technologies and is Licensed under the Open-Realty License. See http://www.open-realty.org/license_info.html for more information.-->";
						break;
					case 'main_listing_data':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->getMainListingData($_GET['listingID']);
						break;
					case 'featured_listings_vertical':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsVertical();
						break;
					case 'featured_listings_horizontal':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsHorizontal();
						break;
					case 'random_listings_vertical':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsVertical(0, true);
						break;
					case 'random_listings_horizontal':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsHorizontal(0, true);
						break;
					case 'latest_listings_vertical':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsVertical(0, false, '', true);
						break;
					case 'latest_listings_horizontal':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsHorizontal(0, false, '', true);
						break;
					case (preg_match("/^featured_listings_horizontal_class_([0-9]*)/", $tag, $feat_class)?$tag:!$tag):
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsHorizontal(0,FALSE,$feat_class[1]);
						break;
					case (preg_match("/^featured_listings_vertical_class_([0-9]*)/", $tag, $feat_class)?$tag:!$tag):
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsVertical(0,FALSE,$feat_class[1]);
						break;
					case (preg_match("/^random_listings_horizontal_class_([0-9]*)/", $tag, $feat_class)?$tag:!$tag):
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsHorizontal(0,TRUE,$feat_class[1]);
						break;
					case (preg_match("/^random_listings_vertical_class_([0-9]*)/", $tag, $feat_class)?$tag:!$tag):
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsVertical(0,TRUE,$feat_class[1]);
						break;
					case (preg_match("/^latest_listings_horizontal_class_([0-9]*)/", $tag, $feat_class)?$tag:!$tag):
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsHorizontal(0, false, $feat_class[1], true);
						break;
					case (preg_match("/^latest_listings_vertical_class_([0-9]*)/", $tag, $feat_class)?$tag:!$tag):
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderFeaturedListingsVertical(0, false, $feat_class[1], true);
						break;
					case 'headline':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderTemplateAreaNoCaption('headline', $_GET['listingID']);
						break;
					case 'listing_images':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsImages($_GET['listingID'], 'yes');
						break;
					case 'listing_images_nocaption':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsImages($_GET['listingID'], 'no');
						break;
					case 'listing_files_select':
						require_once($config['basepath'] . '/include/files.inc.php');
						$files = new file_handler();
						$data = $files->render_files_select($_GET['listingID'], 'listing');
						break;
					case 'files_listing_vertical':
						require_once($config['basepath'] . '/include/files.inc.php');
						$files = new file_handler();
						$data = $files->render_templated_files($_GET['listingID'], 'listing', 'vertical');
						break;
					case 'files_listing_horizontal':
						require_once($config['basepath'] . '/include/files.inc.php');
						$files = new file_handler();
						$data = $files->render_templated_files($_GET['listingID'], 'listing', 'horizontal');
						break;
					case 'slideshow_images':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsMainImageSlideShow($_GET['listingID']);
						break;
					case 'link_calc':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_calc_link();
						break;
					case 'link_calc_url':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_calc_link($url_only = 'yes');
						break;
					case 'link_add_favorites':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_add_favorite_link();
						break;
					case 'link_add_favorites_url':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_add_favorite_link($url_only = 'yes');
						break;
					case 'link_printer_friendly':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_printer_friendly_link();
						break;
					case 'link_email_friend':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_email_friend_link();
						break;
					case 'link_map':
						require_once($config['basepath'] . '/include/maps.inc.php');
						$maps = new maps();
						$data = $maps->create_map_link();
						break;
					case 'link_yahoo_school':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_yahoo_school_link();
						break;
					case 'link_yahoo_neighborhood':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_yahoo_neighborhood_link();
						break;
					case 'link_printer_friendly_url':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_printer_friendly_link($url_only = 'yes');
						break;
					case 'link_email_friend_url':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_email_friend_link($url_only = 'yes');
						break;
					case 'link_map_url':
						require_once($config['basepath'] . '/include/maps.inc.php');
						$maps = new maps();
						$data = $maps->create_map_link($url_only = 'yes');
						break;
					case 'link_yahoo_school_url':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_yahoo_school_link($url_only = 'yes');
						break;
					case 'link_yahoo_neighborhood_url':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->create_yahoo_neighborhood_link($url_only = 'yes');
						break;
					case 'contact_agent_link_url':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->contact_agent_link($url_only = 'yes');
						break;
					case 'agent_info':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->renderUserInfoOnListingsPage($_GET['listingID']);
						break;
					case 'listing_email':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->getListingEmail($_GET['listingID']);
						break;
					case 'hitcount':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->hitcount($_GET['listingID']);
						break;
					case 'main_image':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsMainImage($_GET['listingID'], 'yes', 'no');
						break;
					case 'main_image_nodesc':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsMainImage($_GET['listingID'], 'no', 'no');
						break;
					case 'main_image_java':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsMainImage($_GET['listingID'], 'yes', 'yes');
						break;
					case 'main_image_java_nodesc':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsMainImage($_GET['listingID'], 'no', 'yes');
						break;
					case 'listing_images_java':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsImagesJava($_GET['listingID'], 'no');
						break;
					case 'listing_images_java_caption':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsImagesJava($_GET['listingID'], 'yes');
						break;
					case 'listing_images_java_rows':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsImagesJavaRows($_GET['listingID']);
						break;
					case 'listing_images_mouseover_java':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsImagesJava($_GET['listingID'], 'no', 'yes');
						break;
					case 'listing_images_mouseover_java_caption':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsImagesJava($_GET['listingID'], 'yes', 'yes');
						break;
					case 'listing_images_mouseover_java_rows':
						require_once($config['basepath'] . '/include/images.inc.php');
						$images = new image_handler();
						$data = $images->renderListingsImagesJavaRows($_GET['listingID'], 'yes');
						break;
					case 'vtour_button':
						require_once($config['basepath'] . '/include/vtour.inc.php');
						$vtour = new vtours();
						$data = $vtour->rendervtourlink($_GET['listingID']);
						break;
					case 'listingid':
						$data = $_GET['listingID'];
						break;
					case 'get_creation_date':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->get_creation_date($_GET['listingID']);
						break;
					case 'get_featured_raw':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->get_featured($_GET['listingID'], 'yes');
						break;
					case 'get_featured':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->get_featured($_GET['listingID'], 'no');
						break;
					case 'get_modified_date':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->get_modified_date($_GET['listingID']);
						break;
					case 'contact_agent_link':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->contact_agent_link();
						break;
					case 'select_language':
						// require_once($config['basepath'] . '/include/multilingual.inc.php');
						// $multilingual = new multilingual();
						// $data = $multilingual->multilingual_select();
						break;
					case 'company_name':
						$data = $config['company_name'];
						break;
					case 'company_location':
						$data = $config['company_location'];
						break;
					case 'company_logo':
						$data = $config['company_logo'];
						break;
					case 'show_vtour':
						if (isset($_GET['listingID'])) {
							require_once($config['basepath'] . '/include/vtour.inc.php');
							$vtour = new vtours();
							$data = $vtour->show_vtour($_GET['listingID'], false);
						} else {
							$data = 'No Listing ID';
						}
						break;
					case 'charset':
						$data = $config['charset'];
						break;
					case 'link_edit_listing':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->edit_listing_link();
						break;
					case 'link_edit_listing_url':
						require_once($config['basepath'] . '/include/listing.inc.php');
						$listing = new listing_pages();
						$data = $listing->edit_listing_link('yes');
						break;
					case 'template_select':
						$data = $this->template_selector();
						break;
					case 'money_sign':
						$data = $config['money_sign'];
						break;
					default:
						if (preg_match("/^addon_(.*?)_.*/", $tag, $addon_name)) {
							$file = $config['basepath'] . '/addons/' . $addon_name[1] . '/addon.inc.php';
							if (file_exists($file)) {
								include_once($file);
								$function_name = $addon_name[1] . '_run_template_user_fields';
								$data = $function_name($tag);
							} else {
								$data = '';
							}
						} else {
							$data = '';
						}
						break;
				}
				$this->page = str_replace('{' . $tag . '}', $data, $this->page);
			}
		}
		unset($tags);
		unset($tag);
	}
	function template_selector()
	{
		global $config;
		$display = '';
		$display .= '<form class="template_selector" id="template_select" action="" method="post">';
		$display .= '<fieldset>';
		$display .= '<select id="select_users_template" name="select_users_template" onchange="this.form.submit();">';
		$template_directory = $config['basepath']."/template";
		$template = opendir($template_directory) or die("fail to open");
		while (false !== ($file = readdir($template)))
		{
			if ($file != '.' && $file != '..' && $file != '.svn')
			{
				if(is_dir("$template_directory/$file"))
				{
					$display .= '<option value="'.$file.'"';
					if ($config['template'] == $file)
					{
						$display .= ' selected="selected"';
					}
					$display .= '>'.$file.'</option>';
				}
			}
		}
		$display .= '</select>';
		$display .= '</fieldset>';
		$display .= '</form>';
		closedir($template);
		return $display;
	}
}

class page_admin extends page {
	function replace_tags($tags = array())
	{
		global $config, $lang;
		require_once($config['basepath'] . '/include/login.inc.php');
		$login = new login();
		$login_status = $login->loginCheck('Agent');
		if (sizeof($tags) > 0) {
			// Remove tags not found in teh template
			$new_tags = $tags;
			$tags = array();
			foreach($new_tags as $tag) {
				if (strpos($this->page, '{' . $tag . '}') !== false) {
					$tags[] = $tag;
				}
			}
			unset($new_tags);
			//print_r($tags);
			foreach($tags as $tag) {
				switch ($tag) {
					case 'select_language':
						// require_once($config['basepath'].'/include/multilingual.inc.php');
						// $multilingual = new multilingual();
						// $data = $multilingual->multilingual_select();
						break;
					case 'version':
						$data = $lang['version'] . ' ' . $config['version'];
						break;
					case 'license_tag':
						$data = "<!--Open-Realty is distributed by Transparent Technologies and is Licensed under the Open-Realty License. See http://www.open-realty.org/license_info.html for more information.-->";
						break;
					case 'company_name':
						$data = $config['company_name'];
						break;
					case 'company_location':
						$data = $config['company_location'];
						break;
					case 'company_logo':
						$data = $config['company_logo'];
						break;
					case 'site_title':
						$data = $config['seo_default_title'];
						break;
					case 'lang_index_home':
						$data = $lang['index_home'];
						break;
					case 'lang_index_admin':
						$data = $lang['index_admin'];
						break;
					case 'lang_index_logout':
						$data = $lang['index_logout'];
						break;
					case 'baseurl':
						$data = $config['baseurl'];
						break;
					case 'general_info':
						require_once($config['basepath'] . '/include/admin.inc.php');
						$admin = new general_admin();
						$data = $admin->general_info();
						break;
					case 'openrealty_links':
						require_once($config['basepath'] . '/include/admin.inc.php');
						$admin = new general_admin();
						$data = $admin->openrealty_links();
						break;
					case 'addon_links':
						// Show Addons
						global $config, $lang;
						$data = '';
						$addons = $this->load_addons();
						require_once($config['basepath'] . '/include/admin.inc.php');
						$admin = new general_admin();
						$addon_links = array();
						//print_r($addons);
						foreach ($addons as $addon) {
							//echo 'Loading '.$addon;
							$addon_link = array();
							$addon_link = $admin->display_addons($addon);
							//echo "\r\n Addon Link:".print_r($addon_link,TRUE);
							if (is_array($addon_link)) {
								foreach ($addon_link as $link) {
									if(trim($link)!==''){
										$addon_links[] = $link;
									}
								}
							} else {
								if(trim($addon_link)!==''){
									$addon_links[] = $addon_link;
								}
							}
						}

						$current_link = 0;
						$cell_count = 0;
						$link_count = count($addon_links);
						if ($link_count > 0) {
							$data .= '<tr><td class="addon_header" colspan="4">' . $lang['addons'] . '</td></tr>';
						} while ($current_link < $link_count) {
							if ($cell_count == 4) {
								$data .= '</tr>';
								$cell_count = 0;
							}
							if ($cell_count == 0) {
								$data .= '<tr>';
							}
							if ($addon_links[$current_link]) {
								$data .= '<td style="width:25%; text-align:center;" valign="top">' . $addon_links[$current_link] . '</td>';
								$cell_count++;
							}
							$current_link++;
						} // while

						break;
					case 'lang':
						if (isset($_SESSION["users_lang"]) && $_SESSION["users_lang"] != $config['lang']) {
							$data = $_SESSION["users_lang"];
						} else {
							$data = $config['lang'];
						}
						break;
					case 'user_id':
						$data = $_SESSION['userID'];
						break;
					case 'template_url':
						$data = $config['admin_template_url'];
						break;
					case 'load_js_body':
						require_once($config['basepath'] . '/include/admin.inc.php');
						$admin = new general_admin();
						$data = $admin->load_js_body();
						break;
					case 'load_js':
						$data = $this->load_js();
						break;
					case 'load_js_last':
						global $jscript_last;
						$data = $jscript_last;
						break;
					case 'content':
						$data = $this->replace_admin_actions();
						break;
					case 'charset':
						$data = $config['charset'];
						break;
					case 'help_link':
						if ($config["use_help_link"] == 1) {
						$help_link = $this->get_help_link();
						$data = '<a href="' . $help_link . '" onclick="window.open(this.href,\'_blank\',\'location=0,status=0,scrollbars=1,toolbar=0,menubar=0,width=500,height=520,resizable=yes\');return false">' . $lang['index_help'] . '</a>';
						} else {
						$data = '';
						}
						break;
					default:
						if (preg_match("/^addon_(.*?)_.*/", $tag, $addon_name)) {
							$file = $config['basepath'] . '/addons/' . $addon_name[1] . '/addon.inc.php';
							if (file_exists($file)) {
								include_once($file);
								$function_name = $addon_name[1] . '_run_template_user_fields';
								$data = $function_name($tag);
								//echo 'Found addon tag '.print_r($data,TRUE);
							} else {
								$data = '';
							}
						} else {
							$data = '';
						}
						break;
				}
				$this->page = str_replace('{' . $tag . '}', $data, $this->page);
			}
		}
	}
	function replace_admin_actions()
	{
		global $config, $lang;
		require_once($config['basepath'] . '/include/login.inc.php');
		$login = new login();
		$login_status = $login->loginCheck('Agent');
		if ($login_status !== true) {
			// Run theese commands even if not logged in.
			$data = '';
			switch ($_GET['action']) {
				case 'send_forgot':
					require_once($config['basepath'] . '/include/login.inc.php');
					$data = login::forgot_password();
					break;
				case 'forgot':
					require_once($config['basepath'] . '/include/login.inc.php');
					$data = login::forgot_password_reset();
					break;
				default:
					$data .= $login_status;
					break;
			}
		} else {
			switch ($_GET['action']) {
				case 'index':
					require_once($config['basepath'] . '/include/admin.inc.php');
					$admin = new general_admin();
					$data = $admin->index_page();
					break;
				case 'edit_page':
					require_once($config['basepath'] . '/include/editor.inc.php');
					$listing = new editor();
					$data = $listing->page_edit();
					break;
				case 'edit_user_images':
					require_once($config['basepath'] . '/include/images.inc.php');
					$images = new image_handler();
					$data = $images->edit_user_images();
					break;
				case 'edit_listing_images':
					require_once($config['basepath'] . '/include/images.inc.php');
					$images = new image_handler();
					$data = $images->edit_listing_images();
					break;
				case 'edit_vtour_images':
					require_once($config['basepath'] . '/include/images.inc.php');
					$images = new image_handler();
					$data = $images->edit_vtour_images();
					break;
				case 'edit_listing_files':
					require_once($config['basepath'] . '/include/files.inc.php');
					$files = new file_handler();
					$data = $files->edit_listing_files();
					break;
				case 'edit_user_files':
					require_once($config['basepath'] . '/include/files.inc.php');
					$files = new file_handler();
					$data = $files->edit_user_files();
					break;
				case 'add_listing':
					require_once($config['basepath'] . '/include/listing_editor.inc.php');
					$listing_editor = new listing_editor();
					$data = $listing_editor->add_listing();
					break;
				case 'edit_my_listings':
					require_once($config['basepath'] . '/include/listing_editor.inc.php');
					$listing_editor = new listing_editor();
					$data = $listing_editor->edit_listings();
					break;
				case 'edit_listings':
					require_once($config['basepath'] . '/include/listing_editor.inc.php');
					$listing_editor = new listing_editor();
					$data = $listing_editor->edit_listings(false);
					break;
				case 'configure':
					require_once($config['basepath'] . '/include/controlpanel.inc.php');
					$listing_editor = new configurator();
					$data = $listing_editor->show_configurator();
					break;
				case 'edit_listing_template':
					require_once($config['basepath'] . '/include/template_editor.inc.php');
					$listing = new template_editor();
					$data = $listing->edit_listing_template();
					break;
				case 'edit_listings_template_field_order':
					require_once($config['basepath'] . '/include/template_editor.inc.php');
					$listing = new template_editor();
					$data = $listing->edit_listings_template_field_order();
					break;
				case 'edit_agent_template_field_order':
					require_once($config['basepath'] . '/include/template_editor.inc.php');
					$listing = new template_editor();
					$data = $listing->edit_template_field_order($type = 'agent');
					break;
				case 'edit_member_template_field_order':
					require_once($config['basepath'] . '/include/template_editor.inc.php');
					$listing = new template_editor();
					$data = $listing->edit_template_field_order($type = 'member');
					break;
				case 'edit_agent_template_add_field':
					require_once($config['basepath'] . '/include/template_editor.inc.php');
					$listing = new template_editor();
					$data = $listing->add_user_template_field($type = 'agent');
					break;
				case 'edit_member_template_add_field':
					require_once($config['basepath'] . '/include/template_editor.inc.php');
					$listing = new template_editor();
					$type = 'member';
					$data = $listing->add_user_template_field($type);
					break;
				case 'edit_listing_template_search':
					require_once($config['basepath'] . '/include/template_editor.inc.php');
					$listing = new template_editor();
					$data = $listing->edit_listing_template_search();
					break;
				case 'edit_listing_template_search_results':
					require_once($config['basepath'] . '/include/template_editor.inc.php');
					$listing = new template_editor();
					$data = $listing->edit_listing_template_search_results();
					break;
				case 'user_manager':
					require_once($config['basepath'] . '/include/user_manager.inc.php');
					$user_managment = new user_managment();
					$data = $user_managment->show_user_manager();
					break;
				case 'edit_user_template':
					require_once($config['basepath'] . '/include/template_editor.inc.php');
					$listing = new template_editor();
					$data = $listing->edit_user_template();
					break;
				case 'edit_listing_template_add_field':
					require_once($config['basepath'] . '/include/template_editor.inc.php');
					$listing = new template_editor();
					$data = $listing->add_listing_template_field();
					break;
				case 'add_page':
					require_once($config['basepath'] . '/include/editor.inc.php');
					$listing = new editor();
					$data = $listing->add_page();
					break;
				case 'view_log':
					require_once($config['basepath'] . '/include/log.inc.php');
					$data = log::view();
					break;
				case 'clear_log':
					require_once($config['basepath'] . '/include/log.inc.php');
					$data = log::clear_log();
					break;
				case 'show_property_classes':
					require_once($config['basepath'] . '/include/propertyclass.inc.php');
					$data = propertyclass::show_classes();
					break;
				case 'modify_property_class':
					require_once($config['basepath'] . '/include/propertyclass.inc.php');
					$data = propertyclass::modify_property_class();
					break;
				case 'delete_property_class':
					require_once($config['basepath'] . '/include/propertyclass.inc.php');
					$data = propertyclass::delete_property_class();
					break;
				case 'insert_property_class':
					require_once($config['basepath'] . '/include/propertyclass.inc.php');
					$data = propertyclass::insert_property_class();
					break;
				case 'add_listing_property_class':
					require_once($config['basepath'] . '/include/listing_editor.inc.php');
					$listing_editor = new listing_editor();
					$data = $listing_editor->add_listing_logic();
					break;
				//Todo Finish Adding Blog Items
				case 'edit_blog':
					require_once($config['basepath'] . '/include/blog_editor.inc.php');
					$listing = new blog_editor();
					$data = $listing->blog_edit_index();
					break;
				case 'edit_blog_post':
					require_once($config['basepath'] . '/include/blog_editor.inc.php');
					$listing = new blog_editor();
					$data = $listing->blog_edit();
					break;
				case 'add_blog':
					require_once($config['basepath'] . '/include/blog_editor.inc.php');
					$listing = new blog_editor();
					$data = $listing->add_post();
					break;
				case 'edit_blog_post_comments':
					require_once($config['basepath'] . '/include/blog_editor.inc.php');
					$listing = new blog_editor();
					$data = $listing->edit_post_comments();
					break;
				case 'addon_manager':
					require_once($config['basepath'] . '/include/addon_manager.inc.php');
					$am = new addon_manager();
					$data = $am->display_addon_manager();
					break;
				case 'send_notifications':
					require_once($config['basepath'] . '/include/notification.inc.php');
					$notify = new notification();
					$data = $notify->NotifyUsersOfAllNewListings();
					break;
				default:
					// Handle Addons
					$addon_name = array();
					if (preg_match("/^addon_(.\S*?)_.*/", $_GET['action'], $addon_name)) {
						include_once($config['basepath'] . '/addons/' . $addon_name[1] . '/addon.inc.php');
						$function_name = $addon_name[1] . '_run_action_admin_template';
						$data = $function_name();
					}
			}
		}
		return $data;
	}
	function get_help_link() {
		global $lang, $config, $conn;
		switch ($_GET['action']) {
				case 'add_listing_property_class':
					$data = $config["add_listing_help_link"];
					break;
				case 'edit_listings':
					if (isset($_GET['edit'])) {
						$data = $config["modify_listing_help_link"];
					} else {
						$data = $config["edit_listing_help_link"];
					}
					break;
				case 'edit_my_listings':
					if (isset($_GET['edit'])) {
						$data = $config["modify_listing_help_link"];
					} else {
						$data = $config["edit_listing_help_link"];
					}
					break;
				case 'user_manager':
					if (isset($_GET['edit'])) {
						$data = $config["edit_user_help_link"];
					} else {
						$data = $config["user_manager_help_link"];
					}
					break;
				case 'edit_page':
					$data = $config["page_editor_help_link"];
					break;
				case 'edit_listing_images':
					$data = $config["edit_listing_images_help_link"];
					break;
				case 'edit_vtour_images':
					$data = $config["edit_vtour_images_help_link"];
					break;
				case 'edit_listing_files':
					$data = $config["edit_listing_files_help_link"];
					break;
				case 'edit_agent_template_add_field':
					$data = $config["edit_agent_template_add_field_help_link"];
					break;
				case 'edit_agent_template_field_order':
					$data = $config["edit_agent_template_field_order_help_link"];
					break;
				case 'edit_member_template_add_field':
					$data = $config["edit_member_template_add_field_help_link"];
					break;
				case 'edit_member_template_field_order':
					$data = $config["edit_member_template_field_order_help_link"];
					break;
				case 'edit_listing_template':
					$data = $config["edit_listing_template_help_link"];
					break;
				case 'edit_listing_template_add_field':
					$data = $config["edit_listing_template_add_field_help_link"];
					break;
				case 'edit_listings_template_field_order':
					$data = $config["edit_listings_template_field_order_help_link"];
					break;
				case 'edit_listing_template_search':
					$data = $config["edit_listing_template_search_help_link"];
					break;
				case 'edit_listing_template_search_results':
					$data = $config["edit_listing_template_search_results_help_link"];
					break;
				case 'show_property_classes':
					$data = $config["show_property_classes_help_link"];
					break;
				case 'configure':
					$data = $config["configure_help_link"];
					break;
				case 'view_log':
					$data = $config["view_log_help_link"];
					break;
				case 'addon_transparentmaps_admin':
					$data = $config["addon_transparentmaps_admin_help_link"];
					break;
				case 'addon_transparentmaps_geocode_all':
					$data = $config["addon_transparentmaps_geocode_all_help_link"];
					break;
				case 'addon_transparentRETS_config_server':
					$data = $config["addon_transparentRETS_config_server_help_link"];
					break;
				case 'addon_transparentRETS_config_imports':
					$data = $config["addon_transparentRETS_config_imports_help_link"];
					break;
				case 'edit_user_template':
					if ($_GET['type'] == 'agent') {
						$data = $config["edit_user_template_agent_help_link"];
					} elseif ($_GET['edit'] == 'member') {
						$data = $config["edit_user_template_member_help_link"];
					}
					break;
				case 'modify_property_class':
					$data = $config["modify_property_class_help_link"];
					break;
				case 'addon_IDXManager_config':
					$data = $config["addon_IDXManager_config_help_link"];
					break;
				case 'addon_IDXManager_classmanager':
					$data = $config["addon_IDXManager_classmanager_help_link"];
					break;
				case 'addon_csvloader_admin':
					$data = $config["addon_csvloader_admin_help_link"];
					break;
				case 'insert_property_class':
					$data = $config["insert_property_class_help_link"];
					break;
				default:
					$data = $config["main_admin_help_link"];
		}
	return $data;
	}
}

?>