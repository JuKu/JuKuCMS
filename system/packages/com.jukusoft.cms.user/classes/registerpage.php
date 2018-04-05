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
 * Date: 05.04.2018
 * Time: 17:18
 */

class RegisterPage extends PageType {

	public function getContent(): string {
		$template = new DwooTemplate("pages/register");

		$registration_enabled = Settings::get("registration_enabled", false);

		if (!$registration_enabled) {
			//registration is not enabled
			$template->assign("registration_enabled", false);
		} else {
			$template->assign("registration_enabled", true);

			//TODO: add code here
		}

		return $template->getCode();
	}

	public function listRequiredPermissions(): array {
		return array("not_logged_in");
	}

}

?>