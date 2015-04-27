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
class image_handler {
	var $debug = false;
	function exec_curl($array)
	{
		$array = @explode("?", $array);
		$link = curl_init();
		curl_setopt($link, CURLOPT_URL, $array[0]);
		curl_setopt($link, CURLOPT_VERBOSE, 0);
		curl_setopt($link, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($link, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($link, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($link, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($link, CURLOPT_MAXREDIRS, 6);
		curl_setopt($link, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($link, CURLOPT_TIMEOUT, 60);
		$results = curl_exec($link);
		if (curl_errno($link) > 0) {
			curl_close($link);
			return false;
		}
		curl_close($link);

		return $results;
	}
	function save_remote_file($url, $id, $type = 'listings', $owner, $replace_image = false, $append_file_name = false, $caption='', $description='')
	{
		error_reporting(E_ALL ^ E_WARNING);
		// deals with incoming uploads
		global $config, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// Get file name from URL and remove any bad filename chars.
		$url_parts = explode('/', $url);
		$num = count($url_parts)-1;
		$file_name = $url_parts[$num];
		$badchararray = array(" ", "'", "\"", "$", "&", "%", "-", "#", "^", "*", "(", ")", "~", "@", "/", "?", "=");
		$file_name = str_replace($badchararray, "", $file_name);
		$file_name = stripslashes(strtolower($file_name));
		if ($append_file_name == true) {
			$save_name = $id . '_' . $file_name;
		}else {
			$save_name = $file_name;
		}
		// Now that we know the filename check if the file exist. If it does do not redownload the image.
		if (file_exists($config["listings_upload_path"] . '/' . $save_name) && $replace_image == false) {
			$thumb_name = 'thumb_' . $save_name;
			// Get Max Image Rank
			$sql = "SELECT MAX(listingsimages_rank) AS max_rank FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsdb_id = '$id')";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$rank = $recordSet->fields['max_rank'];
			$rank++;
			$caption = $misc->make_db_safe($caption);
			$description = $misc->make_db_safe($description);
			$sql = "INSERT INTO " . $config['table_prefix'] . "listingsimages (listingsdb_id, userdb_id, listingsimages_file_name, listingsimages_thumb_file_name,listingsimages_rank,listingsimages_caption,listingsimages_description) VALUES ('$id', '$owner', '$save_name', '$thumb_name',$rank,$caption,$description)";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			return true;
		}
		// Image doesn't exist locally yet, so get it.
		if (!ini_get('allow_url_fopen')) {
			$imagecontent = $this->exec_curl($url);
		}else {
			$imagecontent = file_get_contents(str_replace(' ','%20',$url));
		}
		// Check to see if image existed on the remote server, if not skip it.
		if ($imagecontent === false) {
			return false;
		}
		// Ok we have a good file, so now lets save it.
		$handle = fopen($config["listings_upload_path"] . '/' . $save_name, "wb");
		fwrite($handle, $imagecontent);
		fclose($handle);
		// Verify File type
		$extension = substr(strrchr($save_name, "."), 1);
		if (!in_array($extension, explode(',', $config['allowed_upload_extensions']))) {
			unlink($config["listings_upload_path"] . '/' . $save_name);
			return false;
		}
		if (!function_exists('exif_imagetype')) {
			function exif_imagetype($filename) {
				if ((list($width, $height, $type, $attr) = getimagesize($filename)) !== false ) {
					return $type;
				}
				return false;
			}
		}
		if (exif_imagetype($config["listings_upload_path"] . '/' . $save_name) === false) {
			unlink($config["listings_upload_path"] . '/' . $save_name);
			return false;
		}
		// Image is saved, check file widht/height.
		// $imagedata = GetImageSize($config["listings_upload_path"].'/'.$save_name);
		// $imagewidth = $imagedata[0];
		// $imageheight = $imagedata[1];
		$thumb_name = $save_name; // by default -- no difference... unless...
		// Make the thumbnial image.
		if ($config['make_thumbnail'] == '1') {
			// if the option to make a thumbnail is activated...
			$make_thumb = 'make_thumb_' . $config['thumbnail_prog'];
			$thumb_name = $this->$make_thumb ($save_name, $config['listings_upload_path']);
		} // end if $config[make_thumbnail] === "1"
		// Get highest image rank from current db.
		$sql = "SELECT MAX(listingsimages_rank) AS max_rank FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsdb_id = '$id')";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$rank = $recordSet->fields['max_rank'];
		$rank++;
		// Insert image with next highest rank.
		$sql = "INSERT INTO " . $config['table_prefix'] . "listingsimages (listingsdb_id, userdb_id, listingsimages_file_name, listingsimages_thumb_file_name,listingsimages_rank) VALUES ('$id', '$owner', '$save_name', '$thumb_name',$rank)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		return true;
	}
	function renderUserImages($user)
	{
		// grabs the listings for a given user
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$user = $misc->make_db_safe($user);
		// grab the images
		$sql = "SELECT userimages_id, userimages_caption, userimages_thumb_file_name FROM " . $config['table_prefix'] . "userimages WHERE userdb_id = $user ORDER BY userimages_rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_images = $recordSet->RecordCount();
		if ($num_images > 0) {
			while (!$recordSet->EOF) {
				$caption = $misc->make_db_unsafe ($recordSet->fields['userimages_caption']);
				$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['userimages_thumb_file_name']);
				// $file_name = $misc->make_db_unsafe ($recordSet->fields['userimages_file_name']);
				$imageID = $misc->make_db_unsafe ($recordSet->fields['userimages_id']);
				// gotta grab the image size
				$imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				$max_width = $config['thumbnail_width'];
				$max_height = $config['thumbnail_height'];
				$resize_by = $config['resize_thumb_by'];
				$shrinkage = 1;
				if (($max_width == $imagewidth) || ($max_height == $imageheight)) {
					$displaywidth = $imagewidth;
					$displayheight = $imageheight;
				} else {
					if ($resize_by == 'width') {
						$shrinkage = $imagewidth / $max_width;
						$displaywidth = $max_width;
						$displayheight = round($imageheight / $shrinkage);
					} elseif ($resize_by == 'height') {
						$shrinkage = $imageheight / $max_height;
						$displayheight = $max_height;
						$displaywidth = round($imagewidth / $shrinkage);
					} elseif ($resize_by == 'both') {
						$displayheight = $max_height;
						$displaywidth = $max_width;
					}
				}
				$display .= "<a href=\"index.php?action=view_user_image&amp;image_id=$imageID\"> ";
				$display .= "<img src=\"$config[user_view_images_path]/$thumb_file_name\" height=\"$displayheight\" width=\"$displaywidth\"></a><br /> ";
				$display .= "<b>$caption</b><br /><br />";
				$recordSet->MoveNext();
			}
		}else {
			$display .= "<img src=\"images/nophoto.gif\" alt=\"\" /><br />";
		} // end ($num_images > 0)
		return $display;
	} // end function renderUserImages
	function get_image_caption()
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$sql_imageID = $misc->make_db_safe($_GET['image_id']);
		$sql = "SELECT listingsimages_caption FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsimages_id = $sql_imageID)";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$caption = $misc->make_db_unsafe ($recordSet->fields['listingsimages_caption']);
		return $caption;
	}
	function view_image($type)
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		require_once($config['basepath'] . '/include/listing.inc.php');
		$misc = new misc();
		$display = '';
		if (!isset($_GET['image_id'])) {
			return $lang['image_not_found'];
		}
		$sql_imageID = $misc->make_db_safe($_GET['image_id']);
		if ($type == "listing") {
			// get the image data
			$sql = "SELECT listingsimages_caption, listingsimages_file_name, listingsimages_description, listingsdb_id FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsimages_id = $sql_imageID)";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			} while (!$recordSet->EOF) {
				$caption = $misc->make_db_unsafe ($recordSet->fields['listingsimages_caption']);
				$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
				$description = $misc->make_db_unsafe ($recordSet->fields['listingsimages_description']);
				$listing_id = $misc->make_db_unsafe ($recordSet->fields['listingsdb_id']);
				$recordSet->MoveNext();
			}
			$display .= '<div class="view_image">';
			$display .= '<span class="image_caption">';
			if ($caption != "") {
				$display .= "$caption - ";
			}
			//SEO Friendly Links
			$Title = listing_pages::get_title($listing_id);
			if ($config['url_style'] == '1') {
				$url = '<a href="index.php?action=listingview&amp;listingID=' . $listing_id . '">';
			}else {
				$url_title = str_replace("/", "", $Title);
				$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
				$url = '<a href="listing-' . misc::urlencode_to_sef($url_title) . '-' . $listing_id . '.html">';
			}
			$display .= $url.$lang['return_to_listing'].'</a></span><br />';
			$display .= '<img src="' . $config['listings_view_images_path'] . '/' . $file_name . '" alt="' . $caption . '"  />';
			$display .= '<br />';
			$display .= $description ;
			$display .= '</div>';
		} // end if ($type == "listing")
		elseif ($type == "userimage") {
			// get the image data
			$sql = "SELECT userimages_caption, userimages_file_name, userimages_description, userdb_id FROM " . $config['table_prefix'] . "userimages WHERE (userimages_id = $sql_imageID)";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			} while (!$recordSet->EOF) {
				$caption = $misc->make_db_unsafe ($recordSet->fields['userimages_caption']);
				$file_name = $misc->make_db_unsafe ($recordSet->fields['userimages_file_name']);
				$description = $misc->make_db_unsafe ($recordSet->fields['userimages_description']);
				$user_id = $recordSet->fields['userdb_id'];
				$recordSet->MoveNext();
			}
			$display .= '<table class="form_" align="center">';
			$display .= '<tr>';
			$display .= '	<td class="row_main">';
			$display .= '		<h3>';
			if ($caption != "") {
				$display .= "$caption - ";
			}
			$display .= '<a href="index.php?action=view_user&amp;user=' . $user_id . '">' . $lang['return_to_user'] . '</a></h3>';
			$display .= '		<center>';
			$display .= '		<img src="' . $config['user_view_images_path'] . '/' . $file_name . '" alt="' . $caption . '" border="1">';
			$display .= '		</center>';
			$display .= '		<br />';
			$display .= $description;
			$display .= '	</td>';
			$display .= '</tr>';
			$display .= '</table>';
		} // end if ($type == "listing")
		return $display;
	}

