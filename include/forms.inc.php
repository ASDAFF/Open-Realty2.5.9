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
class forms {
	/**
	 * ****************************************************************************\
	 * renderExistingFormElement($field_type, $field_name, $field_value,			  *
	 * 					$field_caption, $default_text, $required, $field_elements)*
	 * \****************************************************************************
	 */
	function renderExistingFormElement($field_type, $field_name, $field_value, $field_caption, $default_text, $required, $field_elements, $field_length='', $tool_tip='')
	{
		// handles the rendering of already filled in user forms
		global $lang, $config;
		$field_value_raw=$field_value;
		$field_value=htmlentities($field_value, ENT_COMPAT, $config['charset']);

		$display = '';
		$display .= "<tr>";
		switch ($field_type) {

			case "lat":
			case "long":
			case "text": // handles text input boxes
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td><td align=\"left\" class=\"row_main\"><input type=\"text\" name=\"$field_name\" value=\"$field_value\" style=\"width:100px;\" ";
				if ($field_length != '' && $field_length != 0)
				{
					$display .= 'maxlength="'.$field_length.'" ';
				}
				$display .= " />";
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16"  /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "date":
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				if ($field_value != '')
				{
					if ($config['date_format']==1) {
						$format="m/d/Y";
					}
					elseif ($config['date_format']==2) {
						$format="Y/d/m";
					}
					elseif ($config['date_format']==3) {
						$format="d/m/Y";
					}
					$field_value=date($format,"$field_value");
				}
				$display .= "</strong></td><td align=\"left\" class=\"row_main\"><input type=\"text\" name=\"$field_name\" value=\"$field_value\" style=\"width:100px;\" ";

				$display .= 'onFocus="javascript:vDateType=\'' . $config['date_format'] . '\'" onKeyUp="DateFormat(this,this.value,event,false,\'' . $config['date_format'] . '\')" onBlur="DateFormat(this,this.value,event,true,\'' . $config['date_format'] . '\')" />(' . $config['date_format_long'] . ')';
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';

				break;
			case "textarea": // handles textarea input
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td><td align=\"left\" class=\"row_main\"><textarea name=\"$field_name\" cols=\"40\" rows=\"6\">$field_value</textarea>";
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "select": // handles single item select boxes
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td><td align=\"left\" class=\"row_main\"><select name=\"$field_name\" size=\"1\">";
				$index_list = explode("||", $field_elements);
				foreach($index_list as $list_item) {
					$display .= '<option value="'.$list_item.'"';
					if ($list_item == $field_value_raw || $list_item == "{lang_$field_value_raw}")
					{
						$display .= ' selected="selected"';
					}
					$display .= '>'.$list_item.'</option>';
				}
				$display .= "</select>";
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "select-multiple": // handles multiple item select boxes
				$display .= "<td align=\"right\" class=\"row_main\" valign=\"top\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td>";
				$display .= "<td align=\"left\" class=\"row_main\" ><select name=\"$field_name" . "[]\" multiple=\"multiple\">";
				$feature_index_list = explode("||", $field_elements);
				foreach($feature_index_list as $feature_list_Value => $feature_list_item) {
					$display .= "<option value=\"$feature_list_item\" ";
					// now, compare it against the list of currently selected feature items
					$field_value_list = explode("||", $field_value_raw);
					sort($field_value_list);
					foreach($field_value_list as $field_value_list_item) {
						if ($field_value_list_item == $feature_list_item || $field_value_list_item == "{lang_$feature_list_item}") {
							$display .= "selected=\"selected\"";
						} // end if
					} // end while
					$display .= " >$feature_list_item</option>";
				} // end while
				$display .= "</select>";
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "divider": // handles dividers in forms
				$display .= "<td align=\"left\" class=\"row_main\" colspan=2><strong>$field_caption</strong></td>";
				break;
			case "price": // handles price input
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td>";
				$display .= "<td align=\"left\" class=\"row_main\">$config[money_sign]<input type=\"text\" name=\"$field_name\" value=\"$field_value\" style=\"width:100px;\" onchange=\"if (!IsNumeric(form." . $field_name . ".value)) { 	alert('". $lang['form_alert_numbers_or_decimal']."" . $field_caption . "" . $lang['form_alert_field'] . "'); form." . $field_name . ".focus();form." . $field_name . ".value=''; } \" ";
				if ($field_length != '' && $field_length != 0)
				{
					$display .= 'maxlength="'.$field_length.'" ';
				}
				$display .= '/>';
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "url": // handles url input fields
				$display .= "<td align=\"right\" class=\"row_main\" ><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong><br />($lang[dont_forget_http])</td>";
				$display .= "<td align=\"left\" class=\"row_main\"><input type=\"text\" name=\"$field_name\" value=\"$field_value\" style=\"width:100px;\" ";
				if ($field_length != '' && $field_length != 0)
				{
					$display .= 'maxlength="'.$field_length.'" ';
				}
				$display .= '/>';
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "email": // handles email input
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong><br />($lang[email_example])</td>";
				$display .= "<td align=\"left\" class=\"row_main\"><input type=\"text\" name=\"$field_name\" value=\"$field_value\" style=\"width:100px;\" ";
				if ($field_length != '' && $field_length != 0)
				{
					$display .= 'maxlength="'.$field_length.'" ';
				}
				$display .= '/>';
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "checkbox": // handles checkboxes
				$display .= '<td colspan="2" align="left" class="row_main">';
				$feature_index_list = explode("||", $field_elements);
				sort($feature_index_list);
				$display .= '<table class="admin_option_table">';
				$display .= '<tr><td colspan="3" class="admin_option_caption">'.$field_caption.' ';
				if ($required == "Yes") {
					$display .= '<span class="required">*</span>';
				}
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '<br /><br /></td></tr>';
				$display .= '<tr>';
				$count = 1;
				$numcols = 3;
				foreach($feature_index_list as $feature_list_Value => $feature_list_item) {
					$display .= "<td><input type=\"checkbox\" value=\"$feature_list_item\" name=\"$field_name" . "[]\"";
					// now, compare it against the list of currently selected feature items
					$field_value_list = explode("||", $field_value_raw);
					sort($field_value_list);
					foreach ($field_value_list as $field_value_list_item) {
						if ($field_value_list_item == $feature_list_item || $field_value_list_item == "{lang_$feature_list_item}") {
							$display .= "checked=\"checked\"";
						} // end if
					} // end while
					$display .= " />$feature_list_item</td>";
					if ($count % $numcols == 0) {
						$display .= '</tr><tr>';
					}
					$count++;
				} // end while
				$display .= '</tr></table>';
				$display .= "</td>";
				break;
			case "option": // handles options
				$display .= '<td colspan="2" align="left" class="row_main">';
				$feature_index_list = explode("||", $field_elements);
				sort($feature_index_list);
				$display .= '<table class="admin_option_table">';
				$display .= '<tr><td colspan="3" class="admin_option_caption">'.$field_caption.' ';
				if ($required == "Yes") {
					$display .= '<span class="required">*</span>';
				}
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '<br /><br /></td></tr>';
				$display .= '<tr>';
				$count = 1;
				$numcols = 3;
				foreach($feature_index_list as $feature_list_Value => $feature_list_item) {
					$display .= "<td><input type=\"radio\" value=\"$feature_list_item\" name=\"$field_name\" ";
					// now, compare it against the list of currently selected feature items
					if ($feature_list_item == $field_value_raw || $feature_list_item == "{lang_$field_value_raw}") {
						$display .= "checked=\"checked\" ";
					} // end if
					$display .= " />$feature_list_item</td>";
					if ($count % $numcols == 0) {
						$display .= '</tr><tr>';
					}
					$count++;
				} // end while
				$display .= '</tr></table>';
				$display .= "</td>";
				break;
			case "number": // deals with numbers
			case "decimal":
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td>";
				$display .= "<td align=\"left\" class=\"row_main\"><input type=\"text\" name=\"$field_name\" value=\"$field_value\" style=\"width:100px;\" onchange=\"if (!IsNumeric(form." . $field_name . ".value)) { 	alert('". $lang['form_alert_numbers_or_decimal']."" . $field_caption . "" . $lang['form_alert_field'] . "'); form." . $field_name . ".focus();form." . $field_name . ".value=''; } \" ";
				if ($field_length != '' && $field_length != 0)
				{
					$display .= 'maxlength="'.$field_length.'" ';
				}
				$display .= '/>';
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "submit": // handles submit buttons
				$display .= "<td align=\"center\" class=\"row_main\"><input type=\"submit\" value=\"$field_value\" /></td>";
				break;
			default: // the catch all... mostly for errors and whatnot
				$display .= "<td align=\"right\" class=\"row_main\">no handler yet</td>";
		} // end switch statement
		$display .= "</tr>";
		return $display;
	} // end renderExistingUserFormElement function
	function validateForm ($db_to_validate,$pclass=array())
	{
		// Validates the info being put into the system
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$pass_the_form = "Yes";
		// this stuff is input that's already been dealt with
		// check to if the form should be passed
		$sql = 'SELECT ' . $db_to_validate . '_required, ' . $db_to_validate . '_field_type, ' . $db_to_validate . '_field_name from ' . $config['table_prefix'] . $db_to_validate;
		if(count($pclass) > 0 && $db_to_validate == 'listingsformelements'){
			$sql .= ' WHERE listingsformelements_id IN (SELECT listingsformelements_id FROM '.$config['table_prefix_no_lang'].'classformelements WHERE class_id IN ('.implode(',',$pclass).'))';
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet == false) {
			$misc->log_error($sql);
		}
		while (!$recordSet->EOF) {
			$required = $recordSet->fields[$db_to_validate . '_required'];
			$field_type = $recordSet->fields[$db_to_validate . '_field_type'];
			$field_name = $recordSet->fields[$db_to_validate . '_field_name'];
			if ($required == "Yes") {
				if(!isset($_POST[$field_name]) || (is_array($_POST[$field_name]) && count($_POST[$field_name])==0) || (!is_array($_POST[$field_name]) && trim($_POST[$field_name]) =='') ) {
					$pass_the_form = "No";
					$error[$field_name] = 'REQUIRED';
				}
			} // end if
			if ($field_type == 'number' && isset($_POST[$field_name]) && !is_numeric($_POST[$field_name]) && $_POST[$field_name] != "") {
				$pass_the_form = "No";
				$error[$field_name] = 'TYPE';
			}
			$recordSet->MoveNext();
		}
		if ($pass_the_form == 'Yes') {
			return $pass_the_form;
		}else {
			return $error;
		}
	} // end function validateForm
	function renderFormElement($field_type, $field_name, $field_caption, $default_text, $field_elements, $required,$field_length = '', $tool_tip='')
	{
		global $lang, $config;
		// handles the rendering of forms...
		$display = '';
		$display .= "<tr>";
		switch ($field_type) {
			case "date":
			case "lat":
			case "long":
			case "text": // handler for regular text boxes
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td>";
				$display .= "<td align=\"left\" class=\"row_main\"><input type=\"text\" name=\"$field_name\" value=\"$default_text\" style=\"width:100px;\" ";
				if ($field_length != '' && $field_length != 0)
				{
					$display .= 'maxlength="'.$field_length.'" ';
				}
				if ($field_type == 'date') {
					$display .= 'onFocus="javascript:vDateType=\'' . $config['date_format'] . '\'" onKeyUp="DateFormat(this,this.value,event,false,\'' . $config['date_format'] . '\')" onBlur="DateFormat(this,this.value,event,true,\'' . $config['date_format'] . '\')" />(' . $config['date_format_long'] . ')';
					if ($tool_tip != '') {
						$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
					}
					$display .= '</td>';
				}else {
					$display .= " />";
					if ($tool_tip != '') {
						$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16"  /><span>'.$tool_tip.'</span></a>';
					}
					$display .= '</td>';
				}
				break;
			case "textarea": // handler for textarea boxes
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";

				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td>";
				$display .= "<td align=\"left\" class=\"row_main\"><textarea name=\"$field_name\" cols=40 rows=6>$default_text</textarea>";
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "select": // handler for select boxes
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td>";
				$display .= "<td align=\"left\" class=\"row_main\"><select name=\"$field_name\" size=\"1\">";

				$index_list = explode("||", $field_elements);
				foreach($index_list as $list_item) {
					$display .= '<option value="'.$list_item.'"';
					if ($list_item == $default_text || $list_item == "{lang_$default_text}")
					{
						$display .= ' selected="selected"';
					}
					$display .= '>'.$list_item.'</option>';
				}
				$display .= "</select>";
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "select-multiple": // handler for select boxes where you can choose multiple items
				$display .= "<td align=\"right\" class=\"row_main\" valign=\"top\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td>";
				$display .= "<td align=\"left\" class=\"row_main\"><select name=\"$field_name" . "[]\" multiple=\"multiple\">";

				$index_list = explode("||", $field_elements);
				foreach($index_list as $list_item) {
					$display .= "<option value=\"$list_item\">$list_item</option>";
				}
				$display .= "</select>";
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "divider": // dividers between items
				$display .= "<td align=\"left\" class=\"row_main\" colspan=2><strong>$field_caption</strong></td>";
				break;
			case "price": // handles price
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td>";
				$display .= "<td align=\"left\" class=\"row_main\">$config[money_sign]<input type=\"text\" name=\"$field_name\" value=\"$default_text\" style=\"width:100px;\" onchange=\"if (!IsNumeric(form." . $field_name . ".value)) { 	alert('". $lang['form_alert_numbers_or_decimal']."" . $field_caption . "" . $lang['form_alert_field'] . "'); form." . $field_name . ".focus();form." . $field_name . ".value=''; } \" ";
				if ($field_length != '' && $field_length != 0)
				{
					$display .= 'maxlength="'.$field_length.'" ';
				}
				$display .= '/>';
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "url": // handles url input fields
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong><br />($lang[dont_forget_http])</td>";
				$display .= "<td align=\"left\" class=\"row_main\"><input type=\"text\" name=\"$field_name\" value=\"$default_text\" style=\"width:100px;\" ";
				if ($field_length != '' && $field_length != 0)
				{
					$display .= 'maxlength="'.$field_length.'" ';
				}
				$display .= '/>';
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "email": // handles email input fields
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong><br />($lang[email_example])</td>";
				$display .= "<td align=\"left\" class=\"row_main\"><input type=\"text\" name=\"$field_name\" value=\"$default_text\" style=\"width:100px;\"  ";
				if ($field_length != '' && $field_length != 0)
				{
					$display .= 'maxlength="'.$field_length.'" ';
				}
				$display .= '/>';
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "checkbox": // handles check boxes
				$display .= '<td colspan="2" align="left" class="row_main">';
				$index_list = explode("||", $field_elements);
				sort($index_list);
				$display .= '<table class="admin_option_table">';
				$display .= '<tr><td colspan="3" class="admin_option_caption">'.$field_caption.' ';
				if ($required == "Yes") {
					$display .= '<span class="required">*</span>';
				}
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '<br /><br /></td></tr>';
				$display .= '<tr>';
				$count = 1;
				$numcols = 3;
				foreach($index_list as $indexValue => $list_item) {
					$display .= "<td><input type=\"checkbox\" value=\"$list_item\" name=\"$field_name" . "[$indexValue]\" />$list_item</td>";
					if ($count % $numcols == 0) {
						$display .= '</tr><tr>';
					}
					$count++;
				} // end while
				$display .= '</tr></table>';
				$display .= "</td>";
				break;
			case "option": // handles radio buttons
				$display .= '<td colspan="2" align="left" class="row_main">';
				$index_list = explode("||", $field_elements);
				sort($index_list);
				$display .= '<table class="admin_option_table">';
				$display .= '<tr><td colspan="3" class="admin_option_caption">'.$field_caption.' ';
				if ($required == "Yes") {
					$display .= '<span class="required">*</span>';
				}
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '<br /><br /></td></tr>';
				$display .= '<br /><br /></td></tr>';
				$display .= '<tr>';
				$count = 1;
				$numcols = 3;
				foreach($index_list as $indexValue => $list_item) {
					$display .= "<td><input type=\"radio\" value=\"$list_item\" name=\"$field_name\" />$list_item</td>";
					if ($count % $numcols == 0) {
						$display .= '</tr><tr>';
					}
					$count++;
				} // end while
				$display .= '</tr></table>';
				$display .= "</td>";
				break;
			case "number": // handles the input of numbers
			case "decimal":
				$display .= "<td align=\"right\" class=\"row_main\"><strong>$field_caption ";
				if ($required == "Yes") {
					$display .= "<span class=\"required\">*</span>";
				}
				$display .= "</strong></td>";
				$display .= "<td align=\"left\" class=\"row_main\"><input type=\"text\" name=\"$field_name\" value=\"$default_text\" style=\"width:100px;\" onchange=\"if (!IsNumeric(form." . $field_name . ".value)) { 	alert('". $lang['form_alert_numbers_or_decimal']."" . $field_caption . "" . $lang['form_alert_field'] . "'); form." . $field_name . ".focus();form." . $field_name . ".value=''; } \" ";
				if ($field_length != '' && $field_length != 0)
				{
					$display .= 'maxlength="'.$field_length.'" ';
				}
				$display .= '/>';
				if ($tool_tip != '') {
					$display .= ' <a href="#" class="tooltip"><img src="images/info.gif" width="16" height="16" /><span>'.$tool_tip.'</span></a>';
				}
				$display .= '</td>';
				break;
			case "submit": // handles submit buttons
				$display .= "<td align=\"center\" class=\"row_main\" colspan=\"2\"><input type=\"submit\" value=\"$field_caption\" /></td>";
				break;

			default: // the default handler -- for errors, mostly
				$display .= "<td align=\"right\" class=\"row_main\">no handler yet - $field_type</td>";
		} // end switch statement
		$display .= "</tr>";
		return $display;
	} // end renderFormElement function
}

?>