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
class file_handler {
	var $debug = false;

		function edit_listing_files()
		{
			global $lang, $conn, $config;
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$display = '';
			if (isset($_GET['edit']) && $_GET['edit'] != '')
			{
			$_POST['edit'] = $_GET['edit'];
			}
			$edit = $_POST['edit'];
			$sql_edit = $misc->make_db_safe($_POST['edit']);
			$uploadpath = $config[listings_file_upload_path].'/'.$edit;
			if (!isset($_POST['action'])) {
				$_POST['action'] = '';
			}
// does this person have access to these listings?
			if (($_SESSION['edit_all_listings'] != "yes") && ($_SESSION['admin_privs'] != "yes")) {
				$sql = "SELECT userdb_id FROM " . $config['table_prefix'] . "listingsdb WHERE (listingsdb_id = $sql_edit)";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				} while (!$recordSet->EOF) {
					$owner = $recordSet->fields['userdb_id'];
					$recordSet->MoveNext();
				}
				if ($_SESSION['userID'] != $owner) {
					die ($lang['priv_failure']);
				}
			} // end priv check
			if ($_POST['action'] == "update_file") {
				$count = 0;
				$num_fields = count($_POST['file']);
				$sql_edit = $misc->make_db_safe($_POST['edit']);
				while ($count < $num_fields) {
				$sql_caption = $misc->make_db_safe($_POST['caption'][$count]);
				$sql_description = $misc->make_db_safe($_POST['description'][$count]);
				$sql_rank = $misc->make_db_safe($_POST['rank'][$count]);
				$sql_file = $misc->make_db_safe($_POST['file'][$count]);

				if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
					$sql = "UPDATE " . $config['table_prefix'] . "listingsfiles SET listingsfiles_caption = $sql_caption, listingsfiles_description = $sql_description, listingsfiles_rank = $sql_rank WHERE ((listingsdb_id = $sql_edit) AND (listingsfiles_file_name = $sql_file))";
				} else {
					$sql = "UPDATE " . $config['table_prefix'] . "listingsfiles SET listingsfiles_caption = $sql_caption, listingsfiles_description = $sql_description, listingsfiles_rank = $sql_rank WHERE ((listingsdb_id = $sql_edit) AND (listingsfiles_file_name = $sql_file) AND (userdb_id = $_SESSION[userID]))";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$count++;
				}
				$display .= '<p>'.$lang['files_update'].'</p>';
				$misc->log_action ($lang['log_updated_listing_file'] . $_POST['edit']);
			}

