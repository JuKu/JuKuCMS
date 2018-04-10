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
 * Date: 11.04.2018
 * Time: 01:14
 */

class Plugin_AdvancedPageTypes_StaticHTMLPage extends PageType {

	public function getContent(): string {
		$file_path = $this->getPage()->getContent();

		//../ is not allowed
		$file_path = str_replace("..", "", $file_path);

		if (file_exists(STORE_PATH . $file_path)) {
			return file_get_contents(STORE_PATH . $file_path);
		} else {
			return "Error! template '" . $file_path . "' doesnt exists!";
		}
	}

}

?>
