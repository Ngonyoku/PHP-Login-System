<?php

/**
 *  Written By @Ngonyoku
 *
 * PHP Version 7
 * MySql database
 *___________________________________________________________________________________________________________________
 *          DB Class
 * This Class Handles basic DB Operations i.e CRUD operations. Database Access Method employed is PDO (PHP Data
 * Objects)
 * ------------------------------------------------------------------------------------------------------------------
 *          PDO functions employed
 *  1. PDO::prepare() — Prepares a statement for execution and returns a statement object
 *  2. PDOStatement::execute() — Executes a prepared statement.
 *  3. PDOStatement::bindValue() — Binds a value to a parameter
 *  4. PDOStatement::fetchAll() — Returns an array containing all of the result set rows, An empty array is returned if
 *      there are zero results to fetch, or FALSE on failure.
 *  5. PDOStatement::rowCount() — Returns the number of rows affected by the last SQL statement
 *___________________________________________________________________________________________________________________
 * */
class DB
{
    private static $_instance = null;
    private $_pdo, $_query, $_error = 0, $_results, $_count = 0;

    private function __construct()
    {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') .
                ';dbname=' . Config::get('mysql/db'),
                Config::get('mysql/username'),
                Config::get('mysql/password'));
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance()
    {
        #We check if an Instance of this class has been set, if not we set the instance
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }

        return self::$_instance;
    }

    public function getPDO()
    {
        return $this->_pdo;
    }

    public function query($sql, $params = array())
    {
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) { #check if the sql statement is prepared for execution.
            $x = 1; #This will represent the position of an element in a array.
            if (count($params)) {
                foreach ($params as $param) { #Loop through the parameter array.
                    $this->_query->bindValue($x, $param); #we Bind the values of the array to the prepared statements.
                    $x++;
                }
            }
        }
        if ($this->_query->execute()) { #check if the prepared statement has been executed.
            $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
            $this->_count = $this->_query->rowCount();
        } else {
            $this->_error = true;
        }

        return $this;
    }

    public function action($sql, $table, $where = array())
    {
        if (count($where) === 3) { #We confirm that the array is has 3 values

            $operators = array('<', '>', '=', '<=', '>='); #We limit the type of Operators entered in the $where array
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];

            #Check if the operators entered in the $where array match either of the ones in the $operators array above
            if (in_array($operator, $operators)) {
                $sql = "{$sql} FROM {$table} WHERE {$field} {$operator} ?";

                if (!$this->query($sql, array($value))->error()) {
                    #if no error is returned by the query() method, we return
                    return $this;
                }
            }
        }

        return false;
    }

    public function insert($table, $fields = array())
    {
        $keys = array_keys($fields);
        $value = "";
        $x = 1;

        foreach ($fields as $field) {
            $value .= "?";
            if ($x < count($fields)) {
                $value .= ", ";
            }
            $x++;
        }

        $sql = "INSERT INTO {$table}(`" . implode('`, `', $keys) . "`) VALUES({$value})";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    public function update($table, $id, $fields = array())
    {
        $set = "";
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";
            if ($x < count($fields)) {
                $set .= ",";
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if (!$this->query($sql, $fields)->error()) {
            return true;
        }

        return false;
    }

    public function get($table, $fields)
    {
        return $this->action("SELECT * ", $table, $fields);
    }

    public function selectOne($item, $table, $fields)
    {
        return $this->action("SELECT {$item}", $table, $fields);
    }

    public function delete($table, $fields)
    {
        return $this->action("DELETE ", $table, $fields);
    }

    public function results()
    {
        return $this->_results;
    }

    public function first()
    {
        return $this->results()[0];
    }

    public function error()
    {
        return $this->_error;
    }

    public function count()
    {
        return $this->_count;
    }
}