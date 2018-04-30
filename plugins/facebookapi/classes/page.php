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
use Facebook\GraphNodes\GraphPage;
use Facebook\GraphNodes\GraphNode;

class Page {

	protected $fb = null;
	protected $api = null;

	protected $pageID = "";

	protected $response = null;
	protected $graphNode = null;
	protected $page = null;

	public function __construct (FacebookApi $api) {
		$this->fb = $api->getSDK();
		$this->api = $api;
	}

	public function loadPage (string $name) {
		$this->pageID = $name;

		//https://stackoverflow.com/questions/7633234/get-public-page-statuses-using-facebook-graph-api-without-access-token

		//get all available fields: /{page-id}?metadata=1

		try {
			// Returns a `Facebook\FacebookResponse` object
			$this->response = $this->fb->get(
				"/" . $this->pageID . "?fields=id,about,fan_count,website,location,name,username,phone,feed.limit(10){child_attachments,application,actions,caption,description,expanded_height,created_time,coordinates,comments_mirroring_domain,backdated_time,event,from,feed_targeting,full_picture,id,is_expired,is_hidden,height,is_popular,is_published,message,message_tags,likes,link,picture,properties,scheduled_publish_time,object_id,type,privacy,name,place},band_members,best_page,band_interests",
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
		$this->page = $this->response->getGraphPage();
	}

	public function listFieldNames () : array {
		return $this->graphNode->getFieldNames();
	}

	public function getGraphNode () : GraphNode {
		return $this->graphNode;
	}

	public function getGraphPage () : GraphPage {
		return $this->page;
	}

	public function getID () : int {
		return $this->getGraphNode()->getField("id");
	}

	public function getName () : string {
		return $this->getGraphPage()->getName();
	}

	public function getUsername () : string {
		return $this->getGraphNode()->getField("username");
	}

	public function countLikes () : int {
		return $this->getGraphPage()->getField("fan_count");
	}

	public function getAbout () : string {
		return $this->getGraphNode()->getField("about");
	}

	public function getWebsite () : string {
		return $this->getGraphNode()->getField("website");
	}

	public function getAllItems () : array {
		return $this->getGraphNode()->all();
	}

}

?>
