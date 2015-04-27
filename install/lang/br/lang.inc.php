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

// PORTUGUESE (Brazil) LANGUAGE FILE - Translated by Renato Dias (ocahost@ocahost.com.br)

//Step 1: Check File Permissions:
$this->lang['install_Page_Title'] = "Instalação do Open-Realty";
$this->lang['install_version_warn'] = "A sua versão do PHP não está preparada para esta versão do Open-Realty. A Instalação foi cancelada.";
$this->lang['install_sqlversion_warn'] = "A sua versão do MySQL não está preparada para esta versão do Open-Realty. A Instalação foi cancelada.";
$this->lang['install_php_version'] = "Sua versão atual do PHP é ";
$this->lang['install_sql_version'] = "Sua versão atual do MySql é ";
$this->lang['install_php_required'] = "Esta versão do Open-Realty requer no mínimo a versão do PHP ";
$this->lang['install_sql_required'] = "Esta versão do Open-Realty requer no mínimo a versão do MySql ";
$this->lang['install_welcome'] = "Bem-vindo à ferramenta de instalação do Open-Realty.";
$this->lang['install_intro'] = "Esta ferramenta irá guiá-lo através da instalação do Open-Realty&reg;. Antes de começar, deverá ser criado uma Base de Dados em branco no seu sistema. Deverá também configurar as permissões dos arquivos para 777 ou 755, dependendo da configuração do seu servidor nos seguintes arquivos ou diretórios: include/common.php, images/listing_photo e images/user_photos. Após a instalação do open-realty, deverá configurar novamente o common.php para 644, por razões de segurança.  Por favor leia o <a href=\"http://wiki.open-realty.org/index.php/Install_guide\">Manual de Instalação</a> para conhecer todos os detalhes.";
$this->lang['install_step_one_header'] = "Passo 1: Verificar as permissões dos arquivos:";
$this->lang['install_Permission_on'] = "Permissão em";
$this->lang['install_are_correct'] = "estão corretas";
$this->lang['install_are_incorrect'] = "estão incorretas";
$this->lang['install_all_correct'] = "Todas as permissões estão corretas.";
$this->lang['install_continue'] = "Clique para continuar a instalação";
$this->lang['install_please_fix'] = "Por favor corrija as permissões dos arquivos e depois atualize esta página.";

//Step 1: Determine Install Type
$this->lang['install_select_type'] = 'Selecione o tipo de instalação:';
$this->lang['upgrade_115'] = 'Atualizar do Open-Realty v.1.1.5';
$this->lang['install_200'] = 'Nova instalação do Open-Realty v.2.x.x';
$this->lang['move'] = 'Atualizar apenas (PATCH) Caminho da URL';
$this->lang['upgrade_200'] = 'Atualizar do Open-Realty v.2.x.x (2.0.0 Beta 1 ou acima)';

//Step 2: Setup Database Connection:
$this->lang['install_setup__database_settings'] = "Configuração da Conexão com a Base de Dados:";
$this->lang['install_Database_Type'] = "Tipo de Base de Dados:";
$this->lang['install_mySQL'] = "MySQL";
$this->lang['install_PostgreSQL'] = "PostgreSQL";
$this->lang['install_Database_Server'] = "Servidor de Base de Dados:";
$this->lang['install_Database_Name'] = "Nome da Base de Dados:";
$this->lang['install_Database_User'] = "Usuário da Base de Dados:";
$this->lang['install_Database_Password'] = "Senha da Base de Dados:";
$this->lang['install_Table Prefix'] = "Prefixo das Tabelas:";
$this->lang['install_Base_URL'] = "URL Base:";
$this->lang['install_Base_Path'] = "Caminho Base:";
$this->lang['install_Language'] = "Idioma:";
$this->lang['install_English'] = "Inglês";
$this->lang['install_Spanish'] = "Espanhol";
$this->lang['install_Italian'] = "Italiano";
$this->lang['install_French'] = "Francês";
$this->lang['install_Portuguese'] = "Português";
$this->lang['install_Russian'] = "Russo";
$this->lang['install_Turkish'] = "Turco";
$this->lang['install_German'] = "Alemão";
$this->lang['install_Dutch'] = "Holandês";
$this->lang['install_Lithuanian'] = "Lituano";
$this->lang['install_Arabic'] = "Árabe";
$this->lang['install_Polish'] = "Polonês";
$this->lang['install_Czech'] = "Checo";
$this->lang['install_connection_fail'] = "Não foi possível conetar à sua Base de Dados. Por favor verifique as suas configurações e tente novamente.";

//Step Three
$this->lang['install_get_old_version'] = 'Determinar versão antiga do Open-Realty';
$this->lang['install_get_old_version_error'] = 'Êrro ao determinar a versão antiga do Open-Realty. A atualização não pôde continuar.';
$this->lang['install_cleared_cache'] = "O cache foi limpo";
$this->lang['install_connection_ok'] = "Conexão à Base de Dados efetuada com sucesso.";
$this->lang['install_save_settings'] = "Estamos gravando seus dados no arquivo common.php";
$this->lang['install_settings_saved'] = "Configuração da Base de Dados gravada e salva com sucesso.";
$this->lang['install_continue_db_setup'] = "Continuar com a configuração da Base de Dados.";
$this->lang['install_populate_db'] = "Vamos agora gravar em sua Base de Dados.";

//finalize installation
$this->lang['install_installation_complete'] = "A Instalação está completa.";
$this->lang['install_configure_installation'] = "Clique aqui para configurar a sua instalação";

//2.2.0 additions.
$this->lang['install_devel_mode'] = 'Instalação Modo Programador - Neste modo poderá continuar a instalação mesmo com êrros. NÃO É RECOMENDADO.';
$this->lang['yes'] = "Sim";
$this->lang['no'] = "Não";

?>