			if (isset($_GET['delete'])) {
// get the data for the file being deleted
				$sql_file_id = $misc->make_db_safe($_GET['delete']);
				$sql_edit = $misc->make_db_safe($_GET['edit']);
				if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
					$sql = "SELECT listingsfiles_file_name FROM " . $config['table_prefix'] . "listingsfiles WHERE ((listingsdb_id = $sql_edit) AND (listingsfiles_id = $sql_file_id))";
				}else {
					$sql = "SELECT listingsfiles_file_name FROM " . $config['table_prefix'] . "listingsfiles WHERE ((listingsdb_id = $sql_edit) AND (listingsfiles_id = $sql_file_id) AND (userdb_id = $_SESSION[userID]))";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				} while (!$recordSet->EOF) {
					$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsfiles_file_name']);
					$recordSet->MoveNext();
				} // end while
// Delete from the DB
				if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
					$sql = "DELETE FROM " . $config['table_prefix'] . "listingsfiles WHERE ((listingsdb_id = $sql_edit) AND (listingsfiles_file_name = '$file_name'))";
				} else {
					$sql = "DELETE FROM " . $config['table_prefix'] . "listingsfiles WHERE ((listingsdb_id = $sql_edit) AND (listingsfiles_file_name = '$file_name') AND (userdb_id = '$_SESSION[userID]'))";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
// delete the files themselves
				if (!unlink("$uploadpath/$file_name")) die("$lang[alert_site_admin]");
				$empty = (count(glob("$uploadpath/*")) === 0) ? 'true' : 'false';
				if ($empty == 'true') {
					rmdir($uploadpath);
				}
				$misc->log_action ("$lang[log_deleted_listing_file] $file_name");
				$display .= "<p>$lang[image] '$file_name' $lang[has_been_deleted]</p>";
			}
			if ($_POST['action'] == "upload") {
				if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
// get the owner of the listing
					$sql = "SELECT userdb_id FROM " . $config['table_prefix'] . "listingsdb WHERE (listingsdb_id = $sql_edit)";
					$recordSet = $conn->Execute($sql);
					if ($recordSet === false) {
						$misc->log_error($sql);
					} while (!$recordSet->EOF) {
						$owner = $recordSet->fields['userdb_id'];
						$recordSet->MoveNext();
					}
					$display .= $this->uploadfile("listings", $edit, $owner);
				} else {
					$display .= $this->uploadfile("listings", $edit, $_SESSION['userID']);
				}
			} // end if $action == "upload"
			if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$sql = "SELECT listingsfiles_id, listingsfiles_caption, listingsfiles_file_name, listingsfiles_description, listingsfiles_rank FROM " . $config['table_prefix'] . "listingsfiles WHERE (listingsdb_id = $sql_edit) ORDER BY listingsfiles_rank";
			} else {
				$sql = "SELECT listingsfiles_id, listingsfiles_caption, listingsfiles_file_name, listingsfiles_description, listingsfiles_rank FROM " . $config['table_prefix'] . "listingsfiles WHERE ((listingsdb_id = $sql_edit) AND (userdb_id = '$_SESSION[userID]')) ORDER BY listingsfiles_rank";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$display .= '<table class="file_upload">';
			$ext = '';
			$num_files = $recordSet->RecordCount();
			$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsfiles_file_name']);
			$ext = substr(strrchr($file_name, '.'), 1);
			$avaliable_files = $config["max_listings_file_uploads"] - $num_files;
			$x = 0;
			if ($num_files < $config['max_listings_file_uploads']) {
				$display .= '<table border="0" cellspacing="0" cellpadding="0">';
				$display .= '<tr>';
				$display .= '<td colspan="2">';
				$display .= '<h3>' . $lang['upload_a_file'] . '</h3>';
				$display .= '</td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td width="150">&nbsp;</td>';
				$display .= '<td>';
				$display .= '<form enctype="multipart/form-data" action="index.php?action=edit_listing_files" method="post">';
				$display .= '<input type="hidden" name="action" value="upload" />';
				$display .= '<input type="hidden" name="edit" value="' . $edit . '" />';
				$display .= '<input type="hidden" name="MAX_FILE_SIZE" value="' . $config['max_listings_file_upload_size'] . '" />';
				while ($x < $avaliable_files) {
					$display .= '<b>' . $lang['upload_send_this_file'] . ': </b><input name="userfile[]" type="file" /><br />';
					$x++;
				}
				$display .= '<input type="submit" value="' . $lang['upload_file'] . '" />';
				$display .= '</form>';
				$display .= '</td>';
				$display .= '</tr>';
				$display .= '</table>';
			} // end if $num_files <= $config['max_listings_file_uploads']
			$display .= '<table class="file_upload">';
			$display .= '<tr>';
			$display .= '<td colspan="2">';
			$display .= '<h3>' . $lang['edit_files'] . ' -- ';
			if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$display .= "<a href=\"index.php?action=edit_listings&amp;edit=$edit\">";
			} else {
				$display .= "<a href=\"index.php?action=edit_my_listings&amp;edit=$edit\">";
			}
			$display .= $lang['return_to_editing_listing'];
			$display .= '</a></h3></td></tr>';
			$display .= '</table>';
			$count = 0;
			$display .= '<form action="index.php?action=edit_listing_files" method="post">';
			$display .= '<table class="file_upload">';
			while (!$recordSet->EOF) {
// $edit = $misc->make_db_safe($_POST['edit']);
				$file_id = $recordSet->fields['listingsfiles_id'];
				$rank = $recordSet->fields['listingsfiles_rank'];
				$caption = $misc->make_db_unsafe ($recordSet->fields['listingsfiles_caption']);
				$description = $misc->make_db_unsafe ($recordSet->fields['listingsfiles_description']);
				$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsfiles_file_name']);
				$ext = substr(strrchr($file_name, '.'), 1);
				$filesize = filesize($uploadpath.'/'.$file_name);
				$showsize = $this->bytesize($filesize);
// alternate the colors
				if ($count == 0) {
					$count = 1;
				}else {
					$count = 0;
				}
				$iconpath = $config["file_icons_path"] . '/' . $ext . '.png';
				if (file_exists($iconpath)) {
					$icon = $config["listings_view_file_icons_path"] . '/' . $ext . '.png';
				} else {
					$icon = $config["listings_view_file_icons_path"] . '/default.png';
				}
				$display .= '<tr class="image_row_'.$count.'"><td valign="top" class="image_row_'.$count.'" width="150"><img src="'.$icon.'" height="'.$config["file_icon_height"].'" width="'.$config["file_icon_width"].'" alt="'.$file_name.'" /> <b>'.$file_name.'</b><br />'.$lang[size].' = '.$showsize.'<br />';
				$display .= '<br /><a href="index.php?action=edit_listing_files&amp;delete='.$file_id.'&amp;edit='.$edit.'" onclick="return confirmDelete()">'.$lang['delete'].'</a>';
				$display .= '</tr><tr><td align="center" class="image_row_'.$count.'">';
				$display .= '<input type="hidden" name="file[]" value="'.$file_name.'" />';
				$display .= '<table border="0">';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['admin_template_editor_field_rank'].':</b></td><td align="left"><input type="text" name="rank[]" value="'.$rank.'" /><div class="small">'.$lang['file_upload_rank_explanation'].'</div></td></tr>';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_caption'].':</b></td><td align="left"><input type="text" name="caption[]" value="'.$caption.'" /></td></tr>';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_description'].':</b><td align="left"><textarea name="description[]" rows="6" cols="40">'.$description.'</textarea></td></tr>';
				$display .= '</table>';
				$display .= '</td></tr><tr><td><hr /></td></tr>';
				$recordSet->MoveNext();
			} // end while
			$display .= '<tr><td align="center" class="image_row_'.$count.'" colspan="2"><input type="submit" value="'.$lang['update'].'" />';
			$display .= '</table>';
			$display .= '<input type="hidden" name="edit" value="'.$edit.'" />';
			$display .= '<input type="hidden" name="action" value="update_file" />';
			$display .= '</form>';
			return $display;
		}

		function edit_user_files()
		{
			global $lang, $conn, $config;
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			$display = '';
			if (isset($_GET['edit']) && $_GET['edit'] != '')
			{
			$_POST['edit'] = $_GET['edit'];
			}
			$edit = $_POST['edit'];
			$sql_edit = $misc->make_db_safe($_POST['edit']);
			$uploadpath = $config[users_file_upload_path].'/'.$edit;
			if (!isset($_POST['action'])) {
				$_POST['action'] = '';
			}
			if ($_POST['action'] == "update_file") {
				$count = 0;
				$num_fields = count($_POST['file']);
				$sql_edit = $misc->make_db_safe($_POST['edit']);
				while ($count < $num_fields) {
				$sql_caption = $misc->make_db_safe($_POST['caption'][$count]);
				$sql_description = $misc->make_db_safe($_POST['description'][$count]);
				$sql_rank = $misc->make_db_safe($_POST['rank'][$count]);
				$sql_file = $misc->make_db_safe($_POST['file'][$count]);
				if ($_SESSION['edit_all_users'] == "yes" || $_SESSION['admin_privs'] == "yes") {
					$sql = "UPDATE " . $config['table_prefix'] . "usersfiles SET usersfiles_caption = $sql_caption, usersfiles_description = $sql_description, usersfiles_rank = $sql_rank WHERE ((userdb_id = $sql_edit) AND (usersfiles_file_name = $sql_file))";
				} else {
					$sql = "UPDATE " . $config['table_prefix'] . "usersfiles SET usersfiles_caption = $sql_caption, usersfiles_description = $sql_description, usersfiles_rank = $sql_rank WHERE ((usersfiles_file_name = $sql_file) AND (userdb_id = $_SESSION[userID]))";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$count++;
				}
				$display .= '<p>'.$lang['files_update'].'</p>';
				$misc->log_action ($lang['log_updated_listing_file'] . $_POST['edit']);
			}

			if (isset($_GET['delete'])) {
// get the data for the file being deleted
				$sql_file_id = $misc->make_db_safe($_GET['delete']);
				$sql_edit = $misc->make_db_safe($_GET['edit']);
				if ($_SESSION['edit_all_users'] == "yes" || $_SESSION['admin_privs'] == "yes") {
					$sql = "SELECT usersfiles_file_name FROM " . $config['table_prefix'] . "usersfiles WHERE ((userdb_id = $sql_edit) AND (usersfiles_id = $sql_file_id))";
				}else {
					$sql = "SELECT usersfiles_file_name FROM " . $config['table_prefix'] . "usersfiles WHERE ((usersfiles_id = $sql_file_id) AND (userdb_id = $_SESSION[userID]))";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				} while (!$recordSet->EOF) {
					$file_name = $misc->make_db_unsafe ($recordSet->fields['usersfiles_file_name']);
					$recordSet->MoveNext();
				} // end while
// Delete from the DB
				if ($_SESSION['edit_all_users'] == "yes" || $_SESSION['admin_privs'] == "yes") {
					$sql = "DELETE FROM " . $config['table_prefix'] . "usersfiles WHERE ((userdb_id = $sql_edit) AND (usersfiles_file_name = '$file_name'))";
				} else {
					$sql = "DELETE FROM " . $config['table_prefix'] . "usersfiles WHERE ((usersfiles_file_name = '$file_name') AND (userdb_id = '$_SESSION[userID]'))";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
// delete the files themselves
				if (!unlink("$uploadpath/$file_name")) die("$lang[alert_site_admin]");
				$empty = (count(glob("$uploadpath/*")) === 0) ? 'true' : 'false';
				if ($empty == 'true') {
					rmdir($uploadpath);
				}
				$misc->log_action ("$lang[log_deleted_listing_file] $file_name");
				$display .= "<p>$lang[image] '$file_name' $lang[has_been_deleted]</p>";
			}
			if ($_POST['action'] == "upload") {
				if ($_SESSION['edit_all_users'] == "yes" || $_SESSION['admin_privs'] == "yes") {
					$display .= $this->uploadfile("users", '', $_POST['edit']);
				} else {
					$display .= $this->uploadfile("users", '', $_SESSION['userID']);
				}
			} // end if $action == "upload"
			if ($_SESSION['edit_all_users'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$sql = "SELECT usersfiles_id, usersfiles_caption, usersfiles_file_name, usersfiles_description, usersfiles_rank FROM " . $config['table_prefix'] . "usersfiles WHERE (userdb_id = $sql_edit) ORDER BY usersfiles_rank";
			} else {
				$sql = "SELECT usersfiles_id, usersfiles_caption, usersfiles_file_name, usersfiles_description, usersfiles_rank FROM " . $config['table_prefix'] . "usersfiles WHERE ((userdb_id = '$_SESSION[userID]')) ORDER BY usersfiles_rank";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$display .= '<table class="file_upload">';
			$ext = '';
			$num_files = $recordSet->RecordCount();
			$file_name = $misc->make_db_unsafe ($recordSet->fields['usersfiles_file_name']);
			$ext = substr(strrchr($file_name, '.'), 1);
			$avaliable_files = $config["max_users_file_uploads"] - $num_files;
			$x = 0;
			if ($num_files < $config['max_users_file_uploads']) {
				$display .= '<table border="0" cellspacing="0" cellpadding="0">';
				$display .= '<tr>';
				$display .= '<td colspan="2">';
				$display .= '<h3>' . $lang['upload_a_file'] . '</h3>';
				$display .= '</td>';
				$display .= '</tr>';
				$display .= '<tr>';
				$display .= '<td width="150">&nbsp;</td>';
				$display .= '<td>';
				$display .= '<form enctype="multipart/form-data" action="index.php?action=edit_user_files" method="post">';
				$display .= '<input type="hidden" name="action" value="upload" />';
				$display .= '<input type="hidden" name="edit" value="' . $edit . '" />';
				$display .= '<input type="hidden" name="MAX_FILE_SIZE" value="' . $config['max_users_file_upload_size'] . '" />';
				while ($x < $avaliable_files) {
					$display .= '<b>' . $lang['upload_send_this_file'] . ': </b><input name="userfile[]" type="file" /><br />';
					$x++;
				}
				$display .= '<input type="submit" value="' . $lang['upload_file'] . '" />';
				$display .= '</form>';
				$display .= '</td>';
				$display .= '</tr>';
				$display .= '</table>';
			} // end if $num_files <= $config['max_listings_file_uploads']
			$display .= '<table class="file_upload">';
			$display .= '<tr>';
			$display .= '<td colspan="2">';
			$display .= '<h3>' . $lang['edit_files'] . ' -- ';
			if ($_SESSION['edit_all_users'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$display .= "<a href=\"index.php?action=user_manager&amp;edit=$edit\">";
			} else {
				$display .= "<a href=\"index.php?action=user_manager&amp;edit=$edit\">";
			}
			$display .= $lang['return_to_editing_account'];
			$display .= '</a></h3></td></tr>';
			$display .= '</table>';
			$count = 0;
			$display .= '<form action="index.php?action=edit_user_files" method="post">';
			$display .= '<table class="file_upload">';
			while (!$recordSet->EOF) {
// $edit = $misc->make_db_safe($_POST['edit']);
				$file_id = $recordSet->fields['usersfiles_id'];
				$rank = $recordSet->fields['usersfiles_rank'];
				$caption = $misc->make_db_unsafe ($recordSet->fields['usersfiles_caption']);
				$description = $misc->make_db_unsafe ($recordSet->fields['usersfiles_description']);
				$file_name = $misc->make_db_unsafe ($recordSet->fields['usersfiles_file_name']);
				$ext = substr(strrchr($file_name, '.'), 1);
				$filesize = filesize($uploadpath.'/'.$file_name);
				$showsize = $this->bytesize($filesize);
// alternate the colors
				if ($count == 0) {
					$count = 1;
				}else {
					$count = 0;
				}
				$iconpath = $config["file_icons_path"] . '/' . $ext . '.png';
				if (file_exists($iconpath)) {
					$icon = $config["listings_view_file_icons_path"] . '/' . $ext . '.png';
				} else {
					$icon = $config["listings_view_file_icons_path"] . '/default.png';
				}
				$display .= '<tr class="image_row_'.$count.'"><td valign="top" class="image_row_'.$count.'" width="150"><img src="'.$icon.'" height="'.$config["file_icon_height"].'" width="'.$config["file_icon_width"].'" alt="'.$file_name.'" /> <b>'.$file_name.'</b><br />'.$lang[size].' = '.$showsize.'<br />';
				$display .= '<br /><a href="index.php?action=edit_user_files&amp;delete='.$file_id.'&amp;edit='.$edit.'" onclick="return confirmDelete()">'.$lang['delete'].'</a>';
				$display .= '</tr><tr><td align="center" class="image_row_'.$count.'">';
				$display .= '<input type="hidden" name="file[]" value="'.$file_name.'" />';
				$display .= '<table border="0">';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['admin_template_editor_field_rank'].':</b></td><td align="left"><input type="text" name="rank[]" value="'.$rank.'" /><div class="small">'.$lang['file_upload_rank_explanation'].'</div></td></tr>';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_caption'].':</b></td><td align="left"><input type="text" name="caption[]" value="'.$caption.'" /></td></tr>';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_description'].':</b><td align="left"><textarea name="description[]" rows="6" cols="40">'.$description.'</textarea></td></tr>';
				$display .= '</table>';
				$display .= '</td></tr><tr><td><hr /></td></tr>';
				$recordSet->MoveNext();
			} // end while
			$display .= '<tr><td align="center" class="image_row_'.$count.'" colspan="2"><input type="submit" value="'.$lang['update'].'" />';
			$display .= '</table>';
			$display .= '<input type="hidden" name="edit" value="'.$edit.'" />';
			$display .= '<input type="hidden" name="action" value="update_file" />';
			$display .= '</form>';
			return $display;
		}

	function uploadfile($type, $edit, $owner)
	{
		// deals with incoming uploads
		global $config, $conn, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$file_x = 0;
		if ($type == 'users') {
			$sql = "SELECT count(" . $type . "files_id) as num_files FROM " . $config['table_prefix'] . "" . $type . "files WHERE (userdb_id = $owner)";
		} else {
			$sql = "SELECT count(" . $type . "files_id) as num_files FROM " . $config['table_prefix'] . "" . $type . "files WHERE (listingsdb_id = $edit)";
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_files = $recordSet->fields['num_files'];
		$avaliable_files = $config['max_' . $type . '_file_uploads'] - $num_files;
		while ($file_x < $avaliable_files) {
			if (is_uploaded_file($_FILES['userfile']['tmp_name'][$file_x])) {
				$realname = $misc->clean_filename($_FILES['userfile']['name'][$file_x]);
				$filename = $_FILES['userfile']['tmp_name'][$file_x];
				$extension = substr(strrchr($realname, "."), 1);
				$pass_the_upload = "true";
// check file extensions
				if (!in_array($extension, explode(',', $config['allowed_file_upload_extensions']))) {
					$pass_the_upload = "$lang[upload_invalid_extension]: $extension";
				}
// check size
				$filesize = $_FILES['userfile']['size'][$file_x];
				if ($config['max_' . $type . '_file_upload_size'] != 0 && $filesize > $config['max_' . $type . '_file_upload_size']) {
					$pass_the_upload = $lang['upload_too_large'] . '<br />' . $lang['failed_max_filesize'] . ' ' . $config['max_' . $type . '_file_upload_size'] . '' . $lang['bytes'];
				}
// security error
				if (strstr($_FILES['userfile']['name'][$file_x], "..") != "") {
					$pass_the_upload = "$lang[upload_security_violation]!";
				}
// make sure the file hasn't already been uploaded...
				if ($type == "listings") {
					$save_name = $realname;
					$sql = "SELECT listingsfiles_file_name FROM " . $config['table_prefix'] . "listingsfiles WHERE listingsfiles_file_name = '$save_name' AND listingsdb_id = $_POST[edit]";
				} elseif ($type == "users") {
					$save_name = $realname;
					$sql = "SELECT usersfiles_file_name FROM " . $config['table_prefix'] . "usersfiles WHERE usersfiles_file_name = '$save_name'";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$num = $recordSet->RecordCount();
				if ($num > 0) {
					$pass_the_upload = "$lang[file_exists]!";
				}
// IF the upload has passed all the tests do:
				if ($pass_the_upload == "true") {
					if ($type == "listings") {
						$uploadpath = $config[listings_file_upload_path].'/'.$edit;

						if (!file_exists($uploadpath)) {
						mkdir($uploadpath, 0777);
						}
						move_uploaded_file($_FILES['userfile']['tmp_name'][$file_x], "$uploadpath/$save_name");
// Get Max Image Rank
						$sql = "SELECT MAX(listingsfiles_rank) AS max_rank FROM " . $config['table_prefix'] . "listingsfiles WHERE (listingsdb_id = '$edit')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$rank = $recordSet->fields['max_rank'];
						$rank++;
						$sql = "INSERT INTO " . $config['table_prefix'] . "listingsfiles (listingsdb_id, userdb_id, listingsfiles_file_name, listingsfiles_rank, listingsfiles_caption, listingsfiles_description, listingsfiles_active) VALUES ('$edit', '$owner', '$save_name', $rank,'','','yes')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$misc->log_action ("$lang[log_uploaded_listing_file] $save_name");
						@chmod("$uploadpath/$save_name", 0777);
					} // end if $type == "listings"
// IF the type of upload is a user file do:
					if ($type == "users") {
						$uploadpath = $config[users_file_upload_path].'/'.$owner;
						if (!file_exists($uploadpath)) {
						mkdir($uploadpath, 0777);
						}
						move_uploaded_file($_FILES['userfile']['tmp_name'][$file_x], "$uploadpath/$save_name");
// Get Max Image Rank
						$sql = "SELECT MAX(usersfiles_rank) AS max_rank FROM " . $config['table_prefix'] . "usersfiles WHERE (userdb_id = '$owner')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$rank = $recordSet->fields['max_rank'];
						$rank++;
						$sql = "INSERT INTO " . $config['table_prefix'] . "usersfiles (userdb_id, usersfiles_file_name,usersfiles_rank,usersfiles_caption,usersfiles_description,usersfiles_active) VALUES ('$owner', '$save_name', $rank,'','','yes')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$misc->log_action ("$lang[log_uploaded_user_image] $save_name");
						@chmod("$config[user_upload_path]/$save_name", 0777);
					} // end if $type == "user"
					$display .= "<p>$realname $lang[upload_success].</p>";
// end if $pass_the_upload == "true"
				} else {
// else the upload has failed... lets tell them why... the suspense is killing me...
					$display .= "<p><strong>$lang[upload_failed]</strong> $pass_the_upload</p>";
				}
			} // end if
			else {
				// print_r($_FILES);
				if ($_FILES['userfile']['error'][$file_x] != 4) {
					$display .= "$lang[upload_too_large]: " . $_FILES['userfile']['name'][$file_x] . ".<br />";
				}
			}
			$file_x++;
		}
		return $display;
	} // end function uploadfiles()

	function render_files_select($ID, $type)
	{
// shows the files connected to a given image
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$folderid = $ID;
		$ID = $misc->make_db_extra_safe($ID);
		if ($type == 'listing') {
			$file_upload_path = $config['listings_file_upload_path'];
			$file_view_path = $config['listings_view_file_path'];
			$sqltype = 'listings';
		} else {
			$file_upload_path = $config['users_file_upload_path'];
			$file_view_path = $config['users_view_file_path'];
			$sqltype = 'user';
		}
//Declare an empty display variable to hold all output from function.
		$display = '';
		$optionvalue = '';
		$sql = "SELECT " . $type . "sfiles_id, " . $type . "sfiles_caption, " . $type . "sfiles_description, " . $type . "sfiles_file_name FROM " . $config['table_prefix'] . "" . $type . "sfiles WHERE (" . $sqltype . "db_id = $ID) ORDER BY " . $type . "sfiles_rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_files = $recordSet->RecordCount();
		if ($num_files >= 1) {
//ENTER OPENING FORM TAG, ACTION AND HIDDEN FORM FIELDS
			$display .= '<form action="index.php?action=create_download" method="POST">';
			$display .= '<input type="hidden" name="ID" value="' . $folderid . '" />';
			$display .= '<input type="hidden" name="type" value="' . $type . '" />';
			$display .= '<select name="file_id">';
			while (!$recordSet->EOF) {
					$optionvalue = '';
					$file_caption = $misc->make_db_unsafe ($recordSet->fields[$type . 'sfiles_caption']);
					$file_filename = $misc->make_db_unsafe ($recordSet->fields[$type . 'sfiles_file_name']);
					$file_id = $misc->make_db_unsafe ($recordSet->fields[$type . 'sfiles_id']);
					$file_url = $file_view_path.'/'.$folderid.'/'.$file_filename;
					$file_download_url = 'index.php?action=create_download&amp;ID='.$folderid.'&amp;file_id='.$file_id.'&amp;type='.$type;
					$file_description = urldecode($misc->make_db_unsafe ($recordSet->fields[$type . 'sfiles_description']));
					$file_icon_height = $config["file_icon_height"];
					$file_icon_width = $config["file_icon_width"];
					if ($file_filename != "" && file_exists("$file_upload_path/$folderid/$file_filename")) {
						$ext = substr(strrchr($file_filename, '.'), 1);
						$filesize = filesize($file_upload_path.'/'.$folderid.'/'.$file_filename);
						if ($caption != '') {
							$alt = $caption;
						} else {
							$alt = $thumb_file_name;
						}
					$iconpath = $config["file_icons_path"] . '/' . $ext . '.png';
					if (file_exists($iconpath)) {
						$file_icon = $config["listings_view_file_icons_path"] . '/' . $ext . '.png';
					} else {
						$file_icon = $config["listings_view_file_icons_path"] . '/default.png';
					}
					$file_filesize = $this->bytesize($filesize);
					}
				if ($config['file_display_option'] == 'filename') {
					$optionvalue .= $file_filename;
					if ($config["file_display_size"] == '1') {
						$optionvalue .= ' - ' . $this->bytesize($filesize);
					}
				} elseif ($config['file_display_option'] == 'caption') {
					if ($file_caption != '') {
						$optionvalue .= $file_caption;
					} else {
						$optionvalue .= $file_filename;
					}
					if ($config["file_display_size"] == '1') {
						$optionvalue .= ' - ' . $this->bytesize($filesize);
					}
				} elseif ($config['file_display_option'] == 'both') {
					if ($file_caption != '') {
						$optionvalue .= $file_caption . ' - ';
					}
					$optionvalue .= $file_filename;
					if ($config["file_display_size"] == '1') {
						$optionvalue .= ' - ' . $this->bytesize($filesize);
					}
				}
				//ENTER SINGLE FORM OPTION HERE
				$display .= '<option value="' . $file_id . '">' . $optionvalue . '</option>';
				$recordSet->MoveNext();
			}
//END while (!$recordSet->EOF)
//ENTER SUBMIT BUTTONS AND CLOSING FORM TAG HERE
			$display .= '</select>';
			$display .= '<input type="button" value="' . $lang['download_file'] . '" onclick="submit();" />';
			$display .= '</form>';
		}
		return $display;
	} // end function renderListingsfiles

	function bytesize($bytes) 
    {
    $size = $bytes / 1024;
    if($size < 1024)
        {
        $size = number_format($size, 2);
        $size .= ' KB';
        } 
    else 
        {
        if($size / 1024 < 1024) 
            {
            $size = number_format($size / 1024, 2);
            $size .= ' MB';
            } 
        else if ($size / 1024 / 1024 < 1024)  
            {
            $size = number_format($size / 1024 / 1024, 2);
            $size .= ' GB';
            } 
        }
    return $size;
    } 

	function render_templated_files($ID, $type, $template)
	{
		global $conn, $lang, $config, $db_type;
//Load the Core Template class and the Misc Class
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$page = new page_user();
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$folderid = $ID;
		$ID = $misc->make_db_extra_safe($ID);
//Declare an empty display variable to hold all output from function.
		$display = '';
		if ($type == 'listing') {
			$file_upload_path = $config['listings_file_upload_path'];
			$file_view_path = $config['listings_view_file_path'];
			$sqltype = 'listings';
		} else {
			$file_upload_path = $config['users_file_upload_path'];
			$file_view_path = $config['users_view_file_path'];
			$sqltype = 'user';
		}
		$sql = "SELECT " . $type . "sfiles_id, " . $type . "sfiles_caption, " . $type . "sfiles_description, " . $type . "sfiles_file_name FROM " . $config['table_prefix'] . "" . $type . "sfiles WHERE (" . $sqltype . "db_id = $ID) ORDER BY " . $type . "sfiles_rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_files = $recordSet->RecordCount();
		if ($num_files >= 1) 
			{
//Load the File Template specified by the calling tag unless a template was specified in the calling template tag.
			$page->load_page($config['template_path'] . '/files_' . $type . '_' . $template . '.html');
// Determine if the template uses rows.
// First item in array is the row conent second item is the number of block per block row
			$file_template_row = $page->get_template_section_row('file_block_row');
			if (is_array($file_template_row)) {
				$row = $file_template_row[0];
				$col_count = $file_template_row[1];
				$uses_rows = true;
				$x = 1;
//Create an empty array to hold the row contents
				$new_row_data = array();
			} else {
				$uses_rows = false;
			}
			$file_template_section = '';
			while (!$recordSet->EOF) {
				if ($uses_rows == true && $x > $col_count) {
//We are at then end of a row. Save the template section as a new row.
					$new_row_data[] = $page->replace_template_section('file_block', $file_template_section, $row);
//$new_row_data[] = $file_template_section;
					$file_template_section = $page->get_template_section('file_block');
					$x=1;
				} else {
					$file_template_section .= $page->get_template_section('file_block');
				}
					$file_caption = $misc->make_db_unsafe ($recordSet->fields[$type . 'sfiles_caption']);
					$file_filename = $misc->make_db_unsafe ($recordSet->fields[$type . 'sfiles_file_name']);
					$file_id = $misc->make_db_unsafe ($recordSet->fields[$type . 'sfiles_id']);
					$file_url = $file_view_path.'/'.$folderid.'/'.$file_filename;
					$file_download_url = 'index.php?action=create_download&amp;ID='.$folderid.'&amp;file_id='.$file_id.'&amp;type='.$type;
					$file_description = urldecode($misc->make_db_unsafe ($recordSet->fields[$type . 'sfiles_description']));
					$file_icon_height = $config["file_icon_height"];
					$file_icon_width = $config["file_icon_width"];
					if ($file_filename != "" && file_exists("$file_upload_path/$folderid/$file_filename")) {
						$ext = substr(strrchr($file_filename, '.'), 1);
						$filesize = filesize($file_upload_path.'/'.$folderid.'/'.$file_filename);
						if ($caption != '') {
							$alt = $caption;
						} else {
							$alt = $thumb_file_name;
						}
					$iconpath = $config["file_icons_path"] . '/' . $ext . '.png';
					if (file_exists($iconpath)) {
						$file_icon = $config["listings_view_file_icons_path"] . '/' . $ext . '.png';
					} else {
						$file_icon = $config["listings_view_file_icons_path"] . '/default.png';
					}
					$file_filesize = $this->bytesize($filesize);
					}
				$file_template_section = $page->parse_template_section($file_template_section, 'file_url', $file_url);
				$file_template_section = $page->parse_template_section($file_template_section, 'file_download_url', $file_download_url);
				$file_template_section = $page->parse_template_section($file_template_section, 'file_filename', $file_filename);
				$file_template_section = $page->parse_template_section($file_template_section, 'file_caption', $file_caption);
				$file_template_section = $page->parse_template_section($file_template_section, 'file_description', $file_description);
				$file_template_section = $page->parse_template_section($file_template_section, 'file_icon', $file_icon);
				$file_template_section = $page->parse_template_section($file_template_section, 'file_icon_height', $file_icon_height);
				$file_template_section = $page->parse_template_section($file_template_section, 'file_icon_width', $file_icon_width);
				$file_template_section = $page->parse_template_section($file_template_section, 'file_filesize', $file_filesize);
				$recordSet->MoveNext();
				if ($uses_rows == true) {
					$x++;
				}
			}
//END while (!$recordSet->EOF)
			if ($uses_rows == true) {
				$file_template_section = $page->cleanup_template_block('file', $file_template_section);
				$new_row_data[] = $page->replace_template_section('file_block', $file_template_section,$row);
				$replace_row = '';
				foreach ($new_row_data as $rows){
					$replace_row .= $rows;
				}
				$page->replace_template_section_row('file_block_row', $replace_row);
			} else {
				$page->replace_template_section('file_block', $file_template_section);
			}
			$page->replace_permission_tags();
			$display .= $page->return_page();
		}
		return $display;
	} // End Render Templated Listing Files

//Create Download Function to prevent direct links to files
	function create_download($ID, $file_id, $type)
	{
		global $config, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();

		$folderid = $ID;
		$ID = $misc->make_db_extra_safe($ID);
		$fileID = $misc->make_db_extra_safe($file_id);
		if ($type == 'listing') {
			$file_upload_path = $config['listings_file_upload_path'];
			$file_view_path = $config['listings_view_file_path'];
			$sqltype = 'listings';
		} else {
			$file_upload_path = $config['users_file_upload_path'];
			$file_view_path = $config['users_view_file_path'];
			$sqltype = 'user';
		}
		$sql = "SELECT DISTINCT " . $type . "sfiles_file_name FROM " . $config['table_prefix'] . "" . $type . "sfiles WHERE (" . $sqltype . "db_id = $ID) AND (" . $type . "sfiles_id = " .$fileID . ") ORDER BY " . $type . "sfiles_rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		while (!$recordSet->EOF) {
				$file_filename = $misc->make_db_unsafe ($recordSet->fields[$type . 'sfiles_file_name']);
					$recordSet->MoveNext();

		}
		$fullPath = $file_upload_path . '/'.$folderid.'/' . $file_filename;
		if ($fd = fopen ($fullPath, "r")) {
			$fsize = filesize($fullPath);
			$path_parts = pathinfo($fullPath);
				header("Content-type: application/octet-stream");
				header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\"");
				header("Content-length: $fsize");
				header("Cache-control: private"); //use this to open files directly
			while(!feof($fd)) {
				$buffer = fread($fd, 2048);
				echo $buffer;
			}
		}
		fclose ($fd);
	}// end create_download

} // End FileHandler Class
?>
