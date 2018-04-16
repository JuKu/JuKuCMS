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
 * Date: 16.04.2018
 * Time: 22:12
 */

class Plugin_AdvancedPageTypes_AsciiDocPage extends PageType {

	public function getAdditionalHeaderCode(): string {
		$base_url = DomainUtils::getBaseURL() . "/";

		return "<!-- header javascript -->
    			<script language=\"javascript\" type=\"text/javascript\" src=\"" . $base_url . "plugins/advancedpagetypes/asciidoc/asciidoc/browser/asciidoctor.js\"></script>";
	}

	public function getContent(): string {
		$content = $this->getPage()->getContent();

		return "<div id=\"asciidocconverter\"></div>";
	}

	public function getFooterScripts(): string {
		return "<script>
					$(document).ready(function () {
					    var asciidoctor = Asciidoctor();
						var content = $" . "(\"#asciidocconverter\").html();
						var html = asciidoctor.convert(content);
						
						$" . "(\"#asciidocconverter\").html(content);
					});
				</script>";
	}

}

?>
