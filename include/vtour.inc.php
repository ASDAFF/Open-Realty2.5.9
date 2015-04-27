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
class vtours {

	public static function show_vtour($listingID, $popup = true)
	{
		global $lang, $conn, $config, $jscript;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		if (isset($_GET['listingID'])) {
			if ($_GET['listingID'] != "") {
				require_once($config['basepath'] . '/include/class/template/core.inc.php');
				$page = new page_user();
				$page->load_page($config['template_path'] . '/' . $config['vtour_template']);
				$listingID = intval($listingID);
				$page->replace_listing_field_tags($listingID);
				$a = 0;
				$sql = "SELECT vtourimages_caption, vtourimages_description, vtourimages_file_name, vtourimages_rank FROM " . $config['table_prefix'] . "vtourimages WHERE (listingsdb_id = $listingID) ORDER BY vtourimages_rank";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$num_images = $recordSet->RecordCount();
				if ($num_images > 0) {
					$vtinit = 0;
					$vtopts .= '<form action="/">' . "\r\n";
					$vtopts .= "<p><select id=\"tourmenu\" onchange=\"swapTour(this)\"> \n";
					$vtparams = "'<param name=\"file\" value=\"ptviewer:$vtinit\" />'+ \n";
					$vtjs = '';
					while (!$recordSet->EOF) {
						$caption = $misc->make_db_unsafe ($recordSet->fields['vtourimages_caption']);
						$description = $conn->qstr($misc->make_db_unsafe($recordSet->fields['vtourimages_description']));
						$file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_file_name']);
						// $imageID = $misc->make_db_unsafe ($recordSet->fields['vtourimages_id']);
						if ($caption == '') {
							$caption = 'Virtual Tour Image ' . $a;
						}

						$vtopts .= "<option value=\"$a\">$caption</option> \n";
						$vtparams .= "'<param name=\"pano$a\" value=\"&#123;file=$config[vtour_view_images_path]/$file_name&#125;&#123;auto=0.1&#125;&#123;pan=-45&#125;&#123;fov=" . $config['vtour_fov'] . "&#125;\" />'+ \n";
						$album = "<param name=\"Album\" value=\"$config[vtour_view_images_path]/$file_name\" /> \n";
						$vtjs .= "tour[$a] = $description; \n";
						$a++;
						$ext = substr(strrchr($file_name, '.'), 1);
						$recordSet->MoveNext();
					} // end while
					$vtopts .= "</select></p>\n";
					$vtopts .= '</form>' . "\r\n";
				} // end if ($num_images > 0)


				if ($ext == 'jpg') { // if it's a jpg file then use PTViewer for spherical pano images

					// First Define the Javascript to be placed in the head
					$jscript .= '<script type="text/javascript">' . "\r\n";
					$jscript .= '<!--' . "\r\n";
					$jscript .= 'inittour = (' . $vtinit . '*1);' . "\r\n";
					$jscript .= 'tour = new Array();' . "\r\n";
					$jscript .= $vtjs;

					$jscript .= 'public static function swapTour(w)' . "\r\n";
					$jscript .= '{' . "\r\n";
					$jscript .= '	si = w.selectedIndex;' . "\r\n";
					$jscript .= '	x = w.options[si].value;' . "\r\n";
					$jscript .= '	n = (x*1);' . "\r\n";
					$jscript .= '	if (n >= 0)' . "\r\n";
					$jscript .= '		{' . "\r\n";
					$jscript .= '		newPano(n);' . "\r\n";
					$jscript .= '		newText(n);' . "\r\n";
					$jscript .= '		}' . "\r\n";
					$jscript .= '}' . "\r\n";

					$jscript .= 'public static function newPano(n)' . "\r\n";
					$jscript .= '{' . "\r\n";
					$jscript .= '	if(n)' . "\r\n";
					$jscript .= '	{' . "\r\n";
					$jscript .= '		if(getptv())' . "\r\n";
					$jscript .= '			{' . "\r\n";
					$jscript .= '			getptv().newPanoFromList(n);' . "\r\n";
					$jscript .= '			}' . "\r\n";
					$jscript .= '	}' . "\r\n";
					$jscript .= '	else' . "\r\n";
					$jscript .= '	{' . "\r\n";
					$jscript .= '	n=inittour;' . "\r\n";
					$jscript .= '	if(getptv())' . "\r\n";
					$jscript .= '	{' . "\r\n";
					$jscript .= '	getptv().newPanoFromList(n);' . "\r\n";
					$jscript .= '		}' . "\r\n";
					$jscript .= '	}' . "\r\n";
					$jscript .= '} ' . "\r\n";

					$jscript .= 'public static function newText(n,id)' . "\r\n";
					$jscript .= '{' . "\r\n";
					$jscript .= '	if(!id)' . "\r\n";
					$jscript .= '		{' . "\r\n";
					$jscript .= '		id=\'desc\';' . "\r\n";
					$jscript .= '		}' . "\r\n";
					$jscript .= '	if (document.layers)' . "\r\n";
					$jscript .= '		{' . "\r\n";
					$jscript .= '		x = document.layers[id];' . "\r\n";
					$jscript .= '		x.document.open();' . "\r\n";
					$jscript .= '		x.document.write(tour[n]);' . "\r\n";
					$jscript .= '		x.document.close();' . "\r\n";
					$jscript .= '		}' . "\r\n";
					$jscript .= '	else if(document.all)' . "\r\n";
					$jscript .= '		{' . "\r\n";
					$jscript .= '		x = eval(\'document.all.\' + id);' . "\r\n";
					$jscript .= '		x.innerHTML = tour[n];' . "\r\n";
					$jscript .= '		}' . "\r\n";
					$jscript .= '	else if (document.getElementById)' . "\r\n";
					$jscript .= '		{' . "\r\n";
					$jscript .= '		x = document.getElementById(id);' . "\r\n";
					$jscript .= '		x.innerHTML = \'\';' . "\r\n";
					$jscript .= '		x.innerHTML = tour[n];' . "\r\n";
					$jscript .= '		}' . "\r\n";
					$jscript .= '}' . "\r\n";

					$jscript .= 'public static function getptv()' . "\r\n";
					$jscript .= '{' . "\r\n";
					$jscript .= 'var forAll=\'\';' . "\r\n";
					$jscript .= '	if (document.ptviewer)' . "\r\n";
					$jscript .= '	{' . "\r\n";
					$jscript .= '	forAll = document.ptviewer;' . "\r\n";
					$jscript .= '	}' . "\r\n";
					$jscript .= '	else if (document.applets)' . "\r\n";
					$jscript .= '	{' . "\r\n";
					$jscript .= '	forAll = document.applets[\'ptviewer\'];' . "\r\n";
					$jscript .= '	}' . "\r\n";
					$jscript .= '	else if (document.getElementById)' . "\r\n";
					$jscript .= '	{' . "\r\n";
					$jscript .= '	forAll = document.getElementById(\'ptviewer\');' . "\r\n";
					$jscript .= '	}' . "\r\n";
					$jscript .= '	else if (document.getElementByName)' . "\r\n";
					$jscript .= '	{' . "\r\n";
					$jscript .= '	forAll = document.getElementByName(\'ptviewer\');' . "\r\n";
					$jscript .= '	}' . "\r\n";
					$jscript .= 'return forAll;' . "\r\n";
					$jscript .= '}' . "\r\n";

					$jscript .= 'public static function AutorotationStartRight()' . "\r\n";
					$jscript .= '{' . "\r\n";
					$jscript .= 'getptv().startAutoPan(0.1, 0.0, 1.0 );' . "\r\n";
					$jscript .= '}' . "\r\n";

					$jscript .= 'public static function AutorotationStartLeft()' . "\r\n";
					$jscript .= '{' . "\r\n";
					$jscript .= 'getptv().startAutoPan(-0.1,0.0,1.0);' . "\r\n";
					$jscript .= '}' . "\r\n";

					$jscript .= 'public static function AutorotationStop()' . "\r\n";
					$jscript .= '{' . "\r\n";
					$jscript .= 'getptv().stopAutoPan();' . "\r\n";
					$jscript .= '}' . "\r\n";

					$jscript .= 'public static function ZoomItIn()' . "\r\n";
					$jscript .= '{' . "\r\n";
					$jscript .= 'getptv().startAutoPan(0, 0, .995);' . "\r\n";
					$jscript .= '}' . "\r\n";

					$jscript .= 'public static function ZoomItOut()' . "\r\n";
					$jscript .= '{' . "\r\n";
					$jscript .= 'getptv().startAutoPan(0, 0, 1.005);' . "\r\n";
					$jscript .= '}' . "\r\n";

					$jscript .= 'public static function StopItZoom()' . "\r\n";
					$jscript .= '{' . "\r\n";
					$jscript .= 'getptv().stopAutoPan();' . "\r\n";
					$jscript .= '}' . "\r\n";

					$jscript .= 'if (document.images)' . "\r\n";
					$jscript .= '	{            // Active Images' . "\r\n";
					$jscript .= '	backon = new Image();' . "\r\n";
					$jscript .= '	backon.src = "' . $config['template_url'] . '/images/vtour_backon.gif";' . "\r\n";

					$jscript .= '	backoff = new Image();' . "\r\n";
					$jscript .= '	backoff.src = "' . $config['template_url'] . '/images/vtour_back.gif";' . "\r\n";

					$jscript .= '	pauseon = new Image();' . "\r\n";
					$jscript .= '	pauseon.src = "' . $config['template_url'] . '/images/vtour_pauseon.gif";' . "\r\n";

					$jscript .= '	pauseoff = new Image();' . "\r\n";
					$jscript .= '	pauseoff.src = "' . $config['template_url'] . '/images/vtour_pause.gif";' . "\r\n";

					$jscript .= '	forwardon = new Image();' . "\r\n";
					$jscript .= '	forwardon.src = "' . $config['template_url'] . '/images/vtour_forwardon.gif";' . "\r\n";

					$jscript .= '	forwardoff = new Image();' . "\r\n";
					$jscript .= '	forwardoff.src = "' . $config['template_url'] . '/images/vtour_forward.gif";' . "\r\n";

					$jscript .= '	zoom_outon = new Image();' . "\r\n";
					$jscript .= '	zoom_outon.src = "' . $config['template_url'] . '/images/vtour_zoom_outon.gif";' . "\r\n";

					$jscript .= '	zoom_outoff = new Image();' . "\r\n";
					$jscript .= '	zoom_outoff.src = "' . $config['template_url'] . '/images/vtour_zoom_out.gif";' . "\r\n";

					$jscript .= '	zoom_inon = new Image();' . "\r\n";
					$jscript .= '	zoom_inon.src = "' . $config['template_url'] . '/images/vtour_zoom_inon.gif";' . "\r\n";

					$jscript .= '	zoom_inoff = new Image();' . "\r\n";
					$jscript .= '	zoom_inoff.src = "' . $config['template_url'] . '/images/vtour_zoom_in.gif";' . "\r\n";
					$jscript .= '	}' . "\r\n";

					$jscript .= '// public static function to \'activate\' images.' . "\r\n";
					$jscript .= 'public static function imgOn(imgName) {' . "\r\n";
					$jscript .= '		if (document.images) {' . "\r\n";
					$jscript .= '			document.images[imgName].src = eval(imgName + "on.src");' . "\r\n";
					$jscript .= '		}' . "\r\n";
					$jscript .= '}' . "\r\n";

					$jscript .= '// public static function to \'deactivate\' images.' . "\r\n";
					$jscript .= 'public static function imgOff(imgName) {' . "\r\n";
					$jscript .= '		if (document.images) {' . "\r\n";
					$jscript .= '			document.images[imgName].src = eval(imgName + "off.src");' . "\r\n";
					$jscript .= '		}' . "\r\n";
					$jscript .= '}' . "\r\n";

					$jscript .= '-->' . "\r\n";
					$jscript .= '</script>' . "\r\n";


					// Code for the {vtour} Tag Replacement
					$bar_y = $config['vtour_height'] - 10;
					$show_ptviewer = '<script type="text/javascript">' . "\r\n";
					$show_ptviewer .= '<!--' . "\r\n";
					$show_ptviewer .= 'ptoutput(\'<applet code="ptviewer.class" archive="ptviewer.jar" height="' . $config['vtour_height'] . '" width="' . $config['vtour_width'] . '" id="ptviewer" name="ptviewer">\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="code" value="ptviewer" />\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="archive" value="ptviewer.jar" />\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="quality" value="3" />\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="pan" value="180" />\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="view_height" value="' . $config['vtour_height'] . '" />\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="mass" value="20" />\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="bar_y" value="' . $bar_y . '" />\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="bar_x" value="0" />\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="cursor" value="move" />\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="wait" value="' . $config['template_url'] . '/images/vtour-load.jpg" />\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="barcolor" value="FF0000" />\'+' . "\r\n";
					$show_ptviewer .= '\'<param name="bar_width" value="' . $config['vtour_width'] . '" />\'+' . "\r\n";
					$show_ptviewer .= $vtparams;
					$show_ptviewer .= '\'<\/applet>\');' . "\r\n";
					$show_ptviewer .= '//-->' . "\r\n";
					$show_ptviewer .= '</script>' . "\r\n";

					// Replace all the vtour tags
					$page->page = str_replace('{vtour}', $show_ptviewer, $page->page);

					$vtour_left_button = '<a onmouseover="imgOn(\'back\')" onmouseout="imgOff(\'back\')" onmousedown="AutorotationStartLeft()"><img src="' . $config['template_url'] . '/images/vtour_back.gif" id="back" alt="Back" /></a>' . "\r\n";
					$page->page = str_replace('{vtour_left_button}', $vtour_left_button, $page->page);

					$vtour_pause_button = '<a onmouseover="imgOn(\'pause\')" onmouseout="imgOff(\'pause\')" onmousedown="AutorotationStop()"><img src="' . $config['template_url'] . '/images/vtour_pause.gif" id="pause" alt="Pause" /></a>' . "\r\n";
					$page->page = str_replace('{vtour_pause_button}', $vtour_pause_button, $page->page);

					$vtour_right_button = '<a onmouseover="imgOn(\'forward\')" onmouseout="imgOff(\'forward\')" onmousedown="AutorotationStartRight()"><img src="' . $config['template_url'] . '/images/vtour_forward.gif" id="forward" alt="Forward" /></a>' . "\r\n";
					$page->page = str_replace('{vtour_right_button}', $vtour_right_button, $page->page);

					$vtour_zoomout_button = '<a onmouseover="imgOn(\'zoom_out\')" onmouseout="imgOff(\'zoom_out\')" onmousedown="ZoomItOut()" onmouseup="StopItZoom()"><img src="' . $config['template_url'] . '/images/vtour_zoom_out.gif" id="zoom_out" alt="Zoom Out" /></a>' . "\r\n";
					$page->page = str_replace('{vtour_zoomout_button}', $vtour_zoomout_button, $page->page);

					$vtour_zoomin_button = '<a onmouseover="imgOn(\'zoom_in\')" onmouseout="imgOff(\'zoom_in\')" onmousedown="ZoomItIn()" onmouseup="StopItZoom()"><img src="' . $config['template_url'] . '/images/vtour_zoom_in.gif" id="zoom_in" alt="Zoom In" /></a>' . "\r\n";
					$page->page = str_replace('{vtour_zoomin_button}', $vtour_zoomin_button, $page->page);

					$page->page = str_replace('{vtour_select}', $vtopts, $page->page);

					$vtour_description = '<div id="desc"></div>' . "\r\n";
					$page->page = str_replace('{vtour_description}', $vtour_description, $page->page);

					// Need to have an onload command in the body tag or else the vtour doesn't load the text description properly
					$onload = 'onload="newText(inittour)"';
					$page->page = str_replace('{onload}', $onload, $page->page);


				} elseif ($ext == 'egg') { // if it's a .egg then use the egg solution for their proprietory file format
					$egg_solution = '<!--[if !IE]>-->' . "\r\n";
					$egg_solution .= '<object codetype="application/java" classid="java:EggApplet.class" archive="' . $config['baseurl'] . '/e3D.jar" width="' . $config['vtour_width'] . '" height="' . $config['vtour_height'] . '">' . "\r\n";
					$egg_solution .= $album;
					$egg_solution .= '<param name="Icons" value="' . $config['baseurl'] . '/applet.ear" />' . "\r\n";
					$egg_solution .= '</object>' . "\r\n";
					$egg_solution .= '<!--<![endif]-->' . "\r\n";
					$egg_solution .= '<object classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93"  codebase="http://java.sun.com/update/1.5.0/jinstall-1_5_0-windows-i586.cab" width="' . $config['vtour_width'] . '" height="' . $config['vtour_height'] . '">' . "\r\n";
					$egg_solution .= '<param name="code" value="EggApplet" />' . "\r\n";
					$egg_solution .= '<param name="archive" value="' . $config['baseurl'] . '/e3D.jar" />' . "\r\n";
					$egg_solution .= $album;
					$egg_solution .= '<param name="Icons" value="' . $config['baseurl'] . '/applet.ear" />' . "\r\n";
					$egg_solution .= '</object>' . "\r\n";

					// Replace all the vtour tags
					$page->page = str_replace('{vtour}', $egg_solution, $page->page);

					$vtour_left_button = '';
					$page->page = str_replace('{vtour_left_button}', $vtour_left_button, $page->page);

					$vtour_pause_button = '';
					$page->page = str_replace('{vtour_pause_button}', $vtour_pause_button, $page->page);

					$vtour_right_button = '';
					$page->page = str_replace('{vtour_right_button}', $vtour_right_button, $page->page);

					$vtour_zoomout_button = '';
					$page->page = str_replace('{vtour_zoomout_button}', $vtour_zoomout_button, $page->page);

					$vtour_zoomin_button = '';
					$page->page = str_replace('{vtour_zoomin_button}', $vtour_zoomin_button, $page->page);

					$vtopts = '';
					$page->page = str_replace('{vtour_select}', $vtopts, $page->page);

					$vtour_description = '';
					$page->page = str_replace('{vtour_description}', $vtour_description, $page->page);

					// Need to have an onload command in the body tag or else the vtour doesn't load the text description properly
					$onload = '';
					$page->page = str_replace('{onload}', $onload, $page->page);

				} //end elseif $ext = egg

				else { // if it's not a .jpg or .egg let them know it's not supported.

					$unsupported_vtour = $lang['unsupported_vtour'];

					// Replace all the vtour tags
					$page->page = str_replace('{vtour}', $unsupported_vtour, $page->page);

					$vtour_left_button = '';
					$page->page = str_replace('{vtour_left_button}', $vtour_left_button, $page->page);

					$vtour_pause_button = '';
					$page->page = str_replace('{vtour_pause_button}', $vtour_pause_button, $page->page);

					$vtour_right_button = '';
					$page->page = str_replace('{vtour_right_button}', $vtour_right_button, $page->page);

					$vtour_zoomout_button = '';
					$page->page = str_replace('{vtour_zoomout_button}', $vtour_zoomout_button, $page->page);

					$vtour_zoomin_button = '';
					$page->page = str_replace('{vtour_zoomin_button}', $vtour_zoomin_button, $page->page);

					$vtopts = '';
					$page->page = str_replace('{vtour_select}', $vtopts, $page->page);

					$vtour_description = '';
					$page->page = str_replace('{vtour_description}', $vtour_description, $page->page);

					// Need to have an onload command in the body tag or else the vtour doesn't load the text description properly
					$onload = '';
					$page->page = str_replace('{onload}', $onload, $page->page);


				} //end else $ext = Unsupported
				if ($popup == false) {
					$page->page = $page->remove_template_block('vtour_header', $page->page);
					$page->page = $page->remove_template_block('vtour_footer', $page->page);
					$page->page = $page->remove_template_block('vtour_content', $page->page);
				} else {
					$page->page = $page->cleanup_template_block('vtour_header', $page->page);
					$page->page = $page->cleanup_template_block('vtour_footer', $page->page);
					$page->page = $page->cleanup_template_block('vtour_content', $page->page);
				}
				$page->page = str_replace('{template_url}', $config['template_url'], $page->page);
				$display = $page->return_page();
			} // end elseif ($listingID != "")
			else {
				$display .= "<a href=\"index.php\">$lang[perhaps_you_were_looking_something_else]</a>";
			}
		}else {
			$display .= "<a href=\"index.php\">$lang[perhaps_you_were_looking_something_else]</a>";
		}
		return $display;
	} // end public static function showvtour


