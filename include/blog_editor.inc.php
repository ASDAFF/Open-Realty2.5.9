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
global $config;
class blog_editor {
	function blog_edit_index(){
		$security = login::loginCheck('can_access_blog_manager', true);
		$display = '';
		if ($security === true) {
			// include global variables
			global $conn, $lang, $config;
			// Include the misc Class
			require_once($config['basepath'] . '/include/misc.inc.php');
			//Load the Core Template
			require_once($config['basepath'] . '/include/class/template/core.inc.php');
			$misc = new misc();
			$page = new page_user();
			require_once($config['basepath'] . '/include/blog_functions.inc.php');
			$blog_functions = new blog_functions();
			//Load TEmplate File
			$page->load_page($config['admin_template_path'] . '/blog_edit_index.html');
			//What Access Rights does user have to blogs? Access Blog Manager means they are at least a contributor.
			/*//Blog Permissions
			* 1 - Subscriber - A subscriber can read posts, comment on posts.
			* 2 - Contributor - A contributor can post and manage their own post but they cannot publish the posts. An administrator must first approve the post before it can be published.
			* 3 - Author - The Author role allows someone to publish and manage posts. They can only manage their own posts, no one else’s.
			* 4 - Editor - An editor can publish posts. They can also manage and edit other users posts. If you are looking for someone to edit your posts, you would assign the Editor role to that person.
			*/
			$blog_user_type = intval($_SESSION['blog_user_type']);
			$blog_user_id = intval($_SESSION['userID']);

			if ((($config["demo_mode"] == 1) && ($_SESSION['admin_privs'] != 'yes')) || (($blog_user_type == 2) && ($published == 1))) {
				$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
			}else{
				if (isset($_POST['delete'])) {
					if (isset($_POST['blogID']) && $_POST['blogID'] != 0) {
							// Delete blog
							$blogID = intval($_POST['blogID']);
							$sql = "DELETE FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_id = " . $blogID;
							$recordSet = $conn->Execute($sql);
							if (!$recordSet) {
								$misc->log_error($sql);
							}
							$blog_deleted=TRUE;
							$_POST['blogID'] = '';
					}
				}
			}
			//Replace Status Counts
			//{blog_edit_status_all_count}
			if($blog_user_type==4 || $_SESSION['admin_privs']==1){
				$sql = "SELECT count(blogmain_id) as blogcount  FROM " . $config['table_prefix'] . "blogmain";
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$count_all = $recordSet->fields['blogcount'];

				$sql = "SELECT count(blogmain_id) as blogcount  FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_published = 1";
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$count_published = $recordSet->fields['blogcount'];

				$sql = "SELECT count(blogmain_id) as blogcount  FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_published = 0";
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$count_draft = $recordSet->fields['blogcount'];

				$sql = "SELECT count(blogmain_id) as blogcount  FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_published = 2";
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$count_review = $recordSet->fields['blogcount'];
			}else{
				$sql = "SELECT count(blogmain_id) as blogcount  FROM " . $config['table_prefix'] . "blogmain WHERE userdb_id = ".$blog_user_id;
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$count_all = $recordSet->fields['blogcount'];

				$sql = "SELECT count(blogmain_id) as blogcount  FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_published = 1 AND userdb_id = ".$blog_user_id;
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$count_published = $recordSet->fields['blogcount'];

				$sql = "SELECT count(blogmain_id) as blogcount  FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_published = 0 AND userdb_id = ".$blog_user_id;
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$count_draft = $recordSet->fields['blogcount'];
				$sql = "SELECT count(blogmain_id) as blogcount  FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_published = 2 AND userdb_id = ".$blog_user_id;
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$count_review = $recordSet->fields['blogcount'];
			}
			$page->replace_tag('blog_edit_status_all_count', $count_all);
			$page->replace_tag('blog_edit_status_published_count', $count_published);
			$page->replace_tag('blog_edit_status_draft_count', $count_draft);
			$page->replace_tag('blog_edit_status_review_count',$count_review);
			//Get Status
			//http://localhost/open-realty/admin/index.php?action=edit_blog&amp;status=Published
			$statusSQL='';
			if(isset($_GET['status']) && $_GET['status']=='Published'){
				$statusSQL = 'blogmain_published = 1';
			}elseif(isset($_GET['status']) && $_GET['status']=='Draft'){
				$statusSQL = 'blogmain_published = 0';
			}elseif(isset($_GET['status']) && $_GET['status']=='Review'){
				$statusSQL = 'blogmain_published = 2';
			}


			//Show Blog List
			if($blog_user_type==4 || $_SESSION['admin_privs']==1){
				if(!empty($statusSQL)){
					$sql = "SELECT blogmain_title, blogmain_id, userdb_id, blogmain_date, blogmain_published, blogmain_keywords  FROM " . $config['table_prefix'] . "blogmain WHERE ".$statusSQL;
				}else{
					$sql = "SELECT blogmain_title, blogmain_id, userdb_id, blogmain_date, blogmain_published, blogmain_keywords  FROM " . $config['table_prefix'] . "blogmain";
				}
			}else{
				if(!empty($statusSQL)){
					$sql = "SELECT blogmain_title, blogmain_id, userdb_id, blogmain_date, blogmain_published, blogmain_keywords  FROM " . $config['table_prefix'] . "blogmain WHERE userdb_id = ".$blog_user_id." AND ".$statusSQL;
				}else{
					$sql = "SELECT blogmain_title, blogmain_id, userdb_id, blogmain_date, blogmain_published, blogmain_keywords  FROM " . $config['table_prefix'] . "blogmain WHERE userdb_id = ".$blog_user_id;
				}
			}
			//Load Record Set
			$recordSet = $conn->Execute($sql);
			if (!$recordSet) {
				$misc->log_error($sql);
			}
			//Handle Next prev
			$num_rows = $recordSet->RecordCount();
			if (!isset($_GET['cur_page'])) {
				$_GET['cur_page'] = 0;
			}


			$limit_str = $_GET['cur_page'] * $config['listings_per_page'];
			$recordSet = $conn->SelectLimit($sql, $config['listings_per_page'], $limit_str);
			if ($recordSet === false) {
				$misc->log_error($sql);
			}
			$blog_edit_template = '';

			while(!$recordSet->EOF()){
				$blog_edit_template .= $page->get_template_section('blog_edit_item_block');
				//echo $blog_edit_template;
				$title = $recordSet->fields['blogmain_title'];
				$blogmain_id = $recordSet->fields['blogmain_id'];
				$author_id = $recordSet->fields['userdb_id'];
				$keywords = $recordSet->fields['blogmain_keywords'];
				$blog_date = $recordSet->fields['blogmain_date'];
				$blog_published = $recordSet->fields['blogmain_published'];
				$comment_count = $blog_functions->get_blog_comment_count($blogmain_id);
				//Get Author
				require_once($config['basepath'] . '/include/user.inc.php');
				$user = new user();
				$author_name = $user->get_user_last_name($author_id).', '.$user->get_user_first_name($author_id);

				$blog_edit_template = $page->parse_template_section($blog_edit_template, 'blog_edit_item_title', $title);
				$blog_edit_template = $page->parse_template_section($blog_edit_template, 'blog_edit_item_id', $blogmain_id);
				$blog_edit_template = $page->parse_template_section($blog_edit_template, 'blog_edit_item_commentcount', $comment_count);
				/*<td>{blog_edit_item_author}</td>
				 <td>{blog_edit_item_keywords}</td>
				 <td>{blog_edit_item_commentcount}</td>
				 <td>{blog_edit_item_date}</td>
				 */
				$blog_edit_template = $page->parse_template_section($blog_edit_template, 'blog_edit_item_author', $author_name);
				$blog_edit_template = $page->parse_template_section($blog_edit_template, 'blog_edit_item_date', $blog_date);
				$blog_edit_template = $page->parse_template_section($blog_edit_template, 'blog_edit_item_keywords', $keywords);
				switch($blog_published){
					case 0:
						$blog_edit_template = $page->parse_template_section($blog_edit_template, 'blog_edit_item_published', $lang['blog_draft']);
						break;
					case 1:
						$blog_edit_template = $page->parse_template_section($blog_edit_template, 'blog_edit_item_published', $lang['blog_published']);

						break;
					case 2:
						$blog_edit_template = $page->parse_template_section($blog_edit_template, 'blog_edit_item_published', $lang['blog_review']);

						break;
				}

				$recordSet->MoveNext();
			}
			/*
			 * td>{blog_edit_item_title}</td>
			 <td>{blog_edit_item_author}</td>
			 <td>{blog_edit_item_keywords}</td>
			 <td>{blog_edit_item_commentcount}</td>
			 <td>{blog_edit_item_date}</td>
			 */


		}
		$page->replace_template_section('blog_edit_item_block', $blog_edit_template);
		//Next Prev
		$next_prev = $misc->next_prev($num_rows, $_GET['cur_page'], "",'blog',TRUE);
		$page->replace_tag('next_prev', $next_prev);
		$page->replace_permission_tags();
		$page->auto_replace_tags('', true);
		$display .= $page->return_page();

		return $display;
	}

