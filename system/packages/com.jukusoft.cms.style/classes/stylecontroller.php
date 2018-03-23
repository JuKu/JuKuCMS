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

class StyleController {

	/**
	 * get name of current style
	 *
	 * @param $registry Registry instance
	 *
	 * @return current style as string name
	 */
	public static function getCurrentStyle (Registry &$registry, Page &$page, PageType &$page_type) : string {
		//get default styles
		$default_style_name = Settings::get("default_style_name");
		$default_mobile_style_name = Settings::get("default_mobile_style_name");

		$style_name = !Browser::isMobile() ? $default_style_name : $default_mobile_style_name;

		//apply style rules
		$style_name = StyleRules::getStyle($registry, $style_name);

		//throw event, so plugins can change style
		Events::throwEvent("get_style", array(
			'default_style_name' => $default_style_name,
			'default_mobile_style_name' => $default_mobile_style_name,
			'style_name' => &$style_name,
			'registry' => $registry
		));

		return $style_name;
	}

	public static function showPage (Registry &$registry, Page &$page, PageType &$page_type) {
		//create new template
		$template = new Template("index");

		$title_preafix = Settings::get("title_praefix", "");
		$title_suffix = Settings::get("title_suffix", "");

		//assign variables
		$template->assign("TITLE", $title_preafix . $page->getTitle() . $title_suffix);
		$template->assign("RAW_TITLE", $page->getTitle());
		$template->assign("REGISTRY", $registry->listSettings());

		$template->assign("CONTENT", $page_type->getContent());
		$template->assign("FOOTER", $registry->getSetting("footer", ""));
		$template->assign("COPYRIGHT", Settings::get("copyright", "<strong>Copyright &copy; 2018 <a href=\"http://jukusoft.com\">JuKuSoft.com</a></strong>, All Rights Reserved."));

		$template->assign("MY_GROUP_IDS", implode(",", $registry->getObject("groups")->listGroupIDs()));
		$template->assign("CHARSET", $page_type->getCharset());

		//create new css builder
		$css_builder = new CSSBuilder();

		//create new js builder
		$js_builder = new JSBuilder();

		$current_style = $registry->getSetting("current_style_name");
		$template->assign("CSS_HASH_ALL", $css_builder->getHash($current_style, "ALL"));
		$template->assign("JS_HASH_ALL_HEADER", $js_builder->getHash($current_style, "ALL", "header"));
		$template->assign("JS_HASH_ALL_FOOTER", $js_builder->getHash($current_style, "ALL", "footer"));

		//set version and build number
		$template->assign("VERSION", Version::current()->getVersion());
		$template->assign("BUILD", Version::current()->getBuildNumber());

		//userid and username
		$user = User::current();
		/*$template->assign("USERID", $user->getID());
		$template->assign("USERNAME", $user->getUsername());*/
		$template->assign("IS_LOGGED_IN", $user->isLoggedIn());

		//assign menu code
		$globalMenu = $registry->getObject("main_menu");
		$localMenu = $registry->getObject("local_menu");
		$template->assign("MENU", $globalMenu->getCode());
		$template->assign("LOCALMENU", $localMenu->getCode());

		if (User::current()->isLoggedIn()) {
			$template->parse("main.logged_in");
		} else {
			$template->parse("main.not_logged_in");
		}

		//throw event
		Events::throwEvent("show_page", array(
			'registry' => &$registry,
			'page' => &$page,
			'page_type' => &$page_type,
			'template' => &$template
		));

		$template->parse();

		echo $template->getCode();
	}

}

?>
