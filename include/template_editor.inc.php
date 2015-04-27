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
// Set Error Handling to E_ALL
class template_editor {
	function insert_user_field($type)
	{
		// include global variables
		global $conn, $lang, $config;
		if ($type == 'member') {
			$security = login::loginCheck('edit_member_template', true);
		}else {
			$security = login::loginCheck('edit_agent_template', true);
		}
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			if (isset($_POST['edit_field']) && !isset($_POST['lang_change'])) {
				$field_type = $misc->make_db_safe($_POST['field_type']);
				$_POST['edit_field'] = str_replace(" ", "_", $_POST['edit_field']);
				$field_name = $misc->make_db_safe($_POST['edit_field']);
				$field_caption = $misc->make_db_safe($_POST['field_caption']);
				$default_text = $misc->make_db_safe($_POST['default_text']);
				$field_elements = $misc->make_db_safe($_POST['field_elements']);
				$rank = $misc->make_db_safe($_POST['rank']);
				$required = $misc->make_db_safe($_POST['required']);
				$tool_tip = $misc->make_db_safe($_POST['tool_tip']);
				if ($type == 'member') {
					$sql = "INSERT INTO " . $config['table_prefix'] . $type . "formelements (" . $type . "formelements_field_type, " . $type . "formelements_field_name, " . $type . "formelements_field_caption, " . $type . "formelements_default_text, " . $type . "formelements_field_elements, " . $type . "formelements_rank, " . $type . "formelements_required, " . $type . "formelements_tool_tip) VALUES ($field_type,$field_name,$field_caption,$default_text,$field_elements,$rank,$required,$tool_tip)";
				}else {
					$sql = "INSERT INTO " . $config['table_prefix'] . $type . "formelements (" . $type . "formelements_field_type, " . $type . "formelements_field_name, " . $type . "formelements_field_caption, " . $type . "formelements_default_text, " . $type . "formelements_field_elements, " . $type . "formelements_rank, " . $type . "formelements_required," . $type . "formelements_display_priv, " . $type . "formelements_tool_tip) VALUES ($field_type,$field_name,$field_caption,$default_text,$field_elements,$rank,$required,0,$tool_tip)";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				return $lang['admin_template_editor_field_added'];
			}
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	function show_user_navbar($type)
	{
		// include global variables
		global $conn, $lang, $config;
		if ($type == 'member') {
			$security = login::loginCheck('edit_member_template', true);
		}else {
			$security = login::loginCheck('edit_agent_template', true);
		}
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$display = '';
			// Grab a list of pages in the Database to Edit
			$sql = 'SELECT ' . $type . 'formelements_field_name FROM ' . $config['table_prefix'] . $type . 'formelements';
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			$field_names = '';
			while (!$recordSet->EOF) {
				$field_names .= '<option value="' . $recordSet->fields[$type . 'formelements_field_name'] . '">' . $recordSet->fields[$type . 'formelements_field_name'] . '</option>';
				$recordSet->MoveNext();
			}
			$display .= '<span class="section_header">' . $lang[$type . '_editor'] . '</span><br /><br />';
			$display .= '<div class="template_editor_navbar">';
			$display .= '<form action="' . $config['baseurl'] . '/admin/index.php?action=edit_user_template&amp;type=' . $type . '" method="post" id="navbar">';
			$display .= '<div class="template_editor_navbar_item">' . $lang['field'];
			$display .= ' <select name="edit_field" class="edit_field">';
			$display .= $field_names;
			$display .= '</select><input type="submit" value="' . $lang['edit'] . '" class="edit_field">';
			$display .= '</div>';
			$display .= '</form>';
			$display .= '<div class="template_editor_navbar_item"><a href="' . $config['baseurl'] . '/admin/index.php?action=edit_' . $type . '_template_add_field">' . $lang['add_field'] . '</a></div>';
			$display .= '<div class="template_editor_navbar_item"><a href="' . $config['baseurl'] . '/admin/index.php?action=edit_' . $type . '_template_field_order">' . $lang['set_field_order'] . '</a></div>';
			$display .= "</div><br />\r\n";

			return $display;
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	function add_user_template_field($type)
	{
		// include global variables
		global $conn, $lang, $config;
		if ($type == 'member') {
			$security = login::loginCheck('edit_member_template', true);
		}else {
			$security = login::loginCheck('edit_agent_template', true);
		}
		if ($security === true) {
			$display = '';
			if (!isset($_POST['edit_field']) && !isset($_POST['lang_change'])) {
				$display .= template_editor::show_user_navbar($type);
				$display .= '<br /><form action="' . $config['baseurl'] . '/admin/index.php?action=edit_' . $type . '_template_add_field" method="post"  id="update_field">';
				$display .= '<table align="center">';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_name'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left"><input type="text" name="edit_field" value=""></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_type'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left">';
				$display .= '<select name="field_type" size="1">';
				$display .= '<option value="text" selected="selected">' . $lang['text'] . '</option>';
				$display .= '<option value="textarea" >' . $lang['textarea'] . '</option>';
				$display .= '<option value="select" >' . $lang['select'] . '</option>';
				$display .= '<option value="select-multiple">' . $lang['select-multiple'] . '</option>';
				$display .= '<option value="option" >' . $lang['option'] . '</option>';
				$display .= '<option value="checkbox" >' . $lang['checkbox'] . '</option>';
				$display .= '<option value="divider">' . $lang['divider'] . '</option>';
				$display .= '<option value="price">' . $lang['price'] . '</option>';
				$display .= '<option value="url">' . $lang['url'] . '</option>';
				$display .= '<option value="email">' . $lang['email'] . '</option>';
				$display .= '<option value="number">' . $lang['number'] . '</option>';
				$display .= '<option value="decimal">' . $lang['decimal'] . '</option>';
				$display .= '<option value="date">' . $lang['date'] . '</option>';
				$display .= '<option value="lat">' . $lang['lat'] . '</option>';
				$display .= '<option value="long">' . $lang['long'] . '</option>';
				$display .= '</select>';
				$display .= '</td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_required'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left">';
				$display .= '<select name="required" size="1">';
				$display .= '<option value="No"  selected="selected">' . $lang['no'] . '</option>';
				$display .= '<option value="Yes" >' . $lang['yes'] . '</option>';
				$display .= '</select>';
				$display .= '</td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_caption'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left"><input type=text name="field_caption" value=""></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_elements'] . ':</b><br /><div class="small">(' . $lang['admin_template_editor_choices_separated'] . ')</div></td>';
				$display .= '<td class="templateEditorHead" align="left"><input type=text name="field_elements" value=""></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_default_text'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left"><input type=text name="default_text" value = ""></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_tool_tip'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left"><textarea name="tool_tip"  cols="80" rows="5"></textarea></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_rank'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left" ><input type=text name="rank" value="0"></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><input type="hidden" name="type" value="' . $type . '"></td>';
				$display .= '<td class="templateEditorHead" align="left"><input type="submit" name="submit" value="' . $lang['add_field'] . '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				$display .= '</tr>';
				$display .= '</table>';
				$display .= '</form>';
			}else {
				$status = template_editor::insert_user_field($_POST['type']);
				$display .= template_editor::show_user_navbar($type);
				$display .= $status;
				if ($status == $lang['admin_template_editor_field_added']) {
					$display .= template_editor::edit_user_field($_POST['edit_field'], $_POST['type']);
				}
			}
			return $display;
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	function edit_user_template()
	{
		global $lang;
		if ($type == 'member') {
			$security = login::loginCheck('edit_member_template', true);
		}else {
			$security = login::loginCheck('edit_agent_template', true);
		}
		$display = '';
		if ($security === true) {
			if (isset($_GET['type'])) {
				template_editor::delete_user_field($_GET['type']);
				template_editor::update_user_field($_GET['type']);
				$display .= template_editor::show_user_navbar($_GET['type']);
				if (isset($_POST['edit_field'])) {
					$display .= template_editor::edit_user_field($_POST['edit_field'], $_GET['type']);
				}
			}
		}else {
			$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
		return $display;
	}
	function edit_user_field($listing_field_name, $type)
	{
		// include global variables
		global $conn, $lang, $config;
		if ($type == 'member') {
			$security = login::loginCheck('edit_member_template', true);
		}else {
			$security = login::loginCheck('edit_agent_template', true);
		}
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$edit_listing_field_name = $misc->make_db_safe($listing_field_name);
			$sql = 'SELECT * FROM ' . $config['table_prefix'] . $type . 'formelements WHERE ' . $type . 'formelements_field_name = ' . $edit_listing_field_name;
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$id = $misc->make_db_unsafe($recordSet->fields[$type . 'formelements_id']);
			$field_type = $misc->make_db_unsafe($recordSet->fields[$type . 'formelements_field_type']);
			$field_name = $misc->make_db_unsafe($recordSet->fields[$type . 'formelements_field_name']);
			$field_caption = $misc->make_db_unsafe($recordSet->fields[$type . 'formelements_field_caption']);
			$default_text = $misc->make_db_unsafe($recordSet->fields[$type . 'formelements_default_text']);
			$field_elements = $misc->make_db_unsafe($recordSet->fields[$type . 'formelements_field_elements']);
			$rank = $misc->make_db_unsafe($recordSet->fields[$type . 'formelements_rank']);
			$required = $misc->make_db_unsafe($recordSet->fields[$type . 'formelements_required']);
			$tool_tip = $misc->make_db_unsafe($recordSet->fields[$type . 'formelements_tool_tip']);
			if ($type == 'agent') {
				$display_priv = $misc->make_db_unsafe($recordSet->fields['agentformelements_display_priv']);
			}

			$display = '';
			$display .= '<br /><form action="' . $config['baseurl'] . '/admin/index.php?action=edit_user_template&amp;type=' . $type . '" method="post"  id="update_field">';
			$display .= '<table align="center">';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_name'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left"><input type="hidden" name="update_id" value="' . $id . '"><input type="text" name="edit_field" value="' . $field_name . '"></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_type'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left">';
			$display .= '<select name="field_type" size="1">';
			$display .= '<option value="' . $field_type . '" selected="selected">' . $lang[$field_type] . '</option>';
			$display .= '<option value="">-----</option>';
			$display .= '<option value="text">' . $lang['text'] . '</option>';
			$display .= '<option value="textarea" >' . $lang['textarea'] . '</option>';
			$display .= '<option value="select" >' . $lang['select'] . '</option>';
			$display .= '<option value="select-multiple">' . $lang['select-multiple'] . '</option>';
			$display .= '<option value="option" >' . $lang['option'] . '</option>';
			$display .= '<option value="checkbox" >' . $lang['checkbox'] . '</option>';
			$display .= '<option value="divider">' . $lang['divider'] . '</option>';
			$display .= '<option value="price">' . $lang['price'] . '</option>';
			$display .= '<option value="url">' . $lang['url'] . '</option>';
			$display .= '<option value="email">' . $lang['email'] . '</option>';
			$display .= '<option value="number">' . $lang['number'] . '</option>';
			$display .= '<option value="decimal">' . $lang['decimal'] . '</option>';
			$display .= '<option value="date">' . $lang['date'] . '</option>';
			$display .= '<option value="lat">' . $lang['lat'] . '</option>';
				$display .= '<option value="long">' . $lang['long'] . '</option>';
			$display .= '</select>';
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_required'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left">';
			$display .= '<select name="required" size="1">';
			$display .= '<option value="' . $required . '" selected="selected">' . $lang[strtolower($required)] . '</option>';
			$display .= '<option value="No">-----</option>';
			$display .= '<option value="No">' . $lang['no'] . '</option>';
			$display .= '<option value="Yes" >' . $lang['yes'] . '</option>';
			$display .= '</select>';
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_caption'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left"><input type=text name="field_caption" value = "' . $field_caption . '"></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_elements'] . ':</b><br /><div class="small">(' . $lang['admin_template_editor_choices_separated'] . ')</div></td>';
			$display .= '<td class="templateEditorHead" align="left"><input type=text name="field_elements" value = "' . $field_elements . '"></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_default_text'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left"><input type=text name="default_text" value = "' . $default_text . '"></td>';
			$display .= '</tr>';
			$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_tool_tip'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left"><textarea name="tool_tip"  cols="80" rows="5">' . $tool_tip . '</textarea></td>';
				$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_rank'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left" ><input type=text name="rank" value = "' . $rank . '"></td>';
			$display .= '</tr>';
			if ($type == 'agent') {
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_display_priv'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left">';
				$display .= '<select name="display_priv" size="1">';
				$display .= '<option value="' . $display_priv . '" selected="selected">' . $lang['display_priv_' . $display_priv] . '</option>';
				$display .= '<option value="0">-----</option>';
				$display .= '<option value="0">' . $lang['display_priv_0'] . '</option>';
				$display .= '<option value="1" >' . $lang['display_priv_1'] . '</option>';
				$display .= '<option value="2" >' . $lang['display_priv_2'] . '</option>';
				$display .= '<option value="3" >' . $lang['display_priv_3'] . '</option>';
				$display .= '</select>';
				$display .= '</td>';
				$display .= '</tr>';
			}
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top">&nbsp;</td>';
			$display .= '<td class="templateEditorHead" align="left"><input type="submit" name="submit" value="' . $lang['update_button'] . '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . $config['baseurl'] . '/admin/index.php?action=edit_user_template&amp;type=' . $type . '&amp;delete_field=' . $listing_field_name . '" onclick="return confirmDelete()">' . $lang['delete'] . '</a></td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</form>';
			return $display;
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	function delete_user_field($type)
	{
		global $conn, $lang, $config;
		if ($type == 'member') {
			$security = login::loginCheck('edit_member_template', true);
		}else {
			$security = login::loginCheck('edit_agent_template', true);
		}
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			if (isset($_GET['delete_field']) && !isset($_POST['lang_change'])) {
				$field_name = $misc->make_db_safe($_GET['delete_field']);
				$sql = 'DELETE FROM ' . $config['table_prefix'] . $type . 'formelements WHERE ' . $type . 'formelements_field_name = ' . $field_name;
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
			}
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	function edit_listing_template()
	{
		global $lang;
		$security = login::loginCheck('edit_listing_template', true);
		$display = '';
		$display1 = '';
		if ($security === true) {
			$display1 .= template_editor::delete_listing_field();
			$display1 .= template_editor::update_listing_field();
			$display .= template_editor::show_listing_navbar();
			$display .= $display1;
			if (isset($_POST['edit_field'])) {
				$display .= template_editor::edit_listing_field($_POST['edit_field']);
			}
		}else {
			$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
		return $display;
	}
	function update_user_field($type)
	{
		// include global variables
		global $conn, $lang, $config;
		if ($type == 'member') {
			$security = login::loginCheck('edit_member_template', true);
		}else {
			$security = login::loginCheck('edit_agent_template', true);
		}
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			if (isset($_POST['update_id']) && !isset($_POST['lang_change'])) {
				$id = $misc->make_db_unsafe($_POST['update_id']);
				$field_type = $misc->make_db_safe($_POST['field_type']);
				$_POST['edit_field'] = str_replace(" ", "_", $_POST['edit_field']);
				$field_name = $misc->make_db_safe($_POST['edit_field']);
				$field_caption = $misc->make_db_safe($_POST['field_caption']);
				$default_text = $misc->make_db_safe($_POST['default_text']);
				$field_elements = $misc->make_db_safe($_POST['field_elements']);
				$rank = $misc->make_db_safe($_POST['rank']);
				$required = $misc->make_db_safe($_POST['required']);
				$tool_tip = $misc->make_db_safe($_POST['tool_tip']);
				if ($type == 'agent') {
					$display_priv = $misc->make_db_safe($_POST['display_priv']);
					$sql = 'UPDATE ' . $config['table_prefix'] . $type . 'formelements SET ' . $type . 'formelements_field_type = ' . $field_type . ', ' . $type . 'formelements_field_name = ' . $field_name . ', ' . $type . 'formelements_field_caption = ' . $field_caption . ', ' . $type . 'formelements_default_text = ' . $default_text . ', ' . $type . 'formelements_field_elements = ' . $field_elements . ', ' . $type . 'formelements_rank = ' . $rank . ', ' . $type . 'formelements_required = ' . $required . ', ' . $type . 'formelements_display_priv  = ' . $display_priv . ', ' . $type . 'formelements_tool_tip  = ' . $tool_tip . ' WHERE ' . $type . 'formelements_id = ' . $id;
				}else {
					$sql = 'UPDATE ' . $config['table_prefix'] . $type . 'formelements SET ' . $type . 'formelements_field_type = ' . $field_type . ', ' . $type . 'formelements_field_name = ' . $field_name . ', ' . $type . 'formelements_field_caption = ' . $field_caption . ', ' . $type . 'formelements_default_text = ' . $default_text . ', ' . $type . 'formelements_field_elements = ' . $field_elements . ', ' . $type . 'formelements_rank = ' . $rank . ', ' . $type . 'formelements_required = ' . $required . ', ' . $type . 'formelements_tool_tip  = ' . $tool_tip . ' WHERE ' . $type . 'formelements_id = ' . $id;
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
			}
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	/**
	 * template_editor :: edit_listing_template_field_order()
	 *
	 * @return
	 */
	function edit_template_field_order($type = 'listings')
	{
		// include global variables
		global $conn, $lang, $config;
		$security = login::loginCheck('edit_listing_template', true);
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$display = '';
			if ($type == 'listings') {
				$display .= template_editor::show_listing_navbar();
			}else {
				$display .= template_editor::show_user_navbar($type);
			}
			// Save any changes.
			if (isset($_POST['field_name'])) {
				$count = 0;
				$num_fields = count($_POST['field_name']);
				while ($count < $num_fields) {
					$field_rank = $misc->make_db_safe($_POST['field_rank'][$count]);
					$field_name = $misc->make_db_safe($_POST['field_name'][$count]);
					$sql = "UPDATE " . $config['table_prefix'] . $type . "formelements SET " . $type . "formelements_rank = $field_rank WHERE " . $type . "formelements_field_name = $field_name";
					$recordSet = $conn->Execute($sql);
					if (!$recordSet) {
						$misc->log_error($sql);
					}
					$count++;
				} // while
				$display .= '<center><strong>' . $lang['admin_template_editor_field_order_set'] . '</strong></center>';
			}
			// Graba  List of field Name and Rank
			$sql = "SELECT " . $type . "formelements_field_name," . $type . "formelements_rank FROM " . $config['table_prefix'] . $type . "formelements ORDER BY " . $type . "formelements_rank";
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}

			if ($recordSet->recordcount() > 0) {
				$display .= '<center><form name="set_field_order" method="post" action ="index.php?action=edit_' . $type . '_template_field_order">';
				$display .= '<table width="450"><tr><td><strong>' . $lang['admin_template_editor_field_name'] . '</strong></td><td><strong>' . $lang['admin_template_editor_field_rank'] . '</strong></td></tr>';
				while (!$recordSet->EOF) {
					$rank = $misc->make_db_unsafe($recordSet->fields[$type . 'formelements_rank']);
					$field_name = $misc->make_db_unsafe($recordSet->fields[$type . 'formelements_field_name']);
					$recordSet->MoveNext();
					$display .= '<tr><td><input type="text" name="field_name[]" value="' . $field_name . '" size="35"></td><td><input type="text" name="field_rank[]" value="' . $rank . '"></td></tr>';
				}
			}

			$display .= '</table><input type="submit" value="' . $lang['admin_template_editor_set_order'] . '" class="edit_field"></form></center>';
			return $display;
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	/**
	 * template_editor :: edit_listing_template_field_order()
	 *
	 * @return
	 */
	function edit_listings_template_field_order()
	{
		// include global variables
		global $conn, $lang, $config;
		$security = login::loginCheck('edit_listing_template', true);
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$display = '';
			$display .= template_editor::show_listing_navbar();
			// Save any changes.
			if (isset($_POST['field_name'])) {
				$count = 0;
				$num_fields = count($_POST['field_name']);
				while ($count < $num_fields) {
					$field_rank = $misc->make_db_safe($_POST['field_rank'][$count]);
					$field_name = $misc->make_db_safe($_POST['field_name'][$count]);
					$sql = "UPDATE " . $config['table_prefix'] . "listingsformelements SET listingsformelements_rank = $field_rank WHERE listingsformelements_field_name = $field_name";
					$recordSet = $conn->Execute($sql);
					if (!$recordSet) {
						$misc->log_error($sql);
					}
					$count++;
				} // while
				$display .= '<center><strong>' . $lang['admin_template_editor_field_order_set'] . '</strong></center>';
			}

			$sections = explode(',', $config['template_listing_sections']);
			$display .= '<center><form name="set_field_order" method="post" action ="index.php?action=edit_listings_template_field_order">';
			foreach($sections as $section) {
				// Graba  List of field Name and Rank
				$sql = "SELECT listingsformelements_field_name,listingsformelements_rank FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_location = '$section' ORDER BY listingsformelements_rank";
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$display .= '<fieldset><legend>' . $section . '</legend>';
				if ($recordSet->recordcount() > 0) {
					$display .= '<table width="450"><tr><td><strong>' . $lang['admin_template_editor_field_name'] . '</strong></td><td><strong>' . $lang['admin_template_editor_field_rank'] . '</strong></td></tr>';
					while (!$recordSet->EOF) {
						$rank = $misc->make_db_unsafe($recordSet->fields['listingsformelements_rank']);
						$field_name = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_name']);
						$recordSet->MoveNext();
						$display .= '<tr><td><input type="text" name="field_name[]" value="' . $field_name . '" size="35"></td><td><input type="text" name="field_rank[]" value="' . $rank . '"></td></tr>';
					}
					$display .= '</table>';
				}
				$display .= '</fieldset>';
			}

			$display .= '<input type="submit" value="' . $lang['admin_template_editor_set_order'] . '" class="edit_field"></form></center>';
			return $display;
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	function edit_listing_template_search()
	{
		// include global variables
		global $conn, $lang, $config;
		$security = login::loginCheck('edit_listing_template', true);
		$display = '';
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$display = '';
			$display .= template_editor::show_listing_navbar();
			if ((isset($_POST['field_name'])) && (!isset($_POST['lang_change']))) {
				$count = 0;
				$num_fields = count($_POST['field_name']);
				while ($count < $num_fields) {
					$field_name = $misc->make_db_safe($_POST['field_name'][$count]);
					$id = $misc->make_db_safe($_POST['id'][$count]);
					if (isset($_POST['searchable_' . $_POST['id'][$count]])) {
						$searchable = $misc->make_db_safe($_POST['searchable_' . $_POST['id'][$count]]);
					}else {
						$searchable = $misc->make_db_safe(0);
					}
					if ($searchable == "'1'" && $_POST['search_type'][$count] == '') {
						$display .= '<span class="error_message">' . $lang['no_search_type'] . '</span><br />';
					}else {
						$search_type = $misc->make_db_safe($_POST['search_type'][$count]);
						$search_label = $misc->make_db_safe($_POST['search_label'][$count]);
						$search_step = $misc->make_db_safe($_POST['search_step'][$count]);
						$search_rank = $misc->make_db_safe($_POST['search_rank'][$count]);
						$sql = "UPDATE " . $config['table_prefix'] . "listingsformelements SET listingsformelements_searchable = $searchable,listingsformelements_search_type = $search_type,listingsformelements_search_step = $search_step, listingsformelements_search_rank = $search_rank WHERE listingsformelements_id = $id";
						$recordSet = $conn->Execute($sql);
						if (!$recordSet) {
							$misc->log_error($sql);
						}
						// Save Search Label in corerect language
						if (!isset($_SESSION["users_lang"])) {
							$lang_table_prefix = $config['table_prefix'];
						}else {
							$lang_table_prefix = $config['lang_table_prefix'];
						}
						$sql = "SELECT listingsformelements_id FROM " . $lang_table_prefix . "listingsformelements WHERE listingsformelements_id = $id";
						$recordSet = $conn->Execute($sql);
						if (!$recordSet) {
							$misc->log_error($sql);
						}
						if ($recordSet->RecordCount() > 0) {
							$sql = "UPDATE " . $lang_table_prefix . "listingsformelements SET listingsformelements_search_label = $search_label WHERE listingsformelements_id = $id";
							$recordSet = $conn->Execute($sql);
							if (!$recordSet) {
								$misc->log_error($sql);
							}
						}else {
							$sql = "INSERT INTO " . $lang_table_prefix . "listingsformelements (listingsformelements_search_label, listingsformelements_id) VALUES ($search_label,$id)";
							$recordSet = $conn->Execute($sql);
							if (!$recordSet) {
								$misc->log_error($sql);
							}
						}
					}
					$count++;
				} // while
				$display .= '<center><strong>' . $lang['admin_template_editor_field_order_set'] . '</strong></center>';
			}
			// Graba  List of field Name and Rank
			$sql = "SELECT listingsformelements_id,listingsformelements_field_name,listingsformelements_searchable,listingsformelements_search_rank,listingsformelements_search_type,listingsformelements_search_label,listingsformelements_search_step FROM " . $config['table_prefix'] . "listingsformelements ORDER BY listingsformelements_searchable DESC,listingsformelements_search_rank";
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}

			if ($recordSet->recordcount() > 0) {
				$display .= '<center><form name="setup_search" method="post" action ="index.php?action=edit_listing_template_search">';
				while (!$recordSet->EOF) {
					$field_name = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_name']);
					$id = $misc->make_db_unsafe($recordSet->fields['listingsformelements_id']);
					$searchable = $misc->make_db_unsafe($recordSet->fields['listingsformelements_searchable']);
					$search_type = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_type']);
					if (!isset($_SESSION['users_lang'])) {
						// Hold empty string for translation fields, as we are workgin with teh default lang
						$default_lang_search_label = '';
						$search_label = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_label']);
					}else {
						$default_lang_search_label = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_label']);
						// Get search label for current language
						$field_id = $recordSet->fields['listingsformelements_id'];
						$lang_sql = "SELECT listingsformelements_search_label FROM " . $config['lang_table_prefix'] . "listingsformelements WHERE listingsformelements_id = $field_id";
						$lang_recordSet = $conn->Execute($lang_sql);
						if (!$lang_recordSet) {
							$misc->log_error($lang_sql);
						}
						$search_label = $misc->make_db_unsafe($lang_recordSet->fields['listingsformelements_search_label']);
					}
					$search_step = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_step']);
					$search_rank = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_rank']);

					$display .= '<table><tr><td>';
					$display .= '<input type="hidden" name="id[]" value="' . $id . '" /><input type="hidden" name="field_name[]" value="' . $field_name . '" /><strong>' . $field_name . '</strong></td>';
					$display .= '<td align="right">' . $lang['allow_searching'] . ' <input type="checkbox" name="searchable_' . $id . '" value="1" ';
					if ($searchable) {
						$display .= 'checked="checked"';
					}
					$display .= '></td>';
					$display .= '<td align="right">' . $lang['admin_template_editor_field_rank_search'] . ' <input type="text" name="search_rank[]" value="' . $search_rank . '" size="2" /></td>';
					$display .= '</tr>';
					$display .= '<tr><td>' . $lang['search_label'] . ' <input name="search_label[]" type="text" value="' . $search_label . '" />';
					if (isset($_SESSION['users_lang'])) {
						// Show Fields value in default language.
						$display .= '<br />' . '<b>' . $lang['translate'] . '</b>' . ': ' . $default_lang_search_label;
					}
					$display .= '</td>';
					$display .= '<td>' . $lang['search_type'] . ' ';
					$display .= '<select name="search_type[]">';
					if ($search_type != '') {
						$display .= '<option value="' . $search_type . '">' . $lang[$search_type . '_description'] . '</option>';
					}
					$display .= '<option></option>';
					$display .= '<option value="ptext">' . $lang['ptext_description'] . '</option>';
					$display .= '<option value="optionlist">' . $lang['optionlist_description'] . '</option>';
					$display .= '<option value="optionlist_or">' . $lang['optionlist_or_description'] . '</option>';
					$display .= '<option value="fcheckbox">' . $lang['fcheckbox_description'] . '</option>';
					$display .= '<option value="fcheckbox_or">' . $lang['fcheckbox_or_description'] . '</option>';
					$display .= '<option value="fpulldown">' . $lang['fpulldown_description'] . '</option>';
					$display .= '<option value="select">' . $lang['select_description'] . '</option>';
					$display .= '<option value="select_or">' . $lang['select_or_description'] . '</option>';
					$display .= '<option value="pulldown">' . $lang['pulldown_description'] . '</option>';
					$display .= '<option value="checkbox">' . $lang['checkbox_description'] . '</option>';
					$display .= '<option value="checkbox_or">' . $lang['checkbox_or_description'] . '</option>';
					$display .= '<option value="option">' . $lang['option_description'] . '</option>';
					$display .= '<option value="minmax">' . $lang['minmax_description'] . '</option>';
					$display .= '<option value="daterange">' . $lang['daterange_description'] . '</option>';
					$display .= '<option value="singledate">' . $lang['singledate_description'] . '</option>';
					$display .= '<option value="null_checkbox">' . $lang['null_checkbox_description'] . '</option>';
					$display .= '<option value="notnull_checkbox">' . $lang['notnull_checkbox_description'] . '</option>';
					$display .= '</select>';
					$display .= '</td>';
					$display .= '<td>' . $lang['step_by'] . ' <input name="search_step[]" type="text" value="' . $search_step . '" /></td></tr>';
					$display .= '</table><hr />';
					$recordSet->MoveNext();
				}
			}
			$display .= '<input type="submit" value="' . $lang['admin_template_editor_save_search_setup'] . '" class="edit_field"></form></center>';
		}else {
			$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
		return $display;
	}
	function edit_listing_template_search_results()
	{
		// include global variables
		global $conn, $lang, $config;
		$security = login::loginCheck('edit_listing_template', true);
		$display = '';
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$display = '';
			$display .= template_editor::show_listing_navbar();
			if ((isset($_POST['field_name'])) && (!isset($_POST['lang_change']))) {
				$count = 0;
				$num_fields = count($_POST['field_name']);
				while ($count < $num_fields) {
					$field_name = $misc->make_db_safe($_POST['field_name'][$count]);
					$id = $misc->make_db_safe($_POST['id'][$count]);

					$display_on_browse = $misc->make_db_unsafe($recordSet->fields['listingsformelements_display_on_browse']);
					$search_result_rank = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_result_rank']);


						$display_on_browse = $misc->make_db_safe($_POST['display_on_browse'][$count]);
						$search_result_rank = $misc->make_db_safe($_POST['search_result_rank'][$count]);
						$sql = "UPDATE " . $config['table_prefix'] . "listingsformelements SET listingsformelements_search_result_rank = $search_result_rank,listingsformelements_display_on_browse = $display_on_browse WHERE listingsformelements_id = $id";
						$recordSet = $conn->Execute($sql);
						if (!$recordSet) {
							$misc->log_error($sql);
						}
					$count++;
				} // while
				$display .= '<center><strong>' . $lang['admin_template_editor_field_order_set'] . '</strong></center>';
			}
			// Graba  List of field Name and Rank
			$sql = "SELECT listingsformelements_id,listingsformelements_field_name,listingsformelements_field_caption,listingsformelements_search_result_rank 	,listingsformelements_display_on_browse FROM " . $config['table_prefix'] . "listingsformelements ORDER BY listingsformelements_search_result_rank ASC";
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}

			if ($recordSet->recordcount() > 0) {
				$display .= '<center><form name="setup_search_results" method="post" action ="index.php?action=edit_listing_template_search_results">';
				while (!$recordSet->EOF) {
					$field_name = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_name']);
					$field_caption = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_caption']);
					$id = $misc->make_db_unsafe($recordSet->fields['listingsformelements_id']);
					$display_on_browse = $misc->make_db_unsafe($recordSet->fields['listingsformelements_display_on_browse']);
					$search_result_rank = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_result_rank']);

					$display .= '<table><tr><td align="left">';
					$display .= '<input type="hidden" name="id[]" value="' . $id . '" /><input type="hidden" name="field_name[]" value="' . $field_name . '" />'. $lang['admin_template_editor_field_name'] . ': <strong>' . $field_name . '</strong></td>';

					$display .= '<td align="right">"<strong>' . $field_caption . '</strong>"</td>';

					$display .= '<tr><td align="left"><strong>' . $lang['admin_template_editor_field_display_browse'] . ':</strong></td>';
					$display .= '<td align="right"><select name="display_on_browse[]" size="1">';
					$display .= '<option value="' . $display_on_browse . '" selected="selected">' . $lang[strtolower($display_on_browse)] . '</option>';
					$display .= '<option value="No">-----</option>';
					$display .= '<option value="No">' . $lang['no'] . '</option>';
					$display .= '<option value="Yes" >' . $lang['yes'] . '</option>';
					$display .= '</select>';
					$display .= '</td></tr>';


			$display .= '<tr><td align="left" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_rank_search_result'] . ': </b></td>';
			$display .= '<td class="templateEditorHead" align="right" ><input type=text name="search_result_rank[]" value = "' . $search_result_rank . '"></td>';

					$display .= '</tr></table><hr />';
					$recordSet->MoveNext();
				}
			}
			$display .= '<input type="submit" value="' . $lang['update_button'] . '" class="edit_field"></form></center>';
		}else {
			$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
		return $display;
	}
	/**
	 * template_editor::show_listing_navbar()
	 *
	 * @return
	 */
	function show_listing_navbar()
	{
		// include global variables
		global $conn, $lang, $config;
		$security = login::loginCheck('edit_listing_template', true);
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$display = '';
			// Grab a list of field_names in the Database to Edit
			$sql = "SELECT listingsformelements_field_name, listingsformelements_field_caption, listingsformelements_id FROM " . $config['table_prefix'] . "listingsformelements";
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			$field_names = '';
			while (!$recordSet->EOF) {
				// Get Caption from users selected language
				if (!isset($_SESSION["users_lang"])) {
					$caption = $recordSet->fields['listingsformelements_field_caption'];
				}else {
					$field_id = $misc->make_db_safe($recordSet->fields['listingsformelements_id']);
					$sql2 = "SELECT listingsformelements_field_caption FROM " . $config['lang_table_prefix'] . "listingsformelements WHERE listingsformelements_id = $field_id";
					$recordSet2 = $conn->Execute($sql2);
					if (!$recordSet2) {
						$misc->log_error($sql2);
					}
					$caption = $recordSet2->fields['listingsformelements_field_caption'];
				}
				if (isset($_POST['edit_field']) && $_POST['edit_field'] == $recordSet->fields['listingsformelements_field_name']) {
					$selected = ' selected="selected" ';
				}else {
					$selected = '';
				}
				$field_names .= '<option value="' . $recordSet->fields['listingsformelements_field_name'] . '" ' . $selected . '>' . $caption . '(' . $recordSet->fields['listingsformelements_field_name'] . ')' . '</option>';

				$recordSet->MoveNext();
			}
			$display .= '<span class="section_header">' . $lang['listing_editor'] . '</span><br /><br />';
			$display .= '<div class="template_editor_navbar">';
			$display .= '<form action="' . $config['baseurl'] . '/admin/index.php?action=edit_listing_template" method="post" id="navbar">';
			$display .= '<div class="template_editor_navbar_item">' . $lang['field'];
			$display .= ' <select name="edit_field" class="edit_field">';
			$display .= $field_names;
			$display .= '</select><input type="submit" value="' . $lang['edit'] . '" class="edit_field">';
			$display .= '</div>';
			$display .= '</form>';
			$display .= '<div class="template_editor_navbar_item"><a href="' . $config['baseurl'] . '/admin/index.php?action=edit_listing_template_add_field">' . $lang['add_field'] . '</a></div>';
			$display .= '<div class="template_editor_navbar_item"><a href="' . $config['baseurl'] . '/admin/index.php?action=edit_listings_template_field_order">' . $lang['set_field_order'] . '</a></div>';
			$display .= '<div class="template_editor_navbar_item"><a href="' . $config['baseurl'] . '/admin/index.php?action=edit_listing_template_search">' . $lang['search_setup'] . '</a></div>';

$display .= '<div class="template_editor_navbar_item"><a href="' . $config['baseurl'] . '/admin/index.php?action=edit_listing_template_search_results">' . $lang['search_results_setup'] . '</a></div>';

			$display .= "</div><br />\r\n";

			return $display;
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	function delete_listing_field()
	{
		global $conn, $lang, $config;
		$security = login::loginCheck('edit_listing_template', true);
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			if (isset($_GET['delete_field']) && !isset($_POST['lang_change'])) {
				$field_name = $misc->make_db_safe($_GET['delete_field']);
				$sql = "SELECT listingsformelements_id FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_field_name = $field_name";
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				// Delete All Translationf for this field.
				$configured_langs = explode(',', $config['configured_langs']);
				while (!$recordSet->EOF) {
					$listingsformelements_id = $recordSet->fields['listingsformelements_id'];
					foreach($configured_langs as $configured_lang) {
						$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsformelements WHERE listingsformelements_id = $listingsformelements_id";
						$recordSet = $conn->Execute($sql);
						if (!$recordSet) {
							$misc->log_error($sql);
						}
					}
					// Remove field from property class.
					$sql = 'DELETE FROM ' . $config['table_prefix_no_lang'] . 'classformelements WHERE listingsformelements_id = ' . $listingsformelements_id;
					$recordSet = $conn->Execute($sql);
					if (!$recordSet) {
						$misc->log_error($sql);
					}
				}
				// Cleanup any listingdbelemts entries from this field.
				foreach($configured_langs as $configured_lang) {
					$sql = "DELETE FROM " . $config['table_prefix_no_lang'] . $configured_lang . "_listingsdbelements WHERE listingsdbelements_field_name = $field_name";
					$recordSet = $conn->Execute($sql);
					if (!$recordSet) {
						$misc->log_error($sql);
					}
				}
			}
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	function update_listing_field()
	{
		// include global variables
		global $conn, $lang, $config;
		$display = '';
		$security = login::loginCheck('edit_listing_template', true);
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			if (isset($_POST['update_id']) && !isset($_POST['lang_change'])) {
				$id = $misc->make_db_unsafe($_POST['update_id']);
				$_POST['old_field_name'] = str_replace(" ", "_", $_POST['old_field_name']);
				$_POST['edit_field'] = str_replace(" ", "_", $_POST['edit_field']);
				$field_name = $misc->make_db_safe($_POST['edit_field']);
				$old_field_name = $misc->make_db_safe($_POST['old_field_name']);
				$required = $misc->make_db_safe($_POST['required']);
				$update_field_name = false;
				if ($old_field_name != $field_name) {
					$update_field_name = true;
				}
				$field_type = $misc->make_db_safe($_POST['field_type']);
				$field_caption = $misc->make_db_safe($_POST['field_caption']);
				$default_text = $misc->make_db_safe($_POST['default_text']);
				$field_elements = $misc->make_db_safe($_POST['field_elements']);
				$rank = $misc->make_db_safe($_POST['rank']);
				$search_rank = $misc->make_db_safe($_POST['search_rank']);
				$search_result_rank = $misc->make_db_safe($_POST['search_result_rank']);
				$location = $misc->make_db_safe($_POST['location']);
				$display_on_browse = $misc->make_db_safe($_POST['display_on_browse']);
				$display_priv = $misc->make_db_safe($_POST['display_priv']);
				$search_step = $misc->make_db_safe($_POST['search_step']);
				$field_length = $misc->make_db_safe($_POST['field_length']);
				$tool_tip = $misc->make_db_safe($_POST['tool_tip']);
				if (isset($_POST['searchable'])) {
					$searchable = $misc->make_db_safe($_POST['searchable']);
				}else {
					$searchable = $misc->make_db_safe(0);
				}
				if ($searchable == "'1'" && $_POST['search_type'] == '') {
					$display .= '<span class="error_message">' . $lang['no_search_type'] . '</span><br />';
				}elseif (count($_POST['property_class']) == 0) {
					$display .= '<span class="error_message">' . $lang['no_property_class_selected'] . '</span><br />';
				}else {
					$search_label = $misc->make_db_safe($_POST['search_label']);
					$search_type = $misc->make_db_safe($_POST['search_type']);
					$sql = "UPDATE " . $config['table_prefix'] . "listingsformelements SET listingsformelements_field_type = $field_type, listingsformelements_field_name = $field_name, listingsformelements_rank = $rank, listingsformelements_search_rank = $search_rank, listingsformelements_search_result_rank = $search_result_rank, listingsformelements_required = $required, listingsformelements_location = $location, listingsformelements_display_on_browse = $display_on_browse, listingsformelements_search_step = $search_step, listingsformelements_searchable = $searchable, listingsformelements_search_type = $search_type, listingsformelements_display_priv = $display_priv, listingsformelements_field_length = $field_length, listingsformelements_tool_tip = $tool_tip WHERE listingsformelements_id = $id";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
					// Update Current language
					if (!isset($_SESSION["users_lang"])) {
						$lang_sql = "UPDATE  " . $config['table_prefix'] . "listingsformelements SET listingsformelements_field_caption = $field_caption, listingsformelements_default_text = $default_text,listingsformelements_field_elements = $field_elements,listingsformelements_search_label = $search_label  WHERE listingsformelements_id = $id";
						$lang_recordSet = $conn->Execute($lang_sql);
						if (!$lang_recordSet) {
							$misc->log_error($lang_sql);
						}
					}else {
						$lang_sql = "DELETE FROM  " . $config['lang_table_prefix'] . "listingsformelements WHERE listingsformelements_id = $id";
						$lang_recordSet = $conn->Execute($lang_sql);
						if (!$lang_recordSet) {
							$misc->log_error($lang_sql);
						}
						$lang_sql = "INSERT INTO " . $config['lang_table_prefix'] . "listingsformelements (listingsformelements_id, listingsformelements_field_caption,listingsformelements_default_text,listingsformelements_field_elements,listingsformelements_search_label) VALUES ($id, $field_caption,$default_text,$field_elements,$search_label)";
						$lang_recordSet = $conn->Execute($lang_sql);
						if (!$lang_recordSet) {
							$misc->log_error($lang_sql);
						}
					}
					// Check if field name changed, if it as update all listingsdbelement tables
					if ($update_field_name) {
						$lang_sql = "UPDATE  " . $config['table_prefix'] . "listingsdbelements SET listingsdbelements_field_name = $field_name  WHERE listingsdbelements_field_name = $old_field_name";
						$lang_recordSet = $conn->Execute($lang_sql);
						if (!$lang_recordSet) {
							$misc->log_error($lang_sql);
						}
					}
					// Delete from classform elements.
					$sql = 'DELETE FROM ' . $config['table_prefix_no_lang'] . 'classformelements WHERE listingsformelements_id = ' . $id;
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
					// Insert new selections into class formelements
					$class_sql = '';
					foreach($_POST['property_class'] as $class_id) {
						$sql = 'INSERT INTO ' . $config['table_prefix_no_lang'] . 'classformelements (listingsformelements_id,class_id) VALUES (' . $id . ',' . $class_id . ')';
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						if (!empty($class_sql)) {
							$class_sql .= ' OR class_id = ' . $class_id;
						}else {
							$class_sql .= ' class_id = ' . $class_id;
						}
					}
					// Remove fields from any listings that are not in this class.
					$pclass_list = '';
					$sql = 'SELECT DISTINCT(listingsdb_id) FROM ' . $config['table_prefix_no_lang'] . 'classlistingsdb WHERE ' . $class_sql;
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					} while (!$recordSet->EOF) {
						if (empty($pclass_list)) {
							$pclass_list .= $recordSet->fields['listingsdb_id'];
						}else {
							$pclass_list .= ',' . $recordSet->fields['listingsdb_id'];
						}
						$recordSet->Movenext();
					}
					if ($pclass_list == '') {
						$pclass_list = 0;
					}
					$sql = 'DELETE FROM ' . $config['table_prefix'] . 'listingsdbelements WHERE listingsdbelements_field_name = ' . $field_name . ' AND listingsdb_id NOT IN (' . $pclass_list . ')';
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}

					$display .= '<center>' . $lang['field_has_been_updated'] . '</center><br />';
				}
			}
		}else {
			$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
		return $display;
	}
	function insert_listing_field()
	{
		// include global variables
		global $conn, $lang, $config;
		$security = login::loginCheck('edit_listing_template', true);
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			if (isset($_POST['edit_field']) && !isset($_POST['lang_change'])) {
				if(empty($_POST['property_class'])) {
					$display = $lang['no_pclass_selected'];
					return $display;
				}
				$field_type = $misc->make_db_safe($_POST['field_type']);
				$_POST['edit_field'] = str_replace(" ", "_", $_POST['edit_field']);
				$field_name = $misc->make_db_safe($_POST['edit_field']);
				$field_caption = $misc->make_db_safe($_POST['field_caption']);
				$default_text = $misc->make_db_safe($_POST['default_text']);
				$field_elements = $misc->make_db_safe($_POST['field_elements']);
				$rank = $misc->make_db_safe($_POST['rank']);
				$search_rank = $misc->make_db_safe($_POST['search_rank']);
				$search_result_rank = $misc->make_db_safe($_POST['search_result_rank']);
				$required = $misc->make_db_safe($_POST['required']);
				$location = $misc->make_db_safe($_POST['location']);
				$display_on_browse = $misc->make_db_safe($_POST['display_on_browse']);
				$search_step = $misc->make_db_safe($_POST['search_step']);
				$display_priv = $misc->make_db_safe($_POST['display_priv']);
				//Handle Post without field lengths or tool tips for backwards compatibility.
				if(!isset($_POST['field_length'])){
					$_POST['field_length']='';
				}
				if(!isset($_POST['tool_tip'])){
					$_POST['tool_tip']='';
				}
				$field_length = $misc->make_db_safe($_POST['field_length']);
				$tool_tip = $misc->make_db_safe($_POST['tool_tip']);
				if (isset($_POST['searchable'])) {
					$searchable = $misc->make_db_safe($_POST['searchable']);
				}else {
					$searchable = 0;
				}
				$search_label = $misc->make_db_safe($_POST['search_label']);
				$search_type = $misc->make_db_safe($_POST['search_type']);
				$id_rand = rand(0, 999999);

				$sql = "INSERT INTO " . $config['table_prefix'] . "listingsformelements (listingsformelements_field_type, listingsformelements_field_name, listingsformelements_field_caption, listingsformelements_default_text, listingsformelements_field_elements, listingsformelements_rank, listingsformelements_search_rank, listingsformelements_search_result_rank, listingsformelements_required, listingsformelements_location, listingsformelements_display_on_browse, listingsformelements_search_step, listingsformelements_searchable, listingsformelements_search_label, listingsformelements_search_type,listingsformelements_display_priv, listingsformelements_field_length, listingsformelements_tool_tip) VALUES ($field_type,$id_rand,$field_caption,$default_text,$field_elements,$rank,$search_rank,$search_result_rank,$required,$location,$display_on_browse,$search_step,$searchable,$search_label,$search_type,$display_priv, $field_length, $tool_tip)";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				// Now we need to get the field ID
				$sql = 'SELECT listingsformelements_id FROM ' . $config['table_prefix'] . 'listingsformelements WHERE listingsformelements_field_name = ' . $id_rand;
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$listingsformelements_id = $recordSet->fields['listingsformelements_id'];
				// Set Real Name
				$sql = 'UPDATE ' . $config['table_prefix'] . 'listingsformelements SET listingsformelements_field_name = ' . $field_name . ' WHERE listingsformelements_field_name = ' . $id_rand;
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				// We should now add a blank field for each listing that already exist.
				$sql = 'SELECT listingsdb_id, userdb_id FROM ' . $config['table_prefix'] . 'listingsdb';
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$id = array();
				$user = array();
				while (!$recordSet->EOF) {
					$id[] = $recordSet->fields['listingsdb_id'];
					$user[] = $recordSet->fields['userdb_id'];
					$recordSet->MoveNext();
				} // while
				$count = count($id);
				$x = 0;
				while ($x < $count) {
					$sql = "INSERT INTO " . $config['table_prefix'] . "listingsdbelements (listingsdbelements_field_name, listingsdb_id,userdb_id,listingsdbelements_field_value) VALUES ($field_name,$id[$x],$user[$x],'')";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
					$x++;
				}
				// Add Listing Field to property class
				foreach($_POST['property_class'] as $class_id) {
					$sql = 'INSERT INTO ' . $config['table_prefix_no_lang'] . 'classformelements (class_id,listingsformelements_id) VALUES (' . $class_id . ',' . $listingsformelements_id . ')';
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					}
				}

				return $lang['admin_template_editor_field_added'];
			}
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	function add_listing_template_field()
	{
		// include global variables
		global $conn, $lang, $config;
		$security = login::loginCheck('edit_listing_template', true);
		if ($security === true) {
			$display = '';
			$display .= template_editor::show_listing_navbar();
			if (!isset($_POST['edit_field']) && !isset($_POST['lang_change'])) {
				$display .= '<br /><form action="' . $config['baseurl'] . '/admin/index.php?action=edit_listing_template_add_field" method="post"  id="update_field" onSubmit="len = document.getElementById(\'propclass\').length;i = 0;for (i = 0; i < len; i++) { if (document.getElementById(\'propclass\')[i].selected) { return true;}}	alert(\''.$lang['no_pclass_selected'].'\');return false;">';
				$display .= '<table align="center">';
				$display .= '<tr>';
				$display .= '<td colspan="2" align="center" class="templateEditorNew" valign="top"><hr><B>' . $lang['general_options'] . '</b></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_name'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left"><input type="text" name="edit_field" value=""></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_type'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left">';
				$display .= '<select name="field_type" size="1">';
				$display .= '<option value="text" selected="selected">' . $lang['text'] . '</option>';
				$display .= '<option value="textarea" >' . $lang['textarea'] . '</option>';
				$display .= '<option value="select" >' . $lang['select'] . '</option>';
				$display .= '<option value="select-multiple">' . $lang['select-multiple'] . '</option>';
				$display .= '<option value="option" >' . $lang['option'] . '</option>';
				$display .= '<option value="checkbox" >' . $lang['checkbox'] . '</option>';
				$display .= '<option value="divider">' . $lang['divider'] . '</option>';
				$display .= '<option value="price">' . $lang['price'] . '</option>';
				$display .= '<option value="url">' . $lang['url'] . '</option>';
				$display .= '<option value="email">' . $lang['email'] . '</option>';
				$display .= '<option value="number">' . $lang['number'] . '</option>';
				$display .= '<option value="decimal">' . $lang['decimal'] . '</option>';
				$display .= '<option value="date">' . $lang['date'] . '</option>';
				$display .= '<option value="lat">' . $lang['lat'] . '</option>';
				$display .= '<option value="long">' . $lang['long'] . '</option>';
				$display .= '</select>';
				$display .= '</td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_required'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left">';
				$display .= '<select name="required" size="1">';
				$display .= '<option value="No" selected="selected">' . $lang['no'] . '</option>';
				$display .= '<option value="Yes" >' . $lang['yes'] . '</option>';
				$display .= '</select>';
				$display .= '</td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_caption'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left"><input type=text name="field_caption" value=""></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_elements'] . ':</b><br /><div class="small">(' . $lang['admin_template_editor_choices_separated'] . ')</div></td>';
				$display .= '<td class="templateEditorHead" align="left"><textarea name="field_elements"  cols="80" rows="5"></textarea></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_default_text'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left"><input type=text name="default_text" value = ""></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_length'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left"><input type=text name="field_length" value = ""></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_tool_tip'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left"><textarea name="tool_tip"  cols="80" rows="5"></textarea></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_display_priv'] . ':</b></td>';				
				$display .= '<td class="templateEditorHead" align="left">';
				$display .= '<select name="display_priv" size="1">';
				$display .= '<option value="0" selected="selected">' . $lang['display_priv_0'] . '</option>';
				$display .= '<option value="1" >' . $lang['display_priv_1'] . '</option>';
				$display .= '<option value="2" >' . $lang['display_priv_2'] . '</option>';
				$display .= '<option value="3" >' . $lang['display_priv_3'] . '</option>';
				$display .= '</select>';
				$display .= '</td>';
				$display .= '</tr>';
				// Property Class Selection
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_property_class'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left">';
				$display .= '<select name="property_class[]" id="propclass" multiple="multiple" size="5">';
				// get list of all property clases
				$sql = 'SELECT class_name, class_id FROM ' . $config['table_prefix'] . 'class ORDER BY class_rank';
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				} while (!$recordSet->EOF()) {
					$class_id = $recordSet->fields['class_id'];
					$class_name = $recordSet->fields['class_name'];
					$display .= '<option value="' . $class_id . '" >' . $class_name . '</option>';
					$recordSet->MoveNext();
				}
				$display .= '</select>';
				$display .= '</td>';
				$display .= '</tr>';
				// Listing Page Options
				$display .= '<tr>';
				$display .= '<td colspan="2" align="center" class="templateEditorNew" valign="top"><hr><B>' . $lang['listing_page_options'] . '</b></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_display_location'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left">';
				$display .= '<select name="location" size="1">';
				$display .= '<option value="" selected="selected"></option>';
				$display .= '<option value="">-- '.$lang['do_not_display'].' --</option>';
				$sections = explode(',', $config['template_listing_sections']);
				foreach($sections as $section) {
					$display .= '<option value="' . $section . '">' . $section . '</option>';
				}
				$display .= '</select>';
				$display .= '</td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_rank'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left" ><input type=text name="rank" value="0"></td>';
				$display .= '</tr>';
				// Search Page Options
				$display .= '<tr>';
				$display .= '<td colspan="2" align="center" class="templateEditorNew" valign="top"><hr><B>' . $lang['search_options'] . '</b></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorNew" valign="top"><b>' . $lang['allow_searching'] . '</b></td>';
				$display .= '<td class="templateEditorNew"><input type="checkbox" name="searchable" value = "1" ></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_rank_search'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left" ><input type=text name="search_rank" value="0"></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorNew" valign="top"><b>' . $lang['search_label'] . '</b></td>';
				$display .= '<td class="templateEditorNew"><input type="text" name="search_label" value=""></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorNew" valign="top"><b>' . $lang['search_type'] . '</b></td>';
				$display .= '<td class="templateEditorNew">';
				$display .= '<select name="search_type">';
				$display .= '<option></option>';
				$display .= '<option value="ptext">' . $lang['ptext_description'] . '</option>';
				$display .= '<option value="optionlist">' . $lang['optionlist_description'] . '</option>';
				$display .= '<option value="optionlist_or">' . $lang['optionlist_or_description'] . '</option>';
				$display .= '<option value="fcheckbox">' . $lang['fcheckbox_description'] . '</option>';
				$display .= '<option value="fcheckbox_or">' . $lang['fcheckbox_or_description'] . '</option>';
				$display .= '<option value="fpulldown">' . $lang['fpulldown_description'] . '</option>';
				$display .= '<option value="select">' . $lang['select_description'] . '</option>';
				$display .= '<option value="select_or">' . $lang['select_or_description'] . '</option>';
				$display .= '<option value="pulldown">' . $lang['pulldown_description'] . '</option>';
				$display .= '<option value="checkbox">' . $lang['checkbox_description'] . '</option>';
				$display .= '<option value="checkbox_or">' . $lang['checkbox_or_description'] . '</option>';
				$display .= '<option value="option">' . $lang['option_description'] . '</option>';
				$display .= '<option value="minmax">' . $lang['minmax_description'] . '</option>';
				$display .= '<option value="daterange">' . $lang['daterange_description'] . '</option>';
				$display .= '<option value="singledate">' . $lang['singledate_description'] . '</option>';
				$display .= '<option value="null_checkbox">' . $lang['null_checkbox_description'] . '</option>';
				$display .= '<option value="notnull_checkbox">' . $lang['notnull_checkbox_description'] . '</option>';
				$display .= '</select>';
				$display .= '</td>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorNew" valign="top"><font size="1">++ </font><b>' . $lang['step_by'] . '</b></td>';
				$display .= '<td class="templateEditorNew"><input type="text" name="search_step" value = "0">';
				$display .= '<br /><font size="1">' . $lang['used_for_range_selections_only'] . '</font>';
				$display .= '</td>';
				$display .= '</tr>';
				// Search Result Options
				$display .= '<tr>';
				$display .= '<td colspan="2" align="center" class="templateEditorNew" valign="top"><hr><B>' . $lang['search_result_options'] . '</b></td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_display_browse'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left">';
				$display .= '<select name="display_on_browse" size="1">';
				$display .= '<option value="No" selected="selected">' . $lang['no'] . '</option>';
				$display .= '<option value="Yes" >' . $lang['yes'] . '</option>';
				$display .= '</select>';
				$display .= '</td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_rank_search_result'] . ':</b></td>';
				$display .= '<td class="templateEditorHead" align="left" ><input type=text name="search_result_rank" value="0"></td>';
				$display .= '</tr>';
				// Save Delete
				$display .= '<tr>';
				$display .= '<td align="right" class="templateEditorHead" valign="top">&nbsp;</td>';
				$display .= '<td class="templateEditorHead" align="left"><input type="submit" name="submit_field" value="' . $lang['add_field'] . '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
				$display .= '</tr>';
				$display .= '</table>';
				$display .= '</form>';
			}else {
				$status = template_editor::insert_listing_field();
				$display .= $status;
				if ($status == $lang['admin_template_editor_field_added']) {
					$display .= template_editor::edit_listing_field($_POST['edit_field']);
				}
			}
			return $display;
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
	function edit_listing_field($edit_listing_field_name)
	{
		// include global variables
		global $conn, $lang, $config;
		$security = login::loginCheck('edit_listing_template', true);
		if ($security === true) {
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$edit_listing_field_name = $misc->make_db_safe($edit_listing_field_name);
			$sql = "SELECT * FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_field_name = $edit_listing_field_name";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$id = $misc->make_db_unsafe($recordSet->fields['listingsformelements_id']);
			$field_type = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_type']);
			$field_name = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_name']);
			// Multi Lingual Support
			if (!isset($_SESSION["users_lang"])) {
				// Hold empty string for translation fields, as we are workgin with teh default lang
				$default_lang_field_caption = '';
				$default_lang_default_text = '';
				$default_lang_field_elements = '';
				$default_lang_search_label = '';

				$field_caption = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_caption']);
				$default_text = $misc->make_db_unsafe($recordSet->fields['listingsformelements_default_text']);
				$field_elements = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_elements']);
				$search_label = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_label']);
			}else {
				// Store default lang to show for tanslator
				$default_lang_field_caption = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_caption']);
				$default_lang_default_text = $misc->make_db_unsafe($recordSet->fields['listingsformelements_default_text']);
				$default_lang_field_elements = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_elements']);
				$default_lang_search_label = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_label']);
				$default_lang_tool_tip = $misc->make_db_unsafe($recordSet->fields['listingsformelements_tool_tip']);
				$field_id = $recordSet->fields['listingsformelements_id'];
				$lang_sql = "SELECT listingsformelements_field_caption,listingsformelements_default_text,listingsformelements_field_elements,listingsformelements_search_label FROM " . $config['lang_table_prefix'] . "listingsformelements WHERE listingsformelements_id = $field_id";
				$lang_recordSet = $conn->Execute($lang_sql);
				if (!$lang_recordSet) {
					$misc->log_error($lang_sql);
				}
				$field_caption = $misc->make_db_unsafe($lang_recordSet->fields['listingsformelements_field_caption']);
				$default_text = $misc->make_db_unsafe($lang_recordSet->fields['listingsformelements_default_text']);
				$field_elements = $misc->make_db_unsafe($lang_recordSet->fields['listingsformelements_field_elements']);
				$search_label = $misc->make_db_unsafe($lang_recordSet->fields['listingsformelements_search_label']);
			}

			$rank = $misc->make_db_unsafe($recordSet->fields['listingsformelements_rank']);
			$search_rank = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_rank']);
			$search_result_rank = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_result_rank']);
			$required = $misc->make_db_unsafe($recordSet->fields['listingsformelements_required']);
			$location = $misc->make_db_unsafe($recordSet->fields['listingsformelements_location']);
			$display_on_browse = $misc->make_db_unsafe($recordSet->fields['listingsformelements_display_on_browse']);
			$display_priv = $misc->make_db_unsafe($recordSet->fields['listingsformelements_display_priv']);
			$search_step = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_step']);
			$searchable = $misc->make_db_unsafe($recordSet->fields['listingsformelements_searchable']);
			$search_type = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_type']);
			$field_length = $misc->make_db_unsafe($recordSet->fields['listingsformelements_field_length']);
			$tool_tip = $misc->make_db_unsafe($recordSet->fields['listingsformelements_tool_tip']);
			$display = '';
			$display .= '<br /><form action="' . $config['baseurl'] . '/admin/index.php?action=edit_listing_template" method="post"  id="update_field">';
			$display .= '<table align="center">';
			$display .= '<tr>';
			$display .= '<td colspan="2" align="center" class="templateEditorNew" valign="top"><hr><B>' . $lang['general_options'] . '</b></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_name'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left"><input type="hidden" name="update_id" value="' . $id . '"><input type="hidden" name="old_field_name" value="' . $field_name . '"><input type="text" name="edit_field" value="' . $field_name . '"></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_type'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left">';
			$display .= '<select name="field_type" size="1">';
			$display .= '<option value="' . $field_type . '" selected="selected">' . $lang[$field_type] . '</option>';
			$display .= '<option value="">-----</option>';
			$display .= '<option value="text">' . $lang['text'] . '</option>';
			$display .= '<option value="textarea" >' . $lang['textarea'] . '</option>';
			$display .= '<option value="select" >' . $lang['select'] . '</option>';
			$display .= '<option value="select-multiple">' . $lang['select-multiple'] . '</option>';
			$display .= '<option value="option" >' . $lang['option'] . '</option>';
			$display .= '<option value="checkbox" >' . $lang['checkbox'] . '</option>';
			$display .= '<option value="divider">' . $lang['divider'] . '</option>';
			$display .= '<option value="price">' . $lang['price'] . '</option>';
			$display .= '<option value="url">' . $lang['url'] . '</option>';
			$display .= '<option value="email">' . $lang['email'] . '</option>';
			$display .= '<option value="number">' . $lang['number'] . '</option>';
			$display .= '<option value="decimal">' . $lang['decimal'] . '</option>';
			$display .= '<option value="date">' . $lang['date'] . '</option>';
			$display .= '<option value="lat">' . $lang['lat'] . '</option>';
				$display .= '<option value="long">' . $lang['long'] . '</option>';
			$display .= '</select>';
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_required'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left">';
			$display .= '<select name="required" size="1">';
			$display .= '<option value="' . $required . '" selected="selected">' . $lang[strtolower($required)] . '</option>';
			$display .= '<option value="No">-----</option>';
			$display .= '<option value="No">' . $lang['no'] . '</option>';
			$display .= '<option value="Yes" >' . $lang['yes'] . '</option>';
			$display .= '</select>';
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_caption'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left"><input type=text name="field_caption" value = "' . $field_caption . '">';
			if (isset($_SESSION["users_lang"])) {
				// Show Fields value in default language.
				$display .= '<b>' . $lang['translate'] . '</b>' . ': ' . $default_lang_field_caption;
			}
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_elements'] . ':</b><br /><div class="small">(' . $lang['admin_template_editor_choices_separated'] . ')</div></td>';
			$display .= '<td class="templateEditorHead" align="left"><textarea name="field_elements" cols="80" rows="5">' . $field_elements . '</textarea>';
			if (isset($_SESSION["users_lang"])) {
				// Show Fields value in default language.
				$display .= '<br />' . '<b>' . $lang['translate'] . '</b>' . ': ' . $default_lang_field_elements;
			}
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_default_text'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left"><input type=text name="default_text" value = "' . $default_text . '">';
			if (isset($_SESSION["users_lang"])) {
				// Show Fields value in default language.
				$display .= '<b>' . $lang['translate'] . '</b>' . ': ' . $default_lang_default_text;
			}
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_tool_tip'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left"><textarea name="tool_tip" cols="80" rows="5">' . $tool_tip . '</textarea>';
			if (isset($_SESSION["users_lang"])) {
				// Show Fields value in default language.
				$display .= '<br />' . '<b>' . $lang['translate'] . '</b>' . ': ' . $default_lang_tool_tip;
			}
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_length'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left"><input type=text name="field_length" value = "' . $field_length . '"></td>';
			$display .= '</tr>';			
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_display_priv'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left">';
			$display .= '<select name="display_priv" size="1">';
			$display .= '<option value="' . $display_priv . '" selected="selected">' . $lang['display_priv_' . $display_priv] . '</option>';
			$display .= '<option value="0">-----</option>';
			$display .= '<option value="0">' . $lang['display_priv_0'] . '</option>';
			$display .= '<option value="1" >' . $lang['display_priv_1'] . '</option>';
			$display .= '<option value="2" >' . $lang['display_priv_2'] . '</option>';
			$display .= '<option value="3" >' . $lang['display_priv_3'] . '</option>';
			$display .= '</select>';
			$display .= '</td>';
			$display .= '</tr>';
			// Property Class Selection
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_property_class'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left">';
			$display .= '<select name="property_class[]" multiple="multiple" size="5">';
			// get list of all property clases
			$sql = 'SELECT class_name, class_id FROM ' . $config['table_prefix'] . 'class ORDER BY class_rank';
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			} while (!$recordSet->EOF()) {
				$class_id = $recordSet->fields['class_id'];
				$class_name = $recordSet->fields['class_name'];
				// check if this field is part of this class
				$sql = 'SELECT count(class_id) as exist FROM ' . $config['table_prefix_no_lang'] . 'classformelements WHERE listingsformelements_id = ' . $id . ' AND class_id =' . $class_id;
				$recordSet2 = $conn->Execute($sql);
				if (!$recordSet2) {
					$misc->log_error($sql);
				}
				$select = $recordSet2->fields['exist'];
				if ($select > 0) {
					$display .= '<option value="' . $class_id . '" selected="selected">' . $class_name . '</option>';
				}else {
					$display .= '<option value="' . $class_id . '" >' . $class_name . '</option>';
				}

				$recordSet->MoveNext();
			}
			$display .= '</select>';
			$display .= '</td>';
			$display .= '</tr>';
			// LISTING PAGE OPTIONS
			$display .= '<tr>';
			$display .= '<td colspan="2" align="center" class="templateEditorNew" valign="top"><hr><B>' . $lang['listing_page_options'] . '</b></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_display_location'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left">';
			$display .= '<select name="location" size="1">';
			$display .= '<option value="' . $location . '" selected="selected">' . $location . '</option>';
			$display .= '<option value="">-- '.$lang['do_not_display'].' --</option>';
			$sections = explode(',', $config['template_listing_sections']);
			foreach($sections as $section) {
				$display .= '<option value="' . $section . '">' . $section . '</option>';
			}
			$display .= '</select>';
			$display .= '</td>';
			$display .= '</tr>';

			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_rank'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left" ><input type=text name="rank" value = "' . $rank . '"></td>';
			$display .= '</tr>';
			// Search Page Options
			$display .= '<tr>';
			$display .= '<td colspan="2" align="center" class="templateEditorNew" valign="top"><hr><B>' . $lang['search_options'] . '</b></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorNew" valign="top"><b>' . $lang['allow_searching'] . '</b></td>';
			$display .= '<td class="templateEditorNew"><input type="checkbox" name="searchable" value="1" ';
			if ($searchable) {
				$display .= 'checked="checked"';
			}
			$display .= '></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_rank_search'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left" ><input type=text name="search_rank" value = "' . $search_rank . '"></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorNew" valign="top"><b>' . $lang['search_label'] . '</b></td>';
			$display .= '<td class="templateEditorNew"><input type="text" name="search_label" value="' . htmlspecialchars($search_label, ENT_COMPAT, $config['charset']) . '">';
			if (isset($_SESSION["users_lang"])) {
				// Show Fields value in default language.
				$display .= '<b>' . $lang['translate'] . '</b>' . ': ' . $default_lang_search_label;
			}
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorNew" valign="top"><b>' . $lang['search_type'] . '</b></td>';
			$display .= '<td class="templateEditorNew">';
			$display .= '<select name="search_type">';
			if ($search_type != '') {
				$display .= '<option value="' . $search_type . '">' . $lang[$search_type . '_description'] . '</option>';
			}
			$display .= '<option></option>';
			$display .= '<option value="ptext">' . $lang['ptext_description'] . '</option>';
			$display .= '<option value="optionlist">' . $lang['optionlist_description'] . '</option>';
			$display .= '<option value="optionlist_or">' . $lang['optionlist_or_description'] . '</option>';
			$display .= '<option value="fcheckbox">' . $lang['fcheckbox_description'] . '</option>';
			$display .= '<option value="fcheckbox_or">' . $lang['fcheckbox_or_description'] . '</option>';
			$display .= '<option value="fpulldown">' . $lang['fpulldown_description'] . '</option>';
			$display .= '<option value="select">' . $lang['select_description'] . '</option>';
			$display .= '<option value="select_or">' . $lang['select_or_description'] . '</option>';
			$display .= '<option value="pulldown">' . $lang['pulldown_description'] . '</option>';
			$display .= '<option value="checkbox">' . $lang['checkbox_description'] . '</option>';
			$display .= '<option value="checkbox_or">' . $lang['checkbox_or_description'] . '</option>';
			$display .= '<option value="option">' . $lang['option_description'] . '</option>';
			$display .= '<option value="minmax">' . $lang['minmax_description'] . '</option>';
			$display .= '<option value="daterange">' . $lang['daterange_description'] . '</option>';
			$display .= '<option value="singledate">' . $lang['singledate_description'] . '</option>';
			$display .= '<option value="null_checkbox">' . $lang['null_checkbox_description'] . '</option>';
			$display .= '<option value="notnull_checkbox">' . $lang['notnull_checkbox_description'] . '</option>';
			$display .= '</select>';
			$display .= '</td>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorNew" valign="top"><font size="1">++ </font><b>' . $lang['step_by'] . '</b></td>';
			$display .= '<td class="templateEditorNew"><input type="text" name="search_step" value = "' . $search_step . '">';
			$display .= '<br /><font size="1">' . $lang['used_for_range_selections_only'] . '</font>';
			$display .= '</td>';
			$display .= '</tr>';
			// SEARCH RESULT OPTIONS
			$display .= '<tr>';
			$display .= '<td colspan="2" align="center" class="templateEditorNew" valign="top"><hr><B>' . $lang['search_result_options'] . '</b></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_display_browse'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left">';
			$display .= '<select name="display_on_browse" size="1">';
			$display .= '<option value="' . $display_on_browse . '" selected="selected">' . $lang[strtolower($display_on_browse)] . '</option>';
			$display .= '<option value="No">-----</option>';
			$display .= '<option value="No">' . $lang['no'] . '</option>';
			$display .= '<option value="Yes" >' . $lang['yes'] . '</option>';
			$display .= '</select>';
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top"><b>' . $lang['admin_template_editor_field_rank_search_result'] . ':</b></td>';
			$display .= '<td class="templateEditorHead" align="left" ><input type=text name="search_result_rank" value = "' . $search_result_rank . '"></td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td align="right" class="templateEditorHead" valign="top">&nbsp;</td>';
			$display .= '<td class="templateEditorHead" align="left"><input type="submit" name="field_submit" value="' . $lang['update_button'] . '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . $config['baseurl'] . '/admin/index.php?action=edit_listing_template&amp;delete_field=' . $field_name . '" onclick="return confirmDelete()">' . $lang['delete'] . '</a></td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</form>';
			return $display;
		}else {
			return '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
	}
}

?>