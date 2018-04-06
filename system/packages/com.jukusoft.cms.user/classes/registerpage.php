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

	public function getAdditionalHeaderCode(): string {
		//check, if captcha is enabled
		if (!Captcha::isEnabled()) {
			return "";
		}

		//get code between <head> and </head>
		return Captcha::getInstance()->getHeader();
	}

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
				'validator' => "Validator_Password",
				'hints' => "All characters are allowed, min length: 8, max length: 64"
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
				'validator' => "Validator_Password",
				'hints' => "All characters are allowed, min length: 8, max length: 64"
			);

			$fields[] = array(
				'name' => "agb",
				'title' => "Terms of use",
				'type' => "checkbox",
				'placeholder' => "",
				'required' => true,
				'value' => "checked",
				'custom_html' => false,
				'text_behind' => "<br />I have read and agree with the <a href=\"" . DomainUtils::generateURL(Settings::get("agb_page", "agb")) . "\" target=\"_blank\">terms of use</a>",
				'validator' => null
			);

			Events::throwEvent("register_fields", array(
				'fields' => &$fields,
				'template'  => &$template
			));

			//array with all validated values of fields
			$field_values = array();

			//add captcha field, if captcha enabled
			if (Captcha::isEnabled()) {
				$fields[] = array(
					'name' => "captcha",
					'title' => "Captcha",
					'type' => "",
					'placeholder' => "",
					'required' => true,
					'value' => "captcha",
					'custom_html' => "<input type=\"hidden\" name=\"captcha\" value=\"captcha\" />" . Captcha::getInstance()->getFormCode(),
					'validator' => null
				);
			}

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

						if (!is_null($validator) && !empty($validator)) {
							$obj = new $validator;

							if (!$obj->isValide($_POST[$field['name']])) {
								$validate = false;
								$error_msg_array[] = "Field '" . $field['title'] . "' is not valide! " . (isset($field['hints']) ? $field['hints'] : "");
							} else {
								//set validated value
								$field_values[$field['name']] = $obj->validate($_POST[$field['name']]);
							}
						}
					}
				}

				//check, if username already exists
				if (isset($_POST['username']) && !empty($_POST['username'])) {
					$username = $_POST['username'];

					if (User::existsUsername($username)) {
						$validate = false;
						$error_msg_array[] = "Username '" . htmlentities($_POST['username']) . "' already exists! Choose another username!";
					}
				}

				//check, if mail already exists
				if (isset($_POST['mail']) && !empty($_POST['mail'])) {
					$mail = $_POST['mail'];

					if (User::existsMail($mail)) {
						$validate = false;
						$error_msg_array[] = "Mail '" . htmlentities($_POST['mail']) . "' already exists in system! Maybe you are already registered? Choose another mail address or login!";
					}
				}

				//check, if passwords are equals
				if (isset($_POST['password']) && isset($_POST['password_repeat']) && !PHPUtils::strEqs($_POST['password'], $_POST['password_repeat'])) {
					$validate = false;
					$error_msg_array[] = "Repeated password isnt the same!";
				}

				//check, if agb is checked
				if (!isset($_POST['agb']) || $_POST['agb'] !== "checked") {
					$validate = false;
					$error_msg_array[] = "Please agree to AGB and fillout checkbox!";
				}

				//check captcha, if enabled
				if (Captcha::isEnabled()) {
					if (!Captcha::getInstance()->verify()) {
						$validate = false;
						$error_msg_array[] = "Wrong captcha!";
					}
				}

				Events::throwEvent("register_validate", array(
					'valide' => &$validate,
					'fields' => &$fields,
					'field_values' => &$field_values,
					'error_msg_array' => &$error_msg_array
				));

				if ($validate) {
					$text = "";

					//get activation method
					$activation_method = Settings::get("register_activation_method", "auto");

					$activated = 2;

					if ($activation_method === "auto") {
						$activated = 1;
					}

					//get fields
					$username = $field_values['username'];
					$password = $field_values['password'];
					$mail = $field_values['mail'];

					//get IP address of user
					$ip = PHPUtils::getClientIP();

					$main_group = 2;

					//create new user
					$res = User::create($username, $password, $mail, $ip, $main_group, "", $activated);

					if ($res === true || $res['success'] === true) {
						//throw event for custom registration fields
						Events::throwEvent("register_execute", array(
							'field_values' => $field_values,
							'text' => &$text
						));

						switch ($activation_method) {
							case "auto":
								//login user automatically
								User::current()->loginByID($res['userID']);

								//redirect user to home page
								header("Location: " . DomainUtils::generateURL(Domain::getCurrent()->getHomePage()));
								exit;

								break;

							case "mail_verification":
								//send verification mail
								Mail_Verification::sendMail($res['userID']);

								$text .= "Registration successful! Before you can login you have to verify your mail address! For this we have send you a mail with a link, please click on this link!";

								break;

							case "manual_verification":

								//TODO: inform administrator

								$text .= "Registration successful! An Administrator has to activate your account manually now.";

								break;

							default:
								throw new IllegalStateException("Unknown activation method: " . $activation_method);
								break;
						}

						$template->assign("error", !$validate);
						$template->assign("error_msg_array", $error_msg_array);
						$template->assign("success", $validate);
						$template->assign("additional_success_text", $text);
					} else {
						$template->assign("error", true);
						$template->assign("error_msg_array", array("Couldnt create user. Please contact the administrator of this website!"));
						$template->assign("success", false);
					}
				} else {
					$template->assign("error", !$validate);
					$template->assign("error_msg_array", $error_msg_array);
					$template->assign("success", $validate);
				}
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
