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
class addon_manager {
	/**
	 * **********************************************************************\
	 * function blog_list() - This Creates a list of blogs that you can edit *
	 * \**********************************************************************
	 */
	function rmdir_recurse($path)
	{
		$path = rtrim($path, '/').'/';
		$handle = opendir($path);
		while(false !== ($file = readdir($handle)))
		{
			if($file != '.' and $file != '..' )
			{
				$fullpath = $path.$file;
				if(is_dir($fullpath))
				$this->rmdir_recurse($fullpath);
				else
				unlink($fullpath);
			}
		}
		closedir($handle);
		rmdir($path);
		return TRUE;
	}

	function get_url($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		$data = curl_exec($ch);
		$header  = curl_getinfo( $ch );
		curl_close($ch);
		if($header['http_code']!='200'){
			return FALSE;
		}
		return $data;
	}

	function get_tmp()
	{
		$tmpfile = tempnam("dummy","");
		$path = dirname($tmpfile);
		unlink($tmpfile);
		return $path;
	}

	function extract($file,$addon_name)
	{
		global $config;
		$unzipped=FALSE;
		$zip = zip_open($file);
		if(is_resource($zip)) {
			while ($zip_entry = zip_read($zip)) {
				$path_parts=pathinfo($config['basepath']."/addons/".$addon_name.'/'.zip_entry_name($zip_entry));
				//echo'<pre>'.print_r($path_parts,true).'</pre>';
				if(!isset($path_parts['extension']) && !file_exists($config['basepath']."/addons/".$addon_name.'/'.zip_entry_name($zip_entry))){
					mkdir($config['basepath']."/addons/".$addon_name.'/'.zip_entry_name($zip_entry));
				}
				if(!is_dir($config['basepath']."/addons/".$addon_name.'/'.zip_entry_name($zip_entry))){
					$fp = fopen($config['basepath']."/addons/".$addon_name.'/'.zip_entry_name($zip_entry), "w");
					$unzipped=TRUE;
					if (zip_entry_open($zip, $zip_entry, "r")) {
						$buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
						fwrite($fp,"$buf");
						zip_entry_close($zip_entry);
						fclose($fp);
					}
				}
			}
			zip_close($zip);
			return TRUE;
		}
		return FALSE;
	}

	function write_tmp_zip($data)
	{
		$tmp_path=$this->get_tmp();
		$file_name=time().'.zip';
		$fp = fopen($tmp_path.'/'.$file_name, "w");
		fwrite($fp,$data);
		fclose($fp);
		return $tmp_path.'/'.$file_name;
	}

