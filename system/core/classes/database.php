<?php

/**
 * Database class
 */
class Database {

    /**
     * array with database instances
     */
    protected static $instances = array();

    protected static $db_settings = null;

    public static function &getInstance ($name = "") : DBDriver {
        if ($name == "") {
            $name = "default";
        }

        if (!isset(self::$instances[$name])) {
            //create new database connection
            self::createInstance($name);
        }

        return self::$instances[$name];
    }

    public static function createInstance ($name = "default") {
        if ($name == "default") {
            //get database configuration
            require(CONFIG_PATH . "database.php");

            //save configuration
            self::$db_settings = $database;

            //get database driver and config
            $db_class_name = self::$db_settings['driver'];
            $db_config_path = self::$db_settings['config'];

            //create new database class instance from string
            $instance1 = new $db_class_name();

            //check, if instance is an database driver
            if (!$instance1 instanceof DBDriver) {
                throw new BadMethodCallException("Cannot initialize database driver " . $db_class_name . ", driver doesnt implements interface DBDriver.");
            }

            //save instance
            self::$instances[$name] = $instance1;

            //connect to database
            self::$instances[$name]->connect($db_config_path);
        } else {
            //check, if configuration file exists
            if (file_exists(CONFIG_PATH . "db_" . $name . ".php")) {
                require(CONFIG_PATH . "db_" . $name . ".php");

                //get database driver and config
                $db_class_name = $database['driver'];
                $db_config_path = $database['config'];

                //create new database class instance from string
                $db_instance = new $db_class_name();

                //check, if instance is an database driver
                if (!$db_instance instanceof DBDriver) {
                    throw new BadMethodCallException("Cannot initialize database driver " . $db_class_name . ", driver doesnt implements interface DBDriver.");
                }

                //save instance
                self::$instances[$name] = $db_instance;

                //connect to database
                self::$instances[$name]->connect($db_config_path);
            } else {
                //Logger::error("Couldnt find database configuration file for database '" . $name . "'.");
				echo "Couldnt find database configuration file for database '" . $name . "'.";
            }
        }
    }

}
