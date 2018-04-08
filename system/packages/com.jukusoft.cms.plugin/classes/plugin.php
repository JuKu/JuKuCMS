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
 * Date: 08.04.2018
 * Time: 12:31
 */

class Plugin {

	//directory name of plugin
	protected $name = "";

	//database row
	protected $row = array();

	protected $json_data = null;

	protected static $allowed_types = array("library", "metaplugin", "project");

	/**
	 * default constructor
	 *
	 * @param string $name directory name of plugin
	 * @param array $row optional database row from plugin
	 */
	public function __construct(string $name, array $row = array()) {
		$this->name = $name;
		$this->row = $row;
	}

	/**
	 * load plugin.json file
	 */
	public function load () {
		$file_path = PLUGIN_PATH . $this->name . "/plugin.json";

		//check, if file exists
		if (!file_exists($file_path)) {
			throw new IllegalStateException("plugin.json for plugin '" . $this->name . "' does not exists (expected path: '" . $file_path . "')!");
		}

		$this->json_data = json_decode(file_get_contents($file_path), true);
	}

	/**
	 * get directory name of plugin
	 *
	 * @return string directory name of plugin
	 */
	public function getName () : string {
		return $this->name;
	}

	public function getPath () : string {
		return PLUGIN_PATH . $this->name . "/";
	}

	public function getType () : string {
		$type = $this->json_data['type'];

		if (!in_array($type, self::$allowed_types)) {
			throw new IllegalStateException("plugin type '" . $type . "' (plugin '" . $this->name . "') is not supported!");
		}

		return $type;
	}

	public function getTitle () : string {
		return htmlentities($this->json_data['title']);
	}

	public function getDescription (string $lang_token = "") : string {
		$desc = $this->json_data['description'];

		if (is_array($desc)) {
			//several languages are supported
			if (empty($lang_token) || !isset($desc[$lang_token])) {
				//return english description
				return htmlentities($desc['en']);
			} else {
				return htmlentities($desc[$lang_token]);
			}
		} else {
			//use default language
			return htmlentities($desc);
		}
	}

	public function getVersion () : string {
		return $this->json_data['version'];
	}

	public function getInstalledVersion () : string {
		return (!empty($this->row) ? $this->row['version'] : "n/a");
	}

	public function getHomepage () : string {
		return (isset($this->json_data['homepage']) ? $this->json_data['homepage'] : "");
	}

	public function getLicense () : string {
		return $this->json_data['license'];
	}

	public function listAuthors () : array {
		return $this->json_data['authors'];
	}

	public function listSupportArray () : array {
		return $this->json_data['support'];
	}

	public function hasSourceLink () : bool {
		return isset($this->json_data['support']) && isset($this->json_data['support']['source']);
	}

	public function getSourceLink () : string {
		if ($this->hasSourceLink()) {
			return $this->json_data['support']['source'];
		} else {
			return "";
		}
	}

	public function hasIssuesLink () : bool {
		return isset($this->json_data['support']) && isset($this->json_data['support']['issues']);
	}

	public function getIssuesLink () : string {
		if ($this->hasIssuesLink()) {
			return $this->json_data['support']['issues'];
		} else {
			return "";
		}
	}

	public function hasSupportMail () : bool {
		return isset($this->json_data['support']) && isset($this->json_data['support']['email']);
	}

	public function getSupportMail () : string {
		if ($this->hasSupportMail()) {
			return $this->json_data['support']['email'];
		} else {
			return "";
		}
	}

	public function listSupportLinks () : array {
		$array = array();

		if ($this->hasIssuesLink()) {
			$array[] = array(
				'title' => Translator::translate("Issues"),
				'href' => $this->getIssuesLink()
			);
		}

		if ($this->hasSourceLink()) {
			$array[] = array(
				'title' => Translator::translate("Source"),
				'href' => $this->getSourceLink()
			);
		}

		if ($this->hasSupportMail()) {
			$array[] = array(
				'title' => Translator::translate("Mail"),
				'href' => "mailto:" . $this->getSupportMail(),
			);
		}

		return $array;
	}

	public function listKeywords () : array {
		return $this->json_data['keywords'];
	}

	public function listCategories () : array {
		return $this->json_data['categories'];
	}

	public function hasInstallJson () : bool {
		return isset($this->json_data['install']);
	}

	public function getInstallJsonFile () : string {
		return $this->json_data['install'];
	}

	public function getRequiredPlugins () : array {
		return $this->json_data['require'];
	}

	public function isAlpha () : bool {
		return PHPUtils::endsWith($this->getVersion(), "-alpha");
	}

	public function isBeta () : bool {
		return PHPUtils::endsWith($this->getVersion(), "-beta");
	}

	public function isInstalled () : bool {
		return (!empty($this->row) ? $this->row['installed'] == 1 : false);
	}

	public function isActivated () : bool {
		return (!empty($this->row) ? $this->row['activated'] == 1 : false);
	}

	public static function castPlugin (Plugin $plugin) : Plugin {
		return $plugin;
	}

}

?>
