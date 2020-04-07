<?php

/*  *  Written By @Ngonyoku
    *___________________________________________________________________________________________________________________\
    * This Class is Used to Perform Basic form validation Operations.
    * __________________________________________________________________________________________________________________
    *      Regular Expressions
    *  1.  "/^[a-zA-Z\s\.\d]+$/" - Only lowercase, uppercase, whitespaces, numbers and period(.)
    *  2.  "/^[a-zA-Z\d\._]+@[a-zA-Z\d\._]+\.[a-zA-Z\d\.]{2,}+$/" - Email Address
    *  3.  "/^(\+254|0)\d{9}$/" - Kenyan Phone Number
    *  4.  "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i" - Website
    *  5.  "/^[a-zA-Z ]*$/" - letters and whitespace
    *___________________________________________________________________________________________________________________
 * */

class Validation
{
    private $_passed = false, $_db = null;
    public $_error = array();

    //The Constructor creates an Instance of the database.
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }

//    public function validate($source, $items = array())
//    {
//        foreach ($items as $item => $rules) {
//            foreach ($rules as $rule => $rule_value) {
//
//                $value = trim($source[$item]);
//
//                if ($rule === 'required' && empty($value)) {
//                    $this->addError(" {$item} Cannot Be Empty! ");
//                } elseif (!empty($value)) {
//                    switch ($item) {
//                        case 'min':
//                            if (strlen($value) < $rule_value) {
//                                $this->addError("{$item} must be a minimum of {$rule_value} characters");
//                            }
//                            break;
//                        case 'max':
//                            if (strlen($value) > $rule_value) {
//                                $this->addError("{$item} shouldn't exceed {$rule_value} characters");
//                            }
//                            break;
//                        case 'matches':
//                            if ($value != $source[$rule_value]) {
//                                $this->addError("{$value} Must Match {$item}");
//                            }
//                            break;
//                        case 'unique':
//                            $check = $this->_db->get($rule_value, array($item, '=', $value));
//                            if ($check->count()) {
//                                $this->addError("{$item} Exists!");
//                            }
//                    }
//                }
//            }
//        }
//
//        if (empty($this->_error)) {
//            $this->_passed = true;
//        }
//
//        return $this;
//    }

    //The method is Used to Sanitize and Validate Email Addresses.
    public function validEmail($email)
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (empty($email)) {
            $this->addError("emailError", " Email is Required ");
        } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->addError("emailError", " Invalid Email Address ");
        } elseif (empty($this->_error)) {
            return $this->_passed = true;
        }
        return $this;
    }

    //This method is used to validate any Kind of input Entered On to the Form By use of RegEx
    public function validate($data, $pattern = "/^[a-zA-Z\s\.\d]+$/", $min = null, $max = null)
    {
        $data = trim($data);
        if (empty($data)) {
            $this->addError("dataError", " Password is Required and should not Contain White Spaces");
        } elseif ($min != null || $max != null) {
            if (strlen($data) < $min || strlen($data) > $max) {
                $this->addError("dataError", "Password must be at least {$min} Characters and at most {$max} Characters");
            }
        } elseif (!preg_match($pattern, $data)) {
            $this->addError("dataError", " Only letters and Numbers are required! ");
        } elseif (empty($this->_error)) {
            return $this->_passed = true;
        }
        return $this;
    }

    //The method stores an array that were pronounced during validation of data
    private function addError($errorName, $error)
    {
        return $this->_error[$errorName] = $error;
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