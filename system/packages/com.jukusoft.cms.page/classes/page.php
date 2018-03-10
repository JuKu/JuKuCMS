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

	//instance of pagetype
	protected $instance = null;

	public function __construct() {
		//
	}

	public function load ($alias = null) {
		if ($alias == null) {
			if (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) {
				$alias = Database::getInstance()->escape($_REQUEST['page']);
			} else {
				$alias = $this->getDomain()->getHomePage();
			}
		} else {
			$alias = Database::getInstance()->escape($alias);
		}

		Events::throwEvent("get_alias", array(
			'alias' => &$alias,
			'page' => &$this,
			'domain' => &$this->getDomain()
		));

		$this->alias = $alias;

		if (Cache::contains("pages", "page_" . $alias)) {
			$this->row = Cache::get("pages", "page_" . $alias);
		} else {
			$row = Database::getInstance()->getRow("SELECT * FROM `{praefix}pages` WHERE `alias` = :alias, `activated` = '1'; ", array('alias' => $alias));

			if (!$row) {
				if (strcmp("error404", $alias)) {
					throw new IllegalStateException("No page with alias 'error404' exists.");
				}

				//page not found
				$new_alias = "error404";

				Events::throwEvent("load_page_error404", array(
					'alias' => &$new_alias,
					'original_alias' => $alias,
					'page' => &$this,
					'domain' => &$this->getDomain()
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

		//create instance of page type
		$this->instance = PageType::loadInstance($this->pagetype);

		//set row
		$this->instance->setPageRow($this->row);
	}

	protected function getDomain () : Domain {
		return Registry::singleton()->getObject("domain");
	}

	public function reloadCache () {
		Cache::clear("pages");
	}

	public function getAlias () : string {
		return $this->alias;
	}

	public function getPageTypeInstance () : PageType {
		return $this->instance;
	}

}

?>
