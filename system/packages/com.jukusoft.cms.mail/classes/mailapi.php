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
 * Time: 15:21
 */

class MailApi {

	public static function sendPlainMail ($to, string $subject, string $message, array $options = array(), string $from = "", string $reply_to = "") : bool {
		if (!Settings::get("send_mails_enabled", true)) {
			//send mails is not enabled
			return false;
		}

		return self::getClass()->sendPlainMail($to, $subject, $message, $options, $from, $reply_to);
	}

	public static function sendHTMLMail ($to, string $subject, string $message, array $options = array(), string $from = "", string $reply_to = "") : bool {
		if (!Settings::get("send_mails_enabled", true)) {
			//send mails is not enabled
			return false;
		}

		return self::getClass()->sendHTMLMail($to, $subject, $message, $options, $from, $reply_to);
	}

	protected static function getClass () : MailSender {
		//get setting
		$class_name = Settings::get("sendmail_method", "PHPMail");

		$obj = new $class_name();

		if (!($obj instanceof MailSender)) {
			throw new IllegalStateException("setting sendmail_method isnt a valide class which implements interface 'MailSender'.");
		}

		return $obj;
	}

	public static function getSignature () : string {
		$signature = Settings::get("mail_signature", "");

		Events::throwEvent("get_mail_signature", array(
			'signature' => &$signature
		));

		return $signature;
	}

}

?>
