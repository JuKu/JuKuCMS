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
 * Database Driver Interface
 */

interface DBDriver {

    //http://www.peterkropff.de/site/mysql/advanced_mysql.htm

    //http://www.peterkropff.de/site/php/pdo.htm

    public function connect ($config_path);

    public function update ($sql, $params = array());

    public function execute ($sql, $params = array());

    public function quote ($str) : string;

    public function query ($sql) : PDOStatement;

    public function listTables () : array;

    public function getRow ($sql, $params = array(), bool $allow_information_schema = false);

    public function listRows ($sql, $params = array(), bool $allow_information_schema = false) : array;

    public function escape (string $str) : string;

    public function countQueries () : int;

    public function beginTransaction ();

    public function rollback ();

    public function commit ();

    public function prepare ($sql, bool $allow_information_schema = false) : PDOStatement;

    public function lastInsertId () : int;

    public function listQueryHistory () : array;

    public function getDatabaseName () : string;

    public function getErrorInfo () : array;

    public function close ();

}

?>
