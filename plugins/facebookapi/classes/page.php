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
 * Date: 30.04.2018
 * Time: 01:14
 */

namespace Plugin\FacebookApi;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

class Page {

	protected $fb = null;
	protected $api = null;

	protected $pageID = "";
	protected $response = null;
	protected $graphNode = null;

	public function __construct (Facebook $fb, FacebookApi $api) {
		$this->fb = $fb;
		$this->api = $api;
	}

	public function loadPage (string $name) {
		$this->pageID = $name;

		//https://stackoverflow.com/questions/7633234/get-public-page-statuses-using-facebook-graph-api-without-access-token

		//get all available fields: /{page-id}?metadata=1

		try {
			// Returns a `Facebook\FacebookResponse` object
			$this->response = $this->fb->get(
				"/" . $this->pageID . "?fields=id,about,fan_count,website,location,name,username,phone,feed",
				$this->api->getAccessToken()
			);
		} catch(FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		$this->graphNode = $this->response->getGraphNode();
	}

}

?>
