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
global $config;
class search_page {
	function browse_all_listings_pclass_link() {
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();

		if($_GET['pclass'][0] == ''){
			return search_page::browse_all_listings_link();
		}
		$class_sql = '';
		$class_url = '';
		foreach($_GET['pclass'] as $x => $y){
			$_GET['pclass'][$x]=intval($y);
		}
		foreach($_GET['pclass'] as $class){
			$class_url .= '&amp;pclass[]='.$class;
		}
		$class_sql = implode(',',$_GET['pclass']);
		$url = '<a href="index.php?action=searchresults'.$class_url.'">' . $lang['browse_all_listings_in_pclass'];
		if ($config['configured_show_count'] == 1) {
			$sql = "SELECT listingsdb_title FROM " . $config['table_prefix'] . "listingsdb," . $config['table_prefix_no_lang'] . "classlistingsdb  WHERE listingsdb_active = 'yes' AND class_id IN ($class_sql) AND " . $config['table_prefix'] . "listingsdb.listingsdb_id = " . $config['table_prefix_no_lang'] . "classlistingsdb.listingsdb_id";
			if ($config['use_expiration'] === "1") {
				$sql .= " AND listingsdb_expiration > " . $conn->DBDate(time());
			}
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			$num_listings = $recordSet->RecordCount();
			$display = $url . ' (' . $num_listings . ')</a>';
		}else {
			$display = $url . '</a>';
		}
		return $display;
	}
	function browse_all_listings_link()
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		if ($config['url_style'] == '1') {
			$url = '<a href="index.php?action=searchresults">' . $lang['browse_all_listings'];
		}else {
			$url = '<a href="searchresults.html">' . $lang['browse_all_listings'];
		}
		if ($config['configured_show_count'] == 1) {
			$sql = "SELECT listingsdb_title FROM " . $config['table_prefix'] . "listingsdb WHERE listingsdb_active = 'yes'";
			if ($config['use_expiration'] === "1") {
				$sql .= " AND listingsdb_expiration > " . $conn->DBDate(time());
			}
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			$num_listings = $recordSet->RecordCount();
			$display = $url . ' (' . $num_listings . ')</a>';
		}else {
			$display = $url . '</a>';
		}
		return $display;
	} // end function browse_all_listings
	function create_search_page_logic()
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// First find out how many property classes exist.
		$sql = 'SELECT count(class_id) as num_classes FROM ' . $config['table_prefix'] . 'class';
		$recordSet = $conn->Execute($sql);
		if (!$recordSet) {
			$misc->log_error($sql);
		}
		$class_count = $recordSet->fields['num_classes'];
		if ($class_count > 1) {
			// Multiple Classes Exist show new search page.
			return search_page::create_class_searchpage();
		}else {
			return search_page::create_searchpage();
		}
	}
	function create_class_searchpage()
	{
		global $config, $lang, $conn, $jscript;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// Get List of all Classes
		$sql = 'SELECT class_name, class_id FROM ' . $config['table_prefix'] . 'class ORDER BY class_rank';
		$recordSet = $conn->Execute($sql);
		if (!$recordSet) {
			$misc->log_error($sql);
		}
		$class_count = $recordSet->RecordCount();
		$class_checkbox = '';
		$x = 1;
		while (!$recordSet->EOF) {
			$class_id = $recordSet->fields['class_id'];
			$class_name = $recordSet->fields['class_name'];
			$class_checkbox .= '<input name="pclass[]" value="' . $class_id . '" type="checkbox" id="class' . $x . '" onclick="SearchClassUnCheckALL()" />' . $class_name . '<br />';
			$recordSet->MoveNext();
			$x++;
		}
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$page = new page_user();
		$page->load_page($config['template_path'] . '/search_class_default.html');
		$page->page = str_replace('{property_class_checkboxes}', $class_checkbox, $page->page);
		// Set the JS
		$jscript .= "<script type=\"text/javascript\">\r\n";
		$jscript .= "<!--\r\n";

		$jscript .= "function SearchClassCheckALL() {
	for (var j = 1; j <= " . $class_count . "; j++) {
		box = eval(\"document.getElementById('class'+j)\");
		if (document.getElementById('class0').checked == true) {
			if (box.checked == true) box.checked = false;
		}
	}
}";
		$jscript .= "function SearchClassUnCheckALL() {
		if (document.getElementById('class0').checked == true) {
			 document.getElementById('class0').checked = false;
		}
}";
		$jscript .= "//-->\r\n";
		$jscript .= "</script>\r\n";
		return $page->page;
	}
	function create_searchpage($template_tag = false, $no_results = false)
	{
		global $config, $lang, $conn;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// Determine if we are searching for a particular property class
		$class_sql = '';
		if (!isset($_GET['pclass'])) {
			$_GET['pclass'] = '';
		}
		if (is_array($_GET['pclass'])) {
			foreach ($_GET['pclass'] as $c => $class) {
				// Ignore non numberic values
				if (is_numeric($class)) {
					if (!empty($class_sql)) {
						$class_sql .= ' OR ';
					}
					$class_sql .= $config['table_prefix_no_lang'] . "classformelements.class_id = $class";
				}else{
					unset($_GET['pclass'][$c]);
				}
			}
		}
		// Determine users login status.
		$display_status = false;
		$display_priv = 'listingsformelements_display_priv = 0';
		$display_status = login::loginCheck('Member', true);
		if ($display_status == true) {
			$display_priv = '(listingsformelements_display_priv = 0 OR listingsformelements_display_priv = 1)';
		}
		$display_status = login::loginCheck('Agent', true);
		if ($display_status == true) {
			$display_priv = '(listingsformelements_display_priv = 0 OR listingsformelements_display_priv = 1 OR listingsformelements_display_priv = 2)';
		}
		// Get all searchable fields and display them
		if (empty($class_sql)) {
			$sql = "SELECT listingsformelements_id, listingsformelements_search_label, listingsformelements_search_type, listingsformelements_field_name FROM " . $config['table_prefix'] . "listingsformelements WHERE  listingsformelements_searchable = 1 AND $display_priv ORDER BY listingsformelements_search_rank";
		}else {
			$sql = "SELECT DISTINCT(" . $config['table_prefix'] . "listingsformelements.listingsformelements_id), listingsformelements_search_label, listingsformelements_search_type, listingsformelements_field_name, listingsformelements_search_rank FROM " . $config['table_prefix'] . "listingsformelements, " . $config['table_prefix_no_lang'] . "classformelements WHERE " . $config['table_prefix'] . "listingsformelements.listingsformelements_id = " . $config['table_prefix_no_lang'] . "classformelements.listingsformelements_id AND ($class_sql) AND listingsformelements_searchable = 1 AND $display_priv ORDER BY listingsformelements_search_rank";
		}

		$rs = $conn->Execute ($sql);
		if (!$rs) {
			$misc->log_error($sql);
		}
		$field_data = '';
		while (!$rs->EOF) {
			// Check for Translations if needed
			if (!isset($_SESSION["users_lang"])) {
				$search_label = $misc->make_db_unsafe ($rs->fields['listingsformelements_search_label']);
			}else {
				$listingsformelements_id = $rs->fields['listingsformelements_id'];
				$lang_sql = "SELECT listingsformelements_search_label FROM " . $config['lang_table_prefix'] . "listingsformelements WHERE listingsformelements_id = $listingsformelements_id";
				$lang_recordSet = $conn->Execute($lang_sql);
				if (!$lang_recordSet) {
					$misc->log_error($lang_sql);
				}
				$search_label = $misc->make_db_unsafe ($lang_recordSet->fields['listingsformelements_search_label']);
			}

			$search_type = $misc->make_db_unsafe($rs->fields['listingsformelements_search_type']);
			$field_name = $misc->make_db_unsafe($rs->fields['listingsformelements_field_name']);

			$field_data .= search_page::searchbox_render($search_label, $field_name, $_GET['pclass'], $search_type);

			$rs->MoveNext();
		}
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$page = new page_user;
		if(is_array($_GET['pclass'])){
			$class = $_GET['pclass'][0];

			if(file_exists($config['template_path'] . '/search_page_class_'.$class.'.html')){
				$page->load_page($config['template_path'] . '/search_page_class_'.$class.'.html');
			}else{
				$page->load_page($config['template_path'] . '/search_page_default.html');
			}
		}else{
			$page->load_page($config['template_path'] . '/search_page_default.html');
		}

		if ($template_tag == true) {
			$page->page = $page->get_template_section('templated_search_form');
		}else {
			$page->page = str_replace('{templated_search_form}', '', $page->page);
			$page->page = str_replace('{/templated_search_form}', '', $page->page);
		}
		$class_inputs = '';
		if (is_array($_GET['pclass'])) {
			foreach ($_GET['pclass'] as $class) {
				$class_inputs .= '<input type="hidden" name="pclass[]" value="' . $class . '" />';
			}
		}
		if ($class_inputs == '') {
			$class_inputs .= '<input type="hidden" name="pclass[]" value="" />';
		}
		$page->page = str_replace('{search_type}', $class_inputs, $page->page);
		$page->replace_tags(array('featured_listings_horizontal', 'featured_listings_vertical'));

		$page->page = $page->parse_template_section($page->page, 'browse_all_listings', search_page::browse_all_listings_link());
		$page->page = $page->parse_template_section($page->page, 'browse_all_listings_pclass', search_page::browse_all_listings_pclass_link());

		$page->page = $page->parse_template_section($page->page, 'search_fields', $field_data);
		$page->page = $page->parse_template_section($page->page, 'agent_searchbox', search_page::searchbox_agentdropdown());
		$page->page = $page->parse_template_section($page->page, 'lat_long_dist_search', search_page::searchbox_latlongdist());
		$page->page = $page->parse_template_section($page->page, 'postalcode_dist_search', search_page::searchbox_postaldist());
		$page->page = $page->parse_template_section($page->page, 'city_dist_search', search_page::searchbox_citydist());
		$ImagesOnlySet='';
		if(isset($_GET['imagesOnly']) && $_GET['imagesOnly']=='yes'){
			$ImagesOnlySet='checked="checked"';
		}
		$page->page = $page->parse_template_section($page->page, 'show_only_with_images', '<input type="checkbox" name="imagesOnly" '.$ImagesOnlySet.' value="yes" />');
		$VtourOnlySet='';
		if(isset($_GET['vtoursOnly']) && $_GET['vtoursOnly']=='yes'){
			$VtourOnlySet='checked="checked"';
		}
		$page->page = $page->parse_template_section($page->page, 'show_only_with_vtours', '<input type="checkbox" name="vtoursOnly" '.$VtourOnlySet.' value="yes" />');
		if(isset($_GET['searchtext']) && $_GET['searchtext'] !=''){
			$page->page = $page->parse_template_section($page->page, 'full_text_search', '<input type="text" name="searchtext" value="'.$_GET['searchtext'].'" />');
		}else{
			$page->page = $page->parse_template_section($page->page, 'full_text_search', '<input type="text" name="searchtext" />');
		}

		if ($no_results == false) {
			$page->replace_template_section('no_search_results_block', '');
		}else {
			$page->page = $page->cleanup_template_block('no_search_results', $page->page);
			// Generate a Saved search link
			$guidestring_no_action = '';
			foreach ($_GET as $k => $v) {
				if ($v != '' && $k != 'cur_page' && $k != 'PHPSESSID' && $k != 'action' && $k != 'printer_friendly' && $k !='template') {
					if (is_array($v)) {
						foreach ($v as $vitem) {
							$guidestring_no_action .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
						}
					}else {
						$guidestring_no_action .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
					}
				}
			}
			$save_search_link = 'index.php?action=save_search' . $guidestring_no_action;
			$page->page = $page->parse_template_section($page->page, 'save_search_link', $save_search_link);
		}
		return $page->page;
	} //End Function create_searchpage
	/**
	* **************************************************************************\
	* Open-Realty - search_results Function										*
	* --------------------------------------------								*
	*   This is the search_results function. The listing_browse page is called is*
	* also now a funciton called search_results_old								*
	* \**************************************************************************
	*/
	function search_results($return_ids_only = false)
	{
		$DEBUG_SQL = FALSE;
		global $config, $conn, $lang, $current_ID,$db_type;;
		require_once($config['basepath'] . '/include/misc.inc.php');
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$misc = new misc();
		$page = new page();
		// Load any addons
		$addons = $page->load_addons();
		$guidestring = "";
		$guidestring_with_sort = "";
		// Save GET
		// Deal with &amp; still being in the URL
		foreach ($_GET as $k => $v) {
			if (strpos($k, 'amp;') !== false) {
				$new_k = str_replace('amp;', '', $k);
				$_GET[$new_k] = $v;
				unset($_GET[$k]);
			}
		}
		//Deal with googlebot double encoding URLS.
		foreach ($_GET as $k => $v) {
			if (strpos($k, '%5B%5D') !== false) {
				$new_k = str_replace('%5B%5D', '', $k);
				$_GET[$new_k][] = $v;
				unset($_GET[$k]);
			}
		}

		foreach ($_GET as $k => $v) {
			if ($v != '' && $k != 'listingID' && $k != 'cur_page' && $k != 'action' && $k != 'PHPSESSID' && $k != 'sortby' && $k != 'sorttype' && $k != 'printer_friendly' && $k !='template') {
				if (is_array($v)) {
					foreach ($v as $vitem) {
						$guidestring .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
					}
				}else {
					$guidestring .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
				}
			}
		}
		$display = '';
		// Now we get the GET and build our WHERE CLAUSE
		$searchresultSQL = '';
		// Set ImageONly to False
		$imageonly = false;
		$vtoursonly = false;
		$tablelist = array();
		$tablelist_fullname = array();
		$postalcode_dist_lat = '';
		$postalcode_dist_long = '';
		$postalcode_dist_dist = '';
		$latlong_dist_lat = '';
		$latlong_dist_long = '';
		$latlong_dist_dist = '';
		$city_dist_lat = '';
		$city_dist_long = '';
		$city_dist_dist = '';
		foreach ($_GET as $k => $v) {
			if ($k == "sortby") {
				$guidestring_with_sort = "$k=$v";
			}elseif ($k == "sorttype") {
				$guidestring_with_sort = "$k=$v&amp;";
			}elseif($k == 'PageID'){
				$searchresultSQL .= '';
			}elseif ($k == "user_ID") {
				if ($v != '' && $v != 'Any Agent') {
					if(is_array($v)){
						$sstring = '';
						foreach($v as $u){
							$u = $misc->make_db_safe($u);
							if(empty($sstring)){
								$sstring .=  $config['table_prefix'] . 'listingsdb.userdb_id = '.$u;
							}else{
								$sstring .=  ' OR ' . $config['table_prefix'] . 'listingsdb.userdb_id = '.$u;
							}
						}
						if ($searchresultSQL != '') {
							$searchresultSQL .= ' AND ';
						}
						$searchresultSQL .=  '(' . $sstring. ')';
					}else{
						$sql_v = $misc->make_db_safe($v);
						if ($searchresultSQL != '') {
							$searchresultSQL .= ' AND ';
						}
						$searchresultSQL .= '(' . $config['table_prefix'] . 'listingsdb.userdb_id = ' . $sql_v . ')';
					}

				}
			}elseif ($k == "featuredOnly") {
				// $guidestring .= "&amp;$k=$v";
				if ($v == "yes") {
					if ($searchresultSQL != '') {
						$searchresultSQL .= ' AND ';
					}
					$searchresultSQL = $searchresultSQL . '(' . $config['table_prefix'] . 'listingsdb.listingsdb_featured = \'yes\')';
				}
			}elseif ($k == 'pclass') {
				$class_sql = '';
				foreach ($v as $class) {
					// Ignore non numberic values
					if (is_numeric($class)) {
						if (!empty($class_sql)) {
							$class_sql .= ' OR ';
						}
						$class_sql .= $config['table_prefix_no_lang'] . "classlistingsdb.class_id = $class";
					}
				}
				if (!empty($class_sql)) {
					if ($searchresultSQL != '') {
						$searchresultSQL .= ' AND ';
					}
					$searchresultSQL = $searchresultSQL . '(' . $class_sql . ') AND ' . $config['table_prefix_no_lang'] . 'classlistingsdb.listingsdb_id = ' . $config['table_prefix'] . 'listingsdb.listingsdb_id';
					$tablelist_fullname[] = $config['table_prefix_no_lang'] . "classlistingsdb";
				}
			}elseif ($k == "listing_id") {
				$listing_id = explode(',', $v);
				$i = 0;
				if ($searchresultSQL != '') {
					$searchresultSQL .= ' AND ';
				}
				foreach ($listing_id as $id) {
					$id = $misc->make_db_safe($id);
					if ($i == 0) {
						$searchresultSQL .= '((' . $config['table_prefix'] . 'listingsdb.listingsdb_id = ' . $id . ')';
					}else {
						$searchresultSQL .= ' OR (' . $config['table_prefix'] . 'listingsdb.listingsdb_id = ' . $id . ')';
					}
					$i++;
				}
				$searchresultSQL .= ')';
			}elseif ($k == "imagesOnly") {
				// Grab only listings with images if that is what we need.
				if ($v == "yes") {
					$imageonly = true;
				}
			}elseif ($k == "vtoursOnly") {
				// Grab only listings with images if that is what we need.
				if ($v == "yes") {
					$vtoursonly = true;
				}
			}elseif($k == 'listing_last_modified_equal'){
				if ($searchresultSQL != '') {
					$searchresultSQL .= ' AND ';
				}
				$safe_v = $conn->DBTimeStamp($v);
				$searchresultSQL .= " listingsdb_last_modified = $safe_v";
				//listingsdb_last_modified
			}elseif($k == 'listing_last_modified_greater'){
				if ($searchresultSQL != '') {
					$searchresultSQL .= ' AND ';
				}
				$safe_v = $conn->DBTimeStamp($v);
				$searchresultSQL .= " listingsdb_last_modified > $safe_v";
				//listingsdb_last_modified
			}elseif($k == 'listing_last_modified_less'){
				if ($searchresultSQL != '') {
					$searchresultSQL .= ' AND ';
				}
				$safe_v =$conn->DBTimeStamp($v);
				$searchresultSQL .= " listingsdb_last_modified < $safe_v";
				//listingsdb_last_modified
			}elseif($k == 'latlong_dist_lat' || $k == 'latlong_dist_long' || $k == 'latlong_dist_dist' && $v !=''){
				switch($k){
					case 'latlong_dist_lat':
						$latlong_dist_lat = $v;
						break;
					case 'latlong_dist_long':
						$latlong_dist_long = $v;
						break;
					case 'latlong_dist_dist':
						$latlong_dist_dist = $v;
						break;
				}
			}elseif($k == 'postalcode_dist_code' && $v !=''){
				$postalcode = $misc->make_db_safe($v);
				$sql = 'SELECT zipdist_latitude, zipdist_longitude FROM '.$config['table_prefix_no_lang'].'zipdist WHERE zipdist_zipcode ='.$postalcode;
				$postalcode_recordSet = $conn->Execute($sql);
				if ($postalcode_recordSet === false) {
					$misc->log_error($sql);
				}
				$postalcode_dist_lat = $misc->make_db_unsafe($postalcode_recordSet->fields['zipdist_latitude']);
				$postalcode_dist_long = $misc->make_db_unsafe($postalcode_recordSet->fields['zipdist_longitude']);
			}elseif($k == 'postalcode_dist_dist' && $v !=''){
				$postalcode_dist_dist = $v;
			}elseif($k == 'city_dist_code' && $v !=''){
				$city = $misc->make_db_safe($v);
				$sql = 'SELECT zipdist_latitude, zipdist_longitude FROM '.$config['table_prefix_no_lang'].'zipdist WHERE zipdist_cityname ='.$city;
				$city_recordSet = $conn->Execute($sql);
				if ($city_recordSet === false) {
					$misc->log_error($sql);
				}
				$city_dist_lat = $misc->make_db_unsafe($city_recordSet->fields['zipdist_latitude']);
				$city_dist_long = $misc->make_db_unsafe($city_recordSet->fields['zipdist_longitude']);

			}elseif($k == 'city_dist_dist' && $v !=''){
				$city_dist_dist = $v;
			}elseif ($v != '' && $k != 'listingID' && $k != 'postalcode_dist_code' && $k != 'postalcode_dist_dist' && $k != 'city_dist_code' && $k != 'city_dist_dist' && $k != 'latlong_dist_lat' && $k != 'latlong_dist_long' && $k != 'latlong_dist_dist' && $k != 'cur_page' && $k != 'action' && $k != 'PHPSESSID' && $k != 'sortby' && $k != 'sorttype' && $k != 'printer_friendly' && $k !='template' && $k != 'pclass' && $k != 'listing_last_modified_less' && $k != 'listing_last_modified_equal' && $k != 'listing_last_modified_greater') {
				if (!is_array($v)) {
					if ($searchresultSQL != '') {
						$searchresultSQL .= ' AND ';
					}
					//Handle NULL/NOTNULL Searches
					if (substr($k, -5) == '-NULL' && $v == '1') {
						$subk = substr($k, 0, -5);
						$searchresultSQL .= "(`$subk`.listingsdbelements_field_name = '$subk' AND (`$subk`.listingsdbelements_field_value IS NULL OR `$subk`.listingsdbelements_field_value = ''))";
						if (!in_array($subk, $tablelist)) {
							$tablelist[] = $subk;
						}
					}elseif (substr($k, -8) == '-NOTNULL' && $v == '1') {
						$subk = substr($k, 0, -8);
						$searchresultSQL .= "(`$subk`.listingsdbelements_field_name = '$subk' AND (`$subk`.listingsdbelements_field_value IS NOT NULL  AND `$subk`.listingsdbelements_field_value <> ''))";
						if (!in_array($subk, $tablelist)) {
							$tablelist[] = $subk;
						}
					}
					//Handle Min/Max Searches
					elseif (substr($k, -4) == '-max') {
						$subk = substr($k, 0, -4);
						if ($db_type == 'mysql') {
							$searchresultSQL .= "(`$subk`.listingsdbelements_field_name = '$subk' AND CAST(`$subk`.listingsdbelements_field_value as signed) <= '$v')";
						}else {
							$searchresultSQL .= "(`$subk`.listingsdbelements_field_name = '$subk' AND CAST(`$subk`.listingsdbelements_field_value as int4) <= '$v')";
						}

						if (!in_array($subk, $tablelist)) {
							$tablelist[] = $subk;
						}
					}else if (substr($k, -4) == '-min') {
						$subk = substr($k, 0, -4);

						if ($db_type == 'mysql') {
							$searchresultSQL .= "(`$subk`.listingsdbelements_field_name = '$subk' AND CAST(`$subk`.listingsdbelements_field_value as signed) >= '$v')";
						}else {
							$searchresultSQL .= "(`$subk`.listingsdbelements_field_name = '$subk' AND CAST(`$subk`.listingsdbelements_field_value as int4) >= '$v')";
						}
						if (!in_array($subk, $tablelist)) {
							$tablelist[] = $subk;
						}

					}elseif (substr($k, -8) == '-maxdate') {
						if ($config['date_format']==1) {
							$format="%m/%d/%Y";
						}
						elseif ($config['date_format']==2) {
							$format="%Y/%d/%m";
						}
						elseif ($config['date_format']==3) {
							$format="%d/%m/%Y";
						}
						$v=$misc->parseDate($v,$format);
						$subk = urldecode(substr($k, 0, -8));
						$searchresultSQL .= "(`$subk`.listingsdbelements_field_name = '$subk' AND `$subk`.listingsdbelements_field_value <= '$v')";
						if (!in_array($subk, $tablelist)) {
							$tablelist[] = $subk;
						}
					}else if (substr($k, -8) == '-mindate') {
						if ($config['date_format']==1) {
							$format="%m/%d/%Y";
						}
						elseif ($config['date_format']==2) {
							$format="%Y/%d/%m";
						}
						elseif ($config['date_format']==3) {
							$format="%d/%m/%Y";
						}
						$v=$misc->parseDate($v,$format);
						$subk = urldecode(substr($k, 0, -8));
						$searchresultSQL .= "(`$subk`.listingsdbelements_field_name = '$subk' AND `$subk`.listingsdbelements_field_value >= '$v')";
						if (!in_array($subk, $tablelist)) {
							$tablelist[] = $subk;
						}
					}else if (substr($k, -5) == '-date') {
						if ($config['date_format']==1) {
							$format="%m/%d/%Y";
						}
						elseif ($config['date_format']==2) {
							$format="%Y/%d/%m";
						}
						elseif ($config['date_format']==3) {
							$format="%d/%m/%Y";
						}
						$v=$misc->parseDate($v,$format);
						$subk = urldecode(substr($k, 0, -5));
						$searchresultSQL .= "(`$subk`.listingsdbelements_field_name = '$subk' AND `$subk`.listingsdbelements_field_value = '$v')";
						if (!in_array($subk, $tablelist)) {
							$tablelist[] = $subk;
						}
					}
					elseif ($k=='searchtext')
					{
						$safe_v = addslashes($v);
						$searchresultSQL .= "((`$k`.listingsdbelements_field_value like '%$safe_v%') OR (listingsdb_title like '%$safe_v%'))";
						$tablelist[] = $k;
					}else {
						$safe_v = $misc->make_db_safe($v);
						$searchresultSQL .= "(`$k`.listingsdbelements_field_name = '$k' AND `$k`.listingsdbelements_field_value = $safe_v)";
						$tablelist[] = $k;
					}
				}else {
					// Make Sure Array is not empty
					$use = false;
					$comma_separated = implode(" ", $v);
					if (trim($comma_separated) != '') {
						$use = true;
						if ($searchresultSQL != '') {
							$searchresultSQL .= ' AND ';
						}
					}
					if ($use === true) {
						if(substr($k,-3) == '_or'){
							$k = substr($k,0,strlen($k)-3);
							$safe_k = addslashes($k);
							$searchresultSQL .= "(`$safe_k`.listingsdbelements_field_name = '$safe_k' AND (";
							$vitem_count = 0;
							foreach ($v as $vitem) {
								$safe_vitem = addslashes($vitem);
								if ($vitem != '') {
									if ($vitem_count != 0) {
										$searchresultSQL .= " OR `$safe_k`.listingsdbelements_field_value LIKE '%$safe_vitem%'";
									}else {
										$searchresultSQL .= " `$safe_k`.listingsdbelements_field_value LIKE '%$safe_vitem%'";
									}
									$vitem_count++;
								}
							}
							$searchresultSQL .= "))";
							$tablelist[] = $safe_k;
						}else{
							$safe_k = addslashes($k);
							$searchresultSQL .= "(`$safe_k`.listingsdbelements_field_name = '$safe_k' AND (";
							$vitem_count = 0;
							foreach ($v as $vitem) {
								$safe_vitem = addslashes($vitem);
								if ($vitem != '') {
									if ($vitem_count != 0) {
										$searchresultSQL .= " AND `$safe_k`.listingsdbelements_field_value LIKE '%$safe_vitem%'";
									}else {
										$searchresultSQL .= " `$safe_k`.listingsdbelements_field_value LIKE '%$safe_vitem%'";
									}
									$vitem_count++;
								}
							}
							$searchresultSQL .= "))";
							$tablelist[] = $safe_k;
						}

					}
				}
			}
		}
		if($postalcode_dist_lat != '' && $postalcode_dist_long != '' && $postalcode_dist_dist != ''){
			$sql = "SELECT zipdist_zipcode FROM $config[table_prefix_no_lang]zipdist WHERE (POW((69.1*(zipdist_longitude-\"$postalcode_dist_long\")*cos($postalcode_dist_lat/57.3)),\"2\")+POW((69.1*(zipdist_latitude-\"$postalcode_dist_lat\")),\"2\"))<($postalcode_dist_dist*$postalcode_dist_dist) ";
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			$zipcodes = array();
			while (!$recordSet->EOF){
				$zipcodes[] = $recordSet->fields['zipdist_zipcode'];
				$recordSet->MoveNext();
			}
			$pc_field_name = $config["map_zip"];
			// Build Search Query
			// Make Sure Array is not empty
			$use = false;
			$comma_separated = implode(" ", $zipcodes);
			if (trim($comma_separated) != '') {
				$use = true;
				if ($searchresultSQL != '') {
					$searchresultSQL .= ' AND ';
				}
			}
			if ($use === true) {
				$searchresultSQL .= "(`$pc_field_name`.listingsdbelements_field_name = '$pc_field_name' AND (";
				$vitem_count = 0;
				foreach ($zipcodes as $vitem) {
					$safe_vitem = addslashes($vitem);
					if ($vitem != '') {
						if ($vitem_count != 0) {
							$searchresultSQL .= " OR `$pc_field_name`.listingsdbelements_field_value = '$save_vitem'";
						}else {
							$searchresultSQL .= " `$pc_field_name`.listingsdbelements_field_value = '$safe_vitem'";
						}
						$vitem_count++;
					}
				}
				$searchresultSQL .= "))";
				$tablelist[] = $pc_field_name;
			}

		}
		if($city_dist_lat != '' && $city_dist_long != '' && $city_dist_dist != ''){
			$sql = "SELECT zipdist_zipcode FROM $config[table_prefix_no_lang]zipdist WHERE (POW((69.1*(zipdist_longitude-\"$city_dist_long\")*cos($city_dist_lat/57.3)),\"2\")+POW((69.1*(zipdist_latitude-\"$city_dist_lat\")),\"2\"))<($city_dist_dist*$city_dist_dist) ";
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			$zipcodes = array();
			while (!$recordSet->EOF){
				$zipcodes[] = $recordSet->fields['zipdist_zipcode'];
				$recordSet->MoveNext();
			}
			$pc_field_name = $config["map_zip"];
			// Build Search Query
			// Make Sure Array is not empty
			$use = false;
			$comma_separated = implode(" ", $zipcodes);
			if (trim($comma_separated) != '') {
				$use = true;
				if ($searchresultSQL != '') {
					$searchresultSQL .= ' AND ';
				}
			}
			if ($use === true) {
				$searchresultSQL .= "(`$pc_field_name`.listingsdbelements_field_name = '$pc_field_name' AND (";
				$vitem_count = 0;
				foreach ($zipcodes as $vitem) {
					$safe_vitem = addslashes($vitem);
					if ($vitem != '') {
						if ($vitem_count != 0) {
							$searchresultSQL .= " OR `$pc_field_name`.listingsdbelements_field_value = '$safe_vitem'";
						}else {
							$searchresultSQL .= " `$pc_field_name`.listingsdbelements_field_value = '$safe_vitem'";
						}
						$vitem_count++;
					}
				}
				$searchresultSQL .= "))";
				$tablelist[] = $pc_field_name;
			}

		}
		//Lat Long Distance
		if($latlong_dist_lat != '' && $latlong_dist_long != '' && $latlong_dist_dist != '') {
			/*
			 max_lon = lon1 + arcsin(sin(D/R)/cos(lat1))
			 min_lon = lon1 - arcsin(sin(D/R)/cos(lat1))
			 max_lat = lat1 + (180/pi)(D/R)
			 min_lat = lat1 - (180/pi)(D/R)
			 */
			//$max_long = $latlong_dist_long + asin(sin($latlong_dist_dist/3956)/cos($latlong_dist_lat));
			//$min_long = $latlong_dist_long - asin(sin($latlong_dist_dist/3956)/cos($latlong_dist_lat));
			//$max_lat = $latlong_dist_lat + (180/pi())*($latlong_dist_dist/3956);
			//$min_lat = $latlong_dist_lat - (180/pi())*($latlong_dist_dist/3956);
			/*
			Latitude:
			Apparently a degree of latitude expressed in miles does
			vary slighty by latitude

			(http://www.ncgia.ucsb.edu/education/curricula/giscc/units/u014/tables/table01.html)
			but for our purposes, I suggest we use 1 degree latitude

			= 69 miles.



			Longitude:
			This is more tricky one since it varies by latitude
			(http://www.ncgia.ucsb.edu/education/curricula/giscc/units/u014/tables/table02.html).
			The

			simplest formula seems to be:
			1 degree longitude expressed in miles = cos (latitude) *
			69.17 miles
			*/
			//Get Correct Milage for ong based on lat.
			$cos_long=69.17;
			if($latlong_dist_lat>=10){
				$cos_long=68.13;
			}
			if($latlong_dist_lat>=20){
				$cos_long=65.03;
			}
			if($latlong_dist_lat>=30){
				$cos_long=59.95;
			}
			if($latlong_dist_lat>=40){
				$cos_long=53.06;
			}
			if($latlong_dist_lat>=50){
				$cos_long=44.55;
			}
			if($latlong_dist_lat>=60){
				$cos_long=34.67;
			}
			if($latlong_dist_lat>=70){
				$cos_long=23.73;
			}
			if($latlong_dist_lat>=80){
				$cos_long=12.05;
			}
			if($latlong_dist_lat>=90){
				$cos_long=0;
			}
			$max_long = $latlong_dist_long + $latlong_dist_dist/(cos(deg2rad($latlong_dist_lat))*$cos_long);
			$min_long = $latlong_dist_long - $latlong_dist_dist/(cos(deg2rad($latlong_dist_lat))*$cos_long);
			$max_lat = $latlong_dist_lat + $latlong_dist_dist/69;
			$min_lat = $latlong_dist_lat - $latlong_dist_dist/69;
			//
			if($max_lat<$min_lat){
				$max_lat2 = $min_lat;
				$min_lat = $max_lat;
				$max_lat = $max_lat2;
			}
			if($max_long<$min_long){
				$max_long2 = $min_long;
				$min_long = $max_long;
				$max_long = $max_long2;
			}
			// Lat and Long Fields
			$sql = "SELECT listingsformelements_field_name FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_field_type  = 'lat'";
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}

			$lat_field = $recordSet->fields['listingsformelements_field_name'];
			$sql = "SELECT listingsformelements_field_name FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_field_type  = 'long'";
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			$long_field =  $recordSet->fields['listingsformelements_field_name'];
			if($lat_field != '' & $long_field != '') {
				$tablelist[] = $lat_field;
				$tablelist[] = $long_field;
				if ($searchresultSQL != '') {
					$searchresultSQL .= ' AND ';
				}
				$searchresultSQL .= "(`$lat_field`.listingsdbelements_field_name = '$lat_field' AND `$lat_field`.listingsdbelements_field_value+0 <= '$max_lat')";
				if ($searchresultSQL != '') {
					$searchresultSQL .= ' AND ';
				}
				$searchresultSQL .= "(`$lat_field`.listingsdbelements_field_name = '$lat_field' AND `$lat_field`.listingsdbelements_field_value+0 >= '$min_lat')";
				if ($searchresultSQL != '') {
					$searchresultSQL .= ' AND ';
				}
				$searchresultSQL .= "(`$long_field`.listingsdbelements_field_name = '$long_field' AND `$long_field`.listingsdbelements_field_value+0 <= '$max_long')";
				if ($searchresultSQL != '') {
					$searchresultSQL .= ' AND ';
				}
				$searchresultSQL .= "(`$long_field`.listingsdbelements_field_name = '$long_field' AND `$long_field`.listingsdbelements_field_value+0 >= '$min_long')";
			}

		}
		// Handle Sorting
		// sort the listings
		// this is the main SQL that grabs the listings
		// basic sort by title..
		$group_order_text = '';
		$sortby = '';
		$sorttype = '';
		if ($config["special_sortby"] != 'none') {
			$sortby = $config["special_sortby"] . ',';
			$sorttype = $config["special_sorttype"] . ',';
		}
		if (!isset($_GET['sortby'])) {
			$_GET['sortby'] = $config["sortby"];
		}
		if (!isset($_GET['sorttype'])) {
			$_GET['sorttype'] = $config["sorttype"];
		}
		$sortby .= $_GET['sortby'];
		$sorttype .= $_GET['sorttype'];
		$sql_sort_type = '';
		$sortby_array = explode(',', $sortby);
		$sorttype_array = explode(',', $sorttype);
		$sort_text = '';
		$order_text = '';
		$group_order_text = '';
		$tablelist_nosort = $tablelist;
		$sort_count = count($sortby_array);
		for ($x = 0;$x < $sort_count;$x++) {
			//make sure user input is sanitized before adding to query string
			$sortby_array[$x]=$misc->sanitize($sortby_array[$x]);
			$sorttype_array[$x]=$misc->sanitize($sorttype_array[$x],4); //limit length to 4 characters as sorttype can only be ASC or DESC
			if ($sorttype_array[$x] != 'ASC' && $sorttype_array[$x] != 'DESC')
			{
				$sorttype_array[$x]='';
			}
			if ($sortby_array[$x] == 'listingsdb_id') {
				if ($x == 0) {
					$order_text .= 'ORDER BY listingsdb_id ' . $sorttype_array[$x];
				}else {
					$order_text .= ',listingsdb_id ' . $sorttype_array[$x];
				}
			}elseif ($sortby_array[$x] == 'listingsdb_title') {
				if ($x == 0) {
					$order_text .= 'ORDER BY listingsdb_title ' . $sorttype_array[$x];
				}else {
					$order_text .= ',listingsdb_title ' . $sorttype_array[$x];
				}
			}elseif ($sortby_array[$x] == 'random') {
				if ($x == 0) {
					$order_text .= 'ORDER BY rand() ' . $sorttype_array[$x];
				}else {
					$order_text .= ',rand() ' . $sorttype_array[$x];
				}
			}elseif ($sortby_array[$x] == 'listingsdb_featured') {
				if ($x == 0) {
					$order_text .= 'ORDER BY listingsdb_featured ' . $sorttype_array[$x];
				}else {
					$order_text .= ',listingsdb_featured ' . $sorttype_array[$x];
				}
			}elseif ($sortby_array[$x] == 'listingsdb_last_modified') {
				if ($x == 0) {
					$order_text .= 'ORDER BY listingsdb_last_modified ' . $sorttype_array[$x];
				}else {
					$order_text .= ',listingsdb_last_modified ' . $sorttype_array[$x];
				}
			}elseif ($sortby_array[$x] == 'pclass') {
				if ($searchresultSQL != '') {
					$searchresultSQL .= ' AND ';
				}
				$searchresultSQL .=  $config['table_prefix_no_lang'] . 'classlistingsdb.listingsdb_id = ' . $config['table_prefix'] . 'listingsdb.listingsdb_id AND '. $config['table_prefix_no_lang'] . 'classlistingsdb.class_id = '.$config['table_prefix'].'class.class_id ';
				$tablelist_fullname[] = $config['table_prefix_no_lang'] . "classlistingsdb";
				$tablelist_fullname[] = $config['table_prefix'].'class';
				if ($x == 0) {
					$order_text .= 'ORDER BY '.$config['table_prefix'].'class.class_name ' . $sorttype_array[$x];
				}else {
					$order_text .= ','.$config['table_prefix'].'class.class_name ' . $sorttype_array[$x];
				}
			}else {
				// Check if field is a number or price field and cast the order.
				$sort_by_field = $misc->make_db_extra_safe($sortby_array[$x]);;
				$sql_sort_type = 'SELECT listingsformelements_field_type FROM ' . $config['table_prefix'] . 'listingsformelements WHERE listingsformelements_field_name = ' . $sort_by_field;
				$recordSet_sort_type = $conn->Execute($sql_sort_type);
				if (!$recordSet_sort_type) {
					$misc->log_error($sql_sort_type);
				}
				$field_type = $recordSet_sort_type->fields['listingsformelements_field_type'];
				if ($field_type == 'price' || $field_type == 'number' || $field_type == 'decimal') {
					$tablelist[] = 'sort' . $x;
					$sort_text .= 'AND (sort' . $x . '.listingsdbelements_field_name = ' . $sort_by_field . ') ';
					global $db_type;
					if ($db_type == 'mysql') {
						if ($x == 0) {
							$order_text .= ' ORDER BY CAST(sort' . $x . '.listingsdbelements_field_value as signed) ' . $sorttype_array[$x];
							$group_order_text .= ',sort' . $x . '.listingsdbelements_field_value';
						}else {
							$order_text .= ',CAST(sort' . $x . '.listingsdbelements_field_value as signed) ' . $sorttype_array[$x];
							$group_order_text .= ',sort' . $x . '.listingsdbelements_field_value';
						}
					}else {
						if ($x == 0) {
							$order_text .= ' ORDER BY CAST(sort' . $x . '.listingsdbelements_field_value as int4) ' . $sorttype_array[$x];
							$group_order_text .= ',sort' . $x . '.listingsdbelements_field_value';
						}else {
							$order_text .= ',CAST(sort' . $x . '.listingsdbelements_field_value as int4) ' . $sorttype_array[$x];
							$group_order_text .= ',sort' . $x . '.listingsdbelements_field_value';
						}
					}
				}else {
					$tablelist[] = 'sort' . $x;
					$sort_text .= 'AND (sort' . $x . '.listingsdbelements_field_name = ' . $sort_by_field . ') ';
					if ($x == 0) {
						$order_text .= ' ORDER BY sort' . $x . '.listingsdbelements_field_value ' . $sorttype_array[$x];
					}
					else
					{
						$order_text .= ', sort' . $x . '.listingsdbelements_field_value ' . $sorttype_array[$x];
					}
					$group_order_text .= ',sort' . $x . '.listingsdbelements_field_value';
				}
			}
		}
		$group_order_text = $group_order_text . ' '.$order_text;

		if ($imageonly == true || $vtoursonly == true) {
			$order_text = "GROUP BY " . $config['table_prefix'] . "listingsdb.listingsdb_id, " . $config['table_prefix'] . "listingsdb.listingsdb_title " . $group_order_text;
		}
		if ($DEBUG_SQL) {
			echo '<strong>Sort Type SQL:</strong> ' . $sql_sort_type . '<br />';
			echo '<strong>Sort Text:</strong> ' . $sort_text . '<br />';
			echo '<strong>Order Text:</strong> ' . $order_text . '<br />';
		}

		$guidestring_with_sort = $guidestring_with_sort . $guidestring;
		// End of Sort
		$arrayLength = count($tablelist);
		if ($DEBUG_SQL) {
			echo '<strong>Table List Array Length:</strong> ' . $arrayLength . '<br />';
		}
		$string_table_list = '';
		for ($i = 0; $i < $arrayLength; $i++) {
			$string_table_list .= ' ,' . $config['table_prefix'] . 'listingsdbelements `' . $tablelist[$i].'`';
		}
		$arrayLength = count($tablelist_nosort);
		$string_table_list_no_sort = '';
		for ($i = 0; $i < $arrayLength; $i++) {
			$string_table_list_no_sort .= ' ,' . $config['table_prefix'] . 'listingsdbelements `' . $tablelist[$i].'`';
		}
		$arrayLength = count($tablelist_fullname);
		if ($DEBUG_SQL) {
			echo '<strong>Table List Array Length:</strong> ' . $arrayLength . '<br />';
		}
		for ($i = 0; $i < $arrayLength; $i++) {
			$string_table_list .= ' ,' . $tablelist_fullname[$i];
			$string_table_list_no_sort .= ' ,' . $tablelist_fullname[$i];
		}

		if ($DEBUG_SQL) {
			echo '<strong>Table List String:</strong> ' . $string_table_list . '<br />';
		}
		$arrayLength = count($tablelist);
		$string_where_clause = '';
		for ($i = 0; $i < $arrayLength; $i++) {
			$string_where_clause .= ' AND (' . $config['table_prefix'] . 'listingsdb.listingsdb_id = `' . $tablelist[$i] . '`.listingsdb_id)';
		}
		$arrayLength = count($tablelist_nosort);
		$string_where_clause_nosort = '';
		for ($i = 0; $i < $arrayLength; $i++) {
			$string_where_clause_nosort .= ' AND (' . $config['table_prefix'] . 'listingsdb.listingsdb_id = `' . $tablelist[$i] . '`.listingsdb_id)';
		}
		if ($imageonly) {
			$searchSQL = "SELECT distinct(" . $config['table_prefix'] . "listingsdb.listingsdb_id), " . $config['table_prefix'] . "listingsdb.userdb_id, " . $config['table_prefix'] . "listingsdb.listingsdb_title FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "listingsimages " . $string_table_list . " WHERE (listingsdb_active = 'yes') " . $string_where_clause . " AND (" . $config['table_prefix'] . "listingsimages.listingsdb_id = " . $config['table_prefix'] . "listingsdb.listingsdb_id) ";
			$searchSQLCount = "SELECT COUNT(distinct(" . $config['table_prefix'] . "listingsdb.listingsdb_id)) as total_listings FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "listingsimages " . $string_table_list_no_sort . " WHERE (listingsdb_active = 'yes') " . $string_where_clause_nosort . " AND (" . $config['table_prefix'] . "listingsimages.listingsdb_id = " . $config['table_prefix'] . "listingsdb.listingsdb_id) ";
		}elseif ($vtoursonly) {
			$searchSQL = "SELECT distinct(" . $config['table_prefix'] . "listingsdb.listingsdb_id), " . $config['table_prefix'] . "listingsdb.userdb_id, " . $config['table_prefix'] . "listingsdb.listingsdb_title FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "vtourimages " . $string_table_list . " WHERE (listingsdb_active = 'yes') " . $string_where_clause . " AND (" . $config['table_prefix'] . "vtourimages.listingsdb_id = " . $config['table_prefix'] . "listingsdb.listingsdb_id) ";
			$searchSQLCount = "SELECT COUNT(distinct(" . $config['table_prefix'] . "listingsdb.listingsdb_id)) as total_listings FROM " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix'] . "vtourimages " . $string_table_list_no_sort . " WHERE (listingsdb_active = 'yes') " . $string_where_clause_nosort . " AND (" . $config['table_prefix'] . "vtourimages.listingsdb_id = " . $config['table_prefix'] . "listingsdb.listingsdb_id) ";
		}else {
			$searchSQL = "SELECT distinct(" . $config['table_prefix'] . "listingsdb.listingsdb_id), " . $config['table_prefix'] . "listingsdb.userdb_id,  " . $config['table_prefix'] . "listingsdb.listingsdb_title FROM " . $config['table_prefix'] . "listingsdb " . $string_table_list . " WHERE (listingsdb_active = 'yes') " . $string_where_clause;
			$searchSQLCount = "SELECT COUNT(distinct(" . $config['table_prefix'] . "listingsdb.listingsdb_id)) as total_listings FROM " . $config['table_prefix'] . "listingsdb " . $string_table_list_no_sort . " WHERE (listingsdb_active = 'yes') " . $string_where_clause_nosort;
		}
		if ($searchresultSQL != '') {
			$searchSQL .= " AND " . $searchresultSQL;
			$searchSQLCount .= " AND " . $searchresultSQL;
		}
		if ($config['use_expiration'] == 1) {
			$searchSQL .= " AND (listingsdb_expiration > " . $conn->DBDate(time()) . ")";
			$searchSQLCount .= " AND (listingsdb_expiration > " . $conn->DBDate(time()) . ")";
		}
		$sql = $searchSQL . " $sort_text $order_text";
		$searchSQLCount = $searchSQLCount;
		// We now have a complete SQL Query. Now grab the results
		$recordSet = $conn->Execute($searchSQLCount);
		if ($DEBUG_SQL) {
			echo '<strong>Listing Count:</strong> ' . $searchSQLCount . '<br />';
		}
		if (!$recordSet) {
			$misc->log_error($searchSQLCount);
		}
		// We have the results so now we need to stack them in arrays to use with the search_result.html template file
		// Load the templste
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$page = new page_user();
		if(count($_GET['pclass']) == 1 && file_exists($config['template_path'] . '/search_results_class_'.$_GET['pclass'][0].'.html')){
			$page->load_page($config['template_path'] . '/search_results_class_'.$_GET['pclass'][0].'.html');
		}else{
			$page->load_page($config['template_path'] . '/' . $config['search_result_template']);
		}
		// Get header section
		$header_section = $page->get_template_section('search_result_header');
		$search_result = '';
		// Ok we have the header section now get the result section
		$search_result_section = $page->get_template_section('search_result_dataset');
		// Get the number of rows(records) we have.
		// $num_rows = $recordSet->RecordCount();
		$num_rows = $recordSet->fields['total_listings'];
		if ($return_ids_only === true) {
			// If we are returning IDs only for the notify listing then get the id and move on.
			$id = array();
			$resultRecordSet = $conn->Execute($sql);
			if (!$resultRecordSet) {
				$misc->log_error($sql);
			}
			if ($DEBUG_SQL) {
				echo '<strong>Search SQL:</strong> ' . $sql . '<br />';
			} while (!$resultRecordSet->EOF) {
				$id[] = $resultRecordSet->fields['listingsdb_id'];
				$resultRecordSet->MoveNext();
			} // while
			return $id;
		} elseif ($return_ids_only === 'perpage') {
			$id = array();
			if(!isset($_GET['cur_page'])){$_GET['cur_page']=0;}
			$limit_str = intval($_GET['cur_page']) * $config['listings_per_page'];
			$resultRecordSet = $conn->SelectLimit($sql, $config['listings_per_page'], $limit_str);
			if (!$resultRecordSet) {
				$misc->log_error($sql);
			}
			if ($DEBUG_SQL) {
				echo '<strong>Search SQL:</strong> ' . $sql . '<br />';
			} while (!$resultRecordSet->EOF) {
				$id[] = $resultRecordSet->fields['listingsdb_id'];
				$resultRecordSet->MoveNext();
			} // while
			return $id;
		} else {
			if ($num_rows > 0) {
				if (!isset($_GET['cur_page'])) {
					$_GET['cur_page'] = 0;
				}
				// build the string to select a certain number of listings per page
				$limit_str = intval($_GET['cur_page']) * $config['listings_per_page'];
				$num_records = $config['listings_per_page'];
				$some_num = intval($_GET['cur_page']) + 1;
				$this_page_max = $some_num * $config['listings_per_page'];
				// Check if we're setting a maximum number of search results
				if ($config["max_search_results"] > 0) {
					// Check if we've reached the max number of listings setting.
					if ($this_page_max > $config["max_search_results"]) {
						$num_records = $this_page_max - $config["max_search_results"];
					}
					// Failsafe check in case the max search results was set lower than the listings per page setting.
					if ($config["max_search_results"] < $config['listings_per_page']) {
						$num_records = $config["max_search_results"];
					}
					// Adjust the $num_rows for the next_prev function to show at the max the max results setting
					if ($num_rows > $config["max_search_results"]) {
						$num_rows = $config["max_search_results"];
					}
				}
				if ($config['show_next_prev_listing_page'] == 1) {
					// ************added for next prev navigation***********
					$newurl = '';
					foreach ($_GET as $k => $v) {
						if ($v && $k != 'cur_page' && $k != 'PHPSESSID' && $k != 'action') {
							if (is_array($v)) {
								foreach ($v as $vitem) {
									$newurl .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
								}
							}else {
								$newurl .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
							}
						}
					}
					$rtest = $conn->Execute($sql);
					if (!$rtest) {
						$misc->log_error($sql);
					}
					$_SESSION['results'] = array();
					$_SESSION['titles'] = array();
					while (!$rtest->EOF) {
						$ID = $rtest->fields['listingsdb_id'];
						$url_title=$rtest->fields['listingsdb_title'];
						$url_title = str_replace("/", "", $url_title);
						$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
						$url_title = str_replace(" ", "+", $url_title);
						$_SESSION['results'][] = $ID;
						$_SESSION['titles'][] = $url_title;
						$rtest->MoveNext();
					}
					$_SESSION['cur_page'] = intval($_GET['cur_page']);
					$_SESSION['searchstring'] = $newurl;
					$_SESSION['count'] = $num_rows;
					// ************added for next prev navigation***********
				}
				// Store the next_prev code as a variable to place in the template
				$next_prev = $misc->next_prev($num_rows, intval($_GET['cur_page']), $guidestring_with_sort);
				$next_prev_bottom = $misc->next_prev($num_rows, intval($_GET['cur_page']), $guidestring_with_sort, 'bottom');

				$resultRecordSet = $conn->SelectLimit($sql, $num_records, $limit_str);
				if (!$resultRecordSet) {
					$misc->log_error($sql);
				}
				if ($DEBUG_SQL) {
					echo '<strong>Search SQL:</strong> ' . $sql . '<br />';
				}
				// Get the the fields marked as browseable.
				$sql = "SELECT listingsformelements_id, listingsformelements_field_caption, listingsformelements_field_name, listingsformelements_display_priv, listingsformelements_search_result_rank FROM " . $config['table_prefix'] . "listingsformelements WHERE (listingsformelements_display_on_browse = 'Yes') AND (listingsformelements_field_type <> 'textarea') ORDER BY listingsformelements_search_result_rank";
				$recordSet = $conn->Execute($sql);
				$num_columns = $recordSet->RecordCount();
				// Get header_title
				$field_caption = $lang['title'];
				$field_name = "listingsdb_title";
				$sorttypestring = '';
				$sort_type_count = 0;
				foreach($sortby_array as $sortby) {
					if ($sortby == $field_name) {
						if (!isset($sorttype_array[$sort_type_count]) || $sorttype_array[$sort_type_count] == 'DESC') {
							$reverse_sort = 'ASC';
						}else {
							$reverse_sort = 'DESC';
						}
						$sorttypestring = 'sorttype=' . $reverse_sort;
					}
					$sort_type_count++;
				}
				if ($sorttypestring == '') {
					$sorttypestring = "sorttype=ASC";
				}
				// This is header_title it is the lang variable for title
				$header_title = '<a href="index.php?action=searchresults&amp;sortby=' . $field_name . '&amp;' . $sorttypestring . $guidestring . '">' . $field_caption . '</a>';
				$header_title_no_sort = $field_caption;

				// Get header_title
				$field_caption = $lang['header_pclass'];
				$field_name = "pclass";
				$sorttypestring = '';
				$sort_type_count = 0;
				foreach($sortby_array as $sortby) {
					if ($sortby == $field_name) {
						if (!isset($sorttype_array[$sort_type_count]) || $sorttype_array[$sort_type_count] == 'DESC') {
							$reverse_sort = 'ASC';
						}else {
							$reverse_sort = 'DESC';
						}
						$sorttypestring = 'sorttype=' . $reverse_sort;
					}
					$sort_type_count++;
				}
				if ($sorttypestring == '') {
					$sorttypestring = "sorttype=ASC";
				}
				// This is header_title it is the lang variable for title
				$header_pclass = '<a href="index.php?action=searchresults&amp;sortby=' . $field_name . '&amp;' . $sorttypestring . $guidestring . '">' . $field_caption . '</a>';
				$header_pclass_no_sort = $field_caption;

				$field = array();
				$field_no_sort = array();
				while (!$recordSet->EOF) {
					$x = $misc->make_db_unsafe($recordSet->fields['listingsformelements_search_result_rank']);
					// Check for Translations if needed
					if (!isset($_SESSION["users_lang"])) {
						$field_caption = $misc->make_db_unsafe ($recordSet->fields['listingsformelements_field_caption']);
					}else {
						$listingsformelements_id = $recordSet->fields['listingsformelements_id'];
						$lang_sql = "SELECT listingsformelements_field_caption FROM " . $config['lang_table_prefix'] . "listingsformelements WHERE listingsformelements_id = $listingsformelements_id";
						$lang_recordSet = $conn->Execute($lang_sql);
						if (!$lang_recordSet) {
							$misc->log_error($lang_sql);
						}
						if ($DEBUG_SQL) {
							echo '<strong>ML: Field Caption SQL:</strong> ' . $lang_sql . '<br />';
						}
						$field_caption = $misc->make_db_unsafe ($lang_recordSet->fields['listingsformelements_field_caption']);
					}

					$field_name = $misc->make_db_unsafe ($recordSet->fields['listingsformelements_field_name']);
					$display_priv = $misc->make_db_unsafe($recordSet->fields['listingsformelements_display_priv']);
					$display_status = false;
					if ($display_priv == 1) {
						$display_status = login::loginCheck('Member', true);
					}elseif ($display_priv == 2) {
						$display_status = login::loginCheck('Agent', true);
					}else {
						$display_status = true;
					}
					if ($display_status === true) {
						$sorttypestring = '';
						$sort_type_count = 0;
						foreach($sortby_array as $sortby) {
							if ($sortby == $field_name) {
								if (!isset($sorttype_array[$sort_type_count]) || $sorttype_array[$sort_type_count] == 'DESC') {
									$reverse_sort = 'ASC';
								}else {
									$reverse_sort = 'DESC';
								}
								$sorttypestring = 'sorttype=' . $reverse_sort;
							}
							$sort_type_count++;
						}
						if ($sorttypestring == '') {
							$sorttypestring = "sorttype=ASC";
						}
						$field[$x] = '<a href="index.php?action=searchresults&amp;sortby=' . $field_name . '&amp;' . $sorttypestring . $guidestring . '">' . $field_caption . '</a>';
						$field_no_sort[$x] = $field_caption;
					}
					$recordSet->MoveNext();
				} // end while
				// We have all the header information so we can now parse that section
				$header_section = $page->parse_template_section($header_section, 'header_title', $header_title);
				$header_section = $page->parse_template_section($header_section, 'header_title_no_sort', $header_title_no_sort);
				$header_section = $page->parse_template_section($header_section, 'header_pclass', $header_pclass);
				$header_section = $page->parse_template_section($header_section, 'header_pclass_no_sort', $header_pclass_no_sort);
				foreach ($field as $x => $f) {
					$header_section = $page->parse_template_section($header_section, 'header_' . $x, $f);
				}
				foreach ($field_no_sort as $x => $f) {
					$header_section = $page->parse_template_section($header_section, 'header_' . $x . '_no_sort', $f);
				}
				// We have the title now we need the image
				$num_columns = $num_columns + 1; // add one for the image
				$count = 0;
				while (!$resultRecordSet->EOF) {
					// Start a new section for each listing.
					$search_result .= $search_result_section;
					// alternate the colors
					if ($count == 0) {
						$count = $count + 1;
					}else {
						$count = 0;
					}

					$Title = $misc->make_db_unsafe ($resultRecordSet->fields['listingsdb_title']);
					$current_ID = $resultRecordSet->fields['listingsdb_id'];
					$or_owner = $resultRecordSet->fields['userdb_id'];
					if ($config['url_style'] == '1') {
						$url = '<a href="index.php?action=listingview&amp;listingID=' . $current_ID . '">';
					}else {
						$url_title = str_replace("/", "", $Title);
						$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
						$url = '<a href="listing-' . misc::urlencode_to_sef($url_title) . '-' . $current_ID . '.html">';
					}
					$field_title = $url . $Title . '</a>';
					// Insert the title as we grabbed it earlier
					$search_result = $page->parse_template_section($search_result, 'field_title', $field_title);
					$search_result = $page->parse_template_section($search_result, 'listingid', $current_ID);
					$search_result = $page->replace_listing_field_tags($current_ID, $search_result);
					//get distance for postal code distance searches
					if (isset($_GET['postalcode_dist_dist']))
					{
						$sql3 = "SELECT listingsdbelements_field_value FROM " . $config['table_prefix'] . "listingsdbelements WHERE ((listingsdb_id = $current_ID) AND (listingsdbelements_field_name = '".$config['map_zip']."'))";
						$recordSet3=$conn->Execute($sql3);
						$sql4 = 'SELECT zipdist_latitude, zipdist_longitude FROM '.$config['table_prefix_no_lang'].'zipdist WHERE zipdist_zipcode ='.$recordSet3->fields['listingsdbelements_field_value'];
						$recordSet4=$conn->Execute($sql4);
						$postalcode_distance=round($this->calculate_mileage($postalcode_dist_lat, $recordSet4->fields['zipdist_latitude'], $postalcode_dist_long, $recordSet4->fields['zipdist_longitude']),2).' '.$lang['postalcode_miles_away'];
						$search_result = $page->parse_template_section($search_result, 'postalcode_search_distance', $postalcode_distance);
					}
					// grab the rest of the listing's data
					$sql2 = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_display_priv, listingsformelements_search_result_rank  FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((listingsdb_id = $current_ID) AND (listingsformelements_display_on_browse = 'Yes')  AND (listingsdbelements_field_name = listingsformelements_field_name)) ORDER BY listingsformelements_search_result_rank";
					$recordSet2 = $conn->Execute($sql2);
					if ($DEBUG_SQL) {
						echo '<strong>Listing Data:</strong> ' . $sql2 . '<br />';
					}
					if (!$recordSet2) {
						$misc->log_error($sql2);
					}
					$field = array();
					$textarea = array();
					while (!$recordSet2->EOF) {
						$field_value = $misc->make_db_unsafe ($recordSet2->fields['listingsdbelements_field_value']);
						$field_type = $misc->make_db_unsafe ($recordSet2->fields['listingsformelements_field_type']);
						$display_priv = $misc->make_db_unsafe($recordSet2->fields['listingsformelements_display_priv']);
						$x = $misc->make_db_unsafe($recordSet2->fields['listingsformelements_search_result_rank']);
						$display_status = false;
						if ($display_priv == 1) {
							$display_status = login::loginCheck('Member', true);
						}elseif ($display_priv == 2) {
							$display_status = login::loginCheck('Agent', true);
						}else {
							$display_status = true;
						}
						if ($display_status === true) {
							switch ($field_type) {
								case 'textarea':
									if ($config['add_linefeeds'] === "1") {
										$textarea[$x] = nl2br($field_value);
									}else {
										$textarea[$x] = $field_value;
									}
									break;
								case "select-multiple":
								case "option":
								case "checkbox":
									// handle field types with multiple options
									$feature_index_list = explode("||", $field_value);
									$field[$x] = '';
									foreach($feature_index_list as $feature_list_item) {
										$field[$x] .= $feature_list_item;
										$field[$x] .= $config['feature_list_separator'];
									}
									break;
								case "price":
									$sql3 = "SELECT listingsdbelements_field_value FROM " . $config['table_prefix'] . "listingsdbelements WHERE ((listingsdb_id = $current_ID) AND (listingsdbelements_field_name = 'status'))";
									$recordSet3 = $conn->Execute($sql3);
									if (!$recordSet3) {
										$misc->log_error($sql3);
									}
									if ($DEBUG_SQL) {
										echo '<strong>Status Lookup for price field:</strong> ' . $sql3 . '<br />';
									}
									$status = $misc->make_db_unsafe ($recordSet3->fields['listingsdbelements_field_value']);
									$recordSet3->Close();
									if ($field_value == "" && $config["zero_price"] == "1") {
										$money_amount = $misc->international_num_format($field_value, $config['number_decimals_price_fields']);
										if ($status == 'Sold') {
											$field[$x] = "<span style=\"text-decoration: line-through\">";
											$field[$x] .= "</span><br /><span style=\"color:red;\"><strong>$lang[mark_as_sold]</strong></span>";
										}elseif ($status == 'Pending') {
											$field[$x] .= "<br /><span style=\"color:green;\"><strong>$lang[mark_as_pending]</strong></span>";
										}else {
											$field[$x] = $lang['call_for_price'];
										}
									} else {
										$money_amount = $misc->international_num_format($field_value, $config['number_decimals_price_fields']);
										if ($status == 'Sold') {
											$field[$x] = "<span style=\"text-decoration: line-through\">";
											$field[$x] .= $misc->money_formats($money_amount);
											$field[$x] .= "</span><br /><span style=\"color:red;\"><strong>$lang[mark_as_sold]</strong></span>";
										}elseif ($status == 'Pending') {
											$field[$x] = $misc->money_formats($money_amount);
											$field[$x] .= "<br /><span style=\"color:green;\"><strong>$lang[mark_as_pending]</strong></span>";
										}else {
											$field[$x] = $misc->money_formats($money_amount);
										}
									} // end else
									break;
								case "number":
									$field[$x] = $misc->international_num_format($field_value, $config['number_decimals_number_fields']);
									break;
								case "url":
									$field[$x] = "<a href=\"$field_value\" target=\"_blank\">$field_value</a>";
									break;
								case "email":
									$field[$x] = "<a href=\"mailto:$field_value\">$field_value</a>";
									break;
								case "date":
									if ($config['date_format']==1) {
										$format="m/d/Y";
									}
									elseif ($config['date_format']==2) {
										$format="Y/d/m";
									}
									elseif ($config['date_format']==3) {
										$format="d/m/Y";
									}
									if($field_value>0){
										$field_value=date($format,"$field_value");
									}
									$field[$x] = "$field_value";
									break;
								default:
									$field[$x] = "$field_value";
									break;
							} // end switch
						}
						$recordSet2->MoveNext();
					} // end while
					foreach ($field as $x => $f) {
						$search_result = $page->parse_template_section($search_result, 'field_' . $x, $f);
					}
					//Form URLS for TextArea
					if ($config['url_style'] == '1') {
						$preview = '... <a href="index.php?action=listingview&amp;listingID=' . $current_ID . '">' . $lang['more_info'] . '</a>';
					}else {
						$url_title = str_replace("/", "", $Title);
						$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
						$preview = '... <a href="listing-' . misc::urlencode_to_sef($url_title) . '-' . $current_ID . '.html">' . $lang['more_info'] . '</a>';
					}
					foreach ($textarea as $x => $f) {
						// Normal Textarea
						$search_result = $page->parse_template_section($search_result, 'textarea_' . $x, $f);
						// Short textarea of first number of characters defined in site config with link to the listing
						$p = substr(strip_tags($f), 0, $config['textarea_short_chars']);
						$p = substr($p, 0, strrpos($p,' '));
						$search_result = $page->parse_template_section($search_result, 'textarea_' . $x . '_short', $p.''.$preview);
					}
					//Cleanup Textareas
					$search_result = preg_replace('/{textarea_(.*?)_short}/',$preview, $search_result);
					$search_result = preg_replace('/{textarea_(.*?)}/', '', $search_result);
					// Show Vtour indicator Image if vtour exists
					require_once($config['basepath'] . '/include/vtour.inc.php');
					$vtour_link = vtours::rendervtourlink($current_ID, true);
					$search_result = $page->parse_template_section($search_result, 'vtour_button', $vtour_link);
					// Show Creation Date
					require_once($config['basepath'] . '/include/listing.inc.php');
					$get_creation_date = listing_pages::get_creation_date($current_ID);
					$search_result = $page->parse_template_section($search_result, 'get_creation_date', $get_creation_date);
					// Show Featured
					require_once($config['basepath'] . '/include/listing.inc.php');
					$get_featured = listing_pages::get_featured($current_ID, 'no');
					$search_result = $page->parse_template_section($search_result, 'get_featured', $get_featured);
					// Show Featured Raw
					require_once($config['basepath'] . '/include/listing.inc.php');
					$get_featured_raw = listing_pages::get_featured($current_ID, 'yes');
					$search_result = $page->parse_template_section($search_result, 'get_featured_raw', $get_featured_raw);
					// Show Modified Date
					require_once($config['basepath'] . '/include/listing.inc.php');
					$get_modified_date = listing_pages::get_modified_date($current_ID);
					$search_result = $page->parse_template_section($search_result, 'get_modified_date', $get_modified_date);
					// Start {isfavorite} search result template section tag
					if (isset($_SESSION['userID'])) {
						$userID = $misc->make_db_safe($_SESSION['userID']);
						$sql1 = "SELECT listingsdb_id FROM " . $config['table_prefix'] . "userfavoritelistings WHERE ((listingsdb_id = $current_ID) AND (userdb_id=$userID))";
						$recordSet1 = $conn->Execute($sql1);
						if ($recordSet1 === false) {
							$misc->log_error($sql1);
						}
						$favorite_listingsdb_id = $misc->make_db_unsafe ($recordSet1->fields['listingsdb_id']);
						if ($favorite_listingsdb_id !== $current_ID) {
							$isfavorite = "no";
							$search_result = $page->parse_template_section($search_result, 'isfavorite', $isfavorite);
						} else {
							$isfavorite = "yes";
							$search_result = $page->parse_template_section($search_result, 'isfavorite', $isfavorite);
						}
					}
					// End {isfavorite} search result template section tag
					// Show Delete From Favorites Link if needed
					$delete_from_fav = '';
					if (isset($_SESSION['userID'])) {
						$userID = $misc->make_db_safe($_SESSION['userID']);
						$sql = "SELECT listingsdb_id FROM " . $config['table_prefix'] . "userfavoritelistings WHERE ((listingsdb_id = $current_ID) AND (userdb_id=$userID))";
						$recordSet = $conn->Execute($sql);
						if (!$recordSet) {
							$misc->log_error($sql);
						}
						if ($DEBUG_SQL) {
							echo '<strong>Delete Favorite Lookup:</strong> ' . $sql . '<br />';
						}
						$num_rows = $recordSet->RecordCount();
						if ($num_rows > 0) {
							$delete_from_fav = '<a href="index.php?action=delete_favorites&amp;listingID=' . $current_ID . '" onclick="return confirmDelete()">' . $lang['delete_from_favorites'] . '</a>';
						}
					}
					// Instert link into section
					$search_result = $page->parse_template_section($search_result, 'delete_from_favorite', $delete_from_fav);
					//Show Add To Favorites
					$link_add_favorites = '';
					if (isset($_SESSION['userID'])) {
						$userID = $misc->make_db_safe($_SESSION['userID']);
						$sql = "SELECT listingsdb_id FROM " . $config['table_prefix'] . "userfavoritelistings WHERE ((listingsdb_id = $current_ID) AND (userdb_id=$userID))";
						$recordSet = $conn->Execute($sql);
						if (!$recordSet) {
							$misc->log_error($sql);
						}
						if ($DEBUG_SQL) {
							echo '<strong>Add Favorite Lookup:</strong> ' . $sql . '<br />';
						}
						$num_rows = $recordSet->RecordCount();
						if ($num_rows == 0) {
							$link_add_favorites = listing_pages::create_add_favorite_link();
						}
					}else{
						$link_add_favorites = listing_pages::create_add_favorite_link();
					}
					// Instert link into section
					$search_result = $page->parse_template_section($search_result, 'link_add_favorites', $link_add_favorites);
					// Insert row number
					$search_result = $page->parse_template_section($search_result, 'row_num_even_odd', $count);
					$resultRecordSet->MoveNext();
					// Replace Edit Listing links
					require_once($config['basepath'] . '/include/listing.inc.php');
					$edit_link = listing_pages::edit_listing_link();
					$search_result = $page->parse_template_section($search_result, 'link_edit_listing', $edit_link);
					$edit_link = listing_pages::edit_listing_link('yes');
					$search_result = $page->parse_template_section($search_result, 'link_edit_listing_url', $edit_link);
					// Replace addon fields.
					$addon_fields = $page->get_addon_template_field_list($addons);
					$search_result = $page->parse_addon_tags($search_result, $addon_fields);
					$search_result = $page->cleanup_fields($search_result);
					$search_result = $page->cleanup_images($search_result);
				} // end while
				$page->replace_template_section('search_result_header', $header_section);
				$page->replace_template_section('search_result_dataset', $search_result);
				$page->replace_permission_tags();
				$page->cleanup_template_sections($next_prev, $next_prev_bottom);
				$display = $page->return_page();
			} // end if ($num_rows > 0)
			else {
				if (!isset($_GET['cur_page'])) {
					$_GET['cur_page'] = 0;
				}
				// This search has no results. Display an error message and the search page again.
				$display .= search_page::create_searchpage(false, true);
			}

			return $display;
		}
	} //End Function search_results()
	function searchbox_latlongdist()
	{
		global $conn, $config, $lang;
		// start the row
		$display = '';
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display .= '<tr><th colspan="2" class="lat_long_header">'. $lang['distance_from_lat_log'] . '</th></tr>';
		$display .= '<tr><td class="searchpage_field_caption">'.$lang['lat'].'</td><td align="left"><input type="text" name="latlong_dist_lat" /></td></tr>';
		$display .= '<tr><td class="searchpage_field_caption">'.$lang['long'].'</td><td align="left"><input type="text" name="latlong_dist_long" /></td></tr>';
		$display .= '<tr><td class="searchpage_field_caption">'.$lang['distance'].'</td><td align="left"><input type="text" name="latlong_dist_dist" />'.$lang['miles'].'</td></tr>';
		return $display;
	}
	function searchbox_postaldist()
	{
		global $conn, $config, $lang;
		// start the row
		$display = '';
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display .= '<tr><th colspan="2" class="postalcode_distance_header">'. $lang['distance_from_zip'] . '</th></tr>';
		$display .= '<tr><td class="searchpage_field_caption">'.$lang['postal_code'].'</td><td align="left"><input type="text" name="postalcode_dist_code" /></td></tr>';
		$display .= '<tr><td class="searchpage_field_caption">'.$lang['distance'].'</td><td align="left"><input type="text" name="postalcode_dist_dist" />'.$lang['miles'].'</td></tr>';
		return $display;
	}
	function searchbox_citydist()
	{
		global $conn, $config, $lang;
		// start the row
		$display = '';
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display .= '<tr><th colspan="2" class="city_distance_header">'. $lang['distance_from_city'] . '</th></tr>';
		$display .= '<tr><td class="searchpage_field_caption">'.$lang['city'].'</td><td align="left"><input type="text" name="city_dist_code" /></td></tr>';
		$display .= '<tr><td class="searchpage_field_caption">'.$lang['distance'].'</td><td align="left"><input type="text" name="city_dist_dist" />'.$lang['miles'].'</td></tr>';
		return $display;
	}
	function searchbox_agentdropdown()
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// start the row
		$display = '';
		$display .= '<tr>';
		$display .= '<td align="right">';
		$display .= '<strong>' . $lang['search_by_agent'] . '</strong></td><td align="left">';
		$display .= '<select name="user_ID">';
		$display .= '<option value="">' . $lang['Any_Agent'] . '</option>';
		$sql = "select userdb_id, userdb_user_first_name,userdb_user_last_name from " . $config['table_prefix'] . "userdb where userdb_is_agent = 'yes' and userdb_active = 'yes'";
		// echo $sql;
		$recordSet = $conn->Execute($sql);
		while (!$recordSet->EOF) {
			$id = $misc->make_db_unsafe($recordSet->fields['userdb_id']);
			$user_name = $misc->make_db_unsafe($recordSet->fields['userdb_user_last_name']).', '.$misc->make_db_unsafe($recordSet->fields['userdb_user_first_name']);
			$display .= '<option value="' . $id . '">' . $user_name . '</option>';
			$recordSet->MoveNext();
		}
		$display .= '</select></td></tr>';
		return $display;
	} // end function searchbox_agentdropdown
	function searchbox_render ($browse_caption, $browse_field_name, $pclass, $searchbox_type)
	{
		// builds a searchbox for any given item you want
		// to let users search by
		global $conn, $config, $lang;
		$display = '';
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$time=$misc->getmicrotime();
		$class_sql = '';
		if (!empty($_GET['pclass'])) {
			$pclass=$_GET['pclass'];
		}
		if (!empty($pclass)) {
			//$classes = array();
			//$classes = explode('|', $_GET['pclass']);
			foreach ($pclass as $class) {
				// Ignore non numberic values
				if (is_numeric($class)) {
					if (!empty($class_sql)) {
						$class_sql .= ' OR ';
					}
					$class_sql .= $config['table_prefix_no_lang'] . "classlistingsdb.class_id = $class";
				}
			}
			if (!empty($class_sql)) {
				$class_sql = ' AND (' . $class_sql . ')';
			}
		}
		//Lookup Field Type
		$sql_browse_field_name = $misc->make_db_safe($browse_field_name);
		$sql = "SELECT listingsformelements_field_type FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_field_name = $sql_browse_field_name";
		$rsStepLookup = $conn->Execute($sql);
		if (!$rsStepLookup) {
			$misc->log_error($sql);
		}
		$field_type = $rsStepLookup->fields['listingsformelements_field_type'];
		unset($rsStepLookup);
		$sortby = '';
		$dateFormat=FALSE;
		if($field_type=='date'){$dateFormat=TRUE;}
		switch ($field_type) {
			case 'decimal':
				$sortby = 'ORDER BY listingsdbelements_field_value+0 ASC';
				break;
			case'number':
				global $db_type;
				if ($db_type == 'mysql') {
					$sortby = 'ORDER BY CAST(listingsdbelements_field_value as signed) ASC';
				}else{
					$sortby = 'ORDER BY CAST(listingsdbelements_field_value as int4) ASC';
				}
				break;
			default:
				$sortby = 'ORDER BY listingsdbelements_field_value ASC';
				break;
		}
		if (!empty($class_sql)) {
			if ($config['configured_show_count'] == 1) {
				$sql = "SELECT listingsdbelements_field_value, count(listingsdbelements_field_value) AS num_type FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsdb," . $config['table_prefix_no_lang'] . "classlistingsdb  WHERE listingsdbelements_field_name = '$browse_field_name' AND listingsdb_active = 'yes' AND listingsdbelements_field_value <> '' AND " . $config['table_prefix'] . "listingsdbelements.listingsdb_id = " . $config['table_prefix'] . "listingsdb.listingsdb_id AND " . $config['table_prefix'] . "listingsdb.listingsdb_id = " . $config['table_prefix_no_lang'] . "classlistingsdb.listingsdb_id $class_sql";
			}else {
				$sql = "SELECT listingsdbelements_field_value FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsdb," . $config['table_prefix_no_lang'] . "classlistingsdb  WHERE listingsdbelements_field_name = '$browse_field_name' AND listingsdb_active = 'yes' AND listingsdbelements_field_value <> '' AND " . $config['table_prefix'] . "listingsdbelements.listingsdb_id = " . $config['table_prefix'] . "listingsdb.listingsdb_id AND " . $config['table_prefix'] . "listingsdb.listingsdb_id = " . $config['table_prefix_no_lang'] . "classlistingsdb.listingsdb_id $class_sql";
			}
		}else {
			if ($config['configured_show_count'] == 1) {
				$sql = "SELECT listingsdbelements_field_value, count(listingsdbelements_field_value) AS num_type FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsdb WHERE listingsdbelements_field_name = '$browse_field_name' AND listingsdb_active = 'yes' AND listingsdbelements_field_value <> '' AND " . $config['table_prefix'] . "listingsdbelements.listingsdb_id = " . $config['table_prefix'] . "listingsdb.listingsdb_id ";
			}else {
				$sql = "SELECT listingsdbelements_field_value FROM " . $config['table_prefix'] . "listingsdbelements, "
				. $config['table_prefix'] . "listingsdb WHERE listingsdbelements_field_name = '$browse_field_name' AND listingsdb_active = 'yes' AND listingsdbelements_field_value <> '' AND " . $config['table_prefix'] . "listingsdbelements.listingsdb_id = " . $config['table_prefix'] . "listingsdb.listingsdb_id ";
			}
		}

		if ($config['use_expiration'] === "1") {
			$sql .= " AND listingsdb_expiration > " . $conn->DBDate(time());
		}
		$sql .= "GROUP BY " . $config['table_prefix'] . "listingsdbelements.listingsdbelements_field_value $sortby ";
		// echo $sql.'<br />';
		$recordSet = $conn->Execute($sql);
		if (!$recordSet) {
			$misc->log_error($sql);
		}
		//Get Date Format Settins
		if ($config['date_format']==1) {
			$format="m/d/Y";
		}
		elseif ($config['date_format']==2) {
			$format="Y/d/m";
		}
		elseif ($config['date_format']==3) {
			$format="d/m/Y";
		}
		switch ($searchbox_type) {
			case 'ptext':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left"><input name="'.$browse_field_name.'[]" type="text"';
				if(isset($_GET[$browse_field_name]) && $_GET[$browse_field_name] !=''){
					$f = htmlspecialchars($_GET[$browse_field_name], ENT_COMPAT, $config['charset']);
					$display .= 'value="'.$f.'"';
				}
				$display .= ' />';
				$display .= '</td></tr>';
				break;
			case 'pulldown':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left"><select name="'.$browse_field_name.'"><option value="">'.$lang['all'].'</option>';
				// if ($rental == "yes")
				while (!$recordSet->EOF) {
					$field_output = $misc->make_db_unsafe($recordSet->fields['listingsdbelements_field_value']);
					$selected='';
					if(isset($_GET[$browse_field_name]) && $_GET[$browse_field_name] ==$field_output){
						$selected = 'selected="selected"';
					}
					$num_type = '';
					if ($config['configured_show_count'] == 1) {
						$num_type = $recordSet->fields['num_type'];
						$num_type = "($num_type)";
					}
					if($dateFormat==TRUE){
						$display .= '<option value="'.$field_output.'" '.$selected.'>'.date($format,$field_output).' '.$num_type.'</option>';
					}else{
						if ($field_type == 'number')
						{
							$field_display = $misc->international_num_format($field_output, $config['number_decimals_number_fields']);
							$display .= '<option value="'.$field_output.'" '.$selected.'>'.$field_display.' '.$num_type.'</option>';
						} else {
							$display .= '<option value="'.$field_output.'" '.$selected.'>'.$field_output.' '.$num_type.'</option>';
						}
					}
					$recordSet->MoveNext();
				} // end while
				$display .= '</select></td></tr>';
				break;
			case 'null_checkbox':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left">';
				$num_type = '';
				if ($config['configured_show_count'] == 1) {
					$num_type = $recordSet->fields['num_type'];
					$num_type = '('.$num_type.')';
				}
				$setvalue ='';
				if(isset($_GET[$browse_field_name.'-NULL']) && $_GET[$browse_field_name.'-NULL'] == 1){
					$setvalue ='checked="checked"';
				}
				$display .= '<input type="checkbox" name="'.$browse_field_name.'-NULL" '.$setvalue.' value="1" />'.$browse_field_name.' '.$lang['null_search'].' '.$num_type.'<br />';
				$display .= '</td></tr>';
				break;
			case 'notnull_checkbox':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left">';
				$num_type = '';
				if ($config['configured_show_count'] == 1) {
					$num_type = $recordSet->fields['num_type'];
					$num_type = "($num_type)";
				}
				$setvalue ='';
				if(isset($_GET[$browse_field_name.'-NOTNULL']) && $_GET[$browse_field_name.'-NOTNULL'] == 1){
					$setvalue ='checked="checked"';
				}
				$display .= '<input type="checkbox" name="'.$browse_field_name.'-NOTNULL" '.$setvalue.' value="1" />'.$browse_field_name.' '.$lang['notnull_search'].' '.$num_type.'<br />';
				$display .= '</td></tr>';
				break;
			case 'select':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left"><select name="'.$browse_field_name.'[]" size="5" multiple="multiple">';
				$selected='';
				if(isset($_GET[$browse_field_name]) && is_array($_GET[$browse_field_name])){
					if(in_array('',$_GET[$browse_field_name])){
						$selected = 'selected="selected"';
					}
				}
				$display .= '<option value="" '.$selected.'>'.$lang['all'].'</option>';
				while (!$recordSet->EOF) {
					$field_output = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
					$selected='';
					if(isset($_GET[$browse_field_name]) && is_array($_GET[$browse_field_name])){
						if(in_array($field_output,$_GET[$browse_field_name])){
							$selected = 'selected="selected"';
						}
					}
					$num_type = '';
					if ($config['configured_show_count'] == 1) {
						$num_type = $recordSet->fields['num_type'];
						$num_type = "($num_type)";
					}
					if($dateFormat==TRUE){
						$display .= '<option value="'.$field_output.'" '.$selected.'>'.date($format,$field_output).' '.$num_type.'</option>';
					}else{
						if ($field_type == 'number')
						{
							$field_display = $misc->international_num_format($field_output, $config['number_decimals_number_fields']);
							$display .= '<option value="'.$field_output.'" '.$selected.'>'.$field_display.' '.$num_type.'</option>';
						} else {
							$display .= '<option value="'.$field_output.'" '.$selected.'>'.$field_output.' '.$num_type.'</option>';
						}
					}
					$recordSet->MoveNext();
				} // end while
				$display .= '</select></td></tr>';
				break;
			case 'select_or':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left"><select name="'.$browse_field_name.'_or[]" size="5" multiple="multiple">';
				$selected='';
				if(isset($_GET[$browse_field_name]) && is_array($_GET[$browse_field_name])){
					if(in_array('',$_GET[$browse_field_name])){
						$selected = 'selected="selected"';
					}
				}
				$display .= '<option value="" '.$selected.'>'.$lang['all'].'</option>';
				while (!$recordSet->EOF) {
					$field_output = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
					$selected='';
					if(isset($_GET[$browse_field_name]) && is_array($_GET[$browse_field_name])){
						if(in_array($field_output,$_GET[$browse_field_name])){
							$selected = 'selected="selected"';
						}
					}
					$num_type = '';
					if ($config['configured_show_count'] == 1) {
						$num_type = $recordSet->fields['num_type'];
						$num_type = "($num_type)";
					}
					if($dateFormat==TRUE){
						$display .= '<option value="'.$field_output.'" '.$selected.'>'.date($format,$field_output).' '.$num_type.'</option>';
					}else{
						if ($field_type == 'number')
						{
							$field_display = $misc->international_num_format($field_output, $config['number_decimals_number_fields']);
							$display .= '<option value="'.$field_output.'" '.$selected.'>'.$field_display.' '.$num_type.'</option>';
						} else {
							$display .= '<option value="'.$field_output.'" '.$selected.'>'.$field_output.' '.$num_type.'</option>';
						}
					}
					$recordSet->MoveNext();
				} // end while
				$display .= '</select></td></tr>';
				break;
			case 'checkbox':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left">';
				while (!$recordSet->EOF) {
					$field_output = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
					$selected='';
					if(isset($_GET[$browse_field_name]) && is_array($_GET[$browse_field_name])){
						if(in_array($field_output,$_GET[$browse_field_name])){
							$selected = 'checked="checked"';
						}
					}
					$num_type = '';
					if ($config['configured_show_count'] == 1) {
						$num_type = $recordSet->fields['num_type'];
						$num_type = "($num_type)";
					}
					if($dateFormat==TRUE){
						$display .= '<input type="checkbox" name="'.$browse_field_name.'[]" value="'.$field_output.'" '.$selected.' />'.date($format,$field_output).' '.$num_type.'';
						$display .= $config['search_list_separator'];
					}else{
						if ($field_type == 'number')
						{
							$field_display = $misc->international_num_format($field_output, $config['number_decimals_number_fields']);
							$display .= '<input type="checkbox" name="'.$browse_field_name.'[]" value="'.$field_output.'" '.$selected.' />'.$field_display.' '.$num_type.'';
							$display .= $config['search_list_separator'];
						} else {
							$display .= '<input type="checkbox" name="'.$browse_field_name.'[]" value="'.$field_output.'" '.$selected.' />'.$field_output.' '.$num_type.'';
							$display .= $config['search_list_separator'];
						}
					}
					$recordSet->MoveNext();
				} // end while
				$display .= '</td></tr>';
				break;
			case 'checkbox_or':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left">';
				while (!$recordSet->EOF) {
					$field_output = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
					$selected='';
				if(isset($_GET[$browse_field_name]) && is_array($_GET[$browse_field_name])){
						if(in_array($field_output,$_GET[$browse_field_name])){
							$selected = 'checked="checked"';
						}
					}
					$num_type = '';
					if ($config['configured_show_count'] == 1) {
						$num_type = $recordSet->fields['num_type'];
						$num_type = "($num_type)";
					}
					if($dateFormat==TRUE){
						$display .= '<input type="checkbox" name="'.$browse_field_name.'_or[]" value="'.$field_output.'" '.$selected.' />'.date($format,$field_output).' '.$num_type.'';
						$display .= $config['search_list_separator'];
					}else{
						if ($field_type == 'number')
						{
							$field_display = $misc->international_num_format($field_output, $config['number_decimals_number_fields']);
							$display .= '<input type="checkbox" name="'.$browse_field_name.'_or[]" value="'.$field_output.'" '.$selected.' />'.$field_display.' '.$num_type.'';
							$display .= $config['search_list_separator'];
						} else {
							$display .= '<input type="checkbox" name="'.$browse_field_name.'_or[]" value="'.$field_output.'" '.$selected.' />'.$field_output.' '.$num_type.'';
							$display .= $config['search_list_separator'];
						}

					}
					$recordSet->MoveNext();
				} // end while
				$display .= '</td></tr>';
				break;
			case 'option':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left">';
				while (!$recordSet->EOF) {
					$field_output = $misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']);
					$selected='';
					if(isset($_GET[$browse_field_name]) && $_GET[$browse_field_name] ==$field_output){
						$selected = 'checked="checked"';
					}
					$num_type = '';
					if ($config['configured_show_count'] == 1) {
						$num_type = $recordSet->fields['num_type'];
						$num_type = "($num_type)";
					}
					if($dateFormat==TRUE){
						$display .= '<input type="radio" name="'.$browse_field_name.'" value="'.$field_output.'" '.$selected.' />'.date($format,$field_output).' '.$num_type.'';
						$display .= $config['search_list_separator'];
					}else{
						if ($field_type == 'number')
						{
							$field_display = $misc->international_num_format($field_output, $config['number_decimals_number_fields']);
							$display .= '<input type="radio" name="'.$browse_field_name.'" value="'.$field_output.'" '.$selected.' />'.$field_display.' '.$num_type.'';
							$display .= $config['search_list_separator'];
						} else {
							$display .= '<input type="radio" name="'.$browse_field_name.'" value="'.$field_output.'" '.$selected.' />'.$field_output.' '.$num_type.'';
							$display .= $config['search_list_separator'];
						}

					}
					$recordSet->MoveNext();
				} // end while
				$display .= '</td></tr>';
				break;
			case 'optionlist':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left"><select name="' . $browse_field_name . '[]" multiple="multiple" size="6">';
				$r = $conn->execute("select listingsformelements_field_elements from " . $config['table_prefix'] . "listingsformelements where listingsformelements_field_name = '$browse_field_name'");
				$r = $r->fields['listingsformelements_field_elements'];
				$r = explode('||', $r);
				sort($r);
				foreach ($r as $f) {
					$selected='';
					if(isset($_GET[$browse_field_name]) && is_array($_GET[$browse_field_name])){
						if(in_array($f,$_GET[$browse_field_name])){
							$selected = 'selected="selected"';
						}
					}
					$f = htmlspecialchars($f, ENT_COMPAT, $config['charset']);
					$display .= '<option value="' . $f . '" '.$selected.'>' . $f . '</option>';
				}
				$display .= '</select></td></tr>';
				break;
			case 'optionlist_or':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left"><select name="' . $browse_field_name . '_or[]" multiple="multiple" size="6">';
				$r = $conn->execute("select listingsformelements_field_elements from " . $config['table_prefix'] . "listingsformelements where listingsformelements_field_name = '$browse_field_name'");
				$r = $r->fields['listingsformelements_field_elements'];
				$r = explode('||', $r);
				sort($r);
				foreach ($r as $f) {
					$selected='';
					if(isset($_GET[$browse_field_name]) && is_array($_GET[$browse_field_name])){
						if(in_array($f,$_GET[$browse_field_name])){
							$selected = 'selected="selected"';
						}
					}
					$f = htmlspecialchars($f, ENT_COMPAT, $config['charset']);
					$display .= '<option value="' . $f . '" '.$selected.'>' . $f . '</option>';
				}
				$display .= '</select></td></tr>';
				break;
			case 'fcheckbox':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left">';
				$r = $conn->Execute("select listingsformelements_field_elements from " . $config['table_prefix'] . "listingsformelements where listingsformelements_field_name = '$browse_field_name'");
				$r = $r->fields['listingsformelements_field_elements'];
				$r = explode('||', $r);
				sort($r);
				foreach ($r as $f) {
					$selected='';
					if(isset($_GET[$browse_field_name]) && is_array($_GET[$browse_field_name])){
						if(in_array($f,$_GET[$browse_field_name])){
							$selected = 'checked="checked"';
						}
					}
					$f = htmlspecialchars($f, ENT_COMPAT, $config['charset']);
					$display .= '<input type="checkbox" name="'.$browse_field_name.'[]" value="'.$f.'" '.$selected.' />'.$f.'';
					$display .= $config['search_list_separator'];
				}
				$display .= '</td></tr>';
				break;
			case 'fcheckbox_or':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left">';
				$r = $conn->Execute("select listingsformelements_field_elements from " . $config['table_prefix'] . "listingsformelements where listingsformelements_field_name = '$browse_field_name'");
				$r = $r->fields['listingsformelements_field_elements'];
				$r = explode('||', $r);
				sort($r);
				foreach ($r as $f) {
					$selected='';
					if(isset($_GET[$browse_field_name]) && is_array($_GET[$browse_field_name])){
						if(in_array($f,$_GET[$browse_field_name])){
							$selected = 'checked="checked"';
						}
					}
					$f = htmlspecialchars($f, ENT_COMPAT, $config['charset']);
					$display .= '<input type="checkbox" name="'.$browse_field_name.'_or[]" value="'.$f.'" '.$selected.' />'.$f.'';
					$display .= $config['search_list_separator'];
				}
				$display .= '</td></tr>';
				break;
			case 'fpulldown':
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td>';
				$display .= '<td align="left">';
				$display .= '<select name="' . $browse_field_name . '"><option value="">'.$lang['all'].'</option>';
				$r = $conn->Execute("select listingsformelements_field_elements from " . $config['table_prefix'] . "listingsformelements  where listingsformelements_field_name = '$browse_field_name'");
				$r = $r->fields['listingsformelements_field_elements'];
				$r = explode('||', $r);
				sort($r);
				foreach ($r as $f) {
					$selected='';
					if(isset($_GET[$browse_field_name]) && $_GET[$browse_field_name] == $f){
						$selected ='selected="selected"';
					}
					$f = htmlspecialchars($f, ENT_COMPAT, $config['charset']);
					$display .= '<option value="' . $f . '" '.$selected.'>' . $f . '</option>';
				}
				$display .= '</select></td></tr>';
				break;
			case 'daterange':
				static $js_added;
				$display = '';
				if (!$js_added) {
					// add date
					$display .= '<script type="text/javascript" src="' . $config['baseurl'] . '/dateformat.js"></script>' . "\r\n";
					$js_added = true;
				}
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td><td align="left">';
				$setvalue ='';
				if(isset($_GET[$browse_field_name.'-mindate']) && $_GET[$browse_field_name.'-mindate'] != ''){
					$f = htmlspecialchars($_GET[$browse_field_name.'-mindate'], ENT_COMPAT, $config['charset']);
					$setvalue ='value="'.$f.'"';
				}
				$display .= $lang['from'] . ' <input type="text" name="' . $browse_field_name . '-mindate" '.$setvalue.'  onFocus="javascript:vDateType=\'' . $config['date_format'] . '\'" onKeyUp="DateFormat(this,this.value,event,false,\'' . $config['date_format'] . '\')" onBlur="DateFormat(this,this.value,event,true,\'' . $config['date_format'] . '\')" /> (' . $config["date_format_long"] . ')<br />';
				$setvalue ='';
				if(isset($_GET[$browse_field_name.'-maxdate']) && $_GET[$browse_field_name.'-maxdate'] != ''){
					$f = htmlspecialchars($_GET[$browse_field_name.'-maxdate'], ENT_COMPAT, $config['charset']);
					$setvalue ='value="'.$f.'"';
				}
				$display .= $lang['to'] . '<input type="text" name="' . $browse_field_name . '-maxdate" '.$setvalue.'  onFocus="javascript:vDateType=\'' . $config['date_format'] . '\'" onKeyUp="DateFormat(this,this.value,event,false,\'' . $config['date_format'] . '\')" onBlur="DateFormat(this,this.value,event,true,\'' . $config['date_format'] . '\')" /> (' . $config["date_format_long"] . ')';
				$display .= '</td></tr>';
				break;
			case 'singledate':
				static $js_added;
				$display = '';
				if (!$js_added) {
					// add date
					$display .= '<script type="text/javascript" src="' . $config['baseurl'] . '/dateformat.js"></script>' . "\r\n";
					$js_added = true;
				}
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td><td align="left">';
				$setvalue ='';
				if(isset($_GET[$browse_field_name.'-date']) && $_GET[$browse_field_name.'-date'] != ''){
					$f = htmlspecialchars($_GET[$browse_field_name.'-date'], ENT_COMPAT, $config['charset']);
					$setvalue ='value="'.$f.'"';
				}
				$display .= ' <input type="text" name="' . $browse_field_name . '-date" '.$setvalue.' onFocus="javascript:vDateType=\'' . $config['date_format'] . '\'" onKeyUp="DateFormat(this,this.value,event,false,\'' . $config['date_format'] . '\')" onBlur="DateFormat(this,this.value,event,true,\'' . $config['date_format'] . '\')" /> (' . $config["date_format_long"] . ')';
				$display .= '</td></tr>';
				break;
			case 'minmax':
				$display = '';
				$display .= '<tr><td class="searchpage_field_caption">'.$browse_caption.'</td><td align="left">';
				$sql = "SELECT listingsformelements_field_type, listingsformelements_search_step FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_field_name = '$browse_field_name'";
				$rsStepLookup = $conn->Execute($sql);
				if (!$rsStepLookup) {
					$misc->log_error($sql);
				}
				// Get max, min and step
				$step = $rsStepLookup->fields['listingsformelements_search_step'];
				$field_type = $rsStepLookup->fields['listingsformelements_field_type'];
				unset($rsStepLookup);
				//Manual Step Values
				if(strpos($step,'|') !==FALSE){
					$step_array=explode('|',$step);
					if(!isset($step_array[0]) || !isset($step_array[1])){
						//Bad Step Array Fail
						exit;
					}
					$min=intval($step_array[0]);
					$max=intval($step_array[1]);
					if(isset($step_array[2])){
						$step=intval($step_array[2]);
					}else{
						$step=0;
					}
				}else{
					if (empty($class_sql)) {
						$field_list = $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsdb WHERE
							" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = " . $config['table_prefix'] . "listingsdb.listingsdb_id";
					}else {
						$field_list = $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsdb, " . $config['table_prefix_no_lang'] . "classlistingsdb
							 WHERE " . $config['table_prefix'] . "listingsdbelements.listingsdb_id = " . $config['table_prefix'] . "listingsdb.listingsdb_id AND
							 " . $config['table_prefix_no_lang'] . "classlistingsdb.listingsdb_id = " . $config['table_prefix'] . "listingsdb.listingsdb_id";
					}
					global $db_type;
					if ($db_type == 'mysql') {
						if ($field_type == 'decimal') {
							$max = $conn->Execute("select max(listingsdbelements_field_value+0) as max  FROM $field_list AND listingsdbelements_field_name = '$browse_field_name'" . $class_sql);
							$max = $max->fields['max'];
							$min = $conn->Execute("select min(listingsdbelements_field_value+0) as min  FROM $field_list AND listingsdbelements_field_name = '$browse_field_name'" . $class_sql);
							$min = $min->fields['min'];
						}else {
							$max = $conn->Execute("select max(CAST(listingsdbelements_field_value as signed)) as max  FROM $field_list AND listingsdbelements_field_name = '$browse_field_name'" . $class_sql);
							$max = $max->fields['max'];
							$min = $conn->Execute("select min(CAST(listingsdbelements_field_value as signed)) as min  FROM $field_list AND listingsdbelements_field_name = '$browse_field_name'" . $class_sql);
							$min = $min->fields['min'];
							if ($field_type == 'price') {
								$min = substr_replace($min, '000', -3);
							}
						}
					}else {
						if ($field_type == 'decimal') {
							$max = $conn->Execute("select max(listingsdbelements_field_value+0) as max  FROM $field_list AND listingsdbelements_field_name = '$browse_field_name'" . $class_sql);
							$max = $max->fields['max'];
							$min = $conn->Execute("select min(listingsdbelements_field_value+0) as min  FROM $field_list AND listingsdbelements_field_name = '$browse_field_name'" . $class_sql);
							$min = $min->fields['min'];
						}else {
							$max = $conn->Execute("select max(CAST(listingsdbelements_field_value as int4)) as max  FROM $field_list AND listingsdbelements_field_name = '$browse_field_name'" . $class_sql);
							$max = $max->fields['max'];
							$min = $conn->Execute("select min(CAST(listingsdbelements_field_value as int4)) as min  FROM $field_list AND listingsdbelements_field_name = '$browse_field_name'" . $class_sql);
							$min = $min->fields['min'];
							if ($field_type == 'price') {
								$min = substr_replace($min, '000', -3);
							}
						}
					}
				}


				if ($step == 0) {
					if ($max > $min) {
						$step = ceil(($max - $min) / 10);
					} else {
						$step = ceil($max/10);
					}
				}
				if ($config["search_step_max"] >= '1') {
					$step_val = (($max - $min) / $config["search_step_max"]);
					if ($step_val > $step) {
						$step = $step_val;
					}
				}




				$display .= '<select name="'.$browse_field_name.'-min">' . "\n";
				$options = '<option value="">'.$lang['all'].'</option>' . "\n";
				if ($field_type == 'price') {
					$i = $min;
					while ($i < $max) {
						$z = $misc->international_num_format($i, $config['number_decimals_price_fields']);
						$z = $misc->money_formats($z);
						$selected='';
						if(isset($_GET[$browse_field_name.'-min']) && $_GET[$browse_field_name.'-min'] == $i){
							$selected ='selected="selected"';
						}
						$options .= '<option value="' . $i . '" '.$selected.'>' . $z . '</option>';
						$i += $step;
					}
					$z = $misc->international_num_format($max, $config['number_decimals_price_fields']);
					$z = $misc->money_formats($z);
					$selected='';
					if(isset($_GET[$browse_field_name.'-min']) && $_GET[$browse_field_name.'-min'] == $i){
						$selected ='selected="selected"';
					}
					$options .= '<option value="' . $max . '" '.$selected.'>' . $z . '</option>';
				}else {
					$i = $min;
					while ($i < $max) {
						$selected='';
						if(isset($_GET[$browse_field_name.'-min']) && $_GET[$browse_field_name.'-min'] == $i){
							$selected ='selected="selected"';
						}
						$options .= '<option '.$selected.'>' . $i . '</option>';
						$i += $step;
					}
					$selected='';
					if(isset($_GET[$browse_field_name.'-min']) && $_GET[$browse_field_name.'-min'] == $max){
						$selected ='selected="selected"';
					}
					$options .= '<option '.$selected.'>' . $max . '</option>';
				}
				$options .= '</select>';
				$display .= $options . ' ' . $lang['to'] . '<br />';
				$options = '<option value="">'.$lang['all'].'</option>' . "\n";
				if ($field_type == 'price') {
					$i = $min;
					while ($i < $max) {
						$z = $misc->international_num_format($i, $config['number_decimals_price_fields']);
						$z = $misc->money_formats($z);
						$selected='';
						if(isset($_GET[$browse_field_name.'-max']) && $_GET[$browse_field_name.'-max'] == $i){
							$selected ='selected="selected"';
						}
						$options .= '<option value="' . $i . '" '.$selected.'>' . $z . '</option>';
						$i += $step;
					}
					$z = $misc->international_num_format($max, $config['number_decimals_price_fields']);
					$z = $misc->money_formats($z);
					$selected='';
					if(isset($_GET[$browse_field_name.'-max']) && $_GET[$browse_field_name.'-max'] == $i){
						$selected ='selected="selected"';
					}
					$options .= '<option value="' . $max . '" '.$selected.'>' . $z . '</option>';
				}else {
					$i = $min;
					while ($i < $max) {
						$selected='';
						if(isset($_GET[$browse_field_name.'-max']) && $_GET[$browse_field_name.'-max'] == $i){
							$selected ='selected="selected"';
						}
						$options .= '<option '.$selected.'>' . $i . '</option>';
						$i += $step;
					}
					$selected='';
					if(isset($_GET[$browse_field_name.'-max']) && $_GET[$browse_field_name.'-max'] == $max){
						$selected ='selected="selected"';
					}
					$options .= '<option '.$selected.'>' . $max . '</option>';
				}
				$options .= '</select>';
				$display .= '<select name="' . $browse_field_name . '-max">' . $options . '</td></tr>';
				break;
		} // End switch ($searchbox_type)
		$time2=$misc->getmicrotime();
		$render_time = sprintf('%.3f',$time2-$time);
		$display .= "\r\n".'<!--Search Box '.$browse_field_name.' Render Time '.$render_time.' -->'."\r\n";
		return $display;
	} // end function searchbox_render
	function calculate_mileage($lat1, $lat2, $lon1, $lon2) {

		// used internally, this function actually performs that calculation to
		// determine the mileage between 2 points defined by lattitude and
		// longitude coordinates.  This calculation is based on the code found
		// at http://www.cryptnet.net/fsp/zipdy/

		// Convert lattitude/longitude (degrees) to radians for calculations
		$lat1 = deg2rad($lat1);
		$lon1 = deg2rad($lon1);
		$lat2 = deg2rad($lat2);
		$lon2 = deg2rad($lon2);

		// Find the deltas
		$delta_lat = $lat2 - $lat1;
		$delta_lon = $lon2 - $lon1;

		// Find the Great Circle distance
		$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
		$distance = 3956 * 2 * atan2(sqrt($temp),sqrt(1-$temp));

		return $distance;
	}	//end function calculate_mileage
} // End Class
?>