<?php 
/*
*-----------------------------------------------------------------------------------------------------------------------------------------------------------
* 	Code By @Ngonyoku
*----------------------------------------------------------------------------------------------------------------------------------------------------------
*/
class DB
{

	private static $instance = null;
	private $_error = 0, $_results, $_count = 0, $_query, $_pdo;

	private function __construct()
	{
		try {
			#Connect to our Mysql database
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
		} catch(PDOException $e) {
			die($e->getMessage());
		}
	}

	//The Method Returns an Instance Of this class
	public static function getInstance()
	{
		#Check if the instance is set, if not it will be set
		if (!isset(self::$instance)) {
			self::$instance = new DB();
		}
		return self::$instance;
	}

	//This method performs the query Operations in the Database
	public function query($sql, $params = array())
	{
		$this->_error = false;
		if ($this->_query = $this->_pdo->prepare($sql)) {
			$x = 1;
			if (count($params)) {
				foreach ($params as $param) {
					$this->_query->bindValue($x, $param);
					$x++;
			 	}
			}
		}
		if ($this->_query->execute()) {
			$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
			$this->_count = $this->_query->rowCount();
		} else {
			$this->_error = true;
		}
		return $this;
	}

	public function action($sql,$table, $where = array())
	{
		if (count($where) === 3) { #We confirm that the array is has 3 values

			$operators = array('<','>','=','<=','>='); #We limit the type of Operators enterd in the $where array
			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];

			#Check if the operators enterd in the $where array match either of the ones in the $operators array above
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

	//The Method Selects data from the Database
	public function getData($table, $where)
	{
		return $this->action("SELECT * ", $table, $where);
	}

	//The Method Deletes data from the Database
	public function deleteData($table, $where)
	{
		return $this->action("DELETE", $table, $where);
	}

	//The Method Inserts data into The Database
	public function insertData($table, $fields = array())
	{
		if (count($fields)) { #Check that the $field array is not empty
			$keys = array_keys($fields);
			$values = '';
			$x = 1;

			foreach ($fields as $field) {
				$values .= '?'; #We set Prepared statements as values
				if ($x < count($fields)) {
					$values .= ', '; #We separete the values added using the Comma
				}
				$x++;
			}

			$sql = "INSERT INTO {$table}(`".implode('`,`', $keys)."`) VALUES({$values})"; #We set the values enterd into our MySql query statement

			if (!$this->query($sql, $fields)->error()) {
				#If the Query does not return an error, we Return true
				return true;
			}
		}

		return false;
	}

	//The Method Updates a Records in the database
	public function updateData($table, $id, $fields = array())
	{
		$set = "";
		$x = 1;

		foreach ($fields as $name => $value) {
			$set .= "{$name} = ?"; #We add a prepared statement i.e(?) as a value in the $fields array
			if ($x < count($fields)) {
				$set .= ", "; #Separeate the values in the $fields array using a comma
			}
			$x++;
		}

		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}"; #We set the values enterd into our MySql query statement

		if (!$this->query($sql, $fields)->error()) {
			#If the Query does not return an error, we Return true
			return true;
		}

		return false;
	}

	//The method Returns erros
	public function error()
	{
		return $this->_error;
	}

	public function count() 
	{
		return $this->_count;
	}

	public function results() 
	{
		return $this->_results;
	}
}

 ?>