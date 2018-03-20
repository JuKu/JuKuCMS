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
		$template->assign("COPYRIGHT", Settings::get("copyright", "&copy; 2018 JuKuSoft.com, All Rights Reserved."));

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
			$template->parse("main.logged-in");
		}

		$template->parse();

		echo $template->getCode();
	}

}

?>
