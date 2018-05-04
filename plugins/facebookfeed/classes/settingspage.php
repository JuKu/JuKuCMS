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
 * Date: 30.04.2018
 * Time: 02:10
 */

namespace Plugin\FacebookFeed;

use PageType;
use DwooTemplate;
use Preferences;

class SettingsPage extends PageType {

	public function getContent(): string {
		$template = new DwooTemplate("plugin_facebookfeed_settings");

		$template->assign("form_action", DomainUtils::generateURL($this->getPage()->getAlias()));

		$prefs = new Preferences("plugin_facebookfeed");

		if (isset($_REQUEST['submit'])) {
			//first check csrf token
			if (!Security::checkCSRFToken()) {
				$template->assign("error_message", "Wrong CSRF token!");
			} else {
				//check values
				if (!isset($_POST['pageID']) || empty($_POST['pageID'])) {
					$template->assign("error_message", "Please complete form! Field pageID is missing!");
				} else {
					$pageID = $_POST['pageID'];

					//validate values
					$validator_string = new Validator_String();

					if (!$validator_string->isValide($pageID)) {
						$template->assign("error_message", "pageID is invalide!");
					} else {
						//save values
						$prefs->put("pageID", $pageID);
						$prefs->save();

						$template->assign("success_message", Translator::translate("pageID saved successfully!"));
					}
				}
			}
		}

		$template->assign("pageID", $prefs->get("pageID", ""));

		return $template->getCode();
	}

}

?>
