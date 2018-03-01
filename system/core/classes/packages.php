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
 * Created by PhpStorm.
 * User: Justin
 * Date: 02.03.2018
 * Time: 00:17
 */

class Packages {

    public static function listPackages (bool $installed_required = true) : array {

        if (!file_exists(ROOT_PATH . "store/package_list.php")) {
            return array();
        }

        require(ROOT_PATH . "store/pluginlist.php");

        $packages = array();

        foreach ($package_list as $plugin=>$installed) {
            if ($installed || !$installed_required) {
                $packages[] = $plugin;
            }
        }

        return $packages;

    }

}

?>
