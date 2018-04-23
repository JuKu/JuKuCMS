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
 * Date: 22.04.2018
 * Time: 13:19
 */

class ChangePasswordPage extends PageType {

	public function getContent() : string {
		$template = new DwooTemplate("pages/changepassword");

		$template->assign("form_action", DomainUtils::generateURL($this->getPage()->getAlias()));

		if (isset($_REQUEST['submit'])) {
			//first, check CSRF token
			if (!Security::checkCSRFToken()) {
				$template->assign("error_message", "Wrong CSRF token!");
			} else {
				if (isset($_POST['old_password']) && !empty($_POST['old_password']) && isset($_POST['new_password']) && !empty($_POST['new_password']) && isset($_POST['retry_password']) && !empty($_POST['retry_password'])) {
					$old_passowrd = $_POST['old_password'];
					$new_password = $_POST['new_password'];
					$retry_password = $_POST['retry_password'];

					//first, check old password
					if (!User::current()->checkPassword($old_passowrd)) {
						$template->assign("error_message", "Wrong old password!");
					} else if (!PHPUtils::strEqs($new_password, $retry_password)) {
						$template->assign("error_message", "New and retried passwords are not equals!");
					} else {
						//create new instance of validator
						$validator = new Validator_Password();

						//check, if password is valide
						if (!$validator->isValide($new_password)) {
							$min_length = Settings::get("password_min_length", 6);
							$max_length = Settings::get("password_max_length", 64);

							$template->assign("error_message", "New password is not valide! Min length: " . $min_length . ", max length: " . $max_length . " .");
						} else {
							//validate password
							//$new_password = $validator->validate($new_password);

							User::current()->setPassword($new_password);

							$template->assign("form_submit", true);
							$template->assign("success_message", "Password changed successfully!");
						}
					}
				} else {
					$template->assign("error_message", "Please complete form!");
				}
			}
		}

		return $template->getCode();
	}

}

?>
