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
class rss {
	function rss_view($option)
	{
		global $conn, $lang, $config;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display = '';
		$sql='SELECT listingsdb_id,listingsdb_last_modified FROM ' . $config['table_prefix'].'listingsdb WHERE ';
		//Allow Filtering by agent ID
		if(isset($_GET['agent_id'])){
			if(!is_array($_GET['agent_id'])){
				$id = $_GET['agent_id'];
				unset($_GET['agent_id']);
				$_GET['agent_id'][]=$id;
			}
			$aidset =FALSE;
			foreach ($_GET['agent_id'] as $aid){
				if(is_numeric($aid)){
					if($aidset){
						$sql .= ' AND userdb_id = '.$aid;
					}else {
						$sql .= ' userdb_id = '.$aid;
					}
					$aidset=TRUE;
				}
			}
			if($aidset){
				$sql .= ' AND ';
			}
		}
		//Decide with RSS feed to show
		switch($option){
			case 'featured':
				if(intval($config['rss_limit_featured']) > 0){
				$sql.=' listingsdb_featured = \'yes\' AND listingsdb_active = \'yes\' LIMIT 0, '.intval($config['rss_limit_featured']);	
				}else{
				$sql.=' listingsdb_featured = \'yes\' AND listingsdb_active = \'yes\' ';
				}
				$rsslink = $config['baseurl'].'/index.php?action=rss_featured_listings';
				$rsstitle = $config['rss_title_featured'];
				$rssdesc = $config['rss_desc_featured'];
				$rsslistingdesc = $config['rss_listingdesc_featured'];
				break;
			case 'lastmodified':
				if(intval($config['rss_limit_lastmodified']) > 0){
					$sql.=' listingsdb_active = \'yes\' ORDER BY listingsdb_last_modified DESC LIMIT 0, '.intval($config['rss_limit_lastmodified']);
				}else{
					$sql.=' listingsdb_active = \'yes\' ORDER BY listingsdb_last_modified DESC';
				}
				$rsslink = $config['baseurl'].'/index.php?action=rss_lastmodified_listings';
				$rsstitle = $config['rss_title_lastmodified'];
				$rssdesc = $config['rss_desc_lastmodified'];
				$rsslistingdesc = $config['rss_listingdesc_lastmodified'];
				break;
		}
		
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}
		//Get RSS Template
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$page = new page_user();
		$page->load_page($config['template_path'] . '/rss.html',FALSE);
		$page->replace_tag('rss_webroot',$rsslink);
		$page->replace_tag('rss_description',$rssdesc);
		$page->replace_tag('listing_modified_date',date("D, d M Y H:i:s O", strtotime($recordSet->fields['listingsdb_last_modified'])));
		$page->replace_tag('rss_title',$rsstitle);
		$page->replace_tag('rss_listing_description',$rsslistingdesc);
		$listing_template=$page->get_template_section('rss_listing_block');
		$completed_listing_template='';
		while(!$recordSet->EOF) {
			// first, check to see whether the listing is currently active
			
			//Lookup Class
			$sql2 = "SELECT class_id FROM " . $config['table_prefix_no_lang'] . "classlistingsdb WHERE listingsdb_id = ".$recordSet->fields['listingsdb_id'];
			$recordSet2 = $conn->SelectLimit($sql2, 1, 0);
			$num = $recordSet2->RecordCount();
			if ($recordSet2 === false) {
				$misc->log_error($sql);
			}
			$class = $recordSet2->fields['class_id'];
			$completed_listing_template .= $page->replace_listing_field_tags($recordSet->fields['listingsdb_id'],$listing_template,TRUE);
			$completed_listing_template = str_replace('{rss_listing_guid}',base64_encode($recordSet->fields['listingsdb_id'].'-'.$recordSet->fields['listingsdb_last_modified']),$completed_listing_template);
			$recordSet->MoveNext();
		}
		$page->replace_template_section('rss_listing_block',$completed_listing_template); 
		$display=$page->return_page();
		return $display;
	}
}
?>