	function display_addon_manager()
	{
		global $config,$conn,$lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		$display='';
		//Check addon folder is writeable
		$addon_permission = is_writeable($config['basepath'].'/addons');
		if($addon_permission==FALSE){
			$display .= '<div class="redtext">'.$lang['warning_addon_folder_not_writeable'].'</div>';
			return $display;
		}
		//Are we deleting?
		if(isset($_GET['uninstall'])){
			$uninstall_name=$_GET['uninstall'];
			$bad_char=preg_match('/[^A-Za-z0-9_-]/',$uninstall_name);
			if($bad_char==1){
				die($lang['addon_name_invalid']);
			}
			$has_uninstall==FALSE;
			if(file_exists($config['basepath'].'/addons/'.$uninstall_name.'/addon.inc.php')){
				include_once($config['basepath'].'/addons/'.$uninstall_name.'/addon.inc.php');
				if(function_exists($uninstall_name.'_uninstall_tables')){
					$has_uninstall=TRUE;
				}
			}
			$folder_removed=FALSE;
			$db_uninstalled=FALSE;
			if($has_uninstall==TRUE){
				$uninstall_function=$uninstall_name.'_uninstall_tables';
				$db_uninstalled=$uninstall_function();
			}
			if($db_uninstalled){
				$folder_removed =$this->rmdir_recurse($config['basepath'].'/addons/'.$uninstall_name);
			}
			if($folder_removed){
				//Ok Addon is now removed, lets remove it from the addon table.
				$sql = 'DELETE FROM '.$config['table_prefix_no_lang'].'addons WHERE addons_name = '.$misc->make_db_safe($uninstall_name);
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$display.='<div class="addon_manager_good_info">'.$lang['removed_addon'].' '.htmlentities($uninstall_name).'</div>';
			}
		}
		//Are we Updating an addon?
		if(isset($_GET['check_update'])){
			$update_name=$_GET['check_update'];
			$bad_char=preg_match('/[^A-Za-z0-9_-]/',$update_name);
			if($bad_char==1){
				die($lang['addon_name_invalid']);
			}
			$update_url='';
			if(file_exists($config['basepath'].'/addons/'.$update_name.'/addon.inc.php')){
				include_once($config['basepath'].'/addons/'.$update_name.'/addon.inc.php');
				if(function_exists($update_name.'_checkupdate_url')){
					$update_function=$update_name.'_checkupdate_url';
					$update_url = $update_function();
					if(!function_exists($update_name.'_update_url')){
						$update_url='';
					}
				}
			}
			$sql_update_name=$misc->make_db_safe($update_name);
			if($update_url!=''){
				$sql = 'SELECT addons_version FROM '.$config['table_prefix_no_lang'].'addons WHERE addons_name ='.$sql_update_name;
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$current_version = trim($misc->make_db_unsafe($recordSet->fields['addons_version']));
				//Get Latest Version
				$latest_version = $this->get_url($update_url);
				//print_r($latest_version);
				if($latest_version===false){
					$display.='<div class="addon_manager_bad_info">'.htmlentities($update_name).' - '.$lang['addon_update_server_not_avaliable'].'</div>';
				}else{
					$latest_version=trim($latest_version);
					if($current_version==$latest_version){
						$display.='<div class="addon_manager_good_info">'.$lang['addon_already_latest_version'].' '.htmlentities($update_name).'</div>';
					}else{
						//Need to update
						$retrieve_function=$update_name.'_update_url';
						$retrieve_url=$retrieve_function($latest_version);
						$file = $this->get_url($retrieve_url);
						if($file===false){
							$display.='<div class="addon_manager_bad_info">'.htmlentities($update_name).' - '.$lang['addon_update_file_not_avaliable'].'</div>';
						}else{
							//we have the file unzip it and then install it
							$update_file = $this->write_tmp_zip($file);
							$update_status = $this->extract($update_file,$update_name);
							//print_r($update_status);
							if($update_status===FALSE){
								$display.='<div class="addon_manager_bad_info">'.htmlentities($update_name).' - '.$lang['addon_update_failed'].'</div>';
							}else{
								$display.='<div class="addon_manager_good_info">'.htmlentities($update_name).' - '.$lang['addon_update_successful'].'</div>';
							}
						}
					}
				}
			}else{
				$display.='<div class="addon_manager_bad_info">'.htmlentities($update_name).' - '.$lang['addon_does_not_support_updates'].'</div>';
			}
		}
		if(isset($_GET['view_help'])){
			$help_name=$_GET['view_help'];
			$bad_char=preg_match('/[^A-Za-z0-9_-]/',$help_name);
			if($bad_char==1){
				die($lang['addon_name_invalid']);
			}
			$help_array=array();
			if(file_exists($config['basepath'].'/addons/'.$help_name.'/addon.inc.php')){
				include_once($config['basepath'].'/addons/'.$help_name.'/addon.inc.php');
				if(function_exists($help_name.'_addonmanager_help')){
					$help_function=$help_name.'_addonmanager_help';
					$help_array = $help_function();
					//return array($template_tags,$action_urls,$doc_url);
					$help_template_tags = $help_array[0];
					$help_action_urls = $help_array[1];
					$help_doc_url = $help_array[2];
					if($help_doc_url!=''){
						$display.= '<div class="addon_manager_ext_help_link"><a href="'.$help_doc_url.'" title="'.$lang['addon_manager_ext_help_link'].'">'.$lang['addon_manager_ext_help_link'].'</a></div>';
					}
					if(!empty($help_template_tags)){
						$display.= '<div class="addon_manager_template_tag_header">'.$lang['addon_manager_template_tags'].'</div>';
						foreach($help_template_tags as $tagname => $tagdesc){
							$display.= '<div class="addon_manager_template_tag_data">
								<span class="addon_manager_template_tag_name">'.$tagname.'</span>
								<span class="addon_manager_template_tag_desc">'.$tagdesc.'</span>
								</div>';
						}
					}
					if(!empty($help_action_urls)){
						$display.= '<div class="addon_manager_action_url_header">'.$lang['addon_manager_action_urls'].'</div>';
						foreach($help_action_urls as $tagname => $tagdesc){
							$display.= '<div class="addon_manager_action_url_data">
								<span class="addon_manager_action_url_name">'.$tagname.'</span>
								<span class="addon_manager_action_url_desc">'.$tagdesc.'</span>
								</div>';
						}
					}
					return $display;
				}
			}
		}
		//Get List of addons
		$sql = 'SELECT * FROM '.$config['table_prefix_no_lang'].'addons ORDER BY addons_name;';
		$recordSet = $conn->Execute($sql);
		if (!$recordSet) {
			$misc->log_error($sql);
		}
		$display .='<table class="addon_manager_list">';
		$display .='<tr class="addon_manager_list_headers">';
		$display .='<th class="addon_manager_list_header">'.$lang['addon_name'].'</th>';
		$display .='<th class="addon_manager_list_header">'.$lang['addon_version'].'</th>';
		$display .='<th class="addon_manager_list_header">'.$lang['addon_status'].'</th>';
		$display .='<th class="addon_manager_list_header">'.$lang['addon_actions'].'</th>';
		$display .='</tr>';
		while(!$recordSet->EOF){
			$name = $misc->make_db_unsafe($recordSet->fields['addons_name']);
			$version = $misc->make_db_unsafe($recordSet->fields['addons_version']);
			$display .='<tr class="addon_manager_list_datarow">';
			$display .='<td class="addon_manager_list_data">'.$name.'</td>';
			$display .='<td class="addon_manager_list_data">'.$version.'</td>';
			//Check Addon Status
			$status_msg=$lang['addon_ok'];
			//Status Code 0=ok 1=FatalError 2=Warngin
			$status_code=0;
			//Define action variables
			$has_update =FALSE;
			$template_tags=array();
			$action_urls=array();
			$doc_url='';
			$has_help=FALSE;
			$has_uninstall=FALSE;
			$actions=array();
			//See if addon was removed.
			$still_here=file_exists($config['basepath'].'/addons/'.$name);
			if($still_here){
				$still_here=file_exists($config['basepath'].'/addons/'.$name.'/addon.inc.php');
				if(!$still_here){
					$status_msg = $lang['addon_files_removed'];
					$status_code=1;
				}else{
					//Ok Adon is here lets get a list of actions.
					include_once($config['basepath'].'/addons/'.$name.'/addon.inc.php');
					if(function_exists($name.'_checkupdate_url')){
						if(function_exists($name.'_update_url')){
							$has_update=TRUE;
						}
					}
					if(function_exists($name.'_addonmanager_help')){
						$help_funtion=$name.'_addonmanager_help';
						$help_array=$help_funtion();
						//return array($template_tags,$action_urls,$doc_url);
						$template_tags=$help_array[0];
						$action_urls=$help_array[1];
						$doc_url=$help_array[2];
						if(!empty($template_tags)){
							$has_help=TRUE;
						}
						if(!empty($action_urls)){
							$has_help=TRUE;
						}
						if(!empty($doc_url)){
							$has_help=TRUE;
						}
					}
					if(function_exists($name.'_uninstall_tables')){
						$has_uninstall=TRUE;
					}
				}
			}else{
				$status_msg = $lang['addon_dir_removed'];
				$status_code=1;
			}
			if($has_update==TRUE){
				$actions[]='<a href="'.$config['baseurl'].'/admin/index.php?action=addon_manager&amp;check_update='.$name.'" title="'.$lang['addon_check_for_updates'].'"><img class="addon_manager_action_icon" src="images/no_lang/addon_check_update.png" alt="'.$lang['addon_check_for_updates'].'" /></a>';
			}
			if($has_help==TRUE){
				$actions[]='<a href="'.$config['baseurl'].'/admin/index.php?action=addon_manager&amp;view_help='.$name.'&amp;popup=yes" onclick="window.open(this.href,\'_addonhelp\',\'location=0,status=0,scrollbars=1,toolbar=0,menubar=0,resizable=1,width=500\');return false" title="'.$lang['addon_view_docs'].'"><img class="addon_manager_action_icon" src="images/no_lang/addon_templatetags.png" alt="'.$lang['addon_view_docs'].'" /></a>';
			}
			if($has_uninstall==TRUE){
				$actions[]='<a href="'.$config['baseurl'].'/admin/index.php?action=addon_manager&amp;uninstall='.$name.'" onclick="return confirmDelete(\'' . $lang['delete_addon'] . '\')" title="'.$lang['addon_uninstall'].'"><img class="addon_manager_action_icon" src="images/no_lang/addon_uninstall.png" alt="'.$lang['addon_uninstall'].'" /></a>';
			}
			$display .='<td class="addon_manager_list_data"><span class="addon_status_'.intval($status_code).'">'.$status_msg.'</span></td>';
			$display .='<td class="addon_manager_list_data">'.implode(' ',$actions).'</td>';
			$display .='</tr>';
			$recordSet->MoveNext();
		}
		$display .='</table>';
		return $display;
	}
}

?>