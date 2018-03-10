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

class MySQLDriver implements DBDriver {

    protected $host = "localhost";
    protected $port = 3306;
    protected $username = "";
    protected $password = "";
    protected $praefix = "";
    protected $database = "";
    protected $options = array();

    protected $queries = 0;
    protected static $query_history = array();

    protected $conn = null;

    protected $prepared_cache = array();

    public function connect ($config_path) {
        if (file_exists($config_path)) {
            require($config_path);
        } else if (file_exists(CONFIG_PATH . $config_path)) {
            require(CONFIG_PATH . $config_path);
        } else {
            throw new ConfigurationException("Couldnt found database configuration file " . $config_path . ".");
        }

        //get mysql connection data from configuration
        $this->host = $mysql_settings['host'];
        $this->port = $mysql_settings['port'];
        $this->username = $mysql_settings['username'];
        $this->password = $mysql_settings['password'];
        $this->praefix = $mysql_settings['praefix'];
        $this->database = $mysql_settings['database'];
        $this->options = $mysql_settings['options'];

        try {
            //create new database instance
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->database . "", $this->username, $this->password, $this->options);
        } catch (PDOException $e) {
            echo "Couldnt connect to database!";
            echo $e->getTraceAsString();

            throw $e;
        }
    }

    public function update ($sql) {
        $this->execute($sql);
    }

    public function close () {
        $this->conn = null;
    }

    public function execute ($sql, $params = array()) {
        //dont allow SELECT statements
        if (strstr($sql, "SELECT")) {
            throw new IllegalArgumentException("method DBDriver::execute() isnt for select statements, its only for write statements, use getRow() or listRows() instead.");
        }

        //add query to history
        self::$query_history[] = array('query' => $sql, 'params' => $params);

        //increment query counter
        $this->queries++;

        //prepare mysql statement
        $stmt = $this->prepare($sql);

        //bind parameters
        foreach ($params as $key=>$value) {
            if (is_array($value)) {
                $stmt->bindValue(":" . $key, $value['value'], $value['type']);
            } else {
                $stmt->bindValue(":" . $key, $value, PDO::PARAM_STR);
            }
        }

        //execute query
        try {
            $res = $stmt->execute();

            if (!$res) {
                //TODO: throw exception instead
                print_r($stmt->errorInfo());

                if (DEBUG_MODE) {
                	echo "SQL Query: " + $sql;
				}

				ob_end_flush();
                exit;
            }

            return $res;
        } catch (PDOException $e) {
            echo "An Error oncurred. Please contact administrator.<br /><br /><small>If you are the administrator: You can enable DEBUG MODE in LIB_PATH/store/settings/settings.php .</small>";

            if (!defined("DEBUG_MODE") || !DEBUG_MODE) {
                exit;
            }

            echo "<br /><br /><b>Query</b>: " . $sql . ", parameters: ";
            var_dump($params);

            echo "<br /><br /><b>PDO Statement: </b>";
            print_r($stmt);

            //flush gzip cache
            ob_end_flush();

            exit;
        } catch (Exception $e) {
            echo "An Error oncurred. Please contact administrator.<br /><br /><small>If you are the administrator: You can enable DEBUG MODE in LIB_PATH/store/settings/settings.php .</small>";

            if (!defined("DEBUG_MODE") || !DEBUG_MODE) {
                exit;
            }

            echo "<br /><br /><b>Query</b>: " . $sql . ", parameters: ";
            var_dump($params);

            echo "<br /><br /><b>PDO Statement: </b>";
            print_r($stmt);

            //flush gzip cache
            ob_end_flush();

            exit;
        }
    }

    public function listAllDrivers () {
        return $this->conn->getAvailableDrivers();
    }

    public function quote ($str) : string {
        return $this->conn->quote($str);
    }

    protected function getQuery ($sql, bool $allow_information_schema = false) {
        /**
         * check, if sql query contains comments
         *
         * because many SQL Injections uses sql comments, we dont allow mysql comments here
         */
        if (strstr($sql, "--")) {
            throw new SecurityException("SQL comments arent allowed here! Please remove sql comments from query!");
        }

        /**
         * check, if LOAD_FILE was used to read out an file from local file system
         *
         * This command if often used by SQL Injections and because many mysql server runs with root privilegs,
         * it allows hackers to read out every file on the file system
         */
        if (strstr(strtoupper($sql), "LOAD_FILE")) {
            throw new SecurityException("SQL command LOAD_FILE isnt allowed here! Please remove sql command LOAD_FILE from query!");
        }

        /**
         * check, if INTO OUTFILE was used to write into an file of local file system
         *
         * This command if often used by SQL Injections and because many mysql server runs with root privilegs,
         * it allows hackers, for example, to select all database querys and save them in an public file on the webserver to download or he can write configuration files.
         */
        if (strstr(strtoupper($sql), "INTO OUTFILE")) {
            throw new SecurityException("SQL command INTO OUTFILE ist allowed here! Please remove sql command INTO OUTFILE from query!");
        }

        /**
         * check, if virtual database INFORMATION_SCHEMA is be used
         *
         * in virtual database INFORMATION_SCHEMA are many meta data of mysql stored, which an hacker can be use to hack the website / database faster
         */
        if (strstr(strtoupper($sql), "INFORMATION_SCHEMA") && !$allow_information_schema) {
            throw new SecurityException("SQL database INFORMATION_SCHEMA ist allowed here! Please remove sql database INFORMATION_SCHEMA from query!");
        }

        $sql = str_replace("{DBPRAEFIX}", $this->praefix, $sql);
        $sql = str_replace("{praefix}", $this->praefix, $sql);
		$sql = str_replace("{prefix}", $this->praefix, $sql);
		$sql = str_replace("{PREFIX}", $this->praefix, $sql);
        return str_replace("{PRAEFIX}", $this->praefix, $sql);
    }

    public function query($sql) : PDOStatement {
        //add query to history
        self::$query_history[] = array('query' => $sql, 'params' => array());

        //increment query counter
        $this->queries++;

        $res = $this->conn->query($this->getQuery($sql));

        if (!$res) {
            throw new PDOException("PDOException while query(): " . ($this->getErrorInfo())[3] . "");
        }

        return $res;
    }

    public function listTables() : array {
        $rows = $this->query("SHOW TABLES;")->fetchAll();

        return $rows;
    }

    public function getRow($sql, $params = array(), bool $allow_information_schema = false) {
        //get prepared statement
        $stmt = $this->prepare($sql, $allow_information_schema);

        foreach ($params as $key=>$value) {
            if (is_array($value)) {
                $stmt->bindValue(":" . $key, $value['value'], $value['type']);
            } else {
                $stmt->bindValue(":" . $key, $value, PDO::PARAM_STR);
            }
        }

        //add query to history
        self::$query_history[] = array('query' => $sql, 'params' => $params);

        //increment query counter
        $this->queries++;

        //execute query
        $res = $stmt->execute();

        if (!$res) {
            throw new PDOException("PDOException while getRow(): " . ($this->getErrorInfo())[3] . "\n" . ($stmt->errorInfo())[2] . "");
        }

        //fetch row
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listRows($sql, $params = array(), bool $allow_information_schema = false) : array {
        //get prepared statement
        $stmt = $this->prepare($sql, $allow_information_schema);

        //add query to history
        self::$query_history[] = array('query' => $sql, 'params' => $params);

        //increment query counter
        $this->queries++;

        foreach ($params as $key=>$value) {
            if (is_array($value)) {
                $stmt->bindValue(":" . $key, $value['value'], $value['type']);
            } else {
                $stmt->bindValue(":" . $key, $value, PDO::PARAM_STR);
            }
        }

        //execute query
        $res = $stmt->execute();

        if (!$res) {
            throw new PDOException("PDOException while listRows(): " . ($this->getErrorInfo())[3] . "\n" . ($stmt->errorInfo())[2] . "");
        }

        //fetch rows
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function escape(string $str) : string {
        return $this->quote($str);
    }

    public function countQueries() : int {
        return $this->queries;
    }

    public function beginTransaction () {
        $this->conn->beginTransaction();
    }

    public function rollback() {
        $this->conn->rollback();
    }

    public function commit() {
        $this->conn->commit();
    }

    public function prepare($sql, bool $allow_information_schema = false) : PDOStatement {
        $sql = $this->getQuery($sql, $allow_information_schema);

        if (isset($this->prepared_cache[md5($sql)])) {
            return $this->prepared_cache[md5($sql)];
        } else {
            $stmt = $this->conn->prepare($sql);

            if (!$stmt/* && defined('DEBUG_MODE') && DEBUG_MODE*/) {
                echo "\nPDO::errorInfo():\n";
                print_r($this->conn->errorInfo());

                exit;
            }

            //put prepared statement into cache
            $this->prepared_cache[md5($sql)] = $stmt;
            return $stmt;
        }
    }

	public function lastInsertId(): int {
		return $this->conn->lastInsertId();
	}

    public function listQueryHistory() : array {
        return self::$query_history;
    }

    public function getDatabaseName() : string {
        return $this->database;
    }

    public function getErrorInfo() : array {
        return $this->conn->errorInfo();
    }

}
