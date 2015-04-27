<?php

	/***************************************************************************\
	* Open-Realty																*
	* http://www.open-realty.org												*
	* Written by Ryan C. Bonham <ryan@transparent-tech.com>						*
	* Copyright 2002, 2003 Transparent Technologies								*
	* --------------------------------------------								*
	* This file is part of Open-Realty.											*
	*																			*
	* Open-Realty is free software; you can redistribute it and/or modify		*
	* it under the terms of the Open-Realty License as published by				*
	* Transparent Technologies; either version 1 of the License, or				*
	* (at your option) any later version.										*
	*																			*
	* Open-Realty is distributed in the hope that it will be useful,			*
	* but WITHOUT ANY WARRANTY; without even the implied warranty of			*
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the				*
	* Open-Realty License for more details.										*
	*																			*
	* You should have received a copy of the Open-Realty License				*
	* along with Open-Realty; if not, write to Transparent Technologies			*
	* RR1 Box 162C, Kingsley, PA  18826  USA									*
	\***************************************************************************/

//error_reporting(E_ALL);
error_reporting(E_ALL& ~E_NOTICE);
global $lang;
$lang = array();
if ($handle = opendir(dirname(__FILE__).'/versions')) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
			if (!is_dir(dirname(__FILE__).'/versions/' . $file)) {
				include(dirname(__FILE__).'/versions/' . $file);
				}
			}
		}
	closedir($handle);
	}
if ($handle = opendir(dirname(__FILE__).'/custom')) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".." && $file != "CVS" && $file != ".svn") {
			if (!is_dir(dirname(__FILE__).'/custom/' . $file)) {
				include(dirname(__FILE__).'/custom/' . $file);
				}
			}
		}
	closedir($handle);
	}

?>