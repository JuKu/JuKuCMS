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
 * Project: RocketCMS
 * License: Apache 2.0 license
 * User: Justin
 * Date: 23.08.2018
 * Time: 21:49
 */

abstract class Widget {

	protected $row = null;

	public function __construct() {
		//
	}

	public function load ($row) {
		if (!is_array($row)) {
			throw new IllegalArgumentException("row has to be an array (table row).");
		}

		$this->row = $row;
	}

	public function getId () : int {
		return $this->row['id'];
	}

	public function getTitle () : string {
		return $this->row['title'];
	}

	protected function getContent () : string {
		return $this->row['content'];
	}

	protected function getClassName () : string {
		return $this->row['class_name'];
	}

	protected function getWidgetParams () : array {
		return unserialize($this->row['widget_params']);
	}

	public function getCSSId () : string {
		return $this->row['css_id'];
	}

	public function getCSSClass () : string {
		return $this->row['css_class'];
	}

	public function getRow () : array {
		return $this->row;
	}

	public function useTemplate () {
		return true;
	}

	/**
	 * get html code which is shown on website
	 */
	public abstract function getCode ();

	/**
	 * get formular html code which is shown in admin area if user edits the widget
	 */
	public abstract function getAdminForm ();

	/**
	 * save widget data when new settings are saved in the admin area
	 */
	public abstract function save ();

	public static function castWidget (Widget $widget) : Widget {
		return $widget;
	}

}

?>
