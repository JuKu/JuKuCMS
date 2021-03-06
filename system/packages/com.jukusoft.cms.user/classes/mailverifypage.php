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
 * Date: 06.04.2018
 * Time: 17:08
 */

class MailVerifyPage extends PageType {

	public function getContent(): string {
		$template = new DwooTemplate("pages/verifymail");

		if (isset($_REQUEST['token']) && !empty($_REQUEST['token'])) {
			$template->assign("no_token", false);

			//check token
			if (!Mail_Verification::checkToken($_REQUEST['token'])) {
				$template->assign("invalide_token", true);
			} else {
				$template->assign("invalide_token", false);
			}
		} else {
			$template->assign("no_token", true);
		}

		return $template->getCode();
	}

}

?>
