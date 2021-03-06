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
 * Implementation for captcha based on googles recaptcha
 *
 * @link https://developers.google.com/recaptcha/intro
 * @link https://github.com/google/recaptcha/blob/1.0.0/php/recaptchalib.php
 */

class ReCaptcha implements ICaptcha {

	public function getHeader(): string {
		return "<script src='https://www.google.com/recaptcha/api.js'></script>";
	}

	public function getFormCode(): string {
		//get website key
		$website_key = Settings::get("recaptcha_website_key", "");

		if (empty($website_key)) {
			throw new IllegalStateException("reCaptcha wasnt configured right. Administrators: generate a website & secret key by google recaptcha and set them in settings to fix this issue!");
		}

		return "<div class=\"g-recaptcha\" data-sitekey=\"" . $website_key . "\"></div>";
	}

	public function verify(array $params = array()): bool {
		$secret_key = Settings::get("recaptcha_secret_key", "");

		if (empty($secret_key)) {
			throw new IllegalStateException("Cannot verify recaptcha, because no secret key was set, <a href=\"https://www.google.com/recaptcha/admin\">generate a website & secret key by recaptcha</a> and set them in global settings.");
		}

		if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
			//no recaptcha response was set
			return false;
		}

		$g_recaptcha_response = $_POST['g-recaptcha-response'];

		$params = array(
			'secret' => $secret_key,
			'response' => $g_recaptcha_response//'remoteip' => "" Optional. The user's IP address.
		);

		//send POST request
		$result = PHPUtils::sendPOSTRequest("https://www.google.com/recaptcha/api/siteverify", $params);

		if (!$result) {
			//coulnd send POST request
			throw new IllegalStateException("Couldnt send POST request to verify recaptcha");
		}

		$json = json_decode($result, true);

		if (isset($json['success']) && $json['success'] == true) {
			//validation successfully
			return true;
		} else {
			//for error-message see https://developers.google.com/recaptcha/docs/verify

			return false;
		}
	}

}

?>
