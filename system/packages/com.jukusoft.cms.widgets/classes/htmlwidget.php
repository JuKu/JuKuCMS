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
 * Date: 23.08.2018
 * Time: 23:37
 */

class HTMLWidget extends Widget {

	/**
	 * get html code which is shown on website
	 */
	public function getCode() {
		return $this->getContent();
	}

	/**
	 * get formular html code which is shown in admin area if user edits the widget
	 */
	public function getAdminForm() {
		// TODO: Implement getAdminForm() method.
	}

	/**
	 * save widget data when new settings are saved in the admin area
	 */
	public function save() {
		// TODO: Implement save() method.
	}

	public function useTemplate() {
		return false;
	}

	public static function create (int $sidebar_id, string $title, string $content, string $unique_name = "") {
		if (empty($unique_name)) {
			$unique_name = md5($title . "_" . $sidebar_id . "_" . time());
		}

		Database::getInstance()->execute("INSERT INTO `{praefix}sidebar_widgets` (
			`id`, `sidebar_id`, `title`, `content`, `class_name`, `widget_params`, `css_id`, `css_class`, `before_widget`, `after_widget`, `unique_name`, `order`
		) VALUES (
			NULL, :sidebar_id, :title, :content, 'HTMLWidget', '', :widget_params, '', '', '', '', :unique_name, 10
		) ON DUPLICATE KEY UPDATE `title` = :title, `content` = :content, `class_name` = 'HTMLWidget', `widget_params` = :widget_params, `unique_name` = :unique_name; ", array(
			'sidebar_id' => $sidebar_id,
			'title' => $title,
			'content' => $content,
			'widget_params' => serialize(array()),
			'unique_name' => $unique_name
		));

		Cache::clear("sidebars");
	}

}

?>
