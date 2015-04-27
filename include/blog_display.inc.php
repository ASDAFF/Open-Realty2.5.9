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
class blog_display {
	function disply_blog_index(){
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		//Load the Core Template
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$misc = new misc();
		$page = new page_user();
		require_once($config['basepath'] . '/include/blog_functions.inc.php');
		$blog_functions = new blog_functions();
		// Make Sure we passed the PageID
		$display = '';
		//TODO Make limit configurable
		$sql = "SELECT blogmain_full,blogmain_id FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_published = 1 ORDER BY blogmain_date DESC LIMIT 5;";
		$recordSet = $conn->Execute($sql);
		if ($recordSet === false) {
			$misc->log_error($sql);
		}

		$page->load_page($config['template_path'] . '/blog_index.html');
		$blog_entry_template = '';
		while (!$recordSet->EOF) {
			$blog_entry_template .= $page->get_template_section('blog_entry_block');
			//Get Fields
			$id = $recordSet->fields['blogmain_id'];
			$full = html_entity_decode($misc->make_db_unsafe($recordSet->fields['blogmain_full']), ENT_NOQUOTES, $config['charset']);
			//Start Replacing Tags
			$blog_title = $blog_functions->get_blog_title($id);
			$blog_entry_template = $page->parse_template_section($blog_entry_template, 'blog_title', $blog_title);
			$summary_endpos = strpos($full,'<hr');
			if($summary_endpos!==FALSE){
				$summary=substr($full,0,$summary_endpos);
			}else{
				$summary=$full;
			}
			$blog_entry_template = $page->parse_template_section($blog_entry_template, 'blog_summary', $summary);
			$blog_author=$blog_functions->get_blog_author($id);
			$blog_entry_template = $page->parse_template_section($blog_entry_template, 'blog_author', $blog_author);
			$blog_comment_count=$blog_functions->get_blog_comment_count($id);
			$blog_entry_template = $page->parse_template_section($blog_entry_template, 'blog_comment_count', $blog_comment_count);
			$blog_date_posted=$blog_functions->get_blog_date($id);
			$blog_entry_template = $page->parse_template_section($blog_entry_template, 'blog_date_posted', $blog_date_posted);
			if ($config['url_style'] == '1') {
				$article_url = 'index.php?action=blog_view_article&amp;ArticleID=' . $id;
			}else {
				$url_title = str_replace("/", "", $blog_title);
				$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
				$article_url = 'article-' . urlencode($url_title) . '-' . $id . '.html';
			}
			$blog_entry_template = $page->parse_template_section($blog_entry_template, 'blog_link_article', $article_url);

			$recordSet->MoveNext();
		}
		$page->replace_template_section('blog_entry_block', $blog_entry_template);
		$page->replace_permission_tags();
		$display .= $page->return_page();
		return $display;
	}
	function display()
	{
		global $conn, $config, $lang;
		require_once($config['basepath'] . '/include/misc.inc.php');
		$misc = new misc();
		require_once($config['basepath'] . '/include/user.inc.php');
		$userclass=new user();
		require_once($config['basepath'] . '/include/class/template/core.inc.php');
		$page = new page_user();
		require_once($config['basepath'] . '/include/blog_functions.inc.php');
		$blog_functions = new blog_functions();
		// Make Sure we passed the PageID
		$display = '';
		if (!isset($_GET['ArticleID']) && intval($_GET['ArticleID'])<=0) {
			$display .= "ERROR. PageID not sent";
		}else{
			$blog_id = intval($_GET['ArticleID']);
			//Check if we posted a comment.
			if(isset($_SESSION['userID']) && $_SESSION['userID']>0 && isset($_POST['comment_text']) && strlen($_POST['comment_text']) > 0){
				require_once($config['basepath'] . '/include/blog_editor.inc.php');
				$blog_comment = $misc->make_db_safe(blog_editor::htmlEncodeText($_POST['comment_text']));
				if($config['blog_requires_moderation']==1){
					$moderated=0;
				}else{
					$moderated=1;
				}
				$sql = "INSERT INTO " . $config['table_prefix'] . "blogcomments (userdb_id,blogcomments_timestamp,blogcomments_text,blogmain_id,blogcomments_moderated) VALUES
				(".intval($_SESSION['userID']).",".time().",$blog_comment,$blog_id,$moderated);";

				$recordSet = $conn->Execute($sql);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
			}


			//$display .= '<div class="page_display">';
			$sql = "SELECT blogmain_full,blogmain_id FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_id=" . $blog_id;
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$full = html_entity_decode($misc->make_db_unsafe($recordSet->fields['blogmain_full']), ENT_NOQUOTES, $config['charset']); //$full = $misc->make_db_unsafe($recordSet->fields['blogmain_full']);
			$full=preg_replace('/\<hr.*?\>/','',$full,1);
			$id = $recordSet->fields['blogmain_id'];
			if ($config["wysiwyg_execute_php"] == 1) {
				ob_start();
				$full = str_replace("<!--<?php", "<?php", $full);
				$full = str_replace("?>-->", "?>", $full);
				eval('?>' . "$full" . '<?php ');
				$full = ob_get_contents();
				ob_end_clean();
			}

			//Load Template
			$page->load_page($config['template_path'] . '/blog_article.html');
			//Start Replacing Tags
			$blog_title = $blog_functions->get_blog_title($id);
			$page->page = $page->parse_template_section($page->page, 'blog_title', $blog_title);
			$blog_author=$blog_functions->get_blog_author($id);
			$page->page = $page->parse_template_section($page->page, 'blog_author', $blog_author);
			$blog_comment_count=$blog_functions->get_blog_comment_count($id);
			$page->page = $page->parse_template_section($page->page, 'blog_comment_count', $blog_comment_count);
			$blog_date_posted=$blog_functions->get_blog_date($id);
			$page->page = $page->parse_template_section($page->page, 'blog_date_posted', $blog_date_posted);
			$page->page = $page->parse_template_section($page->page, 'blog_full_article', $full);

			// Allow Admin To Edit #
			if ((isset($_SESSION['editblog']) && $_SESSION['admin_privs'] == 'yes') && $config["wysiwyg_show_edit"] == 1) {
				$admin_edit_link .= "$config[baseurl]/admin/index.php?action=edit_blog&amp;id=$id";
				$page->page = $page->parse_template_section($page->page, 'admin_edit_link', $admin_edit_link);
				$page->page = $page->cleanup_template_block('admin_edit_link', $page->page);
			} else {
				$page->page = $page->remove_template_block('admin_edit_link', $page->page);
			}
			//Deal with COmments
			$sql = "SELECT blogcomments_id,userdb_id,blogcomments_timestamp,blogcomments_text FROM " . $config['table_prefix'] . "blogcomments WHERE blogmain_id = ".$id." AND blogcomments_moderated = 1 ORDER BY blogcomments_timestamp ASC;";
			$recordSet = $conn->Execute($sql);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$blog_comment_template = '';
			while (!$recordSet->EOF) {
				//Load DB Values
				$comment_author_id=$misc->make_db_unsafe($recordSet->fields['userdb_id']);
				$blogcomments_id=$misc->make_db_unsafe($recordSet->fields['blogcomments_id']);
				$blogcomments_timestamp=$misc->make_db_unsafe($recordSet->fields['blogcomments_timestamp']);
				$blogcomments_text=html_entity_decode($misc->make_db_unsafe($recordSet->fields['blogcomments_text']), ENT_NOQUOTES, $config['charset']);
				//Load Template Block
				$blog_comment_template .= $page->get_template_section('blog_article_comment_item_block');
				//Lookup Blog Author..
				$author_type=$userclass->get_user_type($comment_author_id);
				if($author_type=='member'){
					$author_display=$userclass->get_user_name($comment_author_id);
				}else{
					$author_display=$userclass->get_user_last_name($comment_author_id).', '.$userclass->get_user_first_name($comment_author_id);
				}
				$blog_comment_template = $page->parse_template_section($blog_comment_template, 'blog_comment_author', $author_display);
				if ($config['date_format']==1) {
					$format="m/d/Y";
				}
				elseif ($config['date_format']==2) {
					$format="Y/d/m";
				}
				elseif ($config['date_format']==3) {
					$format="d/m/Y";
				}
				$blog_comment_date_posted=date($format,"$blogcomments_timestamp");
				$blog_comment_template = $page->parse_template_section($blog_comment_template, 'blog_comment_date_posted', $blog_comment_date_posted);
				$blog_comment_template = $page->parse_template_section($blog_comment_template, 'blog_comment_text', $blogcomments_text);
				$recordSet->MoveNext();
			}
			$page->replace_template_section('blog_article_comment_item_block', $blog_comment_template);

			//Render Add New Comment

			if ($config['url_style'] == '1') {
				$article_url = 'index.php?action=blog_view_article&amp;ArticleID=' . $id;
			}else {
				$url_title = str_replace("/", "", $blog_title);
				$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
				$article_url = 'article-' . urlencode($url_title) . '-' . $id . '.html';
			}
			$page->page = $page->parse_template_section($page->page, 'blog_comments_post_url', $article_url);


			//Render Page Out
			//$page->replace_tags(array('templated_search_form', 'featured_listings_horizontal', 'featured_listings_vertical', 'company_name', 'link_printer_friendly'));

			$page->replace_permission_tags();

			$display .= $page->return_page();
		}
		return $display;
	} // End page_display()
} //End page_display Class

?>