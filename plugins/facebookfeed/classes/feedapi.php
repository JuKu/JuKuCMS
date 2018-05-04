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
 * Date: 04.05.2018
 * Time: 15:31
 */

namespace Plugin\FacebookFeed;

use Plugin\FacebookApi\FacebookApi;
use Preferences;

class FeedApi {

	public static function listFBFeeds () : array {
		$res = array();

		//load preferences
		$prefs = new Preferences("plugin_facebookfeed");

		//first check, if pageID is set
		if (empty($prefs->get("pageID", ""))) {
			return array(
				'error' => "No pageID was set in preferences (plugin settings)",
				"status" => 500
			);
		}

		//get pageID
		$pageID = $prefs->get("pageID", "");

		//create facebook api
		$fb_api = new FacebookApi();

		$fb_page = $fb_api->getPage($pageID);

		//https://developers.facebook.com/docs/graph-api/reference/v3.0/page/feed

		//https://stackoverflow.com/questions/28124078/get-latest-facebook-posts-of-page-with-php-sdk

		//https://gist.github.com/biojazzard/740551af0455c528f8a9

		//http://piotrpasich.com/facebook-fanpage-feed/

		print_r($fb_page->listFieldNames());

		return $res;
	}

}

?>
