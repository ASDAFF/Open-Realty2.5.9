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
if (!class_exists('formGeneration')) {
	class formGeneration {
		function startform($action, $method = 'post', $enctype = 'multipart/form-data', $name = '')
		{
			if ($name == '') {
				$formitem = '<form action="' . $action . '" enctype="' . $enctype . '" method="' . $method . '">';
			}else {
				$formitem = '<form action="' . $action . '" enctype="' . $enctype . '" method="' . $method . '" name="' . $name . '">';
			}

			return $formitem;
		} // End function startform()
		function createformitem($type, $name = '', $value = '', $multiple = false, $size = 25, $src = '', $checked = false, $rows = 0, $cols = 0, $options = '', $selected_item = '', $disabled = false)
		{
			// $options needs to be an array value,caption
			$formitem = 'create_' . $type;
			$formitem = $this->$formitem($name, $value, $multiple, $size, $src, $checked, $rows, $cols, $options, $selected_item, $disabled);
			$formitem .= "\r\n";
			return $formitem;
		} // End function startform()
		function endform()
		{
			$formitem = '</form>';
			return $formitem;
		} // End function endform()
		// These functions should not be called direct
		function create_text($name, $value = '', $multiple = false, $size = 25, $src, $checked = false, $rows, $cols, $options, $selected_item = '', $disabled = false)
		{
			if ($disabled) {
				$formitem = '<input type="text" name="' . $name . '" value="' . $value . '" size="' . $size . '" disabled="disabled" />';
			}else {
				$formitem = '<input type="text" name="' . $name . '" value="' . $value . '" size="' . $size . '" />';
			}
			return $formitem;
		}
		function create_lockedtext($name, $value = '', $multiple = false, $size = 25, $src, $checked = false, $rows, $cols, $options, $selected_item = '', $disabled = false)
		{
			$formitem = '<input type="text" name="' . $name . '" value="' . $value . '" size="' . $size . '" onfocus="this.blur()" />';
			return $formitem;
		}
		function create_password($name, $value = '', $multiple = false, $size = 25, $src, $checked = false, $rows, $cols, $options, $selected_item = '', $disabled = false)
		{
			$formitem = '<input type="password" name="' . $name . '" value="' . $value . '" size="' . $size . '" />';
			return $formitem;
		}
		function create_hidden($name, $value = '', $multiple = false, $size = 25, $src, $checked = false, $rows, $cols, $options, $selected_item = '', $disabled = false)
		{
			$formitem = '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
			return $formitem;
		}
		function create_submit($name, $value = '', $multiple = false, $size = 25, $src, $checked = false, $rows, $cols, $options, $selected_item = '', $disabled = false)
		{
			if ($value == '') {
				$value = 'submit';
			}
			$formitem = '<input type="submit" value="' . $value . '"';
			if ($src != '') {
				$formitem .= ' src="' . $src . '"';
			}
			$formitem .= ' />';
			return $formitem;
		}
		function create_checkbox($name, $value = '', $multiple = false, $size = 25, $src, $checked = false, $rows, $cols, $options, $selected_item = '', $disabled = false)
		{
			$formitem = '<input type="checkbox" name="' . $name . '" value="' . $value . '"';
			if ($checked == true) {
				$formitem .= ' checked="checked"';
			}
			$formitem .= ' />';
			return $formitem;
		}
		function create_lockedcheckbox($name, $value = '', $multiple = false, $size = 25, $src, $checked = false, $rows, $cols, $options, $selected_item = '', $disabled = false)
		{
			$formitem = '<input type="checkbox" name="' . $name . '" value="' . $value . '"';
			if ($checked == true) {
				$formitem .= ' checked="checked"';
			}
			$formitem .= ' onfocus="this.blur()" />';
			return $formitem;
		}
		function create_textarea($name, $value = '', $multiple = false, $size = 25, $src, $checked = false, $rows, $cols, $options, $selected_item = '', $disabled = false)
		{
			$formitem = '<textarea rows="' . $rows . '" cols="' . $cols . '"  name="' . $name . '">' . $value.'</textarea>';
			return $formitem;
		}
		function create_select($name, $value = '', $multiple = false, $size = 25, $src, $checked = false, $rows, $cols, $options, $selected_item = '', $disabled = false)
		{
			$formitem = '<select name="' . $name . '"';
			if ($multiple == true) {
				$formitem .= ' multiple="multiple"';
				$formitem .= ' size="' . $size . '"';
			}
			$formitem .= '>';
			if ($options != '') {
				foreach ($options as $k => $v) {
					if (is_array($selected_item)) {
						if (in_array($k, $selected_item)) {
							$formitem .= '<option value="' . $k . '" selected="selected">' . $v . '</option>';
						}else {
							$formitem .= '<option value="' . $k . '">' . $v . '</option>';
						}
					}else {
						if ($k == $selected_item) {
							$formitem .= '<option value="' . $k . '" selected="selected">' . $v . '</option>';
						}else {
							$formitem .= '<option value="' . $k . '">' . $v . '</option>';
						}
					}
				}
			}
			$formitem .= '</select>';
			return $formitem;
		}
		function create_radio($name, $value = '', $multiple = false, $size = 25, $src, $checked = false, $rows, $cols, $options, $selected_item = '', $disabled = false)
		{
			$formitem = '<input type="radio" name="' . $name . '" value="' . $value . '"';
			if ($checked == true) {
				$formitem .= ' checked="checked"';
			}
			$formitem .= ' />';
			return $formitem;
		}
		function create_file($name, $value = '', $multiple = false, $size = 25, $src, $checked = false, $rows, $cols, $options, $selected_item = '', $disabled = false)
		{
			$formitem = '<input type="file" name="' . $name . '" />';
			return $formitem;
		}
	} // End formGeneration class
} //End if (!class exisists)

?>