	function edit_listing_images()
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
		if ($_POST['action'] == "update_pic") {
			$count = 0;
			$num_fields = count($_POST['pic']);
			$sql_edit = $misc->make_db_safe($_POST['edit']);
			while ($count < $num_fields) {
				$sql_caption = $misc->make_db_safe($_POST['caption'][$count]);
				$sql_description = $misc->make_db_safe($_POST['description'][$count]);
				$sql_rank = $misc->make_db_safe($_POST['rank'][$count]);
				$sql_pic = $misc->make_db_safe($_POST['pic'][$count]);

				if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
					$sql = "UPDATE " . $config['table_prefix'] . "listingsimages SET listingsimages_caption = $sql_caption, listingsimages_description = $sql_description, listingsimages_rank = $sql_rank WHERE ((listingsdb_id = $sql_edit) AND (listingsimages_file_name = $sql_pic))";
				}else {
					$sql = "UPDATE " . $config['table_prefix'] . "listingsimages SET listingsimages_caption = $sql_caption, listingsimages_description = $sql_description, listingsimages_rank = $sql_rank WHERE ((listingsdb_id = $sql_edit) AND (listingsimages_file_name = $sql_pic) AND (userdb_id = $_SESSION[userID]))";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$count++;
			}
			$display .= '<p>'.$lang['images_update'].'</p>';
			$misc->log_action ($lang['log_updated_listing_image'] . $_POST['edit']);
		}
		if(isset($_GET['delete_all']) && $_GET['delete_all']=='yes'){
			$sql_edit = $misc->make_db_safe($_GET['edit']);
			if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$sql = "SELECT listingsimages_file_name, listingsimages_thumb_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE listingsdb_id = $sql_edit";
			}else {
				$sql = "SELECT listingsimages_file_name, listingsimages_thumb_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE listingsdb_id = $sql_edit AND userdb_id = $_SESSION[userID]";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			} while (!$recordSet->EOF) {
				$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_thumb_file_name']);
				$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
				if (!unlink("$config[listings_upload_path]/$file_name")) die("$lang[alert_site_admin]");
				if ($file_name != $thumb_file_name) {
					if (!unlink("$config[listings_upload_path]/$thumb_file_name")) die("$lang[alert_site_admin]");
				}
				$misc->log_action ("$lang[log_deleted_listing_image] $file_name");
				$display .= "<p>$lang[image] '$file_name' $lang[has_been_deleted]</p>";
				$recordSet->MoveNext();
			} // end while
			// delete from the db
			if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$sql = "DELETE FROM " . $config['table_prefix'] . "listingsimages WHERE listingsdb_id = $sql_edit";
			}else {
				$sql = "DELETE FROM " . $config['table_prefix'] . "listingsimages WHERE listingsdb_id = $sql_edit AND userdb_id = '$_SESSION[userID]'";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
		}
		if (isset($_GET['delete'])) {
			// get the data for the pic being deleted
			$sql_pic_id = $misc->make_db_safe($_GET['delete']);
			$sql_edit = $misc->make_db_safe($_GET['edit']);
			if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$sql = "SELECT listingsimages_file_name, listingsimages_thumb_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE ((listingsdb_id = $sql_edit) AND (listingsimages_id = $sql_pic_id))";
			}else {
				$sql = "SELECT listingsimages_file_name, listingsimages_thumb_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE ((listingsdb_id = $sql_edit) AND (listingsimages_id = $sql_pic_id) AND (userdb_id = $_SESSION[userID]))";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			} while (!$recordSet->EOF) {
				$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_thumb_file_name']);
				$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
				$recordSet->MoveNext();
			} // end while
			// delete from the db
			if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$sql = "DELETE FROM " . $config['table_prefix'] . "listingsimages WHERE ((listingsdb_id = $sql_edit) AND (listingsimages_file_name = '$file_name'))";
			}else {
				$sql = "DELETE FROM " . $config['table_prefix'] . "listingsimages WHERE ((listingsdb_id = $sql_edit) AND (listingsimages_file_name = '$file_name') AND (userdb_id = '$_SESSION[userID]'))";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			// delete the files themselves
			// on widows, required php 4.11 or better (I think)
			if (!unlink("$config[listings_upload_path]/$file_name")) die("$lang[alert_site_admin]");
			if ($file_name != $thumb_file_name) {
				if (!unlink("$config[listings_upload_path]/$thumb_file_name")) die("$lang[alert_site_admin]");
			}
			$misc->log_action ("$lang[log_deleted_listing_image] $file_name");
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
				$display .= $this->handleUpload("listings", $edit, $owner);
			}else {
				$display .= $this->handleUpload("listings", $edit, $_SESSION['userID']);
			}
		} // end if $action == "upload"
		if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
			$sql = "SELECT listingsimages_id, listingsimages_caption, listingsimages_file_name, listingsimages_thumb_file_name, listingsimages_description, listingsimages_rank FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsdb_id = $sql_edit) ORDER BY listingsimages_rank";
		}else {
			$sql = "SELECT listingsimages_id, listingsimages_caption, listingsimages_file_name, listingsimages_thumb_file_name, listingsimages_description, listingsimages_rank FROM " . $config['table_prefix'] . "listingsimages WHERE ((listingsdb_id = $sql_edit) AND (userdb_id = '$_SESSION[userID]')) ORDER BY listingsimages_rank";
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_images = $recordSet->RecordCount();
		$avaliable_images = $config["max_listings_uploads"] - $num_images;
		$x = 0;
		if ($num_images < $config['max_listings_uploads']) {
			$display .= '<table border="0" cellspacing="0" cellpadding="0">';
			$display .= '<tr>';
			$display .= '<td colspan="2">';
			$display .= '<h3>' . $lang['upload_a_picture'] . '</h3>';
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td width="150">&nbsp;</td>';
			$display .= '<td>';
			$display .= '<form enctype="multipart/form-data" action="index.php?action=edit_listing_images" method="post">';
			$display .= '<input type="hidden" name="action" value="upload" />';
			$display .= '<input type="hidden" name="edit" value="' . $edit . '" />';
			$display .= '<input type="hidden" name="MAX_FILE_SIZE" value="' . $config['max_listings_upload_size'] . '" />';
			while ($x < $avaliable_images) {
				$display .= '<b>' . $lang['upload_send_this_file'] . ': </b><input name="userfile[]" type="file" /><br />';
				$x++;
			}
			$display .= '<input type="submit" value="' . $lang['upload_send_file'] . '" />';
			$display .= '</form>';
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '</table>';
		} // end if $num_images <= $config[max_user_uploads]
		$display .= '<table class="image_upload">';
		$display .= '<tr>';
		$display .= '<td colspan="2">';
		$display .= '<h3>' . $lang['edit_images'] . ' -- ';
		if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
			$display .= "<a href=\"index.php?action=edit_listings&amp;edit=$edit\">";
		}else {
			$display .= "<a href=\"index.php?action=edit_my_listings&amp;edit=$edit\">";
		}
		$display .= $lang['return_to_editing_listing'];
		$display .= '</a></h3></td></tr>';
		$display .= '</table>';
		$count = 0;
		$display .= '<form action="index.php?action=edit_listing_images" method="post">';
		$display .= '<table class="image_upload">';
		while (!$recordSet->EOF) {
			$pic_id = $recordSet->fields['listingsimages_id'];
			$rank = $recordSet->fields['listingsimages_rank'];
			$caption = $misc->make_db_unsafe ($recordSet->fields['listingsimages_caption']);
			$description = $misc->make_db_unsafe ($recordSet->fields['listingsimages_description']);
			$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_thumb_file_name']);
			$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
			// gotta grab the image size
			$imagedata = GetImageSize("$config[listings_upload_path]/$file_name");
			$imagewidth = $imagedata[0];
			$imageheight = $imagedata[1];
			$filesize = filesize("$config[listings_upload_path]/$file_name");
			$filesize = $filesize / 1000; // to get k
			// now grab the thumbnail data
			$thumb_imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
			$thumb_imagewidth = $thumb_imagedata[0];
			$thumb_imageheight = $thumb_imagedata[1];
			$thumb_filesize = filesize("$config[listings_upload_path]/$thumb_file_name");
			$thumb_filesize = $thumb_filesize / 1000;
			$thumb_max_width = $config['thumbnail_width'];
			$thumb_max_height = $config['thumbnail_height'];
			$resize_by = $config['resize_thumb_by'];
			$shrinkage = 1;

			if (($thumb_max_width == $thumb_imagewidth) || ($thumb_max_height == $thumb_imageheight)) {
				$thumb_displaywidth = $thumb_imagewidth;
				$thumb_displayheight = $thumb_imageheight;
			} else {
				if ($resize_by == 'width') {
					$shrinkage = $thumb_imagewidth / $thumb_max_width;
					$thumb_displaywidth = $thumb_max_width;
					$thumb_displayheight = round($thumb_imageheight / $shrinkage);
				} elseif ($resize_by == 'height') {
					$shrinkage = $thumb_imageheight / $thumb_max_height;
					$thumb_displayheight = $thumb_max_height;
					$thumb_displaywidth = round($thumb_imagewidth / $shrinkage);
				} elseif ($resize_by == 'both') {
					$thumb_displayheight = $thumb_max_height;
					$thumb_displaywidth = $thumb_max_width;
				}
			}
			// alternate the colors
			if ($count == 0) {
				$count = 1;
			}else {
				$count = 0;
			}
			$display .= '<tr class="image_row_'.$count.'"><td valign="top" class="image_row_'.$count.'" width="150"><b>'.$file_name.'</b><br />'.$lang['width'].'='.$imagewidth.'<br />'.$lang['height'].'='.$imageheight.'<br />'.$lang['size'].'='.$filesize.' k<br />';
			$display .= '<br />'.$lang['thumbnail'].':<br />';
			$display .= '<img src="'.$config['listings_view_images_path'].'/'.$thumb_file_name.'" height="'.$thumb_displayheight.'" width="'.$thumb_displaywidth.'" border="1" alt="" />';
			$display .= '<br />'.$lang['width'].'='.$thumb_imagewidth.'<br />'.$lang['height'].'='.$thumb_imageheight.'<br />'.$lang['size'].'='.$thumb_filesize.' k<br />';
			$display .= '<br /><a href="index.php?action=edit_listing_images&amp;delete='.$pic_id.'&amp;edit='.$edit.'" onclick="return confirmDelete()">'.$lang['delete'].'</a>';
			$display .= '</td><td align="center" class="image_row_'.$count.'"><img src="'.$config['listings_view_images_path'].'/'.$file_name.'" border="1" alt="" />';
			$display .= '</tr><tr><td align="center" class="image_row_'.$count.'" colspan="2">';
			$display .= '<input type="hidden" name="pic[]" value="'.$file_name.'" />';
			$display .= '<table border="0">';
			$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['admin_template_editor_field_rank'].':</b></td><td align="left"><input type="text" name="rank[]" value="'.$rank.'" /><div class="small">'.$lang['upload_rank_explanation'].'</div></td></tr>';
			$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_caption'].':</b></td><td align="left"><input type="text" name="caption[]" value="'.$caption.'" /></td></tr>';
			$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_description'].':</b><td align="left"><textarea name="description[]" rows="6" cols="40">'.$description.'</textarea></td></tr>';
			$display .= '</table>';
			$display .= '</td></tr><tr><td colspan="2"><hr /></td></tr>';
			$recordSet->MoveNext();
		} // end while
		$display .= '<tr><td align="center" class="image_row_'.$count.'" colspan="2"><input type="submit" value="'.$lang['update'].'" />';
		$display .= '</td></tr>';
		$display .= '</table>';
		$display .= '<input type="hidden" name="edit" value="'.$edit.'" />';
		$display .= '<input type="hidden" name="action" value="update_pic" />';
		$display .= '</form>';
		$display .= '<div style="width:100%"><center>';
		$display .= '<form action="index.php?action=edit_listing_images&delete_all=yes&edit='.$edit.'" method="post" onSubmit="return confirmDelete(\''.$lang['confirm_delete_all_images'].'\')">';
		$display .= '<input class="redtext" type="submit" value="'.$lang['delete_all_images'].'">';
		$display .= '</center></div>';
		return $display;
	}
	function edit_user_images()
	{
		global $config, $lang, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		if (isset($_GET['edit']) && $_GET['edit'] != '')
		{
			$_POST['edit'] = $_GET['edit'];
		}
		$edit = $_POST['edit'];
		if (!isset($_POST['action'])) {
			$_POST['action'] = '';
		}
		if ($_POST['action'] == "update_pic") {
			$count = 0;
			$num_fields = count($_POST['pic']);
			$sql_edit = $misc->make_db_safe($_POST['edit']);
			while ($count < $num_fields) {
				$sql_caption = $misc->make_db_safe($_POST['caption'][$count]);
				$sql_description = $misc->make_db_safe($_POST['description'][$count]);
				$sql_rank = $misc->make_db_safe($_POST['rank'][$count]);
				$sql_pic = $misc->make_db_safe($_POST['pic'][$count]);

				if ($_SESSION['edit_all_users'] == "yes" || $_SESSION['admin_privs'] == "yes") {
					$sql = "UPDATE " . $config['table_prefix'] . "userimages SET userimages_caption = $sql_caption, userimages_description = $sql_description, userimages_rank = $sql_rank WHERE ((userdb_id = $sql_edit) AND (userimages_file_name = $sql_pic))";
				} else {
					$sql = "UPDATE " . $config['table_prefix'] . "userimages SET userimages_caption = $sql_caption, userimages_description = $sql_description, userimages_rank = $sql_rank WHERE ((userimages_file_name = $sql_pic) AND (userdb_id = '$_SESSION[userID] '))";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$count++;
			}
			$misc->log_action ("$lang[log_updated_user_image] $edit");
			$display .= '<p>'.$lang['images_update'].'</p>';
		}
		if (isset($_GET['delete'])) {
			// get the data for the pic being deleted
			$sql_pic_id = $misc->make_db_safe($_GET['delete']);
			$sql_edit = $misc->make_db_safe($_GET['edit']);
			if ($_SESSION['edit_all_users'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$sql = "SELECT userimages_file_name, userimages_thumb_file_name FROM " . $config['table_prefix'] . "userimages WHERE ((userdb_id = $sql_edit) AND (userimages_id = $sql_pic_id))";
			} else {
				$sql = "SELECT userimages_file_name, userimages_thumb_file_name FROM " . $config['table_prefix'] . "userimages WHERE ((userimages_id = $sql_pic_id) AND (userdb_id = '$_SESSION[userID]'))";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			} while (!$recordSet->EOF) {
				$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['userimages_thumb_file_name']);
				$file_name = $misc->make_db_unsafe ($recordSet->fields['userimages_file_name']);
				if ($this->debug) {
					$display .= 'File Name: ' . $file_name . '<br />';
					$display .= 'Thumbnail Name: ' . $thumb_file_name . '<br />';
				}
				$recordSet->MoveNext();
			} // end while
			// delete from the db
			if ($_SESSION['edit_all_users'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$sql = "DELETE FROM " . $config['table_prefix'] . "userimages WHERE ((userdb_id  = '$_POST[edit]') AND (userimages_file_name = '$file_name'))";
			}else {
				$sql = "DELETE FROM " . $config['table_prefix'] . "userimages WHERE ((userimages_file_name = '$file_name') AND (userdb_id = '$_SESSION[userID]'))";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			// delete the files themselves
			// on widows, required php 4.11 or better (I think)
			if (!unlink("$config[user_upload_path]/$file_name")) {
				die("$lang[alert_site_admin]");
			}
			if ($thumb_file_name != $file_name) {
				// if (substr("$thumb_file_name", -3) == "jpg" || substr("$thumb_file_name", -3) == "png")
				// {
				if (!unlink("$config[user_upload_path]/$thumb_file_name")) {
					die("$lang[alert_site_admin]");
				}else {
					$display .= "<p>$lang[image] '$thumb_file_name' $lang[has_been_deleted]</p>";
				}
				// }
			}

			$misc->log_action ("$lang[log_deleted_user_image] $file_name");
			$display .= "<p>$lang[image] '$file_name' $lang[has_been_deleted]</p>";
		}
		if ($_POST['action'] == "upload") {
			if ($_SESSION['edit_all_users'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$display .= $this->handleUpload("user", '', $_POST['edit']);
			} else {
				$display .= $this->handleUpload("user", '', $_SESSION['userID']);
			}
		} // end if $action == "upload"
		if ($_SESSION['edit_all_users'] == "yes" || $_SESSION['admin_privs'] == "yes") {
			if (!isset($_POST['edit'])) {
				if (isset($_POST['edit'])) {
					$_POST['edit'] = $_POST['edit'];
				}else {
					$display .= 'Error in calling function. You forgot to pass the ID to edit';
				}
			}
			$sql = "SELECT userimages_id, userimages_caption, userimages_file_name, userimages_thumb_file_name, userimages_description, userimages_rank FROM " . $config['table_prefix'] . "userimages WHERE (userdb_id = '$_POST[edit]') ORDER BY userimages_rank";
		}else {
			$sql = "SELECT userimages_id, userimages_caption, userimages_file_name, userimages_thumb_file_name, userimages_description, userimages_rank FROM " . $config['table_prefix'] . "userimages WHERE ((userdb_id = '$_SESSION[userID]')) ORDER BY userimages_rank";
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_images = $recordSet->RecordCount();
		$avaliable_images = $config["max_user_uploads"] - $num_images;
		$x = 0;
		if ($num_images < $config['max_user_uploads']) {
			$display .= '<table border="0" cellspacing="0" cellpadding="0">';
			$display .= '<tr>';
			$display .= '<td colspan="2">';
			$display .= '<h3>' . $lang['upload_a_picture'] . '</h3>';
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td width="150">&nbsp;</td>';
			$display .= '<td>';
			$display .= '<form enctype="multipart/form-data" action="index.php?action=edit_user_images" method="post">';
			$display .= '<input type="hidden" name="action" value="upload" />';
			$display .= '<input type="hidden" name="edit" value="' . $_POST['edit'] . '" />';
			$display .= '<input type="hidden" name="MAX_FILE_SIZE" value="' . $config['max_user_upload_size'] . '" />';
			$display .= '<input type="hidden" name="caption" value=" " />';
			while ($x < $avaliable_images) {
				$display .= '<strong>' . $lang['upload_send_this_file'] . ': </strong><input name="userfile[]" type="file" /><br />';
				$x++;
			}
			$display .= '<input type="submit" name="upload_image" value="' . $lang['upload_send_file'] . '" />';
			$display .= '</form>';
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '</table>';
		} // end if $num_images <= $config[max_user_uploads]
		$display .= ' <table class="admin_main">';
		$display .= '<tr><td colspan="2" class="row_main"><form action="' . $config['baseurl'] . '/admin/index.php?action=user_manager&amp;edit=' . $_POST['edit'] . '" method="post" name="editMyUser"><h3>' . $lang['edit_images'] . ' --';
		$display .= '<a href="javascript:document.editMyUser.submit()">';
		$display .= $lang['return_to_editing_account'];
		$display .= '</a></h3></form></td></tr>';
		$display .= '</table>';
		$count = 0;
		$display .= '<form action="index.php?action=edit_user_images" method="post">';
		$display .= '<table class="image_upload">';
		while (!$recordSet->EOF) {
			$pic_id = $recordSet->fields['userimages_id'];
			$rank = $recordSet->fields['userimages_rank'];
			$caption = $misc->make_db_unsafe ($recordSet->fields['userimages_caption']);
			$description = $misc->make_db_unsafe ($recordSet->fields['userimages_description']);
			$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['userimages_thumb_file_name']);
			$file_name = $misc->make_db_unsafe ($recordSet->fields['userimages_file_name']);
			// gotta grab the image size
			$imagedata = GetImageSize("$config[user_upload_path]/$file_name");
			$imagewidth = $imagedata[0];
			$imageheight = $imagedata[1];
			$filesize = filesize("$config[user_upload_path]/$file_name");
			$filesize = $filesize / 1000; // to get k
			// now grab the thumbnail data
			$thumb_imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
			$thumb_imagewidth = $thumb_imagedata[0];
			$thumb_imageheight = $thumb_imagedata[1];
			$thumb_filesize = filesize("$config[user_upload_path]/$thumb_file_name");
			$thumb_filesize = $thumb_filesize / 1000;
			$thumb_max_width = $config['thumbnail_width'];
			$thumb_max_height = $config['thumbnail_height'];
			$resize_by = $config['resize_thumb_by'];
			$shrinkage = 1;
			if (($thumb_max_width == $thumb_imagewidth) || ($thumb_max_height == $thumb_imageheight)) {
				$thumb_displaywidth = $thumb_imagewidth;
				$thumb_displayheight = $thumb_imageheight;
			} else {
				if ($resize_by == 'width') {
					$shrinkage = $thumb_imagewidth / $thumb_max_width;
					$thumb_displaywidth = $thumb_max_width;
					$thumb_displayheight = round($thumb_imageheight / $shrinkage);
				} elseif ($resize_by == 'height') {
					$shrinkage = $thumb_imageheight / $thumb_max_height;
					$thumb_displayheight = $thumb_max_height;
					$thumb_displaywidth = round($thumb_imagewidth / $shrinkage);
				} elseif ($resize_by == 'both') {
					$thumb_displayheight = $thumb_max_height;
					$thumb_displaywidth = $thumb_max_width;
				}
			}
			// now grab the thumbnail data
			$thumb_imagedata = GetImageSize("$config[user_upload_path]/$thumb_file_name");
			$thumb_imagewidth = $thumb_imagedata[0];
			$thumb_imageheight = $thumb_imagedata[1];
			$thumb_filesize = filesize("$config[user_upload_path]/$thumb_file_name");
			$thumb_filesize = $thumb_filesize / 1000;
			// alternate the colors
			if ($count == 0) {
				$count = $count + 1;
			}else {
				$count = 0;
			}
			$display .= '<tr class="image_row_'.$count.'"><td valign="top" class="image_row_'.$count.'" width="150"><b>'.$file_name.'</b><br />'.$lang['width'].'='.$imagewidth.'<br />'.$lang['height'].'='.$imageheight.'<br />'.$lang['size'].'='.$filesize.' k<br />';
			$display .= '<br />'.$lang['thumbnail'].':<br />';
			$display .= '<img src="'.$config['user_view_images_path'].'/'.$thumb_file_name.'" height="'.$thumb_displayheight.'" width="'.$thumb_displaywidth.'" border="1" alt="" />';
			$display .= '<br />'.$lang['width'].'='.$thumb_imagewidth.'<br />'.$lang['height'].'='.$thumb_imageheight.'<br />'.$lang['size'].'='.$thumb_filesize.' k<br />';
			$display .= '<br /><a href="index.php?action=edit_user_images&amp;delete='.$pic_id.'&amp;edit='.$edit.'" onclick="return confirmDelete()">'.$lang['delete'].'</a>';
			$display .= '</td><td align="center" class="image_row_'.$count.'"><img src="'.$config['user_view_images_path'].'/'.$file_name.'" border="1" alt="" />';
			$display .= '</tr><tr><td align="center" class="image_row_'.$count.'" colspan="2">';
			$display .= '<input type="hidden" name="pic[]" value="'.$file_name.'" />';
			$display .= '<table border="0">';
			$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['admin_template_editor_field_rank'].':</b></td><td align="left"><input type="text" name="rank[]" value="'.$rank.'" /><div class="small">'.$lang['upload_rank_explanation'].'</div></td></tr>';
			$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_caption'].':</b></td><td align="left"><input type="text" name="caption[]" value="'.$caption.'" /></td></tr>';
			$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_description'].':</b><td align="left"><textarea name="description[]" rows="6" cols="40">'.$description.'</textarea></td></tr>';
			$display .= '</table>';
			$display .= '</td></tr><tr><td colspan="2"><hr /></td></tr>';
			$recordSet->MoveNext();
		} // end while
		$display .= '<tr><td align="center" class="image_row_'.$count.'" colspan="2"><input type="submit" value="'.$lang['update'].'" />';
		$display .= '</table>';
		$display .= '<input type="hidden" name="edit" value="'.$edit.'" />';
		$display .= '<input type="hidden" name="action" value="update_pic" />';
		$display .= '</form>';

		return $display;
	}




	function renderListingsMainImageSlideShow($listingID)
	{
		// shows the images connected to a given image
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// grab the images
		$listingID = $misc->make_db_extra_safe($listingID);
		$display_method = $config["main_image_display_by"];
		$max_width = $config["main_image_width"];
		$max_height = $config["main_image_height"];
		$output = '';
		$sql = "SELECT listingsimages_id,listingsimages_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsdb_id = $listingID) ORDER BY listingsimages_rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_images = $recordSet->RecordCount();
		if ($num_images > 0) {
			$output .= '<script type="text/javascript" src="'
			. $config['baseurl'] . '/slideshow.js"></script>
					<script type="text/javascript"><!--
					SLIDES = new slideshow("SLIDES");
					';
			while (!$recordSet->EOF) {
				$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
				$imageID = $misc->make_db_unsafe ($recordSet->fields['listingsimages_id']);
				$imagedata = GetImageSize("$config[listings_upload_path]/$file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];

				if ($display_method == 'width') {
					$shrinkage = $max_width / $imagewidth;
					$displaywidth = $max_width;
					$displayheight = $imageheight * $shrinkage;
				} elseif ($display_method == 'height') {
					$shrinkage = $max_height / $imageheight;
					$displayheight = $max_height;
					$displaywidth = $imagewidth * $shrinkage;
				} elseif ($display_method == 'both') {
					$displaywidth = $max_width;
					$displayheight = $max_height;
				}

				if ($config['url_style'] == '1') {
					$url = 'index.php?action=view_listing_image&amp;image_id=' . $imageID;
				}else {
					$url = 'listing_image_' . $imageID . '.html';
				}
				$output .= 's = new slide();
							s.src =  "' . $config['listings_view_images_path'] . '/' . $file_name . '";
							s.width = "' . $displaywidth . '";
							s.height = "' . $displayheight . '";
							s.alt = "' . $file_name . '";
							s.text = unescape("");
							s.link = "' . $url . '";
							s.target = "";
							s.attr = "";
							s.filter = "";
							SLIDES.add_slide(s);
							';
				$recordSet->MoveNext();
			} // end while
			$output .= '--></script>';
			$output .= '<div class="slideshow_img"><a href="javascript:SLIDES.hotlink()"><img id="SLIDESIMG" src="images/image.jpg" width="'.$displaywidth.'" style="border:none;filter:progid:DXImageTransform.Microsoft.Fade()" alt="Slideshow image" /></a></div>
								<div class="slideshow_links"><a href="javascript:;" onclick="SLIDES.previous()">'.$lang['ssprevious'].'</a>&nbsp;&nbsp;&nbsp;
								<a href="javascript:;" onclick="SLIDES.play()">'.$lang['ssplay'].'</a>
								<a href="javascript:;" onclick="SLIDES.pause()">'.$lang['sspause'].'</a>&nbsp;&nbsp;&nbsp;
								<a href="javascript:;" onclick="SLIDES.next()">'.$lang['ssnext'].'</a></div>';
			$output .= '<script type="text/javascript">
								<!--
								if (document.images) {
								  SLIDES.image = document.images.SLIDESIMG;
								  //SLIDES.textid = "SLIDESTEXT";
								  SLIDES.update();
								}
								//-->
								</script>';
		} // end if ($num_images > 0)
		else {
			if ($config['show_no_photo'] == 1) {
				$output .= '<div class="slideshow_img"><img src="images/nophotobig.gif" alt="' . $lang['no_photo'] . '"/></div>';
			}
		}
		return $output;
	} // end function renderListingsMainImageSlideShow
	function renderListingsImages($listingID, $showcap)
	{
		// shows the images connected to a given image
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// grab the images
		$listingID = $misc->make_db_extra_safe($listingID);
		$sql = "SELECT listingsimages_id, listingsimages_caption, listingsimages_thumb_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsdb_id = $listingID) ORDER BY listingsimages_rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$display = '';
		$num_images = $recordSet->RecordCount();
		if ($num_images > 0) {
			// $display .= "<td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";
			$display .= "<strong>$lang[images]</strong><br /><hr style=\"width:75%;\" />";
			while (!$recordSet->EOF) {
				$caption = $misc->make_db_unsafe ($recordSet->fields['listingsimages_caption']);
				$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_thumb_file_name']);
				// $file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
				$imageID = $misc->make_db_unsafe ($recordSet->fields['listingsimages_id']);
				if ($thumb_file_name != "" && file_exists("$config[listings_upload_path]/$thumb_file_name")) {
					$thumb_imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
					$thumb_imagewidth = $thumb_imagedata[0];
					$thumb_imageheight = $thumb_imagedata[1];
					$thumb_max_width = $config['thumbnail_width'];
					$thumb_max_height = $config['thumbnail_height'];
					$resize_by = $config['resize_thumb_by'];
					$shrinkage = 1;
					if (($thumb_max_width == $thumb_imagewidth) || ($thumb_max_height == $thumb_imageheight)) {
						$thumb_displaywidth = $thumb_imagewidth;
						$thumb_displayheight = $thumb_imageheight;
					} else {
						if ($resize_by == 'width') {
							$shrinkage = $thumb_imagewidth / $thumb_max_width;
							$thumb_displaywidth = $thumb_max_width;
							$thumb_displayheight = round($thumb_imageheight / $shrinkage);
						} elseif ($resize_by == 'height') {
							$shrinkage = $thumb_imageheight / $thumb_max_height;
							$thumb_displayheight = $thumb_max_height;
							$thumb_displaywidth = round($thumb_imagewidth / $shrinkage);
						} elseif ($resize_by == 'both') {
							$thumb_displayheight = $thumb_max_height;
							$thumb_displaywidth = $thumb_max_width;
						}
					}
					if ($config['url_style'] == '1') {
						$display .= '<a href="index.php?action=view_listing_image&amp;image_id=' . $imageID . '">';
					}else {
						$display .= '<a href="listing_image_' . $imageID . '.html">';
					}
					if ($caption != '') {
						$alt = $caption;
					} else {
						$alt = $thumb_file_name;
					}
					$display .= "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$thumb_displayheight\" width=\"$thumb_displaywidth\" alt=\"$alt\" /></a><br /> ";
					if ($showcap == 'yes') {
						$display .= "<b>".urldecode($caption)."</b><br /><br />";
					} else {
						$display .= "<br />";
					}
				} // end if ($thumb_file_name != "")
				$recordSet->MoveNext();
			} // end while
			// $display .= "</td>";
		} // end if ($num_images > 0)
		else {
			if ($config['show_no_photo'] == 1) {
				$display .= "<img src=\"$config[baseurl]/images/nophoto.gif\" width=\"$config[thumbnail_width]\" alt=\"$lang[no_photo]\" /><br /> ";
			}
		}
		return $display;
	} // end function renderListingsImages
	function make_thumb_gd ($input_file_name, $input_file_path, $output_path = "")
	{
		// makes a thumbnail using the GD library
		global $config;
		$quality = $config['jpeg_quality']; // jpeg quality -- set in common.php
		if($output_path == ""){
			$output_path=$input_file_path;
		}
		// Specify your file details
		$current_file = $input_file_path . '/' . $input_file_name;
		$max_width = $config['thumbnail_width'];
		$max_height = $config['thumbnail_height'];
		$resize_by = $config['resize_thumb_by'];

		// Get the current info on the file
		$imagedata = getimagesize($current_file);
		$imagewidth = $imagedata[0];
		$imageheight = $imagedata[1];
		$imagetype = $imagedata[2];
		if ($resize_by == 'width') {
			$shrinkage = $imagewidth / $max_width;
			$new_img_width = $max_width;
			$new_img_height = round($imageheight / $shrinkage);
		} elseif ($resize_by == 'height') {
			$shrinkage = $imageheight / $max_height;
			$new_img_height = $max_height;
			$new_img_width = round($imagewidth / $shrinkage);
		} elseif ($resize_by == 'both') {
			$new_img_width = $max_width;
			$new_img_height = $max_height;
		} elseif ($resize_by == 'bestfit') {
			$shrinkage_width = $imagewidth / $max_width;
			$shrinkage_height = $imageheight / $max_height;
			$shrinkage = max($shrinkage_width, $shrinkage_height);
			$new_img_height = round($imageheight / $shrinkage);
			$new_img_width = round($imagewidth / $shrinkage);
		}
		// type definitions
		// 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP
		// 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order)
		// 9 = JPC, 10 = JP2, 11 = JPX
		$thumb_name = $input_file_name; //by default
		// the GD library, which this uses, can only resize GIF, JPG and PNG
		if ($imagetype == 1) {
			// it's a GIF
			// see if GIF support is enabled
			if (imagetypes() &IMG_GIF) {
				$src_img = imagecreatefromgif($current_file);
				$dst_img = imageCreate($new_img_width, $new_img_height);
				// copy the original image info into the new image with new dimensions
				// checking to see which function is available
				ImageCopyResized($dst_img, $src_img, 0, 0, 0, 0, $new_img_width, $new_img_height, $imagewidth, $imageheight);
				$thumb_name = "thumb_" . "$input_file_name";
				imagegif($dst_img, "$output_path/$thumb_name");
				@chmod("$output_path/$thumb_name", 0777);
				imagedestroy($src_img);
				imagedestroy($dst_img);
			} // end if GIF support is enabled
		} // end if $imagetype == 1
		elseif ($imagetype == 2) {
			// it's a JPG
			$src_img = @imagecreatefromjpeg($current_file);
			if (!$src_img) { /* See if it failed */
				$im  = imagecreatetruecolor(150, 30); /* Create a black image */
				$bgc = imagecolorallocate($src_img, 255, 255, 255);
				$tc  = imagecolorallocate($src_img, 0, 0, 0);
				imagefilledrectangle($src_img, 0, 0, 150, 30, $bgc);
				/* Output an errmsg */
				imagestring($src_img, 1, 5, 5, "Error loading $input_file_name", $tc);
			}
			if ($config['gdversion2'] == false) {
				$dst_img = imageCreate($new_img_width, $new_img_height);
			}else {
				$dst_img = imageCreateTrueColor($new_img_width, $new_img_height);
			}
			// copy the original image info into the new image with new dimensions
			// checking to see which function is available
			if ($config['gdversion2'] == false) {
				ImageCopyResized($dst_img, $src_img, 0, 0, 0, 0, $new_img_width, $new_img_height, $imagewidth, $imageheight);
			}else {
				ImageCopyResampled($dst_img, $src_img, 0, 0, 0, 0, $new_img_width, $new_img_height, $imagewidth, $imageheight);
			}
			$thumb_name = "thumb_" . "$input_file_name";
			imagejpeg($dst_img, "$output_path/$thumb_name", $quality);
			@chmod("$output_path/$thumb_name", 0777);
			imagedestroy($src_img);
			imagedestroy($dst_img);
		} // end if $imagetype == 2
		elseif ($imagetype == 3) {
			// it's a PNG
			$src_img = imagecreatefrompng($current_file);
			$dst_img = imagecreate($new_img_width, $new_img_height);
			imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $new_img_width, $new_img_height, $imagewidth, $imageheight);
			$thumb_name = "thumb_" . "$input_file_name";
			imagepng($dst_img, "$output_path/$thumb_name");
			@chmod("$output_path/$thumb_name", 0777);
			imagedestroy($src_img);
			imagedestroy($dst_img);
		} // end if $imagetype == 3
		return $thumb_name;
	} // end function make_thumb_gd
	function resize_img_gd ($input_file_name, $input_file_path, $type)
	{
		// resizes image using the GD library
		global $config;
		$quality = $config['jpeg_quality']; // jpeg quality -- set in common.php
		// Specify your file details
		$current_file = $input_file_path . '/' . $input_file_name;
		$max_width = $config['max_' . $type . '_upload_width'];
		$max_height = $config['max_' . $type . '_upload_height'];
		$resize_by = $config['resize_by'];

		// Get the current info on the file
		$imagedata = getimagesize($current_file);
		$imagewidth = $imagedata[0];
		$imageheight = $imagedata[1];
		$imagetype = $imagedata[2];

		if ($resize_by == 'width') {
			$shrinkage = $imagewidth / $max_width;
			$new_img_width = $max_width;
			$new_img_height = round($imageheight / $shrinkage);
		} elseif ($resize_by == 'height') {
			$shrinkage = $imageheight / $max_height;
			$new_img_height = $max_height;
			$new_img_width = round($imagewidth / $shrinkage);
		} elseif ($resize_by == 'both') {
			$new_img_width = $max_width;
			$new_img_height = $max_height;
		} elseif ($resize_by == 'bestfit') {
			$shrinkage_width = $imagewidth / $max_width;
			$shrinkage_height = $imageheight / $max_height;
			$shrinkage = max($shrinkage_width, $shrinkage_height);
			$new_img_height = round($imageheight / $shrinkage);
			$new_img_width = round($imagewidth / $shrinkage);
		}
		// type definitions
		// 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP
		// 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order)
		// 9 = JPC, 10 = JP2, 11 = JPX
		$img_name = $input_file_name; //by default
		// the GD library, which this uses, can only resize GIF, JPG and PNG
		if ($imagetype == 1) {
			// it's a GIF
			// see if GIF support is enabled
			if (imagetypes() &IMG_GIF) {
				$src_img = imagecreatefromgif($current_file);
				$dst_img = imageCreate($new_img_width, $new_img_height);
				// copy the original image info into the new image with new dimensions
				ImageCopyResized($dst_img, $src_img, 0, 0, 0, 0, $new_img_width, $new_img_height, $imagewidth, $imageheight);
				$thumb_name = "thumb_" . "$input_file_name";
				imagegif($dst_img, "$input_file_path/$img_name");
				imagedestroy($src_img);
				imagedestroy($dst_img);
			} //end if GIF support is enabled
		} // end if $imagetype == 1
		elseif ($imagetype == 2) {
			// it's a JPG
			$src_img = imagecreatefromjpeg($current_file);
			if ($config['gdversion2'] == false) {
				$dst_img = imageCreate($new_img_width, $new_img_height);
			}else {
				$dst_img = imageCreateTrueColor($new_img_width, $new_img_height);
			}
			// copy the original image info into the new image with new dimensions
			// checking to see which function is available
			if ($config['gdversion2'] == false) {
				ImageCopyResized($dst_img, $src_img, 0, 0, 0, 0, $new_img_width, $new_img_height, $imagewidth, $imageheight);
			}else {
				ImageCopyResampled($dst_img, $src_img, 0, 0, 0, 0, $new_img_width, $new_img_height, $imagewidth, $imageheight);
			}
			$img_name = "$input_file_name";
			imagejpeg($dst_img, "$input_file_path/$img_name", $quality);
			imagedestroy($src_img);
			imagedestroy($dst_img);
		} // end if $imagetype == 2
		elseif ($imagetype == 3) {
			// it's a PNG
			$src_img = imagecreatefrompng($current_file);
			$dst_img = imagecreate($new_img_width, $new_img_height);
			imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $new_img_width, $new_img_height, $imagewidth, $imageheight);
			$img_name = "$input_file_name";
			imagepng($dst_img, "$input_file_path/$img_name");
			imagedestroy($src_img);
			imagedestroy($dst_img);
		} // end if $imagetype == 3
	} // end function resize_img_gd
	function resize_img_imagemagick ($input_file_name, $input_file_path, $type)
	{
		// resizes image using ImageMagick
		global $config;
		// Specify your file details
		$current_file = $input_file_path . '/' . $input_file_name;
		$max_width = $config['max_' . $type . '_upload_width'];
		$max_height = $config['max_' . $type . '_upload_height'];
		$resize_by = $config['resize_by'];

		// Get the current info on the file
		$imagedata = getimagesize($current_file);
		$imagewidth = $imagedata[0];
		$imageheight = $imagedata[1];

		if ($resize_by == 'width') {
			$shrinkage = $imagewidth / $max_width;
			$new_img_width = $max_width;
			$new_img_height = round($imageheight / $shrinkage);
		} elseif ($resize_by == 'height') {
			$shrinkage = $imageheight / $max_height;
			$new_img_height = $max_height;
			$new_img_width = round($imagewidth / $shrinkage);
		} elseif ($resize_by == 'both') {
			$new_img_width = $max_width;
			$new_img_height = $max_height;
		} elseif ($resize_by == 'bestfit') {
			$shrinkage_width = $imagewidth / $max_width;
			$shrinkage_height = $imageheight / $max_height;
			$shrinkage = max($shrinkage_width, $shrinkage_height);
			$new_img_height = round($imageheight / $shrinkage);
			$new_img_width = round($imagewidth / $shrinkage);
		}
		// $image_base = explode('.', $current_file);
		// This part gets the new thumbnail name
		// $image_basename = $image_base[0];
		// $image_ext = $image_base[1];
		$path = $config['path_to_imagemagick'];
		$debug_path = '"' . $path . '" -geometry ' . $new_img_width . 'x' . $new_img_height . ' "' . $current_file . '" current_file';
		// Convert the file
		$debug = exec($debug_path);
	} // end function resize_img_imagemagick
	function make_thumb_imagemagick ($input_file_name, $input_file_path)
	{
		// makes a thumbnail using ImageMagick
		global $config;
		// Specify your file details
		$current_file = $input_file_path . '/' . $input_file_name;
		$max_width = $config['thumbnail_width'];
		$max_height = $config['thumbnail_height'];
		$resize_by = $config['resize_thumb_by'];

		// Get the current info on the file
		$imagedata = getimagesize($current_file);
		$imagewidth = $imagedata[0];
		$imageheight = $imagedata[1];

		if ($resize_by == 'width') {
			$shrinkage = $imagewidth / $max_width;
			$new_img_width = $max_width;
			$new_img_height = round($imageheight / $shrinkage);
		} elseif ($resize_by == 'height') {
			$shrinkage = $imageheight / $max_height;
			$new_img_height = $max_height;
			$new_img_width = round($imagewidth / $shrinkage);
		} elseif ($resize_by == 'both') {
			$new_img_width = $max_width;
			$new_img_height = $max_height;
		} elseif ($resize_by == 'bestfit') {
			$shrinkage_width = $imagewidth / $max_width;
			$shrinkage_height = $imageheight / $max_height;
			$shrinkage = max($shrinkage_width, $shrinkage_height);
			$new_img_height = round($imageheight / $shrinkage);
			$new_img_width = round($imagewidth / $shrinkage);
		}

		// $image_base = explode('.', $current_file);
		// This part gets the new thumbnail name
		// $image_basename = $image_base[0];
		// $image_ext = $image_base[1];
		$thumb_name = $input_file_path . "/thumb_" . $input_file_name;
		$thumb_name2 = "thumb_" . $input_file_name;
		$path = $config['path_to_imagemagick'];
		// Convert the file
		$debug_path = '"' . $path . '" -geometry ' . $new_img_width . 'x' . $new_img_height . ' "' . $current_file . '" "' . $thumb_name . '"';
		$debug = exec($debug_path);
		@chmod("$input_file_path/$thumb_name", 0777);
		return $thumb_name2;
	} // end function make_thumb
	function handleUpload($type, $edit, $owner)
	{
		// deals with incoming uploads
		global $config, $conn, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$file_x = 0;
		$edit=intval($edit);
		$owner=intval($owner);
		if ($type == 'user') {
			$sql = "SELECT count(" . $type . "images_id) as num_images FROM " . $config['table_prefix'] . "" . $type . "images WHERE (userdb_id = $owner)";
		}else {
			$sql = "SELECT count(" . $type . "images_id) as num_images FROM " . $config['table_prefix'] . "" . $type . "images WHERE (listingsdb_id = $edit)";
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_images = $recordSet->fields['num_images'];
		$avaliable_images = $config['max_' . $type . '_uploads'] - $num_images;
		while ($file_x < $avaliable_images) {
			if (is_uploaded_file($_FILES['userfile']['tmp_name'][$file_x])) {
				$realname = $misc->clean_filename($_FILES['userfile']['name'][$file_x]);
				$filename = $_FILES['userfile']['tmp_name'][$file_x];
				$extension = substr(strrchr($realname, "."), 1);
				$filetype = $_FILES['userfile']['type'][$file_x];
				// checking the filetype to make sure it's what we had in mind
				$pass_the_upload = "true";
				if (!in_array($_FILES['userfile']['type'][$file_x], explode(',', $config['allowed_upload_types']))) {
					$pass_the_upload = "$realname $lang[upload_is_an_invalid_file_type]: $filetype";
				}
				// check file extensions
				if (!in_array($extension, explode(',', $config['allowed_upload_extensions']))) {
					$pass_the_upload = "$lang[upload_invalid_extension] ($extension).";
				}
				// check size
				$filesize = $_FILES['userfile']['size'][$file_x];
				if ($config['max_' . $type . '_upload_size'] != 0 && $filesize > $config['max_' . $type . '_upload_size']) {
					$pass_the_upload = $lang['upload_too_large'] . '<br />' . $lang['failed_max_filesize'] . ' ' . $config['max_' . $type . '_upload_size'] . '' . $lang['bytes'];
				}
				// check width & height
				$imagedata = GetImageSize("$filename");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				if (($config['resize_img'] == '1') && ($type != 'vtour')) {
					$max_width = $config['max_' . $type . '_upload_width'];
					$max_height = $config['max_' . $type . '_upload_height'];
					$resize_by = $config['resize_by'];
					$shrinkage = 1;
					// Figure out what the sizes are going to be AFTER resizing the images to know if we should allow the upload or not
					if ($resize_by == 'width') {
						if ($imagewidth > $max_width) {
							$shrinkage = $imagewidth / $max_width;
						}
						$new_img_width = $max_width;
						$new_img_height = round($imageheight / $shrinkage);
						if ($new_img_height > $max_height) {
							$pass_the_upload = $lang['upload_too_high'] . '<br />' . $lang['failed_max_height'] . ' ' . $max_height . '' . $lang['pixels'];
						}
					} elseif ($resize_by == 'height') {
						if ($imageheight > $max_height) {
							$shrinkage = $imageheight / $max_height;
						}
						$new_img_height = $max_height;
						$new_img_width = round($imagewidth / $shrinkage);
						if ($new_img_width > $max_width) {
							$pass_the_upload = $lang['upload_too_wide'] . '<br />' . $lang['failed_max_width'] . ' ' . $max_width . '' . $lang['pixels'];
						}
					} elseif ($resize_by == 'both') {
					} elseif ($resize_by == 'bestfit') {
					}
				} else {
					if ($imagewidth > $config['max_' . $type . '_upload_width']) {
						$pass_the_upload = $lang['upload_too_wide'] . '<br />' . $lang['failed_max_width'] . ' ' . $max_width . '' . $lang['pixels'];
					}
					if ($type != 'vtour') {
						if ($imageheight > $config['max_' . $type . '_upload_height']) {
							$pass_the_upload = $lang['upload_too_high'] . '<br />' . $lang['failed_max_height'] . ' ' . $max_height . '' . $lang['pixels'];
						}
					}
				}
				// security error
				if (strstr($_FILES['userfile']['name'][$file_x], "..") != "") {
					$pass_the_upload = "$lang[upload_security_violation]!";
				}
				// make sure the file hasn't already been uploaded...
				if ($type == "listings") {
					$save_name = "$_POST[edit]" . "_" . "$realname";
					$sql = "SELECT listingsimages_file_name FROM " . $config['table_prefix'] . "listingsimages WHERE listingsimages_file_name = '$save_name'";
				}elseif ($type == "vtour") {
					$save_name = "$_POST[edit]" . "_" . "$realname";
					$sql = "SELECT vtourimages_file_name FROM " . $config['table_prefix'] . "vtourimages WHERE vtourimages_file_name = '$save_name'";
				}elseif ($type == "user") {
					$save_name = "$owner" . "_" . "$realname";
					$sql = "SELECT userimages_file_name FROM " . $config['table_prefix'] . "userimages WHERE userimages_file_name = '$save_name'";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$num = $recordSet->RecordCount();
				if ($num > 0) {
					$pass_the_upload = "$lang[file_exists]!";
				}

				if ($pass_the_upload == "true") {
					// the upload has passed the tests!
					if ($type == "listings") {
						move_uploaded_file($_FILES['userfile']['tmp_name'][$file_x], "$config[listings_upload_path]/$save_name");
						$thumb_name = $save_name; // by default -- no difference... unless...

						if ($config['make_thumbnail'] == '1') {
							// if the option to make a thumbnail is activated...
							$make_thumb = 'make_thumb_' . $config['thumbnail_prog'];
							$thumb_name = image_handler::$make_thumb ($save_name, $config['listings_upload_path']);
						} // end if $config[make_thumbnail] === "1"
						if ($config['resize_img'] == '1' && ($imagewidth > $config['max_' . $type . '_upload_width'] || $imageheight > $config['max_' . $type . '_upload_height'])) {
							// if the option to resize the images on upload is activated...
							$resize_img = 'resize_img_' . $config['thumbnail_prog'];
							$img_name = image_handler::$resize_img ($save_name, $config['listings_upload_path'], $type);
						} // end if $config[resize_img] === "1"
						// Get Max Image Rank
						$sql = "SELECT MAX(listingsimages_rank) AS max_rank FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsdb_id = '$edit')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$rank = $recordSet->fields['max_rank'];
						$rank++;
						$sql = "INSERT INTO " . $config['table_prefix'] . "listingsimages (listingsdb_id, userdb_id, listingsimages_file_name, listingsimages_thumb_file_name,listingsimages_rank,listingsimages_caption,listingsimages_description,listingsimages_active) VALUES ('$edit', '$owner', '$save_name', '$thumb_name',$rank,'','','yes')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$misc->log_action ("$lang[log_uploaded_listing_image] $save_name");
						@chmod("$config[listings_upload_path]/$save_name", 0777);
					} // end if $type == "listings"
					if ($type == "vtour") {
						move_uploaded_file($_FILES['userfile']['tmp_name'][$file_x], "$config[vtour_upload_path]/$save_name");
						$thumb_name = $save_name; // by default -- no difference... unless...

						if ($config['make_thumbnail'] == '1' && $imagedata != false) {
							// if the option to make a thumbnail is activated...
							$make_thumb = 'make_thumb_' . $config['thumbnail_prog'];
							$thumb_name = image_handler::$make_thumb ($save_name, $config['vtour_upload_path']);
						} // end if $config[make_thumbnail] === "1"
						// Get Max Image Rank
						$sql = "SELECT MAX(vtourimages_rank) AS max_rank FROM " . $config['table_prefix'] . "vtourimages WHERE (listingsdb_id = '$edit')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$rank = $recordSet->fields['max_rank'];
						$rank++;
						$sql = "INSERT INTO " . $config['table_prefix'] . "vtourimages (listingsdb_id, userdb_id, vtourimages_file_name, vtourimages_thumb_file_name, vtourimages_rank,vtourimages_caption,vtourimages_description,vtourimages_active) VALUES ('$edit', '$owner', '$save_name', '$thumb_name',$rank,'','','yes')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$misc->log_action ("$lang[log_uploaded_listing_image] $save_name");
						@chmod("$config[vtour_upload_path]/$save_name", 0777);
					} // end if $type == "vtour"
					if ($type == "user") {
						if ($this->debug) {
							$display .= 'Try to make Thumbnail? ' . $config['make_thumbnail'] . '<br />';
						}
						move_uploaded_file($_FILES['userfile']['tmp_name'][$file_x], "$config[user_upload_path]/$save_name");
						$thumb_name = $save_name; // by default -- no difference... unless...

						if ($config['make_thumbnail'] == 1) {
							// if the option to make a thumbnail is activated...
							// include ("$config[path_to_thumbnailer]");
							$thumb_name = 'make_thumb_' . $config['thumbnail_prog'];
							$thumb_name = image_handler::$thumb_name($save_name, $config['user_upload_path']);
						} // end if $config[make_thumbnail] === "1"
						if ($config['resize_img'] == '1' && $imagewidth > $config['max_' . $type . '_upload_width']) {
							// if the option to make a thumbnail is activated...
							// include ("$config[path_to_thumbnailer]");
							$resize_img = 'resize_img_' . $config['thumbnail_prog'];
							image_handler::$resize_img ($save_name, $config['user_upload_path'], $type);
						} // end if $config[resize_img] === "1"
						// Get Max Image Rank
						$sql = "SELECT MAX(userimages_rank) AS max_rank FROM " . $config['table_prefix'] . "userimages WHERE (userdb_id = '$owner')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$rank = $recordSet->fields['max_rank'];
						$rank++;
						$sql = "INSERT INTO " . $config['table_prefix'] . "userimages (userdb_id, userimages_file_name, userimages_thumb_file_name,userimages_rank,userimages_caption,userimages_description,userimages_active) VALUES ('$owner', '$save_name', '$thumb_name',$rank,'','','yes')";
						$recordSet = $conn->Execute($sql);
						if ($recordSet === false) {
							$misc->log_error($sql);
						}
						$misc->log_action ("$lang[log_uploaded_user_image] $save_name");
						@chmod("$config[user_upload_path]/$save_name", 0777);
					} // end if $type == "user"
					$display .= "<p>$realname $lang[upload_success].</p>";
				} // end if $pass_the_upload == "true"
				else {
					// the upload has failed... here's why...
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
	} // end function handleUpload

	function renderListingsMainImage($listingID, $showdesc, $java)
	{
		// shows the main image
		global $config, $lang, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// grab the images
		$listingID = $misc->make_db_extra_safe($listingID);

		$sql = "SELECT listingsimages_id, listingsimages_caption, listingsimages_file_name, listingsimages_description FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsdb_id = $listingID) ORDER BY listingsimages_rank";
		$recordSet = $conn->SelectLimit($sql, 1, 0);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_images = $recordSet->RecordCount();
		if ($num_images > 0) {
			while (!$recordSet->EOF) {
				$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
				$caption = $misc->make_db_unsafe ($recordSet->fields['listingsimages_caption']);
				$description = $misc->make_db_unsafe ($recordSet->fields['listingsimages_description']);
				$shrinkage = 1;
				$display_method = $config["main_image_display_by"];
				$max_width = $config["main_image_width"];
				$max_height = $config["main_image_height"];
				$display_width = $max_width;
				$display_height = $max_height;
				$width = '';
				$height = '';
				$imagedata = GetImageSize("$config[listings_upload_path]/$file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				// Figure out display sizes based on display method
				if ($display_method == 'width') {
					$width = ' width="'.$max_width.'"';
				} elseif ($display_method == 'height') {
					$height = ' height="'.$max_height.'"';
				} elseif ($display_method == 'both') {
					$width = ' width="'.$max_width.'"';
					$height = ' height="'.$max_height.'"';
				}
				if ($java == 'yes') {
					if ($showdesc == 'yes') {
						$display = "<script type=\"text/javascript\"> function imgchange(id,caption,description){if(document.images){document.getElementById('main').src = \"$config[listings_view_images_path]/\" + id; document.getElementById('main').alt = caption; document.getElementById('main_image_description').innerHTML = description; } else { document.getElementById('main').src = \"images/nophoto.gif\";document.getElementById('main_image_description').innerHTML = ''; }}</script>";
						$display .= "<img src=\"$config[listings_view_images_path]/$file_name\"$width$height id=\"main\" alt=\"$caption\" /><br /><div id=\"main_image_description\">$description</div>";
					} else {
						$display = "<script type=\"text/javascript\"> function imgchange(id,caption,description){if(document.images){document.getElementById('main').src = \"$config[listings_view_images_path]/\" + id; document.getElementById('main').alt = caption;} else { document.getElementById('main').src = \"images/nophoto.gif\"; }}</script>";
						$display .= "<img src=\"$config[listings_view_images_path]/$file_name\"$width$height id=\"main\" alt=\"$caption\" /><br />";
					}
				} else {
					if ($showdesc == 'yes') {
						$display .= "<img src=\"$config[listings_view_images_path]/$file_name\"$width$height alt=\"$caption\" /><br /><div id=\"main_image_description\">$description</div>";
					} else {
						$display .= "<img src=\"$config[listings_view_images_path]/$file_name\"$width$height alt=\"$caption\" /><br />";
					}
				}

				$recordSet->MoveNext();
			} // end while
		} // end if ($num_images > 0)

		else {
			if ($config['show_no_photo'] == 1) {
				$display .= "<img src=\"images/nophotobig.gif\" width=\"$width\" id=\"main\" alt=\"$lang[no_photo]\" /><br />";
			}
		}
		return $display;
	} // end function renderListingsMainImage

	function renderListingsImagesJava($listingID, $showcap, $mouseover = 'no')
	{
		// shows the images connected to a given image
		global $config, $lang, $conn, $style;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// grab the images
		$listingID = $misc->make_db_extra_safe($listingID);
		$sql = "SELECT listingsimages_caption, listingsimages_file_name, listingsimages_thumb_file_name, listingsimages_description FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsdb_id = $listingID) ORDER BY listingsimages_rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$display = '';
		$num_images = $recordSet->RecordCount();
		if ($num_images > 0) {
			// $display .= "<td width=\"$style[image_column_width]\" valign=\"top\" class=\"row_main\" align=\"center\">";
			while (!$recordSet->EOF) {
				$caption = $misc->make_db_unsafe ($recordSet->fields['listingsimages_caption']);
				$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_thumb_file_name']);
				$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
				// $imageID = $misc->make_db_unsafe ($recordSet->fields['listingsimages_id']);
				$description = $misc->make_db_unsafe ($recordSet->fields['listingsimages_description']);
				$description = str_replace('"','&quot;',$description);
				$caption = str_replace('"','&quot;',$caption);
				// gotta grab the image size
				$thumb_imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
				$thumb_imagewidth = $thumb_imagedata[0];
				$thumb_imageheight = $thumb_imagedata[1];
				$thumb_max_width = $config['thumbnail_width'];
				$thumb_max_height = $config['thumbnail_height'];
				$resize_by = $config['resize_thumb_by'];
				$shrinkage = 1;
				if (($thumb_max_width == $thumb_imagewidth) || ($thumb_max_height == $thumb_imageheight)) {
					$thumb_displaywidth = $thumb_imagewidth;
					$thumb_displayheight = $thumb_imageheight;
				} else {
					if ($resize_by == 'width') {
						$shrinkage = $thumb_imagewidth / $thumb_max_width;
						$thumb_displaywidth = $thumb_max_width;
						$thumb_displayheight = round($thumb_imageheight / $shrinkage);
					} elseif ($resize_by == 'height') {
						$shrinkage = $thumb_imageheight / $thumb_max_height;
						$thumb_displayheight = $thumb_max_height;
						$thumb_displaywidth = round($thumb_imagewidth / $shrinkage);
					} elseif ($resize_by == 'both') {
						$thumb_displayheight = $thumb_max_height;
						$thumb_displaywidth = $thumb_max_width;
					}
				}
				if ($mouseover == 'no') {
					$display .= "<a href=\"javascript:imgchange('$file_name','" . addslashes($caption) . "','" . addslashes($description) . "')\"> ";
					$display .= "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$thumb_displayheight\" width=\"$thumb_displaywidth\" alt=\"$caption\" /></a><br />";
				} else {
					$display .= '<img src="'.$config[listings_view_images_path].'/'.$thumb_file_name.'" height="'.$thumb_displayheight.'" width="'.$thumb_displaywidth.'" alt="'.$caption.'" onmouseover="imgchange(\''.$file_name.'\',\'' . addslashes($caption) . '\',\'' . addslashes($description) . '\')" /><br />';
				}
				if ($showcap == 'yes') {
					$display .= "<b>".urldecode($caption)."</b><br /><br />";
				} else {
					$display .= "<br />";
				}
				$recordSet->MoveNext();
			} // end while
			// $display .= "</td>";
		} // end if ($num_images > 0)
		else {
			if ($config['show_no_photo'] == 1) {
				$display .= "<img src=\"$config[baseurl]/images/nophoto.gif\" width=\"$config[thumbnail_width]\" alt=\"$lang[no_photo]\" /><br /> ";
			}
		}
		return $display;
	} // end function renderListingsImagesJava
	function renderListingsImagesJavaRows($listingID, $mouseover = 'no')
	{
		// shows the images connected to a given image
		global $config, $lang, $conn, $style;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// grab the images
		$var_reset = 1; // Reset the var (counter) (DO NOT CHANGE)
		$user_col_max = $config['number_columns']; // How Many To show Per Row
		$listingID = $misc->make_db_extra_safe($listingID);
		$sql = "SELECT listingsimages_caption, listingsimages_file_name, listingsimages_thumb_file_name, listingsimages_description FROM " . $config['table_prefix'] . "listingsimages WHERE (listingsdb_id = $listingID) ORDER BY listingsimages_rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$display = '';
		$num_images = $recordSet->RecordCount();
		if ($num_images > 0) {
			$display .= '<table id="imagerows">';

			while (!$recordSet->EOF) {
				$caption = $misc->make_db_unsafe ($recordSet->fields['listingsimages_caption']);
				$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_thumb_file_name']);
				$file_name = $misc->make_db_unsafe ($recordSet->fields['listingsimages_file_name']);
				$description = $misc->make_db_unsafe ($recordSet->fields['listingsimages_description']);
				$description = str_replace('"','&quot;',$description);
				$caption = str_replace('"','&quot;',$caption);
				// gotta grab the image size
				$thumb_imagedata = GetImageSize("$config[listings_upload_path]/$thumb_file_name");
				$thumb_imagewidth = $thumb_imagedata[0];
				$thumb_imageheight = $thumb_imagedata[1];
				$thumb_max_width = $config['thumbnail_width'];
				$thumb_max_height = $config['thumbnail_height'];
				$resize_by = $config['resize_thumb_by'];
				$shrinkage = 1;
				if (($thumb_max_width == $thumb_imagewidth) || ($thumb_max_height == $thumb_imageheight)) {
					$thumb_displaywidth = $thumb_imagewidth;
					$thumb_displayheight = $thumb_imageheight;
				} else {
					if ($resize_by == 'width') {
						$shrinkage = $thumb_imagewidth / $thumb_max_width;
						$thumb_displaywidth = $thumb_max_width;
						$thumb_displayheight = round($thumb_imageheight / $shrinkage);
					} elseif ($resize_by == 'height') {
						$shrinkage = $thumb_imageheight / $thumb_max_height;
						$thumb_displayheight = $thumb_max_height;
						$thumb_displaywidth = round($thumb_imagewidth / $shrinkage);
					} elseif ($resize_by == 'both') {
						$thumb_displayheight = $thumb_max_height;
						$thumb_displaywidth = $thumb_max_width;
					}
				}
				if ($var_reset == 1) {
					$display .= "<tr>";
				}
				if ($caption=='')
				{
					$caption=$thumb_file_name;
				}
				if ($mouseover == 'no') {
					$display .= "<td><a href=\"javascript:imgchange('$file_name','" . addslashes($caption) . "','" . addslashes($description) . "')\"> ";
					$display .= "<img src=\"$config[listings_view_images_path]/$thumb_file_name\" height=\"$thumb_displayheight\" width=\"$thumb_displaywidth\" alt=\"$caption\" /></a>";
					$display .= "</td>";
				} else {
					$display .= '<td><img src="'.$config[listings_view_images_path].'/'.$thumb_file_name.'" height="'.$thumb_displayheight.'" width="'.$thumb_displaywidth.'" alt="'.$caption.'" onmouseover="imgchange(\''.$file_name.'\',\'' . addslashes($caption) . '\',\'' . addslashes($description) . '\')" /></td>';
				}
				if ($var_reset == $user_col_max) {
					$display .= "</tr>";
					$var_reset = 1;
				}else {
					$var_reset++;
				}
				$recordSet->MoveNext();
			} // end while
			if ($var_reset != 1) {
				$display .= "</tr>";
			}
			$display .= "</table>";
		} // end if ($num_images > 0)
		else {
			if ($config['show_no_photo'] == 1) {
				$display .= '<table id="imagerows">';
				$display .= "<tr><td><img src=\"$config[baseurl]/images/nophoto.gif\" width=\"$config[thumbnail_width]\" alt=\"$lang[no_photo]\" /></td></tr></table>";
			}
		}
		return $display;
	} // end function renderListingsImagesJavaRows
	function edit_vtour_images()
	{
		global $lang, $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		if (isset($_GET['edit']) && $_GET['edit'] != '')
		{
			$_POST['edit'] = $_GET['edit'];
		}
		$edit = intval($_POST['edit']);
		$sql_edit = intval($_POST['edit']);
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
		if ($_POST['action'] == "update_pic") {
			$count = 0;
			$num_fields = count($_POST['pic']);
			$sql_edit = $misc->make_db_safe($_POST['edit']);
			while ($count < $num_fields) {
				$sql_caption = $misc->make_db_safe($_POST['caption'][$count]);
				$sql_description = $misc->make_db_safe($_POST['description'][$count]);
				$sql_rank = $misc->make_db_safe($_POST['rank'][$count]);
				$sql_pic = $misc->make_db_safe($_POST['pic'][$count]);

				if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
					$sql = "UPDATE " . $config['table_prefix'] . "vtourimages SET vtourimages_caption = $sql_caption, vtourimages_description = $sql_description, vtourimages_rank = $sql_rank WHERE ((listingsdb_id = $sql_edit) AND (vtourimages_file_name = $sql_pic))";
				}else {
					$sql = "UPDATE " . $config['table_prefix'] . "vtourimages SET vtourimages_caption = $sql_caption, vtourimages_description = $sql_description, vtourimages_rank = $sql_rank WHERE ((listingsdb_id = $sql_edit) AND (vtourimages_file_name = $sql_pic) AND (userdb_id = $_SESSION[userID]))";
				}
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$count++;
			}
			$display .= '<p>'.$lang['images_update'].'</p>';
			$misc->log_action ($lang['log_updated_listing_image'] . $edit);
		}

		if (isset($_GET['delete'])) {
			// get the data for the pic being deleted
			$sql_pic_id = $misc->make_db_safe($_GET['delete']);
			$sql_edit = $misc->make_db_safe($_GET['edit']);
			if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$sql = "SELECT vtourimages_file_name, vtourimages_thumb_file_name FROM " . $config['table_prefix'] . "vtourimages WHERE ((listingsdb_id = $sql_edit) AND (vtourimages_id = $sql_pic_id))";
			}else {
				$sql = "SELECT vtourimages_file_name, vtourimages_thumb_file_name FROM " . $config['table_prefix'] . "vtourimages WHERE ((listingsdb_id = $sql_edit) AND (vtourimages_id = $sql_pic_id) AND (userdb_id = $_SESSION[userID]))";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			} while (!$recordSet->EOF) {
				$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_thumb_file_name']);
				$file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_file_name']);
				$recordSet->MoveNext();
			} // end while
			// delete from the db
			if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
				$sql = "DELETE FROM " . $config['table_prefix'] . "vtourimages WHERE ((listingsdb_id = $sql_edit) AND (vtourimages_file_name = '$file_name'))";
			}else {
				$sql = "DELETE FROM " . $config['table_prefix'] . "vtourimages WHERE ((listingsdb_id = $sql_edit) AND (vtourimages_file_name = '$file_name') AND (userdb_id = '$_SESSION[userID]'))";
			}
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			// delete the files themselves
			// on widows, required php 4.11 or better (I think)
			if (!unlink("$config[vtour_upload_path]/$file_name")) die("$lang[alert_site_admin]");
			if ($file_name != $thumb_file_name) {
				if (!unlink("$config[vtour_upload_path]/$thumb_file_name")) die("$lang[alert_site_admin]");
			}
			$misc->log_action ("$lang[log_deleted_listing_image] $file_name");
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
				$display .= $this->handleUpload("vtour", $edit, $owner);
			} else {
				$display .= $this->handleUpload("vtour", $edit, $_SESSION['userID']);
			}
		} // end if $action == "upload"
		if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
			$sql = "SELECT vtourimages_id, vtourimages_caption, vtourimages_file_name, vtourimages_thumb_file_name, vtourimages_description, vtourimages_rank FROM " . $config['table_prefix'] . "vtourimages WHERE (listingsdb_id = $sql_edit) ORDER BY vtourimages_rank";
		} else {
			$sql = "SELECT vtourimages_id, vtourimages_caption, vtourimages_file_name, vtourimages_thumb_file_name, vtourimages_description, vtourimages_rank FROM " . $config['table_prefix'] . "vtourimages WHERE ((listingsdb_id = $sql_edit) AND (userdb_id = '$_SESSION[userID]')) ORDER BY vtourimages_rank";
		}
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$display .= '<table class="image_upload">';
		$ext = '';
		$num_images = $recordSet->RecordCount();
		$file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_file_name']);
		$ext = substr(strrchr($file_name, '.'), 1);

		$avaliable_images = $config["max_vtour_uploads"] - $num_images;
		$x = 0;
		if ($num_images < $config['max_vtour_uploads'] && $ext != 'egg') {
			$display .= '<table border="0" cellspacing="0" cellpadding="0">';
			$display .= '<tr>';
			$display .= '<td colspan="2">';
			$display .= '<h3>' . $lang['upload_a_picture'] . '</h3>';
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '<tr>';
			$display .= '<td width="150">&nbsp;</td>';
			$display .= '<td>';
			$display .= '<form enctype="multipart/form-data" action="index.php?action=edit_vtour_images" method="post">';
			$display .= '<input type="hidden" name="action" value="upload" />';
			$display .= '<input type="hidden" name="edit" value="' . $edit . '" />';
			$display .= '<input type="hidden" name="MAX_FILE_SIZE" value="' . $config['max_vtour_upload_size'] . '" />';
			while ($x < $avaliable_images) {
				$display .= '<b>' . $lang['upload_send_this_file'] . ': </b><input name="userfile[]" type="file" /><br />';
				$x++;
			}
			$display .= '<input type="submit" value="' . $lang['upload_send_file'] . '" />';
			$display .= '</form>';
			$display .= '</td>';
			$display .= '</tr>';
			$display .= '</table>';
		} // end if $num_images <= $config[max_user_uploads]
		$display .= '<table class="image_upload">';
		$display .= '<tr>';
		$display .= '<td colspan="2">';
		$display .= '<h3>' . $lang['edit_images'] . ' -- ';

		if ($_SESSION['edit_all_listings'] == "yes" || $_SESSION['admin_privs'] == "yes") {
			$display .= "<a href=\"index.php?action=edit_listings&amp;edit=$edit\">";
		}else {
			$display .= "<a href=\"index.php?action=edit_my_listings&amp;edit=$edit\">";
		}
		$display .= $lang['return_to_editing_listing'];
		$display .= '</a></h3></td></tr>';
		$display .= '</table>';

		$count = 0;
		$display .= '<form action="index.php?action=edit_vtour_images" method="post">';
		$display .= '<table class="image_upload">';
		while (!$recordSet->EOF) {
			// $edit = $misc->make_db_safe($_POST['edit']);
			$pic_id = $recordSet->fields['vtourimages_id'];
			$rank = $recordSet->fields['vtourimages_rank'];
			$caption = $misc->make_db_unsafe ($recordSet->fields['vtourimages_caption']);
			$thumb_file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_thumb_file_name']);
			$description = $misc->make_db_unsafe ($recordSet->fields['vtourimages_description']);
			$file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_file_name']);
			$ext = substr(strrchr($file_name, '.'), 1);
			if ($ext == 'jpg') {
				// gotta grab the image size
				$imagedata = GetImageSize("$config[vtour_upload_path]/$file_name");
				$imagewidth = $imagedata[0];
				$imageheight = $imagedata[1];
				$shrinkage = $config['thumbnail_width'] / $imagewidth;
				$displaywidth = $imagewidth * $shrinkage;
				$displayheight = $imageheight * $shrinkage;
				$filesize = filesize("$config[vtour_upload_path]/$file_name");
				$filesize = $filesize / 1000; // to get k

				// now grab the thumbnail data
				$thumb_imagedata = GetImageSize("$config[vtour_upload_path]/$thumb_file_name");
				$thumb_imagewidth = $thumb_imagedata[0];
				$thumb_imageheight = $thumb_imagedata[1];
				$thumb_filesize = filesize("$config[vtour_upload_path]/$thumb_file_name");
				$thumb_filesize = $thumb_filesize / 1000;
				// alternate the colors
				if ($count == 0) {
					$count = 1;
				}else {
					$count = 0;
				}
				$display .= '<tr class="image_row_'.$count.'"><td valign="top" class="image_row_'.$count.'" width="150"><b>'.$file_name.'</b><br />'.$lang['width'].'='.$imagewidth.'<br />'.$lang['height'].'='.$imageheight.'<br />'.$lang['size'].'='.$filesize.' k<br />';
				$display .= '<br />'.$lang['thumbnail'].':<br />';
				$display .= '<img src="'.$config['vtour_view_images_path'].'/'.$thumb_file_name.'" width="'.$displaywidth.'" border="1" alt="" />';
				$display .= '<br />'.$lang['width'].'='.$thumb_imagewidth.'<br />'.$lang['height'].'='.$thumb_imageheight.'<br />'.$lang['size'].'='.$thumb_filesize.' k<br />';
				$display .= '<br /><a href="index.php?action=edit_vtour_images&amp;delete='.$pic_id.'&amp;edit='.$edit.'" onclick="return confirmDelete()">'.$lang['delete'].'</a>';
				$display .= '</td><td align="center" class="image_row_'.$count.'"><img src="'.$config['vtour_view_images_path'].'/'.$file_name.'" border="1" width="600" alt="" />';
				$display .= '</tr><tr><td align="center" class="image_row_'.$count.'" colspan="2">';
				$display .= '<input type="hidden" name="pic[]" value="'.$file_name.'" />';
				$display .= '<table border="0">';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['admin_template_editor_field_rank'].':</b></td><td align="left"><input type="text" name="rank[]" value="'.$rank.'" /><div class="small">'.$lang['upload_rank_explanation'].'</div></td></tr>';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_caption'].':</b></td><td align="left"><input type="text" name="caption[]" value="'.$caption.'" /></td></tr>';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_description'].':</b><td align="left"><textarea name="description[]" rows="6" cols="40">'.$description.'</textarea></td></tr>';
				$display .= '</table>';
				$display .= '</td></tr><tr><td colspan="2"><hr /></td></tr>';
				$recordSet->MoveNext();
			} // end if ext = jpg
			elseif ($ext == 'egg') {
				// alternate the colors
				if ($count == 0) {
					$count = 1;
				}else {
					$count = 0;
				}
				$display .= '<tr class="image_row_'.$count.'"><td valign="top" align="center" class="image_row_'.$count.'"><b>'.$file_name.'</b><br />';
				$display .= '<img src="'.$config[baseurl].'/images/eggimage.gif" border="1" />';
				$display .= '<br /><a href="index.php?action=edit_vtour_images&amp;delete='.$pic_id.'&amp;edit='.$edit.'" onclick="return confirmDelete()">'.$lang['delete'].'</a>';
				$display .= '</tr>';
				$recordSet->MoveNext();
			} // end elseif ext = egg
			// Unsupported VTour: Display that it's uploaded but let the user know Open-Realty doesn't support it
			else {
				// alternate the colors
				if ($count == 0) {
					$count = 1;
				}else {
					$count = 0;
				}
				$display .= '<tr class="image_row_'.$count.'"><td valign="top" class="image_row_'.$count.'" width="150"><b>'.$lang[unsupported_vtour].'<br />'.$file_name.'</b><br />'.$lang[size].'='.$filesize.'k<br />';
				$display .= '<br /><a href="index.php?action=edit_vtour_images&amp;delete='.$pic_id.'&amp;edit='.$edit.'" onclick="return confirmDelete()">'.$lang['delete'].'</a>';
				$display .= '</tr><tr><td align="center" class="image_row_'.$count.'">';
				$display .= '<input type="hidden" name="pic[]" value="'.$file_name.'" />';
				$display .= '<table border="0">';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['admin_template_editor_field_rank'].':</b></td><td align="left"><input type="text" name="rank[]" value="'.$rank.'" /><div class="small">'.$lang['upload_rank_explanation'].'</div></td></tr>';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_caption'].':</b></td><td align="left"><input type="text" name="caption[]" value="'.$caption.'" /></td></tr>';
				$display .= '<tr><td align="right" class="image_row_'.$count.'"><b>'.$lang['upload_description'].':</b><td align="left"><textarea name="description[]" rows="6" cols="40">'.$description.'</textarea></td></tr>';
				$display .= '</table>';
				$display .= '</td></tr><tr><td><hr /></td></tr>';
				$recordSet->MoveNext();
			} // end else it's not a supported vtour
		} // end while
		$display .= '<tr><td align="center" class="image_row_'.$count.'" colspan="2"><input type="submit" value="'.$lang['update'].'" />';
		$display .= '</table>';
		$display .= '<input type="hidden" name="edit" value="'.$edit.'" />';
		$display .= '<input type="hidden" name="action" value="update_pic" />';
		$display .= '</form>';
		return $display;
	}
} // End ImageHandler Class

?>