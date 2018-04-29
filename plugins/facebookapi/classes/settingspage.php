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
 * Date: 29.04.2018
 * Time: 21:43
 */

namespace Plugin\FacebookApi;

use PageType;
use DwooTemplate;
use Preferences;
use Security;
use Validator_Int;
use Validator_String;
use DomainUtils;
use Translator;

class SettingsPage extends PageType {

	public function getContent(): string {
		$template = new DwooTemplate("plugin_facebookapi_settings");

		$template->assign("form_action", DomainUtils::generateURL("admin/plugins/facebookapi"));

		//load preferences
		$prefs = new Preferences("plugin_facebookapi");

		if (isset($_REQUEST['submit'])) {
			//first check csrf token
			if (!Security::checkCSRFToken()) {
				$template->assign("error_message", "Wrong CSRF token!");
			} else {
				//check values
				if (!isset($_POST['appID']) || empty($_POST['appID'])) {
					$template->assign("error_message", "Please complete form! Field appID is missing!");
				} else if (!isset($_POST['secret_key']) || empty($_POST['secret_key'])) {
					$template->assign("error_message", "Please complete form! Field secret key is missing!");
				} else {
					$appID = $_POST['appID'];
					$secret_key = $_POST['secret_key'];

					//validate values
					$validator = new Validator_Int();
					$validator_string = new Validator_String();

					if (!$validator->isValide($appID)) {
						$template->assign("error_message", "appID is invalide!");
					} else if (!$validator_string->isValide($secret_key)) {
						$template->assign("error_message", "secret key is invalide!");
					} else {
						//save values
						$prefs->put("appID", $appID);
						$prefs->put("secret", $secret_key);
						$prefs->save();

						$template->assign("success_message", Translator::translate("appID & secret key saved successfully!"));
					}
				}
			}
		}

		$template->assign("appID", $prefs->get("appID", ""));
		$template->assign("secret_key", $prefs->get("secret", ""));

		return $template->getCode();
	}

}

?>
