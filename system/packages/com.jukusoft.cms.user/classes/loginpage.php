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
		$show_form = !User::current()->isLoggedIn();

		$template = new Template("pages/login", Registry::singleton());

		if (isset($_REQUEST['action']) && $_REQUEST['action'] === "login") {
			//try to login

			$username_set = false;
			$mail_set = false;
			$password_set = false;

			if (isset($_POST['username']) && !empty($_POST['username'])) {
				$username_set = true;
			}

			if (isset($_POST['mail']) && !empty($_POST['mail'])) {
				$mail_set = true;
			}

			if (isset($_POST['password']) && !empty($_POST['password'])) {
				$password_set = true;
			}

			if (!$username_set && !$mail_set && !$password_set) {
				//form was not submitted
			} else {
				if (!$username_set && !$mail_set) {
					$template->parse("main.no_username");
					$template->parse("main.no_mail");
				}

				if (!$password_set) {
					$template->parse("main.no_password");
				}
			}

			if (($username_set || $mail_set) && $password_set) {
				//check CSRF token
				if (Security::checkCSRFToken()) {
					//check, if user is already logged in
					if (User::current()->isLoggedIn()) {
						$template->assign("ERROR_TEXT", "User is already logged in!");
						$template->parse("main.error_msg");

						//dont show form, because user is already logged in
						$show_form = false;
					} else {
						//try to login
						$user = User::current();

						if ($username_set) {
							$res = $user->loginByUsername($_REQUEST['username'], $_REQUEST['password']);
						} else {
							$res = $user->loginByMail($_REQUEST['mail'], $_REQUEST['password']);
						}

						if ($res['success'] === true) {
							//login successful, show redirect

							if (isset($_REQUEST['redirect_url']) && !empty($_REQUEST['redirect_url'])) {
								//TODO: check for security issues, maybe we should check if redirect_url is a known domain

								header("Location: " . urldecode($_REQUEST['redirect_url']));

								//flush gzip buffer
								ob_end_flush();

								exit;
							} else {
								//redirect to index page

								//get domain
								$domain = Registry::singleton()->getObject("domain");

								//generate index url
								$index_url = DomainUtils::generateURL($domain->getHomePage());

								header("Location: " . $index_url);

								//flush gzip buffer
								ob_end_flush();

								exit;
							}

							$template->parse("login_successful");

							Events::throwEvent("page_login_successful");

							$show_form = false;
						} else {
							if ($res['error'] === "user_not_exists") {
								$template->assign("ERROR_TEXT", "Username doesnt exists!");
								$template->parse("main.error_msg");
							} else if ($res['error'] === "wrong_password") {
								$template->assign("ERROR_TEXT", "Wrong password!");
								$template->parse("main.error_msg");
							} else if ($res['error'] === "mail_not_valide") {
								$template->assign("ERROR_TEXT", "Mail is not valide!");
								$template->parse("main.error_msg");
							} else {
								$template->assign("ERROR_TEXT", "Unknown error message: " . $res['error']);
								$template->parse("main.error_msg");
							}
						}
					}
				} else {
					$template->assign("ERROR_TEXT", "Wrong CSRF token! Please try to login again!");
					$template->parse("main.error_msg");
				}
			}
		}

		if ($show_form) {//show form
			$template->parse("main.form");
		} else if (User::current()->isLoggedIn()) {
			$template->assign("USERID", User::current()->getID());
			$template->assign("USERNAME", User::current()->getUsername());

			$template->parse("main.already_logged_in");
		}

		//get HTML code
		$template->parse();
		return $template->getCode();
	}

}

?>
