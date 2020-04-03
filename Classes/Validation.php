<?php 
/*
*-----------------------------------------------------------------------------------------------------------------------
* 	Code By @Ngonyoku
*-----------------------------------------------------------------------------------------------------------------------
*/
class Validation
{
	private $_passed = false, $_db = null;
	public $_errors = array();

	public function __construct()
	{
		#returns an Instance of the Database
		$this->_db = DB::getInstance();
	}

	//The Function Validates Input
	public function check($source, $items = array())
	{
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_values) {

				$values = trim($source[$item]);

				if ($rule === 'required' && empty($values)) {
					$this->addError("{$item} Cannot Be Empty <br>");
				} else if (!empty($values)) {
					switch ($rule) {
						case 'min':
							#Ensures characters don't go below the min length
							if (strlen($values) < $rule_values) {
								$this->addError("{$item} Must have atleast {$rule_values} characters");
							}
							break;
						case 'max':
							#Ensures Max length is not surpassed
							if (strlen($values) > $rule_values) {
								$this->addError("{$item} Must not Exceed {$rule_values} characters");
							}
							break;
						case 'matches':
							#Ensures 2 sets of data match
							if ($values != $source[$rule_values]) {
								$this->addError("{$item} Don\'t Match with {$values}");
							}
							break;
						case 'unique':
							#Username Must be Unique in the db
							$checkExistence = $this->_db->getData($rule_values, array($item,'=',$values));
							if ($checkExistence->count()) {
								$this->addError("{$item} is not Available");
							}
							break;
					}
				}
			}
		}

		if (empty($this->_errors)) {
			$this->_passed = true;
		}

		return $this;
	}

	//The method stores Errors Encoutred into the array
	private function addError($error)
	{
		$this->_errors[] = $error;
	}

	public function errors()
	{
		return $this->_errors;
	}

	public function passed()
	{
		return $this->_passed;
	}
}
?>