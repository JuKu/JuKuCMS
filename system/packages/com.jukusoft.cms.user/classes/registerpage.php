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
				'text_behind' => ""
			);

			$fields[] = array(
				'name' => "mail",
				'title' => "E-Mail",
				'type' => "email",
				'placeholder' => "john@example.com",
				'required' => true,
				'value' => (isset($_REQUEST['mail']) && !empty($_REQUEST['mail']) ? str_replace("\"", "", $_REQUEST['mail']) : ""),
				'custom_html' => false,
				'text_behind' => ""
			);

			$fields[] = array(
				'name' => "password",
				'title' => "Password",
				'type' => "password",
				'placeholder' => "Password",
				'required' => true,
				'value' => "",
				'custom_html' => false,
				'text_behind' => ""
			);

			$fields[] = array(
				'name' => "password_reply",
				'title' => "Reply password",
				'type' => "password",
				'placeholder' => "Password",
				'required' => true,
				'value' => "",
				'custom_html' => false,
				'text_behind' => ""
			);

			$fields[] = array(
				'name' => "agb",
				'title' => "Terms of use",
				'type' => "checkbox",
				'placeholder' => "",
				'required' => true,
				'value' => "",
				'custom_html' => false,
				'text_behind' => " I have read and agree with the <a href=\"" . DomainUtils::generateURL(Settings::get("agb_page", "agb")) . "\" target=\"_blank\">terms of use</a>"
			);

			Events::throwEvent("register_fields", array(
				'fields' => &$fields,
				'template'  => &$template
			));

			if (isset($_REQUEST['submit']) && !empty($_REQUEST['submit'])) {
				//TODO: check fields

				//TODO: check CSRF token
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
