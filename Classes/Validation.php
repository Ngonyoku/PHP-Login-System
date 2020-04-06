<?php


class Validation
{
    private $_passed = false, $_db = null;
    public $_error = array();

    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

    public function validate($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {

                $value = trim($source[$item]);

                if ($rule === 'required' && empty($value)) {
                    $this->addError(" {$item} Cannot Be Empty! ");
                } elseif (!empty($value)) {
                    switch ($item) {
                        case 'min':
                            #Ensures characters don't go below the min length
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$item} Must have atleast {$rule_value} characters");
                            }
                            break;
                        case 'max':
                            #Ensures Max length is not surpassed
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} Must not Exceed {$rule_value} characters");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError("{$value} Must Match {$item}");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if ($check->count()) {
                                $this->addError("{$item} Exists!");
                            }
                    }
                }
            }
        }

        if (empty($this->_error)) {
            $this->_passed = true;
        }

        return $this;
    }

    public function addError($error)
    {
        return $this->_error[] = $error;
    }

    public function error()
    {
        return $this->_error;
    }

    public function passed()
    {
        return $this->_passed;
    }
}