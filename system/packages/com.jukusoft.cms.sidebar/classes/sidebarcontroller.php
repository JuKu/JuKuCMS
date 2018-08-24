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


/**
 * Project: JuKuCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 24.08.2018
 * Time: 14:05
 */

class SidebarController {

	protected $left_sidebar_id = -1;
	protected $right_sidebar_id = -1;

	protected $left_sidebar = null;
	protected $right_sidebar = null;

	public function __construct(Page $page) {
		$this->left_sidebar_id = $page->getLeftSidebarID();
		$this->right_sidebar_id = $page->getRightSidebarID();

		if ($this->left_sidebar_id == -1) {
			//get global default left sidebar
			$this->left_sidebar_id = (int) Settings::get("default_sidebar_left", 1);
		}

		if ($this->right_sidebar_id == -1) {
			//get global default right sidebar
			$this->right_sidebar_id = (int) Settings::get("default_sidebar_right", 2);
		}

		//initialize left sidebar
		$this->left_sidebar = new Sidebar();
		$this->left_sidebar->load($this->left_sidebar_id);

		//initalize right sidebar
		$this->right_sidebar = new Sidebar();
		$this->right_sidebar->load($this->right_sidebar_id);
	}

	public function getLeftSidebar () : Sidebar {
		return $this->left_sidebar;
	}

	public function getRightSidebar () : Sidebar {
		return $this->right_sidebar;
	}

}

?>
