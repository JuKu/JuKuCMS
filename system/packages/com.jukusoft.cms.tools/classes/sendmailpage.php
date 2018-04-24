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
 * Date: 24.04.2018
 * Time: 21:25
 */

class SendMailPage extends PageType {

	public function getAdditionalHeaderCode(): string {
		$base_url = DomainUtils::getBaseURL() . "/";

		return "<!-- iCheck -->
  				<link rel=\"stylesheet\" href=\"" . $base_url . "styles/admin/plugins/iCheck/flat/blue.css\">
		
				<!-- bootstrap wysihtml5 - text editor -->
  				<link rel=\"stylesheet\" href=\"" . $base_url . "styles/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css\">";
	}

	public function getFooterScripts(): string {
		$base_url = DomainUtils::getBaseURL() . "/";

		return "<!-- iCheck -->
				<script src=\"" . $base_url . "styles/admin/plugins/iCheck/icheck.min.js\"></script>
				<!-- Bootstrap WYSIHTML5 -->
				<script src=\"" . $base_url . "styles/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js\"></script>
				<!-- Page Script -->
				<script>
				  $(function () {
					//Add text editor
					$(\"#compose-textarea\").wysihtml5();
				  });
				</script>";
	}

	public function getContent(): string {
		$template = new DwooTemplate("pages/sendmail");

		$template->assign("form_action", DomainUtils::generateURL("pages/sendmail"));
		$template->assign("content", "");

		if (isset($_REQUEST['submit'])) {
			//first, check csrf token
			if (!Security::checkCSRFToken()) {
				$template->assign("error_message", "Wrong CSRF token!");

				if (isset($_POST['content'])) {
					$template->assign("content", $_POST['content']);
				}
			} else {
				$required_fields = array("to_mail", "subject", "content");

				foreach ($required_fields as $field) {
					if (!isset($_POST[$field]) || empty($_POST['field'])) {
						$template->assign("error_message", "Please complete form!");

						if (isset($_POST['content'])) {
							$template->assign("content", $_POST['content']);
						}

						return $template->getCode();
					}
				}

				//form is complete
				$to_mail = $_POST['to_mail'];
				$subject = $_POST['subject'];
				$content = $_POST['content'];

				//check, if mail is valide
				if (!(new Validator_Mail())->isValide($to_mail)) {
					$template->assign("error_message", "Mail is not valide!");

					if (isset($_POST['content'])) {
						$template->assign("content", $_POST['content']);
					}
				} else if (!(new Validator_String())->isValide($subject)) {
					$template->assign("error_message", "Subject is not valide!");

					if (isset($_POST['content'])) {
						$template->assign("content", $_POST['content']);
					}
				} else {
					//parameters are valide, send mail

					if (MailApi::sendHTMLMail($to_mail, $subject, $content)) {
						$template->assign("success_message", "Mail sended successfully!");
					} else {
						$template->assign("error_message", "Sending of mail failed!");

						if (isset($_POST['content'])) {
							$template->assign("content", $_POST['content']);
						}
					}
				}
			}
		}

		return $template->getCode();
	}

}

?>