	/**
	 * **************************************************************************\
	 * function blog_edit() - Display's the blog editor							*
	 * \**************************************************************************
	 */
	function blog_edit()
	{
		global $conn, $lang, $config;
		$security = login::loginCheck('can_access_blog_manager', true);
		$display = '';
		$blog_saved=FALSE;
		$blog_deleted=FALSE;
		if ($security === true) {
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			//Load the Core Template
			require_once($config['basepath'] . '/include/class/template/core.inc.php');
			$page = new page_user();
			//Load TEmplate File
			$page->load_page($config['admin_template_path'] . '/blog_edit_post.html');
			$blog_user_type = intval($_SESSION['blog_user_type']);
			$blog_user_id = intval($_SESSION['userID']);
			// Do we need to save?
			if (isset($_POST['edit'])) {
				// Save blog now
				$blogID = intval($_POST['blogID']);
				$save_full = $_POST['ta'];
				$save_title = $misc->make_db_safe($_POST['title']);
				$save_full_xhtml =  $misc->make_db_safe(blog_editor::htmlEncodeText($save_full),TRUE);
				$save_description = $misc->make_db_safe($_POST['description']);
				$save_keywords = $misc->make_db_safe($_POST['keywords']);
				$save_published = intval($_POST['published']);
				if ($blog_user_type==2 && $save_published == 1){
					//Throw Error
					$display .= '<div class="error_message">'.$lang['blog_permission_denied'].'</div><br />';
					unset($_POST['edit']);
					$display .= $this->blog_edit();
					return $display;
				}
				$sql = "UPDATE " . $config['table_prefix'] . "blogmain SET blogmain_full = " . $save_full_xhtml . ", blogmain_title = " . $save_title . ", blogmain_description = " . $save_description . ", blogmain_keywords = " . $save_keywords . ", blogmain_published = ".$save_published." WHERE blogmain_id = " . $blogID . "";
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$blog_saved=TRUE;
				$display .= "<center><b>$lang[blog_saved]</b></center><br />";
			}



			$html = '';
			if (isset($_GET['id'])) {
				$_POST['blogID'] = $_GET['id'];
			}
			if (isset($_POST['blogID'])) {
				if ($_POST['blogID'] != '') {
					// Save blogID to Session for Image Upload Plugin
					$_SESSION['blogID'] = $_POST['blogID'];
					$blogID = intval($_POST['blogID']);
					// Pull the blog from the database
					$page->replace_tag('blog_id', $blogID);
					$sql = "SELECT blogmain_full, blogmain_title, blogmain_description, blogmain_keywords,blogmain_published  FROM " . $config['table_prefix'] . "blogmain WHERE blogmain_id = " . $blogID;
					$recordSet = $conn->Execute($sql);
					if (!$recordSet) {
						$misc->log_error($sql);
					}
					if ($config["controlpanel_mbstring_enabled"] == 0) {
						// MBSTRING NOT ENABLED
						$html = htmlentities($misc->make_db_unsafe($recordSet->fields['blogmain_full']));
					} else {
						// MBSTRING ENABLED
						$html = mb_convert_encoding($misc->make_db_unsafe($recordSet->fields['blogmain_full']), 'HTML-ENTITIES', $config['charset']);
					}
					$page->replace_tag('blog_html', $html);
					$title = $misc->make_db_unsafe($recordSet->fields['blogmain_title']);
					$description = $misc->make_db_unsafe($recordSet->fields['blogmain_description']);
					$published = intval($recordSet->fields['blogmain_published']);
					if ($blog_user_type==2 && $published == 1){
						//User can not save published, so reset to review.
						$published=2;
					}
					$keywords = $misc->make_db_unsafe($recordSet->fields['blogmain_keywords']);
					$page->replace_tag('blog_title', $title);
					$page->replace_tag('blog_description', $description);
					$page->replace_tag('blog_keywords', $keywords);
					$page->replace_tag('baseurl',$config['baseurl']);
					//Handle Publish Status
					$page->replace_tag('blog_published', $published);
					switch($published){
						case 0:
							$page->replace_tag('blog_published_lang', $lang['blog_draft']);
							break;
						case 1:
							$page->replace_tag('blog_published_lang', $lang['blog_published']);
							break;
						case 2:
							$page->replace_tag('blog_published_lang', $lang['blog_review']);
							break;
					}
					/*//Blog Permissions
					 * 1 - Subscriber - A subscriber can read posts, comment on posts.
					 * 2 - Contributor - A contributor can post and manage their own post but they cannot publish the posts. An administrator must first approve the post before it can be published.
					 * 3 - Author - The Author role allows someone to publish and manage posts. They can only manage their own posts, no one else’s.
					 * 4 - Editor - An editor can publish posts. They can also manage and edit other users posts. If you are looking for someone to edit your posts, you would assign the Editor role to that person.
					 */
					if($blog_user_type == 2){
						$page->page = $page->remove_template_block('blog_published',$page->page);
					}
					//$blog_user_type

					//blog_published_lang
					if ($config['url_style'] == '1') {
						$article_url = 'index.php?action=blog_view_article&amp;ArticleID=' . $_POST['blogID'];
					}else {
						$url_title = str_replace("/", "", $title);
						$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
						$article_url = 'article-' . urlencode($url_title) . '-' . $_POST['blogID'] . '.html';
					}
					$page->replace_tag('blog_article_url', $article_url);

				}
			}
			//Show Link to Blog Manager
			$page->replace_tag('blog_manager_url', 'index.php?action=edit_blog');
			$page->replace_tag('blog_edit_action','index.php?action=edit_blog_post');

			if ((($config["demo_mode"] == 1) && ($_SESSION['admin_privs'] != 'yes')) || (($blog_user_type == 2) && ($published == 1))) {
				$page->page = $page->remove_template_block('blog_save',$page->page);
				$page->page = $page->remove_template_block('blog_delete',$page->page);
			}else{
				$page->page = $page->cleanup_template_block('blog_save',$page->page);
				$page->page = $page->cleanup_template_block('blog_delete',$page->page);
			}
			$page->replace_permission_tags();
			$page->auto_replace_tags('', true);
			$display .= $page->return_page();

		}else {
			$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
		return $display;
	}
	function add_post()
	{
		global $conn, $lang, $config;
		$security = login::loginCheck('can_access_blog_manager', true);
		$display = '';
		$blog_saved=FALSE;
		$blog_deleted=FALSE;
		$blog_user_type = intval($_SESSION['blog_user_type']);
		if ($security === true) {
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			//Load the Core Template
			require_once($config['basepath'] . '/include/class/template/core.inc.php');
			$page = new page_user();
			//Load TEmplate File
			$page->load_page($config['admin_template_path'] . '/blog_edit_post.html');
			// Do we need to save?
			if (isset($_POST['edit'])) {
				// Save blog now
				$save_full = $_POST['ta'];
				$save_title = $misc->make_db_safe($_POST['title']);
				$save_full_xhtml =  $misc->make_db_safe(blog_editor::htmlEncodeText($save_full),TRUE);
				$save_description = $misc->make_db_safe($_POST['description']);
				$save_keywords = $misc->make_db_safe($_POST['keywords']);
				$save_published = intval($_POST['published']);
				if ($blog_user_type==2 && $save_published == 1){
					//Throw Error
					$display .= '<div class="error_message">'.$lang['blog_permission_denied'].'</div><br />';
					unset($_POST['edit']);
					$display .= $this->add_post();
					return $display;
				}
				$userdb_id = $misc->make_db_safe($_SESSION['userID']);
				$sql = "INSERT INTO " . $config['table_prefix'] . "blogmain (userdb_id,blogmain_full,blogmain_title,blogmain_date,blogmain_published,blogmain_description,blogmain_keywords) VALUES ($userdb_id,$save_full_xhtml,$save_title," . $conn->DBDate(time()) . ",$save_published,$save_description,$save_keywords)";
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				$display .= "<center><b>$lang[blog_saved]</b></center><br />";
				unset($_POST['edit']);
				$_POST['blogID'] = $conn->Insert_ID();
				$display .= $this->blog_edit();
				return $display;


			}

			// Pull the blog from the database
			$page->replace_tag('', $blogID);
			$page->replace_tag('blog_html', '');

			$page->replace_tag('blog_edit_action','index.php?action=add_blog');
			$title = $misc->make_db_unsafe($recordSet->fields['blogmain_title']);
			$description = $misc->make_db_unsafe($recordSet->fields['blogmain_description']);
			$published = intval($recordSet->fields['blogmain_published']);
			$keywords = $misc->make_db_unsafe($recordSet->fields['blogmain_keywords']);
			$page->replace_tag('blog_title', '');
			$page->replace_tag('blog_description', '');
			$page->replace_tag('blog_keywords', '');
			//Handle Publish Status
			$page->replace_tag('blog_published', 0);
			switch($published){
				case 0:
					$page->replace_tag('blog_published_lang', $lang['blog_draft']);
					break;
				case 1:
					$page->replace_tag('blog_published_lang', $lang['blog_published']);
					break;
				case 2:
					$page->replace_tag('blog_published_lang', $lang['blog_review']);
					break;
			}
			/*//Blog Permissions
			 * 1 - Subscriber - A subscriber can read posts, comment on posts.
			 * 2 - Contributor - A contributor can post and manage their own post but they cannot publish the posts. An administrator must first approve the post before it can be published.
			 * 3 - Author - The Author role allows someone to publish and manage posts. They can only manage their own posts, no one else’s.
			 * 4 - Editor - An editor can publish posts. They can also manage and edit other users posts. If you are looking for someone to edit your posts, you would assign the Editor role to that person.
			 */
			if($blog_user_type == 2){
				$page->page = $page->remove_template_block('blog_published',$page->page);
			}
			//$blog_user_type

			//blog_published_lang
			if ($config['url_style'] == '1') {
				$article_url = 'index.php?action=blog_view_article&amp;ArticleID=' . $_POST['blogID'];
			}else {
				$url_title = str_replace("/", "", $title);
				$url_title = strtolower(str_replace(" ", $config['seo_url_seperator'], $url_title));
				$article_url = 'article-' . urlencode($url_title) . '-' . $_POST['blogID'] . '.html';
			}
			$page->replace_tag('blog_article_url', '');


			//Show Link to Blog Manager
			$page->replace_tag('blog_manager_url', 'index.php?action=edit_blog');

			//Remove Delete Post option, as it does  not yet exist
			$page->page = $page->remove_template_block('blog_delete',$page->page);
			if ((($config["demo_mode"] == 1) && ($_SESSION['admin_privs'] != 'yes')) || (($blog_user_type == 2) && ($published == 1))) {
				$page->page = $page->remove_template_block('blog_save',$page->page);
			}else{
				$page->page = $page->cleanup_template_block('blog_save',$page->page);
			}
			$page->replace_permission_tags();
			$page->auto_replace_tags('', true);
			$display .= $page->return_page();

		} else {
			$display .= '<div class="error_text">' . $lang['access_denied'] . '</div>';
		}
		return $display;
	}
	function edit_post_comments(){
		global $conn, $lang, $config;

		$security = login::loginCheck('can_access_blog_manager', true);
		$display = '';
		$blog_user_type = intval($_SESSION['blog_user_type']);
		if ($security === true) {
			require_once($config['basepath'] . '/include/misc.inc.php');
			$misc = new misc();
			//Load the Core Template
			require_once($config['basepath'] . '/include/class/template/core.inc.php');
			$page = new page_user();
			require_once($config['basepath'] . '/include/user.inc.php');
			$userclass=new user();
			require_once($config['basepath'] . '/include/blog_functions.inc.php');
			$blog_functions = new blog_functions();
			//Load TEmplate File
			$page->load_page($config['admin_template_path'] . '/blog_edit_comments.html');
			// Do we need to save?
			if (isset($_GET['id'])) {
				$post_id=intval($_GET['id']);
				//Get Blog Post Information
				$blog_title = $blog_functions->get_blog_title($post_id);
				$page->page = $page->parse_template_section($page->page, 'blog_title', $blog_title);
				$blog_author=$blog_functions->get_blog_author($post_id);
				$page->page = $page->parse_template_section($page->page, 'blog_author', $blog_author);
				$blog_date_posted=$blog_functions->get_blog_date($post_id);
				$page->page = $page->parse_template_section($page->page, 'blog_date_posted', $blog_date_posted);
//Handle any deletions and comment approvals before we load the comments
				if(isset($_GET['caction']) && $_GET['caction'] == 'delete'){
					if(isset($_GET['cid'])){
						$cid = intval($_GET['cid']);
						//Do permission checks.
						if ($blog_user_type < 4){
							//Throw Error
							$display .= '<div class="error_message">'.$lang['blog_permission_denied'].'</div><br />';
							unset($_GET['caction']);
							$display .= $this->edit_post_comments();
							return $display;
						}
						//Delete
						$sql = 'DELETE FROM ' . $config['table_prefix'] . 'blogcomments WHERE blogcomments_id = '.$cid .' AND blogmain_id = '.$post_id;
						//Load Record Set
						$recordSet = $conn->Execute($sql);
						if (!$recordSet) {
							$misc->log_error($sql);
						}
					}
				}
				if(isset($_GET['caction']) && $_GET['caction'] == 'approve'){
					if(isset($_GET['cid'])){
						$cid = intval($_GET['cid']);
						//Do permission checks.
						if ($blog_user_type < 4){
							//Throw Error
							$display .= '<div class="error_message">'.$lang['blog_permission_denied'].'</div><br />';
							unset($_GET['caction']);
							$display .= $this->edit_post_comments();
							return $display;
						}
						//Delete
						$sql = 'UPDATE ' . $config['table_prefix'] . 'blogcomments SET blogcomments_moderated = 1 WHERE blogcomments_id = '.$cid .' AND blogmain_id = '.$post_id;
						//Load Record Set
						$recordSet = $conn->Execute($sql);
						if (!$recordSet) {
							$misc->log_error($sql);
						}
					}
				}

				//Ok Load the comments.
				$sql = 'SELECT * FROM ' . $config['table_prefix'] . 'blogcomments WHERE blogmain_id = '.$post_id.' ORDER BY blogcomments_timestamp ASC';
				//Load Record Set
				$recordSet = $conn->Execute($sql);
				if (!$recordSet) {
					$misc->log_error($sql);
				}
				//Handle Next prev
				$num_rows = $recordSet->RecordCount();
				if (!isset($_GET['cur_page'])) {
					$_GET['cur_page'] = 0;
				}


				$limit_str = $_GET['cur_page'] * $config['listings_per_page'];
				$recordSet = $conn->SelectLimit($sql, $config['listings_per_page'], $limit_str);
				if ($recordSet === false) {
					$misc->log_error($sql);
				}
				$blog_comment_template = '';
				while (!$recordSet->EOF) {
					//Load DB Values
					$comment_author_id=$misc->make_db_unsafe($recordSet->fields['userdb_id']);
					$blogcomments_id=$misc->make_db_unsafe($recordSet->fields['blogcomments_id']);
					$blogcomments_moderated=$misc->make_db_unsafe($recordSet->fields['blogcomments_moderated']);
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
					//Add Delete COmment Link
					//{blog_comment_delete_url}
					$blog_comment_delete_url = 'index.php?action=edit_blog_post_comments&id='.$post_id.'&caction=delete&cid='.$blogcomments_id;
					$blog_comment_template = $page->parse_template_section($blog_comment_template, 'blog_comment_delete_url', $blog_comment_delete_url);
					$blog_comment_approve_url = 'index.php?action=edit_blog_post_comments&id='.$post_id.'&caction=approve&cid='.$blogcomments_id;
					$blog_comment_template = $page->parse_template_section($blog_comment_template, 'blog_comment_approve_url', $blog_comment_approve_url);
					//Do Security Checks
					if($blog_user_type<4){
						$blog_comment_template = $page->remove_template_block('blog_article_comment_approve',$blog_comment_template);
						$blog_comment_template = $page->remove_template_block('blog_article_comment_delete',$blog_comment_template);
					}
					//Handle Moderation
					if($blogcomments_moderated==1){
						$blog_comment_template = $page->remove_template_block('blog_article_comment_approve',$blog_comment_template);
					}else{
						$blog_comment_template = $page->cleanup_template_block('blog_article_comment_approve',$blog_comment_template);
					}

					$recordSet->MoveNext();
				}
				$page->replace_template_section('blog_article_comment_item_block', $blog_comment_template);
				$next_prev = $misc->next_prev($num_rows, $_GET['cur_page'], "",'blog',TRUE);
				$page->replace_tag('next_prev', $next_prev);
				$page->replace_permission_tags();
				$page->auto_replace_tags('', true);
				$display .= $page->return_page();
			}
		}
		return $display;
	}
	function htmlEncodeText($string)
	{
		global $config;
		$pattern = '<([a-zA-Z0-9\.\, "\'_\/\-\+~=;:\(\)?&#%![\]@]+)>';
		preg_match_all('/' . $pattern . '/', $string, $tagMatches, PREG_SET_ORDER);
		$textMatches = preg_split('/' . $pattern . '/', $string);
		foreach($textMatches as $key => $value) {
			$textMatches [$key] = htmlentities($value, ENT_NOQUOTES, $config['charset']);
		}
		for($i = 0; $i < count ($textMatches); $i ++) {
			$textMatches [$i] = $textMatches [$i] . $tagMatches [$i] [0];
		}
		return implode($textMatches);
	}
}

?>