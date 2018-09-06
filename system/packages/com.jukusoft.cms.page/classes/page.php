<?php

/**
 * Copyright (c) 2018 Justin Kuenzel (jukusoft.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class Page {

	protected $pageID = -1;
	protected $alias = null;
	protected $row = null;
	protected $pagetype = "";

	protected $author = null;

	//changed columns to save with save()
	protected $changes = array();

	public function __construct() {
		//
	}

	public function load ($alias = null) {
		if ($alias == null) {
			if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
				$alias = Validator_String::get($_REQUEST['page']);
			} else {
				$alias = $this->getDomain()->getHomePage();
			}
		} else {
			//$alias = Database::getInstance()->escape($alias);
		}

		Events::throwEvent("get_alias", array(
			'alias' => &$alias,
			'page' => &$this,
			'domain' => $this->getDomain()
		));

		$this->alias = $alias;

		if (Cache::contains("pages", "page_" . $alias)) {
			$this->row = Cache::get("pages", "page_" . $alias);
		} else {
			$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}pages` WHERE `alias` = :alias AND `activated` = '1'; ", array('alias' => $alias));

			if (!$row) {
				if (PHPUtils::strEqs("error404", $alias)) {
					throw new IllegalStateException("No page with alias 'error404' exists (requested alias: " . $alias . ").");
				}

				//page not found
				$new_alias = "error404";

				Events::throwEvent("load_page_error404", array(
					'alias' => &$new_alias,
					'original_alias' => $alias,
					'page' => &$this,
					'domain' => $this->getDomain()
				));

				$this->load($new_alias);
				return null;
			}

			$this->row = $row;

			//cache result
			Cache::put("pages", "page_" . $alias, $row);
		}

		//get pageID
		$this->pageID = $this->row['id'];

		//get name of page type (class name)
		$this->pagetype = $this->row['page_type'];
	}

	public function loadByID (int $pageID, bool $use_cache = true) {
		if ($use_cache && Cache::contains("pages", "pageID_" . $pageID)) {
			$this->row = Cache::get("pages", "pageID_" . $pageID);
		} else {
			$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}pages` WHERE `id` = :pageID; ", array('pageID' => $pageID));

			if (!$row) {
				throw new IllegalStateException("Page with pageID " . $pageID . " doesnt exists!");
			}

			$this->row = $row;

			//cache result
			Cache::put("pages", "pageID_" . $pageID, $row);
		}

		$this->pageID = $this->row['id'];
		$this->alias = $this->row['alias'];

		//get name of page type (class name)
		$this->pagetype = $this->row['page_type'];
	}

	protected function getDomain () : Domain {
		return Registry::singleton()->getObject("domain");
	}

	public function reloadCache () {
		Cache::clear("pages");
	}

	public function getPageID () : int {
		return $this->row['id'];
	}

	public function getAlias () : string {
		return $this->alias;
	}

	public function getPageType () : string {
		return $this->pagetype;
	}

	public function getTitle () : string {
		return $this->row['title'];
	}

	public function setTitle (string $title) {
		$this->row['title'] = $title;
		$this->changes[] = "title";
	}

	public function getContent () : string {
		return $this->row['content'];
	}

	public function setContent (string $content) {
		$this->row['content'] = $content;
		$this->changes[] = "content";
		$this->changes[] = "content";
	}

	public function getGlobalMenuID () : int {
		return $this->row['global_menu'];
	}

	public function getLocalMenuID () : int {
		return $this->row['local_menu'];
	}

	public function getStyle () : string {
		return $this->row['design'];
	}

	public function getFolder () : string {
		return $this->row['folder'];
	}

	public function getLastEdit () {
		return $this->row['lastUpdate'];
	}

	public function hasCustomTemplate () : bool {
		return $this->row['template'] !== "none";
	}

	public function getCustomTemplate () : string {
		return $this->row['template'];
	}

	public function hasCustomPermissions () : bool {
		return $this->row['can_see_permissions'] !== "none";
	}

	public function listCustomPermissions () : array {
		return explode("|", $this->row['can_see_permissions']);
	}

	public function isPublished () : bool {
		return $this->row['published'] == 1;
	}

	public function publish () {
		$this->row['published'] = 1;
		$this->changes[] = "published";
	}

	public function getContentType () : string {
		return $this->row['content_type'];
	}

	public function getLeftSidebarID () : int {
		return $this->row['sidebar_left'];
	}

	public function getRightSidebarID () : int {
		return $this->row['sidebar_right'];
	}

	public function getMetaDescription () : string {
		return $this->row['meta_description'];
	}

	public function getMetaKeywords () : string {
		return $this->row['meta_keywords'];
	}

	public function getMetaRobotsOptions () : string {
		return $this->row['meta_robots'];
	}

	public function getMetaCanonicals () : string {
		return $this->row['meta_canonicals'];
	}

	public function getAuthorID () : int {
		return $this->row['author'];
	}

	public function getAuthor () : User {
		if ($this->author == null) {
			//load author
			$this->author = new User();

			if ($this->getAuthorID() <= 0) {
				throw new IllegalArgumentException("authorID has to be > 0.");
			}

			$this->author->load($this->getAuthorID());
		}

		return $this->author;
	}

	public function activate (bool $bool = true) {
		$this->row['activated'] = ($bool ? 1 : 0);
	}

	public function isTrash () : bool {
		return $this->row['activated'] == 2;
	}

	public function isEditable () : bool {
		return $this->row['editable'] == 1;
	}

	public function isDeletable () : bool {
		return $this->row['deletable'] == 1;
	}

	public function isActivated () : bool {
		return $this->row['activated'] == 1;
	}

	public function moveToTrash () {
		self::movePageToTrash($this->pageID);

		//clear cache
		$this->clearCache();
	}

	/**
	 * restore page from trash
	 */
	public function restore () {
		self::restorePage($this->pageID);

		//clear cache
		$this->clearCache();
	}

	/**
	 * save changes into database
	 */
	public function save () {
		//TODO: add code here
	}

	public function clearCache () {
		if (!is_int($this->getPageID())) {
			throw new IllegalStateException("pageID isn't set.");
		}

		//clear cache
		Cache::clear("pages", "pageID_" . $this->getPageID());
		Cache::clear("pages", "page_" . $this->getAlias());
	}

	public static function createIfAbsent (string $alias, string $title, string $page_type, string $content = "", string $folder = "/", int $globalMenu = -1, int $localMenu = -1, int $parentID = -1, bool $sitemap = true, bool $published = true, bool $editable = true, bool $deletable = true, string $author = "system") : int {
		//throw event
		Events::throwEvent("create_page", array(
			'alias' => &$alias,
			'title' => &$title,
			'page_type' => &$page_type,
			'content' => &$content,
			'folder' => &$folder,
			'global_menu' => &$globalMenu,
			'local_menu' => &$localMenu,
			'parentID' => &$parentID,
			'sitemap' => &$sitemap,
			'published' => &$published,
			'editable' => &$editable,
			'author' => &$author
		));

		if (!is_int($author)) {
			//get userID of author
			$author = User::getIDByUsernameFromDB($author);

			if ($author == -1) {
				//username doesnt exists, so choose first user
				$author = 1;
			}
		} else {
			$author = (int) $author;
		}

		Database::getInstance()->execute("INSERT INTO `{praefix}pages` (
			`id`, `alias`, `title`, `content`, `parent`, `folder`, `global_menu`, `local_menu`, `page_type`, `design`, `sitemap`, `published`, `version`, `last_update`, `created`, `editable`, `deletable`, `author`, `activated`
		) VALUES (
			NULL, :alias, :title, :content, :parent, :folder, :globalMenu, :localMenu, :pageType, 'none', :sitemap, :published, '1', '0000-00-00 00:00:00', CURRENT_TIMESTAMP, :editable, :deletable, :author, '1'
		) ON DUPLICATE KEY UPDATE `alias` = :alias, `editable` = :editable, `deletable` = :deletable; ", array(
			'alias' => $alias,
			'title' => $title,
			'content' => $content,
			'parent' => $parentID,
			'folder' => $folder,
			'globalMenu' => $globalMenu,
			'localMenu' => $localMenu,
			'pageType' => $page_type,
			'sitemap' => ($sitemap ? 1 : 0),
			'published' => ($published ? 1 : 0),
			'editable' => ($editable ? 1 : 0),
			'deletable' => ($deletable ? 1 : 0),
			'author' => $author
		));

		Cache::clear("pages");

		//return page id
		$insertID = Database::getInstance()->lastInsertId();

		//throw event
		Events::throwEvent("created_page", array(
			'alias' => $alias,
			'title' => $title,
			'insertID' => $insertID
		));

		//get pageID by alias
		$pageID = Page::getPageIDByAlias($alias);

		//set default rights, allow page for administrators, registered users, guests and bots
		PageRights::setDefaultAllowedGroups($pageID, array(1, 2, 3, 4));

		return $pageID;
	}

	public static function delete (string $alias) {
		$delete = true;

		//plugins can avoid deletion or change alias
		Events::throwEvent("delete_page_alias", array(
			'alias' => &$alias,
			'delete' => &$delete
		));

		if ($delete) {
			//remove page from database
			Database::getInstance()->execute("DELETE FROM `{praefix}pages` WHERE `alias` = :alias; ", array('alias' => $alias));

			Cache::clear("pages");
		}
	}

	public static function deleteByID (int $id) {
		$delete = true;

		//plugins can avoid deletion or change alias
		Events::throwEvent("delete_page_id", array(
			'alias' => &$id,
			'delete' => &$delete
		));

		if ($delete) {
			//remove page from database
			Database::getInstance()->execute("DELETE FROM `{praefix}pages` WHERE `id` = :id; ", array('id' => $id));

			Cache::clear("pages");
		}
	}

	public static function get (string $alias) : Page {
		$page = new Page();
		$page->load($alias);

		return $page;
	}

	public static function setPageType (string $alias, string $page_type) {
		Events::throwEvent("set_pagetype", array(
			'alias' => &$alias,
			'page_type' => &$page_type
		));

		Database::getInstance()->execute("UPDATE `{praefix}pages` SET `page_type` = :page_type WHERE `alias` = :alias; ", array(
			'alias' => $alias,
			'page_type' => $page_type
		));

		Cache::clear("pages");
	}

	/**
	 * get id of page by alias
	 *
	 * only use this method for database upgrade, because their is no caching for this method!
	 *
	 * @param string $alias alias of page
	 *
	 * @throws IllegalStateException if alias doesnt exists
	 *
	 * @return int pageID
	 */
	public static function getPageIDByAlias (string $alias) : int {
		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}pages` WHERE `alias` = :alias; ", array('alias' => $alias));

		if (!$row) {
			throw new IllegalStateException("page with alias '" . $alias . "' doesnt exists.");
		}

		return $row['id'];
	}

	public static function lockPage (int $pageID, int $userID) {
		Database::getInstance()->execute("UPDATE `{praefix}pages` SET `locked_by` = :userID, `locked_timestamp` = CURRENT_TIMESTAMP WHERE `id` = :pageID; ", array(
			'userID' => $userID,
			'pageID' => $pageID
		));
	}

	public static function unlockPage (int $pageID) {
		Database::getInstance()->execute("UPDATE `{praefix}pages` SET `locked_by` WHERE `id` = :pageID; ", array(
			'pageID' => $pageID
		));
	}

	protected static function movePageToTrash (int $pageID) {
		Database::getInstance()->execute("UPDATE `{praefix}pages` SET `activated` = 2 WHERE `id` = :pageID; ", array(
			'pageID' => $pageID
		));

		//clear cache
		Cache::clear("pages", "pageID_" . $pageID);
	}

	protected static function restorePage (int $pageID) {
		Database::getInstance()->execute("UPDATE `{praefix}pages` SET `activated` = 1 WHERE `id` = :pageID; ", array(
			'pageID' => $pageID
		));

		//clear cache
		Cache::clear("pages", "pageID_" . $pageID);
	}

	public static function exists (string $alias) : bool {
		$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}pages` WHERE `alias` = :alias; ", array(
			'alias' => $alias
		));

		return $row !== false;
	}

}

?>