	public static function rendervtourlink($listingID, $use_small_image = false)
	{
		// shows the images connected to a given image
		global $config, $lang, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// grab the images
		$listingID_sql = $misc->make_db_extra_safe($listingID);
		$output = '';
		// $sql = "SELECT vtourimages_id FROM " . $config['table_prefix'] . "vtourimages WHERE (listingsdb_id = $listingID_sql) ORDER BY vtourimages_rank";
		$sql = "SELECT vtourimages_file_name FROM " . $config['table_prefix'] . "vtourimages WHERE (listingsdb_id = $listingID_sql) ORDER BY vtourimages_rank";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		$num_images = $recordSet->RecordCount();
		if ($num_images > 0) {
			while (!$recordSet->EOF) {
				$file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_file_name']);
				$ext = substr(strrchr($file_name, '.'), 1);
				$recordSet->MoveNext();
			} // end while
			if ($ext == 'jpg' || $ext == 'egg') { // if it's a supported VTour then display the link button
				if ($use_small_image === true) {
					$image = 'vtourbuttonsmall.jpg';
				}else {
					$image = 'vtourbutton.jpg';
				}
				if (file_exists($config['template_path'] . '/images/' . $image)) {
					$output .= '<a href="index.php?action=show_vtour&amp;popup=blank&amp;listingID=' . $listingID . '" onclick="window.open(\'index.php?action=show_vtour&amp;popup=blank&amp;listingID=' . $listingID . '\',\'\',\'width=' . $config['vt_popup_width'] . ',height=' . $config['vt_popup_height'] . '\');return false;"><img src="' . $config['template_url'] . '/images/' . $image . '" alt="' . $lang['click_here_for_vtour'] . '" /></a>';
				}else {
					$output = '<a href="index.php?action=show_vtour&amp;popup=blank&amp;listingID=' . $listingID . '" onclick="window.open(\'index.php?action=show_vtour&amp;popup=blank&amp;listingID=' . $listingID . '\',\'\',\'width=' . $config['vt_popup_width'] . ',height=' . $config['vt_popup_height'] . '\');return false;">' . $lang['click_here_for_vtour'] . '</a>';
				}
			} //end if it's a supported VTour
		} // end if ($num_images > 0)
		return $output;
	} // end public static function rendervtourlink

	public static function goodvtour($listingID)
	{
		global $lang, $conn, $config, $jscript;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$ext = 'bad';
		if (isset($_GET['listingID'])) {
			if ($_GET['listingID'] != "") {
				$listingID = intval($listingID);
				$sql = "SELECT vtourimages_file_name, vtourimages_rank FROM " . $config['table_prefix'] . "vtourimages WHERE (listingsdb_id = $listingID) ORDER BY vtourimages_rank";
				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
			}
			$num_images = $recordSet->RecordCount();
			if ($num_images > 0) {
				while (!$recordSet->EOF) {
					$file_name = $misc->make_db_unsafe ($recordSet->fields['vtourimages_file_name']);
					$ext = substr(strrchr($file_name, '.'), 1);
					$recordSet->MoveNext();
				} // end while
			} // end if ($num_images > 0)
		}
		if ($ext == 'jpg' || $ext == 'egg') {
			return true;
		}
		else {
			return false;
		}
	} //end goodvtour function

} // end vtours class
?>