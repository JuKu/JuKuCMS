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
 * Date: 04.04.2018
 * Time: 15:29
 */

class PHPInfoPage extends PageType {

	public function getContent(): string {
		if (isset($_REQUEST['no_design'])) {
			phpinfo();
			exit;
		} else {
			/*$content = "";

			ob_start();
			phpinfo();
			$content = ob_get_contents();
			ob_get_clean();

			return $content;*/

			//show iframe
			return " <iframe src=\"" . DomainUtils::generateURL("admin/phpinfo") . "?no_design=true\" style=\"width: 100%; min-height: 400px; \"></iframe> ";
		}
	}

	public function setCustomHeader() {
		//allow iframe
		header('X-Frame-Options: SAMEORIGIN');
	}

	public function showDesign() {
		return !isset($_REQUEST['no_design']);
	}

	public function listRequiredPermissions(): array {
		return array("can_see_phpinfo");
	}

}

?>
