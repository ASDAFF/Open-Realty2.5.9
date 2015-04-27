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
/**
 * maps This Class contains all the functions related to creating links to the different mapping sites.
 *
 * @author Ryan Bonham
 * @copyright Copyright (c) 2005
 */
class maps {
	/**
	 * maps::create_map_link()
	 * This is the function to call to show a map link. It should be called from the listing detail page, or any page where $_GET['listingID'] is set.
	 * This function then calls the appropriate make_mapname function as specified in the configuration.
	 *
	 * @see maps::make_mapquest()
	 * @see maps::make_yahoo_us()
	 * @return string Return the URL for the map as long as the required fields are filled out, if not it returns a empty string.
	 */
	function create_map_link($url_only = 'no')
	{
		global $conn, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		// Map Type
		// Get Address, City, State, Zip
		// Create Blank Variables
		$display = '';
		$address = '';
		$city = '';
		$state = '';
		$zip = '';
		// Get Listing ID
		$sql_listingID = $misc->make_db_safe($_GET['listingID']);
		$listing_title = urlencode(listing_pages::get_title($_GET['listingID']));
		// get address
		$sql_address_field = $misc->make_db_safe($config['map_address']);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = $sql_address_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$address = urlencode($misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']));
			$recordSet->MoveNext();
		} // end while
		// Add address fields 2 & 3
		$sql_address_field = $misc->make_db_safe($config['map_address2']);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = $sql_address_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$address .= ' ' . urlencode($misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']));
			$recordSet->MoveNext();
		} // end while
		$sql_address_field = $misc->make_db_safe($config['map_address3']);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = $sql_address_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$address .= ' ' . urlencode($misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']));
			$recordSet->MoveNext();
		} // end while
		$sql_address_field = $misc->make_db_safe($config['map_address4']);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = $sql_address_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$address .= ' ' . urlencode($misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']));
			$recordSet->MoveNext();
		} // end while
		// get city
		$sql_city_field = $misc->make_db_safe($config['map_city']);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = $sql_city_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$city = urlencode($misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']));
			$recordSet->MoveNext();
		} // end while
		// get state
		$sql_state_field = $misc->make_db_safe($config['map_state']);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = $sql_state_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$state = urlencode($misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']));
			$recordSet->MoveNext();
		} // end while
		// get zip
		$sql_zip_field = $misc->make_db_safe($config['map_zip']);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = $sql_zip_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$zip = urlencode($misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']));
			$recordSet->MoveNext();
		} // end while
		// get zip
		$sql_country_field = $misc->make_db_safe($config['map_country']);
		$sql = "SELECT listingsdbelements_field_value, listingsformelements_field_type, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsdbelements, " . $config['table_prefix'] . "listingsformelements WHERE ((" . $config['table_prefix'] . "listingsdbelements.listingsdb_id = $sql_listingID) AND (listingsformelements_field_name = listingsdbelements_field_name) AND (listingsdbelements_field_name = $sql_country_field))";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		} while (!$recordSet->EOF) {
			$country = urlencode($misc->make_db_unsafe ($recordSet->fields['listingsdbelements_field_value']));
			$recordSet->MoveNext();
		} // end while
		if ($address != '' || $city != '' || $state != '' || $zip != '') {
			$map_type = 'make_' . $config['map_type'];

			$pos = strpos($map_type, 'mapquest');

			$pos2 = strpos($map_type, 'multimap');
			$pos3 = strpos($map_type, 'global_');
			if ($pos3 !== false) {
				if ($pos !== false) {
					$display = maps::make_mapquest($country, $address, $city, $state, $zip, $listing_title, $url_only);
				}elseif ($pos2 !== false) {
					$display = maps::make_multimap($country, $address, $city, $state, $zip, $listing_title, $url_only);
				}
			}elseif ($pos !== false) {
				$country = substr($map_type, -2);
				$display = maps::make_mapquest($country, $address, $city, $state, $zip, $listing_title, $url_only);
			}elseif ($pos2 !== false) {
				$country = substr($map_type, -2);
				$display = maps::make_multimap($country, $address, $city, $state, $zip, $listing_title, $url_only);
			}else {
				$display = maps::$map_type($address, $city, $state, $zip, $listing_title, $url_only);
			}
		}
		return $display;
	}
	function make_mapquest($country, $address, $city, $state, $zip, $listing_title, $url_only)
	{
		// renders a link to yahoo maps on the page
		global $lang;
		$mapquest_string = "country=$country&amp;addtohistory=&amp;address=$address&amp;city=$city&amp;zipcode=$zip";
		if ($url_only == 'no') {
			$display = "<a href=\"http://www.mapquest.com/maps/map.adp?$mapquest_string\" onclick=\"window.open(this.href,'_map','location=0,status=0,scrollbars=1,toolbar=0,menubar=0,width=800,height=600');return false\">$lang[map_link]</a>";
			} else {
				$display = "http://www.mapquest.com/maps/map.adp?$mapquest_string";
			}
		return $display;
	} // end makeMapQuestMap
	function make_yahoo_us($address, $city, $state, $zip, $listing_title, $url_only)
	{
		global $lang;
		$yahoo_string = "Pyt=Tmap&amp;addr=$address&amp;csz=$city,$state,$zip&amp;Get+Map=Get+Map";		
		if ($url_only == 'no') {
			$display = "<a href=\"http://maps.yahoo.com/py/maps.py?$yahoo_string\" onclick=\"window.open(this.href,'_map','location=0,status=0,scrollbars=1,toolbar=0,menubar=0,width=800,height=600');return false\">$lang[map_link]</a>";
			} else {
				$display = "http://maps.yahoo.com/py/maps.py?$yahoo_string";
			}
		return $display;
	}
	function make_google_us($address, $city, $state, $zip, $listing_title, $url_only)
	{
		global $lang;
		$google_string = "maps?q=loc:$address%20$city%20$state%20$zip%20($listing_title)";
		if ($url_only == 'no') {
			$display = "<a href=\"http://maps.google.com/$google_string\" onclick=\"window.open(this.href,'_map','location=0,status=0,scrollbars=1,toolbar=0,menubar=0,width=800,height=600');return false\">$lang[map_link]</a>";
			} else {
				$display = "http://maps.google.com/$google_string";
			}
		return $display;
	}
	function make_multimap($country, $address, $city, $state, $zip, $listing_title, $url_only)
	{
		// renders a link to multi map on the page
		global $lang;
		$multimap_string = "&amp;db=$country&amp;addr2=$address&amp;addr3=$city&amp;pc=$zip";
		if ($url_only == 'no') {
			$display = '<a href="http://www.multimap.com/map/places.cgi?client=public'.$multimap_string.'" target="_map">'.$lang['map_link'].'</a>';
			} else {
				$display = 'http://www.multimap.com/map/places.cgi?client=public'.$multimap_string;
			}
		return $display;
	} // end makeMultiMapFRMap
}

?>