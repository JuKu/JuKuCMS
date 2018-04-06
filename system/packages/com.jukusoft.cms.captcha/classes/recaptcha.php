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

		return "<div class=\"g-recaptcha\" data-sitekey=\"6LeYfVEUAAAAACEow7FNCXqPzmX7SfXoFrNP7xBH\"></div>";
	}

	public function verify(array $params = array()): bool {
		// TODO: Implement verify() method.
	}

}

?>
