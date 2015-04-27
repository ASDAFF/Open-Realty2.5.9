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

// RUSSIAN LANGUAGE FILE - Translated by Sergey Faizulin (swish2@mail.ru) at Jul/2008

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Инсталляция Open-Realty";
$this->lang['install_version_warn'] = "Версия php на вашем сервере не соотвествует той, которая необходима для запуска данной версиии Open-Realty и поэтому инсталляция отменена.";
$this->lang['install_sqlversion_warn'] = "Версия mysql на вашем сервере не соотвествует той, которая необходима для запуска данной версиии Open-Realty и поэтому инсталляция отменена.";
$this->lang['install_php_version'] = "Версия PHP на вашем сервере ";
$this->lang['install_sql_version'] = "Версия MySql на вашем сервере ";
$this->lang['install_php_required'] = "Для данной версии Open-Realty требуется по-меньшей мере PHP версии ";
$this->lang['install_sql_required'] = "Для данной версии Open-Realty требуется по-меньшей мере MySql версии  ";
$this->lang['install_welcome'] = "Добро пожаловать на программу инсталляции Open-Realty.";
$this->lang['install_intro'] = "Перед началом установки необходимо создать пустую базу данных. Также необходимо выставить права доступа к файлам 777 или 775 для следующих файлов или директорий: include/common.php, images/listing_photo, images/user_photos.  После завершения установки верните права файла common.php в 644, для улучшения безопасности. Пожалуйста прочтите <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">руководство к установке</a> для полного понимания.";
$this->lang['install_step_one_header'] = "Шаг 1: Проверка разрешений файлов:";
$this->lang['install_Permission_on'] = "Разрешение в";
$this->lang['install_are_correct'] = "верны";
$this->lang['install_are_incorrect'] = "неверны";
$this->lang['install_All_correct.'] = "Все разрешения верны";
$this->lang['install_continue'] = "Щёлкните мышкой для продолжения";
$this->lang['install_please_fix'] = "Исправьте, пожалйста, разрешения допусков к файлам и зарузите страницу снова, нажав на кнопку Refresh или Обновить";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Выберите тип инсталляции:';
$this->lang['upgrade_115'] = 'Обновление для предыдущей версии Open-Realty 1.1.5';
$this->lang['install_200'] = 'Новая инсталляция  Open-Realty 2.x.x';
$this->lang['move'] = 'Одноаить только информацию о пути и о адресе в интренете';
$this->lang['upgrade_200'] = 'Обновление для версии Open-Realty 2.x.x (2.0.0 Beta 1 или выше)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Установка доступа к БД:";
$this->lang['install_Database_Type'] = "Тип БД:";
$this->lang['install_mySQL'] = "mySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Сервер БД:";
$this->lang['install_Database_Name'] = "Название БД:";
$this->lang['install_Database_User'] = "Пользователь БД:";
$this->lang['install_Database_Password'] = "Пароль для доступа к БД:";
$this->lang['install_Table Prefix'] = "Table Prefix:";
$this->lang['install_Base_URL'] = "Base URL:";
$this->lang['install_Base_Path'] = "Base Path:";
$this->lang['install_Language'] = "Языки";
$this->lang['install_English'] = "английский";
$this->lang['install_Spanish'] = "испанский";
$this->lang['install_Italian'] = "итальянский";
$this->lang['install_French'] = "французский";
$this->lang['install_Portuguese'] = "португальский";
$this->lang['install_Russian'] = "русский";
$this->lang['install_Turkish'] = "турецкий";
$this->lang['install_German'] = "немецкий";
$this->lang['install_Dutch'] = "голландский";
$this->lang['install_Lithuanian'] = "Lithuanian";
$this->lang['install_Arabic'] = "Arabic";
$this->lang['install_Polish'] = "Polish";
$this->lang['install_Czech'] = "Czech";
$this->lang['install_connection_fail'] = "Нет доступа к вашей БД. Проверьте, пожалуйтса, ваши настройки и попробуйте ещё раз";

//Step Three
$this->lang['install_get_old_version'] = 'Определение версии старой Open-Realty';
$this->lang['install_get_old_version_error'] = 'Ошибка при определении старой Open-Realty. Обновление не может быть продолжено';
$this->lang['install_cleared_cache'] = "Сбросить кэш";
$this->lang['install_connection_ok'] = "Доступ к БД установлен.";
$this->lang['install_save_settings'] = "Все ваши настройки будут сейчас сохранены в файле common.php";
$this->lang['install_settings_saved'] = "Настройки БД сохранены.";
$this->lang['install_continue_db_setup'] = "Продолжение установки БД";
$this->lang['install_populate_db'] = "А теперь произойдёт создание БД с вашими настройками";

//finalize installation
$this->lang['install_Installation_complete.'] = "Инсталляция завершена.";
$this->lang['install_configure_installation'] = "Щёлкнете здесь для изменгения настроек вашей интсалляции.";

//2.2.0 additions.
$this->lang['install_devel_mode'] = "Режим инсталляции для программистов. В этом режиме инсталляции будет происходить даже с ошибками. ИНСТАЛЛЯЦИЮ В ЭТОМ РЕЖИМЕ МЫ ВАМ НЕ РЕКОММЕНДУЕМ";
$this->lang['yes'] = "Да";
$this->lang['no'] = "Нет";

?>