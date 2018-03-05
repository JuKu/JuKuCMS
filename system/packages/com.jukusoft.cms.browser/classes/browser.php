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
 * Date: 05.03.2018
 * Time: 01:57
 */

class Browser {

	/**
	 * check, if browser is mobile
	 *
	 * @return true, if browser is mobile
	 */
	public function isMobile () : bool {
		return preg_match("/(android|webos|avantgo|iphone|ipad|ipod|blackberry|iemobile|bolt|bo‌​ost|cricket|docomo|fone|hiptop|mini|opera mini|kitkat|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $this->getUserAgent());
	}

	public function isMobilePhone () : bool {
		//TODO: add code here
	}

	public function isTablet () : bool {
		//TODO: add code here
	}

	public function isAppleiOS () : bool {
		//TODO: add code here
	}

	public function isAndroid () : bool {
		//TODO: add code here
	}

	public function getUserAgent () : string {
		$user_agent = strtolower(htmlentities($_SERVER['HTTP_USER_AGENT']));

		//throw event, so plugins can modify user agent
		Events::throwEvent("get_user_agent", array(
			'user_agent' => &$user_agent,
		));

		return $user_agent;
	}

}

?>
