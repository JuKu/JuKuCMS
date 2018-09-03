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
 * Project: RocketCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 03.09.2018
 * Time: 14:36
 */

class PageEditPage extends PageType {

	public function getContent(): string {
		$template = new DwooTemplate("pages/editpage");

		//TODO: first check permissions

		return $template->getCode();
	}

	public function getFooterScripts(): string {
		return "";
	}

	public function listRequiredPermissions(): array {
		return array("can_edit_all_pages", "can_edit_own_pages");
	}

}

?>
