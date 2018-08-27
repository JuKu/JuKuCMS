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
 * Date: 05.04.2018
 * Time: 15:13
 */

class PHPMail implements MailSender {

	public function sendPlainMail($to, string $subject, string $message, array $options = array(), string $from = "", string $reply_to = ""): bool {
		//http://php.net/manual/de/function.mail.php

		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "\r\n");

		//throw event, so plugins can interact
		Events::throwEvent("send_plain_mail_php", array(
			'to' => &$to,
			'subject' => &$subject,
			'message' => &$message,
			'options' => &$options,
			'from' => &$from,
			'reply_to' => &$reply_to
		));

		$options = self::getOptions($options, $from, $reply_to);
		$to = self::convertToArray($to);
		$headers = self::generateHeader($options, $to);

		//add signature at end of message
		$message .= MailApi::getSignature();

		//send mail
		return mail($to, $subject, $message, $headers);
	}

	public function sendHTMLMail($to, string $subject, string $message, array $options = array(), string $from = "", string $reply_to = ""): bool {
		// In case any of our lines are larger than 70 characters, we should use wordwrap()
		$message = wordwrap($message, 70, "<br />");

		//throw event, so plugins can interact
		Events::throwEvent("send_html_mail_php", array(
			'to' => &$to,
			'subject' => &$subject,
			'message' => &$message,
			'options' => &$options,
			'from' => &$from,
			'reply_to' => &$reply_to
		));

		$options = $this->getOptions($options, $from, $reply_to);
		$to = self::convertToArray($to);
		$headers = self::generateHeader($options, $to, true);

		//add signature at end of message
		$message .= MailApi::getSignature();

		//send mail
		return mail($to, $subject, $message, $headers);
	}

	public static function getOptions (array $options = array(), string $from = "", string $reply_to = "") : array {
		if (!is_null($from) && !empty($from)) {
			$options['from'] = $from;
		}

		if (!is_null($reply_to) && !empty($reply_to)) {
			$options['reply_to'] = $reply_to;
		}

		//set from address
		if (!isset($options['from']) || empty($options['from'])) {
			//get from address from settings
			$from_mail = Settings::get("mail_sender_address", "none");
			$from_name = Settings::get("mail_sender_name", "");

			if ($from_mail !== "none") {
				$options['from'] = (!empty($from_name) ? $from_name . " " : "") . $from_mail;
			}
		}

		if (!isset($options['reply_to']) || empty($options['reply_to'])) {
			$reply_to = Settings::get("mail_reply_to", "");

			if (!empty($reply_to)) {
				$options['reply_to'] = $reply_to;
			}
		}

		return $options;
	}

	public static function convertToArray ($to) : string {
		if (is_array($to)) {
			//convert array to RFC 2822 string
			$to = implode(", ", $to);
		}

		return $to;
	}

	public static function generateHeader (array $options, string $to, bool $html_mail = false) {
		//generate header
		$headers = array();

		if ($html_mail) {
			$charset = Settings::get("mail_default_charset", "urf-8");

			//add mime version, content type and charset
			$headers[] = "MIME-Version: 1.0";
			$headers[] = "Content-type: text/html; charset=" . $charset;

			$headers[] = "To: " . $to;
		}

		$headers[] = "Content-Transfer-Encoding: quoted-printable";

		if (isset($options['from']) && !empty($options['from'])) {
			$headers[] = "From: " . $options['from'];
		}

		if (isset($options['reply_to']) && !empty($options['reply_to'])) {
			$headers[] = "Reply-To: " . $options['reply_to'];
		}

		if (isset($options['cc'])) {
			if (is_array($options['cc'])) {
				//convert array to string
				$options['cc'] = implode(", ", $options['cc']);
			}

			$headers[] = "Cc: " . $options['cc'];
		}

		if (isset($options['bcc'])) {
			if (is_array($options['bcc'])) {
				//convert array to string
				$options['bcc'] = implode(", ", $options['bcc']);
			}

			$headers[] = "Bcc: " . $options['bcc'];
		}

		//add php X-Mailer header
		$headers[] = "X-Mailer: PHP/" . phpversion();

		if (count($headers) == 0) {
			$headers = null;
		} else {
			//convert array to string
			$headers = implode("\r\n", $headers);
		}

		return $headers;
	}

}

?>
