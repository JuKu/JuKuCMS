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

	protected $alias = null;
	protected $row = null;
	protected $pagetype = "";

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
			$alias = Database::getInstance()->escape($alias);
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

			echo "Alias: " . $alias . ", $" . "_REQUEST['page']: " . $_REQUEST['page'];
			var_dump($row);

			if (!$row) {
				if (!PHPUtils::strEqs("error404", $alias)) {
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

	public function getContent () : string {
		return $this->row['content'];
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

	public function activate (bool $bool = true) {
		$this->row['activated'] = $bool;
	}

	/**
	 * save changes into database
	 */
	public function save () {
		//TODO: add code here
	}

	public static function createIfAbsent (string $alias, string $title, string $page_type, string $content = "", string $folder = "/", int $globalMenu = -1, int $localMenu = -1, int $parentID = -1, bool $sitemap = true, bool $published = true, bool $editable = true, string $author = "system") : int {
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

		Database::getInstance()->execute("INSERT INTO `{praefix}pages` (
			`id`, `alias`, `title`, `content`, `parent`, `folder`, `global_menu`, `local_menu`, `page_type`, `design`, `sitemap`, `published`, `version`, `last_update`, `created`, `editable`, `author`, `activated`
		) VALUES (
			NULL, :alias, :title, :content, :parent, :folder, :globalMenu, :localMenu, :pageType, 'none', :sitemap, :published, '1', '0000-00-00 00:00:00', CURRENT_TIMESTAMP, :editable, :author, '1'
		) ON DUPLICATE KEY UPDATE `alias` = :alias; ", array(
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

		return $insertID;
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

}

?>
