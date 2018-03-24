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
 * Date: 23.03.2018
 * Time: 19:19
 */

class RobotsPage extends PageType {

	public function getContentType(): string {
		return "text/plain";
	}

	public function showDesign() {
		return false;
	}

	public function exitAfterOutput() {
		//dont add additional text (like HTML comments for benchmarks) at end of this content
		return true;
	}

	public function getContent(): string {
		//load robots.txt entries from database
		$robots = new Robots();
		$robots->loadFromDB();

		//get robots.txt content
		return $robots->getContent();
	}

}

?>