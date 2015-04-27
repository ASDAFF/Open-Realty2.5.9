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

/**
 * configurator
 * This class contains all functions related the site configurator.
 *
 * @author Ryan Bonham
 * @copyright Copyright (c) 2005
 */
class configurator {
	/**
	 * configurator::show_configurator()
	 * This function handles the display and updates for the site configurator.
	 *
	 * @param string $guidestring
	 * @return
	 */
	function show_configurator($guidestring = '')
	{
		global $conn, $lang, $config;
		$security = login::loginCheck('edit_site_config', true);
		$display = '';
		if ($security === true) {
			// Open Connection to the Control Panel Table
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			// DISABLE MULTILINGUAL SUPPORT AS IT IS NOT READY FOR THIS RELEASE
			$ml_support = false;
			// Default Options
			$yes_no[0] = 'No';
			$yes_no[1] = 'Yes';

			$asc_desc['ASC'] = 'ASC';
			$asc_desc['DESC'] = 'DESC';
			// New Charset Settings - Current charsets supported by PHP 4.3.0 and up
			$charset['ISO-8859-1'] = 'ISO-8859-1';
			$charset['ISO-8859-15'] = 'ISO-8859-15';
			$charset['UTF-8'] = 'UTF-8';
			$charset['cp866'] = 'cp866';
			$charset['cp1251'] = 'cp1251';
			$charset['cp1252'] = 'cp1252';
			$charset['KOI8-R'] = 'KOI8-R';
			$charset['BIG5'] = 'BIG5';
			$charset['GB2312'] = 'GB2312';
			$charset['BIG5-HKSCS'] = 'BIG5-HKSCS';
			$charset['Shift_JIS'] = 'Shift_JIS';
			$charset['EUC-JP'] = 'EUC-JP';
			// New Global Maps
			$map_types['global_mapquest'] = $lang['global_mapquest'];
			$map_types['global_multimap'] = $lang['global_multimap'];
			// Map Options
			$map_types['mapquest_AD'] = $lang['mapquest_AD'];
			$map_types['mapquest_AE'] = $lang['mapquest_AE'];
			$map_types['mapquest_AF'] = $lang['mapquest_AF'];
			$map_types['mapquest_AG'] = $lang['mapquest_AG'];
			$map_types['mapquest_AI'] = $lang['mapquest_AI'];
			$map_types['mapquest_AL'] = $lang['mapquest_AL'];
			$map_types['mapquest_AM'] = $lang['mapquest_AM'];
			$map_types['mapquest_AN'] = $lang['mapquest_AN'];
			$map_types['mapquest_AO'] = $lang['mapquest_AO'];
			$map_types['mapquest_AR'] = $lang['mapquest_AR'];
			$map_types['mapquest_AS'] = $lang['mapquest_AS'];
			$map_types['mapquest_AT'] = $lang['mapquest_AT'];
			$map_types['mapquest_AU'] = $lang['mapquest_AU'];
			$map_types['mapquest_AW'] = $lang['mapquest_AW'];
			$map_types['mapquest_AZ'] = $lang['mapquest_AZ'];
			$map_types['mapquest_BA'] = $lang['mapquest_BA'];
			$map_types['mapquest_BB'] = $lang['mapquest_BB'];
			$map_types['mapquest_BD'] = $lang['mapquest_BD'];
			$map_types['mapquest_BE'] = $lang['mapquest_BE'];
			$map_types['mapquest_BF'] = $lang['mapquest_BF'];
			$map_types['mapquest_BG'] = $lang['mapquest_BG'];
			$map_types['mapquest_BH'] = $lang['mapquest_BH'];
			$map_types['mapquest_BI'] = $lang['mapquest_BI'];
			$map_types['mapquest_BJ'] = $lang['mapquest_BJ'];
			$map_types['mapquest_BM'] = $lang['mapquest_BM'];
			$map_types['mapquest_BN'] = $lang['mapquest_BN'];
			$map_types['mapquest_BO'] = $lang['mapquest_BO'];
			$map_types['mapquest_BR'] = $lang['mapquest_BR'];
			$map_types['mapquest_BS'] = $lang['mapquest_BS'];
			$map_types['mapquest_BT'] = $lang['mapquest_BT'];
			$map_types['mapquest_BV'] = $lang['mapquest_BV'];
			$map_types['mapquest_BW'] = $lang['mapquest_BW'];
			$map_types['mapquest_BY'] = $lang['mapquest_BY'];
			$map_types['mapquest_BZ'] = $lang['mapquest_BZ'];
			$map_types['mapquest_CA'] = $lang['mapquest_CA'];
			$map_types['mapquest_CC'] = $lang['mapquest_CC'];
			$map_types['mapquest_CD'] = $lang['mapquest_CD'];
			$map_types['mapquest_CF'] = $lang['mapquest_CF'];
			$map_types['mapquest_CG'] = $lang['mapquest_CG'];
			$map_types['mapquest_CH'] = $lang['mapquest_CH'];
			$map_types['mapquest_CI'] = $lang['mapquest_CI'];
			$map_types['mapquest_CK'] = $lang['mapquest_CK'];
			$map_types['mapquest_CL'] = $lang['mapquest_CL'];
			$map_types['mapquest_CM'] = $lang['mapquest_CM'];
			$map_types['mapquest_CN'] = $lang['mapquest_CN'];
			$map_types['mapquest_CO'] = $lang['mapquest_CO'];
			$map_types['mapquest_CR'] = $lang['mapquest_CR'];
			$map_types['mapquest_CS'] = $lang['mapquest_CS'];
			$map_types['mapquest_CU'] = $lang['mapquest_CU'];
			$map_types['mapquest_CV'] = $lang['mapquest_CV'];
			$map_types['mapquest_CX'] = $lang['mapquest_CX'];
			$map_types['mapquest_CY'] = $lang['mapquest_CY'];
			$map_types['mapquest_CZ'] = $lang['mapquest_CZ'];
			$map_types['mapquest_DE'] = $lang['mapquest_DE'];
			$map_types['mapquest_DJ'] = $lang['mapquest_DJ'];
			$map_types['mapquest_DK'] = $lang['mapquest_DK'];
			$map_types['mapquest_DM'] = $lang['mapquest_DM'];
			$map_types['mapquest_DO'] = $lang['mapquest_DO'];
			$map_types['mapquest_DZ'] = $lang['mapquest_DZ'];
			$map_types['mapquest_EC'] = $lang['mapquest_EC'];
			$map_types['mapquest_EE'] = $lang['mapquest_EE'];
			$map_types['mapquest_EG'] = $lang['mapquest_EG'];
			$map_types['mapquest_EH'] = $lang['mapquest_EH'];
			$map_types['mapquest_ER'] = $lang['mapquest_ER'];
			$map_types['mapquest_ES'] = $lang['mapquest_ES'];
			$map_types['mapquest_ET'] = $lang['mapquest_ET'];
			$map_types['mapquest_FI'] = $lang['mapquest_FI'];
			$map_types['mapquest_FJ'] = $lang['mapquest_FJ'];
			$map_types['mapquest_FK'] = $lang['mapquest_FK'];
			$map_types['mapquest_FM'] = $lang['mapquest_FM'];
			$map_types['mapquest_FO'] = $lang['mapquest_FO'];
			$map_types['mapquest_FR'] = $lang['mapquest_FR'];
			$map_types['multimap_FR'] = $lang['multimap_FR'];
			$map_types['mapquest_GA'] = $lang['mapquest_GA'];
			$map_types['mapquest_GB'] = $lang['mapquest_GB'];
			$map_types['mapquest_GD'] = $lang['mapquest_GD'];
			$map_types['mapquest_GE'] = $lang['mapquest_GE'];
			$map_types['mapquest_GF'] = $lang['mapquest_GF'];
			$map_types['mapquest_GH'] = $lang['mapquest_GH'];
			$map_types['mapquest_GI'] = $lang['mapquest_GI'];
			$map_types['mapquest_GL'] = $lang['mapquest_GL'];
			$map_types['mapquest_GM'] = $lang['mapquest_GM'];
			$map_types['mapquest_GN'] = $lang['mapquest_GN'];
			$map_types['mapquest_GP'] = $lang['mapquest_GP'];
			$map_types['mapquest_GQ'] = $lang['mapquest_GQ'];
			$map_types['mapquest_GR'] = $lang['mapquest_GR'];
			$map_types['mapquest_GS'] = $lang['mapquest_GS'];
			$map_types['mapquest_GT'] = $lang['mapquest_GT'];
			$map_types['mapquest_GU'] = $lang['mapquest_GU'];
			$map_types['mapquest_GW'] = $lang['mapquest_GW'];
			$map_types['mapquest_GY'] = $lang['mapquest_GY'];
			$map_types['mapquest_GZ'] = $lang['mapquest_GZ'];
			$map_types['mapquest_HK'] = $lang['mapquest_HK'];
			$map_types['mapquest_HM'] = $lang['mapquest_HM'];
			$map_types['mapquest_HN'] = $lang['mapquest_HN'];
			$map_types['mapquest_HR'] = $lang['mapquest_HR'];
			$map_types['mapquest_HT'] = $lang['mapquest_HT'];
			$map_types['mapquest_HU'] = $lang['mapquest_HU'];
			$map_types['mapquest_ID'] = $lang['mapquest_ID'];
			$map_types['mapquest_IE'] = $lang['mapquest_IE'];
			$map_types['mapquest_IL'] = $lang['mapquest_IL'];
			$map_types['mapquest_IN'] = $lang['mapquest_IN'];
			$map_types['mapquest_IO'] = $lang['mapquest_IO'];
			$map_types['mapquest_IQ'] = $lang['mapquest_IQ'];
			$map_types['mapquest_IR'] = $lang['mapquest_IR'];
			$map_types['mapquest_IS'] = $lang['mapquest_IS'];
			$map_types['mapquest_IT'] = $lang['mapquest_IT'];
			$map_types['mapquest_JM'] = $lang['mapquest_JM'];
			$map_types['mapquest_JO'] = $lang['mapquest_JO'];
			$map_types['mapquest_JP'] = $lang['mapquest_JP'];
			$map_types['mapquest_KE'] = $lang['mapquest_KE'];
			$map_types['mapquest_KG'] = $lang['mapquest_KG'];
			$map_types['mapquest_KH'] = $lang['mapquest_KH'];
			$map_types['mapquest_KI'] = $lang['mapquest_KI'];
			$map_types['mapquest_KM'] = $lang['mapquest_KM'];
			$map_types['mapquest_KN'] = $lang['mapquest_KN'];
			$map_types['mapquest_KP'] = $lang['mapquest_KP'];
			$map_types['mapquest_KR'] = $lang['mapquest_KR'];
			$map_types['mapquest_KW'] = $lang['mapquest_KW'];
			$map_types['mapquest_KY'] = $lang['mapquest_KY'];
			$map_types['mapquest_KZ'] = $lang['mapquest_KZ'];
			$map_types['mapquest_LA'] = $lang['mapquest_LA'];
			$map_types['mapquest_LB'] = $lang['mapquest_LB'];
			$map_types['mapquest_LC'] = $lang['mapquest_LC'];
			$map_types['mapquest_LI'] = $lang['mapquest_LI'];
			$map_types['mapquest_LK'] = $lang['mapquest_LK'];
			$map_types['mapquest_LR'] = $lang['mapquest_LR'];
			$map_types['mapquest_LS'] = $lang['mapquest_LS'];
			$map_types['mapquest_LT'] = $lang['mapquest_LT'];
			$map_types['mapquest_LU'] = $lang['mapquest_LU'];
			$map_types['mapquest_LV'] = $lang['mapquest_LV'];
			$map_types['mapquest_LY'] = $lang['mapquest_LY'];
			$map_types['mapquest_MA'] = $lang['mapquest_MA'];
			$map_types['mapquest_MC'] = $lang['mapquest_MC'];
			$map_types['mapquest_MD'] = $lang['mapquest_MD'];
			$map_types['mapquest_MG'] = $lang['mapquest_MG'];
			$map_types['mapquest_MH'] = $lang['mapquest_MH'];
			$map_types['mapquest_MK'] = $lang['mapquest_MK'];
			$map_types['mapquest_ML'] = $lang['mapquest_ML'];
			$map_types['mapquest_MM'] = $lang['mapquest_MM'];
			$map_types['mapquest_MN'] = $lang['mapquest_MN'];
			$map_types['mapquest_MO'] = $lang['mapquest_MO'];
			$map_types['mapquest_MP'] = $lang['mapquest_MP'];
			$map_types['mapquest_MQ'] = $lang['mapquest_MQ'];
			$map_types['mapquest_MR'] = $lang['mapquest_MR'];
			$map_types['mapquest_MS'] = $lang['mapquest_MS'];
			$map_types['mapquest_MT'] = $lang['mapquest_MT'];
			$map_types['mapquest_MU'] = $lang['mapquest_MU'];
			$map_types['mapquest_MV'] = $lang['mapquest_MV'];
			$map_types['mapquest_MW'] = $lang['mapquest_MW'];
			$map_types['mapquest_MX'] = $lang['mapquest_MX'];
			$map_types['mapquest_MY'] = $lang['mapquest_MY'];
			$map_types['mapquest_MZ'] = $lang['mapquest_MZ'];
			$map_types['mapquest_NA'] = $lang['mapquest_NA'];
			$map_types['mapquest_NC'] = $lang['mapquest_NC'];
			$map_types['mapquest_NE'] = $lang['mapquest_NE'];
			$map_types['mapquest_NF'] = $lang['mapquest_NF'];
			$map_types['mapquest_NG'] = $lang['mapquest_NG'];
			$map_types['mapquest_NI'] = $lang['mapquest_NI'];
			$map_types['mapquest_NL'] = $lang['mapquest_NL'];
			$map_types['mapquest_NO'] = $lang['mapquest_NO'];
			$map_types['mapquest_NP'] = $lang['mapquest_NP'];
			$map_types['mapquest_NR'] = $lang['mapquest_NR'];
			$map_types['mapquest_NU'] = $lang['mapquest_NU'];
			$map_types['mapquest_NZ'] = $lang['mapquest_NZ'];
			$map_types['mapquest_OM'] = $lang['mapquest_OM'];
			$map_types['mapquest_PA'] = $lang['mapquest_PA'];
			$map_types['mapquest_PE'] = $lang['mapquest_PE'];
			$map_types['mapquest_PF'] = $lang['mapquest_PF'];
			$map_types['mapquest_PG'] = $lang['mapquest_PG'];
			$map_types['mapquest_PH'] = $lang['mapquest_PH'];
			$map_types['mapquest_PK'] = $lang['mapquest_PK'];
			$map_types['mapquest_PL'] = $lang['mapquest_PL'];
			$map_types['mapquest_PM'] = $lang['mapquest_PM'];
			$map_types['mapquest_PN'] = $lang['mapquest_PN'];
			$map_types['mapquest_PR'] = $lang['mapquest_PR'];
			$map_types['mapquest_PS'] = $lang['mapquest_PS'];
			$map_types['mapquest_PT'] = $lang['mapquest_PT'];
			$map_types['mapquest_PW'] = $lang['mapquest_PW'];
			$map_types['mapquest_PY'] = $lang['mapquest_PY'];
			$map_types['mapquest_QA'] = $lang['mapquest_QA'];
			$map_types['mapquest_RE'] = $lang['mapquest_RE'];
			$map_types['mapquest_RO'] = $lang['mapquest_RO'];
			$map_types['mapquest_RU'] = $lang['mapquest_RU'];
			$map_types['mapquest_RW'] = $lang['mapquest_RW'];
			$map_types['mapquest_SA'] = $lang['mapquest_SA'];
			$map_types['mapquest_SB'] = $lang['mapquest_SB'];
			$map_types['mapquest_SC'] = $lang['mapquest_SC'];
			$map_types['mapquest_SD'] = $lang['mapquest_SD'];
			$map_types['mapquest_SE'] = $lang['mapquest_SE'];
			$map_types['mapquest_SG'] = $lang['mapquest_SG'];
			$map_types['mapquest_SH'] = $lang['mapquest_SH'];
			$map_types['mapquest_SI'] = $lang['mapquest_SI'];
			$map_types['mapquest_SJ'] = $lang['mapquest_SJ'];
			$map_types['mapquest_SK'] = $lang['mapquest_SK'];
			$map_types['mapquest_SL'] = $lang['mapquest_SL'];
			$map_types['mapquest_SM'] = $lang['mapquest_SM'];
			$map_types['mapquest_SN'] = $lang['mapquest_SN'];
			$map_types['mapquest_SO'] = $lang['mapquest_SO'];
			$map_types['mapquest_SR'] = $lang['mapquest_SR'];
			$map_types['mapquest_ST'] = $lang['mapquest_ST'];
			$map_types['mapquest_SV'] = $lang['mapquest_SV'];
			$map_types['mapquest_SY'] = $lang['mapquest_SY'];
			$map_types['mapquest_SZ'] = $lang['mapquest_SZ'];
			$map_types['mapquest_TC'] = $lang['mapquest_TC'];
			$map_types['mapquest_TD'] = $lang['mapquest_TD'];
			$map_types['mapquest_TF'] = $lang['mapquest_TF'];
			$map_types['mapquest_TG'] = $lang['mapquest_TG'];
			$map_types['mapquest_TH'] = $lang['mapquest_TH'];
			$map_types['mapquest_TJ'] = $lang['mapquest_TJ'];
			$map_types['mapquest_TK'] = $lang['mapquest_TK'];
			$map_types['mapquest_TM'] = $lang['mapquest_TM'];
			$map_types['mapquest_TN'] = $lang['mapquest_TN'];
			$map_types['mapquest_TO'] = $lang['mapquest_TO'];
			$map_types['mapquest_TP'] = $lang['mapquest_TP'];
			$map_types['mapquest_TR'] = $lang['mapquest_TR'];
			$map_types['mapquest_TT'] = $lang['mapquest_TT'];
			$map_types['mapquest_TV'] = $lang['mapquest_TV'];
			$map_types['mapquest_TW'] = $lang['mapquest_TW'];
			$map_types['mapquest_TZ'] = $lang['mapquest_TZ'];
			$map_types['mapquest_UA'] = $lang['mapquest_UA'];
			$map_types['mapquest_UG'] = $lang['mapquest_UG'];
			$map_types['multimap_GB'] = $lang['multimap_uk'];
			$map_types['google_us'] = $lang['google_us'];
			$map_types['mapquest_US'] = $lang['mapquest_US'];
			$map_types['yahoo_us'] = $lang['yahoo_us'];
			$map_types['mapquest_UY'] = $lang['mapquest_UY'];
			$map_types['mapquest_UZ'] = $lang['mapquest_UZ'];
			$map_types['mapquest_VA'] = $lang['mapquest_VA'];
			$map_types['mapquest_VC'] = $lang['mapquest_VC'];
			$map_types['mapquest_VE'] = $lang['mapquest_VE'];
			$map_types['mapquest_VG'] = $lang['mapquest_VG'];
			$map_types['mapquest_VI'] = $lang['mapquest_VI'];
			$map_types['mapquest_VN'] = $lang['mapquest_VN'];
			$map_types['mapquest_VU'] = $lang['mapquest_VU'];
			$map_types['mapquest_WF'] = $lang['mapquest_WF'];
			$map_types['mapquest_WS'] = $lang['mapquest_WS'];
			$map_types['mapquest_YE'] = $lang['mapquest_YE'];
			$map_types['mapquest_YT'] = $lang['mapquest_YT'];
			$map_types['mapquest_ZA'] = $lang['mapquest_ZA'];
			$map_types['mapquest_ZM'] = $lang['mapquest_ZM'];
			$map_types['mapquest_ZW'] = $lang['mapquest_ZW'];
			// Listing Template Field Names for Map Field Selection
			$sql = "SELECT listingsformelements_field_name, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsformelements";
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			$listing_field_name_options[''] = '';
			while (!$recordSet->EOF) {
				$field_name = $recordSet->fields['listingsformelements_field_name'];
				$listing_field_name_options[$field_name] = $field_name.' ('.$recordSet->fields['listingsformelements_field_caption'].')';
				$recordSet->MoveNext();
			}
			// Agent Template Field Names for Vcard Selection
			$sql = "SELECT agentformelements_field_name, agentformelements_field_caption FROM " . $config['table_prefix'] . "agentformelements";
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			$agent_field_name_options[''] = '';
			while (!$recordSet->EOF) {
				$field_name = $recordSet->fields['agentformelements_field_name'];
				$agent_field_name_options[$field_name] = $field_name.' ('.$recordSet->fields['agentformelements_field_caption'].')';
				$recordSet->MoveNext();
			}
			// Listing Template Field Names for Search Field Selection
			$sql = "SELECT listingsformelements_field_name, listingsformelements_field_caption FROM " . $config['table_prefix'] . "listingsformelements WHERE listingsformelements_display_on_browse = 'Yes'";
			$recordSet = $conn->Execute($sql);
			$search_field_sortby_options['random'] = $lang['random'];
			$search_field_sortby_options['listingsdb_id'] = $lang['id'];
			$search_field_sortby_options['listingsdb_title'] = $lang['title'];
			$search_field_sortby_options['listingsdb_featured'] = $lang['featured'];
			$search_field_sortby_options['listingsdb_last_modified'] = $lang['last_modified'];
			$search_field_special_sortby_options['none'] = $lang['none'];
			$search_field_special_sortby_options['listingsdb_featured'] = $lang['featured'];
			$search_field_special_sortby_options['listingsdb_id'] = $lang['id'];
			$search_field_special_sortby_options['listingsdb_title'] = $lang['title'];
			$search_field_special_sortby_options['listingsdb_last_modified'] = $lang['last_modified'];
			if (!$recordSet) {
				$misc->log_error($sql);
			} while (!$recordSet->EOF) {
				$field_name = $recordSet->fields['listingsformelements_field_name'];
				$search_field_sortby_options[$field_name] = $field_name.' ('.$recordSet->fields['listingsformelements_field_caption'].')';
				$search_field_special_sortby_options[$field_name] = $field_name.' ('.$recordSet->fields['listingsformelements_field_caption'].')';
				$recordSet->MoveNext();
			}
			$thumbnail_prog['gd'] = 'GD Libs';
			$thumbnail_prog['imagemagick'] = 'ImageMagick';
			$resize_opts['width'] = 'Width';
			$resize_opts['height'] = 'Height';
			$resize_opts['bestfit'] = 'Best Fit';
			$resize_opts['both'] = 'Both';
			$mainimage_opts['width'] = 'Width';
			$mainimage_opts['height'] = 'Height';
			$mainimage_opts['both'] = 'Both';
			$filedisplay['filename'] = 'Filename';
			$filedisplay['caption'] = 'Caption';
			$filedisplay['both'] = 'Both';
			// Generate GuideString
			$guidestring = '';
			foreach ($_GET as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $vitem) {
						$guidestring .= '&amp;' . urlencode("$k") . '[]=' . urlencode("$vitem");
					}
				}else {
					$guidestring .= '&amp;' . urlencode("$k") . '=' . urlencode("$v");
				}
			}
			// Save any Post Data
			if (isset($_POST['controlpanel_admin_name'])) {
				if ($ml_support === true) {
					// Setup any new Language Databases
					require_once($config['basepath'] . '/include/multilingual.inc.php');
					foreach ($_POST['controlpanel_configured_langs'] as $f) {
						// $display .= $f;
						$new_langs[] = $f;
					}
					$sql = 'SELECT controlpanel_configured_langs from ' . $config['table_prefix_no_lang'] . 'controlpanel';
					$recordSet = $conn->Execute($sql);
					if (!$recordSet) {
						$misc->log_error($sql);
					}
					$old_langs = explode(',', $recordSet->fields['controlpanel_configured_langs']);
					// Setup New Language Tables
					foreach ($new_langs as $newlang) {
						if (!in_array($newlang, $old_langs)) {
							multilingual::setup_additional_language($newlang);
						}
					}
					// Remove Old Language Tables
					foreach ($old_langs as $oldlang) {
						if (!in_array($oldlang, $new_langs)) {
							multilingual::remove_additional_language($oldlang);
						}
					}
				}
				// Update ControlPanel
				$sql = 'UPDATE ' . $config['table_prefix_no_lang'] . 'controlpanel SET ';
				$sql_part = '';
				foreach($_POST as $field => $value) {
					if (is_array($value)) {
						$value2 = '';
						foreach ($value as $f) {
							if ($value2 == '') {
								$value2 = "$f";
							}else {
								$value2 .= ",$f";
							}
						}
						$value2 = $misc->make_db_safe($value2);
						if ($sql_part == '') {
							$sql_part = "$field = $value2";
						}else {
							$sql_part .= " , $field = $value2";
						}
					}else {
						$value = $misc->make_db_safe($value);
						if ($sql_part == '') {
							$sql_part = "$field = $value";
						}else {
							$sql_part .= " , $field = $value";
						}
					}
				}
				$sql .= $sql_part;
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$display .= '<br /><b>' . $lang['configuration_saved'] . '</b><br />';
			}
			// START SITE CONFIGURATOR
			$sql = 'SELECT * from ' . $config["table_prefix_no_lang"] . 'controlpanel';
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			// Include the Form Generation Class
			include($config['basepath'] . '/include/class/form_generation.inc.php');
			$formGen = new formGeneration();
			$display .= '<h2>' . $lang['open_realty_configurator'] . '</h2>';
			$display .= $formGen->startform('index.php?' . $guidestring);
			//Start tabbed page
			$display .= '<div class="tab-pane" id="tabPane1">';
			$display .= '<script type="text/javascript">'."\r\n";
			$display .= 'tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );'."\r\n";
			$display .= '</script>'."\r\n";
			//Tab 1
			$display .= '<div class="tab-page" id="tabPage1">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_general'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage1" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_general_info'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_name'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_admin_name', $misc->make_db_unsafe($recordSet->fields['controlpanel_admin_name']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_admin_name'])) . '</td>';
			$display .= '<td>' . $lang['admin_name_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['admin_email'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_admin_email', $misc->make_db_unsafe($recordSet->fields['controlpanel_admin_email']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_admin_email'])) . '</td>';
			$display .= '<td>' . $lang['admin_email_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['site_email'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_site_email', $misc->make_db_unsafe($recordSet->fields['controlpanel_site_email']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_site_email'])) . '</td>';
			$display .= '<td>' . $lang['site_email_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['company_name'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_company_name', $misc->make_db_unsafe($recordSet->fields['controlpanel_company_name']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_company_name'])) . '</td>';
			$display .= '<td>' . $lang['company_name_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['company_location'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_company_location', $misc->make_db_unsafe($recordSet->fields['controlpanel_company_location']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_company_location'])) . '</td>';
			$display .= '<td>' . $lang['company_location_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['company_logo'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_company_logo', $misc->make_db_unsafe($recordSet->fields['controlpanel_company_logo']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_company_logo'])) . '</td>';
			$display .= '<td>' . $lang['company_logo_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['automatic_update_check'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_automatic_update_check', $misc->make_db_unsafe($recordSet->fields['controlpanel_automatic_update_check']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_automatic_update_check'])) . '</td>';
			$display .= '<td>' . $lang['automatic_update_check_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['demo_mode'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_demo_mode', $misc->make_db_unsafe($recordSet->fields['controlpanel_demo_mode']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_demo_mode'])) . '</td>';
			$display .= '<td>' . $lang['demo_mode_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['maintenance_mode'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_maintenance_mode', $misc->make_db_unsafe($recordSet->fields['controlpanel_maintenance_mode']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_maintenance_mode'])) . '</td>';
			$display .= '<td>' . $lang['maintenance_mode_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset><br />';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_server_paths'] . '</b></legend>';
			$display .= '<table align="center" cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['base_url'] . '</strong></td>';
			$display .= '<td>' . $misc->make_db_unsafe($recordSet->fields['controlpanel_baseurl']) . '</td>';
			$display .= '<td>' . $lang['base_url_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['base_path'] . '</strong></td>';
			$display .= '<td>' . $misc->make_db_unsafe($recordSet->fields['controlpanel_basepath']) . '</td>';
			$display .= '<td>' . $lang['base_path_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset><br />';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_language_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="150"><strong>' . $lang['lang'] . '</strong></td>';
			// Get Language Options
			$dir = 0;
			$options = array();
			if ($handle = opendir($config['basepath'] . '/include/language')) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
						if (is_dir($config['basepath'] . '/include/language/' . $file)) {
							$options[$file] = $file;
							$dir++;
						}
					}
				}
				closedir($handle);
			}
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_lang', $misc->make_db_unsafe($recordSet->fields['controlpanel_lang']), false, 35, '', '', '', '', $options, $misc->make_db_unsafe($recordSet->fields['controlpanel_lang']), true) . '</td>';
			$display .= '<td>' . $lang['lang_desc'] . '</td>';
			$display .= '</tr>';
			if ($ml_support === true) {
				$display .= '<tr class=tdshade1>';
				$display .= '<td><strong>' . $lang['configured_langs'] . '</strong></td>';
				$dir = 0;
				$options = array();
				if ($handle = opendir($config['basepath'] . '/include/language')) {
					while (false !== ($file = readdir($handle))) {
						if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
							if (is_dir($config['basepath'] . '/include/language/' . $file)) {
								$options[$file] = $file;
								$dir++;
							}
						}
					}
					closedir($handle);
				}
				$selected = explode(',', $recordSet->fields['controlpanel_configured_langs']);
				$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_configured_langs[]', $misc->make_db_unsafe($recordSet->fields['controlpanel_configured_langs']), true, 8, '', '', '', '', $options, $selected) . '</td>';
				$display .= '<td>' . $lang['configured_langs_desc'] . '</td>';
				$display .= '</tr>';
			}
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';
			//End Tab1
			//Tab 2
			$display .= '<div class="tab-page" id="tabPage2">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_template'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage2" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_template_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$url_type[1] = $lang['url_standard'];
			$url_type[2] = $lang['url_search_friendly'];
			$url_seperator["+"] = $lang['url_seperator_default'];
			$url_seperator["-"] = $lang['url_seperator_hyphen'];
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['charset'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_charset', $misc->make_db_unsafe($recordSet->fields['controlpanel_charset']), false, 35, '', '', '', '', $charset, $misc->make_db_unsafe($recordSet->fields['controlpanel_charset'])) . '</td>';
			$display .= '<td>' . $lang['charset_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['template'] . '</strong></td>';
			// Get Template List
			$dir = 0;
			$options = array();
			if ($handle = opendir($config['basepath'] . '/template')) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
						if (is_dir($config['basepath'] . '/template/' . $file)) {
							$options[$file] = $file;
							$dir++;
						}
					}
				}
				closedir($handle);
			}
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_template', $misc->make_db_unsafe($recordSet->fields['controlpanel_template']), false, 35, '', '', '', '', $options, $misc->make_db_unsafe($recordSet->fields['controlpanel_template'])) . '</td>';
			$display .= '<td>' . $lang['template_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['admin_template'] . '</strong></td>';
			// Get Template List
			$dir = 0;
			$options = array();
			if ($handle = opendir($config['basepath'] . '/admin/template')) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
						if (is_dir($config['basepath'] . '/admin/template/' . $file)) {
							$options[$file] = $file;
							$dir++;
						}
					}
				}
				closedir($handle);
			}
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_admin_template', $misc->make_db_unsafe($recordSet->fields['controlpanel_admin_template']), false, 35, '', '', '', '', $options, $misc->make_db_unsafe($recordSet->fields['controlpanel_admin_template'])) . '</td>';
			$display .= '<td>' . $lang['admin_template_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['listing_template'] . '</strong></td>';
			// Get Listing Template List
			$options = array();
			if ($handle = opendir($config['basepath'] . '/template/' . $config['template'])) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
						if (!is_dir($config['basepath'] . '/template/' . $config['template'] . '/' . $file)) {
							if (substr($file, 0, 14) == 'listing_detail') {
								$options[$file] = substr($file, 15, -5);
							}
						}
					}
				}
				closedir($handle);
			}
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_listing_template', $misc->make_db_unsafe($recordSet->fields['controlpanel_listing_template']), false, 35, '', '', '', '', $options, $misc->make_db_unsafe($recordSet->fields['controlpanel_listing_template'])) . '</td>';
			$display .= '<td>' . $lang['listing_template_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['template_listing_sections'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_template_listing_sections', $misc->make_db_unsafe($recordSet->fields['controlpanel_template_listing_sections']), false, 35, '', '', '', '', '', $misc->make_db_unsafe($recordSet->fields['controlpanel_template_listing_sections'])) . '</td>';
			$display .= '<td>' . $lang['template_listing_sections_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['search_result_template'] . '</strong></td>';
			// Get Search Result Template List
			$options = array();
			if ($handle = opendir($config['basepath'] . '/template/' . $config['template'])) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
						if (!is_dir($config['basepath'] . '/template/' . $config['template'] . '/' . $file)) {
							if (substr($file, 0, 13) == 'search_result') {
								$options[$file] = substr($file, 14, -5);
							}
						}
					}
				}
				closedir($handle);
			}
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_search_result_template', $misc->make_db_unsafe($recordSet->fields['controlpanel_search_result_template']), false, 35, '', '', '', '', $options, $misc->make_db_unsafe($recordSet->fields['controlpanel_search_result_template'])) . '</td>';
			$display .= '<td>' . $lang['search_result_template_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['view_agent_template'] . '</strong></td>';
			// Get View Agent Template List
			$options = array();
			if ($handle = opendir($config['basepath'] . '/template/' . $config['template'])) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
						if (!is_dir($config['basepath'] . '/template/' . $config['template'] . '/' . $file)) {
							if (substr($file, 0, 10) == 'view_user_') {
								$options[$file] = substr($file, 10, -5);
							}
						}
					}
				}
				closedir($handle);
			}
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_template', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_template']), false, 35, '', '', '', '', $options, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_template'])) . '</td>';
			$display .= '<td>' . $lang['view_agent_template_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['vtour_template'] . '</strong></td>';
			// Get VTour Template List
			$options = array();
			if ($handle = opendir($config['basepath'] . '/template/' . $config['template'])) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
						if (!is_dir($config['basepath'] . '/template/' . $config['template'] . '/' . $file)) {
							if (substr($file, 0, 6) == 'vtour_') {
								$options[$file] = substr($file, 6, -5);
							}
						}
					}
				}
				closedir($handle);
			}
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_vtour_template', $misc->make_db_unsafe($recordSet->fields['controlpanel_vtour_template']), false, 35, '', '', '', '', $options, $misc->make_db_unsafe($recordSet->fields['controlpanel_vtour_template'])) . '</td>';
			$display .= '<td>' . $lang['vtour_template_desc'] . '</td>';
			$display .= '</tr>';
			//Listing Notification Template
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['notify_template'] . '</strong></td>';
			// Get VTour Template List
			$options = array();
			if ($handle = opendir($config['basepath'] . '/template/' . $config['template'])) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
						if (!is_dir($config['basepath'] . '/template/' . $config['template'] . '/' . $file)) {
							if (substr($file, 0, 16) == 'notify_listings_') {
								$options[$file] = substr($file, 16,-5);
							}
						}
					}
				}
				closedir($handle);
			}
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_notify_listings_template', $misc->make_db_unsafe($recordSet->fields['controlpanel_notify_listings_template']), false, 35, '', '', '', '', $options, $misc->make_db_unsafe($recordSet->fields['controlpanel_notify_listings_template'])) . '</td>';
			$display .= '<td>' . $lang['notify_template_desc'] . '</td>';
			$display .= '</tr>';

			$display .= '</table>';
			$display .= '</fieldset>';
			$display .=  '</div>';
			//End Tab2
			//Start tab3
			$display .= '<div class="tab-page" id="tabPage3">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_seo'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage3" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_seo_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['url_type'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_url_style', $misc->make_db_unsafe($recordSet->fields['controlpanel_url_style']), false, 35, '', '', '', '', $url_type, $misc->make_db_unsafe($recordSet->fields['controlpanel_url_style'])) . '</td>';
			$display .= '<td>' . $lang['url_type_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['url_seperator'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_seo_url_seperator', $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_url_seperator']), false, 35, '', '', '', '', $url_seperator, $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_url_seperator'])) . '</td>';
			$display .= '<td>' . $lang['url_seperator_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['seo_default_title'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_seo_default_title', $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_default_title']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_default_title'])) . '</td>';
			$display .= '<td>' . $lang['seo_default_title_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['seo_default_keywords'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_seo_default_keywords', $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_default_keywords']), false, 35, '', '', '', '', $url_type, $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_default_keywords'])) . '</td>';
			$display .= '<td>' . $lang['seo_default_keywords_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['seo_default_description'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_seo_default_description', $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_default_description']), false, 35, '', '', '', '', $url_type, $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_default_description'])) . '</td>';
			$display .= '<td>' . $lang['seo_default_description_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['seo_listing_title'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_seo_listing_title', $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_listing_title']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_listing_title'])) . '</td>';
			$display .= '<td>' . $lang['seo_listing_title_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['seo_listing_keywords'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_seo_listing_keywords', $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_listing_keywords']), false, 35, '', '', '', '', $url_type, $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_listing_keywords'])) . '</td>';
			$display .= '<td>' . $lang['seo_listing_keywords_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['seo_listing_description'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_seo_listing_description', $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_listing_description']), false, 35, '', '', '', '', $url_type, $misc->make_db_unsafe($recordSet->fields['controlpanel_seo_listing_description'])) . '</td>';
			$display .= '<td>' . $lang['seo_listing_description_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>'; //End tab3
			//start tab4
			$display .= '<div class="tab-page" id="tabPage4">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_wysiwyg'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage4" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_wysiwyg_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['wysiwyg_editor'] . '</strong></td>';
			$wysiwyg_editor_list = array();
			$wysiwyg_editor_list['list'] = 'None';
			if (file_exists($config['basepath'] . '/include/class/fckeditor')) {
				$wysiwyg_editor_list['fckeditor'] = 'FCKeditor';
			}
			if (file_exists($config['basepath'] . '/include/class/xinha')) {
				$wysiwyg_editor_list['xinha'] = 'Xinha';
			}
			if (file_exists($config['basepath'] . '/include/class/tinymce')) {
				$wysiwyg_editor_list['tinymce'] = 'TinyMCE';
			}
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_wysiwyg_editor', $misc->make_db_unsafe($recordSet->fields['controlpanel_wysiwyg_editor']), false, 35, '', '', '', '', $wysiwyg_editor_list, $misc->make_db_unsafe($recordSet->fields['controlpanel_wysiwyg_editor'])) . '</td>';
			$display .= '<td>' . $lang['wysiwyg_editor_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['wysiwyg_show_edit'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_wysiwyg_show_edit', $misc->make_db_unsafe($recordSet->fields['controlpanel_wysiwyg_show_edit']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_wysiwyg_show_edit'])) . '</td>';
			$display .= '<td>' . $lang['wysiwyg_show_edit_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['wysiwyg_execute_php'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_wysiwyg_execute_php', $misc->make_db_unsafe($recordSet->fields['controlpanel_wysiwyg_execute_php']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_wysiwyg_execute_php'])) . '</td>';
			$display .= '<td>' . $lang['wysiwyg_execute_php_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['controlpanel_mbstring_enabled'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_mbstring_enabled', $misc->make_db_unsafe($recordSet->fields['controlpanel_mbstring_enabled']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_mbstring_enabled'])) . '</td>';
			$display .= '<td>' . $lang['mbstring_enabled_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset><br />';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_html_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['add_linefeeds'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_add_linefeeds', $misc->make_db_unsafe($recordSet->fields['controlpanel_add_linefeeds']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_add_linefeeds'])) . '</td>';
			$display .= '<td>' . $lang['add_linefeeds_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['strip_html'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_strip_html', $misc->make_db_unsafe($recordSet->fields['controlpanel_strip_html']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_strip_html'])) . '</td>';
			$display .= '<td>' . $lang['strip_html_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['allowed_html_tags'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_allowed_html_tags', $misc->make_db_unsafe($recordSet->fields['controlpanel_allowed_html_tags']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_allowed_html_tags'])) . '</td>';
			$display .= '<td>' . $lang['allowed_html_tags_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';//End tab4
			//start tab5
			$display .= '<div class="tab-page" id="tabPage5">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_numbers'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage5" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_number_formatting'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$number_format[1] = '1,000.00';
			$number_format[2] = '1.000,00';
			$number_format[3] = '1 000.00';
			$number_format[4] = '1 000,00';
			$number_format[5] = '1\'000,00';
			$number_format[6] = '1-000 00';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['number_format_style'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_number_format_style', $misc->make_db_unsafe($recordSet->fields['controlpanel_number_format_style']), false, 35, '', '', '', '', $number_format, $misc->make_db_unsafe($recordSet->fields['controlpanel_number_format_style'])) . '</td>';
			$display .= '<td>' . $lang['number_format_style_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['number_decimals_number_fields'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_number_decimals_number_fields', $misc->make_db_unsafe($recordSet->fields['controlpanel_number_decimals_number_fields']), false, 3, '', '', '', '', $number_format, $misc->make_db_unsafe($recordSet->fields['controlpanel_number_decimals_number_fields'])) . '</td>';
			$display .= '<td>' . $lang['number_decimals_number_fields_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['number_decimals_price_fields'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_number_decimals_price_fields', $misc->make_db_unsafe($recordSet->fields['controlpanel_number_decimals_price_fields']), false, 3, '', '', '', '', $number_format, $misc->make_db_unsafe($recordSet->fields['controlpanel_number_decimals_price_fields'])) . '</td>';
			$display .= '<td>' . $lang['number_decimals_price_fields_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['force_decimals'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_force_decimals', $misc->make_db_unsafe($recordSet->fields['controlpanel_force_decimals']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_force_decimals'])) . '</td>';
			$display .= '<td>' . $lang['force_decimals_desc'] . '</td>';
			$display .= '</tr>';
			$money_format[1] = $misc->make_db_unsafe($recordSet->fields['controlpanel_money_sign']) . '1';
			$money_format[2] = '1' . $misc->make_db_unsafe($recordSet->fields['controlpanel_money_sign']);
			$money_format[3] = $misc->make_db_unsafe($recordSet->fields['controlpanel_money_sign']) . ' 1';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['money_format'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_money_format', $misc->make_db_unsafe($recordSet->fields['controlpanel_money_format']), false, 35, '', '', '', '', $money_format, $misc->make_db_unsafe($recordSet->fields['controlpanel_money_format'])) . '</td>';
			$display .= '<td>' . $lang['money_format_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['money_sign'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_money_sign', $misc->make_db_unsafe($recordSet->fields['controlpanel_money_sign']), false, 2, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_money_sign'])) . '</td>';
			$display .= '<td>' . $lang['money_sign_desc'] . '</td>';
			$display .= '</tr>';
			$date_format[1] = 'mm/dd/yyyy';
			$date_format[2] = 'yyyy/dd/mm';
			$date_format[3] = 'dd/mm/yyyy';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['date_format'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_date_format', $misc->make_db_unsafe($recordSet->fields['controlpanel_date_format']), false, 2, '', '', '', '', $date_format, $misc->make_db_unsafe($recordSet->fields['controlpanel_date_format'])) . '</td>';
			$display .= '<td>' . $lang['date_format_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['zero_price_text'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_zero_price', $misc->make_db_unsafe($recordSet->fields['controlpanel_zero_price']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_zero_price'])) . '</td>';
			$display .= '<td>' . $lang['zero_price_text_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['site_config_price_field'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_price_field', $misc->make_db_unsafe($recordSet->fields['controlpanel_price_field']), false, 35, '', '', '', '', $listing_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_price_field'])) . '</td>';
			$display .= '<td>' . $lang['site_config_price_field_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>'; //End tab5
			//start tab6
			$display .= '<div class="tab-page" id="tabPage6">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_uploads'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage6" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_upload_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['allowed_upload_extensions'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_allowed_upload_extensions', $misc->make_db_unsafe($recordSet->fields['controlpanel_allowed_upload_extensions']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_allowed_upload_extensions'])) . '</td>';
			$display .= '<td>' . $lang['allowed_upload_extensions_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['allowed_upload_types'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_allowed_upload_types', $misc->make_db_unsafe($recordSet->fields['controlpanel_allowed_upload_types']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_allowed_upload_types'])) . '</td>';
			$display .= '<td>' . $lang['allowed_upload_types_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['make_thumbnail'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_make_thumbnail', $misc->make_db_unsafe($recordSet->fields['controlpanel_make_thumbnail']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_make_thumbnail'])) . '</td>';
			$display .= '<td>' . $lang['make_thumbnail_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['thumbnail_width'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_thumbnail_width', $misc->make_db_unsafe($recordSet->fields['controlpanel_thumbnail_width']), false, 4, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_thumbnail_width'])) . '</td>';
			$display .= '<td>' . $lang['thumbnail_width_desc'] . '</td>';
			$display .= '</tr>';

			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['thumbnail_height'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_thumbnail_height', $misc->make_db_unsafe($recordSet->fields['controlpanel_thumbnail_height']), false, 4, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_thumbnail_height'])) . '</td>';
			$display .= '<td>' . $lang['thumbnail_height_desc'] . '</td>';
			$display .= '</tr>';

			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['resize_thumb_by'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_resize_thumb_by', $misc->make_db_unsafe($recordSet->fields['controlpanel_resize_thumb_by']), false, 4, '', '', '', '', $resize_opts, $misc->make_db_unsafe($recordSet->fields['controlpanel_resize_thumb_by'])) . '</td>';
			$display .= '<td>' . $lang['resize_thumb_by_desc'] . '</td>';
			$display .= '</tr>';

			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['thumbnail_prog'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_thumbnail_prog', $misc->make_db_unsafe($recordSet->fields['controlpanel_thumbnail_prog']), false, 4, '', '', '', '', $thumbnail_prog, $misc->make_db_unsafe($recordSet->fields['controlpanel_thumbnail_prog'])) . '</td>';
			$display .= '<td>' . $lang['thumbnail_prog_desc'] . '</td>';
			$display .= '</tr>';
			// Path
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['path_to_imagemagick'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_path_to_imagemagick', $misc->make_db_unsafe($recordSet->fields['controlpanel_path_to_imagemagick']), false, 25, '', '', '', '', $thumbnail_prog, $misc->make_db_unsafe($recordSet->fields['controlpanel_path_to_imagemagick'])) . '</td>';
			$display .= '<td>' . $lang['path_to_imagemagick_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['jpeg_quality'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_jpeg_quality', $misc->make_db_unsafe($recordSet->fields['controlpanel_jpeg_quality']), false, 4, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_jpeg_quality'])) . '</td>';
			$display .= '<td>' . $lang['jpeg_quality_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['resize_img'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_resize_img', $misc->make_db_unsafe($recordSet->fields['controlpanel_resize_img']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_resize_img'])) . '</td>';
			$display .= '<td>' . $lang['resize_img_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['resize_by'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_resize_by', $misc->make_db_unsafe($recordSet->fields['controlpanel_resize_by']), false, 4, '', '', '', '', $resize_opts, $misc->make_db_unsafe($recordSet->fields['controlpanel_resize_by'])) . '</td>';
			$display .= '<td>' . $lang['resize_by_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['gdversion2'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_gd_version', $misc->make_db_unsafe($recordSet->fields['controlpanel_gd_version']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_gd_version'])) . '</td>';
			$display .= '<td>' . $lang['gdversion2_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['show_no_photo'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_show_no_photo', $misc->make_db_unsafe($recordSet->fields['controlpanel_show_no_photo']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_show_no_photo'])) . '</td>';
			$display .= '<td>' . $lang['show_no_photo_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset><br />';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_upload_limits'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['max_listings_uploads'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_listings_uploads', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_uploads']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_uploads'])) . '</td>';
			$display .= '<td>' . $lang['max_listings_uploads_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['max_listings_upload_size'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_listings_upload_size', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_upload_size']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_upload_size'])) . '</td>';
			$display .= '<td>' . $lang['max_listings_upload_size_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['max_listings_upload_width'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_listings_upload_width', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_upload_width']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_upload_width'])) . '</td>';
			$display .= '<td>' . $lang['max_listings_upload_width_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['max_listings_upload_height'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_listings_upload_height', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_upload_height']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_upload_width'])) . '</td>';
			$display .= '<td>' . $lang['max_listings_upload_height_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['max_user_uploads'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_user_uploads', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_user_uploads']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_user_uploads'])) . '</td>';
			$display .= '<td>' . $lang['max_user_uploads_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['max_user_upload_size'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_user_upload_size', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_user_upload_size']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_user_upload_size'])) . '</td>';
			$display .= '<td>' . $lang['max_user_upload_size_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['max_user_upload_width'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_user_upload_width', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_user_upload_width']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_user_upload_width'])) . '</td>';
			$display .= '<td>' . $lang['max_user_upload_width_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['max_user_upload_height'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_user_upload_height', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_user_upload_height']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_user_upload_width'])) . '</td>';
			$display .= '<td>' . $lang['max_user_upload_height_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['max_vtour_uploads'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_vtour_uploads', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_vtour_uploads']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_vtour_uploads'])) . '</td>';
			$display .= '<td>' . $lang['max_vtour_uploads_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['max_vtour_upload_size'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_vtour_upload_size', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_vtour_upload_size']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_vtour_upload_size'])) . '</td>';
			$display .= '<td>' . $lang['max_vtour_upload_size_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['max_vtour_upload_width'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_vtour_upload_width', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_vtour_upload_width']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_vtour_upload_width'])) . '</td>';
			$display .= '<td>' . $lang['max_vtour_upload_width_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['image_display_sizes'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['main_image_display_by'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_main_image_display_by', $misc->make_db_unsafe($recordSet->fields['controlpanel_main_image_display_by']), false, 7, '', '', '', '', $mainimage_opts, $misc->make_db_unsafe($recordSet->fields['controlpanel_main_image_display_by'])) . '</td>';
			$display .= '<td>' . $lang['main_image_display_by_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['main_image_width'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_main_image_width', $misc->make_db_unsafe($recordSet->fields['controlpanel_main_image_width']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_main_image_width'])) . '</td>';
			$display .= '<td>' . $lang['main_image_width_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['main_image_height'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_main_image_height', $misc->make_db_unsafe($recordSet->fields['controlpanel_main_image_height']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_main_image_height'])) . '</td>';
			$display .= '<td>' . $lang['main_image_height_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['number_columns'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_number_columns', $misc->make_db_unsafe($recordSet->fields['controlpanel_number_columns']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_number_columns'])) . '</td>';
			$display .= '<td>' . $lang['number_columns_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>'; //End tab6
//start tab7
			$display .= '<div class="tab-page" id="tabPage7">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_uploads_files'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage7" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_upload_file_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['allowed_upload_extensions'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_allowed_file_upload_extensions', $misc->make_db_unsafe($recordSet->fields['controlpanel_allowed_file_upload_extensions']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_allowed_file_upload_extensions'])) . '</td>';
			$display .= '<td>' . $lang['allowed_upload_extensions_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset><br />';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_upload_file_limits'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['max_file_uploads'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_listings_file_uploads', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_file_uploads']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_file_uploads'])) . '</td>';
			$display .= '<td>' . $lang['max_file_uploads_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['max_file_upload_size'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_listings_file_upload_size', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_file_upload_size']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_listings_file_upload_size'])) . '</td>';
			$display .= '<td>' . $lang['max_file_upload_size_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['max_user_file_uploads'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_users_file_uploads', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_users_file_uploads']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_users_file_uploads'])) . '</td>';
			$display .= '<td>' . $lang['max_user_file_uploads_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['max_user_file_upload_size'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_users_file_upload_size', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_users_file_upload_size']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_users_file_upload_size'])) . '</td>';
			$display .= '<td>' . $lang['max_user_file_upload_size_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['file_display_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['show_file_icon'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_show_file_icon', $misc->make_db_unsafe($recordSet->fields['controlpanel_show_file_icon']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_show_file_icon'])) . '</td>';
			$display .= '<td>' . $lang['show_file_icon_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['show_file_display_option'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_file_display_option', $misc->make_db_unsafe($recordSet->fields['controlpanel_file_display_option']), false, 4, '', '', '', '', $filedisplay, $misc->make_db_unsafe($recordSet->fields['controlpanel_file_display_option'])) . '</td>';
			$display .= '<td>' . $lang['show_file_display_option_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['show_file_size'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_show_file_size', $misc->make_db_unsafe($recordSet->fields['controlpanel_show_file_size']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_show_file_size'])) . '</td>';
			$display .= '<td>' . $lang['show_file_size_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['file_icon_width'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_icon_image_width', $misc->make_db_unsafe($recordSet->fields['controlpanel_icon_image_width']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_icon_image_width'])) . '</td>';
			$display .= '<td>' . $lang['file_icon_width_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['file_icon_height'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_icon_image_height', $misc->make_db_unsafe($recordSet->fields['controlpanel_icon_image_height']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_icon_image_height'])) . '</td>';
			$display .= '<td>' . $lang['file_icon_height_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';//End tab7
//start tab8
			$display .= '<div class="tab-page" id="tabPage8">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_search'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage8" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_search_options'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['search_step_max'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_search_step_max', $misc->make_db_unsafe($recordSet->fields['controlpanel_search_step_max']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_search_step_max'])) . '</td>';
			$display .= '<td>' . $lang['search_step_max_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['listings_per_page'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_listings_per_page', $misc->make_db_unsafe($recordSet->fields['controlpanel_listings_per_page']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_listings_per_page'])) . '</td>';
			$display .= '<td>' . $lang['listings_per_page_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['configured_search_sortby'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_search_sortby', $misc->make_db_unsafe($recordSet->fields['controlpanel_search_sortby']), false, 35, '', '', '', '', $search_field_sortby_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_search_sortby'])) . '</td>';
			$display .= '<td>' . $lang['configured_search_sortby_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['configured_search_sorttype'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_search_sorttype', $misc->make_db_unsafe($recordSet->fields['controlpanel_search_sorttype']), false, 35, '', '', '', '', $asc_desc, $misc->make_db_unsafe($recordSet->fields['controlpanel_search_sorttype'])) . '</td>';
			$display .= '<td>' . $lang['configured_search_sorttype_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['configured_special_search_sortby'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_special_search_sortby', $misc->make_db_unsafe($recordSet->fields['controlpanel_special_search_sortby']), false, 35, '', '', '', '', $search_field_special_sortby_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_special_search_sortby'])) . '</td>';
			$display .= '<td>' . $lang['configured_special_search_sortby_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['configured_special_search_sorttype'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_special_search_sorttype', $misc->make_db_unsafe($recordSet->fields['controlpanel_special_search_sorttype']), false, 35, '', '', '', '', $asc_desc, $misc->make_db_unsafe($recordSet->fields['controlpanel_special_search_sorttype'])) . '</td>';
			$display .= '<td>' . $lang['configured_special_search_sorttype_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['configured_show_count'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_configured_show_count', $misc->make_db_unsafe($recordSet->fields['controlpanel_configured_show_count']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_configured_show_count'])) . '</td>';
			$display .= '<td>' . $lang['configured_show_count_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['max_search_results'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_max_search_results', $misc->make_db_unsafe($recordSet->fields['controlpanel_max_search_results']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_max_search_results'])) . '</td>';
			$display .= '<td>' . $lang['max_search_results_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['search_list_separator'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_search_list_separator', $misc->make_db_unsafe($recordSet->fields['controlpanel_search_list_separator']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_search_list_separator'])) . '</td>';
			$display .= '<td>' . $lang['search_list_separator_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['textarea_short_chars'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_textarea_short_chars', $misc->make_db_unsafe($recordSet->fields['controlpanel_textarea_short_chars']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_textarea_short_chars'])) . '</td>';
			$display .= '<td>' . $lang['textarea_short_chars_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';//End tab8
//start tab9
			$display .= '<div class="tab-page" id="tabPage9">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_vtours'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage9" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_vtour_options'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['vtour_width'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_vtour_width', $misc->make_db_unsafe($recordSet->fields['controlpanel_vtour_width']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_vtour_width'])) . '</td>';
			$display .= '<td>' . $lang['vtour_width_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['vtour_height'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_vtour_height', $misc->make_db_unsafe($recordSet->fields['controlpanel_vtour_height']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_vtour_height'])) . '</td>';
			$display .= '<td>' . $lang['vtour_height_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['vtour_fov'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_vtour_fov', $misc->make_db_unsafe($recordSet->fields['controlpanel_vtour_fov']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_vtour_fov'])) . '</td>';
			$display .= '<td>' . $lang['vtour_fov_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="100"><strong>' . $lang['vt_popup_width'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_vt_popup_width', $misc->make_db_unsafe($recordSet->fields['controlpanel_vt_popup_width']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_vt_popup_width'])) . '</td>';
			$display .= '<td>' . $lang['vt_popup_width_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['vt_popup_height'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_vt_popup_height', $misc->make_db_unsafe($recordSet->fields['controlpanel_vt_popup_height']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_vt_popup_height'])) . '</td>';
			$display .= '<td>' . $lang['vt_popup_height_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';//End tab9
//start tab10
			$display .= '<div class="tab-page" id="tabPage10">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_notify'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage10" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_notification_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['email_notification_of_new_users'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_email_notification_of_new_users', $misc->make_db_unsafe($recordSet->fields['controlpanel_email_notification_of_new_users']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_email_notification_of_new_users'])) . '</td>';
			$display .= '<td>' . $lang['email_notification_of_new_users_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['email_notification_of_new_listings'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_email_notification_of_new_listings', $misc->make_db_unsafe($recordSet->fields['controlpanel_email_notification_of_new_listings']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_email_notification_of_new_listings'])) . '</td>';
			$display .= '<td>' . $lang['email_notification_of_new_listings_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['email_users_notification_of_new_listings'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_email_users_notification_of_new_listings', $misc->make_db_unsafe($recordSet->fields['controlpanel_email_users_notification_of_new_listings']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_email_users_notification_of_new_listings'])) . '</td>';
			$display .= '<td>' . $lang['email_users_notification_of_new_listings_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['email_registration_information_to_new_users'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_email_information_to_new_users', $misc->make_db_unsafe($recordSet->fields['controlpanel_email_information_to_new_users']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_email_information_to_new_users'])) . '</td>';
			$display .= '<td>' . $lang['email_information_to_new_users_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['use_email_image_verification'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_use_email_image_verification', $misc->make_db_unsafe($recordSet->fields['controlpanel_use_email_image_verification']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_use_email_image_verification'])) . '</td>';
			$display .= '<td>' . $lang['use_email_image_verification_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['disable_referrer_check'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_disable_referrer_check', $misc->make_db_unsafe($recordSet->fields['controlpanel_disable_referrer_check']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_disable_referrer_check'])) . '</td>';
			$display .= '<td>' . $lang['disable_referrer_check_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['include_senders_ip'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_include_senders_ip', $misc->make_db_unsafe($recordSet->fields['controlpanel_include_senders_ip']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_include_senders_ip'])) . '</td>';
			$display .= '<td>' . $lang['include_senders_ip_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';//End tab10
//start tab11
			$display .= '<div class="tab-page" id="tabPage11">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_users'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage11" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_signup_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['signup_image_verification'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_use_signup_image_verification', $misc->make_db_unsafe($recordSet->fields['controlpanel_use_signup_image_verification']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_use_signup_image_verification'])) . '</td>';
			$display .= '<td>' . $lang['signup_image_verification_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['signup_email_verification'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_require_email_verification', $misc->make_db_unsafe($recordSet->fields['controlpanel_require_email_verification']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_require_email_verification'])) . '</td>';
			$display .= '<td>' . $lang['signup_email_verification_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset><br />';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_member_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['moderate_members'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_moderate_members', $misc->make_db_unsafe($recordSet->fields['controlpanel_moderate_members']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_moderate_members'])) . '</td>';
			$display .= '<td>' . $lang['moderate_members_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['allow_member_signup'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_allow_member_signup', $misc->make_db_unsafe($recordSet->fields['controlpanel_allow_member_signup']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_allow_member_signup'])) . '</td>';
			$display .= '<td>' . $lang['allow_member_signup_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset><br />';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_agent_permissions'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['moderate_agents'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_moderate_agents', $misc->make_db_unsafe($recordSet->fields['controlpanel_moderate_agents']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_moderate_agents'])) . '</td>';
			$display .= '<td>' . $lang['moderate_agents_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['allow_agent_signup'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_allow_agent_signup', $misc->make_db_unsafe($recordSet->fields['controlpanel_allow_agent_signup']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_allow_agent_signup'])) . '</td>';
			$display .= '<td>' . $lang['allow_agent_signup_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['agent_default_active'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_active', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_active']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_active'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_active_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['agent_default_admin'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_admin', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_admin']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_admin'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_admin_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['agent_default_edit_all_users'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_edit_all_users', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_all_users']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_all_users'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_edit_all_users_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['agent_default_edit_all_listings'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_edit_all_listings', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_all_listings']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_all_listings'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_edit_all_listings_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['agent_default_feature'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_feature', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_feature']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_feature'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_feature_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['agent_default_moderate'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_moderate', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_moderate']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_moderate'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_moderate_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['agent_default_logview'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_logview', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_logview']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_logview'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_logview_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['agent_default_edit_site_config'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_edit_site_config', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_site_config']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_site_config'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_edit_site_config_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['agent_default_edit_member_template'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_edit_member_template', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_member_template']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_member_template'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_edit_member_template_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['agent_default_edit_agent_template'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_edit_agent_template', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_agent_template']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_agent_template'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_edit_agent_template_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['agent_default_edit_listing_template'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_edit_listing_template', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_listing_template']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_edit_listing_template'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_edit_listing_template_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['agent_default_canExportListings'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_can_export_listings', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_can_export_listings']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_can_export_listings'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_canExportListings_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['agent_default_canChangeExpirations'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_canchangeexpirations', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_canchangeexpirations']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_canchangeexpirations'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_canChangeExpirations_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['agent_default_editpages'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_editpages', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_editpages']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_editpages'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_editpages_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['agent_default_havevtours'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_havevtours', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_havevtours']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_havevtours'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_havevtours_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['agent_default_havefiles'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_agent_default_havefiles', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_havefiles']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_havefiles'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_havefiles_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['agent_default_num_listings'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_agent_default_num_listings', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_num_listings']), false, 4, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_num_listings'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_num_listings_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['agent_default_num_featuredlistings'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_agent_default_num_featuredlistings', $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_num_featuredlistings']), false, 4, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_agent_default_num_featuredlistings'])) . '</td>';
			$display .= '<td>' . $lang['agent_default_num_featuredlistings_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset><br />';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_agent_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['users_per_page'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_users_per_page', $misc->make_db_unsafe($recordSet->fields['controlpanel_users_per_page']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_users_per_page'])) . '</td>';
			$display .= '<td>' . $lang['users_per_page_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['admin_show_admin_on_agent_list'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_show_admin_on_agent_list', $misc->make_db_unsafe($recordSet->fields['controlpanel_show_admin_on_agent_list']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_show_admin_on_agent_list'])) . '</td>';
			$display .= '<td>' . $lang['admin_show_admin_on_agent_list_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';//End tab11
//Start tab12
			$display .= '<div class="tab-page" id="tabPage12">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_listings'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage12" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_listing_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['allow_multiple_pclasses_selection'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_multiple_pclass_selection', $misc->make_db_unsafe($recordSet->fields['controlpanel_multiple_pclass_selection']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_multiple_pclass_selection'])) . '</td>';
			$display .= '<td>' . $lang['allow_multiple_pclasses_selection_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['num_featured_listings'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_num_featured_listings', $misc->make_db_unsafe($recordSet->fields['controlpanel_num_featured_listings']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_num_featured_listings'])) . '</td>';
			$display .= '<td>' . $lang['num_featured_listings_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['use_expiration'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_use_expiration', $misc->make_db_unsafe($recordSet->fields['controlpanel_use_expiration']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_use_expiration'])) . '</td>';
			$display .= '<td>' . $lang['use_expiration_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['days_until_listings_expire'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_days_until_listings_expire', $misc->make_db_unsafe($recordSet->fields['controlpanel_days_until_listings_expire']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_days_until_listings_expire'])) . '</td>';
			$display .= '<td>' . $lang['days_until_listings_expire_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['moderate_listings'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_moderate_listings', $misc->make_db_unsafe($recordSet->fields['controlpanel_moderate_listings']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_moderate_listings'])) . '</td>';
			$display .= '<td>' . $lang['moderate_listings_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['export_listings'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_export_listings', $misc->make_db_unsafe($recordSet->fields['controlpanel_export_listings']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_export_listings'])) . '</td>';
			$display .= '<td>' . $lang['export_listings_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['show_listedby_admin'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_show_listedby_admin', $misc->make_db_unsafe($recordSet->fields['controlpanel_show_listedby_admin']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_show_listedby_admin'])) . '</td>';
			$display .= '<td>' . $lang['show_listedby_admin_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['show_next_prev_listing_page'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_show_next_prev_listing_page', $misc->make_db_unsafe($recordSet->fields['controlpanel_show_next_prev_listing_page']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_show_next_prev_listing_page'])) . '</td>';
			$display .= '<td>' . $lang['show_next_prev_listing_page_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['show_notes_field'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_show_notes_field', $misc->make_db_unsafe($recordSet->fields['controlpanel_show_notes_field']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_show_notes_field'])) . '</td>';
			$display .= '<td>' . $lang['show_notes_field_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['feature_list_separator'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_feature_list_separator', $misc->make_db_unsafe($recordSet->fields['controlpanel_feature_list_separator']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_feature_list_separator'])) . '</td>';
			$display .= '<td>' . $lang['feature_list_separator_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';//End tab12
//Start tab13
			$display .= '<div class="tab-page" id="tabPage13">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_map'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage13" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_heading_map_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['site_config_map_type'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_map_type', $misc->make_db_unsafe($recordSet->fields['controlpanel_map_type']), false, 35, '', '', '', '', $map_types, $misc->make_db_unsafe($recordSet->fields['controlpanel_map_type'])) . '</td>';
			$display .= '<td>' . $lang['site_config_map_type_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['site_config_map_address'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_map_address', $misc->make_db_unsafe($recordSet->fields['controlpanel_map_address']), false, 35, '', '', '', '', $listing_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_map_address'])) . '</td>';
			$display .= '<td>' . $lang['site_config_map_address_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['site_config_map_address2'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_map_address2', $misc->make_db_unsafe($recordSet->fields['controlpanel_map_address2']), false, 35, '', '', '', '', $listing_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_map_address2'])) . '</td>';
			$display .= '<td>' . $lang['site_config_map_address2_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['site_config_map_address3'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_map_address3', $misc->make_db_unsafe($recordSet->fields['controlpanel_map_address3']), false, 35, '', '', '', '', $listing_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_map_address3'])) . '</td>';
			$display .= '<td>' . $lang['site_config_map_address3_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['site_config_map_address4'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_map_address4', $misc->make_db_unsafe($recordSet->fields['controlpanel_map_address4']), false, 35, '', '', '', '', $listing_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_map_address4'])) . '</td>';
			$display .= '<td>' . $lang['site_config_map_address4_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['site_config_map_city'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_map_city', $misc->make_db_unsafe($recordSet->fields['controlpanel_map_city']), false, 35, '', '', '', '', $listing_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_map_city'])) . '</td>';
			$display .= '<td>' . $lang['site_config_map_city_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['site_config_map_state'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_map_state', $misc->make_db_unsafe($recordSet->fields['controlpanel_map_state']), false, 35, '', '', '', '', $listing_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_map_state'])) . '</td>';
			$display .= '<td>' . $lang['site_config_map_state_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td><strong>' . $lang['site_config_map_zip'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_map_zip', $misc->make_db_unsafe($recordSet->fields['controlpanel_map_zip']), false, 35, '', '', '', '', $listing_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_map_zip'])) . '</td>';
			$display .= '<td>' . $lang['site_config_map_zip_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td><strong>' . $lang['site_config_map_country'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_map_country', $misc->make_db_unsafe($recordSet->fields['controlpanel_map_country']), false, 35, '', '', '', '', $listing_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_map_country'])) . '</td>';
			$display .= '<td>' . $lang['site_config_map_country_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';//End tab13
//Start tab14
			$display .= '<div class="tab-page" id="tabPage14">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_vcards'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage14" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['site_config_vcard_settings'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['site_config_vcard_phone'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_vcard_phone', $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_phone']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_phone'])) . '</td>';
			$display .= '<td>' . $lang['site_config_vcard_phone_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['site_config_vcard_fax'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_vcard_fax', $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_fax']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_fax'])) . '</td>';
			$display .= '<td>' . $lang['site_config_vcard_fax_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['site_config_vcard_mobile'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_vcard_mobile', $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_mobile']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_mobile'])) . '</td>';
			$display .= '<td>' . $lang['site_config_vcard_mobile_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['site_config_vcard_address'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_vcard_address', $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_address']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_address'])) . '</td>';
			$display .= '<td>' . $lang['site_config_vcard_address_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['site_config_vcard_city'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_vcard_city', $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_city']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_city'])) . '</td>';
			$display .= '<td>' . $lang['site_config_vcard_city_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['site_config_vcard_state'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_vcard_state', $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_state']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_state'])) . '</td>';
			$display .= '<td>' . $lang['site_config_vcard_state_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['site_config_vcard_zip'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_vcard_zip', $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_zip']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_zip'])) . '</td>';
			$display .= '<td>' . $lang['site_config_vcard_zip_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['site_config_vcard_country'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_vcard_country', $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_country']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_country'])) . '</td>';
			$display .= '<td>' . $lang['site_config_vcard_country_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['site_config_vcard_notes'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_vcard_notes', $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_notes']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_notes'])) . '</td>';
			$display .= '<td>' . $lang['site_config_vcard_notes_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['site_config_vcard_url'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_vcard_url', $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_utl']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_vcard_url'])) . '</td>';
			$display .= '<td>' . $lang['site_config_vcard_url_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';//End tab14
//Start tab15
			$display .= '<div class="tab-page" id="tabPage15">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_rss'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage15" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['rss_config'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['rss_title_featured'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_rss_title_featured', $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_title_featured']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_title_featured'])) . '</td>';
			$display .= '<td>' . $lang['rss_title_featured_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['rss_desc_featured'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_rss_desc_featured', $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_desc_featured']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_desc_featured'])) . '</td>';
			$display .= '<td>' . $lang['rss_desc_featured_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['rss_listingdesc_featured'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_rss_listingdesc_featured', $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_listingdesc_featured']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_listingdesc_featured'])) . '</td>';
			$display .= '<td>' . $lang['rss_listingdesc_featured_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['rss_limit_featured'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_rss_limit_featured', $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_limit_featured']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_limit_featured'])) . '</td>';
			$display .= '<td>' . $lang['rss_limit_featured_desc'] . '</td>';
			$display .= '</tr>';
			//Last modified RSS Feed
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['rss_title_lastmodified'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_rss_title_lastmodified', $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_title_lastmodified']), false, 35, '', '', '', '', $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_title_lastmodified'])) . '</td>';
			$display .= '<td>' . $lang['rss_title_lastmodified_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['rss_desc_lastmodified'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_rss_desc_lastmodified', $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_desc_lastmodified']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_desc_lastmodified'])) . '</td>';
			$display .= '<td>' . $lang['rss_desc_lastmodified_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['rss_listingdesc_lastmodified'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_rss_listingdesc_lastmodified',$misc->make_db_unsafe($recordSet->fields['controlpanel_rss_listingdesc_lastmodified']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_listingdesc_lastmodified'])) . '</td>';
			$display .= '<td>' . $lang['rss_listingdesc_lastmodified_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="100"><strong>' . $lang['rss_limit_lastmodified'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('text', 'controlpanel_rss_limit_lastmodified', $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_limit_lastmodified']), false, 7, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_rss_limit_lastmodified'])) . '</td>';
			$display .= '<td>' . $lang['rss_limit_lastmodified_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';//End tab15
//Start tab16
			$display .= '<div class="tab-page" id="tabPage16">';
			$display .= '<h2 class="tab">'.$lang['site_config_tab_help'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage16" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['help_config'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['use_help_links'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_use_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_use_help_link']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_use_help_link'])) . '</td>';
			$display .= '<td>' . $lang['use_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_main_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_main_admin_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_main_admin_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_main_admin_help_link'])) . '</td>';
			$display .= '<td>' . $lang['main_admin_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_configure_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_configure_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_configure_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_configure_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_configure_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_add_listing_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_add_listing_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_add_listing_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_add_listing_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_add_listing_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_listing_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_listing_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_listing_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_modify_listing_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_modify_listing_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_modify_listing_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_modify_listing_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_modify_listing_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_user_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_user_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_user_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_user_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_user_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_user_manager_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_user_manager_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_user_manager_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_user_manager_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_user_manager_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_page_editor_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_page_editor_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_page_editor_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_page_editor_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_page_editor_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_listing_images_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_listing_images_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_images_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_images_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_listing_images_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_vtour_images_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_vtour_images_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_vtour_images_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_vtour_images_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_vtour_images_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_listing_files_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_listing_files_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_files_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_files_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_listing_files_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_agent_template_add_field_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_agent_template_add_field_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_agent_template_add_field_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_agent_template_add_field_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_agent_template_add_field_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_agent_template_field_order_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_agent_template_field_order_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_agent_template_field_order_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_agent_template_field_order_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_agent_template_field_order_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_member_template_add_field_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_member_template_add_field_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_member_template_add_field_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_member_template_add_field_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_member_template_add_field_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_member_template_field_order_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_member_template_field_order_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_member_template_field_order_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_member_template_field_order_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_member_template_field_order_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_listing_template_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_listing_template_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_template_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_template_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_listing_template_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_listing_template_add_field_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_listing_template_add_field_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_template_add_field_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_template_add_field_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_listing_template_add_field_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_listings_template_field_order_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_listings_template_field_order_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listings_template_field_order_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listings_template_field_order_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_listings_template_field_order_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_listing_template_search_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_listing_template_search_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_template_search_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_template_search_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_listing_template_search_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_edit_listing_template_search_results_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_edit_listing_template_search_results_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_template_search_results_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_edit_listing_template_search_results_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_edit_listing_template_search_results_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_show_property_classes_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_show_property_classes_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_show_property_classes_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_show_property_classes_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_show_property_classes_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_view_log_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_view_log_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_view_log_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_view_log_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_view_log_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_user_template_member_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_user_template_member_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_user_template_member_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_user_template_member_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_user_template_member_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_user_template_agent_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_user_template_agent_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_user_template_agent_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_user_template_agent_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_user_template_agent_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_modify_property_class_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_modify_property_class_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_modify_property_class_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_modify_property_class_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_modify_property_class_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_insert_property_class_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_insert_property_class_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_insert_property_class_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_insert_property_class_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_insert_property_class_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_addon_transparentmaps_admin_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_addon_transparentmaps_admin_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_transparentmaps_admin_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_transparentmaps_admin_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_addon_transparentmaps_admin_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_addon_transparentmaps_geocode_all_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_addon_transparentmaps_geocode_all_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_transparentmaps_geocode_all_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_transparentmaps_geocode_all_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_addon_transparentmaps_geocode_all_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_addon_transparentRETS_config_server_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_addon_transparentRETS_config_server_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_transparentRETS_config_server_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_transparentRETS_config_server_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_addon_transparentRETS_config_server_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_addon_transparentRETS_config_imports_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_addon_transparentRETS_config_imports_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_transparentRETS_config_imports_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_transparentRETS_config_imports_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_addon_transparentRETS_config_imports_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_addon_IDXManager_config_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_addon_IDXManager_config_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_IDXManager_config_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_IDXManager_config_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_addon_IDXManager_config_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['admin_addon_IDXManager_classmanager_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_addon_IDXManager_classmanager_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_IDXManager_classmanager_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_IDXManager_classmanager_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_addon_IDXManager_classmanager_help_link_desc'] . '</td>';
			$display .= '</tr>';
			$display .= '<tr class=tdshade1>';
			$display .= '<td width="130"><strong>' . $lang['admin_addon_csvloader_admin_help_link'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('textarea', 'controlpanel_addon_csvloader_admin_help_link', $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_csvloader_admin_help_link']), false, 35, '', '', 5, 35, $agent_field_name_options, $misc->make_db_unsafe($recordSet->fields['controlpanel_addon_csvloader_admin_help_link'])) . '</td>';
			$display .= '<td>' . $lang['admin_addon_csvloader_admin_help_link_desc'] . '</td>';
			$display .= '</tr>';
//End tab16
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';


			//Start tab17
			$display .= '<div class="tab-page" id="tabPage17">';
			$display .= '<h2 class="tab">'.$lang['site_config_blog'].'</h2>';
			$display .= '<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage17" ) );</script>';
			$display .= '<fieldset>';
			$display .= '<legend><b>' . $lang['blog_config'] . '</b></legend>';
			$display .= '<table cellspacing="0" cellpadding="3" width="99%" border="0">';
			$display .= '<tr class=tdshade2>';
			$display .= '<td width="130"><strong>' . $lang['blog_requires_moderation'] . '</strong></td>';
			$display .= '<td>' . $formGen->createformitem('select', 'controlpanel_blog_requires_moderation', $misc->make_db_unsafe($recordSet->fields['controlpanel_blog_requires_moderation']), false, 35, '', '', '', '', $yes_no, $misc->make_db_unsafe($recordSet->fields['controlpanel_blog_requires_moderation'])) . '</td>';
			$display .= '<td>' . $lang['blog_requires_moderation_desc'] . '</td>';
			$display .= '</tr>';
//End tab17
			$display .= '</table>';
			$display .= '</fieldset>';
			$display .= '</div>';
			//End tabbed page
			$display .= '</div>';
			// END OF SITE CONFIGURATOR
			$display .= '<table width="99%" align="center"><tr><td align="center">';
			if (($config["demo_mode"] != 1) || ($_SESSION['admin_privs'] == 'yes')) {
			$display .= $formGen->createformitem('submit', '', $lang['save_changes']);
			} else {
				$display .= $lang['demo_mode_no_changes'];
			}
			$display .= '</td></tr></table>';
			$display .= $formGen->endform();
		}else {
			$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
		return $display;
	} //End  show_configurator
} //End configurator class

?>