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

// SPANISH (Spain) - Translated by José Carlos García (webmaster@demostenes.es) - updated at 23/JUN/2008

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Instalación de Open-Realty";
$this->lang['install_version_warn'] = "Su versión de php no puede manejar este versión de Open-Realty. La Instalación ha sido cancelada.";
$this->lang['install_sqlversion_warn'] = "Su versión de mysql no puede manejar este versión de Open-Realty. La Instalación ha sido cancelada.";
$this->lang['install_php_version'] = "Su versión de PHP es la ";
$this->lang['install_sql_version'] = "Su versión de MySql es la ";
$this->lang['install_php_required'] = "Este versión de Open-Realty requiere como minimo un PHP versión ";
$this->lang['install_sql_required'] = "Este versión de Open-Realty requiere como minimo un MySql versión ";
$this->lang['install_welcome'] = "Bienvenido al herramienta de instalación de Open-Realty.";
$this->lang['install_intro'] = "Este herramienta le guiará paso a paso a través la instalación de Open-Realty. Antes de empezar, es necesario crear un base de datos en limpio en su sistema.  También va a comprobar los permisos de acceso a los archivos y directorios. Hay que cambiar los siguientes archivos al 777 ó 755, dependiendo de la configuración de su servidor: (include/common.php, images/listing_photo, images/user_photos, cache)  AVISO: Por razones de seguridad, hay que cambiar de nuevo los permisos del archivo common.php al 644, una vez terminada la instalación.";
$this->lang['install_step_one_header'] = "Paso 1: Revisión de Permisos de Archivos:";
$this->lang['install_Permission_on'] = "Permisos activos";
$this->lang['install_are_correct'] = "son correctos";
$this->lang['install_are_incorrect'] = "son incorrectos";
$this->lang['install_all_correct'] = "Todos los permisos son correctos.";
$this->lang['install_continue'] = "Haga clic para continuar la instalación";
$this->lang['install_please_fix'] = "Por favor, haga lo necesario para actualizar los permisos de los archivos y de vuelva a actualizar (recargar) esta página.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Elija el tipo de Instalación:';
$this->lang['upgrade_115'] = 'Actualizar una instalación de Open-Realty 1.1.5';
$this->lang['install_200'] = 'Instalación Nueva de Open-Realty 2.0';
$this->lang['move'] = 'Solo actualiza directorio y su información';
$this->lang['upgrade_200'] = 'Actualizar desde Open-Realty 2.x.x (2.0.0 Beta 1 o superior)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Configurar Conexión a Base de Datos:";
$this->lang['install_Database_Type'] = "Tipo de base de datos:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Servidor de base de datos:";
$this->lang['install_Database_Name'] = "Nombre del base de datos:";
$this->lang['install_Database_User'] = "Usuario base de datos:";
$this->lang['install_Database_Password'] = "Clave del usuario del base de datos:";
$this->lang['install_Table Prefix'] = "Prefijo de la Tabla:";
$this->lang['install_Base_URL'] = "URL de Base:";
$this->lang['install_Base_Path'] = "Directorio de Base:";
$this->lang['install_Language'] = "Idioma:";
$this->lang['install_English'] = "Inglés";
$this->lang['install_Spanish'] = "Español";
$this->lang['install_Italian'] = "Italiano";
$this->lang['install_French'] = "Francés";
$this->lang['install_Portuguese'] = "Portugués";
$this->lang['install_Russian'] = "Ruso";
$this->lang['install_Turkish'] = "Turco";
$this->lang['install_German'] = "Alemán";
$this->lang['install_Dutch'] = "Holandés ";
$this->lang['install_Lithuanian'] = "Lithuanian";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "No se puede contactar con su base de datos. Por Favor, revise la configuración e intentelo de nuevo.";

//Step Three
$this->lang['install_get_old_version'] = 'Determinando la versión instalada de Open-Realty';
$this->lang['install_get_old_version_error'] = 'Error en la determinación de la versión instalada de Open-Realty. No se puede continuar con la Actualización.';
$this->lang['install_cleared_cache'] = "La cache ha sido vaciada";
$this->lang['install_connection_ok'] = "Conexión con base de datos, OK.";
$this->lang['install_save_settings'] = "Ahora vamos a guardar su configuración a su archivo - common.php -";
$this->lang['install_settings_saved'] = "Configuración de la base de datos guardada.";
$this->lang['install_continue_db_setup'] = "Seguimos con la instalación de la base de datos.";
$this->lang['install_populate_db'] = "Vamos a poner las tablas en la base de datos.";

//finalize installation
$this->lang['install_installation_complete'] = "Instalación finalizada.";
$this->lang['install_configure_installation'] = "Pulse aquí para configurar su instalación";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Instalación en Modo Desarrollador - Esto permitirá la instalación aún conteniendo errores. ESTO NO ESTA RECOMENDADO.";
$this->lang['yes'] = "Si";
$this->lang['no'] = "No";

?>