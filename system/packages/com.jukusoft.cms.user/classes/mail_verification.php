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
 * Date: 06.04.2018
 * Time: 15:25
 */

class Mail_Verification {

	public static function sendMail (int $userID, string $username, string $mail) {
		//generate token
		$token = PHPUtils::randomString(64);

		Database::getInstance()->execute("INSERT INTO `{praefix}register_mail_verification` (
			`userID`, `token`
		) VALUES (
			:userID, :token
		) ON DUPLICATE KEY UPDATE `token` = :token; ", array(
			'userID' => $userID,
			'token' => $token
		));

		//send mail
		$template = new DwooTemplate(STORE_PATH . "templates/mail/verify_mail.tpl");

		//assign variables
		$template->assign("token", $token);
		$template->assign("userID", $userID);
		$template->assign("username", $username);
		$template->assign("verify_url", DomainUtils::generateURL("user/verify_mail", array('token' => $token)));
		$template->assign("base_url", DomainUtils::getBaseURL());
		$template->assign("mail", $mail);

		$message = $template->getCode();

		//send mail
		MailApi::sendHTMLMail($mail, "Mail Verification " . Settings::get("website_name", ""), $message);
	}

}

?>
