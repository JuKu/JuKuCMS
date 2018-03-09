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
	public static function getCurrentStyle (Registry $registry) : string {
		//get default styles
		$default_style_name = Settings::get("default_style_name");
		$default_mobile_style_name = Settings::get("default_style_name");

		$style_name = !Browser::isMobile() ? $default_style_name : $default_mobile_style_name;

		//TODO: apply style rules

		return $style_name;
	}

}

?>
