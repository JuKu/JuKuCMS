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
 * Date: 20.03.2018
 * Time: 14:13
 */

class Error404Page extends HTMLPage {

	public function setCustomHeader () {
		//set error 404 not found header
		header("HTTP/1.0 404 Not Found");
	}

	public function getContent(): string {
		//first check, if specific template exists
		$current_style = Registry::singleton()->getSetting("current_style_name");
		if (file_exists(STYLE_PATH . $current_style . "/pages/error404.tpl")) {
			$template = new Template("pages/error404");

			$template->parse("main");
			return $template->getCode();
		} else {
			return parent::getContent();
		}
	}

}

?>
