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
 * Date: 14.07.2016
 * Time: 17:51
 */
class PostGreSQLDriver extends MySQLDriver {

    public function connect ($config_path) {
        if (file_exists($config_path)) {
            require($config_path);
        } else if (file_exists(CONFIG_PATH . $config_path)) {
            require(CONFIG_PATH . $config_path);
        } else {
            throw new ConfigurationException("Couldnt found postgresql database configuration file " . $config_path . ".");
        }

        //get mysql connection data from configuration
        $this->host = $pqsql_settings['host'];
        $this->port = pqsql_settings['port'];
        $this->username = $pqsql_settings['username'];
        $this->password = $pqsql_settings['password'];
        $this->praefix = $pqsql_settings['praefix'];
        $this->database = $pqsql_settings['database'];
        $this->options = $pqsql_settings['options'];

        try {
            //create new database instance
            $this->conn = new PDO("pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->database . "", $this->username, $this->password, $this->options);
        } catch (PDOException $e) {
            echo "Couldnt connect to database!";
            echo $e->getTraceAsString();

            throw $e;
        }
    }

}