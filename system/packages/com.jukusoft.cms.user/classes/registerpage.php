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
			$template->assign("action_url", DomainUtils::generateURL($this->getPage()->getAlias()));

			$fields = array();

			//add fields
			$fields[] = array(
				'name' => "username",
				'title' => "Username",
				'type' => "text",
				'placeholder' => "Username",
				'required' => true,
				'value' => (isset($_REQUEST['username']) && !empty($_REQUEST['username']) ? str_replace("\"", "", $_REQUEST['username']) : ""),
				'custom_html' => false,
				'text_behind',
				'text_behind' => "",
				'validator' => "Validator_Username"
			);

			$fields[] = array(
				'name' => "mail",
				'title' => "E-Mail",
				'type' => "email",
				'placeholder' => "john@example.com",
				'required' => true,
				'value' => (isset($_REQUEST['mail']) && !empty($_REQUEST['mail']) ? str_replace("\"", "", $_REQUEST['mail']) : ""),
				'custom_html' => false,
				'text_behind' => "",
				'validator' => "Validator_Mail"
			);

			$fields[] = array(
				'name' => "password",
				'title' => "Password",
				'type' => "password",
				'placeholder' => "Password",
				'required' => true,
				'value' => "",
				'custom_html' => false,
				'text_behind' => "",
				'validator' => "Validator_Password"
			);

			$fields[] = array(
				'name' => "password_repeat",
				'title' => "Repeat password",
				'type' => "password",
				'placeholder' => "Password",
				'required' => true,
				'value' => "",
				'custom_html' => false,
				'text_behind' => "",
				'validator' => "Validator_Password"
			);

			$fields[] = array(
				'name' => "agb",
				'title' => "Terms of use",
				'type' => "checkbox",
				'placeholder' => "",
				'required' => true,
				'value' => "",
				'custom_html' => false,
				'text_behind' => "<br />I have read and agree with the <a href=\"" . DomainUtils::generateURL(Settings::get("agb_page", "agb")) . "\" target=\"_blank\">terms of use</a>"
			);

			Events::throwEvent("register_fields", array(
				'fields' => &$fields,
				'template'  => &$template
			));

			if (isset($_REQUEST['submit']) && !empty($_REQUEST['submit'])) {
				$validate = true;
				$error_msg_array = array();

				//check CSRF token
				if (!Security::checkCSRFToken()) {
					$validate = false;
					$error_msg_array[] = "Wrong CSRF token!";
				}

				//check fields
				foreach ($fields as $field) {
					//check, if field is required
					if ($field['required']) {
						if (!isset($_POST[$field['name']]) || empty($_POST[$field['name']])) {
							$validate = false;
							$error_msg_array[] = "Field '" . $field['title'] . "' wasnt filled!";
						}
					}

					//validate fields
					if (isset($_POST[$field['name']])) {
						$validator = $field['validator'];
						$obj = new $validator;

						if (!$obj->isValide($_POST[$field['name']])) {
							$validate = false;
							$error_msg_array[] = "Field '" . $field['title'] . "' is not valide!";
						}
					}
				}

				//check, if username already exists
				if (isset($_POST[$field['username']]) && !empty($_POST[$field['username']])) {
					$username = $_POST['username'];

					if (User::existsUsername($username)) {
						$validate = false;
						$error_msg_array[] = "Username '" . htmlentities($_POST['username']) . "' already exists! Choose another username!";
					}
				}

				//check, if mail already exists
				if (isset($_POST[$field['mail']]) && !empty($_POST[$field['mail']])) {
					$mail = $_POST['mail'];

					if (User::existsMail($mail)) {
						$validate = false;
						$error_msg_array[] = "Mail '" . htmlentities($_POST['username']) . "' already exists in system! Maybe you are already registered? Choose another mail address or login!";
					}
				}

				$template->assign("error", !$validate);
				$template->assign("error_msg_array", $error_msg_array);
			} else {
				$template->assign("error", false);
			}

			$template->assign("fields", $fields);

			//TODO: add code here
		}

		return $template->getCode();
	}

	public function listRequiredPermissions(): array {
		return array("not_logged_in");
	}

}

?>
