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

// PORTUGUESE (Brazil) LANGUAGE FILE - Translated by Eduardo Marques (ebmarques@gmail.com) and Renato Dias (ocahost@ocahost.com.br)

//error_reporting(E_ALL);
error_reporting(E_ALL& ~E_NOTICE);
global $lang;

$lang['admin_blog_manager'] = 'Gerenciador do Blog';
$lang['blog_edit_post'] = "Editar Blog";
$lang['create_new_blog'] = "Novo Registro de Blog";
$lang['user_manager_limitFeaturedListings'] = "Limite # de Imóveis em Destaque";
$lang['user_manager_displayorder'] = "Ordem de Exibição";
$lang['agent_default_num_featuredlistings'] = 'Número de Imóveis em Destaque';
$lang['agent_default_num_featuredlistings_desc'] = 'Este é o número de Imóveis em Destaque que os corretores podem criar por padrão. (-1 = ilimitado).';
$lang['admin_listings_editor_featuredlistingerror'] = 'Limite de Imóveis em Destaque atingido. Este Imóvel não foi registrado como "em Destaque".';
$lang['blog_meta_description'] = 'Meta Description';
$lang['blog_meta_keywords'] = 'Meta Keywords';
$lang['delete_blog'] = 'Eliminar este Blog';
$lang['blog_read_story'] = 'Ler o restante deste artigo';
$lang['blog_comments'] = 'Comentários';
$lang['blog_post_by'] = 'Artigo criado por';
$lang['blog_comment_by'] = 'Comentado por';
$lang['blog_comment_on'] = 'on';
$lang['blog_must_login'] = 'Você precisa estar logado para registrar um comentário.';
$lang['blog_comment_subject'] = 'Assunto';
$lang['blog_comment_submit'] = 'Submeter Comentário';
$lang['blog_add_comment'] = 'Adicionar Comentário';
$lang['header_pclass'] = 'Classe de Imóvel';
$lang['delete_all_images'] = 'Eliminar todas as imagens';
$lang['confirm_delete_all_images'] = 'Tem certeza que deseja eliminar todas as imagens?';
$lang['admin_addon_manager'] = 'Gerenciador de Add-ons';
$lang['warning_addon_folder_not_writeable'] = 'Seu diretório Add-on não tem permissão de escrita, o gerenciador de Add-on não irá funcionar corretamente.';
$lang['addon_name'] = 'Nome do Add-on';
$lang['addon_version'] = 'Versão do Add-on';
$lang['addon_status'] = 'Status do Add-on';
$lang['addon_actions'] = 'Ações';
$lang['addon_dir_removed'] = 'O diretório do Add-on foi removido sem que o Add-on tenha sido instalado.';
$lang['addon_files_removed'] = 'Os arquivos do Add-on foram removidos sem que o Add-on tenha sido instalado.';
$lang['addon_ok'] = 'Add-on OK';
$lang['addon_check_for_updates'] = 'Verificar atualizações deste Add-on';
$lang['addon_view_docs'] = 'Ver a ajuda do Add-on';
$lang['addon_uninstall'] = 'Remover o Add-on';
$lang['removed_addon'] = 'Add-on Removido';
$lang['addon_does_not_support_updates'] = 'Add-on não possui suporte para atualizações';
$lang['addon_update_server_not_avaliable'] = 'O servidor que atualiza este Add-on não está disponível neste momento';
$lang['addon_update_file_not_avaliable'] = 'O arquivo de atualização deste Add-on não está disponível neste momento';
$lang['addon_already_latest_version'] = 'Você possui a versão mais recente do ';
$lang['addon_update_successful'] = 'Atualizado com sucesso';
$lang['addon_update_failed'] = 'Falha na Atualização';
$lang['addon_manager_ext_help_link'] = 'Ver a documentação externa da ajuda';
$lang['addon_manager_template_tags'] = 'Template Tags';
$lang['addon_manager_action_urls'] = 'Action URLS';
$lang['user_editor_can_manage_addons'] = 'Pode gerenciar Add-ons?';
$lang['user_editor_blog_privileges'] = 'Privilégios para o Blog?';
$lang['blog_perm_subscriber'] = 'Subscritor';
$lang['blog_perm_contributor'] = 'Contribuidor';
$lang['blog_perm_author'] = 'Autor';
$lang['blog_perm_editor'] = 'Editor';
$lang['site_config_heading_signup_settings'] = 'Configurações de Signup';
$lang['signup_image_verification'] = 'Usar imagem de verificação na página de Signup:';
$lang['signup_image_verification_desc'] = 'Incluir uma imagem codificada no formulário de Signup. Os usuários deverão digitar o código para submeter o formulário. É necessário que o servidor tenha a biblioteca GD instalada.';
$lang['signup_verification_code_not_valid'] = 'Você não digitou o código correto.';
$lang['site_email'] = 'Endereço de Email do Website';
$lang['site_email_desc'] = 'Endereço de Email opcional para ser usado como o destinatário dos Emails enviados desde o Website. Se deixado em "branco", o endereço Administrativo será utilizado.';
$lang['admin_send_notices'] = 'Enviar Emails de novos Imóveis';
$lang['send_notices_tool_tip'] = 'Ao selecionar "sim", este imóvel será enviado para todos os usuários que gravaram resultados de busca que coincidem com este imóvel.';
$lang['controlpanel_mbstring_enabled'] = 'MBString está habilitado no servidor?';
$lang['mbstring_enabled_desc'] = 'MBString (MultiByte) está habilitado no servidor? <strong>O ajuste padrão é "não"</strong>. Verifique com sua Empresa de Hospedagem se este ajuste pode ser alterado para "sim" - isto será útil apenas para armazenar na base de dados "caracteres especiais" (com ou sem Editores WYSIWYG instalados).';
$lang['signup_email_verification'] = 'Requer a confirmação do endereço de Email.';
$lang['signup_email_verification_desc'] = 'Caso selecione "sim", será necessário que o usuário confirme seu endereço de Email (será enviada uma mensagem com um link).';
$lang['admin_new_user_email_verification'] = 'Para completar seu registro, deverá verificar sua Caixa Postal - um Email foi enviado para a sua conta. Você deve clicar no link enviado para ativar e completar o seu registro.';
$lang['admin_new_user_email_verification_message'] = 'Para completar seu registro, a autenticidade do seu endereço de Email precisa ser verificado. Por favor clique no link abaixo para que uma mensagem seja enviada para o seu endereço de Email.';
$lang['verify_email_invalid_link'] = 'Você clicou ou digitou em um link inválido.';
$lang['verify_email_thanks'] = 'Muito obrigado por autenticar seu endereço de Email.';
$lang['admin_favorites'] = 'Ver Favoritos';
$lang['admin_saved_search'] = 'Buscas Gravadas';
$lang['blog_deleted'] = 'Artigo de Blog eliminado.';
$lang['delete_index_blog_error'] = 'Você não pode eliminar todo o Blog, mas apenas editá-lo.';
$lang['template_tag_for_blog'] = 'Template tag para o Blog';
$lang['link_to_blog'] = 'Link para este Blog';
$lang['blog_post'] = 'Post';
$lang['blog_author'] = 'Autor';
$lang['blog_keywords'] = 'Keywords';
$lang['blog_date'] = 'Data';
$lang['blog_published'] = 'Publicado';
$lang['blog_draft'] = 'Rascunho';
$lang['blog_review'] = 'Review';
$lang['blog_all'] = 'Todos';
$lang['blog_title'] = 'Título';
$lang['blog_seo'] = 'Otimização para motores de Busca (SEO)';
$lang['blog_publication_status'] = 'Estatus da Publicação';
$lang['listing_editor_permission_denied'] = 'Permissão negada';
$lang['not_authorized'] = 'Não Autorizado';
$lang['blog_comment_delete'] = 'Eliminado';
$lang['blog_comment_approve'] = 'Aprovado';
$lang['site_config_blog'] = 'Blog';
$lang['blog_config'] = 'Configuração do Blog';
$lang['blog_requires_moderation'] = 'Moderar Comentários';
$lang['blog_requires_moderation_desc'] = 'Fazer comentários requer a aprovação do moderador antes de publicar no site?';
$lang['blog_permission_denied'] = 'Permissão Negada';
$lang['email_address_already_used'] = 'O endereço de Email já foi cadastrado por outro usuário.';

?>