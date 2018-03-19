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
 * Date: 19.03.2018
 * Time: 12:33
 */

class LoginPage extends PageType {

	public function getContent() : string {
		$show_form = true;

		$template = new Template("pages/login", Registry::singleton());

		if (isset($_REQUEST['action']) && $_REQUEST['action'] === "login") {
			//try to login

			$username_set = false;
			$password_set = false;

			if (isset($_POST['username']) && !empty($_POST['username'])) {
				$username_set = true;
			} else {
				$template->parse("main.no_username");
			}

			if (isset($_POST['password']) && !empty($_POST['password'])) {
				$password_set = true;
			} else {
				$template->parse("main.no_password");
			}

			if ($username_set && $password_set) {
				//try to login
				$user = User::current();
				$res = $user->loginByUsername($_REQUEST['username'], $_REQUEST['password']);

				if ($res['success'] === true) {
					//login successful, show redirect

					$template->parse("login_successful");

					$show_form = false;
				} else {
					if ($res['error'] === "user_not_exists") {
						$template->assign("ERROR_MSG", "Username doesnt exists!");
						$template->parse("error_msg");
					} else if ($res['error'] === "wrong_password") {
						$template->assign("ERROR_MSG", "Wrong password!");
						$template->parse("error_msg");
					} else {
						$template->assign("ERROR_MSG", "Unknown error message: " . $res['error']);
						$template->parse("error_msg");
					}
				}
			}
		}

		if ($show_form) {//show form
			$template->parse("main.form");
		}

		//get HTML code
		$template->parse();
		return $template->getCode();
	}

}

?>
